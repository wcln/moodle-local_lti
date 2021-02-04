<?php
// This file is part of Moodle - http://moodle.org/
//
// Moodle is free software: you can redistribute it and/or modify
// it under the terms of the GNU General Public License as published by
// the Free Software Foundation, either version 3 of the License, or
// (at your option) any later version.
//
// Moodle is distributed in the hope that it will be useful,
// but WITHOUT ANY WARRANTY; without even the implied warranty of
// MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
// GNU General Public License for more details.
//
// You should have received a copy of the GNU General Public License
// along with Moodle.  If not, see <http://www.gnu.org/licenses/>.

namespace local_lti\output;

use Exception;
use renderable;
use renderer_base;
use stdClass;
use templatable;

require_once($CFG->libdir.'/filelib.php');

class book implements renderable, templatable
{

    /** @var object A custom book object to render. */
    var $book = null;

    public function __construct($book)
    {
        $this->book = $book;
    }

    /**
     * Export this data so it can be used as the context for a mustache template.
     *
     * @return stdClass
     */
    public function export_for_template(renderer_base $output)
    {
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
                                       ORDER BY pagenum ASC', array(\local_lti\resource_type\book::get_activity_id($this->book->content_id)));
        } catch (Exception $e) {
            // Re-throw exception with custom message.
            throw new Exception(get_string('error_retrieving_book_page', 'local_lti'));
        }

        // Set title.
        $data->title = $lesson->title;

        // Retrieve and set the current request session ID.
        // Will be used to verify subsequent requests coming from this book.
        $data->session_id = $this->book->request->get_session_id();

        // Rewrite pluginfile URLs.
        // Required to render database images and files.
        $chaptertext = file_rewrite_pluginfile_urls($lesson->content, "local/lti/file.php?sessid=$data->session_id",
            $this->book->get_context()->id, 'mod_book', 'chapter', $lesson->id);

        // Apply filters and format the chapter text.
        $data->content = format_text($chaptertext, $lesson->contentformat, array(
            'noclean'     => true,
            'overflowdiv' => true,
            'context'     => $this->book->get_context(),
        ));

        // Set the page number.
        $data->pagenum = $this->book->get_pagenum();

        // Set pages. Needed for table of contents.
        $data->pages = [];
        foreach ($pages as $page) {
            $data->pages[] = [
                'title'       => $page->title,
                'pagenum'     => $page->pagenum,
                'sesssion_id' => $data->session_id,
            ];
        }

        // The total count of pages. Used for the loading bar.
        $data->total_pages = count($data->pages);

        // The URL to return to the course. Will be used for the back to course button.
        $data->back_to_course_url = $this->book->request->get_parameter('launch_presentation_return_url');

        // Return the data object.
        return $data;
    }
}
