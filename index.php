<?php

use local_lti\provider\request;
use local_lti\provider\error;

// Cancel any existing session.
session_start();
$_SESSION = array();
session_destroy();
session_start();

// Catch all exceptions and render them using a custom template.
try {
  // Require standard Moodle configuration file.
  require_once(__DIR__ . '/../../config.php');

  // Set page context.
  $PAGE->set_context(context_system::instance());

  // Get the page renderer.
  $renderer = $PAGE->get_renderer('local_lti');

  // Check for a page number.
  $pagenum = optional_param('page', null, PARAM_INT);

  // Check if content id was set.
  if ($content_id = optional_param('content_id', false, PARAM_INT)) {

    // Retrieve previous request.
    if (isset($_SESSION['lti_request'])) {
        $request = $_SESSION['lti_request'];
    } else {
      throw new Exception(get_string('error_session_not_found', 'local_lti'));
    }

    // Verify the request.
    // Otherwise anyone could create a resource link using the above optional parameter.
    if ($request->verify()) {
      // Create a resource link using the content id.
      $request->get_resource()->create_link($content_id);
    }

  } else {
    // Initialize the request.
    $request = new request();
  }

  // Verify the request.
  if ($request->verify()) {

    // Store the request if it is verified.
    $_SESSION['lti_request'] = $request;

    // Get the requested resource.
    $resource = $request->get_resource();

    // Check for a page number.
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
  } else {
    // Render the verification error.
    throw new Exception(get_string('error_verification', 'local_lti'));
  }
} catch (Exception $e) {
  // Catch all exceptions and render them using a custom template.
  error::render($e->getMessage());
}
