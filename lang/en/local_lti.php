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
 * English Language strings.
 *
 * @package    local_lti
 * @copyright  2019 Colin Bernard {@link https://wcln.ca}
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

// General.
$string['pluginname']     = "WCLN LTI Provider";
$string['brand_heading']  = 'Western Canadian Learning Network';
$string['brand_short']    = 'WCLN';
$string['update_toolurl'] = 'Update LTI toolurl Field';

// Errors.
$string['error_heading']                 = 'LTI Error';
$string['error_footer']                  = 'Contact a course administrator, teacher or wcln.ca to fix this issue.';
$string['error_invalid_type']            = 'Invalid LTI type in launch URL. Try ?type=book or ?type=page.';
$string['error_missing_required_params'] = 'Missing required parameters!';
$string['error_auth_failed']             = 'Authentication failed! Not a valid launch request.';
$string['error_launch_request']          = 'Not an LTI launch request.';
$string['error_rendering_book']          = 'An error occurred while rendering the LTI book. Ensure that the ID you provided is correct.';
$string['error_rendering_page']          = 'An error occurred while rendering the LTI page. Ensure that the ID you provided is correct.';
$string['error_book_id']                 = 'An error occurred while attempting to retrieve the book id. Check that the id you are providing is correct.';
$string['error_page_id']                 = 'An error occurred while attempting to retrieve the page id. Check that the id you are providing is correct.';
$string['error_missing_type']            = 'LTI type parameter is missing from the launch URL. Try ?type=book or ?type=page.';
$string['error_verification']            = 'The request could not be verified.';
$string['error_session_not_found']       = 'The previous session could not be recovered. Unable to link resource without the request. Try reloading this page.';
$string['error_retrieving_book_page']    = 'An error occurred while attempting to retrieve the book page from the database.';
$string['error_session_expired']         = 'Session expired. Try reloading this page.';

// Resource not configured.
$string['resource_not_setup_heading']     = 'LTI Resource Not Configured';
$string['resource_not_setup_description'] = 'A book ID or page ID custom parameter has not been configured for this resource. Contact a course teacher or administrator to set up this LTI resource.';

// Book.
$string['table_of_contents'] = 'Table of Contents';

// Loading.
$string['loading'] = 'Loading...';
