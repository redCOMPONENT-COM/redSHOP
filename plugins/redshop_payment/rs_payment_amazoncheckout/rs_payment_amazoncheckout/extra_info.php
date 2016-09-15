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

$amazon_recipientEmail           = $this->params->get("amazon_recipientEmail");
$amazon_payment_method           = $this->params->get("amazon_payment_method");
$amazonPaymentsAccountId         = $this->params->get("amazonPaymentsAccountId");
$amazon_accessKey                = $this->params->get("amazon_accessKey");
$amazonSecretAccessKey           = $this->params->get("amazon_secret_accessKey");
$amazon_signature_method         = $this->params->get("amazon_signature_method");

$amazon_variable_marketplace_fee = $this->params->get("amazon_variable_marketplace_fee");
$amazon_fixed_marketplace_fee    = $this->params->get("amazon_fixed_marketplace_fee");
$amazon_is_test                  = $this->params->get("amazon_is_test");
$amazon_caller_reference         = $this->params->get("amazon_caller_reference");

require_once 'ButtonGenerator.php';

$amount = $data['carttotal'];
$description = 'pay for order no.' . $data['order_id'];
$referenceId = JText::_('COM_REDSHOP_MY_TRANSACTION') . '-' . $data['order_id'];

// Optionally, enter "1" if you want to skip the final status page in Amazon Payments
$immediateReturn = "1";

// Optionally, enter "1" if you want to settle the transaction immediately else "0". Default value is "1"
$processImmediate = "1";

// The URL where buyers should be redirected after they complete the transaction
$returnUrl = JURI::base() . "index.php?option=com_redshop&view=order_detail&controller=order_detail&payment_plugin=rs_payment_amazoncheckout&task=notify_payment&Itemid=" . $_REQUEST['Itemid'] . "&orderid=" . $data['order_id'];

// The URL where senders should be redirected if they cancel their transaction
$abandonUrl = JURI::base() . "index.php?option=com_redshop&view=order_detail&controller=order_detail&payment_plugin=rs_payment_amazoncheckout&task=notify_payment&Itemid=" . $_REQUEST['Itemid'] . "&orderid=" . $data['order_id'];

// Type the URL of your host page to which Amazon Payments should send the IPN transaction information.
$ipnUrl = JURI::base() . 'index.php?option=com_redshop&view=order_detail&controller=order_detail&task=notify_payment&payment_plugin=rs_payment_amazoncheckout&Itemid=' . $_REQUEST['Itemid'] . '&orderid=' . $data['order_id'];

// Enter "1" if you want Amazon Payments to return the buyer's shipping address as part of the transaction information
$collectShippingAddress = 1;

$otherParams = array(
	'recipientEmail' => $amazon_recipientEmail,
	'isDonationWidget' => 0,
	'amazonPaymentsAccountId' => $amazonPaymentsAccountId
);

if ($amazon_payment_method == 'MARKETPLACE')
{
	$otherParams['variableMarketplaceFee'] = $amazon_variable_marketplace_fee;
	$otherParams['fixedMarketplaceFee'] = $amazon_fixed_marketplace_fee;
	$otherParams['maxVariableFee'] = $amazon_variable_marketplace_fee;
	$otherParams['maxFixedFee'] = 0.2;
	$otherParams['callerAccountId'] = $amazonPaymentsAccountId;
	$otherParams['recipientPaysFee'] = 'true';
	$otherParams['collectEmailAddress'] = 'true';
	$otherParams['callerReference'] = $amazon_caller_reference;
	$otherParams['callerKey'] = $amazon_accessKey;
	$otherParams['pipelineName'] = 'Recipient';
}

try
{
	ButtonGenerator::GenerateForm(
		$amazon_accessKey, $amazonSecretAccessKey, $amount, $description, $referenceId, $immediateReturn,
		$returnUrl, $abandonUrl, $processImmediate, $ipnUrl, $collectShippingAddress, $amazon_signature_method,
		$amazon_is_test, $otherParams
	);
}
catch (Exception $e)
{
	echo 'Exception : ', $e->getMessage(), "\n";
}

?>
<script>
	document.getElementById("amazoncheckout").submit();
</script>
