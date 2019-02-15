
var width = 1;

function updateLoadingBar(totalPages) {
  width += ((1 / totalPages) * 100);
  $('.local_lti_loading_bar .bar').css("width", width + "%");
}
