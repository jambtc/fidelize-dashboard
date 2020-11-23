<?php

class Backend extends BackendAPI
{

	/**
     * send invia i dati al motore di regole
     * $send = $rules->send();
     *
     * @return array with error message or array of balances
     * @throws \Exception
  */
  public function send($ipn)
  {
    return $this->QueryPrivate($this->url, (array) $ipn);
  }
};
