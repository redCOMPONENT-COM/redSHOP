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

class plgRedshop_paymentrs_payment_beanstream extends JPlugin
{
	/**
	 * Plugin method with the same name as the event will be called automatically.
	 */
	public function onPrePayment_rs_payment_beanstream($element, $data)
	{
		if ($element != 'rs_payment_beanstream')
		{
			return;
		}

		if (empty($plugin))
		{
			$plugin = $element;
		}

		$user    = JFActory::getUser();
		$session = JFactory::getSession();
		$ccdata  = $session->get('ccdata');
		$cart    = $session->get('cart');
		$config  = Redconfiguration::getInstance();

		// For total amount
		$cal_no = 2;

		if (Redshop::getConfig()->get('PRICE_DECIMAL') != '')
		{
			$cal_no = Redshop::getConfig()->get('PRICE_DECIMAL');
		}

		$order_total               = round($data['order_total'], $cal_no);
		$order_payment_expire_year = substr($ccdata['order_payment_expire_year'], -2);
		$order_payment_name        = substr($ccdata['order_payment_name'], 0, 50);
		$CountryCode               = $config->getCountryCode2($data['billinginfo']->country_code);

		// Get params from plugin
		$merchant_id       = $this->params->get("merchant_id");
		$api_username      = $this->params->get("api_username");
		$api_password      = $this->params->get("api_password");
		$view_table_format = $this->params->get("view_table_format");

		// Authenticate vars to send
		$formdata = array(
			'requestType'     => 'BACKEND',
			'merchant_id'     => $merchant_id,
			'username'        => $api_username,
			'password'        => $api_password,
			'trnCardOwner'    => $order_payment_name,
			'trnCardNumber'   => $ccdata['order_payment_number'],
			'trnExpMonth'     => $ccdata['order_payment_expire_month'],
			'trnExpYear'      => $order_payment_expire_year,
			'trnCardCvd'      => $ccdata['credit_card_code'],
			'trnOrderNumber'  => $data['order_number'],
			'trnAmount'       => $order_total,
			'ordEmailAddress' => $data['billinginfo']->user_email,
			'ordName'         => $data['billinginfo']->firstname . " " . $data['billinginfo']->lastname,
			'ordPhoneNumber'  => $data['billinginfo']->phone,
			'ordAddress1'     => $data['billinginfo']->address,
			'ordAddress2'     => "",
			'ordCity'         => $data['billinginfo']->city,
			'ordProvince'     => $data['billinginfo']->state_code,
			'ordPostalCode'   => $data['billinginfo']->zipcode,
			'ordCountry'      => $CountryCode,
		);

		// Build the post string
		$poststring = '';

		foreach ($formdata AS $key => $val)
		{
			$poststring .= urlencode($key) . "=" . $val . "&";
		}

		// Strip off trailing ampersand
		$poststring = substr($poststring, 0, -1);

		// Initialize curl
		$ch = curl_init();

		// Get curl to POST
		curl_setopt($ch, CURLOPT_POST, 1);
		curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);

		// Instruct curl to suppress the output from Beanstream, and to directly
		// return the transfer instead. (Output will be stored in $txResult.)
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);

		// This is the location of the Beanstream payment gateway
		curl_setopt($ch, CURLOPT_URL, "https://www.beanstream.com/scripts/process_transaction.asp");

		// These are the transaction parameters that we will POST
		curl_setopt($ch, CURLOPT_POSTFIELDS, $poststring);

		// Now POST the transaction. $txResult will contain Beanstream's response
		$txResult = curl_exec($ch);

		curl_close($ch);

		// Built array
		$arrResult = $this->explode_assoc("=", "&", $txResult);

		if ($arrResult['trnApproved'] == '1')
		{
			$values->responsestatus = 'Success';
			$message = $arrResult['messageText'];
		}
		else
		{
			// Catch Transaction ID
			$message = $arrResult['messageText'];
			$values->responsestatus = 'Fail';
		}

		$values->transaction_id = $arrResult['trnId'];
		$values->message = $message;

		return $values;
	}

	public function explode_assoc($glue1, $glue2, $array)
	{
		$array2 = explode($glue2, $array);

		foreach ($array2 as $val)
		{
			$pos = strpos($val, $glue1);
			$key = substr($val, 0, $pos);
			$array3[$key] = substr($val, $pos + 1, strlen($val));
		}

		return $array3;
	}
}
