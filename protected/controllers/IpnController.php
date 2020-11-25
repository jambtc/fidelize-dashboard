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
          'send', // send an order to rules engine
        ),
				'users'=>array('*'),
			),
			array('deny',  // deny all users
				'users'=>array('*'),
			),
		);
	}


	/**
	 * Performs the IPNLOGGER validation.
	 * @param none
	 */
	public function actionSend()
	{
    $save = new Save;
    $save->WriteLog('dashboard','ipn','send','Start Ipn log.');

		// Questa opzione abilita i wrapper URL per fopen (file_get_contents), in modo da potere accedere ad oggetti URL come file
		ini_set("allow_url_fopen", true);

		$raw_post_data = file_get_contents('php://input');
		if (false === $raw_post_data) {
      $save->WriteLog('dashboard','ipn','send','Error. Could not read from the php://input stream or invalid IPN received.',true);
		}else{
      $save->WriteLog('dashboard','ipn','send','Stream ok.');
		}

    // echo '<pre>'.print_r($_POST,true).'</pre>';
    // echo '<pre>'.print_r($raw_post_data,true).'</pre>';

    // VERIFICO CHE I DATI INVIATI SIANO CORRETTI
    Yii::import('ext.APIKeys');
    $ipn = json_decode(APIKeys::check(json_decode($raw_post_data)));
		if (true === empty($ipn)) {
      $save->WriteLog('dashboard','ipn','send','Error. Could not decode the JSON payload from Server.',true);
		}else{
      $save->WriteLog('dashboard','ipn','send','Json ok.');
		}

		if (true === empty($ipn->id)) {
      $save->WriteLog('dashboard','ipn','send','Error. Invalid Server payment notification message received - did not receive invoice ID.',true);
		}else{
      $save->WriteLog('dashboard','ipn','send','Ipn ok.');
		}


    // PAUSE - VEDIAMO FINO A QUI CHE SUCCEDE
    $settings=Settings::load();
		if($settings===null)
      $save->WriteLog('dashboard','ipn','send','Error. The requested Settings does not exist.',true);


    $rulesAPIKey = $settings->RuleEngineApiKeyPublic;
    $rulesAPISecret = $settings->RuleEngineApiKeySecret;
  //  $rulesURL = $settings->RuleEngineUrl;
    $rulesURL = 'localhost';


    Yii::import('ext.backendAPI.Backend');
    Yii::import('ext.backendAPI.BackendAPI');

    $api = new Backend($rulesAPIKey,$rulesAPISecret,$rulesURL);

    //$proxy = [ 'address' => 'proxy.example.it', 'port' => '8080', 'user' => 'username', 'pass' => 'password' ];
    //$api->setProxy($proxy);
    $result = $api->send($ipn);




    //echo '<pre>'.print_r($settings,true).'</pre>';
    exit;







		//ADESSO POSSO USCIRE CON UN MESSAGGIO POSITIVO ;^)
    //$save->WriteLog('dashboard','ipn','send',"End: IPN received for Server transaction ".$invoice->getId()." . Status = " .$invoice->getStatus()." Price = ". $invoice->getPrice(). " Paid = ".$invoice->getBtcPaid());

		//Respond with HTTP 200, so BitPay knows the IPN has been received correctly
		//If BitPay receives <> HTTP 200, then BitPay will try to send the IPN again with increasing intervals for two more hours.
		header("HTTP/1.1 200 OK");
	}


}
