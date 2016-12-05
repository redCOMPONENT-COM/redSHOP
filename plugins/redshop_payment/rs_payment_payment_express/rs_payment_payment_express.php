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

/**
 * PlgRedshop_PaymentRs_Payment_Payment_Express installer class.
 *
 * @package  Redshopb.Plugin
 * @since    1.7.0
 */
class PlgRedshop_PaymentRs_Payment_Payment_Express extends JPlugin
{
	/**
	 * Load the language file on instantiation.
	 *
	 * @var    boolean
	 */
	protected $autoloadLanguage = true;

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

		$encryptHex = $request["result"];

		$verifyStatus = $this->params->get('verify_status', '');
		$pxPostUsername = $this->params->get('px_post_username', '');
		$pxPostLabelKey = $this->params->get('px_post_label_key', '');
		$invalidStatus = $this->params->get('invalid_status', '');
		$debugMode = $this->params->get('debug_mode', 0);

		// GetResponse method in PxPay object returns PxPayResponse object
		$pxPayUrl = "https://sec2.paymentexpress.com/pxpay/pxaccess.aspx";
		$pxpay = new PxPay_Curl($pxPayUrl, $pxPostUsername, $pxPostLabelKey);

		// Which encapsulates all the response data
		$rsp = $pxpay->getResponse($encryptHex);

		// The following are the fields available in the PxPayResponse object
		$billingId = $rsp->getBillingId();
		$dpsTxnRef = $rsp->getDpsTxnRef();
		$responseText = $rsp->getResponseText();

		$orderId = $billingId;
		$values = new stdClass;

		// Update the order status to 'CONFIRMED'
		if ($rsp->getSuccess() == "1")
		{
			// Success: update the order status to 'CONFIRMED'
			$values->order_status_code = $verifyStatus;
			$values->order_payment_status_code = 'Paid';

			if ($debugMode == 1)
			{
				$values->log = $responseText;
				$values->msg = $responseText;
			}
			else
			{
				$values->log = JText::_('COM_REDSHOP_ORDER_PLACED');
				$values->msg = JText::_('COM_REDSHOP_ORDER_PLACED');
			}

			$values->order_id = $orderId;
			$values->transaction_id = $dpsTxnRef;
		}
		else
		{
			// Failed: update the order status to 'PENDING'
			$values->order_status_code = $invalidStatus;
			$values->order_payment_status_code = 'Unpaid';

			if ($debugMode == 1)
			{
				$values->log = $responseText;
				$values->msg = $responseText;
			}
			else
			{
				$values->log = JText::_('COM_REDSHOP_ORDER_NOT_PLACED');
				$values->msg = JText::_('COM_REDSHOP_ORDER_NOT_PLACED');
			}

			$values->order_id = $orderId;
			$values->transaction_id = $dpsTxnRef;
		}

		return array($values);
	}

	/**
	 * [onPrePayment description]
	 *
	 * @param   [string]  $element  [plugin name]
	 * @param   [array]   $data     [data array]
	 *
	 * @return  [void]
	 */
	public function onPrePayment($element, $data)
	{
		if ($element != 'rs_payment_payment_express')
		{
			return;
		}

		include JPATH_SITE . '/plugins/redshop_payment/' . $element . '/' . $element . '/extra_info.php';
	}

	/**
	 * [onPrePayment_rs_payment_payment_express description]
	 *
	 * @param   [string]  $element  [plugin name]
	 * @param   [array]   $data     [data array]
	 *
	 * @return  [object/void]  $values
	 */
	public function onPrePayment_rs_payment_payment_express($element, $data)
	{
		if ($element != 'rs_payment_payment_express')
		{
			return;
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
			$paymentpath = JPATH_SITE . '/plugins/redshop_payment/' . $element . '/' . $element . '/extra_info.php';
			include $paymentpath;
		}
	}

	/**
	 * [parse_xml description]
	 *
	 * @param   [array]  $data  [data params]
	 *
	 * @return  [xml]
	 */
	public function parse_xml($data)
	{
		$xmlParser = xml_parser_create();
		xml_parse_into_struct($xmlParser, $data, $vals, $index);
		xml_parser_free($xmlParser);

		$params = array();
		$level = array();

		foreach ($vals as $xmlElem)
		{
			if ($xmlElem['type'] == 'open')
			{
				if (array_key_exists('attributes', $xmlElem))
				{
					list($level[$xmlElem['level']], $extra) = array_values($xmlElem['attributes']);
				}
				else
				{
					$level[$xmlElem['level']] = $xmlElem['tag'];
				}
			}

			if ($xmlElem['type'] == 'complete')
			{
				$startLevel = 1;
				$phpStmt = '$params';

				while ($startLevel < $xmlElem['level'])
				{
					$phpStmt .= '[$level[' . $startLevel . ']]';
					$startLevel++;
				}

				$phpStmt .= '[$xmlElem[\'tag\']] = $xmlElem[\'value\'];';
				@eval($phpStmt);
			}
		}

		return $params;
	}

	/**
	 * [onCapture_Paymentrs_payment_payment_express description]
	 *
	 * @param   [string]  $element  [plugin name]
	 * @param   [array]   $data     [data array]
	 *
	 * @return  [object]  $values
	 */
	public function onCapture_Paymentrs_payment_payment_express($element, $data)
	{
		if ($element != 'rs_payment_payment_express')
		{
			return;
		}

		$objOrder = order_functions::getInstance();
		$db = JFactory::getDbo();
		JPlugin::loadLanguage('com_redshop');
		$orderId = $data['order_id'];

		if ($this->params->get("px_post_txntype") == 'Auth')
		{
			$orderDetail = $objOrder->getOrderPaymentDetail($data['order_id']);
			$cmdDoTxnTransaction = "";
			$db = JFactory::getDbo();

			// Get user billing information

			// Calculate AmountInput
			$amount = $orderTotal;

			$orderPaymentAmount  = $orderDetail[0]->order_payment_amount;
			$orderPaymentTransid = $orderDetail[0]->order_payment_trans_id;

			$currency = Redshop::getConfig()->get('CURRENCY_CODE');
			$cmdDoTxnTransaction .= "<Txn>";

			// Insert your DPS Username here
			$cmdDoTxnTransaction .= "<PostUsername>" . $this->params->get("px_post_username") . "</PostUsername>";

			// Insert your DPS Password here
			$cmdDoTxnTransaction .= "<PostPassword>" . $this->params->get("px_post_password") . "</PostPassword>";
			$cmdDoTxnTransaction .= "<Amount>$orderPaymentAmount</Amount>";
			$cmdDoTxnTransaction .= "<InputCurrency>$currency</InputCurrency>";
			$cmdDoTxnTransaction .= "<DpsTxnRef>$orderPaymentTransid</DpsTxnRef>";
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
