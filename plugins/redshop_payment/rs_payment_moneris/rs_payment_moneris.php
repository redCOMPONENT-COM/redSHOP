<?php
/**
 * @package     RedSHOP
 * @subpackage  Plugin
 *
 * @copyright   Copyright (C) 2008 - 2015 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

defined('_JEXEC') or die;

/**
 * PlgRedshop_PaymentRs_Payment_Moneris installer class.
 *
 * @package  Redshopb.Plugin
 * @since    1.7.0
 */
class PlgRedshop_PaymentRs_Payment_Moneris extends JPlugin
{
	/**
	 * Load the language file on instantiation.
	 *
	 * @var    boolean
	 */
	protected $autoloadLanguage = true;

	/**
	 * [onPrePayment_rs_payment_moneris description]
	 *
	 * @param   [string]  $element  [plugin name]
	 * @param   [array]   $data     [data params]
	 *
	 * @return  [object]  $values
	 */
	public function onPrePayment_rs_payment_moneris($element, $data)
	{
		$config        = Redconfiguration::getInstance();
		$currencyClass = CurrencyHelper::getInstance();

		// Get user billing information
		$user = JFActory::getUser();

		if ($element != 'rs_payment_moneris')
		{
			return;
		}

		$monerisStoreId             = $this->params->get('moneris_store_id', '');
		$monerisTestStoreId         = $this->params->get('moneris_test_store_id', '');
		$monerisApiToken            = $this->params->get('moneris_api_token', '');
		$monerisTestApiToken        = $this->params->get('moneris_test_api_token', '');
		$monerisCheckCreditcardCode = $this->params->get('moneris_check_creditcard_code', '');
		$monerisCheckAvs            = $this->params->get('moneris_check_avs', '');
		$monerisTestStatus          = $this->params->get('moneris_test_status', '');

		$monerisApiHost = ($monerisTestStatus == 1)? "esqa.moneris.com": "www3.moneris.com";

		$session    = JFactory::getSession();
		$ccdata     = $session->get('ccdata');

		// Additional Customer Data
		$userId    = $data['billinginfo']->user_id;
		$remoteAddress = $_SERVER["REMOTE_ADDR"];

		// Email Settings
		$userEmail = $data['billinginfo']->user_email;

		// Get Credit card Information
		$orderPaymentName        = substr($ccdata['order_payment_name'], 0, 50);
		$orderPaymentNumber      = substr($ccdata['order_payment_number'], 0, 20);
		$creditCardCode          = substr($ccdata['credit_card_code'], 0, 4);
		$orderPaymentExpireYear  = substr($ccdata['order_payment_expire_year'], -2);
		$orderPaymentExpireYear .= substr($ccdata['order_payment_expire_month'], 0, 2);

		$crypt         = 7;
		$cvdIndicator = 0;
		$taxExempt    = false;

		include JPATH_SITE . '/plugins/redshop_payment/rs_payment_moneris/rs_payment_moneris/moneris.helper.php';

		if ($monerisTestStatus == 1)
		{
			$storeId  = $monerisTestStoreId;
			$apiToken = $monerisTestApiToken;
			$ptoken   = rand(1, 10);
			$ptoken   = number_format($ptoken, 0, "", "");

			$amount = (($ptoken % 2) == 0)? "10.10": "10.24";
		}
		else
		{
			$storeId     = $monerisStoreId;
			$apiToken    = $monerisApiToken;
			$totalAmount = $order_total = $data['order_total'];
			$amount      = $currencyClass->convert($totalAmount, '', 'USD');
		}

		$avsStreetNumber = substr($data['billinginfo']->address, 0, 60);
		$avsZipcode      = substr($data['billinginfo']->zipcode, 0, 20);
		$orderNumber     = $data['order_number'] . time();

		$txnArray = array(
			'type'       => 'purchase',
			'order_id'   => $orderNumber,
			'cust_id'    => $userId,
			'amount'     => sprintf('%01.2f', $amount),
			'pan'        => $orderPaymentNumber,
			'expdate'    => $orderPaymentExpireYear,
			'crypt_type' => $crypt
		);

		$cvdTemplate = array(
			'cvd_indicator' => $cvdIndicator,
			'cvd_value'     => $creditCardCode
		);

		$avsTemplate = array('avs_street_number' => $avsStreetNumber,'avs_street_name' => '','avs_zipcode' => $avsZipcode);

		$mpgAvsInfo = new mpgAvsInfo($avsTemplate);
		$mpgCvdInfo = new mpgCvdInfo($cvdTemplate);

		$mpgTxn     = new mpgTransaction($txnArray);

		if ($monerisCheckAvs == 1)
		{
			$mpgTxn->setAvsInfo($mpgAvsInfo);
		}

		if ($monerisCheckCreditcardCode == 1)
		{
			$mpgTxn->setCvdInfo($mpgCvdInfo);
		}

		$mpgRequest = new mpgRequest($mpgTxn);

		$mpgHttpPost = new mpgHttpsPost($storeId, $apiToken, $mpgRequest, $monerisApiHost);
		$mpgResponse = $mpgHttpPost->getMpgResponse();

		$mpgRCode = $mpgResponse->getResponseCode();
		$mpgMessage = $mpgResponse->getMessage();
		$mpgTxnNumber = $mpgResponse->getTxnNumber();
		$mpgAvsCode = $mpgResponse->getAvsResultCode();
		$mpgCvdCode = $mpgResponse->getCvdResultCode();

		$values = new stdClass;

		if (stristr($mpgRCode, "null") == false && $mpgRCode !== null)
		{
			if (intval($mpgRCode) < 50)
			{
				$message = "\nA Message from the processor: " . $mpgMessage . "\n";
				$values->responsestatus = 'Success';
				$values->transaction_id = $mpgTxnNumber;
			}
			else
			{
				if (intval($mpgRCode) >= 50)
				{
					$message = "\nA Message from the processor: " . $mpgMessage . "\n";
					$values->responsestatus = 'Fail';
					$values->transaction_id = $mpgTxnNumber;
				}
			}
		}
		else
		{
			$message = "\nA Message from the processor: " . $mpgMessage . "\n";
			$values->responsestatus = 'Fail';
			$values->transaction_id = $mpgTxnNumber;
		}

		$values->message = $message;

		return $values;
	}
}
