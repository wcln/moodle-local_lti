// The page we are currently on (starts at 1).
var currentPage = 1;

// The number of pages.
var numberOfPages = 1;

// Called on body load.
function initialize(page, pageCount) {

  currentPage = page;

  numberOfPages = pageCount;

  // Show the body.
  $(".local_lti_book").fadeIn(600);

  // Remove loading bar.
  $('.local_lti_loading_bar').css("display", "none");

  // Show the page.
  showPage();

  // Update iframe height when the window is resized.
  window.addEventListener("resize", updateIframeHeight);

  // Uncomment to update iframe height every 200ms.
  //window.setInterval(updateIframeHeight, 200);

  // Remove the iframe border (Moodle specific).
  window.parent.postMessage(JSON.stringify({subject: 'lti.removeBorder'}), '*');
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

  // Update the max-height of the dropdown.
  let maxHeight = ($('#page-' + currentPage).outerHeight(false) - 100);
  if (maxHeight < 0) {
    maxHeight = 100;
  }
  $("#page-" + currentPage + " .dropdown-menu").css("max-height", maxHeight + "px");

  // Show/hide next/back buttons.
  updateNavigationButtons();

  // Set the height of the iframe to the height of the new page.
  updateIframeHeight();

  // Scroll to the top of the page.
  window.parent.postMessage(JSON.stringify({subject: 'lti.scrollToTop'}), '*');

}

function updateNavigationButtons() {
  if (currentPage == 1) {
    // Just show the next button
    $(".next-btn").css("visibility", "visible");
    $(".back-btn").css("visibility", "hidden");
  } else if (currentPage === numberOfPages) {
    // Just show the back button
    $(".next-btn").css("visibility", "hidden");
    $(".back-btn").css("visibility", "visible");
  } else {
    // Show both buttons.
    $(".next-btn").css("visibility", "visible");
    $(".back-btn").css("visibility", "visible");
  }
}
