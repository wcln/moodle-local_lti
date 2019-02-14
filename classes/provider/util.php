<?php

namespace local_lti\provider;

/**
 * Provider Utilities.
 *
 * Provides functions to be used by other Provider classes.
 *
 * @package    local_lti
 * @copyright  2019 Colin Bernard
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class util {
  public static function get_all_tool_consumer_secrets() {
    global $DB;

    $tool_consumer_secrets = $DB->get_records_menu('local_lti_consumer', null, '', 'consumer_key, secret');
    return $tool_consumer_secrets;
  }

  public static function get_enabled_tool_consumer_secrets() {
    global $DB;

    $now = date("Y-m-d H:i:s");

    $sql = 'SELECT consumer_key, secret
            FROM {local_lti_consumer}
            WHERE enabled = 1
            AND (enable_from < ? OR enable_from IS NULL)
            AND (enable_until > ? OR enable_until IS NULL)';

    $tool_consumer_secrets = $DB->get_records_sql_menu($sql, array($now, $now));
    return $tool_consumer_secrets;
  }

  public static function get_consumer_id($name) {
    global $DB;

    $record = $DB->get_record('local_lti_consumer', array('name' => $name), 'id');

    if ($record) {
      return $record->id;
    } else {
      return null;
    }
  }

  public static function get_type_id($name) {
    global $DB;

    $record = $DB->get_record('local_lti_type', array('name' => $name), 'id');

    if ($record) {
      return $record->id;
    } else {
      return null;
    }
  }

  public static function get_type_name($id) {
    global $DB;

    $record = $DB->get_record('local_lti_type', array('id' => $id), 'name');

    if ($record) {
      return $record->name;
    } else {
      return null;
    }
  }

}
