<?php

namespace local_lti\external;

use core_external\external_api;
use Firebase\JWT\JWT;
use local_lti\provider\error;
use local_lti\provider\util;

require_once($CFG->libdir.'/filelib.php');

class provider_api extends external_api
{

    /*
    |--------------------------------------------------------------------------
    | Get content
    |--------------------------------------------------------------------------
    |
    | Get HTML content for a resource
    |
    */

    public static function get_content_parameters()
    {
        return new \core_external\external_function_parameters([
            'token'   => new \core_external\external_value(PARAM_RAW, 'JWT containing resource link ID'),
            'pagenum' => new \core_external\external_value(PARAM_INT, 'Book chapter pagenum', VALUE_OPTIONAL),
        ]);
    }

    public static function get_content($token, $pagenum = null)
    {
        global $DB;

        try {
            $params = self::validate_parameters(self::get_content_parameters(), compact('token', 'pagenum'));

            util::load_environment();

            // This will throw an exception if the secret does not match
            $payload = JWT::decode($token, $_ENV['SECRET'], ['HS256']);

            $resource_link = $DB->get_record('local_lti_resource_link', ['id' => $payload->resource_id]);

            $type = util::get_type_name($resource_link->type);

            // Load resource depending on type
            $resource_class = "\\local_lti\\resource_type\\$type";

            if (class_exists($resource_class)) {
                $resource = new $resource_class(
                    $resource_link->type,
                    $resource_link->consumer,
                    null,
                    $resource_link->content_id
                );

                $resource->update_link();

                $content = $resource->get_content($token, $params['pagenum']);
                $pages   = $resource->get_page_data();
                $title   = $resource->get_title($params['pagenum']);
            } else {
                throw new error(error::ERROR_INVALID_TYPE, null, $resource_link->consumer_id);
            }

            return [
                'raw_content' => $content,
                'pages'       => $pages,
                'title'       => $title,
            ];

        } catch (error $e) {
            $e->log();
            $error = $e;
        } catch (\Exception $e) {
            $e = new error(empty($e->getCode()) ? error::ERROR_UNKNOWN : $e->getCode(), $e->getMessage());
            $e->log();
            $error = $e;
        }

        return [
            'error' => [
                'code'    => $error->getCustomCode(),
                'message' => $error->getMessage(),
            ],
        ];
    }

    public static function get_content_returns()
    {
        return new \core_external\external_single_structure([
            'raw_content' => new \core_external\external_value(PARAM_RAW, '', VALUE_OPTIONAL),
            'title'       => new \core_external\external_value(PARAM_TEXT, '', VALUE_OPTIONAL),
            'pages'       => new \core_external\external_multiple_structure(new \core_external\external_single_structure([
                'name'    => new \core_external\external_value(PARAM_TEXT),
                'pagenum' => new \core_external\external_value(PARAM_INT),
            ]), '', VALUE_OPTIONAL),
            'error' => new \core_external\external_single_structure([
                'code' => new \core_external\external_value(PARAM_TEXT),
                'message' => new \core_external\external_value(PARAM_TEXT),
            ], '', VALUE_OPTIONAL)
        ]);
    }

}
