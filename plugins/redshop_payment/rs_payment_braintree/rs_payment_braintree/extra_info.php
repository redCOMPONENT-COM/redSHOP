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


require_once JPATH_COMPONENT . DS . 'helpers' . DS . 'helper.php';
require_once JPATH_SITE . DS . 'administrator' . DS . 'components' . DS . 'com_redshop' . DS . 'helpers' . DS . 'redshop.cfg.php';
$objOrder = new order_functions;

$objconfiguration = new Redconfiguration;

$user = JFactory::getUser();



$redhelper = new redhelper;
$db = JFactory::getDBO();
$user = JFActory::getUser();
$task = JRequest::getVar('task');
$mainframe =& JFactory::getApplication();

$sql = "SELECT op.*,o.order_total,o.user_id,o.order_tax,o.order_subtotal,o.order_shipping,o.order_number,o.payment_discount FROM " . $this->_table_prefix . "order_payment AS op LEFT JOIN " . $this->_table_prefix . "orders AS o ON op.order_id = o.order_id  WHERE o.order_id='" . $data['order_id'] . "'";
$db->setQuery($sql);
$order_details = $db->loadObjectList();

// Buyer details

$buyeremail = $data['billinginfo']->user_email;
$buyerfirstname = $data['billinginfo']->firstname;
$buyerlastname = $data['billinginfo']->lastname;

$cartId = $data['order_id'];
// End


// Get ccdtata session
$session =& JFactory::getSession();
$ccdata = $session->get('ccdata');

//End

if ($this->_params->get("is_test") == '1')
{
	$braintreeurl = Braintree_TransparentRedirect::url();
}
else
{
	$braintreeurl = Braintree_TransparentRedirect::url();
}

// End

if ($data['new_user'])
{
	$post_variables = Array(
		"transaction[customer][first_name]"         => $buyerfirstname,
		"transaction[customer][last_name]"          => $buyerlastname,
		"transaction[customer][email]"              => $buyeremail,
		"transaction[credit_card][number]"          => $ccdata['order_payment_number'],
		"transaction[credit_card][expiration_date]" => ($ccdata['order_payment_expire_month']) . "/" . ($ccdata['order_payment_expire_year']),
		"transaction[credit_card][cvv]"             => $ccdata['credit_card_code'],
		"tr_data"                                   => $data['braintree_token'],

	);
}
else
{
	$post_variables = Array(
		"transaction[customer][first_name]" => $buyerfirstname,
		"transaction[customer][last_name]"  => $buyerlastname,
		"transaction[customer][email]"      => $buyeremail,
		"tr_data"                           => $data['braintree_token'],

	);
}



echo "<form action='$braintreeurl' method='post' name='braintreefrm' id='braintreefrm'>";

foreach ($post_variables as $name => $value)
{

	echo "<input type='hidden' name='$name' value='$value' />";
}

echo "</form>";


// End by me

?>
<script type='text/javascript'>
	window.onload = function () {
		document.braintreefrm.submit();
	}
</script>
