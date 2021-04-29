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
class error extends \Exception
{

    /** Error codes */
    const ERROR_UNKNOWN = 'E000';
    const ERROR_MISSING_PARAMS = 'E001';
    const ERROR_INVALID_TYPE = 'E002';
    const ERROR_AUTH_FAILED = 'E003';
    const ERROR_LAUNCH_REQUEST = 'E004';
    const ERROR_RENDERING_BOOK = 'E005';
    const ERROR_RENDERING_PAGE = 'E006';
    const ERROR_BOOK_ID = 'E007';
    const ERROR_PAGE_ID = 'E008';
    const ERROR_MISSING_TYPE = 'E009';
    const ERROR_VERIFICATION = 'E010';
    const ERROR_MISSING_SESSION = 'E011';
    const ERROR_BOOK_CHAPTER = 'E012';
    const ERROR_SESSION_EXPIRED = 'E013';
    const ERROR_CONSUMER_KEY = 'E014';

    /** @var string[] Error codes and their associated messages */
    const ERROR_MESSAGES
        = [
            self::ERROR_UNKNOWN         => 'Unknown error code',
            self::ERROR_MISSING_PARAMS  => 'Missing required LTI parameters!',
            self::ERROR_INVALID_TYPE    => 'Invalid LTI type in launch URL. Try ?type=book or ?type=page',
            self::ERROR_AUTH_FAILED     => 'Authentication failed. This is not a valid launch request. Try reloading this page.',
            self::ERROR_LAUNCH_REQUEST  => 'This is not a valid launch request.',
            self::ERROR_RENDERING_BOOK  => 'An error ocurred while attempting to render the LTI book. Ensure that the ID provided is correct.',
            self::ERROR_RENDERING_PAGE  => 'An error ocurred while rendering the LTI page. Ensure that the ID provided is correct.',
            self::ERROR_BOOK_ID         => 'An error ocurred while attempting to retrieve the LTI book activity. Check that the book ID is correct.',
            self::ERROR_PAGE_ID         => 'An error ocurred while attempting to retrieve the LTI page activity. Check that the page ID is correct.',
            self::ERROR_MISSING_TYPE    => 'The LTI \'type\' parameter is missing from the launch URL. Try add ?type=book or ?type=page.',
            self::ERROR_VERIFICATION    => 'The request could not be verified.',
            self::ERROR_MISSING_SESSION => 'The previous session could not be recovered. Try reloading this page.',
            self::ERROR_BOOK_CHAPTER    => 'An error ocurred while attempting to retrieve the book chapter from the database.',
            self::ERROR_SESSION_EXPIRED => 'Invalid consumer key.',
        ];

    const TABLE = 'local_lti_error_log';

    /** @var mixed|null What consumer is this error linked to? This is the ID */
    protected $consumer;

    /** @var mixed|string We need to use a custom_code because built in 'code' expects a long, and we use strings */
    protected $custom_code;

    /** @var mixed If this is set, then the message does not exist in ERROR_MESSAGES, and we should make sure to store it in the database */
    protected $custom_message;

    public function __construct($code, $message = null, $consumer = null, $previous = null)
    {
        if (empty($code)) {
            $code = self::ERROR_UNKNOWN;
        }
        $this->custom_code = $code;

        $this->consumer = $consumer;

        if (empty($message)) {
            if (isset(self::ERROR_MESSAGES[$code])) {
                $message = self::ERROR_MESSAGES[$code];
            } else {
                $message = "Unrecognized error, please contact a site administrator with error code '$code'";
            }
        } else {
            // This means we aren't using one of the messages above, so we should store this message in the database
            $this->custom_message = $message;
        }


        parent::__construct($message, null, $previous);
    }

    /**
     * Save the error in the database
     *
     * @throws \dml_exception
     */
    public function log()
    {
        global $DB;

        $DB->insert_record(self::TABLE, (object)[
            'consumer'    => $this->consumer,
            'code'        => $this->custom_code,
            'message'     => empty($this->custom_message) ? null : $this->custom_message,
            'timecreated' => time(),
        ]);
    }

    /**
     * Render this error using a template
     *
     * @throws \coding_exception|\dml_exception
     */
    public function render()
    {
        global $PAGE;

        // Whenever this error is shown to the user, let's log it
        $this->log();

        // Get the plugin renderer.
        $renderer = $PAGE->get_renderer('local_lti');

        // Render the error.
        $error = new \local_lti\output\error($this->getMessage(), $this->custom_code);
        echo $renderer->render($error);
    }

    public function getCustomCode() {
        return $this->custom_code;
    }
}
