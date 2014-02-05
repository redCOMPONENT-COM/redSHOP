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

$redhelper     = new redhelper;
$db            = JFactory::getDbo();
$user          = JFActory::getUser();
$task          = JRequest::getVar('task');
$app           = JFactory::getApplication();
$sql           = "SELECT op.*,o.order_total,o.user_id,o.order_tax,o.order_subtotal,o.order_shipping,o.order_number,o.payment_discount FROM #__redshop_order_payment AS op LEFT JOIN #__redshop_orders AS o ON op.order_id = o.order_id  WHERE o.order_id='" . $data['order_id'] . "'";
$db->setQuery($sql);
$order_details = $db->loadObjectList();

$paypointurl = "https://www.secpay.com/java-bin/ValCard";

$currencyClass = new CurrencyHelper;

$order->order_subtotal = $currencyClass->convert($order_details[0]->order_total, '', 'USD');

// Get params from payment plugin
$merchant_id  = $this->params->get("paypoint_merchant_id");
$vpn_password = $this->params->get("paypoint_vpn_password");
$test_status  = $this->params->get("paypoint_test_status");

if ($test_status == 2)
{
	$test_status = "live";
}
elseif ($test_status == 0)
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

$post_variables = Array(
	"option"   => "test_status=" . $test_status,
	"merchant" => $merchant_id,
	"trans_id" => $txn_id,
	"callback" => $call_back_url,
	"amount"   => $order_amount,
	"order"    => $data['order_id']
);

echo "<form action='$paypointurl' method='post'  id='paypointform'>";

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
