<?php

namespace local_lti\external;

use external_api;
use local_lti\helper\consumer;

class consumers_api extends external_api
{

    const PAGE_SIZE = 10;

    const SORT_DATE_ASC = 'date_asc';
    const SORT_DATE_DESC = 'date_desc';
    const SORT_NAME_ASC = 'name_asc';
    const SORT_NAME_DESC = 'name_desc';
    const SORT_LAST_ACCESS = 'last_access';

    /*
    |--------------------------------------------------------------------------
    | Get consumers
    |--------------------------------------------------------------------------
    |
    | Get all consumers (by page)
    |
    */

    public static function get_consumers_parameters()
    {
        return new \external_function_parameters([
            'keywords' => new \external_value(PARAM_TEXT, 'Filter by keywords', VALUE_DEFAULT, ''),
            'sort'     => new \external_value(PARAM_TEXT, 'Sort the results', VALUE_DEFAULT, self::SORT_DATE_DESC),
            'page'     => new \external_value(PARAM_INT, 'What page of results are we on (start at 0)',
                VALUE_DEFAULT, 0),
        ]);
    }

    public static function get_consumers($keywords, $sort, $page = 0)
    {
        global $DB;

        $params = self::validate_parameters(self::get_consumers_parameters(), compact('keywords', 'sort', 'page'));

        // Keywords search
        $select     = "";
        $sql_params = [];
        if ( ! empty($keywords)) {
            $select                 = "name LIKE '%:keywords%'";
            $sql_params['keywords'] = $params['keywords'];
        }

        // Determine sorting method
        switch ($params['sort']) {
            case self::SORT_DATE_ASC:
                $sort = 'timecreated ASC';
                break;
            case self::SORT_DATE_DESC:
                $sort = 'timecreated DESC';
                break;
            case self::SORT_NAME_ASC:
                $sort = 'name ASC';
                break;
            case self::SORT_NAME_DESC:
                $sort = 'name DESC';
                break;
            case self::SORT_LAST_ACCESS:
                $sort = 'last_access DESC';
                break;
            default:
                $sort = '';
        }

        // Search for consumers in database
        $consumers = $DB->get_records_select(consumer::TABLE, $select, $sql_params, $sort,
            'id,name,consumer_key,secret,enabled,last_access,timecreated,timeupdated',
            $params['page'] * self::PAGE_SIZE, self::PAGE_SIZE);

        // Format date times
        foreach ($consumers as $consumer) {
            $consumer->last_access = ! empty($consumer->last_access) ? userdate($consumer->last_access,
                get_string('strftimedatetime')) : '';
            $consumer->timecreated = ! empty($consumer->timecreated) ? userdate($consumer->timecreated,
                get_string('strftimedatetime')) : '';
            $consumer->timeupdated = ! empty($consumer->timeupdated) ? userdate($consumer->timeupdated,
                get_string('strftimedatetime')) : '';
        }

        return $consumers;
    }

    public static function get_consumers_returns()
    {
        return new \external_multiple_structure(new \external_single_structure([
            'id'           => new \external_value(PARAM_INT, 'Consumer ID'),
            'name'         => new \external_value(PARAM_TEXT),
            'consumer_key' => new \external_value(PARAM_TEXT),
            'secret'       => new \external_value(PARAM_TEXT),
            'enabled'      => new \external_value(PARAM_BOOL),
            'last_access'  => new \external_value(PARAM_TEXT),
            'timecreated'  => new \external_value(PARAM_TEXT),
            'timeupdated'  => new \external_value(PARAM_TEXT),
        ]));
    }

    /*
    |--------------------------------------------------------------------------
    | Update consumer
    |--------------------------------------------------------------------------
    |
    | Get a single consumer
    |
    */

    public static function update_consumer_parameters()
    {
        return new \external_function_parameters([
            'id'    => new \external_value(PARAM_INT, 'Consumer ID'),
            'key'   => new \external_value(PARAM_TEXT),
            'value' => new \external_value(PARAM_TEXT),
        ]);
    }

    public static function update_consumer($id, $key, $value)
    {
        global $DB;

        $params = self::validate_parameters(self::update_consumer_parameters(), compact('id', 'key', 'value'));

        $consumer = [
            'id'           => $params['id'],
            $params['key'] => $params['value'],
        ];

        $DB->update_record(consumer::TABLE, (object)$consumer);
    }

    public static function update_consumer_returns()
    {
        return null;
    }

}
