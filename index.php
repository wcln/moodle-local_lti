<?php

use local_lti\provider\request;
use local_lti\provider\error;
use local_lti\provider\util;

// Catch all exceptions and render them using a custom template.
try {
  // Require standard Moodle configuration file.
  require_once(__DIR__ . '/../../config.php');

  // Set page context.
  $PAGE->set_context(context_system::instance());

  // Get the page renderer.
  $renderer = $PAGE->get_renderer('local_lti');

  // Check if we have an existing session.
  // Supports non-AJAX page changing.
  if ($session_id = optional_param('sessid', false, PARAM_TEXT)) {
    if (isset($SESSION->{"lti_request_$session_id"})) {
      // Load the existing request (we know it has already been verified).
      $request = $SESSION->{"lti_request_$session_id"};

      // Check if content id was set.
      if ($content_id = optional_param('content_id', false, PARAM_INT)) {

        // Create a resource link using the content id.
        $request->get_resource()->create_link($content_id);

      }
    } else {
      // There is a sessid parameter, but it does not exist in the global SESSION.
      throw new Exception(get_string('error_session_expired', 'local_lti'));
    }
  } else {
    // Initialize the request.
    $request = new request();

    // Verify the request.
    // Will throw an exception if not verified.
    $request->verify();

    // Generate a random session ID.
    $session_id = util::generate_random_session_id();

    // Store the session ID in the request.
    $request->set_session_id($session_id);

    // Store the request in the global session variable using the random session ID.
    $SESSION->{"lti_request_$session_id"} = $request;
  }

  // Get the requested resource.
  $resource = $request->get_resource();

  // Check for a page number.
  // Supports non-AJAX page navigation.
  if ($pagenum = optional_param('pagenum', false, PARAM_INT)) {
    // Set the page number of the resource to retrieve.
    $resource->set_pagenum($pagenum);
  }

  // Check if the resource is linked in the lti database already.
  if ($resource->is_linked()) {

    // Check if the resource is enabled.
    if ($resource->is_share_approved()) {

      // Render the resource.
      $resource->render();

    } else {
      throw new Exception(get_string('error_not_shared', 'local_lti'));
    }

  } else if ($request->get_user()->is_teacher() || $request->get_user()->is_content_developer()) {

    // Render form for teacher/admin to enter content ID.
    echo $renderer->render_resource_form(null);

  } else {

    // Render 'Not set up yet' template.
    echo $renderer->render_resource_not_setup(null);

  }

} catch (Exception $e) {
  // Catch all exceptions and render them using a custom template.
  error::render($e->getMessage());
}
