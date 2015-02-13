<?php
/**
 * @package     RedSHOP
 * @subpackage  Plugin
 *
 * @copyright   Copyright (C) 2008 - 2015 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

$uri  = JURI::getInstance();
$url  = $uri->root();
$user = JFactory::getUser();
$db   = JFactory::getDbo();

JLoader::import('redshop.library');
JLoader::load('RedshopHelperAdminOrder');
$request = JRequest::get('REQUEST');
$task = $request['task'];
$Itemid = $_REQUEST['Itemid'];
$orderHelper = new order_functions;
$rs          = $orderHelper->getOrderItemDetail($data['order_id']);

// Authenticate vars to send
$formdata = array(
	'merchant'          => $this->params->get("seller_id"),
	'capturenow'        => $this->params->get("instant_capture"),
	'orderid'           => $data['order_id'],
	'currency'          => $this->params->get("dibs_currency"),
	'accepturl'         => JURI::base() . "index.php?tmpl=component&option=com_redshop&view=order_detail&controller=order_detail&task=notify_payment&status=ok&payment_plugin=rs_payment_dibs&Itemid=$Itemid&orderid=" . $data['order_id'],
	'cancelurl'         => JURI::base() . "index.php?tmpl=component&option=com_redshop&view=order_detail&controller=order_detail&task=notify_payment&status=cancel&payment_plugin=rs_payment_dibs&Itemid=$Itemid&orderid=" . $data['order_id'],
	'ip'                => $_SERVER['REMOTE_ADDR'],

	// Customer Shipping Address

	'delivery1.Name'    => $data['shippinginfo']->firstname . " " . $data['shippinginfo']->lastname,
	'delivery2.Address' => $data['shippinginfo']->address . "," . $data['shippinginfo']->city . "," . $data['shippinginfo']->state_code . "," . $data['shippinginfo']->country_code,

	// Order detail
	'ordline0-1'        => 'Product Id',
	'ordline0-2'        => 'Product Name',
	'ordline0-3'        => 'Quantity',
	'ordline0-4'        => 'Price',

	// Extra parameters
	'flexlang'          => $this->params->get("dibs_languages"),
	'pay_type'          => $this->params->get("dibs_pay_type"),
	'flexwin_color'     => $this->params->get("dibs_color"),
	'flexwin_decorator' => $this->params->get("dibs_flexwin_decorator"),
	'md5key1'           => $this->params->get("dibs_md5key1"),
	'md5key2'           => $this->params->get("dibs_md5key2"),
	'dibs_uniqueid'     => $this->params->get("dibs_uniqueid"),
	'forcecurrency'     => $this->params->get("dibs_forcecurrency")
);

if ($this->params->get("instant_capture") != 1)
{
	unset($formdata['capturenow']);
}

for ($p = 0; $p < count($rs); $p++)
{
	$formdata['ordline' . ($p + 1) . '-1'] = $rs[$p]->product_id;
	$formdata['ordline' . ($p + 1) . '-2'] = $rs[$p]->order_item_name;
	$formdata['ordline' . ($p + 1) . '-3'] = $rs[$p]->product_quantity;
	$formdata['ordline' . ($p + 1) . '-4'] = $rs[$p]->product_item_price;
}

/* extra info */

if ($this->params->get("is_test") == "1")
{
	$formdata['test'] = "yes";
}

$version = "2";
$dibsurl = "https://payment.architrade.com/paymentweb/start.action";
$currencyClass = new CurrencyHelper;
$formdata['amount'] = $currencyClass->convert($data['carttotal'], '', $this->params->get("dibs_currency"));

// For total amount
$cal_no = 2;

if (defined('PRICE_DECIMAL'))
{
	$cal_no = PRICE_DECIMAL;
}

$formdata['amount'] = round($formdata['amount'], $cal_no);
$formdata['amount'] = number_format($formdata['amount'], 2, '.', '') * 100;

if ($formdata['flexlang'] == "Auto")
{
	$formdata["lang"] = 'en';
}
else
{
	$formdata["lang"] = $formdata['flexlang'];
}

if ($formdata["flexwin_decorator"] != "Own Decorator")
{
	$formdata["decorator"] = $formdata["flexwin_decorator"];
	$formdata["color"] = $formdata["flexwin_color"];
}

if ($formdata["md5key1"] != "" && $formdata["md5key2"] != "")
{
	$md5key = md5($formdata["md5key2"] . md5($formdata["md5key1"] . 'merchant=' . $formdata["merchant"] . '&orderid=' . $data['order_id'] . '&currency=' . $formdata['currency'] . '&amount=' . $formdata['amount']));
	$formdata["md5key"] = $md5key;
	$formdata["dibs_uniqueid"] = 'yes';
}
?>
<form action="<?php echo $dibsurl ?>" id='dibscheckout' name="dibscheckout" method="post" accept-charset="iso-8859-1">
	<?php foreach ($formdata as $name => $value): ?>
		<input type="hidden" name="<?php echo $name ?>" value="<?php echo urlencode($value) ?>"/>
	<?php endforeach; ?>
</form>
<script>
	document.getElementById("dibscheckout").submit();
</script>
