<?php

namespace local_lti\external;

use external_api;
use local_lti\event\consumer_created;
use local_lti\event\consumer_deleted;
use local_lti\event\consumer_updated;
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
            $select                 = "name LIKE CONCAT('%', :keywords, '%')";
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

        // What fields do we need to fetch?
        $query_fields = "id,".implode(',', array_column(consumer::CONSUMER_FIELDS, 'field'));

        // Search for consumers in database
        $consumers = $DB->get_records_select(consumer::TABLE, $select, $sql_params, $sort,
            $query_fields,
            $params['page'] * self::PAGE_SIZE, self::PAGE_SIZE);

        $page_count = $DB->count_records_select(consumer::TABLE, $select, $sql_params);

        // Build the array that will be returned
        $consumers_formatted = [];
        foreach ($consumers as $consumer) {
            $consumer->last_access = ! empty($consumer->last_access) ? userdate($consumer->last_access,
                get_string('strftimedatetime')) : '';


            $fields = [];
            foreach (consumer::CONSUMER_FIELDS as $field) {
                $fields[] = [
                    'field'    => $field['field'],
                    'value'    => $consumer->{$field['field']},
                    'type'     => isset($field['type']) ? $field['type'] : consumer::FIELD_TYPE_TEXT,
                    'editable' => isset($field['editable']) ? $field['editable'] : true,
                ];
            }

            $consumers_formatted[] = [
                'fields' => $fields,
                'id'     => $consumer->id,
            ];
        }

        return [
            'page_count' => $page_count,
            'consumers'  => $consumers_formatted,
        ];
    }

    public static function get_consumers_returns()
    {
        return new \external_single_structure([
            'page_count' => new \external_value(PARAM_INT),
            'consumers'  => new \external_multiple_structure(new \external_single_structure([
                'fields' => new \external_multiple_structure(new \external_single_structure([
                    'field'    => new \external_value(PARAM_TEXT, 'The database field name in the consumers table'),
                    'value'    => new \external_value(PARAM_RAW, 'What is the value of this field'),
                    'type'     => new \external_value(PARAM_TEXT, 'checkbox or text', VALUE_DEFAULT, 'text'),
                    'editable' => new \external_value(PARAM_BOOL, 'Is this field editable', VALUE_DEFAULT, true),
                ])),
                'id'     => new \external_value(PARAM_INT, 'Consumer ID'),
            ])),
        ]);
    }

    /*
    |--------------------------------------------------------------------------
    | Update consumer
    |--------------------------------------------------------------------------
    |
    | Update consumer info
    |
    */

    public static function update_consumer_parameters()
    {
        return new \external_function_parameters([
            'id'    => new \external_value(PARAM_INT, 'Consumer ID'),
            'key'   => new \external_value(PARAM_TEXT),
            'value' => new \external_value(PARAM_RAW),
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

        if ($DB->update_record(consumer::TABLE, (object)$consumer)) {
            $event = consumer_updated::create(['objectid' => $params['id']]);
            $event->trigger();
        }
    }

    public static function update_consumer_returns()
    {
        return null;
    }

    /*
    |--------------------------------------------------------------------------
    | Get sort options
    |--------------------------------------------------------------------------
    |
    | Get sort options for the dropdown
    |
    */

    public static function get_sort_options_parameters()
    {
        return new \external_function_parameters([]);
    }

    public static function get_sort_options()
    {
        return [
            ['value' => self::SORT_DATE_DESC, 'name' => 'Date added (newest first)'],
            ['value' => self::SORT_DATE_ASC, 'name' => 'Date added (oldest first)'],
            ['value' => self::SORT_NAME_ASC, 'name' => 'Name (A - Z)'],
            ['value' => self::SORT_NAME_DESC, 'name' => 'Name reverse (Z - A)'],
            ['value' => self::SORT_LAST_ACCESS, 'name' => 'Last access'],
        ];
    }

    public static function get_sort_options_returns()
    {
        return new \external_multiple_structure(new \external_single_structure([
            'value' => new \external_value(PARAM_TEXT),
            'name'  => new \external_value(PARAM_TEXT),
        ]));
    }

    /*
    |--------------------------------------------------------------------------
    | Create consumer
    |--------------------------------------------------------------------------
    |
    | Create a new consumer
    |
    */

    public static function create_consumer_parameters()
    {
        return new \external_function_parameters([]);
    }

    public static function create_consumer()
    {
        global $DB;

        $now = time();

        $consumer_id = $DB->insert_record(consumer::TABLE, (object)[
            'name'         => 'New consumer',
            'consumer_key' => 'Consumer'.rand(1000, 9999),
            'secret'       => substr(str_shuffle('0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ'), 1, 6),
            'enabled'      => true,
            'timecreated'  => $now,
            'timeupdated'  => $now,
        ]);

        $event = consumer_created::create(['objectid' => $consumer_id]);
        $event->trigger();
    }

    public static function create_consumer_returns()
    {
        return null;
    }

    /*
    |--------------------------------------------------------------------------
    | Delete consumer
    |--------------------------------------------------------------------------
    |
    | Delete a consumer from the database
    |
    */

    public static function delete_consumer_parameters()
    {
        return new \external_function_parameters([
            'id' => new \external_value(PARAM_INT, 'The consumer ID'),
        ]);
    }

    public static function delete_consumer($id)
    {
        global $DB;

        $params = self::validate_parameters(self::delete_consumer_parameters(), compact('id'));

        if ($DB->delete_records(consumer::TABLE, ['id' => $params['id']])) {
            $event = consumer_deleted::create(['objectid' => $params['id']]);
            $event->trigger();
        }
    }

    public static function delete_consumer_returns()
    {
        return null;
    }

}
