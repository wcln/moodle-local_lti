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
/**
 * Scheduled task to update a toolurl field.
 *
 * @package    local_lti
 * @copyright  2019 Colin Bernard {@link https://wcln.ca}
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

namespace local_lti\task;

defined('MOODLE_INTERNAL') || die();

// Require the config file for DB calls.
require_once(__DIR__ . '/../../../../config.php');

/**
 * Update the toolurl field in mdl_lti.
 * This field needs to be set to correctly backup/restore custom LTI courses.
 */
class update_toolurl extends \core\task\scheduled_task {

  /**
  * Return the task's name as shown in admin screens.
  *
  * @return string
  */
  public function get_name() {
    return get_string('update_toolurl', 'local_lti');
  }

  /**
   * Execute the task.
   */
  public function execute() {
    global $DB, $CFG;

    // Access the plugin configuration,
    $config = get_config('local_lti');

    // Update the toolurl for books.
    $DB->execute('UPDATE mdl_lti SET toolurl=? WHERE typeid=?', array($config->book_toolurl, $config->book_typeid));
    mtrace("Updated toolurl for LTI Books.");

    // Update the toolurl for pages.
    $DB->execute('UPDATE mdl_lti SET toolurl=? WHERE typeid=?', array($config->page_toolurl, $config->page_typeid));
    mtrace("Updated toolurl for LTI Pages.");

    mtrace("Done.");
  }
}
