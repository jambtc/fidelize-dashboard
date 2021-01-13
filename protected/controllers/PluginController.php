<?php
Yii::import('libs.crypt.crypt');
Yii::import('libs.NaPacks.Logo');
Yii::import('libs.NaPacks.Settings');
Yii::import('libs.NaPacks.Notifi');
Yii::import('libs.NaPacks.Push');
Yii::import('libs.NaPacks.SaveModels');
Yii::import('libs.NaPacks.Save');
Yii::import('libs.NaPacks.WebApp');


use Minishlink\WebPush\WebPush;
use Minishlink\WebPush\Subscription;


class IpnController extends Controller
{
  public function init()
	{
    // change this variable to true if in PRODUCTION
    const PRODUCTION = false;
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
          'saverequest', // receive POST from shopping-cart plugin and SAVE it
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
	public function actionSaverequest()
	{
    $save = new Save;
    $save->WriteLog('dashboard','plugin','save','Start Plugin log.');

		// Questa opzione abilita i wrapper URL per fopen (file_get_contents), in modo da potere accedere ad oggetti URL come file
		ini_set("allow_url_fopen", true);

		$raw_post_data = file_get_contents('php://input');
		if (false === $raw_post_data) {
      $save->WriteLog('dashboard','plugin','send','Error. Could not read from the php://input stream or invalid IPN received.',true);
		}else{
      if (!self::PRODUCTION)
        $save->WriteLog('dashboard','plugin','save','php://input stream is ok.');
		}

    $post = $_POST;
		if (false === $post) {
      $save->WriteLog('dashboard','plugin','save','Error. Could not read from the $_POST stream or invalid IPN received.',true);
		}else{
      if (!self::PRODUCTION)
        $save->WriteLog('dashboard','plugin','save','$_POST stream is ok.');
		}
    if (!self::PRODUCTION)
      $save->WriteLog('dashboard','plugin','save','Received _POST is:<pre>'.print_r($post,true).'</pre>');


    //  echo 'a<pre>'.print_r($_POST,true).'</pre>';
    // // // echo '<pre>'.print_r($raw_post_data,true).'</pre>';
    //  exit;

    // VERIFICO CHE I DATI INVIATI SIANO CORRETTI
    Yii::import('ext.APIKeys');
    $ipn = (object) APIKeys::check();

		if (true === empty($ipn)) {
      $save->WriteLog('dashboard','plugin','save','Error. Could not decode the JSON payload from Server.',true);
		}else{
      if (!self::PRODUCTION)
        $save->WriteLog('dashboard','plugin','save','Json payload and api keys are ok.');
		}

    $payload = (object) $ipn->event;
    // $save->WriteLog('dashboard','plugin','save','Payload is: <pre>'.print_r($payload,true).'</pre>');

		if (true === empty($payload->id)) {
      $save->WriteLog('dashboard','plugin','save','Error. Invalid Server payment notification message received - did not receive invoice ID.',true);
		}else{
      if (!self::PRODUCTION)
        $save->WriteLog('dashboard','plugin','save','Ipn id loaded is ok.');
		}

    // CARICO le impostazioni
    $settings=Settings::load();
		if($settings===null)
      $save->WriteLog('dashboard','plugin','save','Error. The requested Settings does not exist.',true);

    // Load client data
    $client = WalletsBolt::model()->findByAttributes(['id_user'=>$payload->customer_id]);
    if($client===null)
      $save->WriteLog('dashboard','plugin','save','Error. The requested Client Wallet does not exist.',true);

    if (!self::PRODUCTION)
      $save->WriteLog('dashboard','plugin','save','Client wallet address is: '.$client->wallet_address);

    // aggiungo il wallet address
    $payload->client_address = $client->wallet_address;
    $identifier = explode(":",$payload->id);

    if (!(isset($identifier[1])))
      $payload->cart_id = $payload->id;
    else
      $payload->cart_id = $identifier[1];

    // generate the new payload
    $ipn->event = $payload;
    if (!self::PRODUCTION)
      $save->WriteLog('dashboard','plugin','save','New Payload to Rules Engine Server is: <pre>'.print_r($ipn,true).'</pre>');

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

    $model = new RuleEngineRequests;
    $model->id_merchant = $payload->merchant_id;
    $model->payload = json_encode($request);
    $model->save();

    //eseguo lo script che si occuperà in background di verificare lo stato dell'evento appena creata...
    $cmd = Yii::app()->basePath.DIRECTORY_SEPARATOR.'yiic event --id='.crypt::Encrypt($model->id_request);
    Utils::execInBackground($cmd);

    // Now, we can exit and respond with HTTP 200
    $json = array(
      'success'=> 1,
      'id' => crypt::Encrypt($model->id_request)
    );

    echo CJSON::encode($json);
  	// header("HTTP/1.1 200 OK");
	}


}
