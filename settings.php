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
 * Create a settings page for the plugin.
 *
 * @package    local_lti
 * @copyright  2019 Colin Bernard {@link https://wcln.ca}
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

defined('MOODLE_INTERNAL') || die();

$settings = null;

// Ensure the configurations for this site are set.
if ($hassiteconfig) {

	// Create the new settings page.
	$settings = new admin_settingpage('local_lti', get_string('pluginname', 'local_lti'));
	$ADMIN->add('localplugins', $settings);

	$settings->add( new admin_setting_configtext(
		 'local_lti/book_toolurl',
		 'Book Tool URL',
		 'The tool url of the WCLN Book preconfigured tool.',
		 'https://wcln.ca/local/lti/index.php?type=book',
		 PARAM_TEXT
	));

  $settings->add( new admin_setting_configtext(
     'local_lti/page_toolurl',
     'Page Tool URL',
     'The tool url of the WCLN Page preconfigured tool.',
     'https://wcln.ca/local/lti/index.php?type=page',
     PARAM_TEXT
  ));

  $settings->add( new admin_setting_configtext(
     'local_lti/book_toolid',
     'Book Tool ID',
     'The tool ID of the WCLN Book preconfigured tool.',
     19,
     PARAM_INT
  ));

  $settings->add( new admin_setting_configtext(
     'local_lti/page_toolid',
     'Page Tool ID',
     'The tool ID of the WCLN Page preconfigured tool.',
     20,
     PARAM_INT
  ));

}
