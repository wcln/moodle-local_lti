<?php

namespace local_lti\external;

use external_api;
use Firebase\JWT\JWT;
use local_lti\provider\util;

class provider_api extends external_api {

    /*
    |--------------------------------------------------------------------------
    | Get content
    |--------------------------------------------------------------------------
    |
    | Get HTML content for a resource
    |
    */

    public static function get_content_parameters() {
        return new \external_function_parameters([
            'token' => new \external_value(PARAM_RAW, 'JWT containing resource link ID'),
            'pagenum' => new \external_value(PARAM_INT, 'Book chapter pagenum', VALUE_OPTIONAL)
        ]);
    }

    public static function get_content($token, $pagenum = null) {
        util::load_environment();

        // This will throw an exception if the secret does not match
        $payload = JWT::decode($token, $_ENV['SECRET'], ['HS256']);

        // TODO get resource content using $payload->resource_id

        return [
            'raw_content' => '<p>Test return content</p>'
        ];
    }

    public static function get_content_returns() {
        return new \external_single_structure([
            'raw_content' => new \external_value(PARAM_RAW),
            // TODO add page information if this is a book
        ]);
    }

}
