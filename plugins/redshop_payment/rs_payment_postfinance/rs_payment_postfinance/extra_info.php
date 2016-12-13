<?php
/**
 * @package     RedSHOP
 * @subpackage  Plugin
 *
 * @copyright   Copyright (C) 2008 - 2015 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

JLoader::import('redshop.library');

$db 				= JFactory::getDbo();
$app 				= JFactory::getApplication();
$input              = $app->input;
$task 				= $input->get('task');

$query = $db->getQuery(true);

$query->select(
	[
		'op.*',
		$db->qn('o.order_total'), $db->qn('o.user_id'), $db->qn('o.order_tax'),
		$db->qn('o.order_subtotal'), $db->qn('o.order_shipping'), $db->qn('o.order_number'),
		$db->qn('o.payment_discount')
	]
)
->from($db->qn('#__redshop_order_payment', 'op'))
->leftJoin($db->qn('#__redshop_orders', 'o') . ' ON ' . $db->qn('op.order_id') . ' = ' . $db->qn('o.order_id'))
->where($db->qn('o.order_id') . ' = ' . $db->q((int) $data['order_id']));

$db->setQuery($query);
$order_details = $db->loadObjectList();

$buyerEmail     = $data['billinginfo']->user_email;
$buyerFirstName = $data['billinginfo']->firstname;
$buyerLastName  = $data['billinginfo']->lastname;
$cn             = $buyerFirstName . "&nbsp;" . $buyerLastName;
$ownerZip       = $data['billinginfo']->zipcode;
$ownerAddress   = $data['billinginfo']->address;
$ownerCty       = $data['billinginfo']->city;

if ($this->params->get("is_test") == '1')
{
	$postfinanceurl = "https://e-payment.postfinance.ch/ncol/test/orderstandard.asp";
}
else
{
	$postfinanceurl = "https://e-payment.postfinance.ch/ncol/prod/orderstandard.asp";
}

$currencyClass = CurrencyHelper::getInstance();

$order->order_subtotal = round($currencyClass->convert($order_details[0]->order_total, '', 'USD'), 2) * 100;

$postVariables = Array(
	"orderID"      => $data['order_id'],
	"currency"     => "USD",
	"PSPID"        => $this->params->get("postpayment_shopid"),
	"amount"       => $order->order_subtotal,
	"language"     => "en_US",
	"CN"           => $cn,
	"EMAIL"        => $buyerEmail,
	"ownerZIP"     => $ownerZip,
	"owneraddress" => $ownerAddress,
	"ownercty"     => $ownerCty,
	"accepturl"    => JURI::base() . "index.php?option=com_redshop&view=order_detail&controller=order_detail&task=notify_payment&payment_plugin=rs_payment_postfinance",
	"declineurl"   => JURI::base() . "index.php?option=com_redshop&view=order_detail&controller=order_detail&task=notify_payment&payment_plugin=rs_payment_postfinance",
	"exceptionurl" => "http://www.google.com",
	"cancelurl"    => JURI::base() . "index.php"
);

require_once JPluginHelper::getLayoutPath('redshop_payment', 'rs_payment_postfinance');
