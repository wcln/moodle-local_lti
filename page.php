<?php

require_once(__DIR__ . '/../../config.php');

$id = $_POST['custom_id'];

if (!$cm = get_coursemodule_from_id('page', $id)) {
     print_error('invalidcoursemodule');
 }
$page = $DB->get_record('page', array('id'=>$cm->instance), '*', MUST_EXIST);


?>
<html>
<head>
  <!-- jQuery library -->
  <script src="https://bclearningnetwork.com/lib/jquery/jquery-3.2.1.min.js"></script>
  <script>

  function init() {
    window.addEventListener("resize", updateIframeHeight);
    updateIframeHeight();
  }


  function updateIframeHeight() {
    window.parent.postMessage($('body').outerHeight(false) + 10, "*");
  }

  </script>
</head>
<body onload="init()">
<?php echo $page->content; ?>
</body>
</html>
