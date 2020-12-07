<?php

// Standard Moodle config file import
require_once(__DIR__.'/../../config.php');

// Set up the page
$PAGE->set_pagelayout('admin');
$PAGE->set_context(context_system::instance());
$PAGE->set_heading(get_string('dashboard_heading', 'local_lti'));
$PAGE->set_title(get_string('dashboard_title', 'local_lti'));
$PAGE->set_url(new moodle_url('/local/lti/dashboard.php'));

require_login();
require_capability('moodle/site:config', context_system::instance());

// Output Moodle's header
echo $OUTPUT->header();
echo $OUTPUT->heading(get_string('dashboard_heading', 'local_lti'));

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
