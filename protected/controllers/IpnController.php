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

  }

  /** Production Postback URL */
  const RULESENGINE_URI = 'https://rules.engine.com';


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
          'sendToRulesEngine', // send an order to rules engine
          'testRulesEngineResponse', //
          'rules', // action where receiving rules engine responses
        ),
				'users'=>array('*'),
			),
			array('deny',  // deny all users
				'users'=>array('*'),
			),
		);
	}

  public function actionTestRulesEngineResponse(){
    // echo CJSON::encode($_POST);
    // // echo '<pre>'.print_r($_POST,true).'</pre>';
    // exit;
    if (rand(1,10)==1){
      $success = false;
      $message = 'Fake error!';
    }else{
      $success = true;
      $message = 'OK!';
    }


 		echo CJSON::encode([
      'payload'=>$_POST,
      'success'=>$success,
      'message'=>$message,
    ]);

  }


	/**
	 * Performs the IPNLOGGER validation.
	 * @param none
	 */
	public function actionSendToRulesEngine()
	{
    // $origin = $_SERVER["HTTP_ORIGIN"];
    // header('Access-Control-Allow-Origin: ' . $origin);
    // header('Access-Control-Allow-Credentials: true');
		// header('Access-Control-Allow-Methods: POST');
		// header('Access-Control-Allow-Headers: Content-Type');

    $save = new Save;
    $save->WriteLog('dashboard','ipn','send','Start Ipn log.');

		// Questa opzione abilita i wrapper URL per fopen (file_get_contents), in modo da potere accedere ad oggetti URL come file
		ini_set("allow_url_fopen", true);

		$raw_post_data = file_get_contents('php://input');
		if (false === $raw_post_data) {
      $save->WriteLog('dashboard','ipn','send','Error. Could not read from the php://input stream or invalid IPN received.',true);
		}else{
      $save->WriteLog('dashboard','ipn','send','php://input stream is ok.');
		}

    // echo '<pre>'.print_r($_POST,true).'</pre>';
    // echo '<pre>'.print_r($raw_post_data,true).'</pre>';

    // VERIFICO CHE I DATI INVIATI SIANO CORRETTI
    Yii::import('ext.APIKeys');
    $ipn = json_decode(APIKeys::check(json_decode($raw_post_data)));
		if (true === empty($ipn)) {
      $save->WriteLog('dashboard','ipn','send','Error. Could not decode the JSON payload from Server.',true);
		}else{
      $save->WriteLog('dashboard','ipn','send','Json payload and api keys are ok.');
		}

		if (true === empty($ipn->id)) {
      $save->WriteLog('dashboard','ipn','send','Error. Invalid Server payment notification message received - did not receive invoice ID.',true);
		}else{
      $save->WriteLog('dashboard','ipn','send','Ipn id loaded is ok.');
		}


    // PAUSE - VEDIAMO FINO A QUI CHE SUCCEDE
    $settings=Settings::load();
		if($settings===null)
      $save->WriteLog('dashboard','ipn','send','Error. The requested Settings does not exist.',true);


    // Load client data
    $client = WalletsBolt::model()->findByAttributes(['id_user'=>$ipn->customer_id]);
    if($client===null)
      $save->WriteLog('dashboard','ipn','send','Error. The requested Client Wallet does not exist.',true);

    $save->WriteLog('dashboard','ipn','send','Client wallet address is: '.$client->wallet_address);

    $ipn->client_address = $client->wallet_address;
    $ipn->cart_id = $ipn->id;
    // echo '<pre>'.print_r($ipn,true).'</pre>';
    // exit;


    // Send the new Payload to Rules Engine Server
    Yii::import('ext.backendAPI.Backend');
    Yii::import('ext.backendAPI.BackendAPI');

    $api = new Backend($settings->RuleEngineApiKeyPublic,$settings->RuleEngineApiKeySecret);

    // use this to set proxy
    // $proxy = [ 'address' => 'proxy.example.it', 'port' => '8080', 'user' => 'username', 'pass' => 'password' ];
    // $api->setProxy($proxy);

    // set the Rules Engine URL
    // facciamo finta che l'indirizzo del Rules engine Server sia questo
    $rulesURL = 'http://localhost/fidelize-dashboard/index.php?r=ipn/testRulesEngineResponse';
    $api->setRulesEngineUrl($rulesURL);

    $save->WriteLog('dashboard','ipn','send','New Payload to Rules Engine Server is: '.print_r($ipn,true));

    $result = $api->send($ipn);

    //ADESSO POSSO USCIRE CON UN MESSAGGIO POSITIVO ;^)
    $save->WriteLog('dashboard','ipn','send',"IPN received for Shopping Cart transaction id: ".$ipn->id);

    echo CJSON::encode($result);

    // VERIFICARE SE A QUESTO PUNTO SIA DA PRENDERE IN CONSIDERAZIONE SALVARE LA RICHIESTA
    // ÈER UN SUCCESSIVO INVIO DA PARTE DI UN ALTRO PROCESSO,
    // OPPURE RIPETERE L'INVIO DAL PLUGIN, IPN, ECC. ECC.
    // questo per ovviare al fatto che magari il rules engine server è down o
    // anche semplicemente ha risorse impegnate e non risponde subito .



    // echo '<pre>'.print_r($result,true).'</pre>';
    // exit;



		//ADESSO POSSO USCIRE CON UN MESSAGGIO POSITIVO ;^)
    //$save->WriteLog('dashboard','ipn','send',"End: IPN received for Server transaction ".$invoice->getId()." . Status = " .$invoice->getStatus()." Price = ". $invoice->getPrice(). " Paid = ".$invoice->getBtcPaid());

		//Respond with HTTP 200, so BitPay knows the IPN has been received correctly
		//If BitPay receives <> HTTP 200, then BitPay will try to send the IPN again with increasing intervals for two more hours.
		//header("HTTP/1.1 200 OK");
	}


}
