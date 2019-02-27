<?php

define('AJAX_SCRIPT', true);

// Require standard Moodle configuration file.
require_once(__DIR__ . '/../../config.php');

// The outcome object to be returned.
$outcome = new stdClass;
$outcome->success = false;
$outcome->error = null;

// Check if the session ID parameter was provided.
if (($session_id = optional_param('sessid', false, PARAM_TEXT)) && ($pagenum = optional_param('page', false, PARAM_INT))) {

  // Check if a request with this session ID exists.
  if (isset($SESSION->{"lti_request_$session_id"})) {

    // Load the existing request (we know it has already been verified).
    $request = $SESSION->{"lti_request_$session_id"};

    // Retrieve the lesson.
    $lesson = $request->get_resource()->get_lesson($pagenum);
    $outcome->lesson = $lesson;

    // Set the outcome content and title to be returned.
    $outcome->content = $lesson->content;
    $outcome->title = $lesson->title;
    $outcome->success = true;

  } else {
    $outcome->error = get_string('error_session_expired', 'local_lti');
  }

} else {
  $outcome->error = get_string('error_missing_required_params', 'local_lti');
}

// Ouput the outcome object as a JSON string.
echo json_encode($outcome);

// All done.
die;
