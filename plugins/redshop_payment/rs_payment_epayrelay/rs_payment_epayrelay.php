<?php
/**
 * @package     RedSHOP
 * @subpackage  Plugin
 *
 * @copyright   Copyright (C) 2005 - 2013 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

defined('_JEXEC') or die;

class plgRedshop_paymentrs_payment_epayrelay extends JPlugin
{
	/**
	 * Plugin method with the same name as the event will be called automatically.
	 */
	public function onPrePayment($element, $data)
	{
		if ($element != 'rs_payment_epayrelay')
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

	/*
	 *  Plugin onNotifyPayment method with the same name as the event will be called automatically.
	 */
	public function onNotifyPaymentrs_payment_epayrelay($element, $request)
	{
		if ($element != 'rs_payment_epayrelay')
		{
			return false;
		}

		$db             = JFactory::getDbo();
		$tid            = $request["tid"];
		$order_id       = $request["orderid"];
		$order_amount   = $request["amount"];
		$order_ekey     = $request["eKey"];
		$order_currency = $request["cur"];

		JPlugin::loadLanguage('com_redshop');

		include JPATH_SITE . '/plugins/redshop_payment/' . $element . '/' . $element . '/epaysoap.php';

		// Access the web-service
		$epay            = new EpaySoap;
		$merchantnumber  = $this->params->get('merchant_id');
		$verify_status   = $this->params->get('verify_status', '');
		$verify_status   = $this->params->get('verify_status', '');
		$invalid_status  = $this->params->get('invalid_status', '');
		$auth_type       = $this->params->get('auth_type', '');
		$debug_mode      = $this->params->get('debug_mode', 0);
		$values          = new stdClass;
		$epay_paymentkey = $this->params->get('epay_paymentkey', '');
		$epay_md5        = $this->params->get('epay_md5', '');
		$transaction     = $epay->gettransaction($merchantnumber, $tid);

		// Now validate on the MD5 stamping. If the MD5 key is valid or if MD5 is disabled
		if (($order_ekey == md5($order_amount . $order_id . $tid . $epay_paymentkey)) || $epay_md5 == 0)
		{
			$db = JFactory::getDbo();
			$qv = "SELECT order_id, order_number FROM #__redshop_orders WHERE order_id='" . $order_id . "'";
			$db->setQuery($qv);
			$orders = $db->LoadObjectList();

			foreach ($orders as $order_detail)
			{
				$d['order_id'] = $order_detail->order_id;
			}

			// Switch on the order accept code
			// accept = 1 (standard redirect) accept = 2 (callback)
			if ($transaction['gettransactionResult'] == 'true')
			{
				if ($this->orderPaymentNotYetUpdated($db, $order_id, $tid))
				{
					if ($debug_mode == 1)
					{
						$payment_messsge = $transaction['transactionInformation']['history']['TransactionHistoryInfo']['eventMsg'];
					}
					else
					{
						$payment_messsge = JText::_('COM_REDSHOP_ORDER_PLACED');
					}

					// UPDATE THE ORDER STATUS to 'VALID'
					$transaction_id = $tid;
					$values->order_status_code = $verify_status;
					$values->order_payment_status_code = 'Paid';
					$values->log = $payment_messsge;
					$values->msg = $payment_messsge;
				}
			}
			else
			{
				if ($debug_mode == 1)
				{
					$payment_messsge = $epay->getEpayError($merchantnumber, $transaction['epayresponse']);
				}
				else
				{
					$payment_messsge = JText::_('COM_REDSHOP_ORDER_NOT_PLACED');
				}

				$values->order_status_code = $invalid_status;
				$values->order_payment_status_code = 'Unpaid';
				$values->log = $payment_messsge;
				$values->msg = $payment_messsge;
				$msg = JText::_('COM_REDSHOP_EPAY_PAYMENT_ERROR');
			}
		}

		$values->transaction_id = $tid;
		$values->order_id = $order_id;

		return $values;
	}

	public function onCapture_Paymentrs_payment_epayrelay($element, $data)
	{
		include JPATH_SITE . '/plugins/redshop_payment/' . $element . '/' . $element . '/epaysoap.php';

		// Access the webservice
		$epay           = new EpaySoap;
		$merchantnumber = $this->params->get('merchant_id');
		$order_id       = $data['order_id'];
		$tid            = $data['order_transactionid'];
		$order_amount   = round($data['order_amount'] * 100, 2);
		$response       = $epay->capture($merchantnumber, $tid, $order_amount);

		if ($response['captureResult'] == 'true')
		{
			$values->responsestatus = 'Success';
			$message = JText::_('ORDER_CAPTURED');
		}
		else
		{
			$message = JText::_('ORDER_NOT_CAPTURED');
			$values->responsestatus = 'Fail';
		}

		$values->message = $message;

		return $values;
	}

	public function orderPaymentNotYetUpdated($dbConn, $order_id, $tid)
	{
		$db = JFactory::getDbo();
		$res = false;
		$query = "SELECT COUNT(*) `qty` FROM #__redshop_order_payment WHERE `order_id` = '"
			. $db->getEscaped($order_id) . "' and order_payment_trans_id = '" . $db->getEscaped($tid) . "'";
		$db->setQuery($query);
		$order_payment = $db->loadResult();

		if ($order_payment == 0)
		{
			$res = true;
		}

		return $res;
	}
}
