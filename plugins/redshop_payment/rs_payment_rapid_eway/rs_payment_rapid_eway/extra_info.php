<?php
/**
 * @package     RedSHOP
 * @subpackage  Plugin
 *
 * @copyright   Copyright (C) 2005 - 2013 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

require_once JPATH_SITE . '/administrator/components/com_redshop/helpers/configuration.php';
require_once JPATH_SITE . '/administrator/components/com_redshop/helpers/order.php';

$app              = JFactory::getApplication();
$Redconfiguration = new Redconfiguration;
$order_functions  = new order_functions;
$order_items      = $order_functions->getOrderItemDetail($data['order_id']);
$session          = JFactory::getSession();
$ccdata           = $session->get('redirect_ccdata');

include JPATH_SITE . '/plugins/redshop_payment/' . $plugin . '/' . $plugin . '/Rapid.php';

// GetCountryCode2
$data['billinginfo']->country_code = $Redconfiguration->getCountryCode2($data['billinginfo']->country_code);

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
}
// Get Payment Plugin Params
$eWAYcustomer_id = $this->params->get("customer_id");
$eWAYusername    = $this->params->get("username");
$eWAYpassword    = $this->params->get("password");
$test_mode       = $this->params->get("test_mode");

$currencyClass  = new CurrencyHelper;
$order_subtotal = $currencyClass->convert($data['order']->order_total, '', $currency_main);

// Create RapidAPI Service
$service = new RapidAPI;
$service->setTestMode($test_mode);
$service->getAuthorizeData($eWAYusername, $eWAYpassword);

// Create AccessCode Request Object
$request = new CreateAccessCodeRequest;
$request->Customer->Title = "Mr.";
$request->Customer->FirstName = $data['billinginfo']->firstname;
$request->Customer->LastName = $data['billinginfo']->lastname;
$request->Customer->CompanyName = $data['billinginfo']->company_name;
$request->Customer->Street1 = $data['billinginfo']->address;
$request->Customer->City = $data['billinginfo']->city;
$request->Customer->State = $data['billinginfo']->state_code;
$request->Customer->PostalCode = $data['billinginfo']->zipcode;
$request->Customer->Country = $data['billinginfo']->country_code;
$request->Customer->Email = $data['billinginfo']->email;
$request->Customer->Phone = $data['billinginfo']->phone;

if (count($order_items) > 0)
{
	for ($p = 0; $p < count($order_items); $p++)
	{
		// Populate values for LineItems
		$item = new LineItem;
		$item->SKU = $order_items[$p + 1]->order_item_sku;
		$item->Description = $order_items[$p + 1]->order_item_name;
		$request->Items->LineItem[$p + 1] = $item;
	}
}

// Populate values for Payment Object
$request->Payment->TotalAmount = $order_subtotal;
$request->Payment->InvoiceNumber = $data['order']->order_number;
$request->Payment->CurrencyCode = $currency_main;

// Url to the page for getting the result with an AccessCode
$request->RedirectUrl = JURI::base() . "index.php?tmpl=component&option=com_redshop&view=order_detail&controller=order_detail&task=notify_payment&payment_plugin=rs_payment_rapid_eway&orderid=" . $data['order_id'];
$request->Method = "ProcessPayment";
$result = $service->CreateAccessCode($request);

// Check if any error returns
if (isset($result->Errors))
{
	// Get Error Messages from Error Code. Error Code Mappings are in the Config.ini file
	$ErrorArray = explode(",", $result->Errors);
	$lblError = "";

	foreach ($ErrorArray as $error)
	{
		if (isset($service->APIConfig[$error]))
		{
			$lblError .= $error . " " . $service->APIConfig[$error] . "<br>";
		}
		else
		{
			$lblError .= $error;
		}
	}
}

if ($lblError != "")
{
	$link = 'index.php?option=com_redshop&view=order_detail&Itemid=' . $Itemid . '&oid=' . $data['order_id'];
	$app->redirect($link, $lblError);
}
?>
<form method="POST" action="<?php echo $result->FormActionURL ?>" id="ewayfrm" name="ewayfrm">
	<input type="text" name="EWAY_ACCESSCODE" value="<?php echo $result->AccessCode ?>"/>
	<input type="text" name="EWAY_CARDNAME" value="<?php echo $ccdata['order_payment_name'] ?>"/>
	<input type="text" name="EWAY_CARDNUMBER" value="<?php echo $ccdata['order_payment_number'] ?>"/>
	<input type="text" name="EWAY_CARDEXPIRYMONTH" value="<?php echo $ccdata['order_payment_expire_month'] ?>"/>
	<input type="text" name="EWAY_CARDEXPIRYYEAR" value="<?php echo $ccdata['order_payment_expire_year'] ?>"/>
	<input type="text" name="EWAY_CARDCVN" value="<?php echo $ccdata['credit_card_code'] ?>"/>
</form>
<script type='text/javascript'>document.ewayfrm.submit();</script>
