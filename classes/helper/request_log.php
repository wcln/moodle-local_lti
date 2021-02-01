<?php

namespace local_lti\helper;

class request_log
{

    const TABLE = 'local_lti_request_log';

    /**
     * Increase access_count for this year/month in the log table
     *
     * @throws \dml_exception
     */
    public static function log()
    {
        global $DB;

        $params = ['year' => date("Y"), 'month' => date("m")];

        if ($record = $DB->get_record(self::TABLE, $params)) {
            $record->access_count++;
            $DB->update_record(self::TABLE, $record);
        } else {
            $DB->insert_record(self::TABLE, (object)$params);
        }
    }

    /**
     * Get the monthly number of requests
     *
     * @param  int  $num_months  The number of previous months (including current) to return
     *
     * @return array
     * @throws \dml_exception
     */
    public static function get_records($num_months = 6)
    {
        global $DB;

        return $DB->get_records(self::TABLE, [], 'year DESC, month DESC', '*', 0, $num_months);
    }

}
