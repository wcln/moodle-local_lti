<?php

namespace local_lti\IMSGlobal\LTI\OAuth;

/**
 * Class to represent an %OAuth Data Store
 *
 * @copyright  Andy Smith
 * @version 2008-08-04
 * @license https://opensource.org/licenses/MIT The MIT License
 */
class OAuthDataStore {
  private $consumer_key = NULL;
  private $consumer_secret = NULL;

  public function __construct($consumer_key, $consumer_secret) {

    $this->consumer_key = $consumer_key;
    $this->consumer_secret = $consumer_secret;

  }

  function lookup_consumer($consumer_key) {

    return new OAuthConsumer($this->consumer_key, $this->consumer_secret);

  }

  function lookup_token($consumer, $token_type, $token) {

    return new OAuthToken($consumer, '');

  }

  function lookup_nonce($consumer, $token, $nonce, $timestamp) {

    return false;  // If a persistent store is available nonce values should be retained for a period and checked here

  }

  function new_request_token($consumer, $callback = null) {

    return null;

  }

  function new_access_token($token, $consumer, $verifier = null) {

    return null;

  }

}
