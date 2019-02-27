<?php

namespace local_lti\provider;
use local_lti\provider\request;

/**
 * LTI Resource
 *
 * Represents the LTI resource which was requested.
 *
 * @package    local_lti
 * @copyright  2019 Colin Bernard
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class resource {

  /** @var string An identifier that the consumer gurantees will be unique. */
  protected $resource_link_id;

  /** @var string The title of the resource. */
  protected $title;

  /** @var int The type of the resource requested. Ex: Book or Page. */
  protected $type;

  /** @var int The ID of the tool consumer. This is NOT the consumer key. */
  protected $consumer_id;

  /** @var object A reference to the request object. */
  protected $request;

  public function __construct($resource_link_id, $title, $type, $consumer_id, $request) {
    $this->resource_link_id = $resource_link_id;
    $this->title = $title;
    $this->type = $type;
    $this->consumer_id = $consumer_id;
    $this->request = $request;
  }

  /*
   * Check if this resource already exists in local_lti_resource_link table.
   * Checks if content id is set, if it  is shared.
   */
  public function is_linked() {
    global $DB;

    // If an ID has been passed in as a custom parameter, ignore resource linking.
    if ($this->request->is_custom_parameter_set()) {
      return true;
    }

    $sql = 'SELECT {local_lti_resource_link}.id
            FROM {local_lti_resource_link}, {local_lti_consumer}
            WHERE {local_lti_resource_link}.consumer = {local_lti_consumer}.id
            AND resource_link_id = ?
            AND {local_lti_consumer}.id = ?
            AND content_id IS NOT NULL';

    return $DB->record_exists_sql($sql, array($this->resource_link_id, $this->consumer_id));
  }

  public function is_share_approved() {

    // If an ID has been passed in as a custom parameter, ignore resource linking.
    if ($this->request->is_custom_parameter_set()) {
      return true;
    }

    $record = $this->get_record_from_database();

    if ($record) {
      if ($record->share_approved) {
        return true;
      } else {
        return false;
      }
    } else {
      return false;
    }
  }

  private function get_record_from_database() {
    global $DB;

    // Search for the record using the record_link_id and consumer_id.
    $record = $DB->get_record('local_lti_resource_link',
      array(
        'resource_link_id' => $this->resource_link_id,
        'consumer' => $this->consumer_id
      ));

    // Return the record.
    return $record;

  }

  /*
   * Inserts this resource into local_lti_resource_link table.
   */
  public function create_link($content_id) {
    global $DB;

    $now = date("Y-m-d H:i:s");

    $sql = 'SELECT {local_lti_resource_link}.id
            FROM {local_lti_resource_link}, {local_lti_consumer}
            WHERE {local_lti_resource_link}.consumer = {local_lti_consumer}.id
            AND resource_link_id = ?
            AND {local_lti_consumer}.name = ?';

    // Check if resource ID / consumer exist in table already.
    if ($record = $DB->get_record_sql($sql, array($this->resource_link_id, $this->consumer_id))) {
      // Update the content id.
      $record->content_id = $content_id;
      $record->updated = $now;
      $DB->update_record('local_lti_resource_link', $record);
    } else {
      // Insert a new record.
      $record = new \stdClass();
      $record->resource_link_id = $this->resource_link_id;
      $record->resource_link_title = $this->title;
      $record->type = $this->type;
      $record->consumer = $this->consumer_id;
      $record->content_id = $content_id;
      $record->share_approved = true;
      $record->created = $now;
      $record->updated = $now;
      $record->resource_link_id = $this->resource_link_id;
      $DB->insert_record('local_lti_resource_link', $record);
    }
  }

  /**
   * Get the book/page ID of this resource.
   */
  public function get_content_id() {

    // Check if an ID was NOT supplied as a custom parameter.
    if (!$this->request->is_custom_parameter_set()) {
      // Return the record content id.
      $record = $this->get_record_from_database();
      return $record->content_id;

    } else {

      // Check if the request custom ID parameter is set.
      if (!is_null($this->request->get_parameter('custom_id'))) {
        return $this->request->get_parameter('custom_id');

      // If the request is coming from Canvas.
      } else if ($this->request->get_parameter('tool_consumer_info_product_family_code') === "canvas") {

        // Get the ID parameter which was appended to the launch URL.
        $id = optional_param('id', null, PARAM_INT);

        // Set the request custom parameter. This is needed for Canvas to switch book pages.
        // The optional param above will not be set when navigating pages, so will rely on the stored request data.
        $this->request->set_parameter('custom_id', $id, false);

        // Return the ID.
        return $id;
      }

      // There is no ID set.
      return null;
    }
  }

  /**
   * To be overridden by resource type classes.
   */
  public function render() { }
}
