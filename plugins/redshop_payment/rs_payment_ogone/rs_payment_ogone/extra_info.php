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

require_once JPATH_SITE . '/administrator/components/com_redshop/helpers/redshop.cfg.php';
JLoader::import('loadhelpers', JPATH_SITE . '/components/com_redshop');
JLoader::load('RedshopHelperHelper');
$objOrder = new order_functions;

$objconfiguration = new Redconfiguration;

$user = JFactory::getUser();
$shipping_address = $objOrder->getOrderShippingUserInfo($data['order_id']);

$redhelper = new redhelper;
$db = JFactory::getDbo();
$user = JFActory::getUser();
$task = JRequest::getVar('task');
$app = JFactory::getApplication();

$sql = "SELECT op.*,o.order_total,o.user_id,o.order_tax,o.order_subtotal,o.order_shipping,o.order_number,o.payment_discount FROM " . $this->_table_prefix . "order_payment AS op LEFT JOIN " . $this->_table_prefix . "orders AS o ON op.order_id = o.order_id  WHERE o.order_id='" . $data['order_id'] . "'";
$db->setQuery($sql);
$order_details = $db->loadObjectList();

// buyer details

$buyeremail = $data['billinginfo']->user_email;
$buyerfirstname = $data['billinginfo']->firstname;
$buyerlastname = $data['billinginfo']->lastname;
$CN = $buyerfirstname;
$ownerZIP = $data['billinginfo']->zipcode;
$owneraddress = $data['billinginfo']->address;
$ownercty = $data['billinginfo']->city;
$currencyClass = new CurrencyHelper;
$sha_out_pass_phrase = $this->_params->get("sha_in_pass_phrase");
$opreation_mode = $this->_params->get("opreation_mode");
$currency = $this->_params->get("currency");
$language = $this->_params->get("language");
$order->order_subtotal = round($currencyClass->convert($order_details[0]->order_total, '', $currency), 2) * 100;

$str = "ACCEPTURL=" . JURI::base() . "index.php?option=com_redshop&view=order_detail&controller=order_detail&task=notify_payment&payment_plugin=rs_payment_ogone" . $sha_out_pass_phrase . "AMOUNT=" . $order->order_subtotal . $sha_out_pass_phrase . "CANCELURL=" . JURI::base() . "index.php" . $sha_out_pass_phrase . "CN=" . $CN . $sha_out_pass_phrase . "CURRENCY=" . $currency . $sha_out_pass_phrase . "DECLINEURL=" . JURI::base() . "index.php?option=com_redshop&view=order_detail&controller=order_detail&task=notify_payment&payment_plugin=rs_payment_ogone" . $sha_out_pass_phrase . "EMAIL=" . $buyeremail . $sha_out_pass_phrase . "LANGUAGE=" . $language . $sha_out_pass_phrase . "OPERATION=" . $opreation_mode . $sha_out_pass_phrase . "ORDERID=" . $data['order_id'] . $sha_out_pass_phrase . "OWNERADDRESS=" . $owneraddress . $sha_out_pass_phrase . "OWNERCTY=" . $ownercty . $sha_out_pass_phrase . "OWNERZIP=" . $ownerZIP . $sha_out_pass_phrase . "PSPID=" . $this->_params->get("ogone_pspid") . $sha_out_pass_phrase . "USERID=" . $this->_params->get("ogone_userid") . $sha_out_pass_phrase;

$shasign = sha1($str);


// End

if ($this->_params->get("is_test") == '1')
{
	$actionurl = "https://secure.ogone.com/ncol/test/orderstandard.asp";
}
else
{
	$actionurl = "https://secure.ogone.com/ncol/prod/orderstandard.asp";
}

$post_variables = Array(
	"PSPID"        => $this->_params->get("ogone_pspid"),
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
	"USERID"       => $this->_params->get("ogone_userid"),

);



echo "<form action='$actionurl' method='post' name='ogoneform' id='ogoneform'>";

foreach ($post_variables as $name => $value)
{
	echo "<input type='hidden' name='$name' value='$value' />";
}

echo "</form>";

// end by me

?>
<script type='text/javascript'>document.ogoneform.submit();</script>