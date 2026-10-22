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

define(['jquery'], function($) {

    'use strict';

    const lang = $('#mb2megamenu-lang');

    var setLayout = function() {
        setLevel(false, 1);
        setChildCount();
        itemActions();
    };

    var itemHtml = function() {
        var html = '';

        html += '<div class="mb2megamenu-item" data-level="1" data-label="' + lang.attr('data-menuitem') + '">';
        html += '<div class="mb2megamenu-item-header">';
        html += '<div class="mb2megamenu-drag-handle mb2megamenu-item-label">';
        html += '<span class="item-sort mr-2"><i class="bi bi-arrows-move"></i></span> ';
        html += '<span class="item-label">' + lang.attr('data-menuitem') + '</span> ';
        html += '<button type="button" class="themereset showhideitem d-inline-flex align-items-center justify-content-center">';
        html += '<i class="bi bi-eye"></i></button>';
        html += '<div class="item-actions"></div>';
        html += '<button type="button" class="themereset mb2megamenu-item-form-toggle"><span class="sr-only">'
        + lang.attr('data-edit') + '</span></button>';
        html += '</div>'; // Mb2megamenu-drag-handle mb2megamenu-item-label
        html += '</div>'; // Mb2megamenu-item-header

        html += '<div class="mb2megamenu-item-content">';

        html += '<div class="mb2megamenu-item-subform">';
        html += '<input type="hidden" name="hide" value="0">';

        html += '<div class="mb2megamenu-subform-item">';
        html += '<div class="form-label">';
        html += '<label>' + lang.attr('data-url') + '</label>';
        html += '</div>'; // Form-label
        html += '<div class="form-controls">';
        html += '<input type="text" class="mb2megamenu-form-control code" name="url" value="" placeholder="https://">';
        html += '</div>'; // Form-controls
        html += '</div>'; // Mb2megamenu-subform-item

        html += '<div class="mb2megamenu-subform-item">';
        html += '<div class="form-controls">';
        html += '<input type="checkbox" name="blank" value="">';
        html += ' <label>' + lang.attr('data-linktarget') + '</label>';
        html += '</div>'; // Form-controls
        html += '</div>'; // Mb2megamenu-subform-item

        html += '<div class="mb2megamenu-subform-item">';
        html += '<div class="form-label">';
        html += '<label>' + lang.attr('data-navlabel') + '</label>';
        html += '</div>'; // Form-label
        html += '<div class="form-controls">';
        html += '<input type="text" name="label" class="mb2megamenu-form-control" value="' + lang.attr('data-menuitem') + '">';
        html += '</div>'; // Form-controls
        html += '</div>'; // Mb2megamenu-subform-item

        html += '<div class="mb2megamenu-subform-item">';
        html += '<div class="form-controls">';
        html += '<button type="button" class="mb2megamenu-item-options mb2-pb-btn typeinfo sizexs"';
        html += ' data-modal="#mb2megamenu-modal-settings">' + lang.attr('data-menuitemopts') + '</button>';

        // Check if there is language options
        if ($('#mb2megamenu-modal-languages').attr('data-islangopt') == 1) {
            html += ' <button type="button" class="mb2megamenu-item-lang_options mb2-pb-btn sizexs"';
            html += ' data-modal="#mb2megamenu-modal-languages">' + lang.attr('data-languageopts') + '</button>';
        }

        html += '</div>'; // Form-controls
        html += '</div>'; // Mb2megamenu-subform-item

        html += '<div class="mb2megamenu-subform-footer">';
        html += '<button type="button" class="themereset mb2megamenu-item-footer-btn btn-remove">' + lang.attr('data-remove')
        + '</button> | ';
        html += '<button type="button" class="themereset mb2megamenu-item-footer-btn btn-cancel">' + lang.attr('data-cancel')
        + '</button>';
        html += '</div>'; // Mb2megamenu-subform-footer
        html += '</div>'; // Mb2megamenu-item-subform

        html += '<div class="mb2megamenu-sortable mb2megamenu-item-sortable"></div>';

        html += '</div>'; // Mb2megamenu-item-content
        html += '</div>'; // Mb2megamenu-item

        return html;
    };

    var setData = function() {
        var item = [];

        $('.mb2megamenu-main-sortable>.mb2megamenu-item').each(function(index) {
            var obj = dataAttribs($(this));
            delete obj.sortableItem;

            item[index] = {
                'settings': obj,
                'attr': []
            };

            dataItem($(this), item[index]);
        });

        return item;
    };

    var dataItem = function(el, pobj) {
        var isel = el.find(
            '>.mb2megamenu-item-content>.mb2megamenu-sortable>.mb2megamenu-item'
        );

        if (isel.length) {
            isel.each(function(index) {
                var obj = dataAttribs($(this));
                delete obj.sortableItem;

                pobj.attr[index] = {
                    'settings': obj,
                    'attr': []
                };

                dataItem($(this), pobj.attr[index]);

                return pobj.attr[index];
            });
        }
    };

    var dataAttribs = function(section) {
        var obj = {};

        $.each(section[0].attributes, function() {

            // Get only data attributes
            if (this.name.includes('data-')) {
                if (this.specified) {
                    obj[this.name.replace('data-', '')] = this.value;
                }
            }

        });

        return obj;
    };

    var mitemFormToggle = function(isthis) {
        var mitem = isthis.closest('.mb2megamenu-item');

        if (mitem.hasClass('open')) {
            mitem.removeClass('open');
        } else {
            mitem.addClass('open');
        }
    };

    var removeMenuItem = function(isthis) {
        var label = isthis
            .closest('.mb2megamenu-item')
            .find('[name="label"]')
            .val();

        // eslint-disable-next-line no-alert
        if (confirm(lang.attr('data-remove') + ': ' + label + '?')) {
            isthis.closest('.mb2megamenu-item').remove();

            // Sort layout
            setLayout();
        }
    };

    var setLevel = function(item, level) {

        if (!item) {
            item = $('.mb2megamenu-builder-area>.mb2megamenu-sortable>.mb2megamenu-item');
        }

        item.each(function() {

            $(this).attr('data-level', level);

            var subitem = $(this).find(
                '>.mb2megamenu-item-content>.mb2megamenu-sortable>.mb2megamenu-item'
            );

            if (subitem.length) {
                level++;

                setLevel(subitem, level);

                level = level - 1;
            }

        });
    };

    var setChildCount = function() {
        $('.mb2megamenu-builder-area .mb2megamenu-item').each(function() {
            var childCount = $(this)
                .children('.mb2megamenu-item-content')
                .children('.mb2megamenu-sortable')
                .children('.mb2megamenu-item')
                .length;

            this.dataset.childnum = childCount;
        });
    };

    var itemActiosnHtml = function(type) {
        var html = '';

        if (type === 'lup') {
            html += '<button type="button" class="themereset" title="' + lang.attr('data-levelup') + '" data-action="lup">';
            html += '</button>';
        }

        if (type === 'ldown') {
            html += '<button type="button" class="themereset" title="' + lang.attr('data-leveldown') + '" data-action="ldown">';
            html += '</button>';
        }

        if (type === 'mup') {
            html += '<button type="button" class="themereset" title="' + lang.attr('data-moveup') + '" data-action="mup">';
            html += '</button>';
        }

        if (type === 'mdown') {
            html += '<button type="button" class="themereset" title="' + lang.attr('data-movedown') + '" data-action="mdown">';
            html += '</button>';
        }

        return html;
    };

    var itemActions = function() {

        $('.mb2megamenu-item').each(function() {

            var html = '';
            var actionWrap = $(this).find(
                '>.mb2megamenu-item-header .item-actions'
            );
            var prev = $(this).prev('.mb2megamenu-item').length;
            var next = $(this).next('.mb2megamenu-item').length;
            var ischild = $(this).attr('data-level') > 1;

            if (prev) {
                html += itemActiosnHtml('mup');
            }

            if (next) {
                html += itemActiosnHtml('mdown');
            }

            if (prev) {
                html += itemActiosnHtml('lup');
            }

            if (ischild) {
                html += itemActiosnHtml('ldown');
            }

            actionWrap.html(html);

            html = '';

        });

    };

    return {
        itemHtml: itemHtml,
        setData: setData,
        mitemFormToggle: mitemFormToggle,
        removeMenuItem: removeMenuItem,
        setLayout: setLayout
    };

});
