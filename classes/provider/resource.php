<?php

namespace local_lti\provider;

class resource {

  private $id;
  private $title;
  private $type;
  private $consumer_id;

  public function __construct($id, $title, $type, $consumer_id) {
    $this->id = $id;
    $this->title = $title;
    $this->type = $type;
    $this->consumer_id = $consumer_id;
  }

  /*
   * Check if this resource already exists in local_lti_resource_link table.
   * Checks if content id is set, if it  is shared.
   */
  private function is_linked() {
    global $DB;

    $sql = 'SELECT {local_lti_resource_link}.id
            FROM {local_lti_resource_link}, {local_lti_consumer}
            WHERE {local_lti_resource_link}.consumer_id = {local_lti_consumer}.id
            AND resource_link_id = ?
            AND {local_lti_consumer}.id = ?
            AND content_id IS NOT NULL';

    return $DB->record_exists_sql($sql, array($this->id, $this->consumer_id));
  }

  private function is_share_approved() {

    $record = get_record_from_database();

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
        'resource_link_id' => $this->id,
        'consumer_id' => $this->consumer_id
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
            WHERE {local_lti_resource_link}.consumer_id = {local_lti_consumer}.id
            AND resource_link_id = ?
            AND {local_lti_consumer}.id = ?';

    // Check if resource ID / consumer exist in table already.
    if ($record = $DB->get_record_sql('local_lti_resource_link', $sql, array($this->id, $this->consumer_id))) {
      // Update the content id.
      $record->content_id = $content_id;
      $record->updated = $now;
      $DB->update_record('local_lti_resource_link', $record);
    } else {
      // Insert a new record.
      $record = new \stdClass();
      $record->resource_link_id = $this->id;
      $record->resource_link_title = $this->title;
      $record->type = $this->type;
      $record->consumer = $this->consumer_id;
      $record->content_id = $content_id;
      $record->share_approved = true;
      $record->created = $now;
      $record->resource_link_id
      $DB->insert_record('local_lti_resource_link', $record);
    }
  }

  /*
   * Get the book/page ID of this resource.
   */
  public function get_content_id() {

    // Check if this resource is linked already and approved for sharing.
    if (is_linked() && is_share_approved()) {

      // Return the record content id.
      $record = get_record_from_database();
      return $record->content_id;
    }

    // Return false if it is not linked.
    return false;
  }

}
