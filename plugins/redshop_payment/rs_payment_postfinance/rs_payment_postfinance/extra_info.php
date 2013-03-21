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


require_once(JPATH_COMPONENT . DS . 'helpers' . DS . 'helper.php');
require_once (JPATH_SITE . DS . 'administrator' . DS . 'components' . DS . 'com_redshop' . DS . 'helpers' . DS . 'redshop.cfg.php');
$objOrder = new order_functions();

$objconfiguration = new Redconfiguration();

$user = JFactory::getUser();
$shipping_address = $objOrder->getOrderShippingUserInfo($data['order_id']);


$redhelper = new redhelper();
$db = JFactory::getDBO();
$user = JFActory::getUser();
$task = JRequest::getVar('task');
$mainframe =& JFactory::getApplication();

$sql = "SELECT op.*,o.order_total,o.user_id,o.order_tax,o.order_subtotal,o.order_shipping,o.order_number,o.payment_discount FROM " . $this->_table_prefix . "order_payment AS op LEFT JOIN " . $this->_table_prefix . "orders AS o ON op.order_id = o.order_id  WHERE o.order_id='" . $data['order_id'] . "'";
$db->setQuery($sql);
$order_details = $db->loadObjectList();

// buyer details

$buyeremail = $data['billinginfo']->user_email;
$buyerfirstname = $data['billinginfo']->firstname;
$buyerlastname = $data['billinginfo']->lastname;
$CN = $buyerfirstname . "&nbsp;" . $buyerlastname;
$ownerZIP = $data['billinginfo']->zipcode;
$owneraddress = $data['billinginfo']->address;
$ownercty = $data['billinginfo']->city;






// End


if ($this->_params->get("is_test") == '1')
{
	$postfinanceurl = "https://e-payment.postfinance.ch/ncol/test/orderstandard.asp";
}
else
{
	$postfinanceurl = "https://e-payment.postfinance.ch/ncol/prod/orderstandard.asp";
}

$currencyClass = new convertPrice ();

$order->order_subtotal = round($currencyClass->convert($order_details[0]->order_total, '', 'USD'), 2) * 100;


$post_variables = Array(
	"orderID"      => $data['order_id'],
	"currency"     => "USD",
	"PSPID"        => $this->_params->get("postpayment_shopid"),
	"amount"       => $order->order_subtotal,
	"language"     => "en_US",
	"CN"           => $CN,
	"EMAIL"        => $buyeremail,
	"ownerZIP"     => $ownerZIP,
	"owneraddress" => $owneraddress,
	"ownercty"     => $ownercty,
	"accepturl"    => JURI::base() . "index.php?option=com_redshop&view=order_detail&controller=order_detail&task=notify_payment&payment_plugin=rs_payment_postfinance",
	"declineurl"   => JURI::base() . "index.php?option=com_redshop&view=order_detail&controller=order_detail&task=notify_payment&payment_plugin=rs_payment_postfinance",
	"exceptionurl" => "http://www.google.com",
	"cancelurl"    => JURI::base() . "index.php",


);



echo "<form action='$postfinanceurl' method='post' name='postfinanacefrm' id='postfinanacefrm'>";
echo "<input type='submit' name='submit'  value='submit' />";

foreach ($post_variables as $name => $value)
{
	echo "<input type='hidden' name='$name' value='$value' />";
}

echo "</form>";


// end by me

?>
<script type='text/javascript'>document.postfinanacefrm.submit();</script>
