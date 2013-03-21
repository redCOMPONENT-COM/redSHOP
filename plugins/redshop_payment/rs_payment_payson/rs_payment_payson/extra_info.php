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

$buyeremail = $data['billinginfo']->user_email;
$buyerfirstname = $data['billinginfo']->firstname;
$buyerlastname = $data['billinginfo']->lastname;

$pays_md5 = $this->_params->get("pays_md5");
$pays_agentid = $this->_params->get("pays_agentid");
$pays_selleremail = $this->_params->get("pays_selleremail");
$pays_seller_first_name = $this->_params->get("pays_seller_first_name");
$pays_seller_last_name = $this->_params->get("pays_seller_last_name");
$pays_purchase_url = $this->_params->get("pays_purchase_url");
$pays_description = $this->_params->get("pays_description");
$pays_shipping_type = $this->_params->get("pays_shipping_type");
$pays_shipping_mode = $this->_params->get("pays_shipping_mode");
$pays_cust_receipt = $this->_params->get("pays_cust_receipt");
$pays_method = $this->_params->get("pays_method");
$testMode = $this->_params->get("testMode");

$extracost = 0;
$guaranteeoffered = 1;

// Convert price into SEK
$currency = new convertPrice;
$cost_dotsep = $currency->convert($data['carttotal'], '', 'SEK');
$cost = number_format($cost_dotsep, 2);
$cost = str_replace(',', '', $cost);
$cost = str_replace('.', ',', $cost);


if ($cost_dotsep < 10)
{
	echo "The purchase amount must be greater than 10 sec at betalningmed Payson";
	exit ();

}

if ($testMode == '1')
{
	$url = "https://www.payson.se/testagent/default.aspx";
}
else
{
	$url = "https://www.payson.se/merchant/default.aspx";
}

$selleremail = $pays_selleremail;
$okurl = JURI::base() . "index.php?option=com_redshop&view=order_detail&controller=order_detail&task=notify_payment&payment_plugin=rs_payment_payson&orderid=" . $data['order_id'];


$MD5string = $selleremail . ":" . $cost . ":" . $extracost . ":" . $okurl . ":" . $guaranteeoffered . $pays_md5;
$md5code = md5($MD5string);



$post_variables = Array("SellerEmail"      => $selleremail,
                        "BuyerEmail"       => $buyeremail,
                        "BuyerFirstName"   => $buyerfirstname,
                        "BuyerLastName"    => $buyerlastname,
                        "Description"      => $pays_description,
                        "Cost"             => $cost,
                        "ExtraCost"        => $extracost,
                        "RefNr"            => $data['order_id'],
                        "OkUrl"            => $okurl,
                        "AgentId"          => $pays_agentid,
                        "MD5"              => $md5code,
                        "GuaranteeOffered" => $guaranteeoffered,
                        "CustomReceipt"    => $pays_cust_receipt,
                        "PaymentMethod"    => $pays_method,
                        "SellerFirstName"  => $pays_seller_first_name,
                        "SellerLastName"   => $pays_seller_last_name,
                        "PurchaseUrl"      => $pays_purchase_url,
                        "LongDescription"  => $pays_description,
                        "ShippingType"     => $pays_shipping_type,
                        "ShippingLabel"    => $pays_shipping_mode);

foreach ($post_variables as $key => $val)
{
	$poststring .= "<input type='hidden' name='$key' value='$val' />";
}

?>
<html>
<head></head>
<body>
<form id="paysonForm" name="paysonForm" action="<?php
echo $url?>"
      method="post">
	<?php
	echo $poststring?>
</form>
<script type="text/javascript" language="JavaScript">
	document.paysonForm.submit();
</script>
</body>
</html>


