<?php
/**
 * @package     RedSHOP
 * @subpackage  Plugin
 *
 * @copyright   Copyright (C) 2005 - 2015 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

defined('_JEXEC') or die;

class plgRedshop_paymentrs_payment_eway3dsecure extends JPlugin
{
	/**
	 * Plugin method with the same name as the event will be called automatically.
	 */
	public function onPrePayment($element, $data)
	{
		if ($element != 'rs_payment_eway3dsecure')
		{
			return;
		}

		if (empty($plugin))
		{
			$plugin = $element;
		}

		$app = JFactory::getApplication();

		include JPATH_SITE . '/plugins/redshop_payment/' . $plugin . '/' . $plugin . '/extra_info.php';
	}

	public function onNotifyPaymentrs_payment_eway3dsecure($element, $request)
	{
		if ($element != 'rs_payment_eway3dsecure')
		{
			return;
		}

		$db                  = JFactory::getDbo();
		$request             = JRequest::get('request');
		$result              = $request["ewayTrxnStatus"];
		$trxnReference       = $request["ewayTrxnReference"];
		$eWAYresponseText    = $request["eWAYresponseText"];
		$eWAYReturnAmount    = $request["eWAYReturnAmount"];
		$eWAYAuthCode        = $request["eWAYAuthCode"];
		$order_id            = $request['orderid'];
		$Itemid              = $request['Itemid'];

		$verify_status  = $this->params->get('verify_status', '');
		$invalid_status = $this->params->get('invalid_status', '');

		if ($transaction_number = "")
		{
			$transaction_number = "NOT DEFINED";
		}

		if ($result == 'True')
		{
			// UPDATE THE ORDER STATUS to 'VALID'
			$values->order_status_code = $verify_status;
			$values->order_payment_status_code = 'PAID';
			$values->log = JText::_('COM_REDSHOP_ORDER_PLACED');
			$values->msg = JText::_('COM_REDSHOP_ORDER_PLACED');
		}
		else
		{
			$values->order_status_code = $invalid_status;
			$values->order_payment_status_code = 'UNPAID';
			$values->log = JText::_('COM_REDSHOP_ORDER_NOT_PLACED.');
			$values->msg = JText::_('COM_REDSHOP_ORDER_NOT_PLACED');
		}

		$values->transaction_id = $trxnReference;
		$values->order_id = $order_id;

		return $values;
	}

	public function onCapture_Paymentrs_payment_eway3dsecure($element, $data)
	{
		return;
	}
}
