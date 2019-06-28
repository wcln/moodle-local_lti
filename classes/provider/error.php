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
     *
     * @param string $message The error message to show the user.
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
