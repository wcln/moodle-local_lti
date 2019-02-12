function init() {
  window.addEventListener("resize", updateIframeHeight);
  updateIframeHeight();
}


function updateIframeHeight() {
  window.parent.postMessage($('body').outerHeight(false) + 10, "*");
}
