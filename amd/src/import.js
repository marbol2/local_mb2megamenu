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

    var actions = function() {

        doc.on('click', '.mb2megamenu-import-header', function() {

            var wrap = $(this).closest('.mb2megamenu-menu-import');

            if (wrap.hasClass('active')) {
                wrap.removeClass('active');
            } else {
                wrap.addClass('active');
            }

        });

        doc.on('click', '.mb2megamenu-import-item button', function() {
            importDemoMenu($(this));
        });

    };

    var importDemoMenu = function(btn) {

        if (btn.hasClass('importing') || btn.hasClass('importdone')) {
            return null;
        }

        btn.removeClass('noaction');
        btn.addClass('importing');

        const ajaxRequest = () => {
            const params = {
                dataid: btn.attr('data-id')
            };

            const request = {
                methodname: 'local_mb2megamenu_import_menu',
                args: params
            };

            return Ajax.call([request])[0];
        };

        ajaxRequest().then(() => {

            setTimeout(function() {
                btn.removeClass('importing');
                btn.addClass('importdone');

                setTimeout(function() {
                    location.reload();
                }, 600);

            }, 1000);

            return true;
        }).catch(err => {
            require(["core/log"], function(log) {
                log.debug(err);
            });
        });
        return true;
    };


    return {
        actions: actions
    };

});
