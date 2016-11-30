<?php
/**
 * @package     RedSHOP
 * @subpackage  Plugin
 *
 * @copyright   Copyright (C) 2008 - 2015 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

JLoader::import('redshop.library');

// Redirection after full page load
JHtml::_('redshopjquery.framework');
$document = JFactory::getDocument();
$document->addScriptDeclaration(
	'jQuery(document).ready(function(){
		jQuery("#authodpmfrm").submit();
	});'
);

$objOrder         = order_functions::getInstance();
$objconfiguration = Redconfiguration::getInstance();
$user             = JFactory::getUser();
$session          = JFactory::getSession();
$redirect_ccdata  = $session->get('ccdata');

$app              = JFactory::getApplication();
$Itemid           = $app->input->getInt('Itemid', 0);
$api_login_id         = $this->params->get("access_id");
$transaction_key         = $this->params->get("transaction_id");
$is_test          = $this->params->get("is_test");

$relay_response_url = JURI::root() . 'index.php?option=com_redshop&view=order_detail&layout=checkout_final&stap=2&tmpl=component&oid='
	. $data["order_id"] . '&encr=' . $data['order']->encr_key . '&Itemid=' . $Itemid;

$amount          = $data['order']->order_total;
$fp_sequence     = $data['order']->order_number;

$time = time();
$fp   = AuthorizeNetDPM::getFingerprint($api_login_id, $transaction_key, $amount, $fp_sequence, $time);

$sim = new AuthorizeNetSIM_Form(
	array(
		'x_amount'         => $amount,
		'x_fp_sequence'    => $fp_sequence,
		'x_fp_hash'        => $fp,
		'x_fp_timestamp'   => $time,
		'x_relay_response' => "TRUE",
		'x_relay_url'      => $relay_response_url,
		'x_login'          => $api_login_id,
	)
);

$hidden_fields = $sim->getHiddenFieldString();
$post_url      = ($is_test ? "https://test.authorize.net/gateway/transact.dll" : "https://secure2.authorize.net/gateway/transact.dll");

echo "<h3>" . JText::_('PLG_RS_PAYMENT_AUTHORIZE_DPM_MESSAGE') . "</h3>";
echo '
	<form method="post" action="' . $post_url . '" id="authodpmfrm" name="authodpmfrm">
	' . $hidden_fields . '
		<input type="hidden" name="x_card_num" value="' . $redirect_ccdata['order_payment_number'] . '"/>
		<input type="hidden" name="x_exp_date" value="' . $redirect_ccdata['order_payment_expire_month'] . '/' . $redirect_ccdata['order_payment_expire_year'] . '"/>
		<input type="hidden" name="x_card_code" value="' . $redirect_ccdata['credit_card_code'] . '"/>
		<input type="hidden" name="x_first_name" value="' . $data['billinginfo']->firstname . '"/>
		<input type="hidden" name="x_last_name" value="' . $data['billinginfo']->lastname . '"/>
		<input type="hidden" name="x_address" value="' . $data['billinginfo']->address . '"/>
		<input type="hidden" name="x_city" value="' . $data['billinginfo']->city . '"/>
		<input type="hidden" name="x_state" value="' . $data['billinginfo']->state_code . '"/>
		<input type="hidden" name="x_zip" value="' . $data['billinginfo']->zipcode . '"/>
		<input type="hidden" name="x_country" value="' . $data['billinginfo']->country_2_code . '"/>
	</form>';
