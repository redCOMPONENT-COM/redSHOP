"use strict";

/**
 * @copyright  Copyright (C) 2008 - 2017 redCOMPONENT.com. All rights reserved.
 * @license    GNU General Public License version 2 or later; see LICENSE
 */

// redSHOP Admin base object

(function (w, $) {
	define(
		function () {
			w.redSHOP.Admin = {
				/**
				 *
				 * @param lock
				 */
				blockElements: function (elements, block) {
					var blockClass = 'disabled muted';

					if (!block) {
						$(elements).removeClass(blockClass);
						$(elements).prop("disabled", false);
					}
					else {
						$(elements).addClass(blockClass);
						$(elements).prop("disabled", true);
					}
				},

				/**
				 *
				 * @param type
				 * @param message
				 */
				appendLog: function (target, type, message) {
					if (typeof redSHOP.Log[type] === 'function') {
						message = redSHOP.Log[type](message);
					}

					$(target).append(message);
				},

				clean: function (elements) {
					$(elements).html('');
				},

				cleanProgress: function (elements) {
					$(elements).html('0%').css("width", "0%");
				},

				/**
				 * @return  string
				 */
				getAdminFormSerialize: function ()
				{
					return $("#adminForm").serialize();
				}
			};

			return w.redSHOP.Admin;
		}
	)
})(window, jQuery);