<?php

namespace local_lti\provider;
use local_lti\provider\error;
use local_lti\provider\util;
use local_lti\provider\resource;
use local_lti\provider\user;
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
class request extends \local_lti\imsglobal\lti\oauth\request {

  private $resource;
  private $user;

  public function __construct() {

    // Construct oauth parent request.
    parent::__construct();

    // Load resource.
    $this->resource = new resource(
      parent::get_parameter('resource_link_id'),
      parent::get_parameter('resource_link_title'),
      util::get_type_id(request::get_resource_type()),
      util::get_consumer_id(parent::get_parameter('oauth_consumer_key'))
    );

    // Load user.
    $this->user = new user(parent::get_parameter('roles'));

    $_SESSION['lti_request'] = $this;
  }

  public function verify() {
    if ($this->verify_launch_request()) {
      if ($this->verify_valid_launch_request()) {
        if ($this->verify_required_parameters()) {
          return true;
        } else {
          error::render(get_string('error_missing_required_params', 'local_lti'));
        }
      } else {
        error::render(get_string('error_auth_failed', 'local_lti'));
      }
    } else {
      error::render(get_string('error_launch_request', 'local_lti'));
    }
  }

  private function verify_launch_request() {
    $ok = true;

    // Check it is a POST request
    $ok = $ok && $_SERVER['REQUEST_METHOD'] === 'POST';

    // Check the LTI message type
    $ok = $ok && !is_null(parent::get_parameter('lti_message_type')) && (parent::get_parameter('lti_message_type') === 'basic-lti-launch-request');

    // Check the LTI version
    $ok = $ok && !is_null(parent::get_parameter('lti_version')) && (parent::get_parameter('lti_version') === 'LTI-1p0');

    // Check a consumer key exists
    $ok = $ok && !is_null(parent::get_parameter('oauth_consumer_key'));

    // Check a resource link ID exists
    $ok = $ok && !is_null(parent::get_parameter('resource_link_id'));

    return $ok;
  }

  private function verify_valid_launch_request() {
    $ok = true;

    // Retrieve array of consumer keys and secrets.
    $tool_consumer_secrets = util::get_enabled_tool_consumer_secrets();

    // Check the consumer key is recognised
    $ok = $ok && array_key_exists(parent::get_parameter('oauth_consumer_key'), $tool_consumer_secrets);

    // Check the OAuth credentials (nonce, timestamp and signature)
    if ($ok) {
      try {
        $store = new oauth\datastore(parent::get_parameter('oauth_consumer_key'), $tool_consumer_secrets[parent::get_parameter('oauth_consumer_key')]);
        $server = new oauth\server($store);
        $method = new oauth\signature_method_HMAC_SHA1();
        $server->add_signature_method($method);
        $server->verify_request($this); // Verify this request.
      } catch (\Exception $e) {
        $ok = false;
      }
    }

    return $ok;
  }

  private function verify_required_parameters() {
    $ok = true;

    // Check for a consumer product family code (Ex. Moodle, Canvas).
    $ok = $ok && !is_null(parent::get_parameter('tool_consumer_info_product_family_code'));

    return $ok;
  }

  public function get_user() {
    return $this->user;
  }

  public function get_resource() {
    return $this->resource;
  }

  private static function get_resource_type() {
    return required_param('type', PARAM_TEXT);
  }

}
