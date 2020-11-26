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

namespace local_lti\provider;

use Exception;
use local_lti\imsglobal\lti\oauth;

/**
 * LTI Request
 *
 * Represents the LTI request.
 *
 * @package    local_lti
 * @copyright  2019 Colin Bernard
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class request extends oauth\request
{

    /** @var object The resource that was requested. */
    private $resource;

    /** @var object The Tool Consumer (TC) user. */
    private $user;

    /** @var string The random session ID. */
    private $session_id;

    public function __construct()
    {
        // Construct oauth parent request.
        parent::__construct();

        // Load resource depending on type.
        $resource_class = "\\local_lti\\resource_type\\".request::get_resource_type();

        try {
            $this->resource = new $resource_class(
                util::get_type_id(request::get_resource_type()),
                util::get_consumer_id(parent::get_parameter('oauth_consumer_key')),
                $this
            );
        } catch (Exception $e) {
            throw new Exception(get_string('error_invalid_type', 'local_lti'));
        }

        // Load user.
        $this->user = new user(parent::get_parameter('roles'));
    }

    /**
     * Verify the following:
     *   1. This is an LTI launch request.
     *   2. This is a VALID LTI launch request (authenticated).
     *   3. All required parameters have been provided.
     */
    public function verify()
    {
        if ($this->verify_launch_request()) {
            if ($this->verify_valid_launch_request()) {
                if ($this->verify_required_parameters()) {
                    return true;
                } else {
                    throw new Exception(get_string('error_missing_required_params', 'local_lti'));
                }
            } else {
                throw new Exception(get_string('error_auth_failed', 'local_lti'));
            }
        } else {
            throw new Exception(get_string('error_launch_request', 'local_lti'));
        }
    }

    /**
     * Verify that this is an LTI launch request.
     *
     * @return boolean is this a launch request?
     */
    private function verify_launch_request()
    {
        $ok = true;

        // Check it is a POST request
        $ok = $ok && $_SERVER['REQUEST_METHOD'] === 'POST';

        // Check the LTI message type
        $ok = $ok && ! is_null(parent::get_parameter('lti_message_type'))
              && (parent::get_parameter('lti_message_type') === 'basic-lti-launch-request');

        // Check the LTI version
        $ok = $ok && ! is_null(parent::get_parameter('lti_version'))
              && (parent::get_parameter('lti_version') === 'LTI-1p0');

        // Check a consumer key exists
        $ok = $ok && ! is_null(parent::get_parameter('oauth_consumer_key'));

        // Check a resource link ID exists
        $ok = $ok && ! is_null(parent::get_parameter('resource_link_id'));

        return $ok;
    }

    /**
     * Verify that this is a VALID authenticated launch request.
     *
     * @return boolean is this a valid launch request?
     */
    private function verify_valid_launch_request()
    {
        $ok = true;

        // Retrieve array of consumer keys and secrets.
        $tool_consumer_secrets = util::get_enabled_tool_consumer_secrets();

        // Check the consumer key is recognised
        $ok = $ok && array_key_exists(parent::get_parameter('oauth_consumer_key'), $tool_consumer_secrets);

        // Check the OAuth credentials (nonce, timestamp and signature)
        if ($ok) {
            try {
                $store  = new oauth\datastore(parent::get_parameter('oauth_consumer_key'),
                    $tool_consumer_secrets[parent::get_parameter('oauth_consumer_key')]);
                $server = new oauth\server($store);
                $method = new oauth\signature_method_HMAC_SHA1();
                $server->add_signature_method($method);
                $server->verify_request($this); // Verify this request.
            } catch (Exception $e) {
                $ok = false;
            }
        } else {
            // Invalid consumer key.
            throw new Exception("Invalid consumer key '".parent::get_parameter('oauth_consumer_key')."'!'");
        }

        return $ok;
    }

    /**
     * Verify that all required parameters are present.
     *
     * @return boolean are all required parameters present?
     */
    private function verify_required_parameters()
    {
        $ok = true;

        // Check if a custom ID parameter is set.
        $ok = $ok && $this->is_custom_parameter_set();

        // Check that the return URL parameter is set.
        // This is used for the back to course button.
        $ok = $ok && ! empty(parent::get_parameter('launch_presentation_return_url'));

        // If other parameters become required they are to be added here...

        return $ok;
    }

    /**
     * Checks if a content ID parameter is appended to the URL or sent as a custom paraneter with the request.
     *
     * @return boolean is the custom parameter set?
     */
    private function is_custom_parameter_set()
    {
        if ($this->get_parameter('custom_id') || optional_param('id', false, PARAM_INT)) {
            return true;
        }

        return false;
    }

    /**
     * Returns the user logged in to the consumer site.
     *
     * @return object Instance of provider/user class.
     */
    public function get_user()
    {
        return $this->user;
    }

    /**
     * Returns the resource that was requested.
     *
     * @return object Instance of provider/resource class.
     */
    public function get_resource()
    {
        return $this->resource;
    }

    /**
     * Returns the type of resource that was requested.
     *
     * @return string The type of resource that was requested.
     */
    private static function get_resource_type()
    {
        return required_param('type', PARAM_TEXT);
    }

    /**
     * Returns the session ID associated with this request.
     *
     * @return string The random session ID.
     */
    public function get_session_id()
    {
        return $this->session_id;
    }

    /**
     * Sets the session ID associated with this request.
     *
     * @param  string  $session_id  Random string.
     */
    public function set_session_id($session_id)
    {
        $this->session_id = $session_id;
    }
}
