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

$Itemid = $_REQUEST['Itemid'];
$redhelper = new redhelper;
$db = JFactory::getDbo();
$user = JFActory::getUser();
$task = JRequest::getVar('task');
$app = JFactory::getApplication();
$sql = "SELECT op.*,o.order_total,o.user_id,o.order_tax,o.order_subtotal,o.order_shipping,o.order_number,o.payment_discount FROM " . $this->_table_prefix . "order_payment AS op LEFT JOIN " . $this->_table_prefix . "orders AS o ON op.order_id = o.order_id  WHERE o.order_id='" . $data['order_id'] . "'";
$db->setQuery($sql);
$order_details = $db->loadObjectList();
$paygateurl = "https://www.paygate.co.za/paywebv2/process.trans";

$currencyClass = new CurrencyHelper;

$order->order_subtotal = $currencyClass->convert($order_details[0]->order_total, '', 'ZAR');

// hidden variables for payment form

$TRANSACTION_DATE = date("y-m-d h:i:s");
$PAYGATE_ID = $this->_params->get("merchant_email");
$REFERENCE = $data['order_id'];
$AMOUNT = $order->order_subtotal * 100;
$CURRENCY = "ZAR";
$RETURN_URL = JURI::base() . "index.php?option=com_redshop&view=order_detail&controller=order_detail&task=notify_payment&payment_plugin=rs_payment_paygate&Itemid=$Itemid&orderid=" . $data['order_id'];

$encryption_key = $this->_params->get("encryption_key");

$checksum_source = $PAYGATE_ID . "|" . $REFERENCE . "|" . $AMOUNT . "|" . $CURRENCY . "|" . $RETURN_URL . "|" . $TRANSACTION_DATE . "|";

$checksum_source .= $encryption_key;

$CHECKSUM = md5($checksum_source);

// end

$post_variables = Array(
	"PAYGATE_ID"       => $PAYGATE_ID,
	"REFERENCE"        => $REFERENCE,
	"AMOUNT"           => $AMOUNT,
	"RETURN_URL"       => $RETURN_URL,
	"EMAIL"            => "",
	"TRANSACTION_DATE" => $TRANSACTION_DATE,
	"CHECKSUM"         => $CHECKSUM,
	"CURRENCY"         => "ZAR"

);


echo "<form action='$paygateurl' method='post'  id='paygateform'>";
//echo "<input type='submit' name='submitbtn'  value='Pay Now'>";

foreach ($post_variables as $name => $value)
{
	echo "<input type='hidden' name='$name' value='$value' />";
}

echo "</form>";

?>

<script language="javascript">

	document.getElementById('paygateform').submit();

</script>