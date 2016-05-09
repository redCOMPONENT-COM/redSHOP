<?php
/**
 * @package     RedSHOP
 * @subpackage  Plugin
 *
 * @copyright   Copyright (C) 2008 - 2015 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

JLoader::import('redshop.library');

$objOrder         = order_functions::getInstance();
$objconfiguration = Redconfiguration::getInstance();
$user             = JFactory::getUser();
$shipping_address = RedshopHelperOrder::getOrderShippingUserInfo($data['order_id']);
$redhelper        = redhelper::getInstance();
$db               = JFactory::getDbo();
$user             = JFActory::getUser();
$task             = JRequest::getCmd('task');
$app              = JFactory::getApplication();

// Redirection after full page load
JHtml::_('redshopjquery.framework');
$document = JFactory::getDocument();
$document->addScriptDeclaration(
	'jQuery(document).ready(function(){
		jQuery("#ingenicoform").submit();
	});'
);

$query = $db->getQuery(true)
	->select('op.*, o.order_total, o.user_id, o.order_tax, o.order_subtotal, o.order_shipping, o.order_number, o.payment_discount')
	->from($db->qn('#__redshop_order_payment', 'op'))
	->leftJoin($db->qn('#__redshop_orders', 'o') . ' ON op.order_id = o.order_id')
	->where('o.order_id = ' . $db->q($data['order_id']));
$order_details = $db->setQuery($query)->loadObject();

$currencyClass         = CurrencyHelper::getInstance();
$sha_out_pass_phrase   = $this->params->get("sha_in_pass_phrase");
$opreation_mode        = $this->params->get("opreation_mode");
$currency              = $this->params->get("currency");
$language              = $this->params->get("language");

$orderSubtotal = round($currencyClass->convert($order_details->order_total, '', $currency), 2) * 100;
$linkForReturn = JURI::base()
	. "index.php?option=com_redshop&view=order_detail&controller=order_detail&task=notify_payment&payment_plugin=rs_payment_ingenico";

$post_variables = array(
	"PSPID"        => $this->params->get("ingenico_pspid"),
	"ORDERID"      => $data['order_id'],
	"AMOUNT"       => $orderSubtotal,
	"CURRENCY"     => $currency,
	"LANGUAGE"     => $language,
	"ACCEPTURL"    => $linkForReturn,
	"DECLINEURL"   => $linkForReturn,
	"CANCELURL"    => $linkForReturn,
	"OPERATION"    => $opreation_mode
);

if ($ingenico_userid = $this->params->get("ingenico_userid"))
{
	$post_variables['USERID'] = $ingenico_userid;
}

if ($ownercty = $data['billinginfo']->city)
{
	$post_variables['OWNERCTY'] = $ownercty;
}

if ($owneraddress = $data['billinginfo']->address)
{
	$post_variables['OWNERADDRESS'] = $owneraddress;
}

if ($buyeremail = $data['billinginfo']->user_email)
{
	$post_variables['EMAIL'] = $buyeremail;
}

if ($ownerZIP = $data['billinginfo']->zipcode)
{
	$post_variables['OWNERZIP'] = $ownerZIP;
}

if ($buyerfirstname = $data['billinginfo']->firstname)
{
	$post_variables['CN'] = $buyerfirstname;
}

ksort($post_variables);
$str = '';

foreach ($post_variables as $key => $variable)
{
	$str .= $key . '=' . $variable . $sha_out_pass_phrase;
}

$shasign = sha1($str);

if ($this->params->get("is_test") == '1')
{
	$actionurl = "https://secure.ogone.com/ncol/test/orderstandard.asp";
}
else
{
	$actionurl = "https://secure.ogone.com/ncol/prod/orderstandard.asp";
}

$post_variables['SHASIGN'] = $shasign;
echo "<h3>" . JText::_('PLG_RS_PAYMENT_INGENICO_WAIT_MESSAGE') . "</h3>";
echo "<form action='$actionurl' method='post' name='ingenicoform' id='ingenicoform'>";

foreach ($post_variables as $name => $value)
{
	echo "<input type='hidden' name='$name' value='$value' />";
}
