<?php

namespace local_lti\output;

use renderable;
use renderer_base;
use templatable;
use stdClass;

class book implements renderable, templatable {

    /** @var object A custom book object to render. */
    var $book = null;

    public function __construct($book) {
      $this->book = $book;
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

      try {
        // Retrieve the lesson to display.
        $lesson = $this->book->get_lesson();

        // Retrieve pages... Needed for table of contents.
        $pages = $DB->get_records_sql('SELECT id, pagenum, title
                                       FROM {book_chapters}
                                       WHERE bookid=?
                                       ORDER BY pagenum ASC', array($this->book->get_book_id()));

      } catch(\Exception $e) {
        // Re-throw exception with custom message.
        throw new \Exception(get_string('error_retrieving_book_page', 'local_lti'));
      }

      // Set data properties.
      $data->title = $lesson->title;
      $data->content = \local_lti\provider\util::format_content_for_mathjax($lesson->content);
      $data->pagenum = $this->book->get_pagenum();
      $data->session_id = $this->book->request->get_session_id();

      // Set pages. Needed for table of contents.
      $data->pages = [];
      foreach ($pages as $page) {
        $data->pages[] = [
          'title' => $page->title,
          'pagenum' => $page->pagenum,
          'sesssion_id' => $this->session_id
        ];
      }

      // The total count of pages. Used for the loading bar.
      $data->total_pages = count($data->pages);

      // Return the data object.
      return $data;
    }
}
