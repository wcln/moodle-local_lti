<?php

// Require standard Moodle configuration file.
require_once(__DIR__ . '/../../config.php');

try {

    // Make sure that a verified session exists with the provided session ID.
    // This is to prevent anyone from using this PHP script to access all Moodle files.
    // Only LTI consumers will be able to access files using this.
    $session_id = optional_param('sessid', false, PARAM_TEXT);
    if (! empty($session_id)) {
        $session_id = explode('/', $session_id)[0];
        if (isset($SESSION->{"lti_request_$session_id"})) {

            // Find and send the file.
            $fs = get_file_storage();
            $fullpath = rawurldecode(preg_replace('/\?time=\d*$/', '', preg_split('/file\.php\?sessid=[^\/]*/',  $_SERVER['REQUEST_URI'])[1]));

            // If this file is stored in mod_page, always use 0.
            if (strpos($fullpath, '/mod_page/') !== false) {
                $fullpath = str_replace('/1/', '/0/', $fullpath);
            }

            $file = $fs->get_file_by_hash(sha1($fullpath));
            send_stored_file($file);

        } else {
            throw new Exception('Invalid session id.');
        }
    } else {
        throw new Exception('No session id parameter!');
    }

} catch (Exception $e) {
    die('Error retrieving Moodle database file. Exception: ' . $e->getMessage());
}