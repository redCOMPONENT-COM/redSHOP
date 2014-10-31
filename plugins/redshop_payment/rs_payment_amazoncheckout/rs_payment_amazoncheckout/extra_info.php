<?php
/**
 * @package     RedSHOP
 * @subpackage  Plugin
 *
 * @copyright   Copyright (C) 2005 - 2014 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */
JLoader::import('redshop.library');
JLoader::load('RedshopHelperAdminOrder');
JLoader::load('RedshopHelperHelper');

$amazon_signature                = $this->params->get("amazon_signature");
$amazon_recipientEmail           = $this->params->get("amazon_recipientEmail");
$amazon_payment_method           = $this->params->get("amazon_payment_method");
$amazonPaymentsAccountId         = $this->params->get("amazonPaymentsAccountId");
$amazon_accessKey                = $this->params->get("amazon_accessKey");
$amazon_signature_method         = $this->params->get("amazon_signature_method");

$amazon_variable_marketplace_fee = $this->params->get("amazon_variable_marketplace_fee");
$amazon_fixed_marketplace_fee    = $this->params->get("amazon_fixed_marketplace_fee");
$amazon_is_test                  = $this->params->get("amazon_is_test");
$amazon_caller_reference         = $this->params->get("amazon_caller_reference");

if ($amazon_is_test == 1)
{
	$amazon_url = 'https://authorize.payments-sandbox.amazon.com/pba/paypipeline';
}
else
{
	$amazon_url = 'https://authorize.payments.amazon.com/pba/paypipeline';
}

?>
<!--  Standard Payment -->
<form action="<?php echo $amazon_url ?>" method="post" id="amazoncheckout">
	<input type="hidden" name="immediateReturn" value="1">
	<input type="hidden" name="collectShippingAddress" value="1">
	<input type="hidden" name="signatureVersion" value="2">
	<input type="hidden" name="signatureMethod" value="<?php echo $amazon_signature_method ?>">
	<input type="hidden" name="accessKey" value="<?php echo $amazon_accessKey ?>">
	<input type="hidden" name="recipientEmail" value="<?php echo $amazon_recipientEmail ?>">
	<?php if ($amazon_payment_method == 'MARKETPLACE') : ?>
		<input type="hidden" name="variableMarketplaceFee" value="<?php echo $amazon_variable_marketplace_fee ?>">
		<input type="hidden" name="fixedMarketplaceFee" value="<?php echo $amazon_fixed_marketplace_fee ?>">
	<?php endif; ?>
	<input type="hidden" name="referenceId"
	       value="<?php echo JText::_('COM_REDSHOP_MY_TRANSACTION') ?>-<?php echo $data['order_id']; ?>">
	<input type="hidden" name="amount" value="<?php echo $data['carttotal']; ?>">
	<input type="hidden" name="signature" value="<?php echo $amazon_signature ?>">
	<input type="hidden" name="isDonationWidget" value="0">
	<input type="hidden" name="description" value="pay for order no.<?php echo $data['order_id']; ?> ">
	<input type="hidden" name="amazonPaymentsAccountId" value="<?php echo $amazonPaymentsAccountId ?>">
	<input type="hidden" name="ipnUrl"
	       value="<?php echo JURI::base(); ?>index.php?option=com_redshop&view=order_detail&controller=order_detail&task=notify_payment&payment_plugin=rs_payment_amazoncheckout&Itemid=<?php echo $_REQUEST['Itemid']; ?>&orderid=<?php echo $data['order_id'] ?>">
	<input type="hidden" name="returnUrl"
	       value="<?php echo JURI::base(); ?>index.php?option=com_redshop&view=order_detail&controller=order_detail&payment_plugin=rs_payment_amazoncheckout&task=notify_payment&Itemid=<?php echo $_REQUEST['Itemid']; ?>&orderid=<?php echo $data['order_id'] ?>">
	<input type="hidden" name="processImmediate" value="1">
	<input type="hidden" name="cobrandingStyle" value="logo">
	<input type="hidden" name="abandonUrl"
	       value="<?php echo JURI::base(); ?>index.php?option=com_redshop&view=order_detail&controller=order_detail&payment_plugin=rs_payment_amazoncheckout&task=notify_payment&Itemid=<?php echo $_REQUEST['Itemid']; ?>&orderid=<?php echo $data['order_id'] ?>">
	<input type="image" src="http://g-ecx.images-amazon.com/images/G/01/asp/beige_small_paynow_withmsg_whitebg.gif"
	       border="0">
</form>

<!--  MarketPlace Payment -->
<?php if ($amazon_payment_method == 'MARKETPLACE'): ?>
	<form action="<?php echo $amazon_url ?>" method="get" id="amazoncheckout">
		<input type="hidden" name="maxVariableFee" value="<?php echo $amazon_variable_marketplace_fee ?>">
		<input type="hidden" name="signature" value="<?php echo $amazon_signature ?>">
		<input type="hidden" name="maxFixedFee" value="0.2">
		<input type="hidden" name="signatureVersion" value="2">
		<input type="hidden" name="signatureMethod" value="<?php echo $amazon_signature_method ?>">
		<input type="hidden" name="callerAccountId" value="<?php echo $amazonPaymentsAccountId ?>">
		<input type="hidden" name="recipientPaysFee" value="true">
		<input type="hidden" name="returnURL"
		       value="<?php echo JURI::base(); ?>index.php?option=com_redshop&view=order_detail&controller=order_detail&payment_plugin=rs_payment_amazoncheckout&task=notify_payment&Itemid=<?php echo $_REQUEST['Itemid']; ?>&orderid=<?php echo $data['order_id'] ?>">
		<input type="hidden" name="collectEmailAddress" value="true">
		<input type="hidden" name="callerReference" value="<?php echo $amazon_caller_reference ?>">
		<input type="hidden" name="callerKey" value="<?php echo $amazon_accessKey ?>">
		<input type="hidden" name="pipelineName" value="Recipient">
		<input type="image" src="http://g-ecx.images-amazon.com/images/G/01/asp/MarketPlaceFeeWithLogo.gif" border="0">
	</form>
<?php endif; ?>
<script>
	document.getElementById("amazoncheckout").submit();
</script>
