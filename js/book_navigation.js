var currentPage = 1;
var numberOfPages = 1;

// Called on body load.
function initialize() {

  showFirstPage();
  $("#wcln-body").css("visibility", "visible");

  window.addEventListener("resize", updateIframeHeight);
}

function updateIframeHeight() {
  window.parent.postMessage($('#wcln-body').outerHeight(false), "*");
}

function updateTableOfContents() {
  $(".toc-item").css("font-weight", "normal");
  $("#toc-" + currentPage).css("font-weight", "bold");
  $("#table-of-contents li").removeClass("current");
  $("#toc-" + currentPage).parent().addClass("current");
}

function showFirstPage() {
  numberOfPages = $(".lti-page").length;

  $(".lti-page").css("display", "none"); // Hide all pages.
  $("#page-1").css("display", "inline-block"); // Show the first page.

  updateNavigationButtons();
  updateTableOfContents();
  updateIframeHeight();
}

function back() {
  $(".lti-page").css("display", "none"); // Hide all pages.
  currentPage--;
  $("#page-" + currentPage).css("display", "inline-block"); // Show the current page.

  updateNavigationButtons();
  updateTableOfContents();
  updateIframeHeight();
}

function next() {
  $(".lti-page").css("display", "none"); // Hide all pages.
  currentPage++;
  $("#page-" + currentPage).css("display", "inline-block"); // Show the current page.

  updateNavigationButtons();
  updateTableOfContents();
  updateIframeHeight();
}

function navigate(pageNumber) {
  if (pageNumber > 0 && pageNumber <= numberOfPages) {
    currentPage = pageNumber;
    $(".lti-page").css("display", "none"); // Hide all pages.
    $("#page-" + currentPage).css("display", "inline-block"); // Show the current page.
  }

  updateNavigationButtons();
  updateTableOfContents();
  updateIframeHeight();
}

function updateNavigationButtons() {
  if (currentPage == 1) {
    // Just show the next button
    $("#nextBtn").css("visibility", "visible");
    $("#backBtn").css("visibility", "hidden");
  } else if (currentPage === numberOfPages) {
    // Just show the back button
    $("#backBtn").css("visibility", "visible");
    $("#nextBtn").css("visibility", "hidden");
  } else {
    // Show both buttons
    $("#nextBtn").css("visibility", "visible");
    $("#backBtn").css("visibility", "visible");
  }
}
