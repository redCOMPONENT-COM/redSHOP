<?php
/**
 * @package     RedSHOP
 * @subpackage  Plugin
 *
 * @copyright   Copyright (C) 2008 - 2015 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

defined('_JEXEC') or die;

class plgRedshop_paymentrs_payment_ewayuk extends JPlugin
{
	/**
	 * Plugin method with the same name as the event will be called automatically.
	 */
	public function onPrePayment($element, $data)
	{
		if ($element != 'rs_payment_ewayuk')
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

	public function onNotifyPaymentrs_payment_ewayuk($element, $request)
	{
		if ($element != 'rs_payment_ewayuk')
		{
			return;
		}

		$db                  = JFactory::getDbo();
		$TransactionAccepted = $request["TransactionAccepted"];
		$Reference           = $request["Reference"];
		$RETC                = $request["RETC"];
		$m_4                 = $request["m_4"];
		$m_5                 = $request["m_5"];
		$m_6                 = $request["m_6"];
		$Reason              = $request["Reason"];
		$Amount              = $request["Amount"];

		JPlugin::loadLanguage('com_redshop');

		$verify_status  = $this->params->get('verify_status', '');
		$invalid_status = $this->params->get('invalid_status', '');
		$auth_type      = $this->params->get('auth_type', '');
		$order_id       = $request['orderid'];
		$status         = $request['status'];
		$values         = new stdClass;

		if ($TransactionAccepted == 'true')
		{
			$tid = $request['RETC'];

			if ($this->orderPaymentNotYetUpdated($db, $order_id, $tid))
			{
				$transaction_id = $tid;
				$values->order_status_code = $verify_status;
				$values->order_payment_status_code = 'Paid';
				$values->log = JText::_('COM_REDSHOP_ORDER_PLACED');
				$values->msg = JText::_('COM_REDSHOP_ORDER_PLACED');
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
		$db = JFactory::getDbo();
		$res = false;
		$query = "SELECT COUNT(*) FROM #__redshop_order_payment WHERE `order_id` = '" . $db->getEscaped($order_id) . "' and order_payment_trans_id = '" . $db->getEscaped($tid) . "'";
		$db->setQuery($query);
		$order_payment = $db->loadResult();

		if ($order_payment == 0)
		{
			$res = true;
		}

		return $res;
	}

	public function onCapture_Paymentrs_payment_ewayuk($element, $data)
	{
		return;
	}

	public function fetch_data($string, $start_tag, $end_tag)
	{
		$position = stripos($string, $start_tag);

		$str = substr($string, $position);

		$str_second = substr($str, strlen($start_tag));

		$second_positon = stripos($str_second, $end_tag);

		$str_third = substr($str_second, 0, $second_positon);

		$fetch_data = trim($str_third);

		return $fetch_data;
	}
}
