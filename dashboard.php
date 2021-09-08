<?php

// Standard Moodle config file import
require_once(__DIR__.'/../../config.php');

// Set up the page
$PAGE->set_pagelayout('admin');
$PAGE->set_context(context_system::instance());
$PAGE->set_heading(get_string('dashboard_heading', 'local_lti'));
$PAGE->set_title(get_string('dashboard_title', 'local_lti'));
$PAGE->set_url(new moodle_url('/local/lti/dashboard.php'));

// The LTI provider cookie can interfere with accessing this page
// This occurs when there are two cookies:
// - The cookie with path '/' that we want
// - The cookie with path '/local/lti' that is present because the user was viewing an LTI resource on a consumer site
// Delete the cookie with path '/local/lti' to ensure we use the correct cookie when viewing this page
if (! isloggedin()) {
  setcookie('MoodleSession', null, -1, '/local/lti'); 
}

require_login();
require_capability('moodle/site:config', context_system::instance());

// Output Moodle's header
echo $OUTPUT->header();
echo $OUTPUT->heading(get_string('dashboard_heading', 'local_lti'));

$event = \local_lti\event\dashboard_viewed::create();
$event->trigger();

// Load the Vue app
$PAGE->requires->js_call_amd('local_lti/dashboard-lazy', 'init');

// Output HTML, inside of which the Vue app will be loaded
echo <<<HTML
<div id="lti-dashboard-app">
  <router-view>
    <div class="loader"></div>
  </router-view>
</div>
HTML;

// Output Moodle's footer
echo $OUTPUT->footer();
