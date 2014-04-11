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

$objOrder = new order_functions;
$request = JRequest::get('request');
$Itemid = $request["Itemid"];
$task = $request['task'];
$PxPay_Url = "https://sec2.paymentexpress.com/pxpay/pxaccess.aspx";
$pxpay_success_url = JURI::base() . "plugins/redshop_payment/rs_payment_payment_express/rs_payment_payment_express/rs_pxpost_notify.php";
$pxpay_fail_url = JURI::base() . "plugins/redshop_payment/rs_payment_payment_express/rs_payment_payment_express/rs_pxpost_notify.php";
$pxpay = new PxPay_Curl($PxPay_Url, $this->_params->get("px_pay_username"), $this->_params->get("px_post_label_key"));

$request = new PxPayRequest;

$http_host = getenv("HTTP_HOST");
$request_uri = getenv("SCRIPT_NAME");
$server_url = "http://$http_host";
#$script_url  = "$server_url/$request_uri"; //using this code before PHP version 4.3.4
#$script_url  = "$server_url$request_uri"; //Using this code after PHP version 4.3.4
$script_url = (version_compare(PHP_VERSION, "4.3.4", ">=")) ? "$server_url$request_uri" : "$server_url/$request_uri";

#Calculate AmountInput

$AmountInput = $data['carttotal'];

#Generate a unique identifier for the transaction
$BillingId = $data['order_id'];

#Set PxPay properties

$request->setAmountInput($AmountInput);
$request->setMerchantReference('');
$request->setTxnType($this->_params->get("px_post_txntype"));
$request->setTxnData1(JText::_('COM_REDSHOP_ORDER_ID') . ":" . $data['order_id']);
$request->setCurrencyInput(CURRENCY_CODE);
$request->setUrlFail($pxpay_fail_url); # can be a dedicated failure page
$request->setUrlSuccess($pxpay_success_url); # can be a dedicated success page
$request->setBillingId($BillingId);
#The following properties are not used in this case
$request->setEnableAddBillCard(1);
# $request->setOpt($Opt);

#Call makeRequest function to obtain input XML
$request_string = $pxpay->makeRequest($request);

#Obtain output XML
$response = new MifMessage($request_string);

#Parse output XML
$url = $response->get_element_text("URI");
$valid = $response->get_attribute("valid");
$session = JFactory::getSession();
$session->set('cart', "");

$app->redirect($url);


?>