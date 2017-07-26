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
		if (typeof redSHOP.Ajax === 'undefined') {

			var redAjax = {
				/**
				 * Default configuration object
				 */
				_default: {

				},

			}

			redSHOP.Log = redLog;
		}

		return redSHOP.Log;
	})(window, jQuery)
)
