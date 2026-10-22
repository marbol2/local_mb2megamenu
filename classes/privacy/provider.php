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

/**
 * Defines forms.
 *
 * @package    local_mb2megamenu
 * @copyright  2019 - 2026 Mariusz Boloz (lmsstyle.com)
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

namespace local_mb2megamenu\privacy;

use core_privacy\local\metadata\collection;
use core_privacy\local\request\writer;
use core_privacy\local\request\approved_contextlist;
use core_privacy\local\request\contextlist;
use core_privacy\local\request\userlist;
use core_privacy\local\request\approved_userlist;
use context_system;

/**
 * Privacy provider for local_mb2megamenu.
 */
class provider implements
    \core_privacy\local\metadata\provider,
    \core_privacy\local\request\core_userlist_provider,
    \core_privacy\local\request\core_user_data_provider,
    \core_privacy\local\request\plugin\provider,
    \core_privacy\local\request\user_preference_provider {
    /**
     * Metadata description (MUST match XMLDB fields).
     */
    public static function get_metadata(collection $collection): collection {

        $collection->add_database_table(
            'local_mb2megamenu_menus',
            [
                'createdby'    => 'privacy:metadata:menus:createdby',
                'modifiedby'   => 'privacy:metadata:menus:modifiedby',
            ],
            'privacy:metadata:menus'
        );

        return $collection;
    }

    /**
     * Contexts containing user data.
     */
    public static function get_contexts_for_userid(int $userid): contextlist {

        $contextlist = new contextlist();
        $contextlist->add_system_context();

        return $contextlist;
    }

    /**
     * Export user data.
     */
    public static function export_user_data(approved_contextlist $contextlist) {
        global $DB;

        if (empty($contextlist->get_contextids())) {
            return;
        }

        $userid = $contextlist->get_user()->id;
        $context = context_system::instance();

        $records = $DB->get_records_sql(
            "SELECT *
            FROM {local_mb2megamenu_menus}
            WHERE createdby = :createdby
                OR modifiedby = :modifiedby",
            [
                'createdby' => $userid,
                'modifiedby' => $userid,
            ]
        );

        if (!$records) {
            return;
        }

        writer::with_context($context)->export_data(
            [get_string('privacy:metadata:menus', 'local_mb2megamenu')],
            $records
        );
    }



    /**
     * Delete user data.
     */
    public static function delete_data_for_user(approved_contextlist $contextlist) {
        global $DB;

        $userid = $contextlist->get_user()->id;

        $DB->delete_records_select(
            'local_mb2megamenu_menus',
            'createdby = :createdby OR modifiedby = :modifiedby',
            [
                'createdby' => $userid,
                'modifiedby' => $userid,
            ]
        );
    }

    /**
     * Users in system context.
     */
    public static function get_users_in_context(userlist $userlist) {
        global $DB;

        $context = $userlist->get_context();

        if ($context->contextlevel !== CONTEXT_SYSTEM) {
            return;
        }

        $sql = "
            SELECT createdby AS userid FROM {local_mb2megamenu_menus}
            UNION
            SELECT modifiedby FROM {local_mb2megamenu_menus}
        ";

        $records = $DB->get_records_sql($sql);

        foreach ($records as $record) {
            if (!empty($record->userid)) {
                $userlist->add_user($record->userid);
            }
        }
    }

    /**
     * Bulk deletion.
     */
    public static function delete_data_for_users(approved_userlist $userlist) {
        global $DB;

        $context = $userlist->get_context();

        if ($context->contextlevel !== CONTEXT_SYSTEM) {
            return;
        }

        foreach ($userlist->get_userids() as $userid) {
            $DB->delete_records_select(
                'local_mb2megamenu_menus',
                'createdby = :createdby OR modifiedby = :modifiedby',
                [
                    'createdby' => $userid,
                    'modifiedby' => $userid,
                ]
            );
        }
    }

    /**
     * Delete all data.
     */
    public static function delete_data_for_all_users_in_context(\context $context) {

        if ($context->contextlevel !== CONTEXT_SYSTEM) {
            return;
        }

        global $DB;

        $DB->delete_records('local_mb2megamenu_menus');
    }

    /**
     * User preferences (none used here).
     */
    public static function export_user_preferences(int $userid) {
        return [];
    }
}
