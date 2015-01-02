<?php
/**
 * @package     RedSHOP
 * @subpackage  Plugin
 *
 * @copyright   Copyright (C) 2005 - 2015 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

$uri = JURI::getInstance();
$url = $uri->root();
$app = JFactory::getApplication();

$eWAYcustomer_id     = $this->params->get("customer_id");
$eWAYusername        = $this->params->get("username");
$eWAYpagetitle       = $this->params->get("pagetitle");
$eWAYpagedescription = $this->params->get("pagedescription");
$eWAYpagefooter      = $this->params->get("pagefooter");
$eWAYlanguage        = $this->params->get("ewaynz_language");
$eWAYcompanylogo     = $this->params->get("companylogo");
$eWAYpagebanner      = $this->params->get("pagebanner");
$eWay_companyname    = $this->params->get("merchant_companyname");

$currencyClass = new CurrencyHelper;
$item_price = $currencyClass->convert($data['carttotal'], '', 'NZD');
$item_price = round($item_price);
$item_price = number_format($item_price, 2);
$ewayurl .= "?CustomerID=" . $eWAYcustomer_id;
$ewayurl .= "&UserName=" . $eWAYusername;
$ewayurl .= "&Amount=" . $item_price;
$ewayurl .= "&Currency=NZD";
$ewayurl .= "&PageTitle=" . $eWAYpagetitle;
$ewayurl .= "&PageDescription=" . $eWAYpagedescription;
$ewayurl .= "&PageFooter=" . $eWAYpagefooter;
$ewayurl .= "&Language=" . $eWAYlanguage;
$ewayurl .= "&CompanyName=" . $eWay_companyname;
$ewayurl .= "&CustomerFirstName=" . $data['billinginfo']->firstname;
$ewayurl .= "&CustomerLastName=" . $data['billinginfo']->lastname;
$ewayurl .= "&CustomerAddress=" . $data['billinginfo']->address;
$ewayurl .= "&CustomerCity=" . $data['billinginfo']->city;
$ewayurl .= "&CustomerState=" . $data['billinginfo']->state_code;
$ewayurl .= "&CustomerPostCode=" . $data['billinginfo']->zipcode;
$ewayurl .= "&CustomerCountry=" . $data['billinginfo']->country_code;
$ewayurl .= "&CustomerEmail=" . $data['billinginfo']->user_email;
$ewayurl .= "&CustomerPhone=" . $data['billinginfo']->phone;
$ewayurl .= "&InvoiceDescription=Individual%20InvoiceDescription";
$ewayurl .= "&CompanyLogo=" . $eWAYcompanylogo;
$ewayurl .= "&PageBanner=" . $eWAYpagebanner;
$ewayurl .= "&MerchantReference=" . $data['order_id'];
$ewayurl .= "&MerchantReference=Inv" . $data['order_id'];
$ewayurl .= "&MerchantOption1=Option1";
$ewayurl .= "&MerchantOption2=Option2";
$ewayurl .= "&MerchantOption3=Option2";
$ewayurl .= "&ModifiableCustomerDetails=false";

$returnurl = JURI::base() . "plugins/redshop_payment/rs_payment_ewaynz/rs_payment_ewaynz/eway_response.php";
$cancelurl = JURI::base() . "plugins/redshop_payment/rs_payment_ewaynz/rs_payment_ewaynz/eway_response.php";

$ewayurl = "CustomerID=" . $eWAYcustomer_id . "&UserName=" . $eWAYusername . "&Amount=" . $data['carttotal'] . "&Currency=NZD&PageTitle=" . $eWAYpagetitle . "&PageDescription=" . $eWAYpagedescription . "&PageFooter=" . $eWAYpagefooter . "&Language=" . $eWAYlanguage . "&CompanyName=" . $eWay_companyname . "&CustomerFirstName=" . $data['billinginfo']->firstname . "&CustomerLastName=" . $data['billinginfo']->lastname . "&CustomerAddress=" . $data['billinginfo']->address . "&CustomerState=" . $data['billinginfo']->state_code . "&CustomerPostCode=" . $data['billinginfo']->zipcode . "&CustomerCountry=" . $data['billinginfo']->country_code . "&CustomerEmail=" . $data['billinginfo']->user_email . "&CustomerPhone=" . $data['billinginfo']->phone . "&InvoiceDescription=Individual%20InvoiceDescription&CancelURL=" . $cancelurl . "&ReturnUrl=" . $returnurl . "&CompanyLogo=https://www.yoursite.com/securelogo.jpg&PageBanner=" . $eWAYpagebanner . "&ModifiableCustomerDetails=true&MerchantReference=" . $data['order_id'] . "&MerchantInvoice=Inv" . $data['order_id'] . "&MerchantOption1=" . $data['order_id'] . "&MerchantOption2=Option2&MerchantOption3=Option3&ModifiableCustomerDetails=false";
$spacereplace = str_replace(" ", "%20", $ewayurl);

$posturl = "https://nz.ewaygateway.com/Request/?$spacereplace";

$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $posturl);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
curl_setopt($ch, CURLOPT_HEADER, 1);
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);

if (CURL_PROXY_REQUIRED == 'True')
{
	$proxy_tunnel_flag = (defined('CURL_PROXY_TUNNEL_FLAG') && strtoupper(CURL_PROXY_TUNNEL_FLAG) == 'FALSE') ? false : true;
	curl_setopt($ch, CURLOPT_HTTPPROXYTUNNEL, $proxy_tunnel_flag);
	curl_setopt($ch, CURLOPT_PROXYTYPE, CURLPROXY_HTTP);
	curl_setopt($ch, CURLOPT_PROXY, CURL_PROXY_SERVER_DETAILS);
}

$response = curl_exec($ch);

$responsemode = $this->fetch_data($response, '<result>', '</result>');
$responseurl = $this->fetch_data($response, '<uri>', '</uri>');

if ($responsemode == "True")
{
	$app->redirect($responseurl);
}
else
{
	$app->redirect(JURI::base() . "index.php?option=com_redshop&view=order_detail&oid=" . $data['order_id']);
}
