<?php

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

  private $roles;

  public function __construct($roles) {
    $this->roles = $roles;
  }

  public function is_teacher() {
    return preg_match('/Instructor/', $this->roles);
  }

  public function is_content_developer() {
    return preg_match('/ContentDeveloper/', $this->roles);
  }
}
