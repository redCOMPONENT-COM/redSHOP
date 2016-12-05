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

/**
 * PlgRedshop_PaymentRs_Payment_PayFlowPro class.
 *
 * @package  Redshopb.Plugin
 * @since    1.7.0
 */
class PlgRedshop_PaymentRs_Payment_PayFlowPro extends JPlugin
{
	/**
	 * Load the language file on instantiation.
	 *
	 * @var    boolean
	 * @since  3.1
	 */
	protected $autoloadLanguage = true;

	/**
	 * [onPrePayment_rs_payment_payflowpro description]
	 *
	 * @param   [string]  $element  [plugin name]
	 * @param   [array]   $data     [data params]
	 *
	 * @return  [object]  $values
	 */
	public function onPrePayment_rs_payment_payflowpro($element, $data)
	{
		if ($element != 'rs_payment_payflowpro')
		{
			return;
		}

		$app               = JFactory::getApplication();
		$user              = JFactory::getUser();

		$session           = JFactory::getSession();
		$ccdata            = $session->get('ccdata');

		// Get Payment Params
		$partner          = $this->params->get("partner");
		$merchantId       = $this->params->get("merchant_id");
		$merchantPassword = $this->params->get("merchant_password");
		$merchantUser     = $this->params->get("merchant_user");
		$paymentType      = $this->params->get("sales_auth_only");
		$isTest           = $this->params->get("is_test");

		// Get Customer Data
		$firstName        = urlencode($data['billinginfo']->firstname);
		$lastName         = urlencode($data['billinginfo']->lastname);
		$address          = urlencode($data['billinginfo']->address);
		$city             = urlencode($data['billinginfo']->city);
		$state            = urlencode($data['billinginfo']->state_code);
		$zip              = urlencode($data['billinginfo']->zipcode);
		$country          = urlencode($data['billinginfo']->country_2_code);
		$userEmail        = $data['billinginfo']->user_email;

		$shippingFirstName = urlencode($data['shippinginfo']->firstname);
		$shippingLastName  = urlencode($data['shippinginfo']->lastname);
		$shippingAddress   = urlencode($data['shippinginfo']->address);
		$shippingCity      = urlencode($data['shippinginfo']->city);
		$shippingState     = urlencode($data['shippinginfo']->state_code);
		$shippingZipcode   = urlencode($data['shippinginfo']->zipcode);
		$shippingCountry   = urlencode($data['shippinginfo']->country_2_code);

		// Get CreditCard Data
		$strCardHolder    = substr($ccdata['order_payment_name'], 0, 100);
		$creditCardType   = urlencode($ccdata['creditcard_code']);
		$creditCardNumber = urlencode($ccdata['order_payment_number']);
		$strExpiryDate    = substr($ccdata['order_payment_expire_month'], 0, 2) . substr($ccdata['order_payment_expire_year'], -2);
		$strCV2           = substr($ccdata['credit_card_code'], 0, 4);

		if ($this->params->get("currency") != "")
		{
			$currencyID = $this->params->get("currency");
		}
		elseif (Redshop::getConfig()->get('CURRENCY_CODE') != "")
		{
			$currencyID = urlencode(Redshop::getConfig()->get('CURRENCY_CODE'));
		}
		else
		{
			$currencyID = "USD";
		}

		$currencyClass = CurrencyHelper::getInstance();

		/*
		As per the email error no need to remove shipping - tmp fix
		$orderTotal = $data['order_total'] - $data['order_shipping'] - $data['order_tax'];
		$orderTotal = $data['order_total'] - $data['order_tax'];
		*/
		$orderTotal     = $data['order_total'];
		$amount          = $currencyClass->convert($orderTotal, '', $currencyID);
		$amount          = urlencode(number_format($amount, 2));

		$shippingAmount = $data['order_shipping'];
		$shippingAmount = $currencyClass->convert($shippingAmount, '', $currencyID);
		$shippingAmount = urlencode(number_format($shippingAmount, 2));

		$taxAmount      = $data['order_tax'];
		$taxAmount      = $currencyClass->convert($taxAmount, '', $currencyID);
		$taxAmount      = urlencode(number_format($taxAmount, 2));

		if ($isTest)
		{
			$apiUrl = "https://pilot-payflowpro.paypal.com";
		}
		else
		{
			$apiUrl = "https://payflowpro.paypal.com";
		}

		$params = array(
			'USER'      => $merchantUser,
			'VENDOR'    => $merchantId,
			'PARTNER'   => $partner,
			'PWD'       => $merchantPassword,
			'TENDER'    => 'C',
			'TRXTYPE'   => $paymentType,
			'AMT'       => $amount,
			'CURRENCY'  => $currencyID,
			'FIRSTNAME' => $firstName,
			'LASTNAME'  => $lastName,
			'STREET'    => $address,
			'CITY'      => $city,
			'STATE'     => $state,
			'COUNTRY'   => $country,
			'ZIP'       => $zip,
			'CLIENTIP'  => $_SERVER['REMOTE_ADDR'],
			'EMAIL'     => $userEmail,
			'ACCT'      => $creditCardNumber,
			'EXPDATE'   => $strExpiryDate,
			'CVV2'      => $strCV2
		);

		if ($taxAmount > 0)
		{
			$params['TAXAMT'] = $taxAmount;
		}

		if ($shippingAmount > 0)
		{
			$params['FREIGHTAMT'] = $shippingAmount;
		}

		if (count($data['shippinginfo']) > 0)
		{
			$shippingParams = array(
				'SHIPTOFIRSTNAME' => $shippingFirstName,
				'SHIPTOLASTNAME'  => $shippingLastName,
				'SHIPTOCOUNTRY'   => $shippingCountry,
				'SHIPTOCITY'      => $shippingCity,
				'SHIPTOSTREET'    => $shippingAddress,
				'SHIPTOZIP'       => $shippingZipcode
			);
		}

		$params = array_merge($params, $shippingParams);

		$postString = '';

		foreach ($params as $key => $value)
		{
			$postString .= $key . '[' . strlen(urlencode(utf8_encode(trim($value)))) . ']=' . urlencode(utf8_encode(trim($value))) . '&';
		}

		$postString    = substr($postString, 0, -1);
		$response       = $this->sendTransactionToGateway($apiUrl, $postString, array('X-VPS-REQUEST-ID: ' . md5($creditCardNumber . rand())));
		$responseArray = array();
		parse_str($response, $responseArray);

		if ($responseArray['RESULT'] == 0 && $responseArray['RESPMSG'] == 'Approved')
		{
			$values->responsestatus = 'Success';
			$message = JText::_('COM_REDSHOP_ORDER_PLACED');
		}
		else
		{
			$values->responsestatus = 'Fail';
			$message = $responseArray['RESPMSG'];
		}

		$values->transaction_id = $responseArray['PNREF'];
		$values->message = $message;

		return $values;
	}

