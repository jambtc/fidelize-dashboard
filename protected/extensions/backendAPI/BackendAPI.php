<?php
/**
 * Reference implementation for Backend Engine's REST API.
 */

class BackendAPIException  extends ErrorException {};

class BackendAPI
{
  protected $key;     // API key
  protected $secret;  // API secret
  protected $url;     // API base URL
  protected $version; // API version
  protected $curl;    // curl handle

	protected $proxytunnel = false;	// set proxy
	protected $proxyurl = null;		// set proxy
	protected $proxyuserpwd = null;	// set proxy

  protected $rulesEngineUrl = null; // url of rules engine server

  /**
   * Constructor for BackendAPI
   *
   * @param string $key API key
   * @param string $secret API secret
   * @param string $url base URL for Kraken API
   * @param string $version API version
   * @param bool $sslverify enable/disable SSL peer verification.  disable if using beta.api.kraken.com
  */
  function __construct($key, $secret, $version='0', $sslverify=true)
  {
    $this->key = $key;
    $this->secret = $secret;
    $this->version = $version;
    $this->curl = curl_init();

    curl_setopt_array($this->curl, array(
      CURLOPT_SSL_VERIFYPEER => $sslverify,
      CURLOPT_SSL_VERIFYHOST => 2,
      CURLOPT_USERAGENT => 'Backend Engine PHP API Agent',
      CURLOPT_POST => true,
      CURLOPT_RETURNTRANSFER => true)
    );
  }

  function __destruct()
  {
    curl_close($this->curl);
  }

  private function error($e,$r){
    $msg['error'] = $e;
    $msg['description'] = $r;
    throw new BackendAPIException($e." - ". $r);
    // echo CJSON::encode($msg);
    // die();
  }

  /**
  * @param url Url of Rules Engine server
  */
  public function setRulesEngineUrl($url)
  {
    $this->rulesEngineUrl = $url;
  }
  /**
  * @param url Url of Rules Engine server
  */
  public function getRulesEngineUrl()
  {
    $this->rulesEngineUrl;
  }

	/**
	 * questa funzione imposta il proxy
	 * by Sergio Casizzone
     * I parametri vanno inviati in formato array
     *
     * @param address Url del proxy
     * @param port Porta del proxy
     * @param user Nome utente per accedere al proxy
     * @param pass Password
	*/
	public function setProxy($array){
		$this->proxytunnel = true;
		$this->proxyurl = $array['address'].':'.$array['port'];
		$this->proxyuserpwd = $array['user'].':'.$array['pass'];
	}

  /**
    * Query private methods
    *
    * @param string $path method path
    * @param array $request request parameters
    * @return array request result on success
    * @throws BackendAPIException
  */
  function QueryPrivate($path, array $request = array())
  {
    if(!isset($request['nonce'])) {
        // generate a 64 bit nonce using a timestamp at microsecond resolution
        // string functions are used to avoid problems on 32 bit systems
        $nonce = explode(' ', microtime());
        $request['nonce'] = $nonce[1] . str_pad(substr($nonce[0], 2, 6), 6, '0');
    }

    // build the POST data string
    $postdata = http_build_query($request, '', '&');

    // set API key and sign the message
    $sign = hash_hmac('sha512', hash('sha256', $request['nonce'] . $postdata, true), base64_decode($this->secret), true);
    $headers = array(
      'API-Key: ' . $this->key,
      'API-Sign: ' . base64_encode($sign),
      'x-fre-origin: '. $request['merchant_id'],
      'Authorization: ' . $this->key,
      'Content-Type: application/json',
      'accept: application/json',
    );

		// set proxy
		if ($this->proxytunnel == true){
			curl_setopt($this->curl, CURLOPT_HTTPPROXYTUNNEL, $this->proxytunnel);
			curl_setopt($this->curl, CURLOPT_PROXY, $this->proxyurl);
			curl_setopt($this->curl, CURLOPT_PROXYUSERPWD, $this->proxyuserpwd);
		}

    // transofmr the paylod in json format
    $payload = json_encode($request);

    // make request
    curl_setopt($this->curl, CURLOPT_URL, $this->url . $path);
    curl_setopt($this->curl, CURLOPT_POSTFIELDS, $payload); // IN JSON FORMAT to RULES ENGINE!!!
    curl_setopt($this->curl, CURLOPT_HTTPHEADER, $headers);
    $result = curl_exec($this->curl);
    if($result===false)
      throw new BackendAPIException ('CURL error: ' . curl_error($this->curl));


    return $result;

    // decode results
    // $res = json_decode($result, true);
    // if(!is_array($res))
    //   BackendAPI::error('JSON decode error',$result);

    // return $res;
  }
}
