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

define(['jquery', 'local_mb2megamenu/helper', 'theme_mb2nl/sortable', 'local_mb2megamenu/layout'],
    function($, Helper, Sortable, Layout) {

    'use strict';

    const doc = $(document);
    const helper = Helper;
    const lang = $('#mb2megamenu-lang');
    const layout = Layout;

    var setLabel = function() {
        doc.on('keyup', '[name="label"]', function() {

            var label = $(this)
                .closest('.mb2megamenu-item')
                .find('>.mb2megamenu-item-header>.mb2megamenu-item-label>.item-label');

            var labeltext = helper.stripHtml($(this).val());

            if (labeltext.replace(/\s/g, '') === '') {
                label.text(lang.attr('data-menuitem'));
            } else {
                label.text(labeltext);
            }

        });
    };


    // This is require for menu item cancel settings
    var setItemForm = function(isthis) {

        var item = isthis.closest('.mb2megamenu-item');

        // Foreach all input fileds and set menu item adata ttributes
        item.find('>.mb2megamenu-item-content>.mb2megamenu-item-subform input').each(function() {

            var itemval = item.attr('data-' + $(this).attr('name'));

            // Checkbox
            if ($(this).attr('type') === 'checkbox') {

                if (itemval == 1) {
                    $(this).prop('checked', true);
                } else {
                    $(this).prop('checked', false);
                }

            } else {
                $(this).val(itemval);
            }

            // Set menu title
            if ($(this).attr('name') === 'label') {
                itemval = itemval ? itemval : lang.attr('data-menuitem');

                item.find(
                    '>.mb2megamenu-item-header>.mb2megamenu-item-label .item-label'
                ).text(itemval);
            }

        });

    };


    var saveItemForm = function() {

        // Foreach all input fileds and set menu item adata ttributes
        $('.mb2megamenu-item-subform input').each(function() {

            var menuItem = $(this).closest('.mb2megamenu-item');
            var inputName = $(this).attr('name');
            var inputValue = helper.stripHtml($(this).val());

            // Prevent to use empty label
            if ($(this).attr('name') === 'label' && inputValue.replace(/\s/g, '') === '') {
                inputValue = lang.attr('data-menuitem');
            }

            // Set checkboxes value
            if ($(this).attr('type') === 'checkbox') {
                inputValue = $(this).is(':checked') ? 1 : 0;
            }

            menuItem.attr('data-' + inputName, inputValue);
        });

    };


    var setIconPreview = function(icon) {
        var html = '';
        html += '<i class="' + icon + '"></i>';
        return html;
    };


    var setImagePreview = function(url) {
        var html = '';
        html += '<img src="' + url + '?preview=thumb">';
        return html;
    };


    var setIcon = function(isthis) {

        var icon = isthis.attr('data-iconname');

        // Set icon
        $('input.activefield').val(icon);

        // Set icon preview
        $('input.activefield')
            .closest('.form-controls')
            .find('.form-preview')
            .html(setIconPreview(icon));

        // Hide modal window
        helper.modalHide(isthis);

    };


    var setImage = function(isthis) {

        var imageurl = isthis.attr('data-imgurl');

        // Set icon
        $('input.activefield').val(imageurl);

        // Set icon preview
        $('input.activefield')
            .closest('.form-controls')
            .find('.form-preview')
            .html(setImagePreview(imageurl));

        // Hide modal window
        helper.modalHide(isthis);

    };


    // Set active menu item to add settings from modal window
    var activateMenuItem = function(isthis) {

        // Deactivate all items
        $('.mb2megamenu-item').removeClass('active');

        // Activate current item
        isthis.closest('.mb2megamenu-item').addClass('active');

    };


    // Set settings form based on the menu item data attributes
    var setSettingsForm = function() {

        // Clear form preview
        $('.form-preview').html('');

        var menuitem = $('.mb2megamenu-item.active');

        $('.mb2megamenu-input').each(function() {

            var dataattr = menuitem.attr('data-' + $(this).attr('name'));
            var inputvalue = dataattr !== undefined ? dataattr : $(this).attr('data-value');

            // Set input value
            if ($(this).hasClass('input-checkbox')) {

                if (inputvalue == 1) {
                    $(this).prop('checked', true);
                } else {
                    $(this).prop('checked', false);
                }

            } else if ($(this).hasClass('input-image') && inputvalue) {

                $(this)
                    .closest('.form-controls')
                    .find('.form-preview')
                    .html(setImagePreview(inputvalue));

                $(this).val(inputvalue);

            } else if ($(this).hasClass('input-icon') && inputvalue) {

                $(this)
                    .closest('.form-controls')
                    .find('.form-preview')
                    .html(setIconPreview(inputvalue));

                $(this).val(inputvalue);

            } else if ($(this).hasClass('input-color')) {

                $(this).val(inputvalue);
                setColor($(this), inputvalue);

            } else {
                $(this).val(inputvalue);
            }

        });

    };


    var setSettingsLanguagesForm = function() {

        var menuitem = $('.mb2megamenu-item.active');

        $('.mb2megamenu-langform-item .mb2megamenu-form-control').each(function() {

            var dataattr = menuitem.attr('data-' + $(this).attr('name'));
            var inputvalue = dataattr !== undefined ? dataattr : '';

            $(this).val(inputvalue);

        });

    };


    // Save active menu item to add settings from modal window
    var saveSettings = function(languages, isthis) {

        var menuitem = $('.mb2megamenu-item.active');
        var formItem = languages
            ? $('.mb2megamenu-langform-item .mb2megamenu-form-control')
            : $('.mb2megamenu-input');

        formItem.each(function() {

            var inputvalue = helper.stripHtml($(this).val());
            var inputname = $(this).attr('name');

            if ($(this).attr('type') === 'checkbox') {
                inputvalue = $(this).is(':checked') ? 1 : 0;
            }

            menuitem.attr('data-' + inputname, inputvalue);

        });

        // Hide modal window
        helper.modalHide(isthis);

    };


    var setColor = function(el) {

        require(['theme_mb2nl/spectrum'], function() {
            el.spectrum({
                showInput: true,
                showButtons: false,
                preferredFormat: 'rgb',
                allowEmpty: true,
                color: '',
                showAlpha: true
            });
        });

    };


    var setModalWindowTitle = function(isthis, pref) {

        var menuitem = isthis.closest('.mb2megamenu-item');
        var ispref = pref ? pref + ': ' : '';

        $(isthis.attr('data-modal'))
            .find('.modal-title')
            .html(
                ispref +
                menuitem.find(
                    '>.mb2megamenu-item-header>.mb2megamenu-item-label>.item-label'
                ).html()
            );

    };


    var setModalWindow = function(isthis) {

        var menuitem = isthis.closest('.mb2megamenu-item');
        var parentmenuitem = isthis.closest('[data-level="1"]');
        var modalcontainer = $('#mb2megamenu-modal-settings');

        if (menuitem.attr('data-level') == 1) {
            modalcontainer.attr('data-id', 'mparent');
        } else if (
            menuitem.attr('data-level') == 2 &&
            parentmenuitem.attr('data-mega') == 1
        ) {
            modalcontainer.attr('data-id', 'mcolumn');
        } else {
            modalcontainer.attr('data-id', 'mitem');
        }

    };


    var searchItem = function() {

        $(doc).on('keyup', '.search-modal-item>input[type="text"]', function() {

            var searchTerm = $(this).val().toLowerCase();
            var isimage = $(this).hasClass('mb2megamenu-search-image') ? 1 : 0;

            var toitemclosest = isimage
                ? '.mb2megamenu-modal-body'
                : '.tab-pane';

            var toitemfind = isimage
                ? '.mb2megamenu-image-toinsert'
                : '.mb2megamenu-icon-toinsert';

            var dataname = isimage
                ? 'data-simgname'
                : 'data-iconname';

            var toitem = isimage
                ? $(this).closest(toitemclosest).find(toitemfind)
                : $(this).closest(toitemclosest).find(toitemfind);

            toitem.each(function() {

                if (
                    $(this).filter('[' + dataname + '*="' + searchTerm + '"]').length > 0 ||
                    searchTerm.length == 0
                ) {
                    $(this).show();
                } else {
                    $(this).hide();
                }

            });

        });

    };


    var sortItems = function() {
        var root = document.querySelector('.mb2megamenu-main-sortable');

        if (!root) {
            return;
        }

        var containers = [
            root,
            ...root.querySelectorAll('.mb2megamenu-sortable')
        ];

        containers.forEach(function(container) {
            if (Sortable.get(container)) {
                return;
            }

            Sortable.create(container, {
                group: 'mb2megamenu-sortable',
                draggable: '.mb2megamenu-item',
                handle: '.mb2megamenu-drag-handle',
                filter: 'button, input, select, textarea, a',
                preventOnFilter: false,
                animation: 150,
                fallbackOnBody: true,
                swapThreshold: 0.65,
                onMove: function(evt) {
                    // Prevent dropping an item into its own descendants.
                    return !evt.dragged.contains(evt.to);
                },
                onEnd: function() {
                    layout.setLayout();
                }
            });
        });
    };


    return {
        setLabel: setLabel,
        saveItemForm: saveItemForm,
        setItemForm: setItemForm,
        setIcon: setIcon,
        setImage: setImage,
        activateMenuItem: activateMenuItem,
        saveSettings: saveSettings,
        setModalWindow: setModalWindow,
        setModalWindowTitle: setModalWindowTitle,
        searchItem: searchItem,
        setSettingsForm: setSettingsForm,
        setSettingsLanguagesForm: setSettingsLanguagesForm,
        sortItems: sortItems
    };
});
