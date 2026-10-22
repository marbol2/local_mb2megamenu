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
 * @module     local_mb2megamenu
 * @copyright  2026 Mariusz Boloz (lmsstyle.com)
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

define([
    'jquery',
    'local_mb2megamenu/helper',
    'local_mb2megamenu/layout',
    'local_mb2megamenu/settings'
], function($, Helper, Layout, Settings) {

    'use strict';

    const doc = $(document);
    const helper = Helper;
    const layout = Layout;
    const settings = Settings;
    const lang = $('#mb2megamenu-lang');

    var actions = function() {

        doc.on('click', '[data-dismiss="mb2modal"]', function() {

            helper.modalHide($(this));

        });


        /* ============ ADD MENU ITEM */

        doc.on('click', '#mb2megamenu-add-menu-item', function() {

            // Insert menu item HTML
            $('.mb2megamenu-main-sortable').append(
                layout.itemHtml()
            );

            // Update sort layout
            // layout.layoutSort();
            layout.setLayout();

        });


        /* ============ SUBMIT MENU FORM */
        // We need this: [role="main"]>form
        // top prevent to send two forms: menu form and file manager in modla window
        doc.on('submit', '[role="main"]>form', function() {

            // Save menu items settings
            settings.saveItemForm();

            // Set the 'menuitems' form value
            var data = JSON.stringify(layout.setData());
            $('input[name="menuitems"]').val(data);

        });


        /* ============ MENU ITEM TOGGLE FORM */

        doc.on('click', '.mb2megamenu-item-form-toggle', function() {

            layout.mitemFormToggle($(this));

        });


        /* ============ REMOVE MENU ITEM */

        doc.on('click', '.mb2megamenu-item-footer-btn.btn-remove', function() {

            layout.removeMenuItem($(this));

        });


        /* ============ SHOW/HIDE MENU ITEM */

        doc.on('click', '.mb2megamenu-item-header .showhideitem', function() {

            let btn = $(this);
            let icon = btn.find('i');

            let input = btn.closest('.mb2megamenu-item')
                .find('>.mb2megamenu-item-content>.mb2megamenu-item-subform>input[name="hide"]');

            if (btn.hasClass('hideon')) {

                input.val(0);
                btn.removeClass('hideon');
                btn.closest('.mb2megamenu-item').removeClass('hideon');
                icon.attr('class', 'bi bi-eye');

            } else {

                input.val(1);
                btn.addClass('hideon');
                btn.closest('.mb2megamenu-item').addClass('hideon');
                icon.attr('class', 'bi bi-eye-slash');

            }

        });


        /* ============ CANCEL MENU ITEM SETTINGS */

        doc.on('click', '.mb2megamenu-item-footer-btn.btn-cancel', function() {

            // Set seved settings before
            settings.setItemForm($(this));

            // Toggle menu form
            layout.mitemFormToggle($(this));

        });


        /* ============ ACTIVETE OPTION FOR SETTINGS (ICON AND IMAGE) */

        doc.on('click', '[data-modal]', function() {

            if ($(this).hasClass('mb2megamenu-activate-option')) {

                // Make input filed active.
                $('#mb2megamenu-modal-settings')
                    .find('input')
                    .removeClass('activefield');

                $(this)
                    .closest('.form-controls')
                    .find('input')
                    .addClass('activefield');
            }

            // Open the settings modal window.
            if ($(this).hasClass('mb2megamenu-item-options')) {

                // Activate current menu item.
                settings.activateMenuItem($(this));

                // Set modal window title.
                settings.setModalWindowTitle(
                    $(this),
                    lang.attr('data-menuitemopts')
                );

                // Set modal window ID data attribute.
                settings.setModalWindow($(this));

                // Set settings form.
                settings.setSettingsForm();
            }

            // Open language options.
            if ($(this).hasClass('mb2megamenu-item-lang_options')) {

                // Activate current menu item.
                settings.activateMenuItem($(this));

                // Set modal window title.
                settings.setModalWindowTitle(
                    $(this),
                    lang.attr('data-languageopts')
                );

                // Set settings form.
                settings.setSettingsLanguagesForm();
            }

            // Open modal window.
            helper.modalShow(
                $(this).attr('data-modal')
            );

        });


        /* ============ SELECT ICON */

        doc.on('click', '[data-iconname]', function() {

            settings.setIcon($(this));

        });


        /* ============ SELECT IMAGE */

        doc.on('click', '.mb2megamenu-insert-image', function() {

            settings.setImage($(this));

        });


        /* ============ REMOVE ICON */

        doc.on('click', '.mb2megamenu-fieldremove', function() {

            // Make icon filed active
            $(this)
                .closest('.form-controls')
                .find('input')
                .val('');

            // Remove preview
            $(this)
                .closest('.form-controls')
                .find('.form-preview')
                .html('');

        });


        /* ============ SAVE SETTINGS */

        doc.on('click', '.mb2megamenu-modalbtn-save', function() {

            settings.saveSettings(0, $(this));

        });


        /* ============ SAVE LANGUAGE SETTINGS */

        doc.on('click', '.mb2megamenu-lang_modalbtn-save', function() {

            settings.saveSettings(1, $(this));

        });


        /* ============ ACTION BUTTON - MOVE UP */

        doc.on('click', '[data-action="mup"]', function() {

            var menuItem = $(this).closest('.mb2megamenu-item');

            menuItem.insertBefore(menuItem.prev('.mb2megamenu-item'));

            // Update actions.
            layout.setLayout();

        });


        /* ============ ACTION BUTTON - MOVE DOWN */

        doc.on('click', '[data-action="mdown"]', function() {

            var menuItem = $(this).closest('.mb2megamenu-item');

            menuItem.insertAfter(menuItem.next('.mb2megamenu-item'));

            // Update actions
            layout.setLayout();

        });


        /* ============ ACTION BUTTON - MOVE ONE LEVEL UP */

        doc.on('click', '[data-action="lup"]', function() {

            var menuItem = $(this).closest('.mb2megamenu-item');

            var childWrap = menuItem
                .prev('.mb2megamenu-item')
                .find('>.mb2megamenu-item-content>.mb2megamenu-sortable');

            childWrap.append(menuItem);

            // Update actions
            layout.setLayout();

        });


        /* ============ ACTION BUTTON - MOVE ONE LEVEL DOWN */

        doc.on('click', '[data-action="ldown"]', function() {

            var menuItem = $(this).closest('.mb2megamenu-item');

            var level = menuItem.attr('data-level') - 1;

            var parentMenu = menuItem.closest('[data-level="' + level + '"]');

            menuItem.insertAfter(parentMenu);

            // Update actions
            layout.setLayout();

        });

        layout.setLayout();
        settings.setLabel();
        settings.searchItem(); // Saerch icon and image settings modal window
        settings.sortItems();

    };

    return {
        actions: actions
    };

});
