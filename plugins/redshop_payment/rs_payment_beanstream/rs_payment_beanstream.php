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
 * PlgRedshop_PaymentRs_Payment_Beanstream class.
 *
 * @package  Redshopb.Plugin
 * @since    1.7.0
 */
class PlgRedshop_PaymentRs_Payment_Beanstream extends JPlugin
{
	/**
	 * [onPrePayment_rs_payment_beanstream]
	 *
	 * @param   [string]  $element  [plugin name]
	 * @param   [array]   $data     [data params]
	 *
	 * @return  [array]   $values
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
		$calNo = 2;

		if (Redshop::getConfig()->get('PRICE_DECIMAL') != '')
		{
			$calNo = Redshop::getConfig()->get('PRICE_DECIMAL');
		}

		$orderTotal             = round($data['order_total'], $calNo);
		$orderPaymentExpireYear = substr($ccdata['order_payment_expire_year'], -2);
		$orderPaymentName       = substr($ccdata['order_payment_name'], 0, 50);
		$CountryCode            = $config->getCountryCode2($data['billinginfo']->country_code);

		// Get params from plugin
		$merchantId      = $this->params->get("merchant_id");
		$apiUserName     = $this->params->get("api_username");
		$apiPassword     = $this->params->get("api_password");
		$viewTableFormat = $this->params->get("view_table_format");

		// Authenticate vars to send
		$formData = array(
			'requestType'     => 'BACKEND',
			'merchant_id'     => $merchantId,
			'username'        => $apiUserName,
			'password'        => $apiPassword,
			'trnCardOwner'    => $orderPaymentName,
			'trnCardNumber'   => $ccdata['order_payment_number'],
			'trnExpMonth'     => $ccdata['order_payment_expire_month'],
			'trnExpYear'      => $orderPaymentExpireYear,
			'trnCardCvd'      => $ccdata['credit_card_code'],
			'trnOrderNumber'  => $data['order_number'],
			'trnAmount'       => $orderTotal,
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
		$postString = '';

		foreach ($formData AS $key => $val)
		{
			$postString .= urlencode($key) . "=" . $val . "&";
		}

		// Strip off trailing ampersand
		$postString = substr($postString, 0, -1);

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
		curl_setopt($ch, CURLOPT_POSTFIELDS, $postString);

		// Now POST the transaction. $txResult will contain Beanstream's response
		$txResult = curl_exec($ch);

		curl_close($ch);

		// Built array
		$arrResult = $this->explodeAssoc("=", "&", $txResult);

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

	/**
	 * [explode_assoc]
	 *
	 * @param   [string]  $glue1  [first string]
	 * @param   [string]  $glue2  [second string]
	 * @param   [array]   $array  [array to explode]
	 *
	 * @return  [type]          [description]
	 */
	public function explodeAssoc($glue1, $glue2, $array)
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
