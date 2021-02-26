<?php

namespace local_lti\event;

use core\event\base;

defined('MOODLE_INTERNAL') || die;

class dashboard_viewed extends base
{
    protected function init()
    {
        $this->data['crud']     = 'r';
        $this->data['edulevel'] = self::LEVEL_OTHER;
        $this->context          = \context_system::instance();
    }

    public static function get_name()
    {
        return 'LTI dashboard viewed';
    }

    public function get_description()
    {
        $userid = $this->data['userid'];

        return "The user with id '$userid' viewed the LTI dashboard.";
    }

    public function get_url()
    {
        return new \moodle_url('/local/lti/dashboard.php');
    }
}
