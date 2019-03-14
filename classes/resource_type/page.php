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
