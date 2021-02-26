<?php
Yii::import('libs.crypt.crypt');
Yii::import('libs.NaPacks.Logo');
Yii::import('libs.NaPacks.Settings');
Yii::import('libs.NaPacks.Notifi');
Yii::import('libs.NaPacks.Push');
Yii::import('libs.NaPacks.SaveModels');
Yii::import('libs.NaPacks.Save');
Yii::import('libs.NaPacks.WebApp');
Yii::import('libs.Utils.Utils');


use Minishlink\WebPush\WebPush;
use Minishlink\WebPush\Subscription;


class RulesEngineController extends Controller
{
  public function init()
	{
    // change this constant to true in PRODUCTION
    define('PRODUCTION',true);
  }

	/**
	 * @var string the default layout for the views. Defaults to '//layouts/column2', meaning
	 * using two-column layout. See 'protected/views/layouts/column2.php'.
	 */
	public $layout='//layouts/column1';

	/**
	 * @return array action filters
	 */
	public function filters()
	{
		return array(
			'accessControl', // perform access control for CRUD operations
			'postOnly + delete', // we only allow deletion via POST request
		);
	}

	/**
	 * Specifies the access control rules.
	 * This method is used by the 'accessControl' filter.
	 * @return array access control rules
	 */
	public function accessRules()
	{
		return array(
			array('allow',  // allow all users to perform 'index' and 'view' actions
				'actions'=>array(
          'saveRequest', // receive POST from shopping-cart plugin and SAVE it
        ),
				'users'=>array('*'),
			),
			array('deny',  // deny all users
				'users'=>array('*'),
			),
		);
	}


