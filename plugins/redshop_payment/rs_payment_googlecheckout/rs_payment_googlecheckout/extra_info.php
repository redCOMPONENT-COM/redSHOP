<?php
/**
 * @package     RedSHOP
 * @subpackage  Plugin
 *
 * @copyright   Copyright (C) 2008 - 2015 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

JLoader::import('redshop.library');

// Currency accepted by Google
$currency_code = "USD";
$order         = $data['order'];

$db = JFactory::getDbo();
$q = "SELECT * FROM #__redshop_order_item WHERE order_id=" . $order->order_id;
$db->setQuery($q);
$rs = $db->loadObjectlist();

$url = JURI::root();

// Include all the required files
require_once 'library/googlecart.php';
require_once 'library/googleitem.php';
require_once 'library/googleshipping.php';
require_once 'library/googletax.php';

$servertype  = $this->params->get("is_test", "sandbox");
$merchantid  = $this->params->get("merchant_id", "");
$merchantkey = $this->params->get("merchant_key", "");
$buttonsize  = $this->params->get("button_size", "medium");
$buttonstyle = $this->params->get("button_style", "white");

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

// Changing start
// Your Merchant ID
$merchant_id = $this->params->get("merchant_id", "");

// Your Merchant Key
$merchant_key = $this->params->get("merchant_key", "");
$server_type = $this->params->get("is_test", "sandbox");

$currency = "USD";
$cart = new GoogleCart($merchant_id, $merchant_key, $server_type, $currency);

// Add product items
for ($p = 0, $pn = count($rs); $p < $pn; $p++)
{
	$item_price = RedshopHelperCurrency::convert($rs [$p]->product_item_price, '', $currency_code);

	$item = new GoogleItem(
		// Item name
		$rs [$p]->order_item_name,
		// Item description
		$order->order_id,
		// Quantity
		$rs [$p]->product_quantity,
		// Unit price
		$item_price
	);

	$cart->AddItem($item);
}

$discount_price = (0 - RedshopHelperCurrency::convert($order->order_discount, '', $currency_code));

$disoucnt_item = new GoogleItem(
	// Item name
	JText::_('COM_REDSHOP_DISCOUNT'),
	// Item description
	"",
	// Quantity
	1,
	// Unit price
	$discount_price
);

if ($discount_price > 0)
{
	$cart->AddItem($disoucnt_item);
}

$cart->SetMerchantPrivateData(
	new MerchantPrivateData(
		array("shopping-cart.merchant-private-data" => $order->order_id)
	)
);

// Add shipping options
$shipping_method_name = RedshopHelperShipping::decryptShipping($order->ship_method_id);

if (isset ($shipping_method_name [1]) && $shipping_method_name [1] != "")
{
	$shipping_price = RedshopHelperCurrency::convert($order->order_shipping, '', $currency_code);
	$ship_1 = new GoogleFlatRateShipping($shipping_method_name [1], $shipping_price);

	$cart->AddShipping($ship_1);
}

// Specify "Return to xyz" link
$cart->SetContinueShoppingUrl($conurl);

// Request buyer's phone number
$cart->SetRequestBuyerPhone(false);

// Display Google Checkout button
echo $cart->CheckoutButtonCode(strtoupper($buttonsize));
