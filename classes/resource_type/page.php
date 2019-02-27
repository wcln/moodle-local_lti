<?php

namespace local_lti\resource_type;
use local_lti\provider\resource;

class page extends resource {
  public function render() {
    global $PAGE;

    // Get the plugin renderer.
    $renderer = $PAGE->get_renderer('local_lti');

    // Retrieve the requested content ID.
    $content_id = $this->request->get_resource()->get_content_id();

    // Retrieve page ID.
    $cm = get_coursemodule_from_id('page', $content_id);

    // Render page.
    $page = new \local_lti\output\page($cm->instance);
    echo $renderer->render($page);
  }
}
