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
 * File to receive LTI requests.
 *
 * @package    local_lti
 * @copyright  2019 Colin Bernard {@link https://wcln.ca}
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

use local_lti\provider\request;
use local_lti\provider\error;
use local_lti\provider\util;

// Catch all exceptions and render them using a custom template.
try {
    // Require standard Moodle configuration file.
    require_once(__DIR__ . '/../../config.php');

    // Set page context.
    $PAGE->set_context(context_system::instance());

    // Get the page renderer.
    $renderer = $PAGE->get_renderer('local_lti');

    // Initialize the request.
    $request = new request();

    // Verify the request.
    // Will throw an exception if not verified.
    $request->verify();

    // Generate a random session ID.
    $session_id = util::generate_random_session_id();

    // Store the session ID in the request.
    $request->set_session_id($session_id);

    // Store the request in the global session variable using the random session ID.
    // Subsequent page loading within the book will be handled via AJAX calls.
    $SESSION->{"lti_request_$session_id"} = $request;

    // Get the requested resource.
    $resource = $request->get_resource();

    // Render the resource.
    $resource->render();

} catch (Exception $e) {
    // Catch all exceptions and render them using a custom template.
    error::render($e->getMessage());
}
