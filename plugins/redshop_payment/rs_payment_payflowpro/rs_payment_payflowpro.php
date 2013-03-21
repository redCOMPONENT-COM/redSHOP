<?php
/**
 * @copyright Copyright (C) 2010 redCOMPONENT.com. All rights reserved.
 * @license   GNU/GPL, see license.txt or http://www.gnu.org/copyleft/gpl.html
 *            Developed by email@recomponent.com - redCOMPONENT.com
 *
 * redSHOP can be downloaded from www.redcomponent.com
 * redSHOP is free software; you can redistribute it and/or
 * modify it under the terms of the GNU General Public License 2
 * as published by the Free Software Foundation.
 *
 * You should have received a copy of the GNU General Public License
 * along with redSHOP; if not, write to the Free Software
 * Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston, MA  02110-1301, USA.
 */

/** ensure this file is being included by a parent file */
defined('_JEXEC') or die('Restricted access');
jimport('joomla.plugin.plugin');
//$mainframe =& JFactory::getApplication();
//$mainframe->registerEvent( 'onPrePayment', 'plgRedshoprs_payment_bbs' );
require_once (JPATH_SITE . DS . 'administrator' . DS . 'components' . DS . 'com_redshop' . DS . 'helpers' . DS . 'order.php');
class plgRedshop_paymentrs_payment_payflowpro extends JPlugin
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
	function plgRedshop_paymentrs_payment_payflowpro(&$subject)
	{
		// load plugin parameters
		parent::__construct($subject);
		$this->_table_prefix = '#__redshop_';
		$this->_plugin = JPluginHelper::getPlugin('redshop_payment', 'rs_payment_payflowpro');
		$this->_params = new JRegistry($this->_plugin->params);
	}

	/**
	 * Plugin method with the same name as the event will be called automatically.
	 */
	function onPrePayment_rs_payment_payflowpro($element, $data)
	{
		if ($element != 'rs_payment_payflowpro')
		{
			return;
		}

		if (empty($plugin))
		{
			$plugin = $element;
		}

		$mainframe =& JFactory::getApplication();
		$user = JFactory::getUser();

		$session =& JFactory::getSession();
		$ccdata = $session->get('ccdata');

		// Get Payment Params
		$partner = $this->_params->get("partner");
		$merchant_id = $this->_params->get("merchant_id");
		$merchant_password = $this->_params->get("merchant_password");
		$merchant_user = $this->_params->get("merchant_user");
		$paymentType = $this->_params->get("sales_auth_only");
		$is_test = $this->_params->get("is_test");
		//Get Customer Data
		$firstName = urlencode($data['billinginfo']->firstname);
		$lastName = urlencode($data['billinginfo']->lastname);
		$address = urlencode($data['billinginfo']->address);
		$city = urlencode($data['billinginfo']->city);
		$state = urlencode($data['billinginfo']->state_code);
		$zip = urlencode($data['billinginfo']->zipcode);
		$country = urlencode($data['billinginfo']->country_2_code);
		$user_email = $data['billinginfo']->user_email;

		$shfirstName = urlencode($data['shippinginfo']->firstname);
		$shlastName = urlencode($data['shippinginfo']->lastname);
		$shaddress = urlencode($data['shippinginfo']->address);
		$shcity = urlencode($data['shippinginfo']->city);
		$shstate = urlencode($data['shippinginfo']->state_code);
		$shzip = urlencode($data['shippinginfo']->zipcode);
		$shcountry = urlencode($data['shippinginfo']->country_2_code);
		// Get CreditCard Data
		$strCardHolder = substr($ccdata['order_payment_name'], 0, 100);
		$creditCardType = urlencode($ccdata['creditcard_code']);
		$creditCardNumber = urlencode($ccdata['order_payment_number']);
		$strExpiryDate = substr($ccdata['order_payment_expire_month'], 0, 2) . substr($ccdata['order_payment_expire_year'], -2);
		$strCV2 = substr($ccdata['credit_card_code'], 0, 4);

		if ($this->_params->get("currency") != "")
		{
			$currencyID = $this->_params->get("currency");
		}
		else if (CURRENCY_CODE != "")
		{
			$currencyID = urlencode(CURRENCY_CODE);
		}
		else
		{
			$currencyID = "USD";
		}

		$currencyClass = new convertPrice ();
		// as per the email error no need to remove shipping - tmp fix
		//$order_total = $data['order_total'] - $data['order_shipping'] - $data['order_tax'];
		//$order_total = $data['order_total'] - $data['order_tax'];
		$order_total = $data['order_total'];
		$amount = $currencyClass->convert($order_total, '', $currencyID);
		$amount = urlencode(number_format($amount, 2));

		$shipping_amount = $data['order_shipping'];
		$shipping_amount = $currencyClass->convert($shipping_amount, '', $currencyID);
		$shipping_amount = urlencode(number_format($shipping_amount, 2));

		$tax_amount = $data['order_tax'];
		$tax_amount = $currencyClass->convert($tax_amount, '', $currencyID);
		$tax_amount = urlencode(number_format($tax_amount, 2));

		if ($is_test)
		{
			$api_url = "https://pilot-payflowpro.paypal.com";
		}
		else
		{
			$api_url = "https://payflowpro.paypal.com";
		}

		$params = array('USER'      => $merchant_user,
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
		                'CVV2'      => $strCV2);

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
			$ship_params = array('SHIPTOFIRSTNAME' => $shfirstName,
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

		$post_string = substr($post_string, 0, -1);
		$response = $this->sendTransactionToGateway($api_url, $post_string, array('X-VPS-REQUEST-ID: ' . md5($creditCardNumber . rand())));
		$response_array = array();
		parse_str($response, $response_array);

		if ($response_array['RESULT'] == 0 && $response_array['RESPMSG'] == 'Approved')
		{
			$values->responsestatus = 'Success';
			$message = JText::_('COM_REDSHOP_ORDER_PLACED');
		}
		else
		{
			$values->responsestatus = 'Fail';
			$message = $response_array['RESPMSG'];
		}

		$values->transaction_id = $response_array['PNREF'];
		$values->message = $message;

		return $values;

	}

	function sendTransactionToGateway($url, $parameters, $headers = null)
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
	function onNotifyPaymentrs_payment_payflowpro($element, $request)
	{

		if ($element != 'rs_payment_payflowpro')
		{
			break;
		}

		return;
	}


	function onCapture_Paymentrs_payment_payflowpro($element, $data)
	{

		// Get Payment Params
		$partner = $this->_params->get("partner");
		$merchant_id = $this->_params->get("merchant_id");
		$merchant_password = $this->_params->get("merchant_password");
		$merchant_user = $this->_params->get("merchant_user");
		$paymentType = $this->_params->get("sales_auth_only");
		$is_test = $this->_params->get("is_test");

		$order_id = $data['order_id'];
		$tid = $data['order_transactionid'];

		if ($this->_params->get("currency") != "")
		{
			$currencyID = $this->_params->get("currency");
		}
		else if (CURRENCY_CODE != "")
		{
			$currencyID = urlencode(CURRENCY_CODE);
		}
		else
		{
			$currencyID = "USD";
		}

		$currencyClass = new convertPrice ();
		$order_amount = $currencyClass->convert($data['order_amount'], '', $currencyID);
		$order_amount = urlencode(number_format($order_amount, 2));

		if ($is_test)
		{
			$api_url = "https://pilot-payflowpro.paypal.com";
		}
		else
		{
			$api_url = "https://payflowpro.paypal.com";
		}

		$params = array('USER'    => $merchant_user,
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
		$response = $this->sendTransactionToGateway($api_url, $post_string, array('X-VPS-REQUEST-ID: ' . md5($order_id . rand())));

		$response_array = array();
		parse_str($response, $response_array);

		if ($response_array['RESULT'] == 0 && $response_array['RESPMSG'] == 'Approved')
		{
			$values->responsestatus = 'Success';
			$message = JText::_('COM_REDSHOP_TRANSACTION_APPROVED');
		}
		else
		{
			$values->responsestatus = 'Fail';
			$message = $response_array['RESPMSG'];
		}


		$values->message = $message;

		return $values;

	}

	function onStatus_Paymentrs_payment_payflowpro($element, $data)
	{

		// Get Payment Params
		$partner = $this->_params->get("partner");
		$merchant_id = $this->_params->get("merchant_id");
		$merchant_password = $this->_params->get("merchant_password");
		$merchant_user = $this->_params->get("merchant_user");
		$paymentType = $this->_params->get("sales_auth_only");
		$is_test = $this->_params->get("is_test");


		$order_id = $data['order_id'];
		$tid = $data['order_transactionid'];

		if ($this->_params->get("currency") != "")
		{
			$currencyID = $this->_params->get("currency");
		}
		else if (CURRENCY_CODE != "")
		{
			$currencyID = urlencode(CURRENCY_CODE);
		}
		else
		{
			$currencyID = "USD";
		}

		$currencyClass = new convertPrice ();
		$order_amount = $currencyClass->convert($data['order_amount'], '', $currencyID);
		$order_amount = urlencode(number_format($order_amount, 2));

		if ($is_test)
		{
			$api_url = "https://pilot-payflowpro.paypal.com";
		}
		else
		{
			$api_url = "https://payflowpro.paypal.com";
		}

		$params = array('USER'    => $merchant_user,
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
		$response = $this->sendTransactionToGateway($api_url, $post_string, array('X-VPS-REQUEST-ID: ' . md5($order_id . rand())));

		$response_array = array();
		parse_str($response, $response_array);

		if ($response_array['RESULT'] == 0 && $response_array['RESPMSG'] == 'Approved')
		{
			$values->responsestatus = 'Success';
			$message = JText::_('COM_REDSHOP_TRANSACTION_APPROVED');
		}
		else
		{
			$values->responsestatus = 'Fail';
			$message = $response_array['RESPMSG'];
		}


		$values->message = $message;

		return $values;
	}

}
