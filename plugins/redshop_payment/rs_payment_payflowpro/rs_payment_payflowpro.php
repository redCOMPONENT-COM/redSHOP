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

class plgRedshop_Paymentrs_Payment_Payflowpro extends JPlugin
{
	/**
	 * Plugin method with the same name as the event will be called automatically.
	 */
	public function onPrePayment_rs_payment_payflowpro($element, $data)
	{
		if ($element != 'rs_payment_payflowpro')
		{
			return;
		}

		if (empty($plugin))
		{
			$plugin = $element;
		}

		$app     = JFactory::getApplication();
		$user    = JFactory::getUser();
		$values  = new stdClass;
		$session = JFactory::getSession();
		$ccdata  = $session->get('ccdata');

		// Get Payment Params
		$partner           = $this->params->get("partner");
		$merchant_id       = $this->params->get("merchant_id");
		$merchant_password = $this->params->get("merchant_password");
		$merchant_user     = $this->params->get("merchant_user");
		$paymentType       = $this->params->get("sales_auth_only");
		$is_test           = $this->params->get("is_test");

		// Get Customer Data
		$firstName  = urlencode($data['billinginfo']->firstname);
		$lastName   = urlencode($data['billinginfo']->lastname);
		$address    = urlencode($data['billinginfo']->address);
		$city       = urlencode($data['billinginfo']->city);
		$state      = urlencode($data['billinginfo']->state_code);
		$zip        = urlencode($data['billinginfo']->zipcode);
		$country    = urlencode($data['billinginfo']->country_2_code);
		$user_email = $data['billinginfo']->user_email;

		$shfirstName = urlencode($data['shippinginfo']->firstname);
		$shlastName  = urlencode($data['shippinginfo']->lastname);
		$shaddress   = urlencode($data['shippinginfo']->address);
		$shcity      = urlencode($data['shippinginfo']->city);
		$shstate     = urlencode($data['shippinginfo']->state_code);
		$shzip       = urlencode($data['shippinginfo']->zipcode);
		$shcountry   = urlencode($data['shippinginfo']->country_2_code);

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

		/*
		As per the email error no need to remove shipping - tmp fix
		$order_total = $data['order_total'] - $data['order_shipping'] - $data['order_tax'];
		$order_total = $data['order_total'] - $data['order_tax'];
		*/
		$order_total = $data['order_total'];
		$amount      = RedshopHelperCurrency::convert($order_total, '', $currencyID);
		$amount      = urlencode(number_format($amount, 2));

		$shipping_amount = $data['order_shipping'];
		$shipping_amount = RedshopHelperCurrency::convert($shipping_amount, '', $currencyID);
		$shipping_amount = urlencode(number_format($shipping_amount, 2));

		$tax_amount = $data['order_tax'];
		$tax_amount = RedshopHelperCurrency::convert($tax_amount, '', $currencyID);
		$tax_amount = urlencode(number_format($tax_amount, 2));

		if ($is_test)
		{
			$api_url = "https://pilot-payflowpro.paypal.com";
		}
		else
		{
			$api_url = "https://payflowpro.paypal.com";
		}

		$params = array(
			'USER'      => $merchant_user,
			'VENDOR'    => $merchant_id,
			'PARTNER'   => $partner,
			'PWD'       => $merchant_password,
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
			'EMAIL'     => $user_email,
			'ACCT'      => $creditCardNumber,
			'EXPDATE'   => $strExpiryDate,
			'CVV2'      => $strCV2
		);

		if ($tax_amount > 0)
		{
			$params['TAXAMT'] = $tax_amount;
		}

		if ($shipping_amount > 0)
		{
			$params['FREIGHTAMT'] = $shipping_amount;
		}

		if (count($data['shippinginfo']) > 0)
		{
			$ship_params = array(
				'SHIPTOFIRSTNAME' => $shfirstName,
				'SHIPTOLASTNAME'  => $shlastName,
				'SHIPTOCOUNTRY'   => $shcountry,
				'SHIPTOCITY'      => $shcity,
				'SHIPTOSTREET'    => $shaddress,
				'SHIPTOZIP'       => $shzip
			);
		}

		$params = array_merge($params, $ship_params);

		$post_string = '';

		foreach ($params as $key => $value)
		{
			$post_string .= $key . '[' . strlen(urlencode(utf8_encode(trim($value)))) . ']=' . urlencode(utf8_encode(trim($value))) . '&';
		}

		$post_string    = substr($post_string, 0, -1);
		$response       = $this->sendTransactionToGateway($api_url, $post_string, array('X-VPS-REQUEST-ID: ' . md5($creditCardNumber . rand())));
		$response_array = array();
		parse_str($response, $response_array);

		if ($response_array['RESULT'] == 0 && $response_array['RESPMSG'] == 'Approved')
		{
			$values->responsestatus = 'Success';
			$message                = JText::_('COM_REDSHOP_ORDER_PLACED');
		}
		else
		{
			$values->responsestatus = 'Fail';
			$message                = $response_array['RESPMSG'];
		}

		$values->transaction_id = $response_array['PNREF'];
		$values->message        = $message;

		return $values;
	}

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

