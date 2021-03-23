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
use local_lti\provider\error;
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
     */
    public function get_activity_id()
    {
        try {
            $cm = get_coursemodule_from_id('page', $this->content_id, 0, false, MUST_EXIST);
        } catch (\Exception $e) {
            throw new error(error::ERROR_PAGE_ID, null, $this->consumer_id);
        }

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

    public function get_content($token, $pagenum = null)
    {
        global $DB;

        $page          = $DB->get_record('page', ['id' => $this->get_activity_id()],
            'id, name, content, revision, contentformat', MUST_EXIST);

        // Rewrite pluginfile URLs
        // Required to render database images and files
        $content = file_rewrite_pluginfile_urls($page->content,
            "local/lti/file.php?token=$token", $this->get_context()->id,
            'mod_page', 'content', $page->revision);

        // Apply filters and format the chapter text
        $content = format_text($content, $page->contentformat, [
            'noclean'     => true,
            'overflowdiv' => true,
            'context'     => $this->get_context(),
        ]);

        return $content;
    }

    public function get_title($pagenum = null)
    {
        $page = $this->get_activity_record();

        return $page->name;
    }
}

