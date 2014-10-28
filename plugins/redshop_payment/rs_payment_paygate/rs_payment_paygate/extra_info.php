<?php
/**
 * @package     RedSHOP
 * @subpackage  Plugin
 *
 * @copyright   Copyright (C) 2005 - 2013 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */
require_once JPATH_SITE . '/administrator/components/com_redshop/helpers/redshop.cfg.php';
JLoader::import('redshop.library');
JLoader::load('RedshopHelperHelper');

$objOrder         = new order_functions;
$objconfiguration = new Redconfiguration;
$user             = JFactory::getUser();
$shipping_address = $objOrder->getOrderShippingUserInfo($data['order_id']);

$Itemid        = JFactory::getApplication()->input->getInt('Itemid');
$redhelper     = new redhelper;
$db            = JFactory::getDbo();
$user          = JFActory::getUser();
$task          = JRequest::getVar('task');
$app           = JFactory::getApplication();
$sql           = "SELECT op.*,o.order_total,o.user_id,o.order_tax,o.order_subtotal,o.order_shipping,o.order_number,o.payment_discount FROM #__redshop_order_payment AS op LEFT JOIN #__redshop_orders AS o ON op.order_id = o.order_id  WHERE o.order_id='" . $data['order_id'] . "'";
$db->setQuery($sql);
$order_details = $db->loadObjectList();
$paygateurl    = "https://www.paygate.co.za/paywebv2/process.trans";

$currencyClass = new CurrencyHelper;

$order->order_subtotal = $currencyClass->convert($order_details[0]->order_total, '', 'ZAR');

// Hidden variables for payment form
$TRANSACTION_DATE = date("y-m-d h:i:s");
$PAYGATE_ID       = $this->params->get("merchant_email");
$REFERENCE        = $data['order_id'];
$AMOUNT           = $order->order_subtotal * 100;
$CURRENCY         = "ZAR";
$RETURN_URL       = JURI::base() . "index.php?option=com_redshop&view=order_detail&controller=order_detail&task=notify_payment&payment_plugin=rs_payment_paygate&Itemid=$Itemid&orderid=" . $data['order_id'];

$encryption_key   = $this->params->get("encryption_key");
$checksum_source  = $PAYGATE_ID . "|" . $REFERENCE . "|" . $AMOUNT . "|" . $CURRENCY . "|" . $RETURN_URL . "|" . $TRANSACTION_DATE . "|";
$checksum_source  .= $encryption_key;
$CHECKSUM         = md5($checksum_source);

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

foreach ($post_variables as $name => $value)
{
	echo "<input type='hidden' name='$name' value='$value' />";
}

echo "</form>";
?>
<script language="javascript">
	document.getElementById('paygateform').submit();
</script>
