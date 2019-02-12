<?php

namespace local_lti\provider;
use \local_lti\imsglobal\lti\oauth;

class verification {

  public static function verify_request() {
    if (verification::verify_launch_request()) {
      if (verification::verify_valid_launch_request()) {
        if (verification::verify_required_parameters()) {
          return true;
        } else {
          echo "Error: Missing required parameters!";
        }
      } else {
        echo "Error: Authentication failed! Not a valid launch request.";
      }
    } else {
      echo "Error: Not an LTI launch request.";
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

    // TODO: create lti database table and retrieve array of consumer keys and secrets.
    $tool_consumer_secrets = array('wcln' => 'testsecret');

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
      } catch (Exception $e) {
        $ok = false;
        echo $e; // temp.
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

}
