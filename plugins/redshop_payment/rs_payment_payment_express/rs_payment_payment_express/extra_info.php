<?php
/**
 * @package     RedSHOP
 * @subpackage  Plugin
 *
 * @copyright   Copyright (C) 2008 - 2016 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

defined('_JEXEC') or die;

$app    = JFactory::getApplication();
$input  = $app->input;
$itemid = $input->get("Itemid", 0);
$task   = $input->get('task', '');

$pxPayUrl         = "https://sec2.paymentexpress.com/pxpay/pxaccess.aspx";

$pxPaySuccessUrl = JURI::base() . 'index.php?tmpl=component&option=com_redshop&view=order_detail
    &controller=order_detail
    &task=notify_payment
    &payment_plugin=rs_payment_payment_express
    &Itemid=' . $itemid . '&orderid=' . $data['order_id'];

$pxPayFailUrl = JURI::base() . 'index.php?tmpl=component&option=com_redshop&view=order_detail
    &controller=order_detail
    &task=notify_payment
    &payment_plugin=rs_payment_payment_express
    &Itemid=' . $itemid . '&orderid=' . $data['order_id'];

$pxPay             = new PxPay_Curl($pxPayUrl, $this->params->get("px_pay_username"), $this->params->get("px_post_label_key"));
$request           = new PxPayRequest;

$httpHost          = getenv("HTTP_HOST");
$requestUri       = getenv("SCRIPT_NAME");
$serverUrl        = "http://$httpHost";
$scriptUrl        = (version_compare(PHP_VERSION, "4.3.4", ">=")) ? "$serverUrl$requestUri" : "$serverUrl/$requestUri";

// Calculate AmountInput
$amountInput = $data['carttotal'];

// Generate a unique identifier for the transaction
$billingId = $data['order_id'];

// Set PxPay properties

$request->setAmountInput($amountInput);
$request->setMerchantReference('');
$request->setTxnType($this->params->get("px_post_txntype"));
$request->setTxnData1(JText::_('COM_REDSHOP_ORDER_ID') . ":" . $data['order_id']);
$request->setCurrencyInput(Redshop::getConfig()->get('CURRENCY_CODE'));

// Can be a dedicated failure page
$request->setUrlFail($pxPayFailUrl);

// Can be a dedicated success page
$request->setUrlSuccess($pxPaySuccessUrl);
$request->setBillingId($billingId);

// The following properties are not used in this case
$request->setEnableAddBillCard(1);

// Call makeRequest function to obtain input XML
$requestString = $pxPay->makeRequest($request);

// Obtain output XML
$response = new MifMessage($requestString);

// Parse output XML
$url = $response->get_element_text("URI");
$valid = $response->get_attribute("valid");
$session = JFactory::getSession();
$session->set('cart', "");

$app->redirect($url);
