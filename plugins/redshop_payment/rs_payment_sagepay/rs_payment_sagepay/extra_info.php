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

$strRedirecturl = "https://test.sagepay.com/simulator/vspformgateway.asp";
$uri =& JURI::getInstance();
$url = $uri->root();
$user = JFactory::getUser();
$db = JFactory::getDBO();
$Itemid = $_REQUEST['Itemid'];

if ($this->_params->get("payment_method") == "TEST")
{
	$strRedirecturl = "https://test.sagepay.com/gateway/service/vspform-register.vsp";
}
else if ($this->_params->get("payment_method") == "LIVE")
{
	$strRedirecturl = "https://live.sagepay.com/gateway/service/vspform-register.vsp";
}

$strTransactionType = strtoupper($this->_params->get("sagepay_transactiontype"));
$strVendorName = $this->_params->get("sagepay_vendorname");
$VendorEMail = $this->_params->get("sagepay_vendoremail");
$strEncryptionPassword = $this->_params->get("sagepay_encryptpass");
$strProtocol = $this->_params->get("sagepay_protocol");
$intRandNum = rand(0, 32000) * rand(0, 32000);

$strVendorTxCode = $strVendorName . "-" . $intRandNum;

if (!$this->_params->get("enable_shipping"))
{
	$shippingaddresses = $billingaddresses;
}

$sql = "SELECT cc.country_2_code FROM " . $this->_table_prefix . "country AS cc LEFT JOIN " . $this->_table_prefix . "users_info AS ui ON cc.country_3_code  = ui.country_code  WHERE ui.address_type ='BT' and ui.user_id='" . $data['order']->user_id . "'";
$db->setQuery($sql);
$country_code = $db->loadResult();

$sql_st = "SELECT cc.country_2_code FROM " . $this->_table_prefix . "country AS cc LEFT JOIN " . $this->_table_prefix . "users_info AS ui ON cc.country_3_code  = ui.country_code  WHERE ui.address_type ='ST' and ui.user_id='" . $data['order']->user_id . "'";
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
		"Currency"           => CURRENCY_CODE,
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
		"Currency"           => CURRENCY_CODE,
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


//  if($data['billinginfo']->country_2_code!='US')
//      unset($crypt_variables['BillingState']);
//  if($data['shippinginfo']->country_2_code!='US')
//      unset($crypt_variables['DeliveryState']);

$strPost = "";
$i = 0;
foreach ($crypt_variables as $name => $value)
{

	$strPost .= $name . "=" . $value;

	if ($i < count($crypt_variables) - 1)
	{
		$strPost .= "&";
	}

	$i++;
}

$strCrypt = base64Encode(SimpleXor($strPost, $strEncryptionPassword));

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


function base64Encode($plain)
{
	// Initialise output variable
	$output = "";

	// Do encoding
	$output = base64_encode($plain);

	// Return the result
	return $output;
}

function base64Decode($scrambled)
{
	// Initialise output variable
	$output = "";

	// Fix plus to space conversion issue
	$scrambled = str_replace(" ", "+", $scrambled);

	// Do encoding
	$output = base64_decode($scrambled);

	// Return the result
	return $output;
}

function simpleXor($InString, $Key)
{
	// Initialise key array
	$KeyList = array();
	// Initialise out variable
	$output = "";

	// Convert $Key into array of ASCII values
	for ($i = 0; $i < strlen($Key); $i++)
	{
		$KeyList[$i] = ord(substr($Key, $i, 1));
	}

	// Step through string a character at a time
	for ($i = 0; $i < strlen($InString); $i++)
	{
		// Get ASCII code from string, get ASCII code from key (loop through with MOD), XOR the two, get the character from the result
		// % is MOD (modulus), ^ is XOR
		$output .= chr(ord(substr($InString, $i, 1)) ^ ($KeyList[$i % strlen($Key)]));
	}

	// Return the result
	return $output;
}
?>
<script>
	document.frmsagepay.submit();
</script>