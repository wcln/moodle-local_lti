<?php

namespace local_lti\provider;
use \local_lti\imsglobal\lti\oauth;

class page_provider {

  public static function get_page_id() {
    $request = oauth\request::from_request();

    $id = $request->get_parameter('custom_id');

    if (!$cm = get_coursemodule_from_id('page', $id)) {
         print_error('invalidcoursemodule');
     }

     return $cm->instance;
  }
}
