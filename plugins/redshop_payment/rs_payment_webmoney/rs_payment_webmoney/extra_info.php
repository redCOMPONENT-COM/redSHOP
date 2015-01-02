<?php
/**
 * @package     RedSHOP
 * @subpackage  Plugin
 *
 * @copyright   Copyright (C) 2005 - 2015 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

$order_number        = $data['order_id'];
$total_sum_to_pay    = $data['carttotal'];
$webmoneyurl         = "https://merchant.webmoney.ru/lmi/payment.asp";
$payee_purse         = $this->params->get("purse_prodovtsa");
$payment_description = $this->params->get("payment_description");

$post_variables = Array(

	"LMI_PAYMENT_AMOUNT" => round($total_sum_to_pay, 2),
	"LMI_PAYMENT_DESC"   => $payment_description,
	"LMI_PAYMENT_NO"     => $data['order_id'],
	"LMI_PAYEE_PURSE"    => $payee_purse,
	"LMI_SIM_MODE"       => "1",
	"LMI_RESULT_URL"     => JURI::base() . "index.php?option=com_redshop&view=order_detail&controller=order_detail&task=notify_payment&payment_plugin=rs_payment_webmoney&orderid=" . $data['order_id'],
	"LMI_SUCCESS_URL"    => JURI::base() . "index.php?option=com_redshop&view=order_detail&oid=" . $data['order_id'],
	"LMI_SUCCESS_METHOD" => "2",
	"LMI_FAIL_URL"       => JURI::base() . "index.php?option=com_redshop&view=order_detail&oid=" . $data['order_id'],
	"LMI_FAIL_METHOD"    => "2"
);

echo '<form action="' . $webmoneyurl . '" method="post">';
echo '<input type="submit" value="Pay" name="formSubmit" class="button"/>';

foreach ($post_variables as $name => $value)
{
	echo '<input type="hidden" name="' . $name . '" value="' . htmlspecialchars($value) . '" />';
}

echo '</form>';
