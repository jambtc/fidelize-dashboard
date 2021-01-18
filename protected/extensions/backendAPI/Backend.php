<?php
/**
 * send invia i dati al motore di regole
 * $send = $rules->send();
 *
 * @return array with error message or array of balances
 * @throws \Exception
*/

require_once ('BackendAPI.php');

class Backend extends BackendAPI
{
  public function send($ipn)
  {
    return $this->QueryPrivate($this->rulesEngineUrl, (array) $ipn);
  }
};
