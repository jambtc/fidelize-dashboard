<?php
// 2 seconds for margin of error
define ('NONCE_STEP',200); // 200 for testing

class APIKeys
{
  /**
   * This function checks the API Keys
   *
   * @param array $request is the POST message
  */
  public function check($post = null)
  {
    $save = new Save;

    if (!function_exists('getallheaders')) {
      function getallheaders() {
        $headers = [];
        foreach ($_SERVER as $name => $value) {
          if (substr($name, 0, 5) == 'HTTP_') {
            $headers[str_replace(' ', '-', ucwords(strtolower(str_replace('_', ' ', substr($name, 5)))))] = $value;
          }
        }
        return $headers;
      }
    }
    // echo '<pre>apikeys'.print_r($_POST,true).'</pre>';
    // exit;

    if (null === $post)
      $event = (object) $_POST['event'];
    else
      $event = (object) $post['event'];

    $headers = getallheaders();

    // check if isset nonce
    if (!(isset($event->nonce))){
      $save->WriteLog('dashboard','ipn','APIKeys','Nonce isn\'t set! POST is:<pre>'.print_r($_POST,true).'</pre>');
      die (json_encode(['success'=>false,'message'=>'Nonce isn\'t set!']));
    }

    // check if the message is outdated
    $microtime = explode(' ', microtime());
    $nonce = $microtime[1] . str_pad(substr($microtime[0], 2, 6), 6, '0');
    $a = substr($nonce,1,9)*1;
    $b = substr($event->nonce,1,9)*1;

    // $message = "Nonce are: ".$a." - ".$b. " = ". round($a - $b) . " > ".NONCE_STEP;
    // $save->WriteLog('dashboard','ipn','APIKeys',$message);

    if (($a - $b) > NONCE_STEP){
      $message = "Data is outdated! ".$a." - ".$b. " = ". round($a - $b) . " > ".NONCE_STEP;
      $save->WriteLog('dashboard','ipn','APIKeys',$message);
      die (json_encode(['success'=>false,'message'=>'Data is outdated!']));
    }

    foreach ($headers as $name => $value) {
      if (strtoupper($name) == 'API-KEY'){
        // Load the Api keys from table to check existence
        $model = ApiConnections::model()->findByAttributes(['key_public'=>$value]);
        if (null === $model){
          $save->WriteLog('dashboard','ipn','APIKeys','Public key doesn\'t exist!');
          die (json_encode(['success'=>false,'message'=>'Public key doesn\'t exist!']));
        }

        // Now we re-generate the POST hash
        $postdata = http_build_query($_POST, '', '&');

        // Now do the sign
        $sign = base64_encode(hash_hmac('sha512', hash('sha256', $event->nonce . $postdata, true), base64_decode($model->key_secret), true));

        // compare the two signatures
        if (strcmp($sign, $headers['API-Sign']) <> 0){
          $save->WriteLog('dashboard','ipn','APIKeys','Api keys are invalid!');
          die (json_encode(['success'=>false,'message'=>'Api keys are invalid!']));
        }

        return $_POST;
      }
    }
  }
}
