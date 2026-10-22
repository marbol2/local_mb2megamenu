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

define(['jquery', 'core/ajax'], function($, Ajax) {

    'use strict';

    const doc = $(document);

    const imageUpload = (isthis) => {

        var filepicker = isthis.closest('#mb2megamenu-modal-file-manager').find('[data-fieldtype="filepicker"]');
        var overlay = $('#mb2megamenu-modal-images').find('.mb2megamenu-overlay');
        var form = isthis.closest('#mb2megamenu-modal-file-manager').find('form');
        var mediafile = form.find('[name="mediafile"]').val();

        if (!filepicker.find('.filepicker-filename>a').length) {
            return null;
        }

        // Show loading screen
        overlay.fadeIn(180);

        // Close upload window
        isthis.closest('.mb2megamenu-modal-footer').find('[data-dismiss="mb2modal"]').trigger('click');

        // Build filedpicker content again
        filepicker.find('.filepicker-filename').html(filepickerContent());

        const ajaxRequest = () => {
            const params = {
                mediafile: mediafile
            };

            const request = {
                methodname: 'local_mb2megamenu_image_upload',
                args: params
            };

            return Ajax.call([request])[0];
        };

        ajaxRequest().then(data => {
            $('#mb2megamenu-modal-images').find('.mb2megamenu-images').html(data.images);

            // Clear search form
            $('.mb2megamenu-search-image').val('');
            $('.mb2megamenu-search-image').trigger('keyup');

            setTimeout(function() {
                overlay.fadeOut(250);
            }, 450);

            return;
        }).catch(err => {
            require(["core/log"], function(log) {
                log.debug(err);
            });
        });
        return true;
    };


    const imageRemove = (isthis) => {

        var overlay = $('#mb2megamenu-modal-images').find('.mb2megamenu-overlay');
        var imageswrap = isthis.closest('.mb2megamenu-images');
        var imgname = isthis.closest('.mb2megamenu-image-toinsert').attr('data-imgname');

        // Show loading screen
        overlay.fadeIn(180);

        const ajaxRequest = () => {
            const params = {
                imgname: imgname
            };

            const request = {
                methodname: 'local_mb2megamenu_image_remove',
                args: params
            };

            return Ajax.call([request])[0];
        };

        ajaxRequest().then(data => {
            imageswrap.html(data.images);

            // Clear search form
            $('.mb2megamenu-search-image').val('');
            $('.mb2megamenu-search-image').trigger('keyup');

            setTimeout(function() {
                overlay.fadeOut(250);
            }, 450);

            return;
        }).catch(err => {
            require(["core/log"], function(log) {
                log.debug(err);
            });
        });
    };


    var filepickerContent = function() {

        var html = '';

        html += '<div class="filepicker-container">';
        html += '<div class="dndupload-message">You can drag and drop files here to add them. <br>';
        html += '<div class="dndupload-arrow d-flex"><i class="fa fa-arrow-circle-o-down fa-3x m-auto"></i></div>';
        html += '</div>';
        html += '</div>';
        html += '<div class="dndupload-progressbars"></div>';

        return html;

    };

    var imageActions = function() {

        doc.on('click', '#applay-file-manager', function() {
            imageUpload($(this));
        });

        doc.on('click', '.imgremove', function() {
           imageRemove($(this));
        });

    };

    return {
        imageActions: imageActions
    };

});
