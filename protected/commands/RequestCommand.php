<?php
Yii::import('libs.crypt.crypt');
Yii::import('libs.NaPacks.Settings');
Yii::import('libs.NaPacks.WebApp');
Yii::import('libs.NaPacks.SaveModels');
Yii::import('libs.NaPacks.Save');
Yii::import('libs.NaPacks.Push');
Yii::import('libs.NaPacks.Notifi');
Yii::import('libs.Utils.Utils');
Yii::import('libs.webRequest.webRequest');

class RequestCommand extends CConsoleCommand
{
	public $logfilehandle = null;

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
				'actions'=>array('index'),
				'users'=>array('*'),
			),
			array('deny',  // deny all users
				'users'=>array('*'),
			),
		);
	}



	/**
	 * Returns the data model based on the primary key given in the GET variable.
	 * If the data model is not found, an HTTP exception will be raised.
	 * @param integer $id the ID of the model to be loaded
	 * @return RuleEngineRequests the loaded model
	 * @throws CHttpException
	 */
	public function loadRequest($id)
	{
		$model=RuleEngineRequests::model()->findByPk($id);
		if($model===null)
			$this->log("The requested page does not exist.",true);

		return $model;
	}

	//scrive a video e nel file log le informazioni richieste
  private function log($text, $die = false){
		$save = new Save;
		$save->WriteLog('dashboard','commands','request', $text, $die);
		echo "\r\n" .date('Y/m/d h:i:s a - ', time()) .$text;
	}

	public function actionIndex($id){
		set_time_limit(0); //imposto il time limit unlimited
		$save = new Save;
		$try = 1;
		$MAXtry = 32768; // quasi 1 giorno di monitoraggio

		$this->log("Checking request $id");

		//carico la richiesta
		$request = $this->loadRequest(crypt::Decrypt($id));
		$this->log("Request loaded. Status is $request->sent");

		// INIZIO IL LOOP
		while(true){
			if ($request->sent == 0){ //se il valore è 0 proseguo
				// CARICO le impostazioni
		    $settings=Settings::load();
				if($settings===null)
		      $this->log('Error. The requested Settings page does not exist.',true);

				// Send the new Payload to Rules Engine Server
				// - import class
				Yii::import('ext.rulesEngine.RulesEngineApi');

				// set the api keys to send in the header
				$REA = new RulesEngineApi($settings->RulesEngineApiKeyPublic,$settings->RulesEngineApiKeySecret);

				// set the Rules Engine URL
				$REA->setRulesEngineUrl($settings->RulesEngineApiKeyURL);

				// transform payload in json format
				$payload = (array) json_decode($request->payload);

				// Send request to Rules Engine
				$result = $REA->send($payload);
				$this->log("Response of server is: ".$result);

				$analisi = CJSON::decode($result);
				//$this->log("Analisi is: <pre>".print_r($analisi,true)."</pre>");

				if (is_array($analisi)){
					if (!isset($analisi['errors'])){
						if ($analisi['event']['group']['total_items'][0] == 'ok'){
							$this->log('Payload sent correctly!');
							// imposto il sent to true
							$request->sent = 1;
							$request->save();
							break;
						}else{
							$this->log('Payload sent, but cannot trigger event!');
							$request->sent = 1;
							$request->save();
							break;
						}
					}else{
						$this->log('Payload sent, but there was an error:'. $analisi['errors']['detail']);
						// imposto il sent to true
						$request->sent = 1;
						$request->save();
						break;
					}
				}else{
					$this->log('Payload not sent! Retry again.');
				}
			}else if ($request->sent == 1){
				$this->log('Payload already sent!');
				break;
			}else{
				$this->log('Payload already sent, but there was an unknown error!');
				break;
			}
			$this->log("Request id: $id, Status: ".$request->sent.", Waiting seconds: ".$try."\n");
			sleep($try);
			$try = $try*2;

			if ($try > $MAXtry){
				// imposto il sent to error
				$request->sent = 2;
				$request->save();
				$this->log('Payload '.$id.' is not monitored anymore.');
				break;
			}
		}
	}


}
?>
