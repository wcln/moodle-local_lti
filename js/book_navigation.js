// The page we are currently on (starts at 1).
var currentPage = 1;

// The number of pages.
var numberOfPages = 1;

// Called on body load.
function initialize() {

  // Show the first page.
  showFirstPage();

  // Show the body.
  $("body").css("visibility", "visible");

  // Udate iframe height when the window is resized.
  window.addEventListener("resize", updateIframeHeight);
}

function updateIframeHeight() {

  // Calculate height of current page content.
  let height = $('#page-' + currentPage).outerHeight(false);

  // Moodle.
  // TODO check if moodle or canvas.
  // window.parent.postMessage(height, "*");

  // Canvas.
  window.parent.postMessage(JSON.stringify({subject: 'lti.frameResize', height: height}), '*');
}

function showFirstPage() {
  numberOfPages = $(".lti-page").length;
  navigate(1);
}

function back(pageNumber) {
  navigate(--pageNumber);
}

function next(pageNumber) {
  navigate(++pageNumber);
}

function navigate(pageNumber) {
  if (pageNumber > 0 && pageNumber <= numberOfPages) {

    // Update the current page.
    currentPage = pageNumber;

    // Hide all pages.
    $(".lti-page").css("display", "none");

    // Show the current page.
    $("#page-" + currentPage).css("display", "inline-block");

    // Remove the active class from all pages in TOC.
    $(".dropdown-item").removeClass("active");

    // Add the active class to the current page in TOC.
    $(".dropdown-" + currentPage).addClass("active");
  }

  // Show/hide next/back buttons.
  updateNavigationButtons();

  // Set the height of the iframe to the height of the new page.
  updateIframeHeight();
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
