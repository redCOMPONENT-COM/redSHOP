/**
 * @copyright  Copyright (C) 2008 - 2016 redCOMPONENT.com. All rights reserved.
 * @license    GNU General Public License version 2 or later; see LICENSE
 */

// Only define the redSHOP namespace if not defined.
redSHOP = window.redSHOP || {};

/**
 * Custom behavior for JavaScript dynamic variables
 *
 * Allows you to call redSHOP.RSConfig._() to get a dynamic JavaScript string pushed in with JText::script() in Joomla.
 */
redSHOP.RSConfig = {
	configStrings: {},
	'_': function(key, def) {
		return typeof this.configStrings[key.toUpperCase()] !== 'undefined' ? this.configStrings[key.toUpperCase()] : def;
	},
	load: function(object) {
		for (var key in object) {
			this.configStrings[key.toUpperCase()] = object[key];
		}

		return this;
	}
};

/**
 * Identification flag to make sure that ajax order payament status check script
 *  is executed
 *
 * @type  {Boolean}
 */
redSHOP.AjaxOrderPaymentStatusExecure = false;

/**
 * Update order payment status to DOM periodically if current status is unpaid.
 */
redSHOP.AjaxOrderPaymentStatusCheck = function(){

	var checkValidity = jQuery.trim(jQuery('#order_payment_status').html()) == Joomla.JText._('COM_REDSHOP_PAYMENT_STA_PAID');

	// Don't do anything if status is already paid.
	if (checkValidity)
	{
		return false;
	}

	jQuery.ajax({
		url: redSHOP.RSConfig._('SITE_URL') + 'index.php?option=com_redshop&view=order_detail&task=order_detail.AjaxOrderPaymentStatusCheck&tmpl=component',
		type: 'POST',
		dataType: 'HTML',
		data: {id: redSHOP.RSConfig._('orderId')},
	})
	.done(function(res) {

		// Update status to matched DOM. Make sure you add this ID in order receipt template.
		jQuery('#order_payment_status').html(res);

		// Make sure script is executed at least one time.
		if (redSHOP.AjaxOrderPaymentStatusExecure)
		{
			setTimeout('redSHOP.AjaxOrderPaymentStatusCheck()', 10000);
		}

		redSHOP.AjaxOrderPaymentStatusExecure = true;
	})
	.fail(function() {
		console.log("error");
	});
};

// Write script here to execute on page load - dom ready.
jQuery(document).ready(function($) {

	if (jQuery('#order_payment_status').length > 0)
	{
		// Execure first time after 1 second.
		setTimeout('redSHOP.AjaxOrderPaymentStatusCheck()', 1000);

		// Then run second time after 10 second.
		setTimeout('redSHOP.AjaxOrderPaymentStatusCheck()', 10000);
	}
	else
	{
		console.warn('Make sure you add #order_payment_status ID in order receipt template');
	}
});
