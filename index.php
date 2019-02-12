<?php

use local_lti\provider\verification;
use local_lti\provider\book_provider;
use local_lti\provider\page_provider;
use local_lti\provider\error;

// Temp.
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Require standard Moodle configuration file.
require_once(__DIR__ . '/../../config.php');

// Set page context.
$PAGE->set_context(context_system::instance());

// Get the plugin renderer.
$renderer = $PAGE->get_renderer('local_lti');

// Retrieve redirect URL for routing.
// $request = $_SERVER['REDIRECT_URL'];
$request = substr($_SERVER['REQUEST_URI'], strrpos($_SERVER['REQUEST_URI'], '/')); // Temp... waiting for sub domain. Uncomment above once set up.
$request = "test";
switch ($request) {

  // Consumer is requesting an LTI book.
  case '/book':

    // Check if the request is valid.
    if (verification::verify_request()) {

      // Render the book.
      $book = new \local_lti\output\book(book_provider::get_book_id());
      echo $renderer->render($book);
    }

    break;

  // Consumer is requesting an LTI page.
  case '/page':

    // Check if the request is valid.
    if (verification::verify_request()) {

      // Render the page.
      $page = new \local_lti\output\page(page_provider::get_page_id());
      echo $renderer->render($page);
    }

    break;

  default:
    error::display('Invalid LTI type. Try lti.wcln.ca/book or lti.wcln.ca/page');
    break;
}
