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

$input = JFactory::getApplication()->input;

$objOrder         = order_functions::getInstance();
$objconfiguration = Redconfiguration::getInstance();
$user             = JFactory::getUser();
$redhelper        = redhelper::getInstance();
$db               = JFactory::getDbo();
$user             = JFActory::getUser();
$task             = $input->getCmd('task');
$app              = JFactory::getApplication();

$query = $db->getQuery(true)
	->select('op.*')
	->select(
		$db->qn(
			array('o.order_total', 'o.user_id', 'o.order_tax', 'o.order_subtotal', 'o.order_shipping', 'o.order_number', 'o.payment_discount')
		)
	)
	->from($db->qn('#__redshop_order_payment', 'op'))
	->leftJoin($db->qn('#__redshop_orders', 'o') . ' ON ' . $db->qn('op.order_id') . ' = ' . $db->qn('o.order_id'))
	->where($db->qn('o.order_id') . ' = ' . (int) $data['order_id']);

$orderDetail = $db->setQuery($query)->loadObjectList();

// Buyer details
$buyerEmail     = $data['billinginfo']->user_email;
$buyerFirstName = $data['billinginfo']->firstname;
$buyerLastName  = $data['billinginfo']->lastname;
$cartId         = $data['order_id'];

// Get ccdtata session
$session = JFactory::getSession();
$ccData  = $session->get('ccdata');

if ($this->params->get("is_test") == '1')
{
	$braintreeUrl = Braintree_TransparentRedirect::url();
}
else
{
	$braintreeUrl = Braintree_TransparentRedirect::url();
}

if ($data['new_user'])
{
	$postVariables = Array(
		"transaction[customer][first_name]"         => $buyerFirstName,
		"transaction[customer][last_name]"          => $buyerLastName,
		"transaction[customer][email]"              => $buyerEmail,
		"transaction[credit_card][number]"          => $ccData['order_payment_number'],
		"transaction[credit_card][expiration_date]" => ($ccData['order_payment_expire_month']) . "/" . ($ccData['order_payment_expire_year']),
		"transaction[credit_card][cvv]"             => $ccData['credit_card_code'],
		"tr_data"                                   => $data['braintree_token']
	);
}
else
{
	$postVariables = Array(
		"transaction[customer][first_name]" => $buyerFirstName,
		"transaction[customer][last_name]"  => $buyerLastName,
		"transaction[customer][email]"      => $buyerEmail,
		"tr_data"                           => $data['braintree_token']
	);
}

echo '<form action="' . $braintreeUrl . '" method="post" name="braintreefrm" id="braintreefrm">';

foreach ($postVariables as $name => $value)
{
	echo '<input type="hidden" name="' . $name . 'EXTERNAL_FRAGMENT" value="' . $value . '" />';
}

echo '</form>';
?>
<script type='text/javascript'>
    document.braintreefrm.submit()
</script>
