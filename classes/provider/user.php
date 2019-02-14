<?php

namespace local_lti\provider;

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
