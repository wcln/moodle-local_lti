<?php

namespace local_lti\provider;
use \local_lti\imsglobal\lti\oauth;

class page_provider {

  private static function get_page_id() {
    $request = oauth\request::from_request();

    $id = $request->get_parameter('custom_id');

    if (!$cm = get_coursemodule_from_id('page', $id)) {
         print_error('invalidcoursemodule');
     }

     return $cm->instance;
  }

  public static function render() {
    global $PAGE;

    // Get the plugin renderer.
    $renderer = $PAGE->get_renderer('local_lti');

    // Render the page.
    $page = new \local_lti\output\page(page_provider::get_page_id());
    echo $renderer->render($page);
  }
}
