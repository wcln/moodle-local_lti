<?php

namespace local_lti\helper;

class consumer
{

    const TABLE = 'local_lti_consumer';

    /** @var array These are the fields which will be displayed on the consumer tab of the LTI dashboard */
    const CONSUMER_FIELDS
        = [
            ['field' => 'name', 'name' => 'Name'],
            ['field' => 'consumer_key', 'name' => 'Consumer key'],
            ['field' => 'secret', 'name' => 'Secret'],
            ['field' => 'enabled', 'name' => 'Enabled', 'type' => self::FIELD_TYPE_CHECKBOX],
            ['field' => 'last_access', 'name' => 'Last access', 'editable' => false],
        ];

    /** @var string Consumer field type 'checkbox'. Display this field as a checkbox on the dashboard consumers table */
    const FIELD_TYPE_CHECKBOX = 'checkbox';

    /** @var string This is the default field type */
    const FIELD_TYPE_TEXT = 'text';

    /**
     * Get the top consumer sites by access count
     *
     * @param  int  $limit
     *
     * @return array
     * @throws \dml_exception
     */
    public static function get_top_consumers($limit = 5): array
    {
        global $DB;

        $sql = "
            SELECT c.id, c.name, SUM(access_count) access_count 
            FROM {".resource_link::TABLE."} rl 
            JOIN {".self::TABLE."} c 
            ON c.id = rl.consumer
            GROUP BY consumer 
            ORDER BY access_count DESC
        ";

        return $DB->get_records_sql($sql, null, 0, $limit);
    }

}
