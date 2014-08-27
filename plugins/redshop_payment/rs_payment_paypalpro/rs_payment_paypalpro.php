<?php
/**
 * @package     RedSHOP
 * @subpackage  Plugin
 *
 * @copyright   Copyright (C) 2005 - 2013 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

defined('_JEXEC') or die;

jimport('joomla.plugin.plugin');

JLoader::import('loadhelpers', JPATH_SITE . '/components/com_redshop');
JLoader::load('RedshopHelperAdminOrder');

class plgRedshop_paymentrs_payment_paypalpro extends JPlugin
{
	var $_table_prefix = null;

	/**
	 * Constructor
	 *
	 * For php4 compatability we must not use the __constructor as a constructor for
	 * plugins because func_get_args ( void ) returns a copy of all passed arguments
	 * NOT references.  This causes problems with cross-referencing necessary for the
	 * observer design pattern.
	 */
	public function plgRedshop_paymentrs_payment_paypalpro(&$subject)
	{
		// Load plugin parameters
		parent::__construct($subject);
		$this->_table_prefix = '#__redshop_';
		$this->_plugin = JPluginHelper::getPlugin('redshop_payment', 'rs_payment_paypalpro');
		$this->_params = new JRegistry($this->_plugin->params);
	}

	/**
	 * Plugin method with the same name as the event will be called automatically.
	 */
	public function onPrePayment_rs_payment_paypalpro($element, $data)
	{
		if ($element != 'rs_payment_paypalpro')
		{
			return;
		}

		if (empty($plugin))
		{
			$plugin = $element;
		}

		$app = JFactory::getApplication();
		$objOrder = new order_functions;
		$uri = JURI::getInstance();
		$url = $uri->root();
		$user = JFactory::getUser();
		$sessionid = session_id();
		$session = JFactory::getSession();
		$ccdata = $session->get('ccdata');

		// Set request-specific fields.
		$paymentType = urlencode($this->_params->get("sales_auth_only"));

		$debug_mode = $this->_params->get('debug_mode', 0); // or 'Sale'
		$firstName = urlencode($data['billinginfo']->firstname);
		$lastName = urlencode($data['billinginfo']->lastname);
		$creditCardType = urlencode($ccdata['creditcard_code']);
		$creditCardNumber = urlencode($ccdata['order_payment_number']);
		$expDateMonth = $ccdata['order_payment_expire_month'];

		// Month must be padded with leading zero
		$padDateMonth = urlencode(str_pad($expDateMonth, 2, '0', STR_PAD_LEFT));

		$expDateYear = urlencode($ccdata['order_payment_expire_year']);
		$cvv2Number = urlencode($creditCardType);
		$address1 = urlencode($data['billinginfo']->address);
		$address2 = urlencode('customer_address2');
		$city = urlencode($data['billinginfo']->city);
		$state = urlencode($data['billinginfo']->state_code);
		$zip = urlencode($data['billinginfo']->zipcode);

		// US or other valid country code
		$country = urlencode($data['billinginfo']->country_code);
		$amount = urlencode(number_format($data['order_total'], 2));

		// Or other currency ('GBP', 'EUR', 'JPY', 'CAD', 'AUD')
		$currencyID = urlencode(CURRENCY_CODE);

		if ($creditCardType == "MC")
		{
			$creditCardType = "MasterCard";
		}

		// Add request-specific fields to the request string.
		$nvpStr = "&PAYMENTACTION=$paymentType&AMT=$amount&CREDITCARDTYPE=$creditCardType&ACCT=$creditCardNumber" .
			"&EXPDATE=$padDateMonth$expDateYear&CVV2=$cvv2Number&FIRSTNAME=$firstName&LASTNAME=$lastName" .
			"&STREET=$address1&CITY=$city&STATE=$state&ZIP=$zip&COUNTRYCODE=$country&CURRENCYCODE=$currencyID";

		// Execute the API operation; see the PPHttpPost function above.
		$httpParsedResponseAr = $this->PPHttpPost('DoDirectPayment', $nvpStr);

		$transaction_id = $request['transaction_id'];

		if ("SUCCESS" == strtoupper($httpParsedResponseAr["ACK"]) || "SUCCESSWITHWARNING" == strtoupper($httpParsedResponseAr["ACK"]))
		{
			$values->responsestatus = 'Success';

			if ($debug_mode == 1)
			{
				$message = $httpParsedResponseAr["L_ERRORCODE0"] . ' <br>' . $httpParsedResponseAr["L_SHORTMESSAGE0"] . ' <br>' . $httpParsedResponseAr["L_LONGMESSAGE0"];
			}
			else
			{
				$message = JText::_('COM_REDSHOP_ORDER_PLACED');
			}
		}
		else
		{
			$values->responsestatus = 'Fail';

			if ($debug_mode == 1)
			{
				$message = $httpParsedResponseAr["L_ERRORCODE0"] . ' <br>' . $httpParsedResponseAr["L_SHORTMESSAGE0"] . ' <br>' . $httpParsedResponseAr["L_LONGMESSAGE0"];
			}
			else
			{
				$message = JText::_('COM_REDSHOP_ORDER_NOT_PLACED');
			}
		}

		$values->transaction_id = $transaction_id;
		$values->message = $message;

		return $values;
	}

	public function PPHttpPost($methodName_, $nvpStr_)
	{
		$paypalpro_parameters = $this->getparameters('rs_payment_paypalpro');
		$paymentinfo = $paypalpro_parameters[0];
		$paymentparams = new JRegistry($paymentinfo->params);

		$api_username = $paymentparams->get('api_username', '');
		$api_password = $paymentparams->get('api_password', '');
		$api_signature = $paymentparams->get('api_signature', '');
		$sales_auth_only = $paymentparams->get('sales_auth_only', '');

		// Set up your API credentials, PayPal end point, and API version.
		$API_UserName = urlencode($api_username);
		$API_Password = urlencode($api_password);
		$API_Signature = urlencode($api_signature);
		$API_method = urlencode('DoDirectPayment');

		$API_Endpoint = "https://api-3t.paypal.com/nvp";
		$apiurl = $paymentparams->get('is_test', '');

		if ($apiurl)
		{
			$API_Endpoint = "https://api-3t.sandbox.paypal.com/nvp";
		}

		$version = urlencode('51.0');

		// Set the curl parameters.
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, $API_Endpoint);
		curl_setopt($ch, CURLOPT_VERBOSE, 1);

		// Turn off the server and peer verification (TrustManager Concept).
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
		curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);

		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($ch, CURLOPT_POST, 1);

		// Set the API operation, version, and API signature in the request.
		$nvpreq = "METHOD=$API_method&VERSION=$version&PWD=$API_Password&USER=$API_UserName&SIGNATURE=$API_Signature$nvpStr_";

		// Set the request as a POST FIELD for curl.
		curl_setopt($ch, CURLOPT_POSTFIELDS, $nvpreq);

		// Get response from the server.
		$httpResponse = curl_exec($ch);

		if (!$httpResponse)
		{
			exit("$methodName_ failed: " . curl_error($ch) . '(' . curl_errno($ch) . ')');
		}

		// Extract the response details.
		$httpResponseAr = explode("&", $httpResponse);

		$httpParsedResponseAr = array();

		foreach ($httpResponseAr as $i => $value)
		{
			$tmpAr = explode("=", $value);

			if (sizeof($tmpAr) > 1)
			{
				$httpParsedResponseAr[$tmpAr[0]] = $tmpAr[1];
			}
		}

		if ((0 == sizeof($httpParsedResponseAr)) || !array_key_exists('ACK', $httpParsedResponseAr))
		{
			exit("Invalid HTTP Response for POST request($nvpreq) to $API_Endpoint.");
		}

		return $httpParsedResponseAr;
	}

	/*
	 *  Plugin onNotifyPayment method with the same name as the event will be called automatically.
	 */
	public function onNotifyPaymentrs_payment_paypalpro($element, $request)
	{
		if ($element != 'rs_payment_paypalpro')
		{
			return false;
		}

		$db = JFactory::getDbo();
		$request = JRequest::get('request');
		$accept = $request["accept"];
		$tid = $request["tid"];
		$order_id = $request["orderid"];
		$Itemid = $request["Itemid"];
		$order_amount = $request["amount"];
		@$order_ekey = $request["eKey"];
		@$error = $request["error"];
		$order_currency = $request["cur"];

		JPlugin::loadLanguage('com_redshop');
		$amazon_parameters = $this->getparameters('rs_payment_epay');
		$paymentinfo = $amazon_parameters[0];
		$paymentparams = new JRegistry($paymentinfo->params);

		$verify_status = $paymentparams->get('verify_status', '');
		$invalid_status = $paymentparams->get('invalid_status', '');
		$auth_type = $paymentparams->get('auth_type', '');

		$values = new stdClass;

		// Now validat on the MD5 stamping. If the MD5 key is valid or if MD5 is disabled
		//
		if ((@$order_ekey == md5($order_amount . $order_id . $tid . $epay_paymentkey)) || $epay_md5 == 0)
		{
			// Find the corresponding order in the database

			$db = JFactory::getDbo();
			$qv = "SELECT order_id, order_number FROM " . $this->_table_prefix . "orders WHERE order_id='" . $order_id . "'";
			$db->setQuery($qv);
			$orders = $db->LoadObjectList();

			foreach ($orders as $order_detail)
			{
				$d['order_id'] = $order_detail->order_id;
			}

			// Switch on the order accept code
			// accept = 1 (standard redirect) accept = 2 (callback)
			if (empty($request['errorcode']) && ($accept == "1" || $accept == "2"))
			{
				// Only update the order information once
				if ($this->orderPaymentNotYetUpdated($db, $order_id, $tid))
				{
					// UPDATE THE ORDER STATUS to 'VALID'
					$transaction_id = $tid;
					$values->order_status_code = $verify_status;
					$values->order_payment_status_code = 'Paid';
					$values->log = JText::_('COM_REDSHOP_ORDER_PLACED');
					$values->msg = JText::_('COM_REDSHOP_ORDER_PLACED');

					// Add history callback info
					if ($accept == "2")
					{
						$msg = JText::_('COM_REDSHOP_EPAY_PAYMENT_CALLBACK');
					}

					// Payment fee
					if ($request["transfee"])
					{
						$msg = JText::_('COM_REDSHOP_EPAY_PAYMENT_FEE');
					}

					// Payment date
					if ($request["date"])
					{
						$msg = JText::_('COM_REDSHOP_EPAY_PAYMENT_DATE');
					}

					// Payment fraud control
					if (@$request["fraud"])
					{
						$msg = JText::_('COM_REDSHOP_EPAY_FRAUD');
					}

					// Card id
					if ($request["cardid"])
					{
						$cardname = "Unknown";
						$cardimage = "c" . $_REQUEST["cardid"] . ".gif";

						switch ($_REQUEST["cardid"])
						{
							case 1:
								$cardname = 'Dankort (DK)';
								break;
							case 2:
								$cardname = 'Visa/Dankort (DK)';
								break;
							case 3:
								$cardname = 'Visa Electron (Udenlandsk)';
								break;
							case 4:
								$cardname = 'Mastercard (DK)';
								break;
							case 5:
								$cardname = 'Mastercard (Udenlandsk)';
								break;
							case 6:
								$cardname = 'Visa Electron (DK)';
								break;
							case 7:
								$cardname = 'JCB (Udenlandsk)';
								break;
							case 8:
								$cardname = 'Diners (DK)';
								break;
							case 9:
								$cardname = 'Maestro (DK)';
								break;
							case 10:
								$cardname = 'American Express (DK)';
								break;
							case 11:
								$cardname = 'Ukendt';
								break;
							case 12:
								$cardname = 'eDankort (DK)';
								break;
							case 13:
								$cardname = 'Diners (Udenlandsk)';
								break;
							case 14:
								$cardname = 'American Express (Udenlandsk)';
								break;
							case 15:
								$cardname = 'Maestro (Udenlandsk)';
								break;
							case 16:
								$cardname = 'Forbrugsforeningen (DK)';
								break;
							case 17:
								$cardname = 'eWire';
								break;
							case 18:
								$cardname = 'VISA';
								break;
							case 19:
								$cardname = 'IKANO';
								break;
							case 20:
								$cardname = 'Andre';
								break;
							case 21:
								$cardname = 'Nordea';
								break;
							case 22:
								$cardname = 'Danske Bank';
								break;
							case 23:
								$cardname = 'Danske Bank';
								break;
						}

						$msg = JText::_('COM_REDSHOP_EPAY_PAYMENT_CARDTYPE');
					}

					// Creation information
					$msg = JText::_('COM_REDSHOP_EPAY_PAYMENT_LOG_TID');
					$msg = JText::_('COM_REDSHOP_EPAY_PAYMENT_TRANSACTION_SUCCESS');
				}
			}
			elseif ($accept == "0")
			{
				$values->order_status_code = $invalid_status;
				$values->order_payment_status_code = 'Unpaid';
				$values->log = JText::_('COM_REDSHOP_ORDER_NOT_PLACED.');
				$values->msg = JText::_('COM_REDSHOP_ORDER_NOT_PLACED');
				$msg = JText::_('COM_REDSHOP_EPAY_PAYMENT_ERROR');
			}
			else
			{
				$values->order_status_code = $invalid_status;
				$values->order_payment_status_code = 'Unpaid';
				$values->log = JText::_('COM_REDSHOP_ORDER_NOT_PLACED.');
				$values->msg = JText::_('COM_REDSHOP_ORDER_NOT_PLACED');
				$msg = JText::_('COM_REDSHOP_PHPSHOP_PAYMENT_ERROR');
			}
		}
		else
		{
			$values->order_status_code = $invalid_status;
			$values->order_payment_status_code = 'Unpaid';
			$values->log = JText::_('COM_REDSHOP_ORDER_NOT_PLACED.');
			$values->msg = JText::_('COM_REDSHOP_ORDER_NOT_PLACED');
			$msg = JText::_('COM_REDSHOP_PHPSHOP_PAYMENT_ERROR');
		}

		$values->transaction_id = $tid;
		$values->order_id = $order_id;

		return $values;
	}

	public function getparameters($payment)
	{
		$db = JFactory::getDbo();
		$sql = "SELECT * FROM #__extensions WHERE `element`='" . $payment . "'";
		$db->setQuery($sql);
		$params = $db->loadObjectList();

		return $params;
	}

	public function orderPaymentNotYetUpdated($dbConn, $order_id, $tid)
	{
		$db = JFactory::getDbo();
		$res = false;
		$query = "SELECT COUNT(*) `qty` FROM " . $this->_table_prefix . "order_payment WHERE `order_id` = '" . $db->getEscaped($order_id) . "' and order_payment_trans_id = '" . $db->getEscaped($tid) . "'";
		$db->setQuery($query);
		$order_payment = $db->loadResult();

		if ($order_payment == 0)
		{
			$res = true;
		}

		return $res;
	}

	public function onCapture_Paymentrs_payment_paypalpro($element, $data)
	{
		$db = JFactory::getDbo();
		$objOrder = new order_functions;

		$paypalpro_parameters = $this->getparameters('rs_payment_paypalpro');
		$paymentinfo = $paypalpro_parameters[0];
		$paymentparams = new JRegistry($paymentinfo->params);

		// Set request-specific fields.
		$authorizationID = urlencode($paymentparams->get('api_username'));
		$amount = urlencode($data['order_amount']);

		// Or other currency ('GBP', 'EUR', 'JPY', 'CAD', 'AUD')
		$currency = urlencode(CURRENCY_CODE);
		$completeCodeType = urlencode('Complete'); // or 'NotComplete'
		$invoiceID = urlencode($data['order_transaction_id']);
		$note = urlencode(JText::_('COM_REDSHOP_CAPTURED_PAYMENT'));

		// Add request-specific fields to the request string.
		$nvpStr = "&AUTHORIZATIONID=$authorizationID&AMT=$amount&COMPLETETYPE=$completeCodeType&CURRENCYCODE=$currency&NOTE=$note";

		// Execute the API operation; see the PPHttpPost function above.
		$httpParsedResponseAr = $this->PPHttpPost('DoCapture', $nvpStr);

		if ("SUCCESS" == strtoupper($httpParsedResponseAr["ACK"]) || "SUCCESSWITHWARNING" == strtoupper($httpParsedResponseAr["ACK"]))
		{
			$values->responsestatus = 'Success';
			$message = JText::_('COM_REDSHOP_TRANSACTION_APPROVED');
		}
		else
		{
			$message = $httpParsedResponseAr["L_ERRORCODE0"] . ' <br>' . $httpParsedResponseAr["L_SHORTMESSAGE0"] . ' <br>' . $httpParsedResponseAr["L_LONGMESSAGE0"];
			$values->responsestatus = 'Fail';
		}

		$values->message = $message;

		return $values;
	}
}
