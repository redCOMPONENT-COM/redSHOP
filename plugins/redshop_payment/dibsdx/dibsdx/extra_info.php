<?php
/**
 * @package     RedSHOP
 * @subpackage  Plugin
 *
 * @copyright   Copyright (C) 2008 - 2015 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

defined('_JEXEC') or die;

use Redshop\Currency\Currency;

JLoader::import('redshop.library');

$Itemid          = $app->input->get('Itemid');
$order_functions = order_functions::getInstance();
$order_items     = $order_functions->getOrderItemDetail($data['order_id']);
$order           = $order_functions->getOrderDetails($data['order_id']);
$hmac_key        = $this->params->get("hmac_key");
$language        = $this->params->get("dibs_languages");

if ($language == "Auto")
{
	$language = "en";
}

// For total amount
$amount       = 0;
$paytype      = $this->params->get('dibs_paytype', '');

// Authenticate vars to send
$formdata = array(
	'merchant'            => $this->params->get("seller_id"),
	'orderId'             => $data['order_id'],
	'currency'            => $this->params->get("dibs_currency"),
	'yourRef'             => $data['order_id'],
	'ourRef'              => $data['order_id'],
	'language'            => $language,
	'amount'              => $amount,
	'acceptReturnUrl'     => JURI::base() . "index.php?tmpl=component&option=com_redshop&view=order_detail&controller=order_detail&task=notify_payment&payment_plugin=dibsdx&Itemid=$Itemid&orderid=" . $data['order_id'],
	'cancelreturnurl'     => JURI::base() . "index.php?tmpl=component&option=com_redshop&view=order_detail&controller=order_detail&task=notify_payment&payment_plugin=dibsdx&Itemid=$Itemid&orderid=" . $data['order_id'],
	'callbackUrl'         => JURI::base() . "index.php?tmpl=component&option=com_redshop&view=order_detail&controller=order_detail&task=notify_payment&payment_plugin=dibsdx&Itemid=$Itemid&orderid=" . $data['order_id'],

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

	// Extra parameters
	'oiTypes'             => "QUANTITY;UNITCODE;DESCRIPTION;AMOUNT;ITEMID;VATAMOUNT",
	'oiNames'             => "Items;UnitCode;Description;Amount;ItemId;VatAmount"
);

if ($data['shippinginfo']->is_company)
{
	$groupPaytype = $this->params->get('paytype_business', '');
} else
{
	$groupPaytype = $this->params->get('paytype_private', '');
}

if (!empty($paytype) && empty($groupPaytype))
{
	$formdata['payType'] = $paytype;
} elseif (!empty($groupPaytype))
{
	$formdata['payType'] = $groupPaytype;
}

if ($this->params->get("instant_capture"))
{
	$formdata['captureNow'] = $this->params->get("instant_capture");
}

if ($this->params->get("is_test"))
{
	$formdata['test'] = 1;
}

for ($p = 0, $pn = count($order_items); $p < $pn; $p++)
{
	// Price conversion
	$product_item_price          = RedshopHelperCurrency::convert($order_items[$p]->product_item_price, '', $this->params->get("dibs_currency"));
	$product_item_price_excl_vat = RedshopHelperCurrency::convert($order_items[$p]->product_item_price_excl_vat, '', $this->params->get("dibs_currency"));
	$pvat                        = $product_item_price - $product_item_price_excl_vat;
	$product_item_price_excl_vat = floor($product_item_price_excl_vat * 1000) / 1000;
	$product_item_price_excl_vat = number_format($product_item_price_excl_vat, 2, '.', '') * 100;
	$pvat                        = floor($pvat * 1000) / 1000;
	$pvat                        = number_format($pvat, 2, '.', '') * 100;

	// Accumulate total
	$amount += ($product_item_price_excl_vat + $pvat) * $order_items[$p]->product_quantity;

	$formdata['oiRow' . ($p + 1) . ''] = $order_items[$p]->product_quantity
										. ";pcs"
										. ";" . trim($order_items[$p]->order_item_name)
										. ";" . $product_item_price_excl_vat
										. ";" . $order_items[$p]->product_id
										. ";" . $pvat;
}

if ($order->order_discount > 0)
{
	$quantity_discount = 1;
	$discount_amount = RedshopHelperCurrency::convert($order->order_discount, '', $this->params->get("dibs_currency"));
	$discount_amount = floor($discount_amount * 1000) / 1000;
	$discount_amount = number_format($discount_amount, 2, '.', '') * 100;
	$discount_amount = -$discount_amount;
	$discount_pvat = 0;

	$formdata['oiRow' . ($p + 1) . ''] = $quantity_discount
										. ";pcs"
										. ";Discount"
										. ";" . $discount_amount
										. ";" . ($p + 1)
										. ";" . $discount_pvat;
	$p++;
	$amount -= $discount_pvat + $discount_amount;
}

if ($order->order_shipping > 0)
{
	$quantity_shipping = 1;
	$order_shipping_tax = 0;

	if ($order->order_shipping_tax > 0 && $order->order_shipping_tax != null)
	{
		$order_shipping_tax = $order->order_shipping_tax;
	}

	$shipping_price = RedshopHelperCurrency::convert($order->order_shipping, '', $this->params->get("dibs_currency"));
	$shipping_vat   = RedshopHelperCurrency::convert($order_shipping_tax, '', $this->params->get("dibs_currency"));
	$shipping_price = floor($shipping_price * 1000) / 1000;
	$shipping_price = number_format($shipping_price, 2, '.', '') * 100;
	$shipping_vat   = floor($shipping_vat * 1000) / 1000;
	$shipping_vat   = number_format($shipping_vat, 2, '.', '') * 100;

	$formdata['oiRow' . ($p + 1) . ''] = $quantity_shipping
										. ";pcs"
										. ";Shipping"
										. ";" . ($shipping_price - $shipping_vat)
										. ";" . ($p + 1)
										. ";" . $shipping_vat;
	$p++;
	$amount += $shipping_price;
}

$payment_price = $order->payment_discount;

if ($payment_price > 0)
{
	$quantity_payment = 1;
	$payment_price    = RedshopHelperCurrency::convert($payment_price, '', $this->params->get("dibs_currency"));
	$payment_price    = floor($payment_price * 1000) / 1000;
	$payment_price    = number_format($payment_price, 2, '.', '') * 100;

	if ($order->payment_oprand == '-')
	{
		$discount_payment_price = -$payment_price;
	} else
	{
		$discount_payment_price = $payment_price;
	}

	$payment_vat = 0;

	$formdata['oiRow' . ($p + 1) . ''] = $quantity_payment
										. ";pcs"
										. ";Payment Handling"
										. ";" . $discount_payment_price
										. ";" . ($p + 1)
										. ";" . $payment_vat;

	$amount += $discount_payment_price + $payment_vat;
}

$formdata['amount'] = $amount;

include JPATH_SITE . '/plugins/redshop_payment/' . $element . '/' . $element . '/dibs_hmac.php';
$dibs_hmac = new dibs_hmac;
$mac_key   = $dibs_hmac->calculateMac($formdata, $hmac_key);

// Action URL
$dibsurl = "https://payment.dibspayment.com/dpw/entrypoint";
?>
<h2><?php echo JText::_('PLG_RS_PAYMENT_DIBSDX_WAIT_MESSAGE'); ?></h2>
<form action="<?php echo $dibsurl ?>" id='dibscheckout' name="dibscheckout" method="post" accept-charset="utf-8">
	<?php foreach ($formdata as $name => $value): ?>
	<input type="hidden" name="<?php echo $name ?>" value="<?php echo $value ?>"/>
	<?php endforeach; ?>
	<input type="hidden" name="MAC" value="<?php echo $mac_key ?>"/>
</form>
<script>
	document.getElementById("dibscheckout").submit();
</script>
