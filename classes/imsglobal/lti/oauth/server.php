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
 * Class to represent an %OAuth Server
 *
 * @copyright  Andy Smith
 * @version    2008-08-04
 * @license    https://opensource.org/licenses/MIT The MIT License
 */
class server
{

    protected $timestamp_threshold = 600; // in seconds, five minutes
    protected $version = '1.0';
    protected $signature_methods = array();

    protected $data_store;

    function __construct($data_store)
    {
        $this->data_store = $data_store;
    }

    public function add_signature_method($signature_method)
    {
        $this->signature_methods[$signature_method->get_name()] = $signature_method;
    }

    // high level functions

    /**
     * process a request_token request
     * returns the request token on success
     */
    public function fetch_request_token(&$request)
    {
        $this->get_version($request);

        $consumer = $this->get_consumer($request);

        // no token required for the initial token request
        $token = null;

        $this->check_signature($request, $consumer, $token);

        // Rev A change
        $callback  = $request->get_parameter('oauth_callback');
        $new_token = $this->data_store->new_request_token($consumer, $callback);

        return $new_token;
    }

    /**
     * process an access_token request
     * returns the access token on success
     */
    public function fetch_access_token(&$request)
    {
        $this->get_version($request);

        $consumer = $this->get_consumer($request);

        // requires authorized request token
        $token = $this->get_token($request, $consumer, "request");

        $this->check_signature($request, $consumer, $token);

        // Rev A change
        $verifier  = $request->get_parameter('oauth_verifier');
        $new_token = $this->data_store->new_access_token($token, $consumer, $verifier);

        return $new_token;
    }

    /**
     * verify an api call, checks all the parameters
     */
    public function verify_request(&$request)
    {
        $this->get_version($request);
        $consumer = $this->get_consumer($request);
        $token    = $this->get_token($request, $consumer, "access");
        $this->check_signature($request, $consumer, $token);

        return array($consumer, $token);
    }

    // Internals from here

    /**
     * version 1
     */
    private function get_version(&$request)
    {
        $version = $request->get_parameter("oauth_version");
        if ( ! $version) {
            // Service Providers MUST assume the protocol version to be 1.0 if this parameter is not present.
            // Chapter 7.0 ("Accessing Protected Ressources")
            $version = '1.0';
        }
        if ($version !== $this->version) {
            throw new exception("OAuth version '$version' not supported");
        }

        return $version;
    }

    /**
     * figure out the signature with some defaults
     */
    private function get_signature_method($request)
    {
        $signature_method = $request instanceof request
            ? $request->get_parameter('oauth_signature_method') : null;

        if ( ! $signature_method) {
            // According to chapter 7 ("Accessing Protected Ressources") the signature-method
            // parameter is required, and we can't just fallback to PLAINTEXT
            throw new exception('No signature method parameter. This parameter is required');
        }

        if ( ! in_array($signature_method,
            array_keys($this->signature_methods))
        ) {
            throw new exception(
                "Signature method '$signature_method' not supported ".
                'try one of the following: '.
                implode(', ', array_keys($this->signature_methods))
            );
        }

        return $this->signature_methods[$signature_method];
    }

    /**
     * try to find the consumer for the provided request's consumer key
     */
    private function get_consumer($request)
    {
        $consumer_key = $request instanceof request
            ? $request->get_parameter('oauth_consumer_key') : null;

        if ( ! $consumer_key) {
            throw new exception('Invalid consumer key');
        }

        $consumer = $this->data_store->lookup_consumer($consumer_key);
        if ( ! $consumer) {
            throw new exception('Invalid consumer');
        }

        return $consumer;
    }

    /**
     * try to find the token for the provided request's token key
     */
    private function get_token($request, $consumer, $token_type = "access")
    {
        $token_field = $request instanceof request
            ? $request->get_parameter('oauth_token') : null;

        $token = $this->data_store->lookup_token($consumer, $token_type, $token_field);
        if ( ! $token) {
            throw new exception("Invalid $token_type token: $token_field");
        }

        return $token;
    }

    /**
     * all-in-one function to check the signature on a request
     * should guess the signature method appropriately
     */
    private function check_signature($request, $consumer, $token)
    {
        // this should probably be in a different method
        $timestamp = $request instanceof request
            ? $request->get_parameter('oauth_timestamp')
            : null;
        $nonce     = $request instanceof request
            ? $request->get_parameter('oauth_nonce')
            : null;

        $this->check_timestamp($timestamp);
        $this->check_nonce($consumer, $token, $nonce, $timestamp);

        $signature_method = $this->get_signature_method($request);

        $signature = $request->get_parameter('oauth_signature');
        $valid_sig = $signature_method->check_signature($request, $consumer, $token, $signature);

        if ( ! $valid_sig) {
            throw new exception('Invalid signature');
        }
    }

    /**
     * check that the timestamp is new enough
     */
    private function check_timestamp($timestamp)
    {
        if ( ! $timestamp) {
            throw new exception('Missing timestamp parameter. The parameter is required');
        }

        // verify that timestamp is recentish
        $now = time();
        if (abs($now - $timestamp) > $this->timestamp_threshold) {
            throw new exception("Expired timestamp, yours $timestamp, ours $now");
        }
    }

    /**
     * check that the nonce is not repeated
     */
    private function check_nonce($consumer, $token, $nonce, $timestamp)
    {
        if ( ! $nonce) {
            throw new exception('Missing nonce parameter. The parameter is required');
        }

        // verify that the nonce is uniqueish
        $found = $this->data_store->lookup_nonce($consumer, $token, $nonce, $timestamp);
        if ($found) {
            throw new exception("Nonce already used: $nonce");
        }
    }

}
