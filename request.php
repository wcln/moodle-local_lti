<?php

require_once(__DIR__ . '/../../config.php'); // standard config file

// Ensure the key is set.
if (isset($_POST['oauth_consumer_key'])) {
  if ($_POST['oauth_consumer_key'] === "consumerkey") {

    // Ensure a course module id has been provided.
    $id = null;
    if ($_POST['tool_consumer_info_product_family_code'] === "moodle") {
      if (isset($_POST['custom_id'])) {
        $id = $_POST['custom_id'];
      }
    } else if ($_POST['tool_consumer_info_product_family_code'] === "canvas") {
      if (isset($_GET['book_id'])) {
        $id = $_GET['book_id'];
      }
    }

    $cm = get_coursemodule_from_id('book', $id, 0, false, MUST_EXIST);
    $course = $DB->get_record('course', array('id'=>$cm->course), '*', MUST_EXIST);
    $book = $DB->get_record('book', array('id'=>$cm->instance), '*', MUST_EXIST);

    // Query the database for all lessons in the book, using the book id.
    $lessons = $DB->get_records_sql('SELECT id, pagenum, title, content FROM {book_chapters} WHERE bookid=? ORDER BY pagenum ASC', array($book->id));

    // Replace characters to enable MathJax to filter WIRIS XML.
    foreach ($lessons as $lesson) {
      $lesson->content = str_replace('«', '<', $lesson->content);
      $lesson->content = str_replace('»', '>', $lesson->content);
      $lesson->content = str_replace('§', '&', $lesson->content);
      $lesson->content = str_replace('¨', '"', $lesson->content);
      $lesson->content = str_replace('´', "'", $lesson->content);
    }
  }
}



// Build HTML page
?>

<html>
<head>
  <!-- Latest compiled and minified CSS -->
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
  <!-- jQuery library -->
  <script src="https://bclearningnetwork.com/lib/jquery/jquery-3.2.1.min.js"></script>
  <!-- Latest compiled JavaScript -->
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
  <!-- Custom stylesheet to style this page. -->
  <link rel="stylesheet" type="text/css" href="style.css">

  <script type="text/x-mathjax-config">
    MathJax.Hub.Config({
      MathML: {
        extensions: ["content-mathml.js"]
      }
    });
  </script>

  <!-- MathJax filter. -->
  <script src='https://cdnjs.cloudflare.com/ajax/libs/mathjax/2.7.5/latest.js?config=TeX-MML-AM_CHTML' async></script>

  <!-- Custom script to support navigation between book pages and calls for iframe resizing. -->
  <script src="navigation.js"></script>

  <title>WCLN</title>

</head>

<body onload="initialize()">
  <div id="bcln-body" class="container-left">
    <div class="row">
      <?php foreach ($lessons as $lesson): ?>
        <div class="col-md-9 lti-page" id="page-<?=$lesson->pagenum?>">
          <h3><?=$lesson->title?></h3>
          <hr>
          <div id="content"><?=$lesson->content?></div>
        </div>
      <?php endforeach ?>
      <aside class="col-md-3" id="table-of-contents">
        <div>
          <h2>Table of Contents</h2>
          <img src="images/wcln_logo_light.png">
        </div>
        <ul>
          <?php foreach ($lessons as $lesson): ?>
            <li><a class="toc-item" id="toc-<?=$lesson->pagenum?>" onclick="navigate(<?=$lesson->pagenum?>)"><?=$lesson->title?></a></li>
          <?php endforeach ?>
        </ul>
      </aside>
    </div>
    <div class="row">
      <div class="col-md-3">
        <!--<img src="images/wcln_logo.png" alt="logo">-->
      </div>
      <div class="col-md-6 text-center">
      </div>
      <div class="col-md-3 text-right">
        <a class="back round lti-nav" id="backBtn" onclick="back()">&#8249;</a>
        <a class="next round lti-nav" id="nextBtn" onclick="next()">&#8250;</a>
      </div>
    </div>
  </div>
</body>
</html>
