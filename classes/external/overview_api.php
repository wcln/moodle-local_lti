<?php

namespace local_lti\external;

use external_api;
use local_lti\helper\consumer;

class overview_api extends external_api
{

    /*
    |--------------------------------------------------------------------------
    | Get top consumers
    |--------------------------------------------------------------------------
    |
    | Get top consumer sites by total access count
    |
    */

    public static function get_top_consumers_parameters(): \external_function_parameters
    {
        return new \external_function_parameters([
            'limit' => new \external_value(PARAM_INT, 'Number of top consumers to fetch', VALUE_DEFAULT, 5)
        ]);
    }

    public static function get_top_consumers($limit)
    {
        $params = self::validate_parameters(self::get_top_consumers_parameters(), compact('limit'));

        return consumer::get_top_consumers($limit);
    }

    public static function get_top_consumers_returns()
    {
        return new \external_multiple_structure(new \external_single_structure([
            'id'           => new \external_value(PARAM_INT, 'Consumer ID'),
            'name'         => new \external_value(PARAM_TEXT, 'Consumer name'),
            'access_count' => new \external_value(PARAM_INT),
        ]));
    }

    /*
    |--------------------------------------------------------------------------
    | Get top resources
    |--------------------------------------------------------------------------
    |
    | Get most requested resources by total access count
    |
    */

    public static function get_top_resources_parameters(): \external_function_parameters
    {
        return new \external_function_parameters([]);
    }

    public static function get_top_resources()
    {
    }

    public static function get_top_resources_returns()
    {
    }

    /*
    |--------------------------------------------------------------------------
    | Get total consumers count
    |--------------------------------------------------------------------------
    |
    | Get the total number of (active?) consumer sites
    |
    */

    public static function get_total_consumers_count_parameters(): \external_function_parameters
    {
        return new \external_function_parameters([]);
    }

    public static function get_total_consumers_count($active_only = true)
    {
    }

    public static function get_total_consumers_count_returns()
    {
    }

    /*
    |--------------------------------------------------------------------------
    | Get total requests count
    |--------------------------------------------------------------------------
    |
    | Get the total number of requests
    |
    */

    public static function get_total_requests_count_parameters(): \external_function_parameters
    {
        return new \external_function_parameters([]);
    }

    public static function get_total_requests_count()
    {
    }

    public static function get_total_requests_count_returns()
    {
    }

    /*
    |--------------------------------------------------------------------------
    | Get total resources count
    |--------------------------------------------------------------------------
    |
    | Get the total number of resources requested
    |
    */

    public static function get_total_resources_count_parameters(): \external_function_parameters
    {
        return new \external_function_parameters([]);
    }

    public static function get_total_resources_count()
    {
    }

    public static function get_total_resources_count_returns()
    {
    }

    /*
    |--------------------------------------------------------------------------
    | Get errors count
    |--------------------------------------------------------------------------
    |
    | Get the number of errors that occured in the last 24 hours
    |
    */

    public static function get_errors_count_parameters(): \external_function_parameters
    {
        return new \external_function_parameters([]);
    }

    public static function get_errors_count()
    {
    }

    public static function get_errors_count_returns()
    {
    }

    /*
    |--------------------------------------------------------------------------
    | Get requests by month
    |--------------------------------------------------------------------------
    |
    | Get the number of LTI requests made in each month of the last year
    |
    */

    public static function get_requests_by_month_parameters(): \external_function_parameters
    {
        return new \external_function_parameters([]);
    }

    public static function get_requests_by_month()
    {
    }

    public static function get_requests_by_month_returns()
    {
    }

}
