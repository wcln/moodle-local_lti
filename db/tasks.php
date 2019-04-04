<?php

defined('MOODLE_INTERNAL') || die();

$tasks = [
  // Run the update toolurl task every morning at 2AM.
  [
      'classname' => 'local_lti\task\update_toolurl',
      'blocking' => 0,
      'minute' => '0',
      'hour' => '2',
      'day' => '*',
      'month' => '*',
      'dayofweek' => '*',
      'disabled' => 0
  ]
];
