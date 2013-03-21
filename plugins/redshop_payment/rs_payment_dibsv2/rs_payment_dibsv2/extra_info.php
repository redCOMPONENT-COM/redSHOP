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
require_once JPATH_BASE . DS . 'administrator' . DS . 'components' . DS . 'com_redshop' . DS . 'helpers' . DS . 'order.php';
$Itemid = $_REQUEST['Itemid'];
$order_functions = new order_functions;
$currencyClass = new convertPrice;
$order_items = $order_functions->getOrderItemDetail($data['order_id']);
$order = $order_functions->getOrderDetails($data['order_id']);
$hmac_key = $this->_params->get("hmac_key");
$language = $this->_params->get("dibs_languages");

if ($language == "Auto")
{
	$language = "en";
}
// for total amount
$amount = $currencyClass->convert($data['carttotal'], '', $this->_params->get("dibs_currency"));
$amount = floor($amount * 100) / 100;
$amount = number_format($amount, 2, '.', '') * 100;
$paytype = $this->_params->get("dibs_paytype");
$dibs_paytype = implode(",", $paytype);
//Authnet vars to send
$formdata = array(
	'merchant'            => $this->_params->get("seller_id"),
	'orderId'             => $data['order_id'],
	'currency'            => $this->_params->get("dibs_currency"),
	'language'            => $language,
	'amount'              => $amount,
	'acceptReturnUrl'     => JURI::base() . "index.php?tmpl=component&option=com_redshop&view=order_detail&controller=order_detail&task=notify_payment&payment_plugin=rs_payment_dibsv2&Itemid=$Itemid&orderid=" . $data['order_id'],
	'cancelreturnurl'     => JURI::base() . "index.php?tmpl=component&option=com_redshop&view=order_detail&controller=order_detail&task=notify_payment&payment_plugin=rs_payment_dibsv2&Itemid=$Itemid&orderid=" . $data['order_id'],
	'callbackUrl'         => JURI::base() . "index.php?tmpl=component&option=com_redshop&view=order_detail&controller=order_detail&task=notify_payment&payment_plugin=rs_payment_dibsv2&Itemid=$Itemid&orderid=" . $data['order_id'],
	// Customer Billing Address
	'billingFirstName'    => $data['billinginfo']->firstname,
	'billingLastName'     => $data['billinginfo']->lastname,
	'billingAddress'      => $data['billinginfo']->address,
	'billingPostalCode'   => $data['billinginfo']->zipcode,
	'billingPostalPlace'  => $data['billinginfo']->city,
	// Customer Shipping Address
	'shippingFirstName'   => $data['shippinginfo']->firstname,
	'shippingLastName'    => $data['shippinginfo']->lastname,
	'shippingAddress'     => $data['shippinginfo']->address,
	'shippingPostalCode'  => $data['shippinginfo']->zipcode,
	'shippingPostalPlace' => $data['shippinginfo']->city,
	//Extra parameters
	'payType'             => $dibs_paytype,
	'oiTypes'             => "QUANTITY;UNITCODE;DESCRIPTION;AMOUNT;ITEMID;VATAMOUNT",
	'oiNames'             => "Items;UnitCode;Description;Amount;ItemId;VatAmount"
);

if ($this->_params->get("instant_capture"))
{
	$formdata['capturenow'] = $this->_params->get("instant_capture");
}

if ($this->_params->get("is_test"))
{
	$formdata['test'] = 1;
}

