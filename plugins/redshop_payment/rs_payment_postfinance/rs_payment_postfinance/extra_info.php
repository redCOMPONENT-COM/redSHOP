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




// End

if ($this->_params->get("is_test") == '1')
{
	$postfinanceurl = "https://e-payment.postfinance.ch/ncol/test/orderstandard.asp";
}
else
{
	$postfinanceurl = "https://e-payment.postfinance.ch/ncol/prod/orderstandard.asp";
}

$currencyClass = new CurrencyHelper;

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

?>
<form id='postfinanacefrm' name='postfinanacefrm' action='<?php echo $postfinanceurl; ?>' method='post'>
<input type="button" onclick="sendPostFinanace()" value="Submit">
<?php foreach ($post_variables as $name => $value): ?>
	<input type='hidden' name='<?php echo $name; ?>' value='<?php echo $value; ?>' />
<?php endforeach; ?>
</form>
<?php
JFactory::getDocument()->addScriptDeclaration('
	window.onload = function(){
		sendPostFinanace();
	};

	var sendPostFinanace = function(){
		document.postfinanacefrm.submit();
	};
');
