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

defined('MOODLE_INTERNAL') || die;

require_once($CFG->libdir . '/externallib.php');
require_once(__DIR__ . '/lib.php');
require_once(__DIR__ . '/classes/api.php');
require_once(__DIR__ . '/classes/settings.php');

/**
 * External theme API
 */
class local_mb2megamenu_external extends \core_external\external_api {
    /**
     *
     * Method to get a list of all services.
     *
     */
    public static function get_theme_icons() {

        $params = self::validate_parameters(self::get_theme_icons_parameters(), []);

        // Validate context.
        $context = context_system::instance();
        self::validate_context($context);
        require_capability('local/mb2megamenu:manageitems', $context);

        $results = [
            'icons' => Mb2megamenuSettings::font_icon_tabs(),
        ];

        return $results;
    }




    /**
     * Describes the parameters for get_theme_icons webservice.
     */
    public static function get_theme_icons_parameters() {
        return new \core_external\external_function_parameters(
            []
        );
    }



    /**
     * Describes the return for get_theme_icons
     */
    public static function get_theme_icons_returns() {
        return new \core_external\external_single_structure(
            [
                'icons' => new \core_external\external_value(PARAM_RAW, 'Theme font icons list'),
                'warnings' => new \core_external\external_warnings(),
            ]
        );
    }



    /**
     *
     * Method to get a tabs of all icons.
     *
     */
    public static function image_upload($mediafile) {

        $params = self::validate_parameters(self::image_upload_parameters(), [
            'mediafile' => $mediafile,
        ]);

        // Validate context.
        $context = context_system::instance();
        self::validate_context($context);
        require_capability('local/mb2megamenu:manageitems', $context);

        Mb2megamenuApi::save_file($params['mediafile']);

        $results = [
            'images' => Mb2megamenuSettings::image_preview(),
        ];

        return $results;
    }


    /**
     * Describes the parameters for image_upload webservice.
     */
    public static function image_upload_parameters() {
        return new \core_external\external_function_parameters(
            [
                'mediafile' => new \core_external\external_value(PARAM_INT, 'Media file ID.'),
            ]
        );
    }





    /**
     * Describes the return for image_upload
     */
    public static function image_upload_returns() {
        return new \core_external\external_single_structure(
            [
                'images' => new \core_external\external_value(PARAM_RAW, 'HTML of the image preview list.'),
                'warnings' => new \core_external\external_warnings(),
            ]
        );
    }



    /**
     *
     * Method to get a tabs of all icons.
     *
     */
    public static function image_remove($imgname) {

        $params = self::validate_parameters(self::image_remove_parameters(), [
            'imgname' => $imgname,
        ]);

        // Validate context.
        $context = context_system::instance();
        self::validate_context($context);
        require_capability('local/mb2megamenu:manageitems', $context);

        Mb2megamenuApi::delete_file($params['imgname']);

        $results = [
            'images' => Mb2megamenuSettings::image_preview(),
        ];

        return $results;
    }



    /**
     * Describes the parameters for image_remove webservice.
     */
    public static function image_remove_parameters() {
        return new \core_external\external_function_parameters(
            [
                'imgname' => new \core_external\external_value(PARAM_TEXT, 'Image name.'),
            ]
        );
    }



    /**
     * Describes the return for image_remove
     */
    public static function image_remove_returns() {
        return new \core_external\external_single_structure(
            [
                'images' => new \core_external\external_value(PARAM_RAW, 'HTML of the image preview list.'),
                'warnings' => new \core_external\external_warnings(),
            ]
        );
    }






    /**
     *
     * Method to import demo menus
     *
     */
    public static function import_menu($dataid) {

        $params = self::validate_parameters(self::import_menu_parameters(), [
            'dataid' => $dataid,
        ]);

        // Validate context.
        $context = context_system::instance();
        self::validate_context($context);
        require_capability('local/mb2megamenu:manageitems', $context);

        $data = Mb2megamenuApi::menu_importer_data($params['dataid']);
        $result = Mb2megamenuApi::add_record($data);

        $results = [
            'result' => $result,
        ];

        return $results;
    }



    /**
     * Describes the parameters for import_menu webservice.
     */
    public static function import_menu_parameters() {
        return new \core_external\external_function_parameters(
            [
                'dataid' => new \core_external\external_value(PARAM_TEXT, 'Menu import file name.'),
            ]
        );
    }



    /**
     * Describes the return for import_menu
     */
    public static function import_menu_returns() {
        return new \core_external\external_single_structure(
            [
                'result' => new \core_external\external_value(PARAM_BOOL, 'The processing result'),
                'warnings' => new \core_external\external_warnings(),
            ]
        );
    }
}
