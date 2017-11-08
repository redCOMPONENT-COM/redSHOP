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
 * PlgRedshop_PaymentRs_Payment_Eway class.
 *
 * @package  Redshopb.Plugin
 * @since    1.7.0
 */
class PlgRedshop_PaymentRs_Payment_Eway extends JPlugin
{
	/**
	 * [onPrePayment_rs_payment_eway]
	 *
	 * @param   [string]  $element  [plugin name]
	 * @param   [array]   $data     [data params]
	 *
	 * @return  [object]  $values
	 */
	public function onPrePayment_rs_payment_eway($element, $data)
	{
		$config = Redconfiguration::getInstance();

		// Get user billing information
		$user = JFActory::getUser();

		if ($element != 'rs_payment_eway')
		{
			return;
		}

		$session          = JFactory::getSession();
		$ccdata           = $session->get('ccdata');

		// Collecting user Information ( Billing Information )

		$firstNameBill    = substr($data['billinginfo']->firstname, 0, 50);
		$lastNameBill     = substr($data['billinginfo']->lastname, 0, 50);
		$addressBill      = substr($data['billinginfo']->address, 0, 255);

		$cityBill         = substr($data['billinginfo']->city, 0, 40);
		$stateCodeBill    = substr($data['billinginfo']->state_code, 0, 40);

		$zipcodeBill      = substr($data['billinginfo']->zipcode, 0, 6);
		$countryCodeBill  = substr($data['billinginfo']->country_code, 0, 60);
		$phoneBill        = substr($data['billinginfo']->phone, 0, 25);
		$country2CodeBill = $config->getCountryCode2($countryCodeBill);

		// Collecting user Information ( Shipping Information )
		$firstNameShipp   = substr($data['shippinginfo']->firstname, 0, 50);
		$lastNameShipp    = substr($data['shippinginfo']->lastname, 0, 50);
		$addressShipp     = substr($data['shippinginfo']->address, 0, 60);
		$cityShipp        = substr($data['shippinginfo']->city, 0, 40);
		$stateCodeShipp   = substr($data['shippinginfo']->state_code, 0, 40);
		$zipcodeShipp     = substr($data['shippinginfo']->zipcode, 0, 20);
		$countryCodeShipp = substr($data['shippinginfo']->country_code, 0, 60);

		// Additional Customer Data
		$userId                    = $data['billinginfo']->user_id;
		$remoteAdd                 = $_SERVER["REMOTE_ADDR"];

		// Email Settings
		$userEmail                 = $data['billinginfo']->user_email;

		// Get Credit card Information
		$orderPaymentName        = substr($ccdata['order_payment_name'], 0, 50);
		$creditCardCode          = strtoupper($ccdata['creditcard_code']);
		$orderPaymentNumber      = substr($ccdata['order_payment_number'], 0, 20);
		$creditCardCode2         = substr($ccdata['credit_card_code'], 0, 4);
		$orderPaymentExpireMonth = substr($ccdata['order_payment_expire_month'], 0, 2);
		$orderPaymentExpireYear  = substr($ccdata['order_payment_expire_year'], -2);
		$orderNumber             = substr($data['order_number'], 0, 16);
		$taxExempt               = false;

		include JPATH_SITE . '/plugins/redshop_payment/' . $element . '/' . $element . '/eway.integrator.php';

		$eway = new EwayPayment($this->params->get("eway_customer_id"), $this->params->get("eway_method_type"), $this->params->get("eway_live_gateway"));

		$orderTotal = round($data['order_total'], 2) * 100;

		// Substitute 'FirstName', 'Lastname' etc for $_POST["FieldName"] where FieldName is the name of your INPUT field on your webpage
		$eway->setCustomerFirstname($firstNameBill);
		$eway->setCustomerLastname($lastNameBill);
		$eway->setCustomerEmail($userEmail);
		$eway->setCustomerAddress($addressBill);
		$eway->setCustomerPostcode($zipcodeBill);
		$eway->setCustomerInvoiceDescription('Testing');
		$eway->setCustomerInvoiceRef($data['orderNumber']);
		$eway->setCardHoldersName($orderPaymentName);
		$eway->setCardNumber($orderPaymentNumber);
		$eway->setCardExpiryMonth($orderPaymentExpireMonth);
		$eway->setCardExpiryYear($orderPaymentExpireYear);
		$eway->setTrxnNumber($data['order_number']);
		$eway->setTotalAmount($orderTotal);
		$eway->setCVN($creditCardCode2);

		$values = $eway->doPayment($data['order_id']);

		return $values;
	}

	/**
	 * [onCapture_Paymentrs_payment_eway]
	 *
	 * @param   [string]  $element  [plugin name]
	 * @param   [array]   $data     [data params]
	 *
	 * @return  [void]
	 */
	public function onCapture_Paymentrs_payment_eway($element, $data)
	{
		// @TODO: Unknow what this event use for? Need complete code

		return;
	}
}