for ($p = 0; $p < count($order_items); $p++)
{
	// price conversion
	$product_item_price = $currencyClass->convert($order_items[$p]->product_item_price, '', $this->_params->get("dibs_currency"));
	$product_item_price_excl_vat = $currencyClass->convert($order_items[$p]->product_item_price_excl_vat, '', $this->_params->get("dibs_currency"));
	$pvat = $product_item_price - $product_item_price_excl_vat;
	$total_amount = $product_item_price * $order_items[$p]->quantity;
	$product_item_price_excl_vat = floor($product_item_price_excl_vat * 100) / 100;
	$product_item_price_excl_vat = number_format($product_item_price_excl_vat, 2, '.', '') * 100;
	$pvat = floor($pvat * 100) / 100;
	$pvat = number_format($pvat, 2, '.', '') * 100;
	$formdata['oiRow' . ($p + 1) . ''] = "" . $order_items[$p]->product_quantity . ";" . $order_items[$p]->order_item_name . ";" . $order_items[$p]->order_item_name . ";" . $product_item_price_excl_vat . ";" . $order_items[$p]->product_id . ";" . $pvat;
}

if ($order->order_discount > 0)
{
	$quantity_discount = 1;
	$discount_amount = $currencyClass->convert($order->order_discount, '', $this->_params->get("dibs_currency"));
	$discount_amount = floor($discount_amount * 100) / 100;
	$discount_amount = number_format($discount_amount, 2, '.', '') * 100;
	$discount_amount = -$discount_amount;
	$discount_pvat = 0;
	$formdata['oiRow' . ($p + 1) . ''] = "" . $quantity_discount . ";Discount;Discount;" . $discount_amount . ";" . ($p + 1) . ";" . $discount_pvat;
	$p++;
}

if ($order->order_shipping > 0)
{
	$quantity_shipping = 1;
	$order_shipping_tax = 0;

	if ($order->order_shipping_tax > 0 && $order->order_shipping_tax != null)
	{
		$order_shipping_tax = $order->order_shipping_tax;
	}

	$shipping_price = $currencyClass->convert($order->order_shipping, '', $this->_params->get("dibs_currency"));
	$shipping_vat = $currencyClass->convert($order_shipping_tax, '', $this->_params->get("dibs_currency"));
	$shipping_price = floor($shipping_price * 100) / 100;
	$shipping_price = number_format($shipping_price, 2, '.', '') * 100;
	$shipping_vat = floor($shipping_vat * 100) / 100;
	$shipping_vat = number_format($shipping_vat, 2, '.', '') * 100;

	$formdata['oiRow' . ($p + 1) . ''] = "" . $quantity_shipping . ";Shipping;Shipping;" . $shipping_price . ";" . ($p + 1) . ";" . $shipping_vat;
	$p++;

}

$payment_price = $order->payment_discount;

if ($payment_price > 0)
{
	$quantity_payment = 1;
	$payment_price = $currencyClass->convert($payment_price, '', $this->_params->get("dibs_currency"));
	$payment_price = floor($payment_price * 100) / 100;
	$payment_price = number_format($payment_price, 2, '.', '') * 100;

	if ($order->payment_oprand == '-')
	{
		$discount_payment_price = -$payment_price;

	}
	else
	{
		$discount_payment_price = $payment_price;

	}

	$payment_vat = 0;
	$formdata['oiRow' . ($p + 1) . ''] = "" . $quantity_payment . ";Payment Handling;Payment Handling;" . $discount_payment_price . ";" . ($p + 1) . ";" . $payment_vat;

}

$api_path = JPATH_SITE . DS . 'plugins' . DS . 'redshop_payment' . DS . $plugin . DS . $plugin . DS . 'dibs_hmac.php';
include($api_path);
$dibs_hmac = new dibs_hmac;
$mac_key = $dibs_hmac->calculateMac($formdata, $hmac_key);
// Action Url
$dibsurl = "https://sat1.dibspayment.com/dibspaymentwindow/entrypoint";
?>
<form action="<?php echo $dibsurl ?>" id='dibscheckout' name="dibscheckout" method="post" accept-charset="utf-8">
	<?php foreach ($formdata as $name => $value)
	{
		?>
		<input type="hidden" name="<?php echo $name ?>" value="<?php echo $value ?>"/>
	<?php
	} ?>
	<input type="hidden" name="MAC" value="<?php echo $mac_key ?>"/>
</form>
<script>
	document.getElementById("dibscheckout").submit();
</script>
