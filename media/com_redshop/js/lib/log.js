"use strict";

define(
	// modular name
	'log',
	// dependencies
	[],
	(function (w, $) {
		var redSHOP = w.redSHOP;

		/**
		 * redSHOP Administrator object
		 */
		if (typeof redSHOP.Log === 'undefined') {

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

			redSHOP.Log = redLog;
		}

		return redSHOP.Log;
	})(window, jQuery)
)
