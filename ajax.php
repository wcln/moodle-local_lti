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

    // Retrieve the requested content ID.
    $content_id = $request->get_resource()->get_content_id();

    // Retrieve book id.
    $cm = get_coursemodule_from_id('book', $content_id, 0, false, MUST_EXIST);
    $course = $DB->get_record('course', array('id'=>$cm->course), '*', MUST_EXIST);
    $book = $DB->get_record('book', array('id'=>$cm->instance), '*', MUST_EXIST);

    // Retrieve the lesson to display.
    $lesson = $DB->get_record_sql('SELECT id, title, content
                                   FROM {book_chapters}
                                   WHERE bookid=?
                                   AND pagenum=?
                                   ORDER BY pagenum ASC', array($book->id, $pagenum));

    // Set the outcome content and title to be returned.
    $outcome->content = $lesson->content;
    $outcome->title = $lesson->title;

  } else {
    $outcome->error = get_string('error_session_expired', 'local_lti');
  }

} else {
  $outcome->error = 'Missing parameters. Session ID and page number are both required to retrieve a page.';
}

// Ouput the outcome object as a JSON string.
echo json_encode($outcome);

// All done.
die;
