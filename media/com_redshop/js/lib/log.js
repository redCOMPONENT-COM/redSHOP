"use strict";

/**
 * @copyright  Copyright (C) 2008 - 2017 redCOMPONENT.com. All rights reserved.
 * @license    GNU General Public License version 2 or later; see LICENSE
 */

define(
	// dependencies
	[],
	(function (w, $) {
		var redLog = {
			/**
			 * Default configuration object
			 */
			_default: {
				html: '<div class="redshop-log"><span><p class="text {log-class}">{log}</p></span></div>'
			},

			/**
			 *
			 * @param elClass
			 * @param message
			 * @returns {string|*}
			 */
			log: function (elClass, message) {
				return this._default.html.replace('{log-class}', 'text-' + elClass).replace('{log}', message);
			},

			/**
			 *
			 * @param message
			 * @returns {*|string}
			 */
			trace: function (message) {
				return this.log('default', message);
			},

			/**
			 *
			 * @param message
			 * @returns {*|string}
			 */
			debug: function (message) {
				return this.log('primary', message);
			},

			/**
			 *
			 * @param message
			 * @returns {*|string}
			 */
			success: function (message) {
				return this.log('success', message);
			},

			/**
			 *
			 * @param message
			 * @returns {*|string}
			 */
			info: function (message) {
				return this.log('info', message);
			},

			/**
			 *
			 * @param message
			 * @returns {*|string}
			 */
			warn: function (message) {
				return this.log('warning', message);
			},

			/**
			 *
			 * @param message
			 * @returns {*|string}
			 */
			error: function (message) {
				return this.log('danger', message);
			}
		}

		w.redSHOP.Log = redLog;

		return w.redSHOP.Log;
	})(window, jQuery)
)
