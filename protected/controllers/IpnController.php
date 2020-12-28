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
  public function actionRules()
  {
    if (rand(1,10)==1){
      $success = false;
      $message = 'Fake error!';
    }else{
      $success = true;
      $message = 'OK!';
    }

    $response = [
      'payload'=>$_POST,
      'headers'=>getallheaders(),
      'success'=>$success,
      'message'=>$message,
    ];

    $save = new Save;
    $save->WriteLog('dashboard','ipn','rules','<pre>'.print_r($response,true).'</pre>');

    echo CJSON::encode($response);
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

    $response = [
      'payload'=>$_POST,
      'headers'=>getallheaders(),
      'success'=>$success,
      'message'=>$message,
    ];

    $save = new Save;
    $save->WriteLog('dashboard','ipn','fake rules','<pre>'.print_r($response,true).'</pre>');

    echo CJSON::encode($response);

  }


	/**
	 * Performs the IPNLOGGER validation.
	 * @param none
	 */
	public function actionSendToRulesEngine()
	{
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

    $post = $_POST;
		if (false === $post) {
      $save->WriteLog('dashboard','ipn','send','Error. Could not read from the $_POST stream or invalid IPN received.',true);
		}else{
      $save->WriteLog('dashboard','ipn','send','$_POST stream is ok.');
		}
    $save->WriteLog('dashboard','ipn','send','Received _POST is:<pre>'.print_r($post,true).'</pre>');


    //  echo 'a<pre>'.print_r($_POST,true).'</pre>';
    // // // echo '<pre>'.print_r($raw_post_data,true).'</pre>';
    //  exit;

    // VERIFICO CHE I DATI INVIATI SIANO CORRETTI
    Yii::import('ext.APIKeys');
    $ipn = (object) APIKeys::check();

    // echo 'ipn <pre>'.print_r($ipn,true).'</pre>';
    // exit;

    // $ipn = json_decode(APIKeys::check(json_decode($raw_post_data)));
		if (true === empty($ipn)) {
      $save->WriteLog('dashboard','ipn','send','Error. Could not decode the JSON payload from Server.',true);
		}else{
      $save->WriteLog('dashboard','ipn','send','Json payload and api keys are ok.');
		}

    $payload = (object) $ipn->event;
    $save->WriteLog('dashboard','ipn','send','Payload is: <pre>'.print_r($payload,true).'</pre>');

		if (true === empty($payload->id)) {
      $save->WriteLog('dashboard','ipn','send','Error. Invalid Server payment notification message received - did not receive invoice ID.',true);
		}else{
      $save->WriteLog('dashboard','ipn','send','Ipn id loaded is ok.');
		}

    // CARICO le impostazioni
    $settings=Settings::load();
		if($settings===null)
      $save->WriteLog('dashboard','ipn','send','Error. The requested Settings does not exist.',true);


    // Load client data
    $client = WalletsBolt::model()->findByAttributes(['id_user'=>$payload->customer_id]);
    if($client===null)
      $save->WriteLog('dashboard','ipn','send','Error. The requested Client Wallet does not exist. Refresh the page to select another `customer_id`',true);

    $save->WriteLog('dashboard','ipn','send','Client wallet address is: '.$client->wallet_address);

    // aggiungo il wallet address
    $payload->client_address = $client->wallet_address;
    $identifier = explode(":",$payload->id);

    if (!(isset($identifier[1])))
      $payload->cart_id = $payload->id;
    else
      $payload->cart_id = $identifier[1];

    // FIX to JSON_NUMERIC_CHECK
    $payload->merchant_id = (string) $payload->merchant_id;


      // //TEST ELIMINARE items diversi dal payload del rule engine .
      // unset($payload->id);
      // unset($payload->redirect_url);
      // unset($payload->customer_id);
      // unset($payload->order_number);
      // unset($payload->order_total);

    // generate the new payload
    $ipn->event = $payload;
    $save->WriteLog('dashboard','ipn','send','New Payload to Rules Engine Server is: <pre>'.print_r($ipn,true).'</pre>');

    // Send the new Payload to Rules Engine Server
    Yii::import('ext.backendAPI.Backend');
    Yii::import('ext.backendAPI.BackendAPI');

    $api = new Backend($settings->RulesEngineApiKeyPublic,$settings->RulesEngineApiKeySecret);

    // use this to set proxy
    // $proxy = [ 'address' => 'proxy.example.it', 'port' => '8080', 'user' => 'username', 'pass' => 'password' ];
    // $api->setProxy($proxy);

    /**
    * GENERATE THE HEADERS
    */
    // build the POST data string with the original payload
    $postdata = http_build_query($ipn, '', '&');

    // set API key and sign the message
    $sign = hash_hmac('sha512', hash('sha256', $payload->nonce . $postdata, true), base64_decode($settings->RulesEngineApiKeySecret), true);
    $headers = array(
      'API-Key: ' . $settings->RulesEngineApiKeyPublic,
      'API-Sign: ' . base64_encode($sign),
      'x-fre-origin: '. $payload->merchant_id,
      'Authorization: ' . $settings->RulesEngineApiKeyPublic,
      'Content-Ttype: application/json',
      'Accept: application/json',
    );
    /////////////////


    $api->setRulesEngineUrl($settings->RulesEngineApiKeyURL);
    $save->WriteLog('dashboard','ipn','send','New Header to Rules Engine Server is: <pre>'.print_r($headers,true).'</pre>');
    $result = $api->send($ipn);

    // echo '<pre>'.print_r($result,true).'</pre>';
    // exit;

    //ADESSO POSSO USCIRE CON UN MESSAGGIO POSITIVO ;^)
    $save->WriteLog('dashboard','ipn','send',"Response of server is: ".$result);

    // echo CJSON::encode(['result'=>$result]);

    // VERIFICARE SE A QUESTO PUNTO SIA DA PRENDERE IN CONSIDERAZIONE SALVARE LA RICHIESTA
    // ÈER UN SUCCESSIVO INVIO DA PARTE DI UN ALTRO PROCESSO,
    // OPPURE RIPETERE L'INVIO DAL PLUGIN, IPN, ECC. ECC.
    // questo per ovviare al fatto che magari il rules engine server è down o
    // anche semplicemente ha risorse impegnate e non risponde subito .


    // echo '<pre>'.print_r($result,true).'</pre>';
    // exit;

		//Respond with HTTP 200, so BitPay knows the IPN has been received correctly
		//If BitPay receives <> HTTP 200, then BitPay will try to send the IPN again with increasing intervals for two more hours.
		//header("HTTP/1.1 200 OK");
	}


}
