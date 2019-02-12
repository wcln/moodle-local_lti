<?php

namespace local_lti\provider;

class error {

  public static function display($message) {
    global $PAGE;

    // Get the plugin renderer.
    $renderer = $PAGE->get_renderer('local_lti');

    // Render the error.
    $error = new \local_lti\output\error($message);
    echo $renderer->render($error);

  }

}
