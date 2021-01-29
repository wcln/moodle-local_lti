<?php

namespace local_lti\helper;

use local_lti\provider\request;
use local_lti\provider\resource;

class resource_link
{

    const TABLE = 'local_lti_resource_link';

    /**
     * Get the most accessed resources
     *
     * @param  int  $limit
     *
     * @return array
     * @throws \dml_exception
     */
    public static function get_top_resources($limit = 5)
    {
        global $DB;

        $sql = "
            SELECT content_id, `type`, SUM(access_count) AS access_count
            FROM mdl_local_lti_resource_link rl
            GROUP BY content_id, `type`
            ORDER BY access_count DESC
        ";

        $resource_links = $DB->get_records_sql($sql, null, 0, $limit);

        $types = $DB->get_records_menu(resource::TYPE_TABLE, null, null, 'id,name');

        $resources = [];
        foreach ($resource_links as $link) {

            if (! isset($types[$link->type])) {
                continue;
            }

            $resource_class = "\\local_lti\\resource_type\\" . $types[$link->type];

            if (class_exists($resource_class)) {
                $record = $resource_class::get_activity_record($link->content_id);

                $resources[] = [
                    'id'           => $record->id,
                    'name'         => $record->name,
                    'access_count' => $link->access_count,
                ];
            }
        }

        return $resources;
    }

}
