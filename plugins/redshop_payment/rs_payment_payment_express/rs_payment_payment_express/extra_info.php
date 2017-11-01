<?php
/**
 * @package     RedSHOP
 * @subpackage  Plugin
 *
 * @copyright   Copyright (C) 2008 - 2015 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

$objOrder          = order_functions::getInstance();
$request           = JRequest::get('request');
$Itemid            = $request["Itemid"];
$task              = $request['task'];
$PxPay_Url         = "https://sec2.paymentexpress.com/pxpay/pxaccess.aspx";
$pxpay_success_url = JURI::base() . 'index.php?tmpl=component&option=com_redshop&view=order_detail
    &controller=order_detail
    &task=notify_payment
    &payment_plugin=rs_payment_payment_express
    &Itemid=' . $Itemid . '&orderid=' . $data['order_id'];
$pxpay_fail_url = JURI::base() . 'index.php?tmpl=component&option=com_redshop&view=order_detail
    &controller=order_detail
    &task=notify_payment
    &payment_plugin=rs_payment_payment_express
    &Itemid=' . $Itemid . '&orderid=' . $data['order_id'];
$pxpay             = new PxPay_Curl($PxPay_Url, $this->params->get("px_pay_username"), $this->params->get("px_post_label_key"));
$request           = new PxPayRequest;
$http_host         = getenv("HTTP_HOST");
$request_uri       = getenv("SCRIPT_NAME");
$server_url        = "http://$http_host";
$script_url        = (version_compare(PHP_VERSION, "4.3.4", ">=")) ? "$server_url$request_uri" : "$server_url/$request_uri";

// Calculate AmountInput
$AmountInput = $data['carttotal'];

// Generate a unique identifier for the transaction
$BillingId = $data['order_id'];

// Set PxPay properties

$request->setAmountInput($AmountInput);
$request->setMerchantReference('');
$request->setTxnType($this->params->get("px_post_txntype"));
$request->setTxnData1(JText::_('COM_REDSHOP_ORDER_ID') . ":" . $data['order_id']);
$request->setCurrencyInput(Redshop::getConfig()->get('CURRENCY_CODE'));

// Can be a dedicated failure page
$request->setUrlFail($pxpay_fail_url);

// Can be a dedicated success page
$request->setUrlSuccess($pxpay_success_url);
$request->setBillingId($BillingId);

// The following properties are not used in this case
$request->setEnableAddBillCard(1);

// Call makeRequest function to obtain input XML
$request_string = $pxpay->makeRequest($request);

// Obtain output XML
$response = new MifMessage($request_string);

// Parse output XML
$url = $response->get_element_text("URI");
$valid = $response->get_attribute("valid");
$session = JFactory::getSession();
$session->set('cart', "");

$app = JFactory::getApplication();
$app->redirect($url);
