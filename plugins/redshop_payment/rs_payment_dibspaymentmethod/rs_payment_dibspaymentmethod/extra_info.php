<?php
/**
 * @package     RedSHOP
 * @subpackage  Plugin
 *
 * @copyright   Copyright (C) 2008 - 2016 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

defined('_JEXEC') or die();

JLoader::import('redshop.library');

$db    = JFactory::getDbo();
$query = $db->getQuery(true);

$query->select($db->qn('o.order_total'))
	->from($db->qn('#__redshop_order_payment', 'op'))
	->leftJoin($db->qn('#__redshop_orders', 'o') . ' ON ' . $db->qn('op.order_id') . ' = ' . $db->qn('o.order_id'))
	->where($db->qn('o.order_id') . ' = ' . $db->q($data['order_id']));

$db->setQuery($query);
$orderDetails = $db->loadObjectList();

$query = $db->getQuery(true);

$query->select($db->qn(['product_id', 'order_item_name', 'product_quantity', 'product_item_price']))
	->from($db->qn('#__redshop_order_item'))
	->where($db->qn('order_id') . ' = ' . $db->q($data['order_id']));
$db->setQuery($query);

$rs = $db->loadObjectlist();

// Authenticate vars to send
$formData = array(
	'merchant'          => $this->params->get("seller_id"),
	'orderid'           => $data['order_id'],
	'currency'          => $this->params->get("dibs_currency"),
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

for ($p = 0, $pn = count($rs); $p < $pn; $p++)
{
	$formData['ordline' . ($p + 1) . '-1'] = $rs[$p]->product_id;
	$formData['ordline' . ($p + 1) . '-2'] = $rs[$p]->order_item_name;
	$formData['ordline' . ($p + 1) . '-3'] = $rs[$p]->product_quantity;
	$formData['ordline' . ($p + 1) . '-4'] = $rs[$p]->product_item_price;
}

if ($this->params->get("is_test") == "1")
{
	$formData['test'] = "yes";
}

$version            = "2";
$dibsurl            = "https://payment.architrade.com/paymentweb/start.action";
$currencyClass      = CurrencyHelper::getInstance();
$formData['amount'] = $currencyClass->convert($orderDetails[0]->order_total, '', $this->params->get("dibs_currency"));
$formData['amount'] = number_format($formData['amount'], 2, '.', '') * 100;

if ($formData['flexlang'] == "Auto")
{
	$dibs_lang_arr = array(
		'Denmark'       => 'da',
		'Sweden'        => 'sv',
		'Norway'        => 'no',
		'Finland'       => 'fi',
		'Germany'       => 'de',
		'Netherlands'   => 'nl',
		'France'        => 'fr',
		'Spain'         => 'es',
		'Italy'         => 'it',
		'Faroe Islands' => 'fo'
	);

	if (isset($lang) && $lang != '')
	{
		$formData["lang"] = $lang;
	}
	else
	{
		$lang = 'en';
		$formData["lang"] = $lang;
	}
}

if ($formData['flexlang'] != "Auto")
{
	$formData["lang"] = $formData['flexlang'];
}

if ($formData["flexwin_decorator"] != "Own Decorator")
{
	$formData["decorator"] = $formData["flexwin_decorator"];
	$formData["color"]     = $formData["flexwin_color"];
}

if ($formData["md5key1"] != "" && $formData["md5key2"] != "")
{
	$md5key                    = md5($formData["md5key2"] . md5($formData["md5key1"] . 'merchant=' . $formData["merchant"] . '&orderid=' . $data['order_id'] . '&currency=' . $formData['currency'] . '&amount=' . $formData['amount']));
	$formData["md5key"]        = $md5key;
	$formData["dibs_uniqueid"] = 'yes';
}

// Build the post string
$postString = '';

$acceptUrl = JURI::base() . "index.php?option=com_redshop&view=order_detail&controller=order_detail&task=notify_payment&payment_plugin=rs_payment_dibspaymentmethod&orderid=" . $data['order_id'];

$cancelUrl = JURI::base() . "index.php?option=com_redshop&view=order_detail&controller=order_detail&task=notify_payment&payment_plugin=rs_payment_dibspaymentmethod&orderid=" . $data['order_id'];

include_once JPluginHelper::getLayoutPath('redshop_payment', 'rs_payment_dibspaymentmethod');
