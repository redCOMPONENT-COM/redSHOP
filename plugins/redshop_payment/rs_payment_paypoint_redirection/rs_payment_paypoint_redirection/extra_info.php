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
JLoader::import('LoadHelpers', JPATH_SITE . '/components/com_redshop');
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

$paypointurl = "https://www.secpay.com/java-bin/ValCard";

$currencyClass = new CurrencyHelper;

$order->order_subtotal = $currencyClass->convert($order_details[0]->order_total, '', 'USD');

// get params from payment plugin
$merchant_id = $this->_params->get("paypoint_merchant_id");
$vpn_password = $this->_params->get("paypoint_vpn_password");
$test_status = $this->_params->get("paypoint_test_status");

if ($test_status == 2)
{
	$test_status = "live";
}
else if ($test_status == 0)
{
	$test_status = "false";
}
else
{
	$test_status = "true";
}

$order_amount = $order_details[0]->order_total;
$txn_id = rand(1111111, 9999999);
$call_back_url = JURI::base() . "index.php?option=com_redshop&view=order_detail&controller=order_detail&task=notify_payment&payment_plugin=rs_payment_paypoint_redirection&orderid=" . $data['order_id'];


// End

$post_variables = Array(
	"option"   => "test_status=" . $test_status,
	"merchant" => $merchant_id,
	"trans_id" => $txn_id,
	"callback" => $call_back_url,
	"amount"   => $order_amount,
	"order"    => $data['order_id'],
	// "template" => "http://www.secpay.com/users/uclick01/c_card.htm"

);


echo "<form action='$paypointurl' method='post'  id='paypointform'>";
//echo "<input type='submit' name='submitbtn'  value='Pay Now'>";

foreach ($post_variables as $name => $value)
{
	echo "<input type='hidden' name='$name' value='$value' />";
}
echo "<input type='submit' value='Pay' name ='paynwbtn'>";

echo "</form>";

?>

<script language="javascript">

	document.getElementById('paypointform').submit();

</script>