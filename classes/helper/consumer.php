<?php

namespace local_lti\helper;

class consumer
{

    const TABLE = 'local_lti_consumer';

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
