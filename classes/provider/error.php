<?php

namespace local_lti\provider;

/**
 * Error
 *
 * Represents a custom LTI provider error.
 *
 * @package    local_lti
 * @copyright  2019 Colin Bernard
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class error {

  /**
   * Renders an error message using a custom tempalte.
   * @param  string $message The error message to show the user.
   */
  public static function render($message) {
    global $PAGE;

    // Get the plugin renderer.
    $renderer = $PAGE->get_renderer('local_lti');

    // Render the error.
    $error = new \local_lti\output\error($message);
    echo $renderer->render($error);

  }
}