	/*
	 *  Plugin onNotifyPayment method with the same name as the event will be called automatically.
	 */
	public function onNotifyPaymentrs_payment_payflowpro($element, $request)
	{
		if ($element != 'rs_payment_payflowpro')
		{
			return false;
		}

		return;
	}

	public function onCapture_Paymentrs_payment_payflowpro($element, $data)
	{
		// Get Payment Params
		$partner           = $this->params->get("partner");
		$merchant_id       = $this->params->get("merchant_id");
		$merchant_password = $this->params->get("merchant_password");
		$merchant_user     = $this->params->get("merchant_user");
		$paymentType       = $this->params->get("sales_auth_only");
		$is_test           = $this->params->get("is_test");
		$values            = new stdClass;

		$order_id = $data['order_id'];
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
		$order_amount  = RedshopHelperCurrency::convert($data['order_amount'], '', $currencyID);
		$order_amount  = urlencode(number_format($order_amount, 2));

		if ($is_test)
		{
			$api_url = "https://pilot-payflowpro.paypal.com";
		}
		else
		{
			$api_url = "https://payflowpro.paypal.com";
		}

		$params      = array(
			'USER'    => $merchant_user,
			'VENDOR'  => $merchant_id,
			'PARTNER' => $partner,
			'PWD'     => $merchant_password,
			'TENDER'  => 'C',
			'TRXTYPE' => 'D',
			'AMT'     => $order_amount,
			'ORIGID'  => $tid
		);
		$post_string = '';

		foreach ($params as $key => $value)
		{
			$post_string .= $key . '[' . strlen(urlencode(utf8_encode(trim($value)))) . ']=' . urlencode(utf8_encode(trim($value))) . '&';
		}

		$post_string = substr($post_string, 0, -1);
		$response    = $this->sendTransactionToGateway($api_url, $post_string, array('X-VPS-REQUEST-ID: ' . md5($order_id . rand())));

		$response_array = array();
		parse_str($response, $response_array);

		if ($response_array['RESULT'] == 0 && $response_array['RESPMSG'] == 'Approved')
		{
			$values->responsestatus = 'Success';
			$message                = JText::_('COM_REDSHOP_TRANSACTION_APPROVED');
		}
		else
		{
			$values->responsestatus = 'Fail';
			$message                = $response_array['RESPMSG'];
		}

		$values->message = $message;

		return $values;
	}

	public function onStatus_Paymentrs_payment_payflowpro($element, $data)
	{
		// Get Payment Params
		$partner           = $this->params->get("partner");
		$merchant_id       = $this->params->get("merchant_id");
		$merchant_password = $this->params->get("merchant_password");
		$merchant_user     = $this->params->get("merchant_user");
		$paymentType       = $this->params->get("sales_auth_only");
		$is_test           = $this->params->get("is_test");
		$values            = new stdClass;

		$order_id = $data['order_id'];
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

		$order_amount = RedshopHelperCurrency::convert($data['order_amount'], '', $currencyID);
		$order_amount = urlencode(number_format($order_amount, 2));

		if ($is_test)
		{
			$api_url = "https://pilot-payflowpro.paypal.com";
		}
		else
		{
			$api_url = "https://payflowpro.paypal.com";
		}

		$params      = array(
			'USER'    => $merchant_user,
			'VENDOR'  => $merchant_id,
			'PARTNER' => $partner,
			'PWD'     => $merchant_password,
			'TENDER'  => 'C',
			'TRXTYPE' => 'C',
			'AMT'     => $order_amount,
			'ORIGID'  => $tid
		);
		$post_string = '';

		foreach ($params as $key => $value)
		{
			$post_string .= $key . '[' . strlen(urlencode(utf8_encode(trim($value)))) . ']=' . urlencode(utf8_encode(trim($value))) . '&';
		}

		$post_string = substr($post_string, 0, -1);
		$response    = $this->sendTransactionToGateway($api_url, $post_string, array('X-VPS-REQUEST-ID: ' . md5($order_id . rand())));

		$response_array = array();
		parse_str($response, $response_array);

		if ($response_array['RESULT'] == 0 && $response_array['RESPMSG'] == 'Approved')
		{
			$values->responsestatus = 'Success';
			$message                = JText::_('COM_REDSHOP_TRANSACTION_APPROVED');
		}
		else
		{
			$values->responsestatus = 'Fail';
			$message                = $response_array['RESPMSG'];
		}

		$values->message = $message;

		return $values;
	}
}
