<?php
/**
 * @package     RedSHOP
 * @subpackage  Plugin
 *
 * @copyright   Copyright (C) 2008 - 2015 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

defined('_JEXEC') or die;

JLoader::import('redshop.library');

class plgRedshop_paymentrs_payment_worldpay extends JPlugin
{
	/**
	 * Plugin method with the same name as the event will be called automatically.
	 */
	public function onPrePayment($element, $data)
	{
		if ($element != 'rs_payment_worldpay')
		{
			return;
		}

		if (empty($plugin))
		{
			$plugin = $element;
		}

		include JPATH_SITE . '/plugins/redshop_payment/' . $plugin . '/' . $plugin . '/extra_info.php';
	}

	function onNotifyPaymentrs_payment_worldpay($element, $request)
	{
		if ($element != 'rs_payment_worldpay')
		{
			return;
		}

		$db             = JFactory::getDbo();
		$request        = JRequest::get('request');

		$order_id       = $request['cartId'];
		$transStatus    = $request['transStatus'];
		$rawAuthMessage = $request['rawAuthMessage'];
		$transId        = $request['transId'];

		// Get params from plugin parameters
		$verify_status  = $this->params->get("verify_status");
		$invalid_status = $this->params->get("invalid_status");
		$values = new stdClass;

		if ($transStatus == "Y")
		{
			// UPDATE THE ORDER STATUS to 'VALID'
			$values->order_status_code = $verify_status;
			$values->order_payment_status_code = 'Paid';
			$values->log = JText::_('COM_REDSHOP_ORDER_PLACED');
			$values->msg = JText::_('COM_REDSHOP_ORDER_PLACED');
			$values->transaction_id = $transId;
			$values->order_id = $order_id;
		}
		else
		{
			$values->order_status_code = $invalid_status;
			$values->order_payment_status_code = 'Unpaid';
			$values->log = JText::_('COM_REDSHOP_ORDER_NOT_PLACED.');
			$values->msg = JText::_('COM_REDSHOP_ORDER_NOT_PLACED');
			$msg = JText::_('COM_REDSHOP_PHPSHOP_PAYMENT_ERROR');
			$values->order_id = $order_id;
		}

		return $values;
	}
}
