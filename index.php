<?php

use local_lti\provider\verification;
use local_lti\provider\book_provider;
use local_lti\provider\page_provider;

// Temp.
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Require standard Moodle configuration file.
require_once(__DIR__ . '/../../config.php');

// Set page context.
$PAGE->set_context(context_system::instance());

// $PAGE->requires->js(new moodle_url("js/navigation.js"));

// Get the plugin renderer.
$renderer = $PAGE->get_renderer('local_lti');

// Check if the request is valid.
if (verification::verify_request()) {

  $is_book = false;
  $is_page = true;

  if ($is_book) {
    // Render the book.
    $book = new \local_lti\output\book(book_provider::get_book_id());
    echo $renderer->render($book);
  } else if ($is_page) {
    // Render the page.
    $page = new \local_lti\output\page(page_provider::get_page_id());
    echo $renderer->render($page);
  } else {
    echo "Invalid type.";
  }


}
