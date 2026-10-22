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

    var stripHtml = function(str) {
       if (str === undefined || str === null) {
           return str;
       }

       return str.replace(/<\/?[^>]+(>|$)/g, '');
    };

    var modalShow = function(object) {
        $(object).addClass('show');
    };

    var modalHide = function(isthis) {
        isthis.closest('.mb2megamenu-modal').removeClass('show');
    };


    return {
        stripHtml: stripHtml,
        modalShow: modalShow,
        modalHide: modalHide
    };

});
