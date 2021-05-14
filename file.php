<?php

require_once(__DIR__.'/../../config.php');

\local_lti\provider\util::load_environment();
$token = required_param('token', PARAM_RAW);
$token = explode('/', $token)[0];
\Firebase\JWT\JWT::decode($token, $_ENV['SECRET'], ['HS256']);

$fs       = get_file_storage();
$fullpath = rawurldecode(preg_replace('/\?time=\d*$/', '',
    preg_split('/file\.php\?token=[^\/]*/', $_SERVER['REQUEST_URI'])[1]));


// If this file is stored in mod_page, always use 0.
if (strpos($fullpath, '/mod_page/') !== false) {
    $fullpath = str_replace('/1/', '/0/', $fullpath);
}

$file = $fs->get_file_by_hash(sha1($fullpath));
send_stored_file($file);
die;
