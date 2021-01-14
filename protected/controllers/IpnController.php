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

}
