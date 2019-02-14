<?php

namespace local_lti\output;

use renderable;
use renderer_base;
use templatable;
use stdClass;

class book implements renderable, templatable {

    /** @var int The ID of the Moodle book to render. */
    var $book_id = null;

    /** @var int The page of the Moodle book to render. */
    var $pagenum = null;

    public function __construct($book_id, $pagenum = null) {
      $this->book_id = $book_id;

      // If no page number is provided, use the first page.
      if (is_null($pagenum)) {
        $this->pagenum = 1;
      } else {
        $this->pagenum = $pagenum;
      }
    }

    /**
     * Export this data so it can be used as the context for a mustache template.
     *
     * @return stdClass
     */
    public function export_for_template(renderer_base $output) {
      global $DB;

      // Data class to be sent to template.
      $data = new stdClass();

      // Retrieve lesson from the database.
      try {
        $lesson = $DB->get_record_sql('SELECT id, pagenum, title, content
                                             FROM {book_chapters}
                                             WHERE bookid=?
                                             AND pagenum=?
                                             ORDER BY pagenum ASC', array($this->book_id, $this->pagenum));
      } catch(\Exception $e) {
        // Re-throw exception with custom message.
        throw new \Exception(get_string('error_retrieving_book_page', 'local_lti'));
      }

      // Replace characters to enable MathJax to filter WIRIS XML.
      $lesson->content = str_replace('«', '<', $lesson->content);
      $lesson->content = str_replace('»', '>', $lesson->content);
      $lesson->content = str_replace('§', '&', $lesson->content);
      $lesson->content = str_replace('¨', '"', $lesson->content);
      $lesson->content = str_replace('´', "'", $lesson->content);

      // Convert to a friendly format for template.
      $data->lesson = [
        'title' => $lesson->title,
        'content' => $lesson->content,
        'pagenum' => $lesson->pagenum
      ];

      // Return the data object.
      return $data;
    }
}
