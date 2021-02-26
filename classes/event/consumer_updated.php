<?php

namespace local_lti\event;

use core\event\base;
use local_lti\helper\consumer;

defined('MOODLE_INTERNAL') || die;

class consumer_updated extends base
{
    protected function init()
    {
        $this->data['crud']        = 'u';
        $this->data['edulevel']    = self::LEVEL_OTHER;
        $this->data['objecttable'] = consumer::TABLE;
        $this->context             = \context_system::instance();
    }

    public static function get_name()
    {
        return 'LTI consumer updated';
    }

    public function get_description()
    {
        $userid      = $this->data['userid'];
        $consumer_id = $this->data['objectid'];

        return "The user with id '$userid' updated a consumer with id '$consumer_id'";
    }

    public function get_url()
    {
        return new \moodle_url('/local/lti/dashboard.php/consumers');
    }
}
