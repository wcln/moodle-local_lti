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
/**
 * AJAX script to handle LTI page loading.
 *
 * @package    local_lti
 * @copyright  2019 Colin Bernard {@link https://wcln.ca}
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

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
    $outcome->content = format_text($lesson->content, $lesson->contentformat, array('noclean'=>true, 'overflowdiv'=>true, 'context'=>$request->get_resource()->get_context()));
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
