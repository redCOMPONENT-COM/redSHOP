<?php
/**
 * @package     RedSHOP
 * @subpackage  Plugin
 *
 * @copyright   Copyright (C) 2008 - 2015 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */
JLoader::import('redshop.library');

$app = JFactory::getApplication();
$Redconfiguration = Redconfiguration::getInstance();
$orderFunctions   = order_functions::getInstance();
$orderItems       = $orderFunctions->getOrderItemDetail($data['order_id']);
$session          = JFactory::getSession();
$ccdata           = $session->get('ccdata');
$app              = JFactory::getApplication();
$Itemid           = $app->input->getInt('Itemid');

include JPATH_SITE . '/plugins/redshop_payment/' . $plugin . '/' . $plugin . '/RapidAPI.php';

// GetCountryCode2
$data['billinginfo']->country_code = $Redconfiguration->getCountryCode2($data['billinginfo']->country_code);
$data['shippinginfo']->country_code = $Redconfiguration->getCountryCode2($data['shippinginfo']->country_code);

/* At this moment plugin work only for currency GBP
if ($data['billinginfo']->country_code == "GB")
{
	$currency_main = "GBP";
}
elseif ($data['billinginfo']->country_code == "NZ")
{
	$currency_main = "NZD";
}
elseif ($data['billinginfo']->country_code == "AU")
{
	$currency_main = "AUD";
}
else
{
	$currency_main = "USD";
}*/

$currencyMain  = "GBP";

$currencyClass = CurrencyHelper::getInstance();
$orderSubtotal = $currencyClass->convert($data['order']->order_total, '', $currencyMain);

// Create DirectPayment Request Object
$request = new eWAY\CreateDirectPaymentRequest;

$request->Customer->FirstName = $data['billinginfo']->firstname;
$request->Customer->LastName = $data['billinginfo']->lastname;
$request->Customer->CompanyName = $data['billinginfo']->company_name;
$request->Customer->Street1 = $data['billinginfo']->address;
$request->Customer->City = $data['billinginfo']->city;
$request->Customer->State = $data['billinginfo']->state_code;
$request->Customer->PostalCode = $data['billinginfo']->zipcode;
$request->Customer->Country = $data['billinginfo']->country_code;
$request->Customer->Email = $data['billinginfo']->user_email;
$request->Customer->Phone = $data['billinginfo']->phone;

// Populate values for ShippingAddress Object.
$request->ShippingAddress->FirstName = $data['shippinginfo']->firstname;
$request->ShippingAddress->LastName = $data['shippinginfo']->lastname;
$request->ShippingAddress->Street1 = $data['shippinginfo']->address;
$request->ShippingAddress->City = $data['shippinginfo']->city;
$request->ShippingAddress->State = $data['shippinginfo']->state_code;
$request->ShippingAddress->Country = $data['shippinginfo']->country_code;
$request->ShippingAddress->PostalCode = $data['billinginfo']->zipcode;
$request->ShippingAddress->Email = $data['billinginfo']->user_email;
$request->ShippingAddress->Phone = $data['billinginfo']->phone;

if (count($orderItems) > 0)
{
	foreach ($orderItems as $orderItem)
	{
		// Populate values for LineItems
		$item = new eWAY\LineItem;
		$item->SKU = $orderItem->order_item_sku;
		$item->Description = $orderItem->order_item_name;
		$request->Items->LineItem[] = $item;
	}
}

// Populate values for Payment Object
$request->Payment->TotalAmount = round($orderSubtotal * 100, 0, PHP_ROUND_HALF_UP);
$request->Payment->InvoiceNumber = $data['order']->order_number;
$request->Payment->CurrencyCode = $currencyMain;

// Url to the page for getting the result with an AccessCode
$request->RedirectUrl = JURI::base() . "index.php?tmpl=component&option=com_redshop&view=order_detail&controller=order_detail&task=notify_payment&payment_plugin=rs_payment_rapid_eway&orderid=" . $data['order_id'];
$request->Method = "ProcessPayment";

$ewayParams = array('sandbox' => $this->params->get('test_mode', true));

$service = new eWAY\RapidAPI($this->params->get("APIKey"), $this->params->get("APIPassword"), $ewayParams);
$result = $service->CreateAccessCode($request);

// Check if any error returns
if (isset($result->Errors))
{
	// Get Error Messages from Error Code.
	$errors = explode(",", $result->Errors);
	$lblError = "";

	foreach ($errors as $error)
	{
		$error = $service->getMessage($error);
		$lblError .= $error . "<br />\n";
	}

	$link = 'index.php?option=com_redshop&view=order_detail&Itemid=' . $Itemid . '&oid=' . $data['order_id'];
	$app->redirect($link, $lblError);
}

require_once JPluginHelper::getLayoutPath('redshop_payment', 'rs_payment_rapid_eway');
