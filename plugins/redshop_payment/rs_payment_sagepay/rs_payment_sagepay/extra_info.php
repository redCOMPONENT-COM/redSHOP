<?php
/**
 * @package     RedSHOP
 * @subpackage  Plugin
 *
 * @copyright   Copyright (C) 2008 - 2015 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

$strRedirecturl = "https://test.sagepay.com/simulator/vspformgateway.asp";
$uri    = JURI::getInstance();
$url    = $uri->root();
$user   = JFactory::getUser();
$db     = JFactory::getDbo();
$input  = JFactory::getApplication()->input;
$Itemid = $input->getInt('Itemid');

if ($this->params->get("payment_method") == "TEST")
{
	$strRedirecturl = "https://test.sagepay.com/gateway/service/vspform-register.vsp";
}
elseif ($this->params->get("payment_method") == "LIVE")
{
	$strRedirecturl = "https://live.sagepay.com/gateway/service/vspform-register.vsp";
}

$strTransactionType    = strtoupper($this->params->get("sagepay_transactiontype"));
$strVendorName         = $this->params->get("sagepay_vendorname");
$VendorEMail           = $this->params->get("sagepay_vendoremail");
$strEncryptionPassword = $this->params->get("sagepay_encryptpass");
$strProtocol           = $this->params->get("sagepay_protocol");
$intRandNum            = rand(0, 32000) * rand(0, 32000);

$strVendorTxCode = $strVendorName . "-" . $intRandNum;

if (!$this->params->get("enable_shipping"))
{
	$shippingaddresses = $billingaddresses;
}

$sql = "SELECT cc.country_2_code FROM #__redshop_country AS cc LEFT JOIN #__redshop_users_info AS ui ON cc.country_3_code  = ui.country_code  WHERE ui.address_type ='BT' and ui.user_id='" . $data['order']->user_id . "'";
$db->setQuery($sql);
$country_code = $db->loadResult();

$sql_st = "SELECT cc.country_2_code FROM #__redshop_country AS cc LEFT JOIN #__redshop_users_info AS ui ON cc.country_3_code  = ui.country_code  WHERE ui.address_type ='ST' and ui.user_id='" . $data['order']->user_id . "'";
$db->setQuery($sql_st);
$country_code_st = $db->loadResult();

if ($country_code != "US")
{
	$data['billinginfo']->state_2_code = "";
}

if ($country_code_st != "US")
{
	$data['shippinginfo']->state_2_code = "";
}

if ($data['shippinginfo']->address != "" && $data['shippinginfo']->address != "0")
{
	if ($country_code_st == "")
	{
		$country_code_st = $country_code;
	}

	if ($country_code_st != "US")
	{
		$data['shippinginfo']->state_2_code = "";
	}
	else
	{
		$data['shippinginfo']->state_2_code = $data['billinginfo']->state_2_code;
	}

	$crypt_variables = Array(
		"VendorTxCode"       => $strVendorTxCode,
		"VendorEMail"        => $VendorEMail,
		"Amount"             => $data['carttotal'],
		"Currency"           => Redshop::getConfig()->get('CURRENCY_CODE'),
		"Description"        => $data['order_id'],
		"SuccessURL"         => JURI::base() . "index.php?option=com_redshop&view=order_detail&tmpl=component&controller=order_detail&task=notify_payment&payment_plugin=rs_payment_sagepay&Itemid=$Itemid&orderid=" . $data['order_id'],
		"FailureURL"         => JURI::base() . "index.php?option=com_redshop&view=order_detail&tmpl=component&controller=order_detail&task=notify_payment&payment_plugin=rs_payment_sagepay&Itemid=$Itemid&orderid=" . $data['order_id'],
		"CustomerName"       => urlencode($data['billinginfo']->firstname),
		"SendEMail"          => 1,
		"BillingFirstnames"  => urlencode($data['billinginfo']->firstname),
		"BillingSurname"     => urlencode($data['billinginfo']->lastname),
		"BillingAddress1"    => $data['billinginfo']->address,
		"BillingCity"        => $data['billinginfo']->city,
		"BillingPostCode"    => $data['billinginfo']->zipcode,
		"BillingCountry"     => $country_code,
		"BillingState"       => $data['billinginfo']->state_2_code,
		"BillingPhone"       => $data['billinginfo']->phone,
		"DeliveryFirstnames" => urlencode($data['shippinginfo']->firstname),
		"DeliverySurname"    => urlencode($data['shippinginfo']->lastname),
		"DeliveryAddress1"   => $data['shippinginfo']->address,
		"DeliveryCity"       => urlencode($data['shippinginfo']->city),
		"DeliveryPostCode"   => $data['shippinginfo']->zipcode,
		"DeliveryCountry"    => $country_code_st,
		"DeliveryState"      => $data['shippinginfo']->state_2_code,
		"DeliveryPhone"      => $data['shippinginfo']->phone,
		"AllowGiftAid"       => 0,
		"ApplyAVSCV2"        => 0,
		"Apply3DSecure"      => 0
	);
}
else
{
	$crypt_variables = Array(
		"VendorTxCode"       => $strVendorTxCode,
		"VendorEMail"        => $VendorEMail,
		"Amount"             => $data['carttotal'],
		"Currency"           => Redshop::getConfig()->get('CURRENCY_CODE'),
		"Description"        => $data['order_id'],
		"SuccessURL"         => JURI::base() . "index.php?option=com_redshop&view=order_detail&tmpl=component&controller=order_detail&task=notify_payment&payment_plugin=rs_payment_sagepay&Itemid=$Itemid&orderid=" . $data['order_id'],
		"FailureURL"         => JURI::base() . "index.php?option=com_redshop&view=order_detail&tmpl=component&controller=order_detail&task=notify_payment&payment_plugin=rs_payment_sagepay&Itemid=$Itemid&orderid=" . $data['order_id'],
		"CustomerName"       => urlencode($data['billinginfo']->firstname),
		"SendEMail"          => 1,
		"BillingFirstnames"  => urlencode($data['billinginfo']->firstname),
		"BillingSurname"     => urlencode($data['billinginfo']->lastname),
		"BillingAddress1"    => $data['billinginfo']->address,
		"BillingCity"        => $data['billinginfo']->city,
		"BillingPostCode"    => $data['billinginfo']->zipcode,
		"BillingCountry"     => $country_code,
		"BillingState"       => $data['billinginfo']->state_2_code,
		"BillingPhone"       => $data['billinginfo']->phone,
		"DeliveryFirstnames" => urlencode($data['billinginfo']->firstname),
		"DeliverySurname"    => urlencode($data['billinginfo']->lastname),
		"DeliveryAddress1"   => $data['billinginfo']->address,
		"DeliveryCity"       => urlencode($data['billinginfo']->city),
		"DeliveryPostCode"   => $data['billinginfo']->zipcode,
		"DeliveryCountry"    => $country_code,
		"DeliveryState"      => $data['billinginfo']->state_2_code,
		"DeliveryPhone"      => $data['billinginfo']->phone,
		"AllowGiftAid"       => 0,
		"ApplyAVSCV2"        => 0,
		"Apply3DSecure"      => 0
	);
}

$crypt_variables['language'] = $this->params->get('language', 'en');

$strCrypt = '';

if ($strEncryptionPassword != null)
{
	$strPost = self::arrayToQueryString($crypt_variables);

	$strCrypt = self::encryptAes($strPost, $strEncryptionPassword);
}

$post_variables = Array(

	"Vendor"      => $strVendorName,
	"TxType"      => $strTransactionType,
	"VPSProtocol" => $strProtocol,
	"Crypt"       => $strCrypt
);

echo '<form action="' . $strRedirecturl . '" method="post" name="frmsagepay">';

foreach ($post_variables as $name => $value)
{
	echo '<input type="hidden" name="' . $name . '" value="' . $value . '" />';
}

echo '</form>';
?>
<script>
	document.frmsagepay.submit();
</script>
