<?php

namespace local_lti\imsglobal\lti\oauth;

/**
 * Class to represent an %OAuth Consumer
 *
 * @copyright  Andy Smith
 * @version 2008-08-04
 * @license https://opensource.org/licenses/MIT The MIT License
 */
class consumer {

    public $key;
    public $secret;

    function __construct($key, $secret, $callback_url=NULL) {
        $this->key = $key;
        $this->secret = $secret;
        $this->callback_url = $callback_url;
    }

    function __toString() {
        return "consumer[key=$this->key,secret=$this->secret]";
    }
}
