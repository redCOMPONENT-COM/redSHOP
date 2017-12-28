/**
 * @copyright  Copyright (C) 2008 - 2017 redCOMPONENT.com. All rights reserved.
 * @license    GNU General Public License version 2 or later; see LICENSE
 */

(function(w, $, redSHOP) {
    var Ajax = {

        // Default settings
        settings: {
            /**
             * Ajax default data
             */
            ajaxData: {
                url: 'index.php',
                data: {},
                method: 'POST',
                dataType: 'json',
                encode: true,
                /**
                 * Default header request
                 */
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'redWEB': 1
                }
            }
        },

        /**
         *
         * @param settings
         * @param callbacks
         * @returns {*}
         */
        post: function (settings) {
            var ajaxSettings = $.extend(true, {}, Ajax.settings.ajaxData, settings);
            ajaxSettings.method = 'POST';

            return this.execute(ajaxSettings);
        },
        /**
         *
         * @param settings
         * @param callbacks
         * @returns {*}
         */
        get: function (settings) {
            var ajaxSettings = $.extend(true, {}, Ajax.settings.ajaxData, settings);
            ajaxSettings.method = 'GET';

            return this.execute(ajaxSettings);
        },
        /**
         *
         * @param settings
         * @param callbacks
         * @returns {*}
         */
        put: function (settings) {
            var ajaxSettings = $.extend(true, {}, Ajax.settings.ajaxData, settings);
            ajaxSettings.method = 'PUT';

            return this.execute(ajaxSettings);
        },
        /**
         * Execute ajax
         * @param settings
         * @param doneCallback
         * @param failCallback
         * @param alwaysCallback
         */
        execute: function (settings) {
            var ajaxSettings = $.extend(true, {},
                Ajax.settings.ajaxData,
                settings);

            return $.ajax(ajaxSettings);
        }
    }
})(window, jQuery.noConflict(), window.redSHOP)