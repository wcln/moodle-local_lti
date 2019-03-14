<?php
// This file is part of Moodle - http://moodle.org/
//
// Moodle is free software: you can redistribute it and/or modify
// it under the terms of the GNU General Public License as published by
// the Free Software Foundation, either version 3 of the License, or
// (at your option) any later version.
//
// Moodle is distributed in the hope that it will be useful,
// but WITHOUT ANY WARRANTY; without even the implied warranty of
// MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
// GNU General Public License for more details.
//
// You should have received a copy of the GNU General Public License
// along with Moodle.  If not, see <http://www.gnu.org/licenses/>.

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
      $page = $DB->get_record('page', array('id' => $this->page_id), 'id, name, content', MUST_EXIST);
      $data->content = $page->content;
      $data->title = $page->name;
      return $data;
    }
}
