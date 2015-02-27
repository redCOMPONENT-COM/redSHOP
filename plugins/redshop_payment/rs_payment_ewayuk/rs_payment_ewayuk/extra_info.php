<?php
/**
 * @package     RedSHOP
 * @subpackage  Plugin
 *
 * @copyright   Copyright (C) 2008 - 2015 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

$uri                 = JURI::getInstance();
$url                 = $uri->root();
$user                = JFactory::getUser();
$app                 = JFactory::getApplication();
$Itemid              = $app->input->getInt('Itemid');

$eWAYcustomer_id     = $this->params->get("customer_id");
$eWAYusername        = $this->params->get("username");
$eWAYpagetitle       = $this->params->get("pagetitle");
$eWAYpagedescription = $this->params->get("pagedescription");
$eWAYpagefooter      = $this->params->get("pagefooter");
$eWAYlanguage        = $this->params->get("ewayuk_language");
$eWAYcompanylogo     = $this->params->get("companylogo");
$eWAYpagebanner      = $this->params->get("pagebanner");
$eWay_companyname    = $this->params->get("merchant_companyname");

$currencyClass = new CurrencyHelper;
$item_price = $currencyClass->convert($data['carttotal'], '', 'GBP');
$item_price = round($item_price);
$item_price = number_format($item_price, 2, '.', '');
$ewayurl = array();
$ewayurl['CustomerID'] = $eWAYcustomer_id;
$ewayurl['UserName'] = $eWAYusername;
$ewayurl['Amount'] = $item_price;
$ewayurl['Currency'] = "GBP";
$ewayurl['PageTitle'] = $eWAYpagetitle;
$ewayurl['PageDescription'] = $eWAYpagedescription;
$ewayurl['PageFooter'] = $eWAYpagefooter;
$ewayurl['Language'] = $eWAYlanguage;
$ewayurl['CompanyName'] = $eWay_companyname;
$ewayurl['CustomerFirstName'] = $data['billinginfo']->firstname;
$ewayurl['CustomerLastName'] = $data['billinginfo']->lastname;
$ewayurl['CustomerAddress'] = $data['billinginfo']->address;
$ewayurl['CustomerCity'] = $data['billinginfo']->city;
$ewayurl['CustomerState'] = $data['billinginfo']->state_code;
$ewayurl['CustomerPostCode'] = $data['billinginfo']->zipcode;
$ewayurl['CustomerCountry'] = $data['billinginfo']->country_code;
$ewayurl['CustomerEmail'] = $data['billinginfo']->user_email;
$ewayurl['CustomerPhone'] = $data['billinginfo']->phone;
$ewayurl['InvoiceDescription'] = "Individual%20InvoiceDescription";
$ewayurl['CompanyLogo'] = $eWAYcompanylogo;
$ewayurl['PageBanner'] = $eWAYpagebanner;
$ewayurl['MerchantReference'] = $data['order_id'];
$ewayurl['MerchantReference'] = "Inv" . $data['order_id'];
$ewayurl['MerchantOption1'] = $data['order_id'];
$ewayurl['MerchantOption2'] = "Option2";
$ewayurl['MerchantOption3'] = "Option2";
$ewayurl['ModifiableCustomerDetails'] = "false";
$ewayurl['ReturnUrl'] = JURI::base() . "index.php?tmpl=component&option=com_redshop&view=order_detail&controller=order_detail&"
	. "task=notify_payment&payment_plugin=rs_payment_ewayuk&Itemid=$Itemid&orderid=" . $data['order_id'];
$ewayurl['CancelURL'] = JURI::base() . "index.php?tmpl=component&option=com_redshop&view=order_detail&controller=order_detail&"
	. "task=notify_payment&payment_plugin=rs_payment_ewayuk&Itemid=$Itemid&orderid=" . $data['order_id'];

$ewayurl = http_build_query($ewayurl, '', '&', PHP_QUERY_RFC3986);
$posturl = "https://payment.ewaygateway.com/Request/?" . $ewayurl;

$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $posturl);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
curl_setopt($ch, CURLOPT_HEADER, 1);
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);

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
