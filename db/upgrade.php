<?php

defined('MOODLE_INTERNAL') || die;

function xmldb_local_lti_upgrade($oldversion)
{
    global $DB, $CFG;

    $dbman = $DB->get_manager();

    if ($oldversion < 2021020100) {

        // Define table local_lti_request_log to be created.
        $table = new xmldb_table('local_lti_request_log');

        // Adding fields to table local_lti_request_log.
        $table->add_field('id', XMLDB_TYPE_INTEGER, '10', null, XMLDB_NOTNULL, XMLDB_SEQUENCE, null);
        $table->add_field('month', XMLDB_TYPE_INTEGER, '2', null, XMLDB_NOTNULL, null, null);
        $table->add_field('year', XMLDB_TYPE_INTEGER, '4', null, XMLDB_NOTNULL, null, null);
        $table->add_field('access_count', XMLDB_TYPE_INTEGER, '10', null, XMLDB_NOTNULL, null, null);

        // Adding keys to table local_lti_request_log.
        $table->add_key('primary', XMLDB_KEY_PRIMARY, ['id']);

        // Conditionally launch create table for local_lti_request_log.
        if (!$dbman->table_exists($table)) {
            $dbman->create_table($table);
        }

        // Lti savepoint reached.
        upgrade_plugin_savepoint(true, 2021020100, 'local', 'lti');
    }

    return true;
}
