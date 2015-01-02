<?php
/**
 * @package     RedSHOP
 * @subpackage  Plugin
 *
 * @copyright   Copyright (C) 2008 - 2015 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

$uri    = JURI::getInstance();
$url    = $uri->root();
$user   = JFactory::getUser();
$db     = JFactory::getDbo();

$md5key_demo  = "AAAABBBBCCCCDDDDEEEEFFFFGGGGHHHH";
$md5key_drift = $this->params->get("md5key");
$url_demo     = "https://www.certitrade.net/webshophtml/e/auth.php";
$url_eko      = "https://www.certitrade.net/webshophtml/e/eko.php";
$url_drift    = "https://payment.certitrade.net/webshophtml/e/auth.php";

$post_variables          = array();
$returned_post_variables = array();
$add_submit_html         = true;

if ($this->params->get("is_test") == 1)
{
	$merchant_id = "1111";
	$md5key      = $md5key_demo;
	$gatewayurl  = $url_demo;
}
else
{
	$merchant_id = $this->params->get("merchant_id");
	$md5key      = $md5key_drift;
	$gatewayurl  = $url_drift;
}

$baseurl = JURI::root();

$rev     = "E";
$orderid = $data['order_id'];
$Itemid  = $_REQUEST['Itemid'];

// Convert price to SEK(752)
$currency = new CurrencyHelper;
$amount   = $currency->convert($data['carttotal'], '', 'SEK');

// SEK
$currency    = "752";
$retururl    = JURI::base() . "index.php?option=com_redshop&view=order_detail&Itemid=$Itemid&oid=" . $data['order_id'];
$cancelurl   = JURI::base() . "index.php";
$declineurl  = JURI::base() . "index.php?option=com_redshop&view=order_detail&Itemid=$Itemid&oid=" . $data['order_id'];
$approveurl  = JURI::base() . "index.php?option=com_redshop&view=order_detail&Itemid=$Itemid&controller=order_detail&payment_plugin=rs_payment_certitrade&task=notify_payment&orderid=" . $data['order_id'];
$returwindow = "";
$lang        = "sv";
$cust_id     = $data['billinginfo']->users_info_id;
$cust_name   = $data['billinginfo']->firstname . " " . $data['billinginfo']->lastname;
$company     = $data['billinginfo']->company_name;

if ($company != "")
{
	$cust_name .= " " . $company;
}

$cust_address1   = $data['billinginfo']->address;
$cust_address2   = $data['billinginfo']->state_code;
$cust_address3   = "";
$cust_zip        = $data['billinginfo']->zipcode;
$cust_city       = $data['billinginfo']->city;
$cust_phone      = $data['billinginfo']->phone;
$cust_email      = $data['billinginfo']->user_email;
$cust_country    = $data['billinginfo']->country_code;
$debug           = "0";
$httpdebug       = "0";
$timeout         = "";
$delayed_capture = "0";
$max_delay_days  = "";
$transp1         = "";
$transp2         = "";


$md5str = $md5key;
$md5str .= $merchant_id;
$md5str .= $rev;
$md5str .= $orderid;
$md5str .= $amount;
$md5str .= $currency;
$md5str .= $retururl;
$md5str .= $approveurl;
$md5str .= $declineurl;
$md5str .= $cancelurl;
$md5str .= $returwindow;
$md5str .= $lang;
$md5str .= $cust_id;
$md5str .= $cust_name;
$md5str .= $cust_address1;
$md5str .= $cust_address2;
$md5str .= $cust_address3;
$md5str .= $cust_zip;
$md5str .= $cust_city;
$md5str .= $cust_phone;
$md5str .= $cust_email;
$md5str .= $connection;
$md5str .= $acquirer;
$md5str .= $debug;
$md5str .= $httpdebug;

$md5code = md5($md5str);
$this->md5code = $md5code;

// Fill array with class variables
$post_variables = array(
	"merchantid"      => $merchant_id,
	"md5code"         => $md5code,
	"rev"             => $rev,
	"orderid"         => $orderid,
	"amount"          => $amount,
	"currency"        => $currency,
	"retururl"        => $retururl,
	"returmetod"      => $returmetod,
	"cancelurl"       => $cancelurl,
	"declineurl"      => $declineurl,
	"approveurl"      => $approveurl,
	"returwindow"     => $returwindow,
	"lang"            => $lang,
	"cust_id"         => $cust_id,
	"cust_name"       => $cust_name,
	"cust_address1"   => $cust_address1,
	"cust_address2"   => $cust_address2,
	"cust_address3"   => $cust_address3,
	"cust_zip"        => $cust_zip,
	"cust_city"       => $cust_city,
	"cust_phone"      => $cust_phone,
	"cust_email"      => $cust_email,
	"cust_country"    => $cust_country,
	"connection"      => $connection,
	"acquirer"        => $acquirer,
	"DEBUG"           => $debug,
	"HTTPDEBUG"       => $httpdebug,
	"timeout"         => $timeout,
	"delayed_capture" => $delayed_capture,
	"max_delay_days"  => $max_delay_days,
	"transp1"         => $transp1,
	"transp2"         => $transp2
);

// The submit button
$add_submit_html = false;

// The start tag
$add_form_tag       = false;
$html_hidden_params = "";

foreach ($post_variables AS $key => $val)
{
	$html_hidden_params .= "<input type='hidden' name='$key' value='$val' />";
}

$form_head = "<form id = \"certitradeform\" name=\"certitradeform\" action=\" ";
$form_head .= $gatewayurl;
$form_head .= " \" method=\"post\"> ";

echo $form_head;
echo $html_hidden_params;
echo  "</form>";
?>
<script>
	document.getElementById("certitradeform").submit();
</script>