	/**
	 * [sendTransactionToGateway description]
	 *
	 * @param   [string]  $url         [url string]
	 * @param   [array]   $parameters  [params]
	 * @param   [html]    $headers     [header html]
	 *
	 * @return  [html]
	 */
	public function sendTransactionToGateway($url, $parameters, $headers = null)
	{
		$header = array();
		$server = parse_url($url);

		if (!isset($server['port']))
		{
			$server['port'] = ($server['scheme'] == 'https') ? 443 : 80;
		}

		if (!isset($server['path']))
		{
			$server['path'] = '/';
		}

		if (isset($server['user']) && isset($server['pass']))
		{
			$header[] = 'Authorization: Basic ' . base64_encode($server['user'] . ':' . $server['pass']);
		}

		if (!empty($headers) && is_array($headers))
		{
			$header = array_merge($header, $headers);
		}

		if (function_exists('curl_init'))
		{
			$curl = curl_init($server['scheme'] . '://' . $server['host'] . $server['path'] . (isset($server['query']) ? '?' . $server['query'] : ''));
			curl_setopt($curl, CURLOPT_PORT, $server['port']);
			curl_setopt($curl, CURLOPT_HEADER, 0);
			curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, 0);
			curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
			curl_setopt($curl, CURLOPT_FORBID_REUSE, 1);
			curl_setopt($curl, CURLOPT_FRESH_CONNECT, 1);
			curl_setopt($curl, CURLOPT_POST, 1);
			curl_setopt($curl, CURLOPT_POSTFIELDS, $parameters);

			if (!empty($header))
			{
				curl_setopt($curl, CURLOPT_HTTPHEADER, $header);
			}

			$result = curl_exec($curl);
			curl_close($curl);
		}

