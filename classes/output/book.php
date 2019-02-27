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

    /** @var string The current ID of the LTI session. Used to change pages without reverifying. */
    var $session_id = null;

    public function __construct($book_id, $session_id, $pagenum = null) {
      $this->book_id = $book_id;
      $this->session_id = $session_id;

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

      try {
        // Retrieve the lesson to display.
        $lesson = $DB->get_record_sql('SELECT id, pagenum, title, content
                                       FROM {book_chapters}
                                       WHERE bookid=?
                                       AND pagenum=?
                                       ORDER BY pagenum ASC', array($this->book_id, $this->pagenum));
        // Retrieve pages... Needed for table of contents.
        $pages = $DB->get_records_sql('SELECT id, pagenum, title
                                       FROM {book_chapters}
                                       WHERE bookid=?
                                       ORDER BY pagenum ASC', array($this->book_id));

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

      // Set data properties.
      $data->title = $lesson->title;
      $data->content = \local_lti\provider\util::format_content_for_mathjax($lesson->content);
      $data->pagenum = $this->pagenum;
      $data->last_page = $this->pagenum - 1;
      $data->next_page = $this->pagenum + 1;
      $data->session_id = $this->session_id;

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
