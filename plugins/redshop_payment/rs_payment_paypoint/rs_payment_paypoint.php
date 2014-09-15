<?php
/**
 * @package     RedSHOP
 * @subpackage  Plugin
 *
 * @copyright   Copyright (C) 2005 - 2013 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

defined('_JEXEC') or die;

class plgRedshop_paymentrs_payment_paypoint extends JPlugin
{
	/**
	 * Plugin method with the same name as the event will be called automatically.
	 */
	public function onPrePayment_rs_payment_paypoint($element, $data)
	{
		$config = new Redconfiguration;

		// Get user billing information
		$user = JFActory::getUser();

		if ($element != 'rs_payment_paypoint')
		{
			return;
		}

		if (empty($plugin))
		{
			$plugin = $element;
		}

		$session                    = JFactory::getSession();
		$ccdata                     = $session->get('ccdata');

		// Additional Customer Data
		$user_id                    = $data['billinginfo']->user_id;
		$remote_add                 = $_SERVER["REMOTE_ADDR"];

		// Email Settings
		$user_email                 = $data['billinginfo']->user_email;

		// Get Credit card Information
		$order_payment_name         = substr($ccdata['order_payment_name'], 0, 50);
		$creditcard_code            = ucfirst(strtolower($ccdata['creditcard_code']));
		$order_payment_number       = substr($ccdata['order_payment_number'], 0, 20);
		$credit_card_code           = substr($ccdata['credit_card_code'], 0, 4);
		$order_payment_expire_month = substr($ccdata['order_payment_expire_month'], 0, 2);
		$order_payment_expire_year  = substr($ccdata['order_payment_expire_year'], -2);
		$order_number               = substr($data['order_number'], 0, 16);
		$tax_exempt                 = false;
		$debug_mode                 = $this->params->get('debug_mode', 0);

		// Get params from payment plugin
		$merchant_id = $this->params->get("paypoint_merchant_id");
		$vpn_password = $this->params->get("paypoint_vpn_password");
		$test_status = $this->params->get("paypoint_test_status");

		if ($test_status == 2)
		{
			$test_status = "live";
		}
		elseif ($test_status == 0)
		{
			$test_status = "false";
		}
		else
		{
			$test_status = "true";
		}

		$paymentpath = JPATH_SITE . '/plugins/redshop_payment/rs_payment_paypoint/xmlrpc.php';
		include $paymentpath;

		$f = new xmlrpcmsg('SECVPN.validateCardFull');
		$order_total = $data['order_total'];

		$txn_id = rand(1111111, 9999999);

		 // Test MerchantId
		$f->addParam(new xmlrpcval($merchant_id, "string"));

		// VPN password
		$f->addParam(new xmlrpcval($vpn_password, "string"));

		// Merchants transaction id
		$f->addParam(new xmlrpcval($txn_id, "string"));

		// The ip of the original caller
		$f->addParam(new xmlrpcval($remote_add, "string"));

		// Card Holders Name
		$f->addParam(new xmlrpcval($order_payment_name, "string"));

		// Card number
		$f->addParam(new xmlrpcval($order_payment_number, "string"));

		// Amount
		$f->addParam(new xmlrpcval($order_total, "string"));

		// Expiry Date
		$f->addParam(new xmlrpcval($order_payment_expire_month . "/" . $order_payment_expire_year, "string"));

		// Issue (Switch/Solo only)
		$f->addParam(new xmlrpcval("", "string"));

		// Start Date
		$f->addParam(new xmlrpcval("", "string"));

		// Order Item String
		$f->addParam(new xmlrpcval("", "string"));

		// Shipping Address
		$f->addParam(new xmlrpcval("", "string"));

		// Billing Address
		$f->addParam(new xmlrpcval("", "string"));

		// Options String
		$f->addParam(new xmlrpcval("test_status=" . $test_status . ",dups=false,card_type=Visa,cv2=" . $credit_card_code . "", "string"));

		print "<pre>sending data ...\n" . htmlentities($f->serialize()) . "... end of send\n</pre>";

		/*
			Create the XMLRPC client, using the server 'make_call', on the host 'www.secpay.com', via the https port '443'
		*/
		$c = new xmlrpc_client("/secxmlrpc/make_call", "www.secpay.com", 443);

		// Debugging is enabled for testing purposes
		$c->setDebug(1);

		// Send the request using the 'https' protocol.
		$r = $c->send($f, 20, "https");

		// Ensure that a response has been received from SECPay
		if (!$r)
		{
			die(" failed");
		}

		$v = $r->value();

		// Display response or fault information
		$pattern = $v->scalarval();
		$pattern = str_replace("&", " ", $pattern);
		$pattern = substr($pattern, 1);
		$arr = explode(" ", $pattern);
		$newk = array();

		for ($i = 0; $i < count($arr); $i++)
		{
			$key = $arr[$i];
			$key1 = explode("=", $key);

			$newk[$key1[0]] .= $key1[1];
		}

		if ($newk['valid'] == 'true')
		{
			if ($newk['code'] == "A")
			{
				$tid = $newk['trans_id'];

				if ($debug_mode == 1)
					$payment_messsge = $newk['message'];
				else
					$payment_messsge = JText::_('COM_REDSHOP_ORDER_PLACED');
				$values->responsestatus = 'Success';
			}
			else
			{
				if ($debug_mode == 1)
					$payment_messsge = $newk['message'];
				else
					$payment_messsge = JText::_('COM_REDSHOP_ORDER_NOT_PLACED.');
				$tid = 0;
				$values->responsestatus = 'Fail';
			}
		}
		else
		{
			if ($debug_mode == 1)
				$payment_messsge = $newk['message'];
			else
				$payment_messsge = JText::_('COM_REDSHOP_ORDER_NOT_PLACED.');
			$tid = 0;
			$values->responsestatus = 'Fail';
		}

		$values->transaction_id = $tid;
		$values->message = $payment_messsge;

		return $values;
	}
}