		return $result;
	}

	/**
	 * [onNotifyPaymentrs_payment_payflowpro description]
	 *
	 * @param   [string]  $element  [plugin name]
	 * @param   [string]  $request  [request params]
	 *
	 * @return  [void]
	 */
	public function onNotifyPaymentrs_payment_payflowpro($element, $request)
	{
		if ($element != 'rs_payment_payflowpro')
		{
			return false;
		}

		return;
	}

	/**
	 * [onCapture_Paymentrs_payment_payflowpro description]
	 *
	 * @param   [string]  $element  [plugin name]
	 * @param   [array]   $data     [data params]
	 *
	 * @return  [object]  $values
	 */
	public function onCapture_Paymentrs_payment_payflowpro($element, $data)
	{
		// Get Payment Params
		$partner          = $this->params->get("partner");
		$merchantId       = $this->params->get("merchant_id");
		$merchantPassword = $this->params->get("merchant_password");
		$merchantUser     = $this->params->get("merchant_user");
		$paymentType      = $this->params->get("sales_auth_only");
		$isTest           = $this->params->get("is_test");

		$orderId = $data['order_id'];
		$tid     = $data['order_transactionid'];

		if ($this->params->get("currency") != "")
		{
			$currencyID = $this->params->get("currency");
		}
		elseif (Redshop::getConfig()->get('CURRENCY_CODE') != "")
		{
			$currencyID = urlencode(Redshop::getConfig()->get('CURRENCY_CODE'));
		}
		else
		{
			$currencyID = "USD";
		}

		$currencyClass = CurrencyHelper::getInstance();
		$orderAmount = $currencyClass->convert($data['order_amount'], '', $currencyID);
		$orderAmount = urlencode(number_format($orderAmount, 2));

		if ($isTest)
		{
			$apiUrl = "https://pilot-payflowpro.paypal.com";
		}
		else
		{
			$apiUrl = "https://payflowpro.paypal.com";
		}

		$params = array(
			'USER'    => $merchantUser,
			'VENDOR'  => $merchantId,
			'PARTNER' => $partner,
			'PWD'     => $merchantPassword,
			'TENDER'  => 'C',
			'TRXTYPE' => 'D',
			'AMT'     => $orderAmount,
			'ORIGID'  => $tid
		);
		$postString = '';

		foreach ($params as $key => $value)
		{
			$postString .= $key . '[' . strlen(urlencode(utf8_encode(trim($value)))) . ']=' . urlencode(utf8_encode(trim($value))) . '&';
		}

		$postString = substr($postString, 0, -1);
		$response = $this->sendTransactionToGateway($apiUrl, $postString, array('X-VPS-REQUEST-ID: ' . md5($orderId . rand())));

		$responseArray = array();
		parse_str($response, $responseArray);

		if ($responseArray['RESULT'] == 0 && $responseArray['RESPMSG'] == 'Approved')
		{
			$values->responsestatus = 'Success';
			$message = JText::_('COM_REDSHOP_TRANSACTION_APPROVED');
		}
		else
		{
			$values->responsestatus = 'Fail';
			$message = $responseArray['RESPMSG'];
		}

		$values->message = $message;

		return $values;
	}

	/**
	 * [onStatus_Paymentrs_payment_payflowpro description]
	 *
	 * @param   [string]  $element  [plugin name]
	 * @param   [array]   $data     [data params]
	 *
	 * @return  [object]  $values
	 */
	public function onStatus_Paymentrs_payment_payflowpro($element, $data)
	{
		// Get Payment Params
		$partner           = $this->params->get("partner");
		$merchantId       = $this->params->get("merchant_id");
		$merchantPassword = $this->params->get("merchant_password");
		$merchantUser     = $this->params->get("merchant_user");
		$paymentType       = $this->params->get("sales_auth_only");
		$isTest           = $this->params->get("is_test");

		$orderId = $data['order_id'];
		$tid      = $data['order_transactionid'];

		if ($this->params->get("currency") != "")
		{
			$currencyID = $this->params->get("currency");
		}
		elseif (Redshop::getConfig()->get('CURRENCY_CODE') != "")
		{
			$currencyID = urlencode(Redshop::getConfig()->get('CURRENCY_CODE'));
		}
		else
		{
			$currencyID = "USD";
		}

		$currencyClass = CurrencyHelper::getInstance();
		$orderAmount = $currencyClass->convert($data['order_amount'], '', $currencyID);
		$orderAmount = urlencode(number_format($orderAmount, 2));

		if ($isTest)
		{
			$apiUrl = "https://pilot-payflowpro.paypal.com";
		}
		else
		{
			$apiUrl = "https://payflowpro.paypal.com";
		}

		$params = array(
			'USER'    => $merchantUser,
			'VENDOR'  => $merchantId,
			'PARTNER' => $partner,
			'PWD'     => $merchantPassword,
			'TENDER'  => 'C',
			'TRXTYPE' => 'C',
			'AMT'     => $orderAmount,
			'ORIGID'  => $tid
		);
		$postString = '';

		foreach ($params as $key => $value)
		{
			$postString .= $key . '[' . strlen(urlencode(utf8_encode(trim($value)))) . ']=' . urlencode(utf8_encode(trim($value))) . '&';
		}

		$postString = substr($postString, 0, -1);
		$response = $this->sendTransactionToGateway($apiUrl, $postString, array('X-VPS-REQUEST-ID: ' . md5($orderId . rand())));

		$responseArray = array();
		parse_str($response, $responseArray);

		if ($responseArray['RESULT'] == 0 && $responseArray['RESPMSG'] == 'Approved')
		{
			$values->responsestatus = 'Success';
			$message = JText::_('COM_REDSHOP_TRANSACTION_APPROVED');
		}
		else
		{
			$values->responsestatus = 'Fail';
			$message = $responseArray['RESPMSG'];
		}

		$values->message = $message;

		return $values;
	}
}
