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

// buyer details

$buyeremail = $data['billinginfo']->user_email;
$buyerfirstname = $data['billinginfo']->firstname;
$buyerlastname = $data['billinginfo']->lastname;
$CN = $buyerfirstname . "&nbsp;" . $buyerlastname;
$ownerZIP = $data['billinginfo']->zipcode;
$owneraddress = $data['billinginfo']->address;
$ownercty = $data['billinginfo']->city;
$country = $data['billinginfo']->country_2_code;
$phone = $data['billinginfo']->phone;
$cartId = $data['order_id'];
// End
$instId = $this->_params->get("installation_id");
$md5_key = $this->_params->get("md5_key");
$order_desc = $this->_params->get("order_desc");


if ($this->_params->get("is_test") == '1')
{
	$worldpayurl = "https://select-test.wp3.rbsworldpay.com/wcc/purchase";
}
else
{
	$worldpayurl = "https://secure.worldpay.com/wcc/purchase";
}

$currencyClass = new CurrencyHelper;
$order->order_subtotal = number_format($order_details[0]->order_total, 2, '.', '');
$amount = $order->order_subtotal;

// md5 secret key

$sign_key = $md5_key . ":" . $instId . ":" . $order->order_subtotal . ":" . CURRENCY_CODE . ":" . $cartId;
$md5_sign_key = md5($sign_key);
// End
$post_variables = Array(
	"instId"      => $instId,
	"cartId"      => $data['order_id'],
	"currency"    => CURRENCY_CODE,
	"amount"      => $order->order_subtotal,
	"email"       => $buyeremail,
	"address"     => $owneraddress,
	"postcode"    => $ownerZIP,
	"desc"        => $order_desc . "-" . $data['order_id'],
	"name"        => $CN,
	"country"     => $country,
	"tel"         => $phone,
	"MC_callback" => JURI::base() . "index.php?tmpl=component&option=com_redshop&view=order_detail&controller=order_detail&task=notify_payment&payment_plugin=rs_payment_worldpay&accept=1&Itemid=" . $_REQUEST['Itemid'] . "&orderid=" . $data['order_id'],
	"signature"   => $md5_sign_key,
);



echo "<form action='$worldpayurl' method='post' name='worldpayfrm' id='worldpayfrm'>";

if ($this->_params->get("is_test") == '1')
{
	echo "<input type='hidden' name='testMode' value='100' />";
}

foreach ($post_variables as $name => $value)
{
	echo "<input type='hidden' name='$name' value='$value' />";
}

echo "</form>";

// end by me

?>
<script type='text/javascript'>document.worldpayfrm.submit();</script>