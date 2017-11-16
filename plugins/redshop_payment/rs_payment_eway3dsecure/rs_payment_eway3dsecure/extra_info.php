<?php
/**
 * @package     RedSHOP
 * @subpackage  Plugin
 *
 * @copyright   Copyright (C) 2008 - 2015 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

// Include RapidAPI Library
require 'RapidAPI.php';

// Create Responsive Shared Page Request Object
$request = new eWAY\CreateAccessCodesSharedRequest;

// Populate values for Customer Object
$request->Customer->FirstName = $data['billinginfo']->firstname;
$request->Customer->LastName = $data['billinginfo']->lastname;
$request->Customer->CompanyName = $data['billinginfo']->company_name;
$request->Customer->Street1 = $data['billinginfo']->address;
$request->Customer->City = $data['billinginfo']->city;
$request->Customer->State = $data['billinginfo']->state_code;
$request->Customer->PostalCode = $data['billinginfo']->zipcode;
$request->Customer->Country = $data['billinginfo']->country_2_code;
$request->Customer->Email = $data['billinginfo']->user_email;
$request->Customer->Phone = $data['billinginfo']->phone;
$request->Customer->Comments = $data['order']->customer_note;

// Populate values for ShippingAddress Object.
$request->ShippingAddress->FirstName = $data['shippinginfo']->firstname;
$request->ShippingAddress->LastName = $data['shippinginfo']->lastname;
$request->ShippingAddress->Street1 = $data['shippinginfo']->address;
$request->ShippingAddress->City = $data['shippinginfo']->city;
$request->ShippingAddress->State = $data['shippinginfo']->state_code;
$request->ShippingAddress->Country = $data['shippinginfo']->country_2_code;
$request->ShippingAddress->PostalCode = $data['shippinginfo']->zipcode;
$request->ShippingAddress->Email = $data['shippinginfo']->user_email;
$request->ShippingAddress->Phone = $data['shippinginfo']->phone;

$order_functions  = order_functions::getInstance();
$orderItems      = $order_functions->getOrderItemDetail($data['order_id']);

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

$currency_main = $this->params->get('paymentCurrency');
$order_subtotal = RedshopHelperCurrency::convert($data['order']->order_total, '', $currency_main);

// Populate values for Payment Object
$request->Payment->TotalAmount = round($order_subtotal * 100, 0, PHP_ROUND_HALF_UP);
$request->Payment->InvoiceNumber = $data['order']->order_number;
$request->Payment->CurrencyCode = $currency_main;
$app = JFactory::getApplication();
$Itemid = $app->input->getInt('Itemid');

$url = JUri::base()
	. "index.php?tmpl=component&option=com_redshop&view=order_detail&controller=order_detail&task=notify_payment&payment_plugin=rs_payment_eway3dsecure&Itemid="
	. $Itemid . "&orderid=" . $data['order_id'];

$request->RedirectUrl = $url;
$request->CancelUrl   = $url;
$request->Method = 'ProcessPayment';
$request->CustomerReadOnly = true;

// Call RapidAPI
$eway_params = array('sandbox' => $this->params->get('test_mode', true));
$service = new eWAY\RapidAPI($this->params->get("APIKey"), $this->params->get("APIPassword"), $eway_params);
$result = $service->CreateAccessCodesShared($request);

// Check if any error returns
if (isset($result->Errors))
{
	// Get Error Messages from Error Code.
	$ErrorArray = explode(",", $result->Errors);
	$lblError = "";

	foreach ($ErrorArray as $error)
	{
		$error = $service->getMessage($error);
		$app->enqueueMessage($error, 'error');
	}
}
else
{
	$app->redirect($result->SharedPaymentUrl);
}
