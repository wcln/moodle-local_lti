<?php

namespace local_lti\output;

defined('MOODLE_INTERNAL') || die;

use plugin_renderer_base;

class renderer extends plugin_renderer_base {

    public function render_book($page) {
        $data = $page->export_for_template($this);
        return parent::render_from_template('local_lti/book', $data);
    }

    public function render_page($page) {
        $data = $page->export_for_template($this);
        return parent::render_from_template('local_lti/page', $data);
    }

    public function render_error($page) {
        $data = $page->export_for_template($this);
        return parent::render_from_template('local_lti/error', $data);
    }

    public function render_resource_form($page) {
      // No data required.
      return parent::render_from_template('local_lti/resource_form', null);
    }

}
