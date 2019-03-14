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

namespace local_lti\provider;

/**
 * LTI User.
 *
 * Represents the LTI user on the consumer site.
 *
 * @package    local_lti
 * @copyright  2019 Colin Bernard
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class user {

  /** @var string LTI roles on the consumer site. */
  private $roles;

  /**
   * Initialize a new user.
   * @param string $roles LTI roles on the consumer site.
   */
  public function __construct($roles) {
    $this->roles = $roles;
  }

  /**
   * Returns true if the LTI consumer user has the role 'Instructor'.
   * @return boolean is the user a teacher?
   */
  public function is_teacher() {
    return preg_match('/Instructor/', $this->roles);
  }

  /**
   * Returns true if the LTI consumer user has the role 'ContentDeveloper'.
   * @return boolean is the user a content developer?
   */
  public function is_content_developer() {
    return preg_match('/ContentDeveloper/', $this->roles);
  }
}
