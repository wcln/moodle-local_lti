<?php

use local_lti\provider\verification;

// temp
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Require standard Moodle configuration file.
require_once(__DIR__ . '/../../config.php');

if (verification::verify_request()) {
  echo "Rendering book";
}
