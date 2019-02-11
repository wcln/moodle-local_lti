<?php

$ok = check_if_launch_request();

if ($ok) {
  $ok = check_required_parameters();

  if ($ok) {
    echo 'Good to go, here is your book.';
  } else {
    echo 'Missing required parameters!';
  }

} else {
  echo 'Not an LTI launch request';
}



function check_if_launch_request() {
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

function check_required_parameters() {
  $ok = true;

  // Check for a consumer product family code (Ex. Moodle, Canvas).
  $ok = $ok && !empty($_POST['tool_consumer_info_product_family_code']);

  // Check that a book ID is set either through GET or POST.
  $ok = $ok && (!empty($_POST['custom_id']) || !empty($_GET['book_id']));

  return $ok;
}
