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
