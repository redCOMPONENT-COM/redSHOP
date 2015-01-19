<?php
/**
 * @package     RedSHOP
 * @subpackage  Plugin
 *
 * @copyright   Copyright (C) 2008 - 2015 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

defined('_JEXEC') or die;

class plgRedshop_paymentrs_payment_eway extends JPlugin
{
	/**
	 * Plugin method with the same name as the event will be called automatically.
	 */
	public function onPrePayment_rs_payment_eway($element, $data)
	{
		$config = new Redconfiguration;

		// Get user billing information
		$user = JFActory::getUser();

		if ($element != 'rs_payment_eway')
		{
			return;
		}

		if (empty($plugin))
		{
			$plugin = $element;
		}

		$session            = JFactory::getSession();
		$ccdata             = $session->get('ccdata');

		// Collecting user Information ( Billing Information )

		$firstname_bill     = substr($data['billinginfo']->firstname, 0, 50);
		$lastname_bill      = substr($data['billinginfo']->lastname, 0, 50);
		$address_bill       = substr($data['billinginfo']->address, 0, 255);
		$city_bill          = substr($data['billinginfo']->city, 0, 40);
		$state_code_bill    = substr($data['billinginfo']->state_code, 0, 40);
		$zipcode_bill       = substr($data['billinginfo']->zipcode, 0, 6);
		$country_code_bill  = substr($data['billinginfo']->country_code, 0, 60);
		$phone_bill         = substr($data['billinginfo']->phone, 0, 25);
		$country_2code_bill = $config->getCountryCode2($country_code_bill);

		// Collecting user Information ( Shipping Information )
		$firstname_shipp    = substr($data['shippinginfo']->firstname, 0, 50);
		$lastname_shipp     = substr($data['shippinginfo']->lastname, 0, 50);
		$address_shipp      = substr($data['shippinginfo']->address, 0, 60);
		$city_shipp         = substr($data['shippinginfo']->city, 0, 40);
		$state_code_shipp   = substr($data['shippinginfo']->state_code, 0, 40);
		$zipcode_shipp      = substr($data['shippinginfo']->zipcode, 0, 20);
		$country_code_shipp = substr($data['shippinginfo']->country_code, 0, 60);

		// Additional Customer Data
		$user_id                    = $data['billinginfo']->user_id;
		$remote_add                 = $_SERVER["REMOTE_ADDR"];

		// Email Settings
		$user_email                 = $data['billinginfo']->user_email;

		// Get Credit card Information
		$order_payment_name         = substr($ccdata['order_payment_name'], 0, 50);
		$creditcard_code            = strtoupper($ccdata['creditcard_code']);
		$order_payment_number       = substr($ccdata['order_payment_number'], 0, 20);
		$credit_card_code           = substr($ccdata['credit_card_code'], 0, 4);
		$order_payment_expire_month = substr($ccdata['order_payment_expire_month'], 0, 2);
		$order_payment_expire_year  = substr($ccdata['order_payment_expire_year'], -2);
		$order_number               = substr($data['order_number'], 0, 16);
		$tax_exempt                 = false;

		include JPATH_SITE . '/plugins/redshop_payment/rs_payment_eway/rs_payment_eway/eway.integrator.php';

		$eway        = new EwayPayment($this->params->get("eway_customer_id"), $this->params->get("eway_method_type"), $this->params->get("eway_live_gateway"));

		$order_total = round($data['order_total'], 2) * 100;

		// Substitute 'FirstName', 'Lastname' etc for $_POST["FieldName"] where FieldName is the name of your INPUT field on your webpage
		$eway->setCustomerFirstname($firstname_bill);
		$eway->setCustomerLastname($lastname_bill);
		$eway->setCustomerEmail($user_email);
		$eway->setCustomerAddress($address_bill);
		$eway->setCustomerPostcode($zipcode_bill);
		$eway->setCustomerInvoiceDescription('Testing');
		$eway->setCustomerInvoiceRef($data['order_number']);
		$eway->setCardHoldersName($order_payment_name);
		$eway->setCardNumber($order_payment_number);
		$eway->setCardExpiryMonth($order_payment_expire_month);
		$eway->setCardExpiryYear($order_payment_expire_year);
		$eway->setTrxnNumber($data['order_number']);
		$eway->setTotalAmount($order_total);
		$eway->setCVN($credit_card_code);

		$values = $eway->doPayment($data['order_id']);

		return $values;
	}

	public function onCapture_Paymentrs_payment_eway($element, $data)
	{
		return;
	}
}
