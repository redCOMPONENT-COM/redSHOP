"use strict";

/**
 * @copyright  Copyright (C) 2008 - 2017 redCOMPONENT.com. All rights reserved.
 * @license    GNU General Public License version 2 or later; see LICENSE
 */

(function (w, $)
{
	if (typeof redSHOP === 'undefined')
	{
		var redSHOP = {};
		w.redSHOP = redSHOP;
	}
	;

	// Requirejs define
	define(
		// dependencies
		[],
		function ()
		{
			// B/C with current redSHOP object by extending it
			w.redSHOP = $.extend({}, w.redSHOP, {
				version: '1.0.0'
			})

			return w.redSHOP;
		}
	)
})(window, jQuery);