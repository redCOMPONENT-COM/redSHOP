<?php
/**
 * @package     RedSHOP
 * @subpackage  Plugin
 *
 * @copyright   Copyright (C) 2008 - 2015 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

defined('_JEXEC') or die;

class plgRedshop_paymentrs_payment_quickpay extends JPlugin
{
	/**
	 * Plugin method with the same name as the event will be called automatically.
	 */
	public function onPrePayment($element, $data)
	{
		if ($element != 'rs_payment_quickpay')
		{
			return;
		}

		if (empty($plugin))
		{
			$plugin = $element;
		}

		$app         = JFactory::getApplication();

		include JPATH_SITE . '/plugins/redshop_payment/' . $plugin . '/' . $plugin . '/extra_info.php';
	}

	function onNotifyPaymentrs_payment_quickpay($element, $request)
	{
		if ($element != 'rs_payment_quickpay')
		{
			return;
		}

		$db      = JFactory::getDbo();
		$request = JRequest::get('request');

		$order_id       = $request["orderid"];
		$order_amount   = $request["amount"];
		$order_currency = $request["currency"];
		$order_currency = $request["time"];
		$order_ekey     = $request["state"];
		$qpstat         = $request["qpstat"];
		$chstat         = $request["chstat"];
		$transaction    = $request["transaction"];
		$tid            = $request["transaction"];
		$cardtype       = $request["cardtype"];
		$cardnumber     = $request["cardnumber"];
		$md5check       = $request["md5check"];

		$md5word = $request["md5word"];
		$ok_page = $request["callback"];

		$verify_status  = $this->params->get('verify_status', '');
		$invalid_status = $this->params->get('invalid_status', '');

		/*
		 * Switch on the order accept code
		 * accept = 000 (callback)
		 */
		if ($qpstat == "000")
		{
			// Find the corresponding order in the database
			$db = JFactory::getDbo();
			$qv = "SELECT order_id, order_number FROM #__redshop_orders WHERE order_id='" . $order_id . "'";
			$db->setQuery($qv);
			$orders = $db->LoadObjectList();

			if ($orders)
			{
				foreach ($orders as $order_detail)
				{
					$d['order_id'] = $order_detail->order_id;
				}

				// UPDATE THE ORDER STATUS to 'VALID'
				$values->order_status_code         = $verify_status;
				$values->order_payment_status_code = 'Paid';
				$values->log                       = JText::_('COM_REDSHOP_ORDER_PLACED');
				$values->msg                       = JText::_('COM_REDSHOP_ORDER_PLACED');
			}
		}
		else
		{
			$values->order_status_code         = $invalid_status;
			$values->order_payment_status_code = 'Unpaid';
			$values->log                       = JText::_('COM_REDSHOP_ORDER_NOT_PLACED.');
			$values->msg                       = JText::_('COM_REDSHOP_ORDER_NOT_PLACED');
		}

		$values->transaction_id = $transaction;
		$values->order_id       = $order_id;

		return $values;
	}

	/**
	 * Send Information on QuickPay using CURL
	 *
	 * @param   array   $data  Order Information
	 * @param   string  $type  Request Type
	 *
	 * @return  object         Simple XML object
	 */
	private function sendQuickpayRequest($data, $type)
	{
		$protocol     = 7;
		$msgtype      = $type;
		$finalize     = 1;
		$merchant_id  = $this->params->get("quickpay_customer_id");
		$order_amount = ($data['order_amount'] * 100);
		$transaction  = $data['order_transactionid'];
		$md5word      = $this->params->get("quickpay_paymentkey");
		$md5check     = md5($protocol . $msgtype . $merchant_id . $order_amount . $finalize . $transaction . $md5word);

		$message = array(
						'protocol'    => $protocol,
						'msgtype'     => $msgtype,
						'merchant'    => $merchant_id,
						'amount'      => $order_amount,
						'finalize'    => $finalize,
						'transaction' => $transaction,
						'md5check'    => $md5check
					);

		$ch = curl_init('https://secure.quickpay.dk/api');
		$encoded = http_build_query($message, false, '&');

		curl_setopt($ch, CURLOPT_POSTFIELDS,  $encoded);
		curl_setopt($ch, CURLOPT_HEADER, 0);
		curl_setopt($ch, CURLOPT_POST, 1);

		// Return the transfer as a string
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		$response = curl_exec($ch);
		curl_close($ch);

		$response  = new SimpleXMLElement($response);

		return $response;
	}

	function onCapture_Paymentrs_payment_quickpay($element, $data)
	{
		if ($element != 'rs_payment_quickpay')
		{
			return;
		}

		$response  = $this->sendQuickpayRequest($data, 'capture');
		$qpstat    = $response->qpstat;
		$qpstatmsg = addslashes($response->qpstatmsg);

		if ($qpstat == '000')
		{
			$values->responsestatus = 'Success';
			$message                = JText::_('COM_REDSHOP_ORDER_CAPTURED');
		}
		else
		{
			$message                = $qpstatmsg ? $qpstatmsg : JText::_('COM_REDSHOP_ORDER_NOT_CAPTURED');
			$values->responsestatus = 'Fail';
		}

		$values->message = $message;

		return $values;
	}

	function onRefund_Paymentrs_payment_quickpay($element, $data)
	{
		if ($element != 'rs_payment_quickpay')
		{
			return;
		}

		$response  = $this->sendQuickpayRequest($data, 'refund');
		$qpstat    = $response->qpstat;
		$qpstatmsg = addslashes($response->qpstatmsg);

		if ($qpstat == '000')
		{
			$values->responsestatus = 'Success';
			$message                = JText::_('QUICKPAY_ORDER_REFUND');
		}
		else
		{
			$message                = $qpstatmsg ? $qpstatmsg : JText::_('QUICKPAY_ORDER_NOT_REFUND');
			$values->responsestatus = 'Fail';
		}

		$values->message = $message;

		return $values;
	}

	function onStatus_Paymentrs_payment_quickpay($element, $data)
	{
		if ($element != 'rs_payment_quickpay')
		{
			return;
		}

		$response        = $this->sendQuickpayRequest($data, 'status');
		$status_count    = count($response->history) - 1;
		$quickpay_status = $response->history[$status_count]->msgtype;

		if ($quickpay_status == "authorize")
		{
			$data_refund = $this->onCancel_Paymentrs_payment_quickpay($element, $data);
		}
		elseif ($quickpay_status == "capture")
		{
			$data_refund = $this->onRefund_Paymentrs_payment_quickpay($element, $data);
		}

		return $data_refund;
	}

	function onCancel_Paymentrs_payment_quickpay($element, $data)
	{
		if ($element != 'rs_payment_quickpay')
		{
			return;
		}

		$response  = $this->sendQuickpayRequest($data, 'cancel');
		$qpstat    = $response->qpstat;
		$qpstatmsg = addslashes($response->qpstatmsg);

		if ($qpstat == '000')
		{
			$values->responsestatus = 'Success';
			$message                = JText::_('ORDER_CANCEL');
		}
		else
		{
			$message                = $qpstatmsg ? $qpstatmsg : JText::_('ORDER_NOT_CANCEL');
			$values->responsestatus = 'Fail';
		}

		$values->message = $message;

		return $values;
	}
}
