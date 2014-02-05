<?php
/**
 * @package     RedSHOP
 * @subpackage  Plugin
 *
 * @copyright   Copyright (C) 2005 - 2013 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

require_once JPATH_COMPONENT . '/helpers/helper.php';
require_once JPATH_SITE . '/administrator/components/com_redshop/helpers/redshop.cfg.php';

$objOrder         = new order_functions;
$objconfiguration = new Redconfiguration;
$user             = JFactory::getUser();
$shipping_address = $objOrder->getOrderShippingUserInfo($data['order_id']);
$redhelper        = new redhelper;
$db               = JFactory::getDbo();
$user             = JFActory::getUser();
$task             = JRequest::getVar('task');
$app              = JFactory::getApplication();

$sql = "SELECT op.*,o.order_total,o.user_id,o.order_tax,o.order_subtotal,o.order_shipping,o.order_number,o.payment_discount FROM #__redshop_order_payment AS op LEFT JOIN #__redshop_orders AS o ON op.order_id = o.order_id  WHERE o.order_id='" . $data['order_id'] . "'";
$db->setQuery($sql);
$order_details = $db->loadObjectList();

// Buyer details
$buyeremail            = $data['billinginfo']->user_email;
$buyerfirstname        = $data['billinginfo']->firstname;
$buyerlastname         = $data['billinginfo']->lastname;
$CN                    = $buyerfirstname;
$ownerZIP              = $data['billinginfo']->zipcode;
$owneraddress          = $data['billinginfo']->address;
$ownercty              = $data['billinginfo']->city;
$currencyClass         = new CurrencyHelper;
$sha_out_pass_phrase   = $this->params->get("sha_in_pass_phrase");
$opreation_mode        = $this->params->get("opreation_mode");
$currency              = $this->params->get("currency");
$language              = $this->params->get("language");
$order->order_subtotal = round($currencyClass->convert($order_details[0]->order_total, '', $currency), 2) * 100;

$str = "ACCEPTURL=" . JURI::base() . "index.php?option=com_redshop&view=order_detail&controller=order_detail&task=notify_payment&payment_plugin=rs_payment_ogone" . $sha_out_pass_phrase . "AMOUNT=" . $order->order_subtotal . $sha_out_pass_phrase . "CANCELURL=" . JURI::base() . "index.php" . $sha_out_pass_phrase . "CN=" . $CN . $sha_out_pass_phrase . "CURRENCY=" . $currency . $sha_out_pass_phrase . "DECLINEURL=" . JURI::base() . "index.php?option=com_redshop&view=order_detail&controller=order_detail&task=notify_payment&payment_plugin=rs_payment_ogone" . $sha_out_pass_phrase . "EMAIL=" . $buyeremail . $sha_out_pass_phrase . "LANGUAGE=" . $language . $sha_out_pass_phrase . "OPERATION=" . $opreation_mode . $sha_out_pass_phrase . "ORDERID=" . $data['order_id'] . $sha_out_pass_phrase . "OWNERADDRESS=" . $owneraddress . $sha_out_pass_phrase . "OWNERCTY=" . $ownercty . $sha_out_pass_phrase . "OWNERZIP=" . $ownerZIP . $sha_out_pass_phrase . "PSPID=" . $this->params->get("ogone_pspid") . $sha_out_pass_phrase . "USERID=" . $this->params->get("ogone_userid") . $sha_out_pass_phrase;

$shasign = sha1($str);


// End

if ($this->params->get("is_test") == '1')
{
	$actionurl = "https://secure.ogone.com/ncol/test/orderstandard.asp";
}
else
{
	$actionurl = "https://secure.ogone.com/ncol/prod/orderstandard.asp";
}

$post_variables = Array(
	"PSPID"        => $this->params->get("ogone_pspid"),
	"orderID"      => $data['order_id'],
	"amount"       => $order->order_subtotal,
	"currency"     => $currency,
	"language"     => $language,
	"CN"           => $CN,
	"EMAIL"        => $buyeremail,
	"ownerZIP"     => $ownerZIP,
	"owneraddress" => $owneraddress,
	"ownercty"     => $ownercty,
	"accepturl"    => JURI::base() . "index.php?option=com_redshop&view=order_detail&controller=order_detail&task=notify_payment&payment_plugin=rs_payment_ogone",
	"declineurl"   => JURI::base() . "index.php?option=com_redshop&view=order_detail&controller=order_detail&task=notify_payment&payment_plugin=rs_payment_ogone",
	"cancelurl"    => JURI::base() . "index.php",
	"SHASign"      => $shasign,
	"operation"    => $opreation_mode,
	"USERID"       => $this->params->get("ogone_userid"),

);

echo "<form action='$actionurl' method='post' name='ogoneform' id='ogoneform'>";

foreach ($post_variables as $name => $value)
{
	echo "<input type='hidden' name='$name' value='$value' />";
}

echo "</form>";
?>
<script type='text/javascript'>document.ogoneform.submit();</script>
