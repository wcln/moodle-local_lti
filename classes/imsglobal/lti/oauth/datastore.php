<?php
// This file is part of Moodle - http://moodle.org/
//
// Moodle is free software: you can redistribute it and/or modify
// it under the terms of the GNU General Public License as published by
// the Free Software Foundation, either version 3 of the License, or
// (at your option) any later version.
//
// Moodle is distributed in the hope that it will be useful,
// but WITHOUT ANY WARRANTY; without even the implied warranty of
// MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
// GNU General Public License for more details.
//
// You should have received a copy of the GNU General Public License
// along with Moodle.  If not, see <http://www.gnu.org/licenses/>.

namespace local_lti\imsglobal\lti\oauth;

/**
 * Class to represent an %OAuth Data Store
 *
 * @copyright  Andy Smith
 * @version    2008-08-04
 * @license    https://opensource.org/licenses/MIT The MIT License
 */
class datastore
{
    private $consumer_key = null;
    private $consumer_secret = null;

    public function __construct($consumer_key, $consumer_secret)
    {
        $this->consumer_key    = $consumer_key;
        $this->consumer_secret = $consumer_secret;
    }

    function lookup_consumer($consumer_key)
    {
        return new consumer($this->consumer_key, $this->consumer_secret);
    }

    function lookup_token($consumer, $token_type, $token)
    {
        return new token($consumer, '');
    }

    function lookup_nonce($consumer, $token, $nonce, $timestamp)
    {
        return false;  // If a persistent store is available nonce values should be retained for a period and checked here

    }

    function new_request_token($consumer, $callback = null)
    {
        return null;
    }

    function new_access_token($token, $consumer, $verifier = null)
    {
        return null;
    }

}
