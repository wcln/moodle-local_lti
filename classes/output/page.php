<?php

namespace local_lti\output;

use renderable;
use renderer_base;
use templatable;
use stdClass;

class page implements renderable, templatable {

    // The ID of the Moodle page to render.
    var $page_id = null;

    public function __construct($page_id) {
      $this->page_id = $page_id;
    }

    /**
     * Export this data so it can be used as the context for a mustache template.
     *
     * @return stdClass
     */
    public function export_for_template(renderer_base $output) {
      global $DB;

      // Data class to be sent to template.
      $data = new stdClass();
      $data->content = $DB->get_record('page', array('id' => $this->page_id), '*', MUST_EXIST)->content;
      return $data;
    }
}
