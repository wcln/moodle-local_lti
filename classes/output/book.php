<?php

namespace local_lti\output;

use renderable;
use renderer_base;
use templatable;
use stdClass;

class book implements renderable, templatable {

    // The ID of the Moodle book to render.
    var $book_id = null;

    public function __construct($book_id) {
      $this->book_id = $book_id;
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

      // Lessons array to be formatted and sent to template.
      $data->lessons = [];

      // Retrieve lessons from the database.
      $lessons = $DB->get_records_sql('SELECT id, pagenum, title, content FROM {book_chapters} WHERE bookid=? ORDER BY pagenum ASC', array($this->book_id));

      // For each lesson in this book.
      foreach ($lessons as $lesson) {

        // Replace characters to enable MathJax to filter WIRIS XML.
        $lesson->content = str_replace('«', '<', $lesson->content);
        $lesson->content = str_replace('»', '>', $lesson->content);
        $lesson->content = str_replace('§', '&', $lesson->content);
        $lesson->content = str_replace('¨', '"', $lesson->content);
        $lesson->content = str_replace('´', "'", $lesson->content);

        // Push each lesson onto the lessons array.
        $data->lessons[] = [
          'title' => $lesson->title,
          'content' => $lesson->content,
          'pagenum' => $lesson->pagenum
        ];
      }

      // The total count of pages. Used for the loading bar.
      $data->total_pages = count($data->lessons);

      // Return the data object.
      return $data;
    }
}
