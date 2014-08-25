<?php
/**
 * @package     RedSHOP
 * @subpackage  Plugin
 *
 * @copyright   Copyright (C) 2005 - 2013 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

JLoader::import('LoadHelpers', JPATH_SITE . '/components/com_redshop');
JLoader::load('RedshopHelperHelper');
require_once JPATH_SITE . '/administrator/components/com_redshop/helpers/redshop.cfg.php';
$objOrder = new order_functions;

$objconfiguration = new Redconfiguration;

$user = JFactory::getUser();
$session = JFactory::getSession();
$redirect_ccdata = $session->get('redirect_ccdata');

$app = JFactory::getApplication();
$Itemid = JRequest::getVar('Itemid');
$request = JRequest::get('request');
$login_id = $this->_params->get("access_id");
$trans_id = $this->_params->get("transaction_id");
$is_test = $this->_params->get("is_test");

$relay_response_url = JURI::root() . 'index.php?option=com_redshop&view=order_detail&layout=checkout_final&stap=2&oid=' . $data["order_id"] . '&Itemid=' . $Itemid;

$api_login_id = $login_id;
$transaction_key = $trans_id;
$amount = $data['order']->order_total;
$fp_sequence = $data['order']->order_number;

$time = time();
$fp = AuthorizeNetDPM::getFingerprint($api_login_id, $transaction_key, $amount, $fp_sequence, $time);

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
$post_url = ($is_test ? "https://test.authorize.net/gateway/transact.dll" : "https://secure.authorize.net/gateway/transact.dll");

$form = '
        <style>
        fieldset {
            overflow: auto;
            border: 0;
            margin: 0;
            padding: 0; }

        fieldset div {
            float: left; }

        fieldset.centered div {
            text-align: center; }

        label {
            color: #183b55;
            display: block;
            margin-bottom: 5px; }

        label img {
            display: block;
            margin-bottom: 5px; }

        input.text {
            border: 1px solid #bfbab4;
            margin: 0 4px 8px 0;
            padding: 6px;
            color: #1e1e1e;
            -webkit-border-radius: 5px;
            -moz-border-radius: 5px;
            border-radius: 5px;
            -webkit-box-shadow: inset 0px 5px 5px #eee;
            -moz-box-shadow: inset 0px 5px 5px #eee;
            box-shadow: inset 0px 5px 5px #eee; }
        .submit {
            display: block;
            background-color: #76b2d7;
            border: 1px solid #766056;
            color: #3a2014;
            margin: 13px 0;
            padding: 8px 16px;
            -webkit-border-radius: 12px;
            -moz-border-radius: 12px;
            border-radius: 12px;
            font-size: 14px;
            -webkit-box-shadow: inset 3px -3px 3px rgba(0,0,0,.5), inset 0 3px 3px rgba(255,255,255,.5), inset -3px 0 3px rgba(255,255,255,.75);
            -moz-box-shadow: inset 3px -3px 3px rgba(0,0,0,.5), inset 0 3px 3px rgba(255,255,255,.5), inset -3px 0 3px rgba(255,255,255,.75);
            box-shadow: inset 3px -3px 3px rgba(0,0,0,.5), inset 0 3px 3px rgba(255,255,255,.5), inset -3px 0 3px rgba(255,255,255,.75); }
        </style>
        <form method="post" action="' . $post_url . '" id="authodpmfrm" name="authodpmfrm">
                ' . $hidden_fields . '
            <fieldset>
                <div>

                    <input type="hidden" class="hidden"  name="x_card_num" value="' . $redirect_ccdata['order_payment_number'] . '"></input>
                </div>
                <div>

                    <input type="hidden" class="hidden"  name="x_exp_date" value="' . $redirect_ccdata['order_payment_expire_month'] . '/' . $redirect_ccdata['order_payment_expire_year'] . '"></input>
                </div>
                <div>

                    <input type="hidden" class="hidden"  name="x_card_code" value="' . $redirect_ccdata['credit_card_code'] . '"></input>
                </div>
            </fieldset>
            <fieldset>
                <div>

                    <input type="hidden" class="text" size="15" name="x_first_name" value="' . $data['billinginfo']->firstname . '"></input>
                </div>
                <div>

                    <input type="hidden" class="text" size="14" name="x_last_name" value="' . $data['billinginfo']->lastname . '"></input>
                </div>
            </fieldset>
            <fieldset>
                <div>

                    <input type="hidden" class="text" size="26" name="x_address" value="' . $data['billinginfo']->address . '"></input>
                </div>
                <div>

                    <input type="hidden" class="text" size="15" name="x_city" value="' . $data['billinginfo']->city . '"></input>
                </div>
            </fieldset>
            <fieldset>
                <div>

                    <input type="hidden" class="text" size="4" name="x_state" value="' . $data['billinginfo']->state_code . '"></input>
                </div>
                <div>

                    <input type="hidden" class="text" size="9" name="x_zip" value="' . $data['billinginfo']->zipcode . '"></input>
                </div>
                <div>

                    <input type="hidden" class="text" size="22" name="x_country" value="' . $data['billinginfo']->country_2_code . '"></input>
                </div>
            </fieldset>
        </form>';
echo $form;

// end by me

?>

<!--<fieldset>
                <div>
                    <label>First Name</label>
                    <input type="text" class="text" size="15" name="x_first_name" value="'.($prefill ? 'John' : '').'"></input>
                </div>
                <div>
                    <label>Last Name</label>
                    <input type="text" class="text" size="14" name="x_last_name" value="'.($prefill ? 'Doe' : '').'"></input>
                </div>
            </fieldset>
            <fieldset>
                <div>
                    <label>Address</label>
                    <input type="text" class="text" size="26" name="x_address" value="'.($prefill ? '123 Main Street' : '').'"></input>
                </div>
                <div>
                    <label>City</label>
                    <input type="text" class="text" size="15" name="x_city" value="'.($prefill ? 'Boston' : '').'"></input>
                </div>
            </fieldset>
            <fieldset>
                <div>
                    <label>State</label>
                    <input type="text" class="text" size="4" name="x_state" value="'.($prefill ? 'MA' : '').'"></input>
                </div>
                <div>
                    <label>Zip Code</label>
                    <input type="text" class="text" size="9" name="x_zip" value="'.($prefill ? '02142' : '').'"></input>
                </div>
                <div>
                    <label>Country</label>
                    <input type="text" class="text" size="22" name="x_country" value="'.($prefill ? 'US' : '').'"></input>
                </div>
            </fieldset>
            <input type="submit" value="BUY" class="submit buy">-->
<script type='text/javascript'>
	window.onload = function () {
		document.authodpmfrm.submit();
	}
</script>