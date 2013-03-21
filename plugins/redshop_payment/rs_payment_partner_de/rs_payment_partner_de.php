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
require_once (JPATH_SITE . DS . 'administrator' . DS . 'components' . DS . 'com_redshop' . DS . 'helpers' . DS . 'order.php');
class plgRedshop_paymentrs_payment_partner_de extends JPlugin
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
	function plgRedshop_paymentrs_payment_partner_de(&$subject)
	{
		// load plugin parameters
		parent::__construct($subject);
		$this->_table_prefix = '#__redshop_';
		$this->_plugin = JPluginHelper::getPlugin('redshop_payment', 'rs_payment_partner_de');
		$this->_params = new JRegistry($this->_plugin->params);

	}

	/**
	 * Plugin method with the same name as the event will be called automatically.
	 */
	function onPrePayment_rs_payment_partner_de($element, $data)
	{
		if ($element != 'rs_payment_partner_de')
		{
			return;
		}

		if (empty($plugin))
		{
			$plugin = $element;
		}

		$session =& JFactory::getSession();
		$ccdata = $session->get('ccdata');
		// get merchant Information
		$merchant_server = $this->_params->get("merchant_server");
		$merchant_path = $this->_params->get("merchant_path");
		$merchant_name = $this->_params->get("merchant_name");
		$merchant_sender = $this->_params->get("merchant_sender");
		$merchant_channel = $this->_params->get("merchant_channel");
		$merchant_user_login = $this->_params->get("merchant_userlogin");
		$merchant_user_pwd = $this->_params->get("merchant_userpassword");
		$transaction_mode = $this->_params->get("transaction_mode");
		$transaction_response = $this->_params->get("transaction_response");

		// collecting user Information ( Billing Information )
		$firstname_bill = substr($data['billinginfo']->firstname, 0, 50);
		$lastname_bill = substr($data['billinginfo']->lastname, 0, 50);
		//$company_bill 			= 	substr($data['billinginfo']->company, 0, 50);
		$address_bill = substr($data['billinginfo']->address, 0, 60);
		$city_bill = substr($data['billinginfo']->city, 0, 40);
		$state_code_bill = substr($data['billinginfo']->state_code, 0, 40);
		$zipcode_bill = substr($data['billinginfo']->zipcode, 0, 20);
		$country_code_bill = substr($data['billinginfo']->country_code, 0, 60);
		$Redconfiguration = new Redconfiguration();
		$country_2code_bill = $Redconfiguration->getCountryCode2($country_code_bill);
		$phone_bill = substr($data['billinginfo']->phone, 0, 25);

		// collecting user Information ( Shipping Information )
		$firstname_shipp = substr($data['shippinginfo']->firstname, 0, 50);
		$lastname_shipp = substr($data['shippinginfo']->lastname, 0, 50);
		//$company_shipp			= 	substr($data['shippinginfo']->company, 0, 50);
		$address_shipp = substr($data['shippinginfo']->address, 0, 60);
		$city_shipp = substr($data['shippinginfo']->city, 0, 40);
		$state_code_shipp = substr($data['shippinginfo']->state_code, 0, 40);
		$zipcode_shipp = substr($data['shippinginfo']->zipcode, 0, 20);
		$country_code_shipp = substr($data['shippinginfo']->country_code, 0, 60);

		// Additional Customer Data
		$user_id = $data['billinginfo']->user_id;
		$remote_add = $_SERVER["REMOTE_ADDR"];


		// Email Settings
		$user_email = $data['billinginfo']->user_email;

		// get Credit card Information
		$order_payment_name = $ccdata['order_payment_name'];
		$creditcard_code = strtoupper($ccdata['creditcard_code']);
		$order_payment_number = $ccdata['order_payment_number'];
		$credit_card_code = $ccdata['credit_card_code'];
		$order_payment_expire_month = $ccdata['order_payment_expire_month'];
		$order_payment_expire_year = $ccdata['order_payment_expire_year'];
		$tax_exempt = false;
		$order_total = $data['order_total'];
		// include Integrator file
		include (JPATH_SITE . DS . "plugins" . DS . "redshop_payment" . DS . "rs_payment_partner_de" . DS . "ppDE.integrator.php");

		// new ctpepost
		// for the correct parameters for authentication refer to the DemoMerchantInfo Link in your Implementation Packages Documentation.
		$payment = new _ctpexmlpost($merchant_server, $merchant_path, $merchant_sender, $merchant_channel, $merchant_user_login, $merchant_user_pwd, $transaction_mode, $transaction_response);

		// set the Account Information
		// credit card (uncomment if you use CC)	4200000000000000
		$payment->setAccountInformation($order_payment_name, $creditcard_code, $order_payment_number, '', '', '', $credit_card_code, $order_payment_expire_year, $order_payment_expire_month);
		// bank account (uncomment if you use DD)

		// set payment information (CC.DB for credit card, DD.DB for direct debit)
		$payment->setPaymentCode('CC.DB');

		$payment->setPaymentInformation($order_total, 'oder#19311/shop.de', $order_number, CURRENCY_CODE);

		// set customer contact information
		$payment->setCustomerContact($user_email, '', $remote_add, '');

		// set customer address
		$payment->setCustomerAddress($address_bill, $zipcode_bill, $city_bill, '', $country_2code_bill); // country code 2 digit

		// set customer name
		$payment->setCustomerName('', '', $firstname_bill, $lastname_bill, '');

		// commit payment
		$output = $payment->commitXMLPayment();

		// false is returned if the connection to the ctpe server could not be established
		// output is "ACK" if successfull
		// elsewhere the error-statement is returned

		if (is_array($output))
		{
			$TransactionID = $output['TransactionID'];

			$Return_Data = $output['Return_Data'];

			if ($output['ACK'] == "ACK") // everything is OK ... redirect to success page
			{
				$values->responsestatus = 'Success';
				$message = $Return_Data;
			}
			else // there is an error (check $output for error code ... e.g. print $output to logfile
			{
				$values->responsestatus = 'Fail';
				$message = $Return_Data;
			}
		}
		else // there is a connection-problem
		{
			$values->responsestatus = 'Fail';
			$message = JText::_('COM_REDSHOP_ORDER_NOT_PLACED');

		}

		$values->transaction_id = $TransactionID;
		$values->message = $message;

		return $values;
	}

	function onCapture_Paymentrs_payment_partner_de($element, $data)
	{
		return;
	}

}