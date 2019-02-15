function init() {
  window.addEventListener("resize", updateIframeHeight);
  updateIframeHeight();
}


function updateIframeHeight() {
  // Calculate height of current page content.
  let height = $('body').outerHeight(false);

  // Moodle.
  // TODO check if moodle or canvas.
  window.parent.postMessage(height, "*");

  // Canvas.
  window.parent.postMessage(JSON.stringify({subject: 'lti.frameResize', height: height}), '*');
}
