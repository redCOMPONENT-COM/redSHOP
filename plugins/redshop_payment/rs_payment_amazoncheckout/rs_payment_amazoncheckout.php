<?php
/**
 * @package     RedSHOP
 * @subpackage  Plugin
 *
 * @copyright   Copyright (C) 2008 - 2015 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

defined('_JEXEC') or die;

class plgRedshop_paymentrs_payment_amazoncheckout extends JPlugin
{
	/**
	 * Plugin method with the same name as the event will be called automatically.
	 */
	public function onPrePayment($element, $data)
	{
		if ($element != 'rs_payment_amazoncheckout')
		{
			return;
		}

		include JPATH_SITE . '/plugins/redshop_payment/' . $element . '/' . $element . '/extra_info.php';
	}

	public function onNotifyPaymentrs_payment_amazoncheckout($element, $request)
	{
		if ($element != 'rs_payment_amazoncheckout')
		{
			return;
		}

		$request           = JRequest::get('request');

		JPlugin::loadLanguage('com_redshop');

		$verify_status     = $this->params->get('verify_status', '');
		$invalid_status    = $this->params->get('invalid_status', '');
		$order_id          = $request['orderid'];

		$values = new stdClass;

		if ($request['status'] == 'PS' && $request['operation'] == 'pay')
		{
			$tid = $request['transactionId'];

			if ($this->orderPaymentNotYetUpdated($order_id, $tid))
			{
				$values->transaction_id = $tid;
				$values->order_status_code = $verify_status;
				$values->order_payment_status_code = 'Paid';
				$values->log = JText::_('COM_REDSHOP_ORDER_PLACED');
				$values->msg = JText::_('COM_REDSHOP_ORDER_PLACED');
			}
			else
			{
				$values->transaction_id = $tid;
				$values->order_status_code = $invalid_status;
				$values->order_payment_status_code = 'Unpaid';
				$values->log = JText::_('COM_REDSHOP_ORDER_NOT_PLACED');
				$values->msg = JText::_('COM_REDSHOP_ORDER_NOT_PLACED');
			}
		}
		else
		{
			$values->order_status_code = $invalid_status;
			$values->order_payment_status_code = 'Unpaid';
			$values->log = JText::_('COM_REDSHOP_ORDER_NOT_PLACED.');
			$values->msg = JText::_('COM_REDSHOP_ORDER_NOT_PLACED');
		}

		$values->order_id = $order_id;

		return $values;
	}

	public function orderPaymentNotYetUpdated($order_id, $tid)
	{
		$db    = JFactory::getDbo();
		$res   = true;
		$query = $db->getQuery(true)
			->select('COUNT(*) AS qty')
			->from($db->qn('#__redshop_order_payment'))
			->where('order_id = ' . $db->q($order_id))
			->where('order_payment_trans_id = ' . $db->q($tid));
		$db->setQuery($query);
		$order_payment = $db->loadResult();

		if ($order_payment == 0)
		{
			$res = false;
		}

		return $res;
	}

	public function onCapture_Paymentrs_payment_amazoncheckout($element, $data)
	{
		return;
	}
}
