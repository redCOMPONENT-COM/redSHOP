<?php
/**
 * @package     RedSHOP
 * @subpackage  Plugin
 *
 * @copyright   Copyright (C) 2005 - 2013 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

require_once JPATH_SITE . '/administrator/components/com_redshop/helpers/redshop.cfg.php';
JLoader::import('loadhelpers', JPATH_SITE . '/components/com_redshop');
JLoader::load('RedshopHelperHelper');

$objOrder         = new order_functions;
$objconfiguration = new Redconfiguration;
$user             = JFactory::getUser();
$shipping_address = $objOrder->getOrderShippingUserInfo($data['order_id']);
$Itemid           = $_REQUEST['Itemid'];

$redhelper        = new redhelper;
$db               = JFactory::getDbo();
$user             = JFActory::getUser();
$task             = JRequest::getVar('task');
$layout           = JRequest::getVar('layout');
$app              = JFactory::getApplication();

if ($this->params->get("currency") != "")
{
	$currency_main = $this->params->get("currency");
}
elseif (CURRENCY_CODE != "")
{
	$currency_main = CURRENCY_CODE;
}
else
{
	$currency_main = "USD";
}

$sql = "SELECT op.*,o.order_total,o.user_id,o.order_tax,o.order_subtotal,o.order_shipping,o.order_number,o.payment_discount FROM #__redshop_order_payment AS op LEFT JOIN #__redshop_orders AS o ON op.order_id = o.order_id  WHERE o.order_id='" . $data['order_id'] . "'";
$db->setQuery($sql);
$order_details = $db->loadObjectList();

if ($this->params->get("sandbox") == '1')
{
	$paypalurl = "https://www.sandbox.paypal.com/cgi-bin/webscr";
}
else
{
	$paypalurl = "https://www.paypal.com/cgi-bin/webscr";
}

$currencyClass = new CurrencyHelper;

$order_details[0]->order_subtotal = $currencyClass->convert($order_details[0]->order_total, '', $currency_main);

$returnUrl = JURI::base() . "index.php?tmpl=component&option=com_redshop&view=order_detail&controller=order_detail&task=notify_payment&payment_plugin=rs_payment_paypal&Itemid=$Itemid&orderid=" . $data['order_id'];

if ($this->params->get("auto_return") == '1')
	$returnUrl = $this->params->get("auto_return_url");

$post_variables = Array(
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
	"rm"                 => '2',
	"item_number"        => $data['order_id'],
	"invoice"            => $order_details[0]->order_number,
	"amount"             => $order_details[0]->order_subtotal,
	"return"             => $returnUrl,
	"notify_url"         => JURI::base() . "index.php?tmpl=component&option=com_redshop&view=order_detail&controller=order_detail&task=notify_payment&payment_plugin=rs_payment_paypal&Itemid=$Itemid&orderid=" . $data['order_id'],
	"night_phone_b"      => substr($data['billinginfo']->phone, 0, 25),
	"cancel_return"      => JURI::base() . "index.php?tmpl=component&option=com_redshop&view=order_detail&controller=order_detail&task=notify_payment&payment_plugin=rs_payment_paypal&Itemid=$Itemid&orderid=" . $data['order_id'],
	"undefined_quantity" => "0",
	"test_ipn"           => $this->params->get("is_test"),
	"pal"                => "NRUBJXESJTY24",
	"no_shipping"        => "0",
	"no_note"            => "1",
	"tax_cart"           => $order_details[0]->order_tax,
	"currency_code"      => $currency_main

);

if (SHIPPING_METHOD_ENABLE)
{
	$shipping_variables = Array(
		"address1"   => $shipping_address->address,
		"city"       => $shipping_address->city,
		"country"    => $shipping_address->country_code,
		"first_name" => $shipping_address->firstname,
		"last_name"  => $shipping_address->lastname,
		"state"      => $shipping_address->state_code,
		"zip"        => $shipping_address->zipcode
	);
}

$payment_price = $this->params->get("payment_price");

$post_variables['discount_amount_cart'] = round($currencyClass->convert($data['odiscount'], '', $currency_main), 2);

if(isset($data['special_discount']))
{
	$post_variables['discount_amount_cart'] += round($currencyClass->convert($data['special_discount'], '', $currency_main), 2);
}

if ($this->params->get("payment_oprand") == '-')
{
	$discount_payment_price = $payment_price;
	$post_variables['discount_amount_cart'] += round($currencyClass->convert($order_details[0]->payment_discount, '', $currency_main), 2);
}
else
{
	$discount_payment_price = $payment_price;
	$post_variables['handling_cart'] = round($currencyClass->convert($order_details[0]->payment_discount, '', $currency_main), 2);
}


$db = JFactory::getDbo();
$q_oi = "SELECT * FROM #__redshop_order_item ";
$q_oi .= "WHERE order_id='" . $data['order_id'] . "'";
$db->setQuery($q_oi);
$items = $db->loadObjectList();

$q_oi = "SELECT sum(product_quantity) FROM #__redshop_order_item ";
$q_oi .= "WHERE order_id='" . $data['order_id'] . "'";
$db->setQuery($q_oi);
$totalq = $db->loadResult();

$shipping = $order_details[0]->order_shipping / $totalq;

for ($i = 0; $i < count($items); $i++)
{
	$item                              = $items[$i];
	$tax                               = ($item->product_final_price / $item->product_quantity) - $item->product_item_price;

	$supp_var["item_name_" . ($i + 1)] = strip_tags(str_replace('"', "'", $item->order_item_name));
	$supp_var["quantity_" . ($i + 1)]  = $item->product_quantity;
	$supp_var["amount_" . ($i + 1)]    = round($currencyClass->convert($item->product_item_price_excl_vat, '', $currency_main), 2);
	$shipping2                         = $item->product_quantity * $shipping;
	$supp_var['shipping_' . ($i + 1)]  = round($currencyClass->convert($shipping2, '', $currency_main), 2);
}

echo "<form action='$paypalurl' method='post' name='paypalfrm' id='paypalfrm'>";
echo "<h3>" . JText::_('COM_REDSHOP_PAYPAL_WAIT_MESSAGE') . "</h3>";

foreach ($post_variables as $name => $value)
{
	echo "<input type='hidden' name='$name' value='$value' />";
}

if (is_array($supp_var) && count($supp_var))
{
	foreach ($supp_var as $name => $value)
	{
		echo '<input type="hidden" name="' . $name . '" value="' . $value . '" />';
	}
}

if (SHIPPING_METHOD_ENABLE)
{
	if (is_array($shipping_variables) && count($shipping_variables))
	{
		foreach ($shipping_variables as $name => $value)
		{
			echo '<input type="hidden" name="' . $name . '" value="' . $value . '" />';
		}
	}
}

echo '<INPUT TYPE="hidden" name="charset" value="utf-8">';
echo "</form>";
?>
<script type='text/javascript'>document.paypalfrm.submit();</script>
