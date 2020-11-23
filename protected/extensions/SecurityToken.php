<?php
define ('NONCE_STEP',0200);

class SecurityToken
{

  /**
   * Questa funzione controlla il securityToken
   *
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

    // verifico se è outdated
    $microtime = explode(' ', microtime());
    $nonce = $microtime[1] . str_pad(substr($microtime[0], 2, 6), 6, '0');
    if (($nonce/1000000 - $post->nonce/1000000) > NONCE_STEP)
      die (json_encode(['success'=>false,'message'=>'Data is outdated!']));

    foreach ($headers as $name => $value) {
      if (strtoupper($name) == 'API-KEY'){
        // Per prima cosa verifico la pre-esistenza del token in archivio
        $model = Api::model()->findByAttributes(['key_public'=>$value]);
        if (null === $model)
          die (json_encode(['success'=>false,'message'=>'Public key doesn\'t exist!']));

        // Ora ricreo l'hash del POST
        $request['data'] = print_r($post->data,true);
        $request['nonce'] = $post->nonce;
        $postdata = http_build_query($request, '', '&');

        $sign = hash_hmac('sha512', hash('sha256', $post->nonce . $postdata, true), base64_decode($model->key_secret), true);
        $sign = base64_encode($sign);

        if (strcmp($sign, $headers['API-Sign']) !== 0)
          die (json_encode(['success'=>false,'message'=>'Token is invalid!']));

        return $post->data;
      }
    }
  }
}
