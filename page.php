<?php

require_once(__DIR__ . '/../../config.php');

$id = $_POST['custom_id'];

if (!$cm = get_coursemodule_from_id('page', $id)) {
     print_error('invalidcoursemodule');
 }
$page = $DB->get_record('page', array('id'=>$cm->instance), '*', MUST_EXIST);

echo $page->content;
