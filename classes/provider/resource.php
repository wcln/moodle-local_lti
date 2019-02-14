<?php

namespace local_lti\provider;

class resource {

  private $resource_link_id;
  private $title;
  private $type;
  private $consumer_id;

  public function __construct($resource_link_id, $title, $type, $consumer_id) {
    $this->resource_link_id = $resource_link_id;
    $this->title = $title;
    $this->type = $type;
    $this->consumer_id = $consumer_id;
  }

  /*
   * Check if this resource already exists in local_lti_resource_link table.
   * Checks if content id is set, if it  is shared.
   */
  public function is_linked() {
    global $DB;

    $sql = 'SELECT {local_lti_resource_link}.id
            FROM {local_lti_resource_link}, {local_lti_consumer}
            WHERE {local_lti_resource_link}.consumer = {local_lti_consumer}.id
            AND resource_link_id = ?
            AND {local_lti_consumer}.id = ?
            AND content_id IS NOT NULL';

    return $DB->record_exists_sql($sql, array($this->resource_link_id, $this->consumer_id));
  }

  public function is_share_approved() {

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

  /*
   * Get the book/page ID of this resource.
   */
  private function get_content_id() {

    // Return the record content id.
    $record = $this->get_record_from_database();
    return $record->content_id;

  }

  public function render() {
    global $PAGE;
    global $DB; // TODO, move DB calls to separate class specific to different lti types.

    $content_id = $this->get_content_id();

    // Get the plugin renderer.
    $renderer = $PAGE->get_renderer('local_lti');

    switch (util::get_type_name($this->type)) {
      case 'book':

        try {

          // Retrieve book id.
          $cm = get_coursemodule_from_id('book', $content_id, 0, false, MUST_EXIST);
          $course = $DB->get_record('course', array('id'=>$cm->course), '*', MUST_EXIST);
          $book = $DB->get_record('book', array('id'=>$cm->instance), '*', MUST_EXIST);

          // Render book.
          $book = new \local_lti\output\book($book->id);
          echo $renderer->render($book);
          break;

        } catch(\Exception $e) {
          throw new \Exception('error retrieving book id.');
        }



      case 'page':

        // Retrieve page ID.
        $cm = get_coursemodule_from_id('page', $content_id);

        // Render page.
        $page = new \local_lti\output\page($cm->instance);
        echo $renderer->render($page);
        break;

      default:
        throw new \Exception('not a valid type');
    }
  }
}
