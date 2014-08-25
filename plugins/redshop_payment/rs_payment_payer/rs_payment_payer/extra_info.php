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
JLoader::import('LoadHelpers', JPATH_SITE . '/components/com_redshop');
JLoader::load('RedshopHelperProduct');
$producthelper = new producthelper;
$order_functions = new order_functions;
$currencyClass = new CurrencyHelper;
$user = JFactory::getUser();

// Order Details
$orderId = $data['order_id'];
$order = $order_functions->getOrderDetails($orderId);
$orderItemDetails = $order_functions->getOrderItemDetail($orderId);
// End

if ($this->_params->get("currency") != "")
{
	$currency_main = $this->_params->get("currency");
}
else if (CURRENCY_CODE != "")
{
	$currency_main = CURRENCY_CODE;
}
else
{
	$currency_main = "USD";
}

include JPATH_SITE . '/plugins/redshop_payment/' . $plugin . '/' . $plugin . '/payread_post_api.php'; //Loads Payers API.
$thePayreadApi = new payread_post_api; //Creates an object from Payers API.
$thePayreadApi->add_valid_ip($_SERVER["REMOTE_ADDR"]);
$thePayreadApi->setAgent($this->_params->get("agent_id"));
$thePayreadApi->setKeys($this->_params->get("payer_key1"), $this->_params->get("payer_key2"));
$Auth_url = JURI::base() . "index.php?tmpl=component&option=com_redshop&view=order_detail&controller=order_detail&task=notify_payment&payment_plugin=rs_payment_payer&orderid=" . $data['order_id'];
$Settle_url = JURI::base() . "index.php?tmpl=component&option=com_redshop&view=order_detail&controller=order_detail&task=notify_payment&payment_plugin=rs_payment_payer&orderid=" . $data['order_id'];
$Shop_url = JURI::base() . "index.php?option=com_redshop&view=order_detail&layout=receipt&oid=" . $data['order_id'];
$thePayreadApi->set_authorize_notification_url($Auth_url);
$thePayreadApi->set_settle_notification_url($Settle_url);
$thePayreadApi->set_redirect_back_to_shop_url($Shop_url);

//Adds purchasing information
$thePayreadApi->add_buyer_info($data['billinginfo']->firstname,
	$data['billinginfo']->lastname,
	$data['billinginfo']->address,
	'',
	$data['billinginfo']->zipcode,
	$data['billinginfo']->city,
	$data['billinginfo']->country_2_code,
	$data['billinginfo']->phone,
	'',
	'',
	$data['billinginfo']->user_email);

//Loops through all the goods/services and info lines.
for ($i = 0; $i < count($orderItemDetails); $i++)
{
	$product_item_price = $currencyClass->convert($orderItemDetails[$i]->product_item_price, '', $currency_main);
	$product_item_price_excl_vat = $currencyClass->convert($orderItemDetails[$i]->product_item_price_excl_vat, '', $currency_main);

	$vat = $product_item_price - $product_item_price_excl_vat;
	$vat = $currencyClass->convert($vat, '', $currency_main);
	$thePayreadApi->add_freeform_purchase($i + 1,
		$orderItemDetails[$i]->order_item_name,
		$product_item_price,
		$vat,
		$orderItemDetails[$i]->product_quantity);
}

if ($order->order_shipping > 0)
{
	$i++;
	$order_shipping_tax = 0;

	if ($order->order_shipping_tax > 0 && $order->order_shipping_tax != null)
	{
		$order_shipping_tax = $order->order_shipping_tax;
		$order_shipping_tax = $currencyClass->convert($order_shipping_tax, '', $currency_main);
	}

	$order_shipping = $currencyClass->convert($order->order_shipping, '', $currency_main);

	$thePayreadApi->add_freeform_purchase($i + 1,
		"Order Shipping",
		$order_shipping,
		$order_shipping_tax,
		1);
}

if ($order->order_discount > 0)
{
	$i++;
	$order_discount = $currencyClass->convert($order->order_discount, '', $currency_main);
	$thePayreadApi->add_freeform_purchase($i + 1,
		"Order Discount",
		-$order_discount,
		0,
		1);
}
//Determines method of payment.
$thePayreadApi->add_payment_method("card");

//Determines the language in payment box sv swedish, en english, fi finish, no norwegian och dk denmark
$thePayreadApi->set_language($this->_params->get("language"));

//Determines the currency, ie. SEK, EUR, GBR, USD, NOK, CAD (Canadian Dollar) or DKK.
$thePayreadApi->set_currency($currency_main);

if ($this->_params->get("is_test") == 0)
{
	$thePayreadApi->set_test_mode(false);
}
else
{
	$thePayreadApi->set_test_mode(true);
}

?>

<form action="<?= $thePayreadApi->get_server_url() ?>" method="post" name="payerfrm" id="payerfrm">
	<? $thePayreadApi->generate_form(); ?>

</form>
<script type='text/javascript'>document.payerfrm.submit();</script>
