<?php
/**
 * @package     RedSHOP
 * @subpackage  Plugin
 *
 * @copyright   Copyright (C) 2008 - 2017 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

JLoader::import('redshop.library');

$objOrder         = order_functions::getInstance();
$objconfiguration = Redconfiguration::getInstance();
$redhelper        = redhelper::getInstance();
$app              = JFactory::getApplication();
$input            = $app->input;

$task   = $input->getCmd('task');
$layout = $input->getCmd('layout');
$Itemid = $input->getInt('Itemid');

$paymentCurrency = $this->params->get("currency", Redshop::getConfig()->get('CURRENCY_CODE'));

if (1 == (int) $this->params->get("sandbox"))
{
	$paypalurl = "https://www.sandbox.paypal.com/cgi-bin/webscr";
}
else
{
	$paypalurl = "https://www.paypal.com/cgi-bin/webscr";
}

$key = array($data['order_id'], (int) $this->params->get("sandbox"), $this->params->get("merchant_email"));
$key = md5(implode('|', $key));

$returnUrl = JUri::base() . "index.php?tmpl=component&option=com_redshop&view=order_detail&"
	. "controller=order_detail&task=notify_payment&payment_plugin=rs_payment_paypal&Itemid=$Itemid&orderid="
	. $data['order_id'] . '&key=' . $key;

if (1 == (int) $this->params->get("auto_return"))
{
	$returnUrl = $this->params->get("auto_return_url");
}

$paypalPostData = Array(
	"cmd"                => "_cart",
	"upload"             => "1",
	"business"           => $this->params->get("merchant_email"),
	"receiver_email"     => $this->params->get("merchant_email"),
	"item_name"          => JText::_('COM_REDSHOP_ORDER_ID_LBL') . ":" . $data['order_id'],
	"first_name"         => $data['billinginfo']->firstname,
	"last_name"          => $data['billinginfo']->lastname,
	"address1"           => $data['billinginfo']->address,
	"city"               => $data['billinginfo']->city,
	"country"            => $data['billinginfo']->country_2_code,
	"zip"                => $data['billinginfo']->zipcode,
	"email"              => $data['billinginfo']->user_email,
	"paymentaction"      => $this->params->get("action_type", "sale"),
	"rm"                 => '2',
	"item_number"        => $data['order_id'],
	"invoice"            => $data['order']->order_number,
	"amount"             => RedshopHelperCurrency::convert($data['order']->order_total, '', $paymentCurrency),
	"landing_page"       => "billing",
	"return"             => $returnUrl,
	"notify_url"         => JURI::base() . "index.php?tmpl=component&option=com_redshop&view=order_detail&controller=order_detail&"
		. "task=notify_payment&payment_plugin=rs_payment_paypal&Itemid=$Itemid&orderid=" . $data['order_id'] . '&key=' . $key,
	"night_phone_b"      => substr($data['billinginfo']->phone, 0, 25),
	"cancel_return"      => JURI::base() . "index.php?tmpl=component&option=com_redshop&view=order_detail&controller=order_detail&"
		. "task=notify_payment&payment_plugin=rs_payment_paypal&Itemid=$Itemid&orderid=" . $data['order_id'] . '&key=' . $key,
	"undefined_quantity" => "0",
	"pal"                => "NRUBJXESJTY24",
	"no_shipping"        => "0",
	"no_note"            => "1",
	"tax_cart"           => $data['order']->order_tax,
	"currency_code"      => $paymentCurrency,
	'bn'                 => 'redCOMPONENT_SP'
);

if (Redshop::getConfig()->get('SHIPPING_METHOD_ENABLE'))
{
	$paypalShippingData = Array(
		"address1"   => $data['shippinginfo']->address,
		"city"       => $data['shippinginfo']->city,
		"country"    => $data['shippinginfo']->country_2_code,
		"first_name" => $data['shippinginfo']->firstname,
		"last_name"  => $data['shippinginfo']->lastname,
		"state"      => $data['shippinginfo']->state_code,
		"zip"        => $data['shippinginfo']->zipcode
	);
}

$paypalPostData['discount_amount_cart'] = round(RedshopHelperCurrency::convert($data['order']->order_discount, '', $paymentCurrency), 2);
$paypalPostData['discount_amount_cart'] += round(RedshopHelperCurrency::convert($data['order']->special_discount, '', $paymentCurrency), 2);

switch ($this->params->get("payment_oprand"))
{
	case '-':
		$paypalPostData['discount_amount_cart'] += round(RedshopHelperCurrency::convert($data['order']->payment_discount, '', $paymentCurrency), 2);
		break;

	case '+':
		$paypalPostData['handling_cart'] = round(RedshopHelperCurrency::convert($data['order']->payment_discount, '', $paymentCurrency), 2);
		break;
}

$items = $objOrder->getOrderItemDetail($data['order_id']);

// Calculate Total Shipping
$shipping        = $data['order']->order_shipping;
$paypalCartItems = array();

for ($i = 0, $countItems = count($items); $i < $countItems; $i++)
{
	$index = $i + 1;

	$item                                   = $items[$i];
	$tax                                    = ($item->product_final_price / $item->product_quantity) - $item->product_item_price;
	$paypalCartItems["item_name_" . $index] = strip_tags(str_replace('"', "'", $item->order_item_name) . ' (' . $item->order_item_sku . ')');
	$paypalCartItems["quantity_" . $index]  = $item->product_quantity;
	$paypalCartItems["amount_" . $index]    = round(
		RedshopHelperCurrency::convert(
			$item->product_item_price_excl_vat,
			'',
			$paymentCurrency
		),
		2
	);

	$paypalCartItems['shipping_' . $index] = 0;
}

$paypalCartItems['shipping_1'] = round(RedshopHelperCurrency::convert($shipping, '', $paymentCurrency), 2);

echo '<form action="' . $paypalurl . '" method="post" name="paypalfrm" id="paypalfrm">';
echo "<h3>" . JText::_('PLG_RS_PAYMENT_PAYPAL_WAIT_MESSAGE') . "</h3>";

foreach ($paypalPostData as $name => $value)
{
	echo "<input type='hidden' name='$name' value='$value' />";
}

if (is_array($paypalCartItems) && count($paypalCartItems))
{
	foreach ($paypalCartItems as $name => $value)
	{
		echo '<input type="hidden" name="' . $name . '" value="' . $value . '" />';
	}
}

if (Redshop::getConfig()->get('SHIPPING_METHOD_ENABLE'))
{
	if (is_array($paypalShippingData) && count($paypalShippingData))
	{
		foreach ($paypalShippingData as $name => $value)
		{
			echo '<input type="hidden" name="' . $name . '" value="' . $value . '" />';
		}
	}
}

echo '<input type="hidden" name="charset" value="utf-8">';
echo "</form>";
?>
<script type='text/javascript'>
    document.getElementById('paypalfrm').submit();
</script>
