<?php

namespace local_lti\resource_type;
use local_lti\provider\resource;

/**
 * Page
 *
 * Represents a Moodle Page.
 * Contains custom render code.
 *
 * @package    local_lti
 * @copyright  2019 Colin Bernard
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class page extends resource {

  /**
   * Renders the page using a template.
   */
  public function render() {
    global $PAGE;

    // Ensure this resource exists in the local_lti_resource_link table, and update it.
    parent::update_link();

    // Get the plugin renderer.
    $renderer = $PAGE->get_renderer('local_lti');

    // Retrieve page ID.
    $cm = get_coursemodule_from_id('page', $this->content_id);

    // Render page.
    $page = new \local_lti\output\page($cm->instance);
    echo $renderer->render($page);
  }
}
