<?php

defined('MOODLE_INTERNAL') || die;

function xmldb_local_lti_upgrade($oldversion)
{
    global $DB, $CFG;

    $dbman = $DB->get_manager();

    // Create request_log table
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
        if ( ! $dbman->table_exists($table)) {
            $dbman->create_table($table);
        }

        // Lti savepoint reached.
        upgrade_plugin_savepoint(true, 2021020100, 'local', 'lti');
    }

    // Create error_log table
    if ($oldversion < 2021020300) {
        // Define table local_lti_error_log to be created.
        $table = new xmldb_table('local_lti_error_log');

        // Adding fields to table local_lti_error_log.
        $table->add_field('id', XMLDB_TYPE_INTEGER, '10', null, XMLDB_NOTNULL, XMLDB_SEQUENCE, null);
        $table->add_field('consumer', XMLDB_TYPE_INTEGER, '10', null, null, null, null);
        $table->add_field('code', XMLDB_TYPE_TEXT, null, null, XMLDB_NOTNULL, null, null);
        $table->add_field('timecreated', XMLDB_TYPE_INTEGER, '10', null, XMLDB_NOTNULL, null, null);

        // Adding keys to table local_lti_error_log.
        $table->add_key('primary', XMLDB_KEY_PRIMARY, ['id']);

        // Conditionally launch create table for local_lti_error_log.
        if ( ! $dbman->table_exists($table)) {
            $dbman->create_table($table);
        }

        // Lti savepoint reached.
        upgrade_plugin_savepoint(true, 2021020300, 'local', 'lti');
    }

    if ($oldversion < 2021020500) {
        // Rename field created on table local_lti_consumer to timecreated.
        $table = new xmldb_table('local_lti_consumer');
        $field = new xmldb_field('created', XMLDB_TYPE_DATETIME, null, null, XMLDB_NOTNULL, null, null, 'last_access');

        // Launch rename field timecreated.
        $dbman->rename_field($table, $field, 'timecreated');

        // Rename field updated on table local_lti_consumer to timeupdated.
        $table = new xmldb_table('local_lti_consumer');
        $field = new xmldb_field('updated', XMLDB_TYPE_DATETIME, null, null, XMLDB_NOTNULL, null, null, 'timecreated');

        // Launch rename field timecreated.
        $dbman->rename_field($table, $field, 'timeupdated');

        // Changing type of field timecreated on table local_lti_consumer to int.
        $table = new xmldb_table('local_lti_consumer');
        $field = new xmldb_field('timecreated', XMLDB_TYPE_INTEGER, '10', null, XMLDB_NOTNULL, null, null,
            'last_access');

        // Launch change of type for field timecreated.
        $dbman->change_field_type($table, $field);

        // Changing type of field timeupdated on table local_lti_consumer to int.
        $table = new xmldb_table('local_lti_consumer');
        $field = new xmldb_field('timeupdated', XMLDB_TYPE_INTEGER, '10', null, XMLDB_NOTNULL, null, null,
            'timecreated');

        // Launch change of type for field timecreated.
        $dbman->change_field_type($table, $field);

        // Lti savepoint reached.
        upgrade_plugin_savepoint(true, 2021020500, 'local', 'lti');
    }

    if ($oldversion < 2021020501) {
        // Changing type of field enable_from on table local_lti_consumer to int.
        $table = new xmldb_table('local_lti_consumer');
        $field = new xmldb_field('enable_from', XMLDB_TYPE_INTEGER, '10', null, null, null, null, 'enabled');

        // Launch change of type for field enable_from.
        $dbman->change_field_type($table, $field);

        // Changing type of field enable_until on table local_lti_consumer to int.
        $table = new xmldb_table('local_lti_consumer');
        $field = new xmldb_field('enable_until', XMLDB_TYPE_INTEGER, '10', null, null, null, null, 'enable_from');

        // Launch change of type for field enable_until.
        $dbman->change_field_type($table, $field);

        // Changing type of field last_access on table local_lti_consumer to int.
        $table = new xmldb_table('local_lti_consumer');
        $field = new xmldb_field('last_access', XMLDB_TYPE_INTEGER, '10', null, null, null, null, 'enable_until');

        // Launch change of type for field last_access.
        $dbman->change_field_type($table, $field);

        // Lti savepoint reached.
        upgrade_plugin_savepoint(true, 2021020501, 'local', 'lti');
    }

    if ($oldversion < 2021020502) {
        // Reset consumer time fields as we have changed their type from datetime to int(10) above
        $consumers = $DB->get_records(\local_lti\helper\consumer::TABLE);
        foreach ($consumers as $consumer) {
            $consumer->last_access = null;
            $consumer->timecreated = time();
            $consumer->timeupdated = time();
            $DB->update_record(\local_lti\helper\consumer::TABLE, $consumer);
        }

        upgrade_plugin_savepoint(true, 2021020502, 'local', 'lti');
    }

    if ($oldversion < 2021020503) {
        // Rename field created on table local_lti_resource_link to timecreated.
        $table = new xmldb_table('local_lti_resource_link');
        $field = new xmldb_field('created', XMLDB_TYPE_DATETIME, null, null, XMLDB_NOTNULL, null, null, 'content_id');

        // Launch rename field created.
        $dbman->rename_field($table, $field, 'timecreated');

        // Changing type of field timecreated on table local_lti_resource_link to int.
        $table = new xmldb_table('local_lti_resource_link');
        $field = new xmldb_field('timecreated', XMLDB_TYPE_INTEGER, '10', null, XMLDB_NOTNULL, null, null,
            'content_id');

        // Launch change of type for field timecreated.
        $dbman->change_field_type($table, $field);

        // Changing type of field last_access on table local_lti_resource_link to int.
        $table = new xmldb_table('local_lti_resource_link');
        $field = new xmldb_field('last_access', XMLDB_TYPE_INTEGER, '10', null, null, null, null, 'access_count');

        // Launch change of type for field last_access.
        $dbman->change_field_type($table, $field);

        // Lti savepoint reached.
        upgrade_plugin_savepoint(true, 2021020503, 'local', 'lti');
    }

    if ($oldversion < 2021020504) {

        $DB->execute("UPDATE {local_lti_resource_link} SET last_access = null AND timecreated = :timecreated", ['timecreated' => time()]);

        // Lti savepoint reached.
        upgrade_plugin_savepoint(true, 2021020504, 'local', 'lti');
    }

    return true;
}
