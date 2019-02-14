<?php

use local_lti\provider\request;
use local_lti\provider\resource;

// Require standard Moodle configuration file.
require_once(__DIR__ . '/../../config.php');

// Set page context.
$PAGE->set_context(context_system::instance());

if ($content_id = optional_param('content_id', false, PARAM_INT)) {

  // Retrieve previous request.
  $request = $_SESSION['lti_request'];

  // Create a resource link using the content id.
  $request->get_resource()->create_link($content_id);

} else {
  // Initialize the request.
  $request = new request();
}


// Verify the request.
if ($request->verify()) {

  // Get the requested resource.
  $resource = $request->get_resource();

  // Check if the resource is linked in the lti database already.
  if ($resource->is_linked()) {

    // Check if the resource is enabled.
    if ($resource->is_share_approved()) {

      // Render the resource.
      $resource->render();

    } else {
      throw new Exception('not shared');
    }

  } else if ($request->get_user()->is_teacher() || $request->get_user()->is_content_developer()) {

    // Render form for teacher/admin to enter content ID.
    $renderer = $PAGE->get_renderer('local_lti');
    echo $renderer->render_resource_form(null);

  } else {

    // Render 'Not set up yet' template.
    // $renderer = $PAGE->get_renderer('local_lti');
    // $not_setup = new \local_lti\output\not_setup(null);
    // echo $renderer->render($not_setup);

  }
} else if (false) { // TODO if resource form was submitted.

  // create_link.

} else {
  // Render the verification error.
  throw new Exception('verification error');
}
