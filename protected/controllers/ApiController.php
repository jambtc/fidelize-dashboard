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

class ApiController extends Controller
{
  public function init()
	{
    // change this constant to true in PRODUCTION
    define('PRODUCTION',false);
  }
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
          'index', // action where receiving rules engine responses
        ),
				'users'=>array('*'),
			),
			array('deny',  // deny all users
				'users'=>array('*'),
			),
		);
	}

  /*
   * This function manages the requests from Rules Engine after it matches the
   * rules.
   *
   * The predefined actions are:
   *        1. send tokens to client client wallet address
   *        2. send mail to client email address
   *        3. send push message to client app
   *
   * The payload Rule Engine have to send to this funciont must have json format
   * and have to satisfy these fields form:
   *
   * {
   *    'event': {
   *        'id': 'the-original-event-id',
   *        'nonce': 'a-nonce-in-microtime',
   *        'merchant_id': 'the-merchant-id',
   *        'customer_id': 'the-customer-id',
   *        'actions': {
   *            'pay':  {
   *                 'token_amount': 'token-amount-to-pay-to-client',
   *                 'client_address': 'client-wallet-address',
   *                 'message': 'message-to-send-in-transaction'
   *             },
   *            'mail': {
   *                 'message': 'email-message-to-client'
   *             },
   *            'push': {
   *                 'message': 'push-message-to-client'
   *            }
   *         }
   *    }
   * }
   *
   for testig curl

   curl -X POST "http://26.210.113.168/fidelize-dashboard/index.php?r=api" \
   -H "API-Key: 6ggfgdhgs7aag7as" \
   -H "API-Sign: 635dfgfg762g8gfgfg6gs9gfysagfgagfhjgaff4444ssd" \
   -H "Content-Type: application/json" \
   -d "{\"event\":{\"id\": \"the-original-event-id\",\"nonce\": \"a-nonce-in-microtime\",\"merchant_id\": \"the-merchant-id\", \"customer_id\": \"the-customer-id\", \"actions\": { \"pay\":  {\"token_amount\": \"token-amount-to-pay-to-client\",\"client_address\": \"client-wallet-address\", \"message\": \"message-to-send-in-transaction\" }, \"mail\": { \"message\": \"email-message-to-client\" },\"push\": {\"message\": \"push-message-to-client\" }}}"

  */
  public function actionIndex()
  {
    $save = new Save;
    if (!PRODUCTION) $save->WriteLog('dashboard','API','Index','Start log.');

		// Questa opzione abilita i wrapper URL per fopen (file_get_contents), in modo da potere accedere ad oggetti URL come file
		ini_set("allow_url_fopen", true);

		$raw_post_data = file_get_contents('php://input');
		if (false === $raw_post_data) $save->WriteLog('dashboard','API','Index','Could not read from the php://input stream or invalid IPN received.',true);
		else if (!PRODUCTION) $save->WriteLog('dashboard','API','Index','php://input stream is valid.');

    if (!PRODUCTION) $save->WriteLog('dashboard','API','Index','Received php://input stream is:<pre>'.print_r($raw_post_data,true).'</pre>');

		if (false === $_POST) $save->WriteLog('dashboard','API','Index','Could not read from the $_POST stream or invalid IPN received.',true);
		else if (!PRODUCTION) $save->WriteLog('dashboard','API','Index','$_POST stream is valid.');

    if (!PRODUCTION) $save->WriteLog('dashboard','API','Index','Received $_POST is:<pre>'.print_r($_POST,true).'</pre>');

    $json = CJSON::decode($raw_post_data);
    if (!PRODUCTION) $save->WriteLog('dashboard','API','Index','json is:<pre>'.print_r($json,true).'</pre>');

    // VERIFICO CHE NEL json CI SIA L'EVENT
    if (!isset($json['event'])) {
      $save->WriteLog('dashboard','API','Index','$json event is not valid.',true);
		}else{
      if (!PRODUCTION)
        $save->WriteLog('dashboard','API','Index','$json event is valid.');
		}

    $_POST = $json;

    // VERIFICO CHE I DATI INVIATI SIANO CORRETTI
    // nella fase di test non controllo l'api key e la signed key nell'header 
    // Yii::import('ext.APIKeys');
    // $ipn = (object) APIKeys::check();

    $ipn = (object) $json;

		if (true === empty($ipn)) {
      $save->WriteLog('dashboard','API','Index','Could not decode the JSON payload from Server.',true);
		}else{
      if (!PRODUCTION)
        $save->WriteLog('dashboard','API','Index','Json payload and api keys are valid.');
		}

    $payload = (object) $ipn->event;
    if (!PRODUCTION)
      $save->WriteLog('dashboard','API','Index','Payload is: <pre>'.print_r($payload,true).'</pre>');

		if (true === empty($payload->id)) {
      $save->WriteLog('dashboard','API','Index','Invalid Server payment notification message received - did not receive invoice ID.',true);
		}else{
      if (!PRODUCTION)
        $save->WriteLog('dashboard','API','Index','Ipn id is valid.');
		}

    // CARICO le impostazioni
    $settings=Settings::load();
		if($settings===null)
      $save->WriteLog('dashboard','API','Index','The requested Settings does not exist.',true);






    // ok. Al momoento restituisco semplicemenre il payload e l'header
    $response = [
      'payload'=>$_POST,
      'headers'=>getallheaders(),
      'success'=>1,
      'message'=>'test-ok-no-action-to-do',
    ];


    $save->WriteLog('dashboard','ipn','rules','<pre>'.print_r($response,true).'</pre>');

    // Rispondi con il json
    header('Content-type:application/json;charset=utf-8');
    echo CJSON::encode($response);
  }

}
