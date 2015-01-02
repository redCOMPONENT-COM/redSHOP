<?php
/**
 * @package     RedSHOP
 * @subpackage  Plugin
 *
 * @copyright   Copyright (C) 2005 - 2015 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

defined('_JEXEC') or die;

class plgRedshop_paymentrs_payment_realex_redirect extends JPlugin
{
	/**
	 * Plugin method with the same name as the event will be called automatically.
	 */
	public function onPrePayment_rs_payment_realex_redirect($element, $data)
	{
		if ($element != 'rs_payment_realex_redirect')
		{
			return;
		}

		if (empty($plugin))
		{
			$plugin = $element;
		}

		$session    = JFactory::getSession();
		$ccdata     = $session->get('ccdata');
		$merchantid = $this->params->get("realex_merchant_id");
		$secret     = $this->params->get("realex_shared_secret");
		$account    = $this->params->get("realex_account_name");
		$ip_address = $_SERVER['REMOTE_ADDR'];

		require_once JPATH_SITE . '/plugins/redshop_payment/rs_payment_realex_redirect/rs_payment_realex_redirect/class.realmpi.php';

		$timestamp  = strftime("%Y%m%d%H%M%S");
		mt_srand((double) microtime() * 1000000);
		$orderid    = $data['order_number'];
		$curr       = CURRENCY_CODE;
		$amount     = $data['carttotal'];
		$tmp        = "$timestamp.$merchantid.$orderid.$amount.$curr";
		$md5hash    = md5($tmp);
		$tmp        = "$md5hash.$secret";
		$md5hash    = md5($tmp);
		$cardnumber = $ccdata['order_payment_number'];
		$cardtype   = urlencode($ccdata['creditcard_code']);
		$exp_month  = $ccdata['order_payment_expire_month'];
		$exp_year   = $ccdata['order_payment_expire_year'];
		$exp_year   = substr($exp_year, 2, 2);
		$expdate    = $exp_month . $exp_year;
		$amount     = round($data['carttotal']);

		$realex = new Realex;
		$total = $data['order_total'] * 100;
		$response = $realex->createRequest(
			array(
				"merchantid"     => $merchantid,
				"secret"         => $secret,
				"account"        => $account,
				"orderid"        => $data['order_number'],
				"amount"         => $total,
				"currency"       => CURRENCY_CODE,
				"cardnumber"     => $cardnumber,
				"cardname"       => "Owen O Byrne",
				"cardtype"       => $cardtype,
				"expdate"        => $expdate,
				"autosettleflag" => "1",
			)
		);
	}

	function onNotifyPaymentrs_payment_realex_redirect($element, $request)
	{
		if ($element != 'rs_payment_realex_redirect')
		{
			return;
		}

		$db      = JFactory::getDbo();
		$request = JRequest::get('request');

		JPlugin::loadLanguage('com_redshop');

		$verify_status  = $this->params->get('verify_status', '');
		$invalid_status = $this->params->get('invalid_status', '');
		$auth_type      = $this->params->get('auth_type', '');
		$order_id       = $request['orderid'];
		$status         = $request['status'];
		$values         = new stdClass;

		if ($request['status'] == 'PS' && $request['operation'] == 'pay')
		{
			$tid = $request['transactionId'];

			if ($this->orderPaymentNotYetUpdated($db, $order_id, $tid))
			{
				$transaction_id = $tid;
				$values->order_status_code = $verify_status;
				$values->order_payment_status_code = 'PAID';
				$values->log = JText::_('COM_REDSHOP_ORDER_PLACED');
				$values->msg = JText::_('COM_REDSHOP_ORDER_PLACED');
			}
		}
		else
		{
			$values->order_status_code = $invalid_status;
			$values->order_payment_status_code = 'UNPAID';
			$values->log = JText::_('COM_REDSHOP_ORDER_NOT_PLACED.');
			$values->msg = JText::_('COM_REDSHOP_ORDER_NOT_PLACED');
		}

		$values->transaction_id = $tid;
		$values->order_id = $order_id;

		return $values;
	}

	function orderPaymentNotYetUpdated($dbConn, $order_id, $tid)
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

	function onCapture_Paymentrs_payment_realex_redirect($element, $data)
	{
		return;
	}
}
