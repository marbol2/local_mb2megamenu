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

if (!class_exists('Mb2megamenuSettings')) {

    /**
     *
     * Settings class
     */
    class Mb2megamenuSettings {
        /**
         *
         * Method to set menu item settings form in modal window
         *
         */
        public static function menuitem_settings_form() {
            $output = '';
            $settings = self::menuitem_settings();

            foreach ($settings as $setting) {
                $levelcls = $setting['level'] == -1 ? 'all' : $setting['level'];

                $output .= html_writer::start_tag('div', ['class' => 'mb2megamenu-modalform-item level-' . $levelcls]);

                switch ($setting['type']) {
                    case 'select':
                        $output .= self::field_select($setting);
                        break;

                    case 'checkbox':
                        $output .= self::field_checkbox($setting);
                        break;

                    case 'icon':
                        $output .= self::field_icon($setting);
                        break;

                    case 'image':
                        $output .= self::field_image($setting);
                        break;

                    case 'color':
                        $output .= self::field_color($setting);
                        break;

                    case 'textarea':
                        $output .= self::field_textarea($setting);
                        break;

                    case 'number':
                        $output .= self::field_number($setting);
                        break;

                    default:
                        $output .= self::field_text($setting);
                        break;
                }

                $output .= html_writer::end_tag('div'); // ...mb2megamenu-modalform-item
            }

            return $output;
        }




        /**
         *
         * Method to define menu item settings
         *
         */
        public static function menuitem_settings() {

            // Level visible:
            // -1 - all menu items,
            // 1 - mega menu parent item,
            // 2 - mega menu parent item AND mega menu columns,
            // 3 - mega menu columns items.

            $settings = [
                [
                    'id' => 'hidetext',
                    'type' => 'checkbox',
                    'label' => get_string('hidetext', 'local_mb2megamenu'),
                    'desc' => get_string('hidetextdesc', 'local_mb2megamenu'),
                    'options' => [
                        1 => get_string('yes'),
                        0 => get_string('no'),
                    ],
                    'default' => 0,
                    'level' => 3,
                ],
                [
                    'id' => 'hcolor',
                    'type' => 'color',
                    'label' => get_string('textcolor', 'local_mb2megamenu'),
                    'twinlabel1' => get_string('normalstate', 'local_mb2megamenu'),
                    'twinlabel2' => get_string('hoverstate', 'local_mb2megamenu'),
                    'desc' => '',
                    'default' => '',
                    'twin' => [
                        'id' => 'hhcolor',
                        'type' => 'color',
                        'label' => '',
                        'desc' => '',
                        'default' => '',
                    ],
                    'level' => 3,
                ],
                [
                    'id' => 'sublabel',
                    'type' => 'textarea',
                    'label' => get_string('sublabel', 'local_mb2megamenu'),
                    'desc' => get_string('sublabeldesc', 'local_mb2megamenu'),
                    'default' => '',
                    'children' => [
                        [
                            'id' => 'childlabel',
                            'content' => get_string('textcolor', 'local_mb2megamenu'),
                        ],
                        [
                            'id' => 'hsubcolor',
                            'type' => 'color',
                            'label' => get_string('normalstate', 'local_mb2megamenu'),
                            'desc' => '',
                            'default' => '',
                        ],
                        [
                            'id' => 'hsubhcolor',
                            'type' => 'color',
                            'label' => get_string('hoverstate', 'local_mb2megamenu'),
                            'desc' => '',
                            'default' => '',
                        ],
                        'level' => 3,
                    ],
                    'level' => -1,
                ],
                [
                    'id' => 'sublabel2',
                    'type' => 'text',
                    'label' => get_string('sublabel2', 'local_mb2megamenu'),
                    'desc' => get_string('sublabel2desc', 'local_mb2megamenu'),
                    'default' => '',
                    'children' => [
                        [
                            'id' => 'childlabel',
                            'content' => get_string('textcolor', 'local_mb2megamenu'),
                        ],
                        [
                            'id' => 'hsub2color',
                            'type' => 'color',
                            'label' => get_string('normalstate', 'local_mb2megamenu'),
                            'desc' => '',
                            'default' => '',
                        ],
                        [
                            'id' => 'hsub2hcolor',
                            'type' => 'color',
                            'label' => get_string('hoverstate', 'local_mb2megamenu'),
                            'desc' => '',
                            'default' => '',
                        ],
                        'level' => 3,
                    ],
                    'level' => 3,
                ],
                [
                    'id' => 'hlabel',
                    'type' => 'text',
                    'label' => get_string('hlabel', 'local_mb2megamenu'),
                    'desc' => get_string('hlabeldesc', 'local_mb2megamenu'),
                    'default' => '',
                    'children' => [
                        [
                            'id' => 'hlabelcolor',
                            'type' => 'color',
                            'label' => get_string('textcolor', 'local_mb2megamenu'),
                            'desc' => '',
                            'default' => '',
                        ],
                        [
                            'id' => 'hlabelbgcolor',
                            'type' => 'color',
                            'label' => get_string('bgcolor', 'local_mb2megamenu'),
                            'desc' => '',
                            'default' => '',
                        ],
                        'level' => -1,
                    ],
                    'level' => -1,
                ],
                [
                    'id' => 'icon',
                    'type' => 'icon',
                    'label' => get_string('icon'),
                    'desc' => '',
                    'default' => '',
                    'children' => [
                        [
                            'id' => 'childlabel',
                            'content' => get_string('hiconcolor', 'local_mb2megamenu'),
                        ],
                        [
                            'id' => 'hiconcolor',
                            'type' => 'color',
                            'label' => get_string('normalstate', 'local_mb2megamenu'),
                            'desc' => '',
                            'default' => '',
                        ],
                        [
                            'id' => 'hiconhcolor',
                            'type' => 'color',
                            'label' => get_string('hoverstate', 'local_mb2megamenu'),
                            'desc' => '',
                            'default' => '',
                        ],
                        [
                            'id' => 'childlabel',
                            'content' => get_string('hiconbgcolor', 'local_mb2megamenu'),
                        ],
                        [
                            'id' => 'hiconbgcolor',
                            'type' => 'color',
                            'label' => get_string('normalstate', 'local_mb2megamenu'),
                            'desc' => '',
                            'default' => '',
                        ],
                        [
                            'id' => 'hiconhbgcolor',
                            'type' => 'color',
                            'label' => get_string('hoverstate', 'local_mb2megamenu'),
                            'desc' => '',
                            'default' => '',
                        ],
                        'level' => 3,
                    ],
                    'level' => -1,
                ],
                [
                    'id' => 'image',
                    'type' => 'image',
                    'label' => get_string('bgimage', 'local_mb2megamenu'),
                    'desc' => get_string('bgimagedesc', 'local_mb2megamenu'),
                    'default' => '',
                    'level' => 2,
                ],
                [
                    'id' => 'bgcolor',
                    'type' => 'color',
                    'label' => get_string('bgcolor', 'local_mb2megamenu'),
                    'desc' => get_string('bgcolordesc', 'local_mb2megamenu'),
                    'twinlabel1' => get_string('normalstate', 'local_mb2megamenu'),
                    'twinlabel2' => get_string('hoverstate', 'local_mb2megamenu'),
                    'default' => '',
                    'twin' => [
                        'id' => 'bghcolor',
                        'type' => 'color',
                        'label' => '',
                        'desc' => '',
                        'default' => '',
                    ],
                    'level' => 2,
                ],
                [
                    'id' => 'cwidth',
                    'type' => 'number',
                    'min' => 100,
                    'max' => 900,
                    'label' => get_string('cwidth', 'local_mb2megamenu'),
                    'desc' => get_string('cwidthdesc', 'local_mb2megamenu'),
                    'default' => '',
                    'level' => 3,
                ],
                [
                    'id' => 'mega',
                    'type' => 'checkbox',
                    'label' => get_string('megamenu', 'local_mb2megamenu'),
                    'desc' => get_string('megamenudesc', 'local_mb2megamenu'),
                    'options' => [
                        1 => get_string('yes'),
                        0 => get_string('no'),
                    ],
                    'default' => 0,
                    'level' => 1,
                ],
                [
                    'id' => 'columns',
                    'type' => 'select',
                    'label' => get_string('columns', 'local_mb2megamenu'),
                    'desc' => get_string('columnsdesc', 'local_mb2megamenu'),
                    'options' => [
                        1 => '1',
                        2 => '2',
                        3 => '3',
                        4 => '4',
                        5 => '5',
                    ],
                    'default' => 3,
                    'level' => 1,
                ],
                [
                    'id' => 'distcols',
                    'type' => 'checkbox',
                    'label' => get_string('distcols', 'local_mb2megamenu'),
                    'desc' => get_string('distcolsdesc', 'local_mb2megamenu'),
                    'options' => [
                        1 => get_string('yes'),
                        0 => get_string('no'),
                    ],
                    'default' => 0,
                    'level' => 1,
                ],
                [
                    'id' => 'width',
                    'type' => 'select',
                    'label' => get_string('megawidth', 'local_mb2megamenu'),
                    'desc' => get_string('megawidthdesc', 'local_mb2megamenu'),
                    'options' => [
                        'aw' => get_string('auto', 'local_mb2megamenu'),
                        'fw' => get_string('maxwidth', 'local_mb2megamenu'),
                        'cw' => get_string('contentwidth', 'local_mb2megamenu'),
                    ],
                    'default' => 'aw',
                    'level' => 1,
                ],
                [
                    'id' => 'cssclass',
                    'type' => 'text',
                    'label' => get_string('cssclass', 'local_mb2megamenu'),
                    'desc' => get_string('cssclassdesc', 'local_mb2megamenu'),
                    'default' => '',
                    'level' => -1,
                ],
            ];

            return $settings;
        }




        /**
         *
         * Method to set list form filed
         *
         */
        public static function field_select($setting) {
            global $OUTPUT;

            $options = [];

            foreach ($setting['options'] as $k => $opt) {
                $options[] = [
                    'value' => $k,
                    'label' => $opt,
                ];
            }

            return $OUTPUT->render_from_template(
                'local_mb2megamenu/field/type/select',
                [
                    'label' => self::field_item_label($setting),
                    'id' => $setting['id'],
                    'type' => $setting['type'],
                    'default' => $setting['default'],
                    'options' => $options,
                ]
            );
        }





        /**
         *
         * Method to set checkbox form filed
         *
         */
        public static function field_checkbox($setting) {
            global $OUTPUT;

            return $OUTPUT->render_from_template(
                'local_mb2megamenu/field/type/checkbox',
                [
                    'label' => self::field_item_label($setting),
                    'type' => $setting['type'],
                    'default' => $setting['default'],
                    'id' => $setting['id'],
                    'children' => self::field_children($setting),
                ]
            );
        }





        /**
         *
         * Method to set checkbox form filed
         *
         */
        public static function field_icon($setting) {
            global $OUTPUT;

            return $OUTPUT->render_from_template(
                'local_mb2megamenu/field/type/icon',
                [
                    'label' => self::field_item_label($setting),
                    'type' => $setting['type'],
                    'id' => $setting['id'],
                    'default' => $setting['default'],
                    'children' => self::field_children($setting),
                ]
            );
        }




        /**
         *
         * Method to set checkbox form filed
         *
         */
        public static function field_image($setting) {
            global $OUTPUT;

            return $OUTPUT->render_from_template(
                'local_mb2megamenu/field/type/image',
                [
                    'label' => self::field_item_label($setting),
                    'type' => $setting['type'],
                    'id' => $setting['id'],
                    'default' => $setting['default'],
                ]
            );
        }




        /**
         *
         * Method to set checkbox form filed
         *
         */
        public static function field_text($setting) {
            global $OUTPUT;

            return $OUTPUT->render_from_template(
                'local_mb2megamenu/field/type/text',
                [
                    'label' => self::field_item_label($setting),
                    'type' => $setting['type'],
                    'id' => $setting['id'],
                    'default' => $setting['default'],
                    'children' => self::field_children($setting),
                ]
            );
        }





        /**
         *
         * Method to set number form filed
         *
         */
        public static function field_number($setting) {
            global $OUTPUT;

            return $OUTPUT->render_from_template(
                'local_mb2megamenu/field/type/number',
                [
                    'label' => self::field_item_label($setting),
                    'type' => $setting['type'],
                    'id' => $setting['id'],
                    'min' => $setting['min'],
                    'max' => $setting['max'],
                    'default' => $setting['default'],
                    'children' => self::field_children($setting),
                ]
            );
        }






        /**
         *
         * Method to set checkbox form filed
         *
         */
        public static function field_textarea($setting) {
            global $OUTPUT;

            return $OUTPUT->render_from_template(
                'local_mb2megamenu/field/type/textarea',
                [
                    'label' => self::field_item_label($setting),
                    'type' => $setting['type'],
                    'id' => $setting['id'],
                    'default' => $setting['default'],
                    'children' => self::field_children($setting),
                ]
            );
        }




        /**
         *
         * Method to set checkbox form filed
         *
         */
        public static function field_color($setting) {
            global $OUTPUT;

            $istwin = isset($setting['twin']);

            $context = [
                'label' => self::field_item_label($setting),
                'istwin' => $istwin,
                'type' => $setting['type'],
                'id' => $setting['id'],
                'default' => $setting['default'],
            ];

            if ($istwin) {
                $twin = $setting['twin'];

                $context += [
                    'twinlabel1' => $setting['twinlabel1'],
                    'twinlabel2' => $setting['twinlabel2'],
                    'twintype' => $twin['type'],
                    'twinid' => $twin['id'],
                    'twindefault' => $twin['default'],
                ];
            }

            return $OUTPUT->render_from_template(
                'local_mb2megamenu/field/type/color',
                $context
            );
        }







        /**
         *
         * Method to set checkbox form filed
         *
         */
        public static function field_item_label($setting) {
            global $OUTPUT;

            return $OUTPUT->render_from_template(
                'local_mb2megamenu/field/label',
                [
                    'label' => $setting['label'],
                    'desc' => $setting['desc'],
                ]
            );
        }





        /**
         *
         * Method to set checkbox form filed
         *
         */
        public static function field_children($setting) {
            global $OUTPUT;

            if (!isset($setting['children'])) {
                return '';
            }

            $levelcls = $setting['children']['level'] == -1
                ? 'all'
                : $setting['children']['level'];

            $children = [];

            foreach ($setting['children'] as $child) {
                if (!is_array($child)) {
                    continue;
                }

                if ($child['id'] === 'childlabel') {
                    $children[] = [
                        'islabel' => true,
                        'content' => $child['content'],
                    ];
                } else {
                    $children[] = [
                        'islabel' => false,
                        'label' => $child['label'],
                        'type' => $child['type'],
                        'id' => $child['id'],
                        'default' => $child['default'],
                    ];
                }
            }

            return $OUTPUT->render_from_template(
                'local_mb2megamenu/field/children',
                [
                    'levelcls' => $levelcls,
                    'children' => $children,
                ]
            );
        }



        /**
         *
         * Method to to get font icons
         *
         */
        public static function get_icons() {

            if (!file_exists(LOCAL_MB2MEGAMENU_PATH_THEME . '/lib.php')) {
                return [];
            }

            require_once(LOCAL_MB2MEGAMENU_PATH_THEME . '/lib.php');

            if (!function_exists('theme_mb2nl_get_icons4plugins')) {
                return [];
            }

            $iconsarr = [];

            // Get cache.
            $cache = cache::make('local_mb2megamenu', 'icons');
            $cacheid = 'fonticons';

            if ($cache->get($cacheid)) {
                return $cache->get($cacheid);
            }

            $themeicons = theme_mb2nl_get_icons4plugins();

            foreach ($themeicons as $iconskey => $icons) {
                $csspath = LOCAL_MB2MEGAMENU_PATH_THEME_ASSETS . '/' . $icons['folder'] . '/' . $icons['css'] . '.css';
                if (!file_exists($csspath)) {
                    continue;
                }

                $iconsarr[$iconskey]['tabid'] = $icons['tabid'];
                $iconsarr[$iconskey]['name'] = $icons['name'];

                $iconsfont = self::icons($csspath);
                foreach ($iconsfont as $iconkey => $icon) {
                    $iconsarr[$iconskey]['icons'][] = $icons['prefhtml'] . $iconkey;
                }
            }

            $cache->set($cacheid, $iconsarr);
            return $iconsarr;
        }


        /**
         *
         * Method to set icons tab
         *
         */
        public static function font_icon_tabs() {
            global $OUTPUT;

            $themeicons = self::get_icons();

            if (empty($themeicons)) {
                return '';
            }

            $tabs = [];
            $panes = [];
            $searchplaceholder = get_string('searchiconfor', 'local_mb2megamenu');

            foreach (array_values($themeicons) as $index => $icons) {
                $active = $index === 0;

                $tabs[] = [
                    'tabid' => $icons['tabid'],
                    'name' => $icons['name'],
                    'active' => $active,
                ];

                $iconitems = [];

                foreach ($icons['icons'] as $icon) {
                    $iconitems[] = [
                        'iconname' => $icon,
                    ];
                }

                $panes[] = [
                    'tabid' => $icons['tabid'],
                    'active' => $active,
                    'icons' => $iconitems,
                    'searchplaceholder' => $searchplaceholder,
                ];
            }

            return $OUTPUT->render_from_template(
                'local_mb2megamenu/icon-tabs',
                [
                    'tabs' => $tabs,
                    'panes' => $panes,
                ]
            );
        }







        /**
         *
         * Method to get icons array from icon css file
         *
         */
        public static function icons($path) {

            $icons = [];

            if (!file_exists($path)) {
                return [];
            }

            $css = file_get_contents($path);
            $pattern = '/\.([a-zA-Z0-9_-]+):{1,2}before\s*\{\s*content\s*:\s*[\'"]([^\'"]+)[\'"]\s*;\s*\}/';

            preg_match_all($pattern, $css, $matches, PREG_SET_ORDER);

            foreach ($matches as $match) {
                $icons[$match[1]] = $match[2];
            }

            return $icons;
        }




        /**
         *
         * Method to get list of images
         *
         */
        public static function images() {
            global $OUTPUT;

            return $OUTPUT->render_from_template(
                'local_mb2megamenu/images',
                [
                    'preview' => self::image_preview(),
                ]
            );
        }


        /**
         *
         * Method to get images preview
         *
         */
        public static function image_preview() {
            global $OUTPUT;

            $fs = get_file_storage();
            $context = context_system::instance();

            $files = $fs->get_area_files(
                $context->id,
                'local_mb2megamenu',
                'mb2megamenumedia'
            );

            $images = [];

            foreach ($files as $file) {
                $filename = $file->get_filename();

                if ($filename === '.') {
                    continue;
                }

                $url = moodle_url::make_pluginfile_url(
                    $file->get_contextid(),
                    $file->get_component(),
                    $file->get_filearea(),
                    0,
                    $file->get_filepath(),
                    $filename
                );

                $url = $url->out(false);

                $images[] = [
                    'filename' => $filename,
                    'searchname' => strtolower($filename),
                    'url' => $url,
                    'previewurl' => $url . '?preview=thumb',
                ];
            }

            return $OUTPUT->render_from_template(
                'local_mb2megamenu/image-preview',
                [
                    'images' => $images,
                ]
            );
        }
    }

}
