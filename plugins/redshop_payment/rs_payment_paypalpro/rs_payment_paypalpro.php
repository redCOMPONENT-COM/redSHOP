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
JLoader::load('RedshopHelperAdminOrder');

class PlgRedshop_Paymentrs_Payment_Paypalpro extends JPlugin
{
	/**
	 * Constructor
	 *
	 * @param   object  &$subject  The object to observe
	 * @param   array   $config    An optional associative array of configuration settings.
	 *                             Recognized key values include 'name', 'group', 'params', 'language'
	 *                             (this list is not meant to be comprehensive).
	 */
	public function __construct(&$subject, $config = array())
	{
		JPlugin::loadLanguage('plg_redshop_payment_rs_payment_paypalpro');
		parent::__construct($subject, $config);
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

		$session = JFactory::getSession();
		$ccdata = $session->get('ccdata');

		// Set request-specific fields.
		$paymentType      = urlencode($this->params->get("sales_auth_only"));

		$firstName        = urlencode($data['billinginfo']->firstname);
		$lastName         = urlencode($data['billinginfo']->lastname);
		$creditCardType   = urlencode($ccdata['creditcard_code']);
		$creditCardNumber = urlencode($ccdata['order_payment_number']);
		$expDateMonth     = $ccdata['order_payment_expire_month'];

		// Month must be padded with leading zero
		$padDateMonth     = urlencode(str_pad($expDateMonth, 2, '0', STR_PAD_LEFT));

		$expDateYear      = urlencode($ccdata['order_payment_expire_year']);
		$cvv2Number       = urlencode($ccdata['credit_card_code']);
		$address1         = urlencode($data['billinginfo']->address);
		$city             = urlencode($data['billinginfo']->city);
		$state            = urlencode($data['billinginfo']->state_code);
		$zip              = urlencode($data['billinginfo']->zipcode);

		// US or other valid country code
		$country = urlencode($data['billinginfo']->country_code);
		$amount  = urlencode(number_format($data['order_total'], 2));

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

		$transaction_id = $httpParsedResponseAr['TRANSACTIONID'];
		$values = new stdClass;

		if ("SUCCESS" == strtoupper($httpParsedResponseAr["ACK"])
			|| "SUCCESSWITHWARNING" == strtoupper($httpParsedResponseAr["ACK"]))
		{

			$values->responsestatus = 'Success';
			$message                = JText::_('PLG_RS_PAYMENT_PAYPALPRO_ORDER_PLACED');
			$messageType            = 'Success';

			// We are not placing order when card is success with warning from paypal
			if ("SUCCESSWITHWARNING" == strtoupper($httpParsedResponseAr["ACK"]))
			{
				$messageType = 'Warning';
			}
		}
		else
		{
			$values->responsestatus = 'Fail';
			$message                = JText::_('PLG_RS_PAYMENT_PAYPALPRO_ORDER_NOT_PLACED');
			$messageType            = 'Error';
		}

		// Set response message only for Error or Warning
		if (1 == $this->params->get('debug_mode', 0)
			&& ('Error' == $messageType || 'Warning' == $messageType))
		{
			$message = urldecode($httpParsedResponseAr["L_ERRORCODE0"] . ' <br>' . $httpParsedResponseAr["L_SHORTMESSAGE0"] . ' <br>' . $httpParsedResponseAr["L_LONGMESSAGE0"]);

			JFactory::getApplication()->enqueueMessage($message, $messageType);
		}

		$values->transaction_id = $transaction_id;
		$values->message        = $message;

		return $values;
	}

	public function PPHttpPost($methodName, $nvpStr_)
	{
		// Set up your API credentials, PayPal end point, and API version.
		$API_UserName  = urlencode($this->params->get('api_username', ''));
		$API_Password  = urlencode($this->params->get('api_password', ''));
		$API_Signature = urlencode($this->params->get('api_signature', ''));

		$API_Endpoint = "https://api-3t.paypal.com/nvp";
		$isTest       = $this->params->get('is_test', '');

		if ($isTest)
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
		$nvpreq = "METHOD=$API_method&VERSION=$version&PWD=$API_Password&USER=$API_UserName&SIGNATURE=$API_Signature$nvpStr_" . '&BUTTONSOURCE=redCOMPONENT_SP';

		// Set the request as a POST FIELD for curl.
		curl_setopt($ch, CURLOPT_POSTFIELDS, $nvpreq);

		// Get response from the server.
		$httpResponse = curl_exec($ch);

		if (!$httpResponse)
		{
			exit(JText::sprintf('PLG_RS_PAYMENT_PAYPALPRO_METHOD_FAILED', $methodName, $ch, $ch));
		}

		// Extract the response details.
		$httpResponseAr = explode("&", $httpResponse);

		$httpParsedResponseAr = array();

		foreach ($httpResponseAr as $i => $value)
		{
			$tmpAr = explode("=", $value);

			if (count($tmpAr) > 1)
			{
				$httpParsedResponseAr[$tmpAr[0]] = $tmpAr[1];
			}
		}

		if ((0 == count($httpParsedResponseAr)) || !array_key_exists('ACK', $httpParsedResponseAr))
		{
			exit(JText::sprintf('PLG_RS_PAYMENT_PAYPALPRO_METHOD_INVALID_RESPONSE', $nvpreq, $API_Endpoint));
		}

		return $httpParsedResponseAr;
	}

	public function onCapture_Paymentrs_payment_paypalpro($element, $data)
	{
		// Set request-specific fields.
		$authorizationID = $data['order_transactionid'];
		$amount          = urlencode($data['order_amount']);

		// Or other currency ('GBP', 'EUR', 'JPY', 'CAD', 'AUD')
		$currency         = urlencode(CURRENCY_CODE);
		$completeCodeType = urlencode('Complete');
		$note             = urlencode(JText::_('COM_REDSHOP_CAPTURED_PAYMENT'));

		// Add request-specific fields to the request string.
		$nvpStr = "&AUTHORIZATIONID=$authorizationID&AMT=$amount&COMPLETETYPE=$completeCodeType&CURRENCYCODE=$currency&NOTE=$note";

		// Execute the API operation; see the PPHttpPost function above.
		$httpParsedResponseAr = $this->PPHttpPost('DoCapture', $nvpStr);

		$values = new stdClass;

		if ("SUCCESSWITHWARNING" == strtoupper($httpParsedResponseAr["ACK"]))
		{
			$message                = urldecode($httpParsedResponseAr["L_ERRORCODE0"] . ' <br>' . $httpParsedResponseAr["L_SHORTMESSAGE0"] . ' <br>' . $httpParsedResponseAr["L_LONGMESSAGE0"]);
			$values->responsestatus = 'Fail';
			$messageType            = 'Warning';
		}
		else if ("SUCCESS" == strtoupper($httpParsedResponseAr["ACK"]))
		{
			$values->responsestatus = 'Success';
			$message                = JText::_('COM_REDSHOP_TRANSACTION_APPROVED');
			$messageType            = 'Success';
		}
		else
		{
			$message                = urldecode($httpParsedResponseAr["L_ERRORCODE0"] . ' <br>' . $httpParsedResponseAr["L_SHORTMESSAGE0"] . ' <br>' . $httpParsedResponseAr["L_LONGMESSAGE0"]);
			$values->responsestatus = 'Fail';
			$messageType            = 'Error';
		}

		$values->message = $message;

		JFactory::getApplication()->enqueueMessage($message, $messageType);

		return $values;
	}
}
