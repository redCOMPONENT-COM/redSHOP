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

	// Init redAjax object
	var redAjax = {
		version: '1.0.0',
		_config: {
			dataType: "json",
			method: "POST",
			headers: {"X-Redshop-Header": "redAJAX"},
			beforeSend: function ()
			{
				// @TODO Implement add ajax loading layer
			}
		},
		requestsArray: [],
		processingQueue: false,
		processTimeout: 50,
	};

	/**
	 * Execute ajax directly
	 *
	 * @param config
	 * @param doneCallback
	 * @param failCallback
	 * @param alwaysCallback
	 * @param processingQueue
	 */
	redAjax['ajax'] = function (config, doneCallback, failCallback, alwaysCallback, processingQueue)
	{
		// Ajax configuration
		var _config = $.extend({}, redAjax._config, config);

		// Execute ajax
		var jqXHR = $.ajax(_config)
			.done(function (response, textStatus, jqXHR)
			{
				// Execute callback
				if (typeof doneCallback === 'function')
				{
					doneCallback(response, textStatus, jqXHR);
				}
			})
			.fail(function (response, textStatus, jqXHR)
			{
				// Execute callback
				if (typeof failCallback === 'function')
				{
					failCallback(response, textStatus, jqXHR);
				}
			})
			.always(function (response, textStatus, jqXHR)
			{
				// Set processingQueue if possible
				if (typeof processingQueue === 'boolean')
				{
					redAjax.processingQueue = boolean;
				}
				;

				// Execute call back
				if (typeof alwaysCallback === 'function')
				{
					alwaysCallback(response, textStatus, jqXHR);
				}

				// @TODO Implement remove ajax loading layer
			})
	};

	/**
	 * Add ajax queue and execute queue
	 *
	 * @param  {object}  ajax
	 */
	redAjax['enqueue'] = function (ajax)
	{
		redAjax.requestsArray.push(ajax);
		redAjax.processQueue();
	};

	/**
	 *
	 */
	redAjax['processQueue'] = function ()
	{
		// Has queue
		if (redAjax.requestsArray.length > 0)
		{
			// There is no ajax under processing than do it
			if (redAjax.processingQueue === false)
			{
				// Get first ajax also remove it in queue
				var ajax = redAjax.requestsArray.shift();
				// Set flag true to determine that ajax under processing
				redAjax.processingQueue = true;
				// Execute ajax also set processingQueue flag to false, so next time we can execute another ajax in queue
				redAjax.ajax(ajax[0], ajax[1], ajax[2], ajax[3], false);
			}

			// Try to execute next ajax if still have queue
			if (redAjax.requestsArray.length)
			{
				setTimeout(redAjax.processQueue, redAjax.processTimeout);
			}
		}
	}

	// Add to redSHOP
	w.redSHOP['Ajax'] = redAjax;

	// Define with requirejs
	if (typeof define === "function" && define.amd)
	{
		define("ajax", [], function ()
		{
			return w.redSHOP['Ajax'];
		});
	}

})(window, jQuery);