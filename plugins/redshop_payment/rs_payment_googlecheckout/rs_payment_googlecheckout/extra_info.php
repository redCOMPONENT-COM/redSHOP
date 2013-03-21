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

include_once (JPATH_COMPONENT_ADMINISTRATOR . DS . 'helpers' . DS . 'shipping.php');
$shippinghelper = new shipping;

$currencyClass = new convertPrice;

$currency_code = "USD"; // currency accepted by google

$order = $data['order'];

$db = JFactory::getDBO();

$q = "SELECT * FROM " . $this->_table_prefix . "order_item WHERE order_id=" . $order->order_id;

$db->setQuery($q);

$rs = $db->loadObjectlist();

$url = JURI::root();

// Include all the required files

require_once 'library' . DS . 'googlecart.php';

require_once 'library' . DS . 'googleitem.php';

require_once 'library' . DS . 'googleshipping.php';

require_once 'library' . DS . 'googletax.php';

$servertype = $this->_params->get("is_test", "sandbox");

$merchantid = $this->_params->get("merchant_id", "");

$merchantkey = $this->_params->get("merchant_key", "");

$buttonsize = $this->_params->get("button_size", "medium");

$buttonstyle = $this->_params->get("button_style", "white");

if ($buttonsize == "medium")
{
	$width = "168";

	$height = "44";
}

elseif ($buttonsize == "small")
{
	$width = "160";

	$height = "43";
}

elseif ($buttonsize == "large")
{
	$width = "180";

	$height = "46";

}

$conurl = $url . "index.php?option=com_redshop&view=order_detail&oid=" . $order->order_id;

$editurl = $url . "index.php?option=com_redshop&view=cart";

/*

 * changing start

 */

$merchant_id = $this->_params->get("merchant_id", ""); // Your Merchant ID

$merchant_key = $this->_params->get("merchant_key", ""); // Your Merchant Key

$server_type = $this->_params->get("is_test", "sandbox");

$currency = "USD";

$cart = new GoogleCart ($merchant_id, $merchant_key, $server_type, $currency);

/*

 * add product items

 */

for ($p = 0; $p < count($rs); $p++)
{
	$item_price = $currencyClass->convert($rs [$p]->product_item_price, '', $currency_code);

	$item = new GoogleItem ($rs [$p]->order_item_name, // Item name

		$order->order_id, // Item      description

		$rs [$p]->product_quantity, // Quantity

		$item_price); // Unit price

	$cart->AddItem($item);

}

$discount_price = (0 - $currencyClass->convert($order->order_discount, '', $currency_code));

$disoucnt_item = new GoogleItem (JText::_('COM_REDSHOP_DISCOUNT'), // Item name

	"", // Item      description

	1, // Quantity

	$discount_price); // Unit price

if ($discount_price > 0)
	$cart->AddItem($disoucnt_item);

$cart->SetMerchantPrivateData(

	new MerchantPrivateData (array("shopping-cart.merchant-private-data" => $order->order_id)));

// Add shipping options

$shipping_method_name = explode("|", $shippinghelper->decryptShipping($order->ship_method_id));

if (isset ($shipping_method_name [1]) && $shipping_method_name [1] != "")
{
	$shipping_price = $currencyClass->convert($order->order_shipping, '', $currency_code);

	$ship_1 = new GoogleFlatRateShipping ($shipping_method_name [1], $shipping_price);

	$cart->AddShipping($ship_1);

}

// Specify "Return to xyz" link

$cart->SetContinueShoppingUrl($conurl);

// Request buyer's phone number

$cart->SetRequestBuyerPhone(false);

// Display Google Checkout button

echo $cart->CheckoutButtonCode(strtoupper($buttonsize));

/*

 * changing end

 */
?>
