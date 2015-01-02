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

		if (empty($plugin))
		{
			$plugin = $element;
		}

		$app = JFactory::getApplication();
		include JPATH_SITE . '/plugins/redshop_payment/' . $plugin . '/' . $plugin . '/extra_info.php';
	}

	public function onNotifyPaymentrs_payment_amazoncheckout($element, $request)
	{
		if ($element != 'rs_payment_amazoncheckout')
		{
			return;
		}

		$db                = JFactory::getDbo();
		$request           = JRequest::get('request');

		JPlugin::loadLanguage('com_redshop');

		$verify_status     = $this->params->get('verify_status', '');
		$invalid_status    = $this->params->get('invalid_status', '');
		$auth_type         = $this->params->get('auth_type', '');

		$order_id          = $request['orderid'];
		$Itemid            = $request['Itemid'];
		$status            = $request['status'];

		$values = new stdClass;

		if ($request['status'] == 'PS' && $request['operation'] == 'pay')
		{
			$tid = $request['transactionId'];

			if ($this->orderPaymentNotYetUpdated($db, $order_id, $tid))
			{
				$transaction_id = $tid;
				$values->order_status_code = $verify_status;
				$values->order_payment_status_code = 'Paid';
				$values->log = JText::_('COM_REDSHOP_ORDER_PLACED');
				$values->msg = JText::_('COM_REDSHOP_ORDER_PLACED');
			}
			else
			{
				$transaction_id = $tid;
				$values->order_status_code = 'P';
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

		$values->transaction_id = $tid;
		$values->order_id = $order_id;

		return $values;
	}

	public function orderPaymentNotYetUpdated($dbConn, $order_id, $tid)
	{
		$db    = JFactory::getDbo();
		$res   = true;
		$query = "SELECT COUNT(*) `qty` FROM `#__redshop_order_payment` WHERE `order_id` = '" . $db->getEscaped($order_id) . "' and order_payment_trans_id = '" . $db->getEscaped($tid) . "'";
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
