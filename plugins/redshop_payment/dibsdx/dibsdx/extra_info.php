<?php
/**
 * @package     RedSHOP
 * @subpackage  Plugin
 *
 * @copyright   Copyright (C) 2008 - 2015 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

defined('_JEXEC') or die;

JLoader::import('redshop.library');

$app           = JFactory::getApplication();
$itemId        = $app->input->get('Itemid');
$orderFunction = order_functions::getInstance();
$orderItems    = RedshopHelperOrder::getOrderItemDetail($data['order_id']);
$order         = RedshopHelperOrder::getOrderDetail($data['order_id']);
$hmacKey       = $this->params->get("hmac_key");
$language      = $this->params->get("dibs_languages");
$language      = ($language == 'Auto') ? 'en' : $language;

// For total amount
$amount  = 0;
$payType = $this->params->get('dibs_paytype', '');

// Authenticate vars to send
$formData = array(
	'merchant'            => $this->params->get("seller_id"),
	'orderId'             => $data['order_id'],
	'currency'            => $this->params->get("dibs_currency"),
	'yourRef'             => $data['order_id'],
	'ourRef'              => $data['order_id'],
	'language'            => $language,
	'amount'              => $amount,
	'acceptReturnUrl'     => JUri::base() . 'index.php?tmpl=component&option=com_redshop&view=order_detail&controller=order_detail'
		. '&task=notify_payment&payment_plugin=dibsdx&Itemid=' . $itemId . '&orderid=' . $data['order_id'],
	'cancelreturnurl'     => JUri::base() . 'index.php?tmpl=component&option=com_redshop&view=order_detail&controller=order_detail'
		. '&task=notify_payment&payment_plugin=dibsdx&Itemid=' . $itemId . '&orderid=' . $data['order_id'],
	'callbackUrl'         => JUri::base() . 'index.php?tmpl=component&option=com_redshop&view=order_detail&controller=order_detail'
		. '&task=notify_payment&payment_plugin=dibsdx&Itemid=' . $itemId . '&orderid=' . $data['order_id'],

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
	$groupPayType = $this->params->get('paytype_business', '');
}
else
{
	$groupPayType = $this->params->get('paytype_private', '');
}

if (!empty($payType) && empty($groupPayType))
{
	$formData['payType'] = $payType;
}
elseif (!empty($groupPayType))
{
	$formData['payType'] = $groupPayType;
}

if ($this->params->get("instant_capture"))
{
	$formData['captureNow'] = $this->params->get("instant_capture");
}

if ($this->params->get("is_test"))
{
	$formData['test'] = 1;
}

for ($p = 0, $pn = count($orderItems); $p < $pn; $p++)
{
	// Price conversion
	$productItemPrice      = RedshopHelperCurrency::convert($orderItems[$p]->product_item_price, '', $this->params->get("dibs_currency"));
	$productItemPriceNoVat = RedshopHelperCurrency::convert($orderItems[$p]->product_item_price_excl_vat, '', $this->params->get("dibs_currency"));
	$productVAT            = $productItemPrice - $productItemPriceNoVat;
	$productItemPriceNoVat = floor($productItemPriceNoVat * 1000) / 1000;
	$productItemPriceNoVat = number_format($productItemPriceNoVat, 2, '.', '') * 100;
	$productVAT            = floor($productVAT * 1000) / 1000;
	$productVAT            = number_format($productVAT, 2, '.', '') * 100;

	// Accumulate total
	$amount += ($productItemPriceNoVat + $productVAT) * $orderItems[$p]->product_quantity;

	$formData['oiRow' . ($p + 1) . ''] = $orderItems[$p]->product_quantity
		. ";pcs"
		. ";" . trim($orderItems[$p]->order_item_name)
		. ";" . $productItemPriceNoVat
		. ";" . $orderItems[$p]->product_id
		. ";" . $productVAT;
}

if ($order->order_discount > 0)
{
	$quantityDiscount   = 1;
	$discountAmount     = RedshopHelperCurrency::convert($order->order_discount, '', $this->params->get("dibs_currency"));
	$discountAmount     = floor($discountAmount * 1000) / 1000;
	$discountAmount     = number_format($discountAmount, 2, '.', '') * 100;
	$discountAmount     = -$discountAmount;
	$discountProductVat = 0;

	$formData['oiRow' . ($p + 1) . ''] = $quantityDiscount
		. ";pcs"
		. ";Discount"
		. ";" . $discountAmount
		. ";" . ($p + 1)
		. ";" . $discountProductVat;
	$p++;
	$amount -= $discountProductVat + $discountAmount;
}

if ($order->order_shipping > 0)
{
	$quantityShipping = 1;
	$orderShippingTax = 0;

	if ($order->order_shipping_tax > 0 && $order->order_shipping_tax != null)
	{
		$orderShippingTax = $order->order_shipping_tax;
	}

	$shippingPrice = RedshopHelperCurrency::convert($order->order_shipping, '', $this->params->get("dibs_currency"));
	$shippingVat   = RedshopHelperCurrency::convert($orderShippingTax, '', $this->params->get("dibs_currency"));
	$shippingPrice = floor($shippingPrice * 1000) / 1000;
	$shippingPrice = number_format($shippingPrice, 2, '.', '') * 100;
	$shippingVat   = floor($shippingVat * 1000) / 1000;
	$shippingVat   = number_format($shippingVat, 2, '.', '') * 100;

	$formData['oiRow' . ($p + 1) . ''] = $quantityShipping
		. ";pcs"
		. ";Shipping"
		. ";" . ($shippingPrice - $shippingVat)
		. ";" . ($p + 1)
		. ";" . $shippingVat;

	$p++;

	$amount += $shippingPrice;
}

$paymentPrice = $order->payment_discount;

if ($paymentPrice > 0)
{
	$quantityPayment = 1;
	$paymentPrice    = RedshopHelperCurrency::convert($paymentPrice, '', $this->params->get("dibs_currency"));
	$paymentPrice    = floor($paymentPrice * 1000) / 1000;
	$paymentPrice    = number_format($paymentPrice, 2, '.', '') * 100;

	if ($order->payment_oprand == '-')
	{
		$discountPaymentPrice = -$paymentPrice;
	}
	else
	{
		$discountPaymentPrice = $paymentPrice;
	}

	$paymentVat = 0;

	$formData['oiRow' . ($p + 1) . ''] = $quantityPayment
		. ";pcs"
		. ";Payment Handling"
		. ";" . $discountPaymentPrice
		. ";" . ($p + 1)
		. ";" . $paymentVat;

	$amount += $discountPaymentPrice + $paymentVat;
}

$formData['amount'] = $amount;

include JPATH_SITE . '/plugins/redshop_payment/' . $element . '/' . $element . '/dibs_hmac.php';
$dibsHmac = new Dibs_Hmac;
$macKey   = $dibsHmac->calculateMac($formData, $hmacKey);

// Action URL
$dibsUrl = "https://payment.dibspayment.com/dpw/entrypoint";
?>
<h2><?php echo JText::_('PLG_RS_PAYMENT_DIBSDX_WAIT_MESSAGE'); ?></h2>
<form action="<?php echo $dibsUrl ?>" id='dibscheckout' name="dibscheckout" method="post" accept-charset="utf-8">
	<?php foreach ($formData as $name => $value): ?>
        <input type="hidden" name="<?php echo $name ?>" value="<?php echo $value ?>"/>
	<?php endforeach; ?>
    <input type="hidden" name="MAC" value="<?php echo $macKey ?>"/>
</form>
<script type="text/javascript">
    document.getElementById('dibscheckout').submit()
</script>
