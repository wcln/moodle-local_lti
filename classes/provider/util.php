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

  /**
   * Returns an assoc array of consumers and their secret keys.
   * Includes enabled and disabled consumers.
   * @return array An assoc. array of consumer => secret key.
   */
  public static function get_all_tool_consumer_secrets() {
    global $DB;

    $tool_consumer_secrets = $DB->get_records_menu('local_lti_consumer', null, '', 'consumer_key, secret');
    return $tool_consumer_secrets;
  }

  /**
   * Returns an assoc array of consumers and their secret keys.
   * Only includes consumers which are enabled.
   * @return array An assoc. array of consumer => secret key.
   */
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

  /**
   * Returns a consumer ID.
   * @param  string $name The consumer name.
   * @return int      The consumer ID.
   */
  public static function get_consumer_id($name) {
    global $DB;

    $record = $DB->get_record('local_lti_consumer', array('name' => $name), 'id');

    if ($record) {
      return $record->id;
    } else {
      return null;
    }
  }

  /**
   * Returns the ID of an LTI resource type given a unique name.
   * @param  string $name The name of the LTI resource type.
   * @return int       The ID of the LTI resource type.
   */
  public static function get_type_id($name) {
    global $DB;

    $record = $DB->get_record('local_lti_type', array('name' => $name), 'id');

    if ($record) {
      return $record->id;
    } else {
      return null;
    }
  }

  /**
   * Returns the name of an LTI resource type given an ID.
   * @param  int $id The ID of the LTI resource type.
   * @return string     The name of the LTI resource type.
   */
  public static function get_type_name($id) {
    global $DB;

    $record = $DB->get_record('local_lti_type', array('id' => $id), 'name');

    if ($record) {
      return $record->name;
    } else {
      return null;
    }
  }

  /**
   * Generates cryptographically secure pseudo-random bytes.
   * @return string Random hexadecimal string.
   */
  public static function generate_random_session_id() {
      return bin2hex(random_bytes(10));
  }

  /**
   * Replace characters to enable MathJax to filter WIRIS XML.
   * @param  string $content_string A string to be formatted.
   * @return string                 The string with characters replaced. Ready to be rendered.
   */
  public static function format_content_for_mathjax($content_string) {
    $content_string = str_replace('«', '<', $content_string);
    $content_string = str_replace('»', '>', $content_string);
    $content_string = str_replace('§', '&', $content_string);
    $content_string = str_replace('¨', '"', $content_string);
    $content_string = str_replace('´', "'", $content_string);

    return $content_string;
  }
}
