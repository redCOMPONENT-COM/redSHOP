<?php
/**
 * @copyright Copyright (C) 2010 redCOMPONENT.com. All rights reserved.
 * @license GNU/GPL, see license.txt or http://www.gnu.org/copyleft/gpl.html
 * Developed by email@recomponent.com - redCOMPONENT.com
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
require_once(JPATH_SITE.DS.'components'.DS.'com_redshop'.DS.'helpers'.DS.'product.php');
$producthelper = new producthelper();

$uri =& JURI::getInstance();
$url= $uri->root();
$user=JFactory::getUser();
$sessionid = session_id();

function get_iso_code($code) {
switch ($code) {
case "ADP": return "020"; break;
case "AED": return "784"; break;
case "AFA": return "004"; break;
case "ALL": return "008"; break;
case "AMD": return "051"; break;
case "ANG": return "532"; break;
case "AOA": return "973"; break;
case "ARS": return "032"; break;
case "AUD": return "036"; break;
case "AWG": return "533"; break;
case "AZM": return "031"; break;
case "BAM": return "977"; break;
case "BBD": return "052"; break;
case "BDT": return "050"; break;
case "BGL": return "100"; break;
case "BGN": return "975"; break;
case "BHD": return "048"; break;
case "BIF": return "108"; break;
case "BMD": return "060"; break;
case "BND": return "096"; break;
case "BOB": return "068"; break;
case "BOV": return "984"; break;
case "BRL": return "986"; break;
case "BSD": return "044"; break;
case "BTN": return "064"; break;
case "BWP": return "072"; break;
case "BYR": return "974"; break;
case "BZD": return "084"; break;
case "CAD": return "124"; break;
case "CDF": return "976"; break;
case "CHF": return "756"; break;
case "CLF": return "990"; break;
case "CLP": return "152"; break;
case "CNY": return "156"; break;
case "COP": return "170"; break;
case "CRC": return "188"; break;
case "CUP": return "192"; break;
case "CVE": return "132"; break;
case "CYP": return "196"; break;
case "CZK": return "203"; break;
case "DJF": return "262"; break;
case "DKK": return "208"; break;
case "DOP": return "214"; break;
case "DZD": return "012"; break;
case "ECS": return "218"; break;
case "ECV": return "983"; break;
case "EEK": return "233"; break;
case "EGP": return "818"; break;
case "ERN": return "232"; break;
case "ETB": return "230"; break;
case "EUR": return "978"; break;
case "FJD": return "242"; break;
case "FKP": return "238"; break;
case "GBP": return "826"; break;
case "GEL": return "981"; break;
case "GHC": return "288"; break;
case "GIP": return "292"; break;
case "GMD": return "270"; break;
case "GNF": return "324"; break;
case "GTQ": return "320"; break;
case "GWP": return "624"; break;
case "GYD": return "328"; break;
case "HKD": return "344"; break;
case "HNL": return "340"; break;
case "HRK": return "191"; break;
case "HTG": return "332"; break;
case "HUF": return "348"; break;
case "IDR": return "360"; break;
case "ILS": return "376"; break;
case "INR": return "356"; break;
case "IQD": return "368"; break;
case "IRR": return "364"; break;
case "ISK": return "352"; break;
case "JMD": return "388"; break;
case "JOD": return "400"; break;
case "JPY": return "392"; break;
case "KES": return "404"; break;
case "KGS": return "417"; break;
case "KHR": return "116"; break;
case "KMF": return "174"; break;
case "KPW": return "408"; break;
case "KRW": return "410"; break;
case "KWD": return "414"; break;
case "KYD": return "136"; break;
case "KZT": return "398"; break;
case "LAK": return "418"; break;
case "LBP": return "422"; break;
case "LKR": return "144"; break;
case "LRD": return "430"; break;
case "LSL": return "426"; break;
case "LTL": return "440"; break;
case "LVL": return "428"; break;
case "LYD": return "434"; break;
case "MAD": return "504"; break;
case "MDL": return "498"; break;
case "MGF": return "450"; break;
case "MKD": return "807"; break;
case "MMK": return "104"; break;
case "MNT": return "496"; break;
case "MOP": return "446"; break;
case "MRO": return "478"; break;
case "MTL": return "470"; break;
case "MUR": return "480"; break;
case "MVR": return "462"; break;
case "MWK": return "454"; break;
case "MXN": return "484"; break;
case "MXV": return "979"; break;
case "MYR": return "458"; break;
case "MZM": return "508"; break;
case "NAD": return "516"; break;
case "NGN": return "566"; break;
case "NIO": return "558"; break;
case "NOK": return "578"; break;
case "NPR": return "524"; break;
case "NZD": return "554"; break;
case "OMR": return "512"; break;
case "PAB": return "590"; break;
case "PEN": return "604"; break;
case "PGK": return "598"; break;
case "PHP": return "608"; break;
case "PKR": return "586"; break;
case "PLN": return "985"; break;
case "PYG": return "600"; break;
case "QAR": return "634"; break;
case "ROL": return "642"; break;
case "RUB": return "643"; break;
case "RUR": return "810"; break;
case "RWF": return "646"; break;
case "SAR": return "682"; break;
case "SBD": return "090"; break;
case "SCR": return "690"; break;
case "SDD": return "736"; break;
case "SEK": return "752"; break;
case "SGD": return "702"; break;
case "SHP": return "654"; break;
case "SIT": return "705"; break;
case "SKK": return "703"; break;
case "SLL": return "694"; break;
case "SOS": return "706"; break;
case "SRG": return "740"; break;
case "STD": return "678"; break;
case "SVC": return "222"; break;
case "SYP": return "760"; break;
case "SZL": return "748"; break;
case "THB": return "764"; break;
case "TJS": return "972"; break;
case "TMM": return "795"; break;
case "TND": return "788"; break;
case "TOP": return "776"; break;
case "TPE": return "626"; break;
case "TRL": return "792"; break;
case "TRY": return "949"; break;
case "TTD": return "780"; break;
case "TWD": return "901"; break;
case "TZS": return "834"; break;
case "UAH": return "980"; break;
case "UGX": return "800"; break;
case "USD": return "840"; break;
case "UYU": return "858"; break;
case "UZS": return "860"; break;
case "VEB": return "862"; break;
case "VND": return "704"; break;
case "VUV": return "548"; break;
case "XAF": return "950"; break;
case "XCD": return "951"; break;
case "XOF": return "952"; break;
case "XPF": return "953"; break;
case "YER": return "886"; break;
case "YUM": return "891"; break;
case "ZAR": return "710"; break;
case "ZMK": return "894"; break;
case "ZWD": return "716"; break;
}
return "XXX"; // return invalid code if the currency is not found
}

function calculateePayCurrency($order_id)
{
   $currency_code = get_iso_code(CURRENCY_CODE);

   return $currency_code;
}

?>
<script type="text/javascript" src="http://www.epay.dk/js/standardwindow.js"></script>
 <script>
function printCard(cardId)
{
document.write ("<table border=0 cellspacing=10 cellpadding=10>");
switch (cardId) {
case 1: document.write ("<input type=hidden name=cardtype value=1>"); break;
case 2: document.write ("<input type=hidden name=cardtype value=2>"); break;
case 3: document.write ("<input type=hidden name=cardtype value=3>"); break;
case 4: document.write ("<input type=hidden name=cardtype value=4>"); break;
case 5: document.write ("<input type=hidden name=cardtype value=5>"); break;
case 6: document.write ("<input type=hidden name=cardtype value=6>"); break;
case 7: document.write ("<input type=hidden name=cardtype value=7>"); break;
case 8: document.write ("<input type=hidden name=cardtype value=8>"); break;
case 9: document.write ("<input type=hidden name=cardtype value=9>"); break;
case 10: document.write ("<input type=hidden name=cardtype value=10>"); break;
case 12: document.write ("<input type=hidden name=cardtype value=12>"); break;
case 13: document.write ("<input type=hidden name=cardtype value=13>"); break;
case 14: document.write ("<input type=hidden name=cardtype value=14>"); break;
case 15: document.write ("<input type=hidden name=cardtype value=15>"); break;
case 16: document.write ("<input type=hidden name=cardtype value=16>"); break;
case 17: document.write ("<input type=hidden name=cardtype value=17>"); break;
case 18: document.write ("<input type=hidden name=cardtype value=18>"); break;
case 19: document.write ("<input type=hidden name=cardtype value=19>"); break;
case 21: document.write ("<input type=hidden name=cardtype value=21>"); break;
case 22: document.write ("<input type=hidden name=cardtype value=22>"); break;
}
document.write ("</table>");
}
</script>
<form action="https://ssl.ditonlinebetalingssystem.dk/popup/default.asp" method="post" name="ePay" id="ePay">
<input type="hidden" name="merchantnumber" value="<?php echo $this->_params->get("merchant_id") ?>">
<input type="hidden" name="amount" value="<?php echo number_format($data['carttotal'],2,'.','')*100; ?>">
<input type="hidden" name="currency" value="<?php echo calculateePayCurrency($data['order_id'])?>">
<input type="hidden" name="orderid" value="<?php echo $data['order_id'] ?>">
<input type="hidden" name="Itemid" value="<?php echo $_REQUEST['Itemid'] ?>">
<input type="hidden" name="ordretext" value="">

<input type="hidden" name="declineurl" value="<?php echo JURI::base(); ?>index.php?tmpl=component&option=com_redshop&view=order_detail&controller=order_detail&task=notify_payment&payment_plugin=rs_payment_epay&accept=0&sessionid=<?php echo $sessionid ?>&Itemid=<?php echo $_REQUEST['Itemid'] ?>&orderid=<?php echo $data['order_id'] ?>">
<?php

if ($this->_params->get("activate_callback") == "1")
{?><input type="hidden" name="callbackurl" value="<?php echo JURI::base(); ?>index.php?tmpl=component&option=com_redshop&view=order_detail&controller=order_detail&task=notify_payment&payment_plugin=rs_payment_epay&accept=1&sessionid=<?php echo $sessionid?>&Itemid=<?php echo $_REQUEST['Itemid']?>&orderid=<?php echo $data['order_id']?>">
<input type="hidden" name="accepturl" value="<?php echo JURI::base(); ?>index.php?option=com_redshop&view=order_detail&Itemid=<?php echo $_REQUEST['Itemid']?>&oid=<?php echo $data['order_id'] ?>">
<? } else {
?>
<input type="hidden" name="accepturl" value="<?php echo JURI::base(); ?>index.php?tmpl=component&option=com_redshop&view=order_detail&controller=order_detail&task=notify_payment&payment_plugin=rs_payment_epay&accept=1&sessionid=<?php echo $sessionid ?>&Itemid=<?php echo $_REQUEST['Itemid'] ?>&orderid=<?php echo $data['order_id'] ?>">
<?php } ?>


<input type="hidden" name="group" value="<?php echo $this->_params->get("payment_group"); ?>">
<input type="hidden" name="instantcapture" value="<?php echo $this->_params->get("auth_type"); ?>">
<input type="hidden" name="instantcallback" value="1">
<input type="hidden" name="language" value="<?php echo $this->_params->get("language"); ?>">
<input type="hidden" name="authsms" value="<?php echo $this->_params->get("epay_authsms"); ?>">
<input type="hidden" name="authmail" value="<?php echo $this->_params->get("epay_authemail") . (strlen($this->_params->get("epay_authemail")) > 0 && $this->_params->get("epay_authemailcustomer") == 1 ? ";" : "") . ($this->_params->get("epay_authemailcustomer") == 1 ? $user->user_email : ""); ?>">
<input type="hidden" name="windowstate" value="<?php echo $this->_params->get("epay_window_state"); ?>">
<?php if($this->_params->get("epay_window_state") == 2){?>
<input type="hidden" name="ownreceipt" value="<?php echo $this->_params->get("ownreceipt"); ?>">
<?php } ?>
<input type="hidden" name="use3D" value="<?php echo $this->_params->get("epay_3dsecure"); ?>">
<input type="hidden" name="addfee" value="<?php echo $this->_params->get("transaction_fee") ?>">
<input type="hidden" name="subscription" value="<?php echo $this->_params->get("epay_subscription") ?>">
<input type="hidden" name="MD5Key" value="<?php if ($this->_params->get("epay_md5") == 2) echo md5( calculateePayCurrency($data['order_id']) . round(($data['carttotal'])*100, 2 ) . $data['order_id']  . $this->_params->get("epay_paymentkey"))?>">
<?php
$cardtypes=$this->_params->get("cardtypes");

$cardtype_main = "";

if(is_array($cardtypes))
{
	if(in_array('DANKORT',@$cardtypes) && !in_array('ALL',@$cardtypes)) $cardtype_main.="1,";
}
if(is_array($cardtypes))
{
	if(in_array('VD',@$cardtypes) && !in_array('ALL',@$cardtypes)) $cardtype_main.="2,";
}
if(is_array($cardtypes))
{
	if(in_array('VE',@$cardtypes) && !in_array('ALL',@$cardtypes)) $cardtype_main.="3,";
}
if(is_array($cardtypes))
{
	if(in_array('MCDK',@$cardtypes) && !in_array('ALL',@$cardtypes)) $cardtype_main.="4,";
}
if(is_array($cardtypes))
{
	if(in_array('MC',@$cardtypes) && !in_array('ALL',@$cardtypes)) $cardtype_main.="5,";
}
if(is_array($cardtypes))
{
	if(in_array('VEDK',@$cardtypes) && !in_array('ALL',@$cardtypes)) $cardtype_main.="6,";
}
if(is_array($cardtypes))
{
	if(in_array('JCB',@$cardtypes) && !in_array('ALL',@$cardtypes)) $cardtype_main.="7,";
}
if(is_array($cardtypes))
{
	if(in_array('DDK',@$cardtypes) && !in_array('ALL',@$cardtypes)) $cardtype_main.="8,";
}
if(is_array($cardtypes))
{
	if(in_array('MDK',@$cardtypes) && !in_array('ALL',@$cardtypes)) $cardtype_main.="9,";
}
if(is_array($cardtypes))
{
	if(in_array('AEDK',@$cardtypes) && !in_array('ALL',@$cardtypes)) $cardtype_main.="10,";
}
if(is_array($cardtypes))
{
	if(in_array('DINERS',@$cardtypes) && !in_array('ALL',@$cardtypes)) $cardtype_main.="13,";
}

if(is_array($cardtypes))
{
	if(in_array('AE',@$cardtypes) && !in_array('ALL',@$cardtypes)) $cardtype_main.="14,";
}
if(is_array($cardtypes))
{
	if(in_array('MAESTRO',@$cardtypes) && !in_array('ALL',@$cardtypes)) $cardtype_main.="15,";
}
if(is_array($cardtypes))
{
	if(in_array('FORBRUGSFORENINGEN',@$cardtypes) && !in_array('ALL',@$cardtypes)) $cardtype_main.="16,";
}
if(is_array($cardtypes))
{
	if(in_array('EWIRE',@$cardtypes) && !in_array('ALL',@$cardtypes)) $cardtype_main.="17,";
}
if(is_array($cardtypes))
{
	if(in_array('VISA',@$cardtypes) && !in_array('ALL',@$cardtypes)) $cardtype_main.="18,";
}
if(is_array($cardtypes))
{
	if(in_array('IKANO',@$cardtypes) && !in_array('ALL',@$cardtypes)) $cardtype_main.="19,";
}
if(is_array($cardtypes))
{
	if(in_array('NORDEA',@$cardtypes) && !in_array('ALL',@$cardtypes)) $cardtype_main.="21,";
}
if(is_array($cardtypes))
{
	if(in_array('DB',@$cardtypes) && !in_array('ALL',@$cardtypes)) $cardtype_main.="22,";
}
?>
<input type="hidden" name="cardtype" value="<?php echo substr_replace($cardtype_main,"",-1); ?>">
</form>
<script>open_ePay_window(); </script>
<br>
<table border="0" width="100%"><tr class="epay_button_row"><td class="epay_button_data"><input type="button" onClick="open_ePay_window();" value="<?php echo JText::_('COM_REDSHOP_OPEN_EPAY_PAYMENT_WINDOW') ?>"></td><td width="100%" id="flashLoader"></td></tr></table><br><br><br>
