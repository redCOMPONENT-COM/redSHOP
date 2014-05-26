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

$uri = JURI::getInstance();
$url = $uri->root();
$user = JFactory::getUser();
$app = JFactory::getApplication();

$session = JFactory::getSession();
$redirect_ccdata = $session->get('redirect_ccdata');

$eWAYcustomer_id = $this->_params->get("customer_id");
$eWAYusername = $this->_params->get("username");
$eWAYpassword = $this->_params->get("password");

$currencyClass = new CurrencyHelper;
$currency_main = "GBP";
$order_subtotal = $currencyClass->convert($data['order']->order_total, '', $currency_main);

$request = array(
	'Authentication' => array(
		'Username'   => $eWAYusername,
		'Password'   => $eWAYpassword,
		'CustomerID' => $eWAYcustomer_id,
	),
	'Customer'       => array(
		'Reference'   => $_POST['txtCustomerRef'],
		'Title'       => $_POST['ddlTitle'],
		'FirstName'   => $data['billinginfo']->firstname,
		'LastName'    => $data['billinginfo']->lastname,
		'Street1'     => $data['billinginfo']->address,
		'City'        => $data['billinginfo']->city,
		'State'       => $data['billinginfo']->state_code,
		'PostalCode'  => $data['billinginfo']->zipcode,
		'Country'     => $data['billinginfo']->country_code,
		'CompanyName' => $data['billinginfo']->company_name,
		'Email'       => $data['billinginfo']->zipcode,
		'Phone'       => $data['billinginfo']->phone,

	),

	'Payment'        => array(
		'TotalAmount'   => $order_subtotal,
		'InvoiceNumber' => $data['order']->order_number,
		'CurrencyCode'  => 'GBP',
	),
	'RedirectUrl'    => JURI::base() . "index.php?tmpl=component&option=com_redshop&view=order_detail&controller=order_detail&task=notify_payment&payment_plugin=rs_payment_rapid_ewaynz&orderid=" . $data['order_id'],
	'ResponseMode'   => 'Redirect',
);


try
{
	$client = new SoapClient("https://nz.ewaypayments.com/hotpotato/soap.asmx?WSDL", array(
		'trace'      => false,
		'exceptions' => true,
	));
	$result = $client->CreateAccessCode(array('request' => $request));
}
catch (Exception $e)
{
	$lblError = $e->getMessage();
}

?>
<form method="POST" action="https://nz.ewaypayments.com/hotpotato/payment" id="ewayfrm" name="ewayfrm">
	<input type="hidden" name="EWAY_ACCESSCODE" value="<?php echo $result->CreateAccessCodeResult->AccessCode ?>"/>
	<input type="hidden" name="EWAY_CARDNAME" value="<?php echo $redirect_ccdata['order_payment_name'] ?>"/>
	<input type="hidden" name="EWAY_CARDNUMBER" value="<?php echo $redirect_ccdata['order_payment_number'] ?>"/>
	<input type="hidden" name="EWAY_CARDMONTH" value="<?php echo $redirect_ccdata['order_payment_expire_month'] ?>"/>
	<input type="hidden" name="EWAY_CARDYEAR" value="<?php echo $redirect_ccdata['order_payment_expire_year'] ?>"/>
	<input type="hidden" name="EWAY_CARDCVN" value="<?php echo $redirect_ccdata['credit_card_code'] ?>"/>

	<!--<input type="submit" value="ProcessPayment" text="Process Payment" />-->
</form>
<script type='text/javascript'>document.ewayfrm.submit();</script>





