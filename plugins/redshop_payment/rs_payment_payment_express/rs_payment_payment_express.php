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
require_once JPATH_SITE . '/plugins/redshop_payment/rs_payment_payment_express/rs_payment_payment_express/PxPay_Curl.inc.php';

class plgRedshop_paymentrs_payment_payment_express extends JPlugin
{
	/**
	 * onNotify Payment rs_payment_payment_express
	 *
	 * @param   string  $element  Element
	 * @param   string  $request  Request
	 *
	 * @return  array|null
	 */
	public function onNotifyPaymentrs_payment_payment_express($element, $request)
	{
		if ($element != 'rs_payment_payment_express')
		{
			return;
		}

		$enc_hex = $request["result"];

		$verify_status = $this->params->get('verify_status', '');
		$px_post_username = $this->params->get('px_post_username', '');
		$px_post_label_key = $this->params->get('px_post_label_key', '');
		$invalid_status = $this->params->get('invalid_status', '');
		$debug_mode = $this->params->get('debug_mode', 0);

		// GetResponse method in PxPay object returns PxPayResponse object
		$PxPay_Url = "https://sec2.paymentexpress.com/pxpay/pxaccess.aspx";
		$pxpay = new PxPay_Curl($PxPay_Url, $px_post_username, $px_post_label_key);

		// Which encapsulates all the response data
		$rsp = $pxpay->getResponse($enc_hex);

		// The following are the fields available in the PxPayResponse object
		$BillingId = $rsp->getBillingId();
		$DpsTxnRef = $rsp->getDpsTxnRef();
		$ResponseText = $rsp->getResponseText();

		$order_id = $BillingId;
		$values = new stdClass;

		// Update the order status to 'CONFIRMED'
		if ($rsp->getSuccess() == "1")
		{
			// Success: update the order status to 'CONFIRMED'
			$values->order_status_code = $verify_status;
			$values->order_payment_status_code = 'Paid';

			if ($debug_mode == 1)
			{
				$values->log = $ResponseText;
				$values->msg = $ResponseText;
			}
			else
			{
				$values->log = JText::_('COM_REDSHOP_ORDER_PLACED');
				$values->msg = JText::_('COM_REDSHOP_ORDER_PLACED');
			}

			$values->order_id = $order_id;
			$values->transaction_id = $DpsTxnRef;
		}
		else
		{
			// Failed: update the order status to 'PENDING'
			$values->order_status_code = $invalid_status;
			$values->order_payment_status_code = 'Unpaid';

			if ($debug_mode == 1)
			{
				$values->log = $ResponseText;
				$values->msg = $ResponseText;
			}
			else
			{
				$values->log = JText::_('COM_REDSHOP_ORDER_NOT_PLACED');
				$values->msg = JText::_('COM_REDSHOP_ORDER_NOT_PLACED');
			}

			$values->order_id = $order_id;
			$values->transaction_id = $DpsTxnRef;
		}

		return array($values);
	}

	public function onPrePayment($element, $data)
	{
		if ($element != 'rs_payment_payment_express')
		{
			return;
		}

		if (empty($plugin))
		{
			$plugin = $element;
		}

		include JPATH_SITE . '/plugins/redshop_payment/' . $plugin . '/' . $plugin . '/extra_info.php';
	}

