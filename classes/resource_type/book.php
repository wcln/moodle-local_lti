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

namespace local_lti\resource_type;

use local_lti\provider\resource;

/**
 * Book
 *
 * Represents a Moodle Book.
 * Contains custom render code.
 *
 * @package    local_lti
 * @copyright  2019 Colin Bernard
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class book extends resource {

    /** @var int The page number of the resource to retrieve. */
    private $pagenum = 1;

    /**
     * Returns the ID of this Book.
     *
     * @return int book ID.
     */
    public function get_book_id() {
        global $DB;

        try {

            // Get the book object using the course module ID.
            $cm     = get_coursemodule_from_id('book', $this->content_id, 0, false, MUST_EXIST);
            $course = $DB->get_record('course', array('id' => $cm->course), '*', MUST_EXIST);
            $book   = $DB->get_record('book', array('id' => $cm->instance), '*', MUST_EXIST);

            return $book->id;

        } catch (\Exception $e) {
            throw new \Exception(get_string('error_book_id', 'local_lti'));
        }
    }

    /**
     * Retrieve the context module instance of this book.
     *
     * @return context
     */
    public function get_context() {
        return \context_module::instance($this->content_id);
    }

    /**
     * Returns a lesson from within this book.
     *
     * @param int $pagenum Page number to return.
     *
     * @return object          Lesson object.
     */
    public function get_lesson($pagenum = null) {
        global $DB;

        if (is_null($pagenum)) {
            $pagenum = $this->pagenum;
        }

        $lesson = $DB->get_record_sql('SELECT id, title, content, contentformat
                                   FROM {book_chapters}
                                   WHERE bookid=?
                                   AND pagenum=?
                                   ORDER BY pagenum ASC', array($this->get_book_id(), $pagenum));

        return $lesson;
    }

    /**
     * Renders the book using a template.
     */
    public function render() {
        global $PAGE;

        // Ensure this resource exists in the local_lti_resource_link table, and update it.
        parent::update_link();

        // Get the plugin renderer.
        $renderer = $PAGE->get_renderer('local_lti');

        try {

            // Render book.
            $book = new \local_lti\output\book($this);
            echo $renderer->render($book);

        } catch (\Exception $e) {
            throw new \Exception(get_string('error_rendering_book', 'local_lti'));
        }
    }

    /**
     * Returns the current page number of this book.
     *
     * @return int Page number.
     */
    public function get_pagenum() {
        return $this->pagenum;
    }

    /**
     * Sets the current page number of the book.
     * Only used for non-AJAX page navigation.
     *
     * @param int $pagenum Page number.
     */
    public function set_pagenum($pagenum) {
        $this->pagenum = $pagenum;
    }
}
