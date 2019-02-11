<?php

namespace local_lti\output;

defined('MOODLE_INTERNAL') || die;

use plugin_renderer_base;

class renderer extends plugin_renderer_base {

    public function render_book($page) {
        $data = $page->export_for_template($this);
        return parent::render_from_template('local_lor/render_book', $data);
    }

}
