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
  public function check($request)
  {
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

    $securityToken = null;
    $post = json_decode($_POST['data']);
    $headers = getallheaders();

    // check if the message is outdated
    $microtime = explode(' ', microtime());
    $nonce = $microtime[1] . str_pad(substr($microtime[0], 2, 6), 6, '0');
    if (($nonce/1000000 - $post->nonce/1000000) > NONCE_STEP)
      die (json_encode(['success'=>false,'message'=>'Data is outdated!']));

    foreach ($headers as $name => $value) {
      if (strtoupper($name) == 'API-KEY'){
        // Load the Api keys from table to check existence
        $model = Api::model()->findByAttributes(['key_public'=>$value]);
        if (null === $model)
          die (json_encode(['success'=>false,'message'=>'Public key doesn\'t exist!']));

        // Now we re-generate the POST hash
        $request['data'] = print_r($post->data,true);
        $request['nonce'] = $post->nonce;
        $postdata = http_build_query($request, '', '&');

        $sign = base64_encode(hash_hmac('sha512', hash('sha256', $post->nonce . $postdata, true), base64_decode($model->key_secret), true));

        if (strcmp($sign, $headers['API-Sign']) !== 0)
          die (json_encode(['success'=>false,'message'=>'Api keys are invalid!']));

        return $post->data;
      }
    }
  }
}
