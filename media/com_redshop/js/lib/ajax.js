"use strict";

/**
 * @copyright  Copyright (C) 2008 - 2017 redCOMPONENT.com. All rights reserved.
 * @license    GNU General Public License version 2 or later; see LICENSE
 */

(function (w, $) {
	define(
		// dependencies
		['lib/log'],
		// Ajax class
		function () {
			// Declare ajax
			var redAjax = {
				/**
				 * Default configuration object
				 */
				_default: {
					ajaxConfig: {
						dataType: "json",
						method: "POST",
						headers: {"X-Redshop-Header": "Ajax"}
					},

					debug: true
				},

				/**
				 * Execute ajax
				 * @param config
				 * @param doneCallback
				 * @param failCallback
				 * @param alwaysCallback
				 */
				execute: function (config, doneCallback, failCallback, alwaysCallback) {
					// Ajax configuration
					var _config = $.extend({}, this._default.ajaxConfig, config);

					// Execute ajax
					var jqXHR = $.ajax(_config)
						.done(function (response, textStatus, jqXHR) {
							// Do something

							if (typeof doneCallback === 'function') {
								doneCallback(response, textStatus, jqXHR);
							}
						})
						.fail(function (response, textStatus, jqXHR) {
							// Do something

							if (typeof failCallback === 'function') {
								failCallback(response, textStatus, jqXHR);
							}
						})
						.always(function (response, textStatus, jqXHR) {
							// Do something

							// Execute call back
							if (typeof alwaysCallback === 'function') {
								alwaysCallback(response, textStatus, jqXHR);
							}
						})

				},

				download: function()
				{

				}
			};

			w.redSHOP.Ajax = redAjax;
			return w.redSHOP.Ajax;
		}
	)
})(window, jQuery);
