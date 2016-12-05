<?php
/**
 * @package     RedSHOP
 * @subpackage  Plugin
 *
 * @copyright   Copyright (C) 2008 - 2015 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

JLoader::import('redshop.library');

$shippinghelper = shipping::getInstance();
$currencyClass  = CurrencyHelper::getInstance();

// Currency accepted by Google
$currencyCode = "USD";
$order        = $data['order'];

$db = JFactory::getDbo();
$query = $db->getQuery(true);
$query->select('*')
	->from($db->qn('#__redshop_order_item'))
	->where($db->qn('order_id') . ' = ' . $db->q($order->order_id));

$db->setQuery($query);
$rs = $db->loadObjectlist();

$url = JURI::root();

// Include all the required files
require_once 'library/googlecart.php';
require_once 'library/googleitem.php';
require_once 'library/googleshipping.php';
require_once 'library/googletax.php';

$buttonSize  = $this->params->get("button_size", "medium");
$buttonStyle = $this->params->get("button_style", "white");

switch ($buttonSize)
{
	case 'large':
		$width = "180";
		$height = "46";
		break;
	case 'small':
		$width = "160";
		$height = "43";
		break;
	case 'medium':
	default:
		$width = "168";
		$height = "44";
		break;
}

$connectUrl = $url . "index.php?option=com_redshop&view=order_detail&oid=" . $order->order_id;
$editUrl = $url . "index.php?option=com_redshop&view=cart";

// Changing start
// Your Merchant ID
$merchantId = $this->params->get("merchant_id", "");

// Your Merchant Key
$merchantKey = $this->params->get("merchant_key", "");
$serverType = $this->params->get("is_test", "sandbox");

$currency = "USD";
$cart = new GoogleCart($merchantId, $merchantKey, $serverType, $currency);

// Add product items
for ($p = 0, $pn = count($rs); $p < $pn; $p++)
{
	$itemPrice = $currencyClass->convert($rs [$p]->product_item_price, '', $currencyCode);

	$item = new GoogleItem(
		// Item name
		$rs [$p]->order_item_name,
		// Item description
		$order->order_id,
		// Quantity
		$rs [$p]->product_quantity,
		// Unit price
		$itemPrice
	);

	$cart->AddItem($item);
}

$discountPrice = (0 - $currencyClass->convert($order->order_discount, '', $currencyCode));

$discountItem = new GoogleItem(
	// Item name
	JText::_('COM_REDSHOP_DISCOUNT'),
	// Item description
	"",
	// Quantity
	1,
	// Unit price
	$discountPrice
);

if ($discountPrice > 0)
{
	$cart->AddItem($discountItem);
}

$cart->SetMerchantPrivateData(
	new MerchantPrivateData(
		array("shopping-cart.merchant-private-data" => $order->order_id)
	)
);

// Add shipping options
$shippingMethodName = explode("|", $shippinghelper->decryptShipping($order->ship_method_id));

if (isset ($shippingMethodName [1]) && $shippingMethodName [1] != "")
{
	$shippingPrice = $currencyClass->convert($order->order_shipping, '', $currencyCode);
	$shipping = new GoogleFlatRateShipping($shippingMethodName [1], $shippingPrice);

	$cart->AddShipping($shipping);
}

// Specify "Return to xyz" link
$cart->SetContinueShoppingUrl($connectUrl);

// Request buyer's phone number
$cart->SetRequestBuyerPhone(false);

// Display Google Checkout button
echo $cart->CheckoutButtonCode(strtoupper($buttonSize));
