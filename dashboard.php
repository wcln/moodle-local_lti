<?php

// Standard Moodle config file import
require_once(__DIR__ . '/../../config.php');

// Set up the page
$PAGE->set_context(context_system::instance());
$PAGE->set_heading('LTI Dashboard');
$PAGE->set_title('LTI Dashboard');
$PAGE->set_url(new moodle_url('/local/lti/dashboard.php'));

// Output Moodle's header
echo $OUTPUT->header();

// Load the Vue app
$PAGE->requires->js_call_amd('local_lti/app-lazy', 'init');

// Output HTML, inside of which the Vue app will be loaded
echo <<<HTML
<div id="lti-dashboard-app">
  <router-view></router-view>
</div>
HTML;

// Output Moodle's footer
echo $OUTPUT->footer();
