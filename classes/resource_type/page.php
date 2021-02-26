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
use local_lti\provider\resource;

/**
 * Page
 *
 * Represents a Moodle Page.
 * Contains custom render code.
 *
 * @package    local_lti
 * @copyright  2021 Colin Perepelken (colin@lingellearning.com)
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class page extends resource
{

    const TABLE = 'page';

    /** @var int The course module instance aka the page ID. */
    var $page_id = null;

    /**
     * Renders the page using a template.
     */
    public function render()
    {
        global $PAGE;

        // Ensure this resource exists in the local_lti_resource_link table, and update it.
        parent::update_link();

        // Get the plugin renderer.
        $renderer = $PAGE->get_renderer('local_lti');

        // Get the ID of this page
        $this->page_id = self::get_activity_id($this->content_id);

        // Render page.
        $page = new \local_lti\output\page($this);
        echo $renderer->render($page);
    }

    /**
     * Get context object for this page.
     *
     * @return context_module
     */
    public function get_context()
    {
        return context_module::instance(get_coursemodule_from_id('page', $this->content_id)->id);
    }

    /**
     * Get the ID of this page activity
     *
     * @return int
     * @throws \coding_exception
     */
    public function get_activity_id()
    {
        $cm = get_coursemodule_from_id('page', $this->content_id, 0, false, MUST_EXIST);

        return $cm->instance;
    }

    /**
     * Get the page record from the database
     *
     * @return mixed|void
     * @throws \coding_exception
     * @throws \dml_exception
     */
    public function get_activity_record()
    {
        global $DB;

        return $DB->get_record(self::TABLE, ['id' => $this->get_activity_id()]);
    }
}

