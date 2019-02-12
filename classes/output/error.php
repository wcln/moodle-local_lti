<?php

namespace local_lti\output;

use renderable;
use renderer_base;
use templatable;
use stdClass;

class error implements renderable, templatable {

    // The error message to render.
    var $message = null;

    public function __construct($message) {
      $this->message = $message;
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
      $data->message = $this->message;
      return $data;
    }
}
