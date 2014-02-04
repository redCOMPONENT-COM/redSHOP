<?php
/**
 * @package     RedSHOP
 * @subpackage  Plugin
 *
 * @copyright   Copyright (C) 2005 - 2013 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

$uri       = JURI::getInstance();
$url       = $uri->root();
$user      = JFactory::getUser();
$sessionid = session_id();
$db        = JFactory::getDbo();

require_once JPATH_ADMINISTRATOR . '/components/com_redshop/helpers/redshop.cfg.php';

$sql = "SELECT op.*,o.order_total,o.user_id FROM #__redshop_order_payment AS op LEFT JOIN #__redshop_orders AS o ON op.order_id = o.order_id  WHERE o.order_id='" . $data['order_id'] . "'";
$db->setQuery($sql);
$order_details = $db->loadObjectList();

// Get order item information
$q_oi = "SELECT * FROM #__redshop_order_item ";
$q_oi .= "WHERE #__redshop_order_item.order_id='" . $orderid . "'";
$db->setQuery($q_oi);
$items = $db->loadObjectList();

$item = array();

for ($i = 0; $i < count($items); $i++)
{
	$item[] = strip_tags($items[$i]->order_item_name) . '  ' . round($items[$i]->product_item_price, 2);
}

$table_desc = join(', ', $item);
// Currency converter
$currency = new CurrencyHelper;
$amount = $order_details[0]->order_total * 100;

$post_variables = array();

// Get plugin params
$live_mode = $this->params->get("is_live");

if ($live_mode == "1")
{
	$customer_id = $this->params->get("eway_3dsecure");
	$gatewayurl = $this->params->get("eway_3dsecure_liveurl");
}
else
{
	$customer_id = "87654321";
	$gatewayurl = $this->params->get("eway_3dsecure_sandboxurl");
}

$cancelurl     = JURI::base() . "index.php";
$declineurl    = JURI::base() . "index.php?option=com_redshop&view=order_detail&task=notify_payment&payment_plugin=rs_payment_eway3dsecure&Itemid=1&orderid=" . $data['order_id'];
$approveurl    = JURI::base() . "index.php?option=com_redshop&view=order_detail&task=notify_payment&payment_plugin=rs_payment_eway3dsecure&Itemid=1&orderid=" . $data['order_id'];

$cust_name     = $data['billinginfo']->firstname . " " . $data['billinginfo']->lastname;
$company       = $data['billinginfo']->company_name;

$cust_address1 = $data['billinginfo']->address;
$cust_address2 = $data['billinginfo']->state_code;
$cust_address3 = "";
$cust_zip      = $data['billinginfo']->zipcode;
$cust_city     = $data['billinginfo']->city;
$cust_phone    = $data['billinginfo']->phone;
$cust_email    = $data['billinginfo']->user_email;
$cust_country  = $data['billinginfo']->country_code;

// Fill array with class variables
$post_variables = array("ewayCustomerID" => $customer_id, "ewayTotalAmount" => $amount, "ewayCustomerFirstName" => $data['billinginfo']->firstname, "ewayCustomerLastName" => $data['billinginfo']->lastname, "ewayCustomerEmail" => $cust_email, "ewayCustomerAddress" => $cust_address1, "ewayCustomerPostcode" => $cust_zip, "ewayCustomerInvoiceDescription" => $table_desc, "ewayCustomerInvoiceRef" => $data['order_id'], "ewayURL" => $approveurl, "ewaySiteTitle" => "WebActive", "eWAYoption1" => $data['order_id']);

// Hidden form variables
$html_hidden_params = "";

foreach ($post_variables as $key => $val)
{
	$html_hidden_params .= "<input type='hidden' name='$key' value='$val' />";
}

$form_head = "<form id = \"eway3dform\" name=\"eway3dform\" action=\" ";
$form_head .= $gatewayurl;
$form_head .= " \" method=\"post\"> ";

echo $form_head;
echo $html_hidden_params;

// Now we make our own form end tag without any visible html
echo "</form>";
?>
<script>
	document.eway3dform.submit();
</script>
