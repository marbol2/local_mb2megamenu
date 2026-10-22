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

defined('MOODLE_INTERNAL') || die();

require_once(__DIR__ . '/api.php');
require_once(__DIR__ . '/settings.php');

if (!class_exists('Mb2megamenuBuilder')) {

    /**
     *
     * Builder class
     */
    class Mb2megamenuBuilder {
        /**
         *
         * Method to get menu builder
         */
        public static function get_menu_builder() {
            global $OUTPUT;

            $itemid = optional_param('itemid', 0, PARAM_INT);
            $record = $itemid ? Mb2megamenuApi::get_record($itemid) : new stdClass();

            $data = !empty($record->menuitems)
                ? json_decode($record->menuitems)
                : [];

            if (!is_array($data) && !is_object($data)) {
                $data = [];
            }

            return $OUTPUT->render_from_template(
                'local_mb2megamenu/builder/menu',
                [
                    'languageel' => self::language_el(),
                    'hasitems' => !empty($data),
                    'items' => self::prepare_menu_items($data),
                    'additem' => get_string('additem', 'local_mb2megamenu'),
                ]
            );
        }


        /**
         *
         * Method to get menu item HTML
         */
        public static function get_menu_item_html($items) {
            global $OUTPUT;

            return $OUTPUT->render_from_template(
                'local_mb2megamenu/builder/menu-items',
                [
                    'items' => self::prepare_menu_items($items),
                ]
            );
        }

        /**
         * Prepare menu items for Mustache rendering.
         *
         * @param array $items Menu items.
         * @return array
         */
        private static function prepare_menu_items($items) {
            $output = [];
            $showlanguageopts = (bool) self::language_labels();

            foreach ($items as $item) {
                $settings = $item->settings;

                $hide = !empty($settings->hide);
                $label = !empty($settings->label)
                    ? $settings->label
                    : get_string('nolabel', 'local_mb2megamenu');

                $blank = $settings->blank ?? 0;

                $output[] = [
                    'datatts' => self::get_item_settings($settings),
                    'hidecls' => $hide ? ' hideon' : '',
                    'hideicon' => $hide ? 'bi bi-eye-slash' : 'bi bi-eye',

                    'label' => $label,
                    'inputlabel' => $settings->label ?? '',
                    'hide' => $hide ? 1 : 0,
                    'url' => $settings->url ?? '',
                    'blank' => $blank,
                    'blankchecked' => !empty($blank),

                    'edit' => get_string('edit'),
                    'urltext' => get_string('url'),
                    'linktarget' => get_string('linktarget', 'local_mb2megamenu'),
                    'navlabel' => get_string('navlabel', 'local_mb2megamenu'),
                    'menuitemopts' => get_string('menuitemopts', 'local_mb2megamenu'),
                    'languageopts' => get_string('languageopts', 'local_mb2megamenu'),
                    'showlanguageopts' => $showlanguageopts,
                    'remove' => get_string('remove'),
                    'cancel' => get_string('cancel'),

                    'children' => self::prepare_menu_items($item->attr ?? []),
                ];
            }

            return $output;
        }



        /**
         *
         * Method to get menu item settings
         */
        public static function get_item_settings($settings) {
            $output = '';

            foreach ($settings as $k => $v) {
                $output .= ' data-' . $k . '="' . $v . '"';
            }

            return $output;
        }


        /**
         *
         * Method to get menu item language element
         */
        public static function language_el() {

            $output = '';
            $strings = self::language_strings();

            $attributes = [
                'id' => 'mb2megamenu-lang',
            ];

            foreach ($strings as $id => $string) {
                $attributes['data-' . $id] = $string;
            }

            $output .= html_writer::start_tag('div', $attributes);
            $output .= html_writer::end_tag('div');

            return $output;
        }



        /**
         *
         * Method to get menu item language labels
         */
        public static function language_labels() {
            global $CFG, $OUTPUT;

            $langarr = get_string_manager()->get_list_of_translations();

            if (count($langarr) <= 1) {
                return '';
            }

            $flagfile = (string) $OUTPUT->image_url('noflag', 'theme_mb2nl');
            $flagimages = function_exists('theme_mb2nl_filearea')
                ? theme_mb2nl_filearea('langflags')
                : [];

            $languages = [];

            foreach ($langarr as $k => $v) {
                if ($CFG->lang === $k) {
                    continue;
                }

                $flag = array_key_exists($k, $flagimages)
                    ? $flagimages[$k]
                    : $flagfile;

                $languages[] = [
                    'name' => $v,
                    'flag' => (string) $flag,
                    'labelname' => 'label__' . $k,
                    'sublabelname' => 'sublabel__' . $k,
                    'hlabelname' => 'hlabel__' . $k,
                    'sublabel2name' => 'sublabel2__' . $k,
                ];
            }

            if (empty($languages)) {
                return '';
            }

            return $OUTPUT->render_from_template(
                'local_mb2megamenu/builder/form-language',
                ['languages' => $languages],
            );
        }


        /**
         *
         * Method to get language strings
         */
        public static function language_strings() {

            return [
                'remove' => get_string('remove'),
                'edit' => get_string('edit'),
                'cancel' => get_string('cancel'),
                'hide' => get_string('hide'),
                'url' => get_string('url'),
                'moveup' => get_string('moveup'),
                'movedown' => get_string('movedown'),
                'navlabel' => get_string('navlabel', 'local_mb2megamenu'),
                'menuitem' => get_string('menuitem', 'local_mb2megamenu'),
                'nolabel' => get_string('nolabel', 'local_mb2megamenu'),
                'linktarget' => get_string('linktarget', 'local_mb2megamenu'),
                'menuitemopts' => get_string('menuitemopts', 'local_mb2megamenu'),
                'levelup' => get_string('levelup', 'local_mb2megamenu'),
                'leveldown' => get_string('leveldown', 'local_mb2megamenu'),
                'languageopts' => get_string('languageopts', 'local_mb2megamenu'),
                'show' => get_string('show'),
            ];
        }



        /**
         *
         * Method to get modal template
         */
        public static function get_modal_template($type, $size = 'md') {
            global $CFG, $OUTPUT;

            $title = '';
            $content = '';
            $footerbuttons = [];

            $cancelbutton = [
                'class' => 'mb2-pb-btn sizesm mb2megamenu-modalbtn-cancel',
                'dismiss' => 'mb2modal',
                'label' => get_string('cancel'),
            ];

            switch ($type) {
                case 'settings':
                    $title = get_string('menuitemopts', 'local_mb2megamenu');
                    $content = Mb2megamenuSettings::menuitem_settings_form();

                    $footerbuttons[] = $cancelbutton;
                    $footerbuttons[] = [
                        'class' => 'mb2-pb-btn typesuccess sizesm mb2megamenu-modalbtn-save',
                        'label' => get_string('save'),
                    ];
                    break;

                case 'icons':
                    $title = get_string('icon');
                    // Content is loaded via JavaScript.

                    $footerbuttons[] = $cancelbutton;
                    break;

                case 'images':
                    $title = get_string('bgimage', 'local_mb2megamenu');
                    $content = Mb2megamenuSettings::images();

                    $footerbuttons[] = $cancelbutton;
                    $footerbuttons[] = [
                        'class' => 'mb2-pb-btn sizesm typesuccess mb2megamenu-modalbtn-cancel',
                        'modal' => '#mb2megamenu-modal-file-manager',
                        'label' => get_string('upload'),
                    ];
                    break;

                case 'file-manager':
                    $title = get_string('bgimage', 'local_mb2megamenu');
                    $content = Mb2megamenuApi::file_manager_iframe();

                    $footerbuttons[] = $cancelbutton;
                    $footerbuttons[] = [
                        'id' => 'applay-file-manager',
                        'class' => 'mb2-pb-btn sizesm typesuccess',
                        'label' => get_string('save'),
                    ];
                    break;

                case 'languages':
                    $title = get_string('languageopts', 'local_mb2megamenu');
                    $content = self::language_labels();

                    $footerbuttons[] = $cancelbutton;
                    $footerbuttons[] = [
                        'class' => 'mb2-pb-btn sizesm typesuccess mb2megamenu-lang_modalbtn-save',
                        'label' => get_string('save'),
                    ];
                    break;
            }

            return $OUTPUT->render_from_template(
                'local_mb2megamenu/builder/modal',
                [
                    'type' => $type,
                    'size' => $size,
                    'lang' => $CFG->lang,
                    'islangopt' => count(get_string_manager()->get_list_of_translations()) > 1 ? 1 : 0,
                    'title' => $title,
                    'content' => $content,
                    'footerbuttons' => $footerbuttons,
                ]
            );
        }
    }

}