	/**
	 * Plugin method with the same name as the event will be called automatically.
	 */
	public function onPrePayment_rs_payment_payment_express($element, $data)
	{
		if ($element != 'rs_payment_payment_express')
		{
			return;
		}

		if (empty($plugin))
		{
			$plugin = $element;
		}

		if ($this->params->get("px_post_txnmethod") == 'PxPost')
		{
			// Pxpost
			$cmdDoTxnTransaction = "";

			// Get user billing information
			$session = JFactory::getSession();
			$ccdata = $session->get('ccdata');

			// Calculate AmountInput

			$amount = $data['order_total'];

			// Convert .8 to .80
			$amount = sprintf("%9.2f", $amount);

			$currency = Redshop::getConfig()->get('CURRENCY_CODE');
			$merchRef = substr($data['billinginfo']->firstname, 0, 50) . " " . substr($data['billinginfo']->lastname, 0, 50);
			$cmdDoTxnTransaction .= "<Txn>";

			// Insert your DPS Username here
			$cmdDoTxnTransaction .= "<PostUsername>" . $this->params->get("px_post_username")
				. "</PostUsername>";

			// Insert your DPS Password here
			$cmdDoTxnTransaction .= "<PostPassword>" . $this->params->get("px_post_password")
				. "</PostPassword>";
			$cmdDoTxnTransaction .= "<Amount>" . $amount . "</Amount>";
			$cmdDoTxnTransaction .= "<InputCurrency>$currency</InputCurrency>";
			$cmdDoTxnTransaction .= "<CardHolderName>" . $ccdata['order_payment_name'] . "</CardHolderName>";
			$cmdDoTxnTransaction .= "<CardNumber>" . $ccdata['order_payment_number'] . "</CardNumber>";
			$cmdDoTxnTransaction .= "<DateExpiry>" . ($ccdata['order_payment_expire_month']) . substr($ccdata['order_payment_expire_year'], 2, 2) . "</DateExpiry>";
			$cmdDoTxnTransaction .= "<Cvc2>" . $ccdata['credit_card_code'] . "</Cvc2>";
			$cmdDoTxnTransaction .= "<TxnType>" . $this->params->get("px_post_txntype") . "</TxnType>";
			$cmdDoTxnTransaction .= "<TxnData1>" . JText::_('COM_REDSHOP_ORDER_ID') . " : "
				. $order_number . "</TxnData1>";
			$cmdDoTxnTransaction .= "<MerchantReference>$merchRef</MerchantReference>";
			$cmdDoTxnTransaction .= "</Txn>";

			$URL = "sec2.paymentexpress.com/pxpost.aspx";

			$ch = curl_init();
			curl_setopt($ch, CURLOPT_URL, "https://" . $URL);
			curl_setopt($ch, CURLOPT_POST, 1);
			curl_setopt($ch, CURLOPT_POSTFIELDS, $cmdDoTxnTransaction);
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);

			// Needs to be included if no *.crt is available to verify SSL certificates
			// Curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
			curl_setopt($ch, CURLOPT_SSLVERSION, 3);
			$result = curl_exec($ch);

			curl_close($ch);

			$params = $this->parse_xml($result);

			if (!$params)
			{
				return false;
			}

			$response = $params['TXN']['SUCCESS'];
			$authorized = $params['TXN'][$response]['AUTHORIZED'];

			$values = new stdClass;

			// Approved - Success!
			if ($authorized == '1')
			{
				$values->responsestatus = 'Success';
			}
			else
			{
				$values->responsestatus = 'Fail';
			}

			$values->transaction_id = $params['TXN'][$response]['DPSTXNREF'];
			$values->message = $params['TXN'][$response]['CARDHOLDERHELPTEXT'];

			return $values;
		}
		else
		{
			$app = JFactory::getApplication();
			$paymentpath = JPATH_SITE . '/plugins/redshop_payment/' . $plugin . '/' . $plugin . '/extra_info.php';
			include $paymentpath;
		}

	}

	public function parse_xml($data)
	{
		$xml_parser = xml_parser_create();
		xml_parse_into_struct($xml_parser, $data, $vals, $index);
		xml_parser_free($xml_parser);

		$params = array();
		$level = array();

		foreach ($vals as $xml_elem)
		{
			if ($xml_elem['type'] == 'open')
			{
				if (array_key_exists('attributes', $xml_elem))
				{
					list($level[$xml_elem['level']], $extra) = array_values($xml_elem['attributes']);
				}
				else
				{
					$level[$xml_elem['level']] = $xml_elem['tag'];
				}
			}

			if ($xml_elem['type'] == 'complete')
			{
				$start_level = 1;
				$php_stmt = '$params';

				while ($start_level < $xml_elem['level'])
				{
					$php_stmt .= '[$level[' . $start_level . ']]';
					$start_level++;
				}

				$php_stmt .= '[$xml_elem[\'tag\']] = $xml_elem[\'value\'];';
				@eval($php_stmt);
			}
		}

		return $params;
	}

	public function onCapture_Paymentrs_payment_payment_express($element, $data)
	{
		if ($element != 'rs_payment_payment_express')
		{
			return;
		}

		$objOrder = order_functions::getInstance();
		$db = JFactory::getDbo();
		JPlugin::loadLanguage('com_redshop');
		$order_id = $data['order_id'];
		$Itemid = $_REQUEST['Itemid'];

		if ($this->params->get("px_post_txntype") == 'Auth')
		{
			$orderDetail = $objOrder->getOrderPaymentDetail($data['order_id']);
			$cmdDoTxnTransaction = "";
			$db = JFactory::getDbo();

			// Get user billing information

			// Calculate AmountInput
			$amount = $order_total;

			$order_payment_amount = $orderDetail[0]->order_payment_amount;
			$order_payment_trans_id = $orderDetail[0]->order_payment_trans_id;

			$currency = Redshop::getConfig()->get('CURRENCY_CODE');
			$cmdDoTxnTransaction .= "<Txn>";

			// Insert your DPS Username here
			$cmdDoTxnTransaction .= "<PostUsername>" . $this->params->get("px_post_username") . "</PostUsername>";

			// Insert your DPS Password here
			$cmdDoTxnTransaction .= "<PostPassword>" . $this->params->get("px_post_password") . "</PostPassword>";
			$cmdDoTxnTransaction .= "<Amount>$order_payment_amount</Amount>";
			$cmdDoTxnTransaction .= "<InputCurrency>$currency</InputCurrency>";
			$cmdDoTxnTransaction .= "<DpsTxnRef>$order_payment_trans_id</DpsTxnRef>";
			$cmdDoTxnTransaction .= "<TxnType>Complete</TxnType>";
			$cmdDoTxnTransaction .= "</Txn>";

			$URL = "sec2.paymentexpress.com/pxpost.aspx";

			$ch = curl_init();
			curl_setopt($ch, CURLOPT_URL, "https://" . $URL);
			curl_setopt($ch, CURLOPT_POST, 1);
			curl_setopt($ch, CURLOPT_POSTFIELDS, $cmdDoTxnTransaction);
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);

			// Needs to be included if no *.crt is available to verify SSL certificates
			// curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);

			curl_setopt($ch, CURLOPT_SSLVERSION, 3);
			$result = curl_exec($ch);

			curl_close($ch);

			$params = $this->parse_xml($result);

			if (!$params)
			{
				return false;
			}

			$PX_msg = $params['TXN']['RESPONSETEXT'];
			$authorized = $params['TXN']['SUCCESS'];
			$message = $params['TXN'][$response]['CARDHOLDERHELPTEXT'];

			// Approved - Success!

			$values = new stdClass;

			if ($authorized == '1' || $authorized == 1)
			{
				$values->responsestatus = 'Success';
			}
			else
			{
				$values->responsestatus = 'Fail';
			}

			$values->message = $message;

			return $values;
		}
	}
}
