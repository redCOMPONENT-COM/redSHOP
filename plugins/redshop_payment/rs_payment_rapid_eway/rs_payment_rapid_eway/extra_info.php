<?php
/**
 * @package     RedSHOP
 * @subpackage  Plugin
 *
 * @copyright   Copyright (C) 2008 - 2015 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */
JLoader::import('redshop.library');

$app              = JFactory::getApplication();
$Redconfiguration = Redconfiguration::getInstance();
$order_functions  = order_functions::getInstance();
$order_items      = $order_functions->getOrderItemDetail($data['order_id']);
$session          = JFactory::getSession();
$ccdata           = $session->get('ccdata');
$app              = JFactory::getApplication();
$Itemid           = $app->input->getInt('Itemid');

include JPATH_SITE . '/plugins/redshop_payment/' . $plugin . '/' . $plugin . '/RapidAPI.php';

// GetCountryCode2
$data['billinginfo']->country_code  = $Redconfiguration->getCountryCode2($data['billinginfo']->country_code);
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

$currency_main = "GBP";

$order_subtotal = RedshopHelperCurrency::convert($data['order']->order_total, '', $currency_main);

// Create DirectPayment Request Object
$request = new eWAY\CreateDirectPaymentRequest;

$request->Customer->FirstName   = $data['billinginfo']->firstname;
$request->Customer->LastName    = $data['billinginfo']->lastname;
$request->Customer->CompanyName = $data['billinginfo']->company_name;
$request->Customer->Street1     = $data['billinginfo']->address;
$request->Customer->City        = $data['billinginfo']->city;
$request->Customer->State       = $data['billinginfo']->state_code;
$request->Customer->PostalCode  = $data['billinginfo']->zipcode;
$request->Customer->Country     = $data['billinginfo']->country_code;
$request->Customer->Email       = $data['billinginfo']->user_email;
$request->Customer->Phone       = $data['billinginfo']->phone;

// Populate values for ShippingAddress Object.
$request->ShippingAddress->FirstName  = $data['shippinginfo']->firstname;
$request->ShippingAddress->LastName   = $data['shippinginfo']->lastname;
$request->ShippingAddress->Street1    = $data['shippinginfo']->address;
$request->ShippingAddress->City       = $data['shippinginfo']->city;
$request->ShippingAddress->State      = $data['shippinginfo']->state_code;
$request->ShippingAddress->Country    = $data['shippinginfo']->country_code;
$request->ShippingAddress->PostalCode = $data['billinginfo']->zipcode;
$request->ShippingAddress->Email      = $data['billinginfo']->user_email;
$request->ShippingAddress->Phone      = $data['billinginfo']->phone;

if (count($order_items) > 0)
{
	foreach ($order_items as $order_item)
	{
		// Populate values for LineItems
		$item                       = new eWAY\LineItem;
		$item->SKU                  = $order_item->order_item_sku;
		$item->Description          = $order_item->order_item_name;
		$request->Items->LineItem[] = $item;
	}
}

// Populate values for Payment Object
$request->Payment->TotalAmount   = round($order_subtotal * 100, 0, PHP_ROUND_HALF_UP);
$request->Payment->InvoiceNumber = $data['order']->order_number;
$request->Payment->CurrencyCode  = $currency_main;

// Url to the page for getting the result with an AccessCode
$request->RedirectUrl = JURI::base() . "index.php?tmpl=component&option=com_redshop&view=order_detail&controller=order_detail&task=notify_payment&payment_plugin=rs_payment_rapid_eway&orderid=" . $data['order_id'];
$request->Method      = "ProcessPayment";

$eway_params = array('sandbox' => $this->params->get('test_mode', true));

$service = new eWAY\RapidAPI($this->params->get("APIKey"), $this->params->get("APIPassword"), $eway_params);
$result  = $service->CreateAccessCode($request);

// Check if any error returns
if (isset($result->Errors))
{
	// Get Error Messages from Error Code.
	$ErrorArray = explode(",", $result->Errors);
	$lblError   = "";

	foreach ($ErrorArray as $error)
	{
		$error    = $service->getMessage($error);
		$lblError .= $error . "<br />\n";
	}

	$link = 'index.php?option=com_redshop&view=order_detail&Itemid=' . $Itemid . '&oid=' . $data['order_id'];
	$app->redirect($link, $lblError);
}

// Redirection after full page load
JHtml::_('redshopjquery.framework');
$document = JFactory::getDocument();
$document->addScriptDeclaration(
	'jQuery(document).ready(function(){
		jQuery("#ewayfrm").submit();
	});'
);
?>
<h3><?php echo JText::_('PLG_RS_PAYMENT_RAPID_EWAY_WAIT_MESSAGE'); ?></h3>
<form method="POST" action="<?php echo $result->FormActionURL ?>" id="ewayfrm" name="ewayfrm">
    <input type="hidden" name="EWAY_ACCESSCODE" value="<?php echo $result->AccessCode ?>"/>
    <input type="hidden" name="EWAY_CARDNAME" value="<?php echo $ccdata['order_payment_name'] ?>"/>
    <input type="hidden" name="EWAY_CARDNUMBER" value="<?php echo $ccdata['order_payment_number'] ?>"/>
    <input type="hidden" name="EWAY_CARDEXPIRYMONTH" value="<?php echo $ccdata['order_payment_expire_month'] ?>"/>
    <input type="hidden" name="EWAY_CARDEXPIRYYEAR" value="<?php echo $ccdata['order_payment_expire_year'] ?>"/>
    <input type="hidden" name="EWAY_CARDCVN" value="<?php echo $ccdata['credit_card_code'] ?>"/>
</form>
