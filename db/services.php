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
 *
 * @package    local_mb2megamenu
 * @copyright  2019 - 2026 Mariusz Boloz (lmsstyle.com)
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

defined('MOODLE_INTERNAL') || die();

$functions = [
    'local_mb2megamenu_get_theme_icons' => [
            'classname' => 'local_mb2megamenu_external',
            'methodname' => 'get_theme_icons',
            'classpath' => 'local/mb2megamenu/externallib.php',
            'description' => 'Get list of theme font icons.',
            'type' => 'read',
            'ajax' => true,
            'loginrequired' => true,
            'capabilities' => '',
            'services' => [MOODLE_OFFICIAL_MOBILE_SERVICE],
    ],
    'local_mb2megamenu_image_upload' => [
            'classname' => 'local_mb2megamenu_external',
            'methodname' => 'image_upload',
            'classpath' => 'local/mb2megamenu/externallib.php',
            'description' => 'Upload image.',
            'type' => 'write',
            'ajax' => true,
            'loginrequired' => true,
            'capabilities' => 'local/mb2megamenu:manageitems',
            'services' => [MOODLE_OFFICIAL_MOBILE_SERVICE],
    ],
    'local_mb2megamenu_image_remove' => [
            'classname' => 'local_mb2megamenu_external',
            'methodname' => 'image_remove',
            'classpath' => 'local/mb2megamenu/externallib.php',
            'description' => 'Remove image.',
            'type' => 'write',
            'ajax' => true,
            'loginrequired' => true,
            'capabilities' => 'local/mb2megamenu:manageitems',
            'services' => [MOODLE_OFFICIAL_MOBILE_SERVICE],
    ],
    'local_mb2megamenu_import_menu' => [
            'classname' => 'local_mb2megamenu_external',
            'methodname' => 'import_menu',
            'classpath' => 'local/mb2megamenu/externallib.php',
            'description' => 'Import demo menu.',
            'type' => 'write',
            'ajax' => true,
            'loginrequired' => true,
            'capabilities' => 'local/mb2megamenu:manageitems',
            'services' => [MOODLE_OFFICIAL_MOBILE_SERVICE],
    ],

];
