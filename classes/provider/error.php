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
 * @copyright  2021 Colin Perepelken (colin@lingellearning.com)
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class error
{

    /** @var string[] Error codes and their associated messages */
    const ERROR_CODES
        = [
            'E110' => ''
            // TODO
        ];

    const TABLE = 'local_lti_error_log';

    public static function log(\Exception $exception, $request = null)
    {
        global $DB;

        // TODO
        $DB->insert_record(self::TABLE, (object)[
            'consumer'    => 0,
            'code'        => 0,
            'timecreated' => time(),
        ]);
    }

    /**
     * Renders an error message using a custom tempalte.
     *
     * @param  \Exception  $exception
     *
     * @throws \coding_exception
     */
    public static function render(\Exception $exception)
    {
        global $PAGE;

        // Get the plugin renderer.
        $renderer = $PAGE->get_renderer('local_lti');

        // Render the error.
        $error = new \local_lti\output\error($exception->getMessage());
        echo $renderer->render($error);
    }
}
