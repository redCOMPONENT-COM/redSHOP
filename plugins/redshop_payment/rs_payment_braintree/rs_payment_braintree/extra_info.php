<?php
/**
 * @package     RedSHOP
 * @subpackage  Plugin
 *
 * @copyright   Copyright (C) 2008 - 2015 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

JLoader::import('redshop.library');

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
	$postVariables = [
			"transaction[customer][first_name]"         => $buyerFirstName,
			"transaction[customer][last_name]"          => $buyerLastName,
			"transaction[customer][email]"              => $buyerEmail,
			"transaction[credit_card][number]"          => $ccData['order_payment_number'],
			"transaction[credit_card][expiration_date]" => ($ccData['order_payment_expire_month']) . "/" . ($ccData['order_payment_expire_year']),
			"transaction[credit_card][cvv]"             => $ccData['credit_card_code'],
			"tr_data"                                   => $data['braintree_token'],
		];
}
else
{
	$postVariables = [
		"transaction[customer][first_name]" => $buyerFirstName,
		"transaction[customer][last_name]"  => $buyerLastName,
		"transaction[customer][email]"      => $buyerEmail,
		"tr_data"                           => $data['braintree_token'],
	];
}

require JPluginHelper::getLayoutPath('redshop_payment', 'rs_payment_braintree', 'extra_info');
