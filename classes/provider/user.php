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