	/**
	 * receive POST from shopping-cart plugin and SAVE it
	 * @param POST
	 */
	public function actionSaveRequest()
	{
    $save = new Save;
    if (!PRODUCTION) $save->WriteLog('dashboard','RulesEngine','save','Start Plugin log.');

		// Questa opzione abilita i wrapper URL per fopen (file_get_contents), in modo da potere accedere ad oggetti URL come file
		ini_set("allow_url_fopen", true);

		$raw_post_data = file_get_contents('php://input');
		if (false === $raw_post_data) $save->WriteLog('dashboard','RulesEngine','send','Could not read from the php://input stream or invalid IPN received.',true);
		else if (!PRODUCTION) $save->WriteLog('dashboard','RulesEngine','save','php://input stream is valid.');

		if (false === $_POST) $save->WriteLog('dashboard','RulesEngine','save','Could not read from the $_POST stream or invalid IPN received.',true);
		else if (!PRODUCTION) $save->WriteLog('dashboard','RulesEngine','save','$_POST stream is valid.');

    if (!PRODUCTION) $save->WriteLog('dashboard','RulesEngine','save','Received _POST is:<pre>'.print_r($_POST,true).'</pre>');

    // VERIFICO CHE NEL POST CI SIA L'EVENT
    if (!isset($_POST['event'])) $save->WriteLog('dashboard','RulesEngine','save','$_POST event is not valid.',true);
		else if (!PRODUCTION) $save->WriteLog('dashboard','RulesEngine','save','$_POST event is valid.');

    // VERIFICO CHE I DATI INVIATI SIANO CORRETTI
    Yii::import('ext.APIKeys');
    $ipn = (object) APIKeys::check();

		if (true === empty($ipn)) $save->WriteLog('dashboard','RulesEngine','save','Could not decode the JSON payload from Server.',true);
		else if (!PRODUCTION) $save->WriteLog('dashboard','RulesEngine','save','Json payload and api keys are valid.');

    $payload = (object) $ipn->event;
    if (!PRODUCTION) $save->WriteLog('dashboard','RulesEngine','save','Payload is: <pre>'.print_r($payload,true).'</pre>');

		if (true === empty($payload->id)) $save->WriteLog('dashboard','RulesEngine','save','Invalid Server payment notification message received - did not receive invoice ID.',true);
		else if (!PRODUCTION) $save->WriteLog('dashboard','RulesEngine','save','Ipn id is valid.');

    // CARICO le impostazioni
    $settings=Settings::load();
		if($settings===null) $save->WriteLog('dashboard','RulesEngine','save','The requested Settings does not exist.',true);

    // Load client data
    $client = WalletsBolt::model()->findByAttributes(['id_user'=>$payload->customer_id]);
    if($client===null) $save->WriteLog('dashboard','RulesEngine','save','The requested Client Wallet does not exist.',true);

    if (!PRODUCTION) $save->WriteLog('dashboard','RulesEngine','save','Client wallet address is: '.$client->wallet_address);

    // aggiungo il wallet address
    $payload->client_address = $client->wallet_address;
    $identifier = explode(":",$payload->id);

    if (!(isset($identifier[1]))) $payload->cart_id = $payload->id;
    else $payload->cart_id = $identifier[1];

    // generate the new payload
    $ipn->event = $payload;
    if (!PRODUCTION) $save->WriteLog('dashboard','RulesEngine','save','New Payload to Rules Engine Server is: <pre>'.print_r($ipn,true).'</pre>');

    // SAVE THE PAYLOAD
    // save in a new table
    // then call commandController

    // -- Struttura della tabella `re_requests`
    //
    // CREATE TABLE `re_requests` (
    //   `id_request` int(11) NOT NULL,
    //   `id_merchant` int(11) NOT NULL,
    //   `payload` text NOT NULL
    // ) ENGINE=InnoDB DEFAULT CHARSET=utf8;
    //
    // -- Indici per le tabelle `re_requests`
    // ALTER TABLE `re_requests` ADD PRIMARY KEY (`id_request`);
    //
    // -- AUTO_INCREMENT per la tabella `re_requests`
    // ALTER TABLE `re_requests` MODIFY `id_request` int(11) NOT NULL AUTO_INCREMENT;
    // ALTER TABLE `re_requests` ADD `sent` INT(1) NOT NULL AFTER `payload`;
    //
    // -- AGGIUNGI IL CAMPO timestamp
    // ALTER TABLE `re_requests` ADD `timestamp` INT(11) NOT NULL AFTER `id_request`;

    $model = new RuleEngineRequests;
    $model->timestamp = time();
    $model->id_merchant = $payload->merchant_id;
    $model->payload = json_encode($ipn);
    $model->sent = 0; // NON INVIATO
    $model->save();

    //eseguo lo script che si occuperà in background di verificare lo stato dell'evento appena creata...
    $cmd = Yii::app()->basePath.DIRECTORY_SEPARATOR.'yiic request --id='.crypt::Encrypt($model->id_request);
    $ssh = Seclib::execInBackground($cmd);

    // if there is an error...
    if (is_array($ssh) && isset($ssh['error'])){
      $save->WriteLog('dashboard','RulesEngine','save','SSH response is: '.$ssh['error']);

      //INVIO UN MESSAGGIO DI NOTIFICA
      $notification = array(
        'type_notification' => 'RulesEngine',
        'id_user' => $payload->merchant_id,
        'id_tocheck' => $model->id_request,
        'status' => 0,
        'description' => Yii::t('notify','An error occurred!: '.$ssh['error']),
        'url' => Yii::app()->createUrl('rulesenginerequests/view',array('id'=>crypt::Encrypt($model->id_request))),
        'timestamp' => time(),
        'price' => 0,
        'deleted' => 0,
      );
      Push::Send($save->Notification($notification,true),'RulesEngine');
    }

    // Now, we can exit and respond with HTTP 200
    $json = array(
      'success'=> 1,
      'id' => crypt::Encrypt($model->id_request),
      'message'=>'Payload saved.',
      'payload'=>$model->payload, // ovviamente questo può essere tolto. Serve solo a dimostrare che il plugin riceve il payuload giusto.
    );

    if (!PRODUCTION) $save->WriteLog('dashboard','RulesEngine','save','json response is: <pre>'.print_r($json,true).'</pre>');

    // Rispondi con il json
    header('Content-type:application/json;charset=utf-8');
    echo CJSON::encode($json);

    $save->WriteLog('dashboard','RulesEngine','save','Request id <b>'.crypt::Encrypt($model->id_request).'</b> from shopping cart id <b>'.$payload->id.'</b> saved.');
	}


}
