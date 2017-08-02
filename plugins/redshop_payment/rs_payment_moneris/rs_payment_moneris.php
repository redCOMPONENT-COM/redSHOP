<?php
/**
 * @package     RedSHOP
 * @subpackage  Plugin
 *
 * @copyright   Copyright (C) 2008 - 2015 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

defined('_JEXEC') or die;

class plgRedshop_paymentrs_payment_moneris extends JPlugin
{
	/**
	 * Constructor
	 * Plugin method with the same name as the event will be called automatically.
	 *
	 * @param   string  $element    Element
	 * @param   array   $data       Data
	 *
	 * @return  false|stdClass
	 *
	 * @since   2.0.6
	 */
	public function onPrePayment_rs_payment_moneris($element, $data)
	{

		// Get user billing information
		$user = JFActory::getUser();

		if ($element != 'rs_payment_moneris')
		{

			return false;
		}

		if (empty($plugin))
		{
			$plugin = $element;
		}

		$moneris_store_id              = $this->params->get('moneris_store_id', '');
		$moneris_test_store_id         = $this->params->get('moneris_test_store_id', '');
		$moneris_api_token             = $this->params->get('moneris_api_token', '');
		$moneris_test_api_token        = $this->params->get('moneris_test_api_token', '');
		$moneris_check_creditcard_code = $this->params->get('moneris_check_creditcard_code', '');
		$moneris_check_avs             = $this->params->get('moneris_check_avs', '');
		$moneris_test_status           = $this->params->get('moneris_test_status', '');

		if ($moneris_test_status == 1)
		{
			$moneris_api_host = "esqa.moneris.com";
		}
		else
		{
			$moneris_api_host = "www3.moneris.com";
		}

		$session = JFactory::getSession();
		$ccdata  = $session->get('ccdata');

		// Additional Customer Data
		$user_id    = $data['billinginfo']->user_id;
		$remote_add = $_SERVER["REMOTE_ADDR"];

		// Email Settings
		$user_email = $data['billinginfo']->user_email;

		// Get Credit card Information
		$order_payment_name        = substr($ccdata['order_payment_name'], 0, 50);
		$creditcard_code           = ucfirst(strtolower($ccdata['creditcard_code']));
		$order_payment_number      = substr($ccdata['order_payment_number'], 0, 20);
		$credit_card_code          = substr($ccdata['credit_card_code'], 0, 4);
		$order_payment_expire_year = substr($ccdata['order_payment_expire_year'], -2);
		$order_payment_expire_year .= substr($ccdata['order_payment_expire_month'], 0, 2);

		$crypt         = 7;
		$cvd_indicator = 0;
		$tax_exempt    = false;

		include JPATH_SITE . '/plugins/redshop_payment/rs_payment_moneris/rs_payment_moneris/moneris.helper.php';

		if ($moneris_test_status == 1)
		{
			$storeid  = $moneris_test_store_id;
			$apitoken = $moneris_test_api_token;
			$ptoken   = rand(1, 10);
			$ptoken   = number_format($ptoken, 0, "", "");

			if (($ptoken % 2) == 0)
			{
				$amount = "10.10";
			}
			else
			{
				$amount = "10.24";
			}
		}
		else
		{
			$storeid    = $moneris_store_id;
			$apitoken   = $moneris_api_token;
			$tot_amount = $order_total = $data['order_total'];
			$amount     = RedshopHelperCurrency::convert($tot_amount, '', 'USD');
		}

		$avs_street_number = substr($data['billinginfo']->address, 0, 60);
		$avs_zipcode       = substr($data['billinginfo']->zipcode, 0, 20);
		$order_number      = $data['order_number'] . time();

		$txnArray = array(
			'type'       => 'purchase',
			'order_id'   => $order_number,
			'cust_id'    => $user_id,
			'amount'     => sprintf('%01.2f', $amount),
			'pan'        => $order_payment_number,
			'expdate'    => $order_payment_expire_year,
			'crypt_type' => $crypt
		);

		$cvdTemplate = array(
			'cvd_indicator' => $cvd_indicator,
			'cvd_value'     => $credit_card_code
		);

		$avsTemplate = array('avs_street_number' => $avs_street_number, 'avs_street_name' => '', 'avs_zipcode' => $avs_zipcode);

		$mpgAvsInfo = new mpgAvsInfo($avsTemplate);
		$mpgCvdInfo = new mpgCvdInfo($cvdTemplate);

		$mpgTxn = new mpgTransaction($txnArray);

		if ($moneris_check_avs == 1)
		{
			$mpgTxn->setAvsInfo($mpgAvsInfo);
		}

		if ($moneris_check_creditcard_code == 1)
		{
			$mpgTxn->setCvdInfo($mpgCvdInfo);
		}

		$mpgRequest = new mpgRequest($mpgTxn);
		$mpgGlobals = new mpgGlobals;
		$mpgHttpPost = new mpgHttpsPost($storeid, $apitoken, $mpgRequest, $moneris_api_host);
		$mpgResponse = $mpgHttpPost->getMpgResponse();

		if ($moneris_test_status == 1)
		{
			echo "<pre>";
			echo "Raw Data<br /><br />";
			echo "Globals: <br />";
			var_export($mpgGlobals->getGlobals());
			echo "<br />";
			echo "Request: <br />";
			var_export($mpgHttpPost);
			echo "<br />";
			echo "Response: <br />";
			var_export($mpgResponse);
			echo "</pre>";
		}

		$mpgRCode     = $mpgResponse->getResponseCode();
		$mpgMessage   = $mpgResponse->getMessage();
		$mpgTxnNumber = $mpgResponse->getTxnNumber();
		$mpgAvsCode   = $mpgResponse->getAvsResultCode();
		$mpgCvdCode   = $mpgResponse->getCvdResultCode();

		$values = new stdClass;

		if (stristr($mpgRCode, "null") == false && $mpgRCode !== null)
		{
			if (intval($mpgRCode) < 50)
			{
				$message                = "\nA Message from the processor: " . $mpgMessage . "\n";
				$values->responsestatus = 'Success';
				$values->transaction_id = $mpgTxnNumber;
			}
			else
			{
				if (intval($mpgRCode) >= 50)
				{
					$message                = "\nA Message from the processor: " . $mpgMessage . "\n";
					$values->responsestatus = 'Fail';
					$values->transaction_id = $mpgTxnNumber;
				}
			}
		}
		else
		{
			$message                = "\nA Message from the processor: " . $mpgMessage . "\n";
			$values->responsestatus = 'Fail';
			$values->transaction_id = $mpgTxnNumber;
		}

		$values->message = $message;

		return $values;
	}
}
