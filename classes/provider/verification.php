<?php

namespace local_lti\provider;
use \local_lti\imsglobal\lti\oauth;
use local_lti\provider\error;

class verification {

  public static function verify_request() {
    if (verification::verify_launch_request()) {
      if (verification::verify_valid_launch_request()) {
        if (verification::verify_required_parameters()) {
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

  private static function verify_launch_request() {
    $ok = true;

    // Check it is a POST request
    $ok = $ok && $_SERVER['REQUEST_METHOD'] === 'POST';

    // Check the LTI message type
    $ok = $ok && isset($_POST['lti_message_type']) && ($_POST['lti_message_type'] === 'basic-lti-launch-request');

    // Check the LTI version
    $ok = $ok && isset($_POST['lti_version']) && ($_POST['lti_version'] === 'LTI-1p0');

    // Check a consumer key exists
    $ok = $ok && !empty($_POST['oauth_consumer_key']);

    // Check a resource link ID exists
    $ok = $ok && !empty($_POST['resource_link_id']);

    return $ok;
  }

  private static function verify_valid_launch_request() {
    $ok = true;

    // Retrieve array of consumer keys and secrets.
    $tool_consumer_secrets = verification::get_enabled_tool_consumer_secrets();

    // Check the consumer key is recognised
    $ok = $ok && array_key_exists($_POST['oauth_consumer_key'], $tool_consumer_secrets);

    // Check the OAuth credentials (nonce, timestamp and signature)
    if ($ok) {
      try {
        $store = new oauth\datastore($_POST['oauth_consumer_key'], $tool_consumer_secrets[$_POST['oauth_consumer_key']]);
        $server = new oauth\server($store);
        $method = new oauth\signature_method_HMAC_SHA1();
        $server->add_signature_method($method);
        $request = oauth\request::from_request();
        $server->verify_request($request);
      } catch (\Exception $e) {
        $ok = false;
      }
    }

    return $ok;
  }

  private static function verify_required_parameters() {
    $ok = true;

    // Check for a consumer product family code (Ex. Moodle, Canvas).
    $ok = $ok && !empty($_POST['tool_consumer_info_product_family_code']);

    // Check that a book ID is set either through GET or POST.
    $ok = $ok && (!empty($_POST['custom_id']) || !empty($_GET['id']));

    return $ok;
  }

  private static function get_all_tool_consumer_secrets() {
    global $DB;

    $tool_consumer_secrets = $DB->get_records_menu('local_lti_consumer', null, '', 'consumer_key, secret');
    return $tool_consumer_secrets;
  }

  private static function get_enabled_tool_consumer_secrets() {
    global $DB;

    $now = date("Y-m-d H:i:s");

    $sql = 'SELECT consumer_key, secret
            FROM {local_lti_consumer}
            WHERE enabled = 1
            AND (enable_from < ? OR enable_from IS NULL)
            AND (enable_until > ? OR enable_until IS NULL)';

    $tool_consumer_secrets = $DB->get_records_sql_menu($sql, array($now, $now));
    return $tool_consumer_secrets;
  }

}
