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

use context_module;
use Exception;
use local_lti\provider\error;
use local_lti\provider\resource;

/**
 * Book
 *
 * Represents a Moodle Book.
 * Contains custom render code.
 *
 * @package    local_lti
 * @copyright  2021 Colin Perepelken (colin@lingellearning.com)
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class book extends resource
{

    const TABLE = 'book';

    /** @var int The page number of the resource to retrieve. */
    private $pagenum = 1;

    /**
     * Retrieve the context module instance of this book.
     *
     * @return context
     */
    public function get_context()
    {
        return context_module::instance($this->content_id);
    }

    /**
     * Returns a chapter from within this book.
     *
     * @param  int  $pagenum  Page number to return.
     *
     * @return object          chapter object.
     */
    public function get_chapter($pagenum = null)
    {
        global $DB;

        if (is_null($pagenum)) {
            $pagenum = $this->pagenum;
        }

        $chapter = $DB->get_record_sql('SELECT id, title, content, contentformat
                                   FROM {book_chapters}
                                   WHERE bookid=?
                                   AND pagenum=?
                                   ORDER BY pagenum ASC', [$this->get_activity_id(), $pagenum]);

        return $chapter;
    }

    /**
     * Renders the book using a template.
     */
    public function render()
    {
        global $PAGE;

        // Ensure this resource exists in the local_lti_resource_link table, and update it.
        parent::update_link();

        // Get the plugin renderer.
        $renderer = $PAGE->get_renderer('local_lti');

        try {
            // Render book.
            $book = new \local_lti\output\book($this);
            echo $renderer->render($book);
        } catch (Exception $e) {
            throw new error(error::ERROR_RENDERING_BOOK, null, $this->consumer_id);
        }
    }

    /**
     * Returns the current page number of this book.
     *
     * @return int Page number.
     */
    public function get_pagenum()
    {
        return $this->pagenum;
    }

    /**
     * Sets the current page number of the book.
     * Only used for non-AJAX page navigation.
     *
     * @param  int  $pagenum  Page number.
     */
    public function set_pagenum($pagenum)
    {
        $this->pagenum = $pagenum;
    }

    /**
     * Get the ID of this book activity
     *
     * @return int
     * @throws \coding_exception
     */
    public function get_activity_id()
    {
        global $DB;

        try {
            // Get the book object using the course module ID.
            $cm   = get_coursemodule_from_id('book', $this->content_id, 0, false, MUST_EXIST);
            $book = $DB->get_record('book', ['id' => $cm->instance], '*', MUST_EXIST);

            return $book->id;
        } catch (Exception $e) {
            throw new error(error::ERROR_BOOK_ID, null, $this->consumer_id);
        }
    }

    /**
     * Get the book record from mdl_book table
     *
     * @return false|mixed|\stdClass
     * @throws \coding_exception
     * @throws \dml_exception
     */
    public function get_activity_record()
    {
        global $DB;

        return $DB->get_record(self::TABLE, ['id' => $this->get_activity_id()]);
    }
}
