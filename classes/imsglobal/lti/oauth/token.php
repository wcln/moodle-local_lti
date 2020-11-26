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
 * Class to represent an %OAuth Token
 *
 * @copyright  Andy Smith
 * @version    2008-08-04
 * @license    https://opensource.org/licenses/MIT The MIT License
 */
class token
{

    // access tokens and request tokens
    public $key;
    public $secret;

    /**
     * key = the token
     * secret = the token secret
     */
    function __construct($key, $secret)
    {
        $this->key    = $key;
        $this->secret = $secret;
    }

    /**
     * generates the basic string serialization of a token that a server
     * would respond to request_token and access_token calls with
     */
    function to_string()
    {
        return 'oauth_token='.
               util::urlencode_rfc3986($this->key).
               '&oauth_token_secret='.
               util::urlencode_rfc3986($this->secret);
    }

    function __toString()
    {
        return $this->to_string();
    }

}
