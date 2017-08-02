<?php
/**
 * @package     RedSHOP
 * @subpackage  Plugin
 *
 * @copyright   Copyright (C) 2008 - 2015 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

defined('_JEXEC') or die;

JLoader::import('redshop.library');
$objOrder         = order_functions::getInstance();
$objconfiguration = Redconfiguration::getInstance();
$user             = JFactory::getUser();
$redhelper        = redhelper::getInstance();
$db               = JFactory::getDbo();
$user             = JFActory::getUser();
$task             = JRequest::getVar('task');
$app              = JFactory::getApplication();

$sql = "SELECT op.*,o.order_total,o.user_id,o.order_tax,o.order_subtotal,o.order_shipping,o.order_number,o.payment_discount
		FROM #__redshop_order_payment AS op
		LEFT JOIN #__redshop_orders AS o ON op.order_id = o.order_id
		WHERE o.order_id='" . $data['order_id'] . "'";
$db->setQuery($sql);
$order_details = $db->loadObjectList();

// Buyer details
$buyeremail     = $data['billinginfo']->user_email;
$buyerfirstname = $data['billinginfo']->firstname;
$buyerlastname  = $data['billinginfo']->lastname;
$cartId         = $data['order_id'];

// Get ccdtata session
$session = JFactory::getSession();
$ccdata  = $session->get('ccdata');

if ($this->params->get("is_test") == '1')
{
	$braintreeurl = Braintree_TransparentRedirect::url();
}
else
{
	$braintreeurl = Braintree_TransparentRedirect::url();
}

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

?>
<script type='text/javascript'>
	document.braintreefrm.submit();
</script>
