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

use renderable;
use renderer_base;
use stdClass;
use templatable;

class error implements renderable, templatable
{

    // The error message to render
    var $message = null;

    // The error code
    var $code = null;

    public function __construct($message, $code)
    {
        $this->message = $message;
        $this->code    = $code;
    }

    /**
     * Export this data so it can be used as the context for a mustache template.
     *
     * @return stdClass
     */
    public function export_for_template(renderer_base $output)
    {
        // Data class to be sent to template
        $data          = new stdClass();
        $data->message = $this->message;
        $data->code    = $this->code;

        return $data;
    }
}
