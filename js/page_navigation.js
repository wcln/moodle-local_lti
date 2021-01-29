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
 * Handles sending LTI messages to resize the iframe from within an LTI page.
 *
 * @package    local_lti
 * @copyright  2021 Colin Perepelken (colin@lingellearning.com) {@link https://wcln.ca}
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

function init() {

    $('.local_lti_page').fadeIn(800);

    window.addEventListener("resize", updateIframeHeight);
    updateIframeHeight();
}


function updateIframeHeight() {
    // Calculate height of current page content.
    let height = $('html').outerHeight(false);

    // Send message to LMS to resize the iframe.
    window.parent.postMessage(JSON.stringify({subject: 'lti.frameResize', height: height}), '*');

    // Remove the iframe border (Moodle specific).
    window.parent.postMessage(JSON.stringify({subject: 'lti.removeBorder'}), '*');
}
