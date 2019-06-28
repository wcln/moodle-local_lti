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
 * Handles page loading within a Book and sending LTI messages.
 *
 * @package    local_lti
 * @copyright  2019 Colin Bernard {@link https://wcln.ca}
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

// The page we are currently on (starts at 1).
var currentPage = 1;

// The number of pages.
var numberOfPages = 1;

// Called on body load.
function initialize(page, pageCount) {

    // Delay to show loading animation longer.
    setTimeout(function () {
        currentPage = page;

        numberOfPages = pageCount;

        // Remove loading animation.
        $('.local_lti_loader').css("display", "none");

        // Show the body.
        $(".local_lti_book").fadeIn(600);

        // Show the page.
        showPage();

        // Update iframe height when the window is resized.
        window.addEventListener("resize", updateIframeHeight);

        // Uncomment to update iframe height every 200ms.
        //window.setInterval(updateIframeHeight, 200);

        // Remove the iframe border (Moodle specific).
        window.parent.postMessage(JSON.stringify({subject: 'lti.removeBorder'}), '*');
    }, 400);

}

function updateIframeHeight() {

    // Calculate height of current page content.
    let height = $('.local_lti_book').outerHeight(false);

    // Send message to LMS to resize the iframe.
    window.parent.postMessage(JSON.stringify({subject: 'lti.frameResize', height: height}), '*');

}

function showPage() {

    // Remove the active class from all pages in TOC.
    $(".dropdown-item").removeClass("active");

    // Add the active class to the current page in TOC.
    $(".dropdown-" + currentPage).addClass("active");

    // Set the height of the iframe to the height of the new page.
    updateIframeHeight();

    // Update the max-height of the dropdown.
    let maxHeight = ($('.local_lti_book').outerHeight(false) - 50);
    if (maxHeight < 0) {
        maxHeight = 100;
    }
    $(".local_lti_book .dropdown-menu").css("max-height", maxHeight + "px");

    // Show/hide next/back buttons.
    updateNavigationButtons();

    // Scroll to the top of the page.
    window.parent.postMessage(JSON.stringify({subject: 'lti.scrollToTop'}), '*');

}

function navigate(page, sessionId) {
    $.ajax({
        url: "ajax.php",
        method: "POST",
        data: {page: page, sessid: sessionId}
    }).done(function (response) {

        if (response['success'] == true) {
            // Update current page.
            currentPage = page;

            // Load page content.
            $('.local_lti_book .lti-page .content').html(response['content']);

            // Set title.
            $('.local_lti_book .lti-page .navbar-brand .title').html(response['title']);

            // Updates iframe height and updates navigation buttons.
            showPage();
        } else {
            console.log('Error loading page.'); // TODO get_string.
        }

    }).fail(function () {
        console.log('AJAX error.'); // TODO get_string.
    })
}

function nextPage(sessionId) {
    navigate(currentPage + 1, sessionId);
}

function previousPage(sessionId) {
    navigate(currentPage - 1, sessionId);
}

function updateNavigationButtons() {
    if (currentPage == 1) {
        // Just show the next button
        $(".next-btn").css("visibility", "visible");
        $(".next-btn").css("display", "inline");
        $(".back-btn").css("visibility", "hidden");

        // Hide the back to course button.
        $('.back-to-course-btn').css('display', 'none');
    } else if (currentPage === numberOfPages) {
        // Just show the back button
        $(".next-btn").css("display", "none");
        $(".back-btn").css("visibility", "visible");

        // Show the back to course button.
        $('.back-to-course-btn').css('display', 'inline');
    } else {
        // Show both buttons.
        $(".next-btn").css("visibility", "visible");
        $(".next-btn").css("display", "inline");
        $(".back-btn").css("visibility", "visible");

        // Hide the back to course button.
        $('.back-to-course-btn').css('display', 'none');
    }
}
