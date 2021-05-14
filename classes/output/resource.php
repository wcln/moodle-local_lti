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

use Firebase\JWT\JWT;
use local_lti\provider\util;
use renderable;
use renderer_base;
use stdClass;
use templatable;

class resource implements renderable, templatable
{

    var $resource_id = null;
    var $return_url = null;

    public function __construct($resource_id, $return_url)
    {
        $this->resource_id = $resource_id;
        $this->return_url  = $return_url;
    }

    /**
     * Export this data so it can be used as the context for a mustache template.
     *
     * @return stdClass
     */
    public function export_for_template(renderer_base $output)
    {
        $data = new stdClass();

        // Generate a JWT so the Vue app can make subsequent requests for information
        $data->token = JWT::encode(['resource_id' => $this->resource_id],
            util::get_secret());

        $data->return_url = $this->return_url;

        return $data;
    }
}
