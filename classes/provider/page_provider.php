<?php

namespace local_lti\provider;
use \local_lti\imsglobal\lti\oauth;

class page_provider {

  private static function get_page_id() {
    $request = oauth\request::from_request();

    $id = $request->get_parameter('custom_id');

    if (!$cm = get_coursemodule_from_id('page', $id)) {
       error::render(get_string('error_page_id', 'local_lti'));
       return null;
     }

     return $cm->instance;
  }

  public static function render() {
    global $PAGE;

    // Get the plugin renderer.
    $renderer = $PAGE->get_renderer('local_lti');

    if ($page_id = page_provider::get_page_id()) {
      // Render the page.
      $page = new \local_lti\output\page($page_id);
      echo $renderer->render($page);
      return true;
    } else {
      return false;
    }


  }
}
