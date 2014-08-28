<?php
/**
 * @package     RedSHOP
 * @subpackage  Plugin
 *
 * @copyright   Copyright (C) 2005 - 2013 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

require_once JPATH_ADMINISTRATOR . '/components/com_redshop/helpers/redshop.cfg.php';
JLoader::import('loadhelpers', JPATH_SITE . '/components/com_redshop');
JLoader::load('RedshopHelperProduct');
$producthelper = new producthelper;

$uri = JURI::getInstance();
$url = $uri->root();
$user = JFactory::getUser();
$sessionid = session_id();

$db = JFactory::getDbo();

$sql = "SELECT op.*,o.order_total,o.user_id FROM " . $this->_table_prefix . "order_payment AS op LEFT JOIN " . $this->_table_prefix . "orders AS o ON op.order_id = o.order_id  WHERE o.order_id='" . $data['order_id'] . "'";
$db->setQuery($sql);
$order_details = $db->loadObjectList();

function get_iso_code($code)
{
	switch ($code)
	{
		case "ADP":
			return "020";
			break;
		case "AED":
			return "784";
			break;
		case "AFA":
			return "004";
			break;
		case "ALL":
			return "008";
			break;
		case "AMD":
			return "051";
			break;
		case "ANG":
			return "532";
			break;
		case "AOA":
			return "973";
			break;
		case "ARS":
			return "032";
			break;
		case "AUD":
			return "036";
			break;
		case "AWG":
			return "533";
			break;
		case "AZM":
			return "031";
			break;
		case "BAM":
			return "977";
			break;
		case "BBD":
			return "052";
			break;
		case "BDT":
			return "050";
			break;
		case "BGL":
			return "100";
			break;
		case "BGN":
			return "975";
			break;
		case "BHD":
			return "048";
			break;
		case "BIF":
			return "108";
			break;
		case "BMD":
			return "060";
			break;
		case "BND":
			return "096";
			break;
		case "BOB":
			return "068";
			break;
		case "BOV":
			return "984";
			break;
		case "BRL":
			return "986";
			break;
		case "BSD":
			return "044";
			break;
		case "BTN":
			return "064";
			break;
		case "BWP":
			return "072";
			break;
		case "BYR":
			return "974";
			break;
		case "BZD":
			return "084";
			break;
		case "CAD":
			return "124";
			break;
		case "CDF":
			return "976";
			break;
		case "CHF":
			return "756";
			break;
		case "CLF":
			return "990";
			break;
		case "CLP":
			return "152";
			break;
		case "CNY":
			return "156";
			break;
		case "COP":
			return "170";
			break;
		case "CRC":
			return "188";
			break;
		case "CUP":
			return "192";
			break;
		case "CVE":
			return "132";
			break;
		case "CYP":
			return "196";
			break;
		case "CZK":
			return "203";
			break;
		case "DJF":
			return "262";
			break;
		case "DKK":
			return "208";
			break;
		case "DOP":
			return "214";
			break;
		case "DZD":
			return "012";
			break;
		case "ECS":
			return "218";
			break;
		case "ECV":
			return "983";
			break;
		case "EEK":
			return "233";
			break;
		case "EGP":
			return "818";
			break;
		case "ERN":
			return "232";
			break;
		case "ETB":
			return "230";
			break;
		case "EUR":
			return "978";
			break;
		case "FJD":
			return "242";
			break;
		case "FKP":
			return "238";
			break;
		case "GBP":
			return "826";
			break;
		case "GEL":
			return "981";
			break;
		case "GHC":
			return "288";
			break;
		case "GIP":
			return "292";
			break;
		case "GMD":
			return "270";
			break;
		case "GNF":
			return "324";
			break;
		case "GTQ":
			return "320";
			break;
		case "GWP":
			return "624";
			break;
		case "GYD":
			return "328";
			break;
		case "HKD":
			return "344";
			break;
		case "HNL":
			return "340";
			break;
		case "HRK":
			return "191";
			break;
		case "HTG":
			return "332";
			break;
		case "HUF":
			return "348";
			break;
		case "IDR":
			return "360";
			break;
		case "ILS":
			return "376";
			break;
		case "INR":
			return "356";
			break;
		case "IQD":
			return "368";
			break;
		case "IRR":
			return "364";
			break;
		case "ISK":
			return "352";
			break;
		case "JMD":
			return "388";
			break;
		case "JOD":
			return "400";
			break;
		case "JPY":
			return "392";
			break;
		case "KES":
			return "404";
			break;
		case "KGS":
			return "417";
			break;
		case "KHR":
			return "116";
			break;
		case "KMF":
			return "174";
			break;
		case "KPW":
			return "408";
			break;
		case "KRW":
			return "410";
			break;
		case "KWD":
			return "414";
			break;
		case "KYD":
			return "136";
			break;
		case "KZT":
			return "398";
			break;
		case "LAK":
			return "418";
			break;
		case "LBP":
			return "422";
			break;
		case "LKR":
			return "144";
			break;
		case "LRD":
			return "430";
			break;
		case "LSL":
			return "426";
			break;
		case "LTL":
			return "440";
			break;
		case "LVL":
			return "428";
			break;
		case "LYD":
			return "434";
			break;
		case "MAD":
			return "504";
			break;
		case "MDL":
			return "498";
			break;
		case "MGF":
			return "450";
			break;
		case "MKD":
			return "807";
			break;
		case "MMK":
			return "104";
			break;
		case "MNT":
			return "496";
			break;
		case "MOP":
			return "446";
			break;
		case "MRO":
			return "478";
			break;
		case "MTL":
			return "470";
			break;
		case "MUR":
			return "480";
			break;
		case "MVR":
			return "462";
			break;
		case "MWK":
			return "454";
			break;
		case "MXN":
			return "484";
			break;
		case "MXV":
			return "979";
			break;
		case "MYR":
			return "458";
			break;
		case "MZM":
			return "508";
			break;
		case "NAD":
			return "516";
			break;
		case "NGN":
			return "566";
			break;
		case "NIO":
			return "558";
			break;
		case "NOK":
			return "578";
			break;
		case "NPR":
			return "524";
			break;
		case "NZD":
			return "554";
			break;
		case "OMR":
			return "512";
			break;
		case "PAB":
			return "590";
			break;
		case "PEN":
			return "604";
			break;
		case "PGK":
			return "598";
			break;
		case "PHP":
			return "608";
			break;
		case "PKR":
			return "586";
			break;
		case "PLN":
			return "985";
			break;
		case "PYG":
			return "600";
			break;
		case "QAR":
			return "634";
			break;
		case "ROL":
			return "642";
			break;
		case "RUB":
			return "643";
			break;
		case "RUR":
			return "810";
			break;
		case "RWF":
			return "646";
			break;
		case "SAR":
			return "682";
			break;
		case "SBD":
			return "090";
			break;
		case "SCR":
			return "690";
			break;
		case "SDD":
			return "736";
			break;
		case "SEK":
			return "752";
			break;
		case "SGD":
			return "702";
			break;
		case "SHP":
			return "654";
			break;
		case "SIT":
			return "705";
			break;
		case "SKK":
			return "703";
			break;
		case "SLL":
			return "694";
			break;
		case "SOS":
			return "706";
			break;
		case "SRG":
			return "740";
			break;
		case "STD":
			return "678";
			break;
		case "SVC":
			return "222";
			break;
		case "SYP":
			return "760";
			break;
		case "SZL":
			return "748";
			break;
		case "THB":
			return "764";
			break;
		case "TJS":
			return "972";
			break;
		case "TMM":
			return "795";
			break;
		case "TND":
			return "788";
			break;
		case "TOP":
			return "776";
			break;
		case "TPE":
			return "626";
			break;
		case "TRL":
			return "792";
			break;
		case "TRY":
			return "949";
			break;
		case "TTD":
			return "780";
			break;
		case "TWD":
			return "901";
			break;
		case "TZS":
			return "834";
			break;
		case "UAH":
			return "980";
			break;
		case "UGX":
			return "800";
			break;
		case "USD":
			return "840";
			break;
		case "UYU":
			return "858";
			break;
		case "UZS":
			return "860";
			break;
		case "VEB":
			return "862";
			break;
		case "VND":
			return "704";
			break;
		case "VUV":
			return "548";
			break;
		case "XAF":
			return "950";
			break;
		case "XCD":
			return "951";
			break;
		case "XOF":
			return "952";
			break;
		case "XPF":
			return "953";
			break;
		case "YER":
			return "886";
			break;
		case "YUM":
			return "891";
			break;
		case "ZAR":
			return "710";
			break;
		case "ZMK":
			return "894";
			break;
		case "ZWD":
			return "716";
			break;
	}
	return "XXX"; // return invalid code if the currency is not found
}

function calculateePayCurrency($order_id)
{
	$currency_code = get_iso_code(CURRENCY_CODE);

	return $currency_code;
}

$trans_fee = $this->_params->get("transaction_fee");

?>
<script type="text/javascript">
	function getFee(cardno, acq) {
		//document.getElementById("div_transfee").innerHTML = 'Please wait...';

		// if (cardno.length < 6) {
		//	document.forms['ePay'].submit.disabled = true;
		//   return false;
		//  }

		cardno = cardno.substr(0, 6);

		var xmlHttpReq = false;
		var self = this;
		// Mozilla/Safari
		if (window.XMLHttpRequest) {
			self.xmlHttpReq = new XMLHttpRequest;
		}
		// IE
		else if (window.ActiveXObject) {
			self.xmlHttpReq = new ActiveXObject("Microsoft.XMLHTTP");
		}

		self.xmlHttpReq.open('POST', "<?php echo $url;?>/plugins/redshop_payment/rs_payment_epayrelay/webservice_fee.php?merchantnumber=" + document.forms['ePay'].merchantnumber.value + "&cardno_prefix=" + cardno + "&acquirer=" + acq + "&amount=" + document.forms['ePay'].amount.value + "&currency=" + document.forms['ePay'].currency.value + "", true);
		self.xmlHttpReq.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');

		self.xmlHttpReq.onreadystatechange = function () {
			if (self.xmlHttpReq.readyState == 4) {
				var returnvalues = self.xmlHttpReq.responseText.split(",");

				if (returnvalues.length == 3) {
					var fee = returnvalues[0];
					var cardtype = returnvalues[1];
					var cardtext = returnvalues[2];

					//	document.getElementById("div_transfee").innerHTML = fee / 100 +'&nbsp;&nbsp;&nbsp;( '+ cardtext +' ) ';

					document.forms['ePay'].transfee.value = fee;

					//document.forms['ePay'].submit.disabled = false;
				} else {
					var epayresponse = returnvalues[0];
					//	document.getElementById("div_transfee").innerHTML = 'Error (' + epayresponse + ')';
					//document.forms['ePay'].submit.disabled = true;
				}
			}
		}

		self.xmlHttpReq.send();
	}

	function validate() {
		if (document.forms['ePay'].cardno.value == "") {
			alert("Please enter Card No.");
			return false;
		}

		if (document.forms['ePay'].cvc.value == "") {
			alert("Please enter CVV code.");
			return false;
		}

		return true;
	}
</script>

<form action="https://ssl.ditonlinebetalingssystem.dk/auth/default.aspx" method="post" name="ePay" id="ePay"
      onsubmit="return validate();">
	<table cellspacing="0" cellpadding="3" style="width: 400px;">
		<input type="hidden" name="merchantnumber" value="<?php echo $this->_params->get("merchant_id"); ?>"
		       style="width: 200px;"/>
		<input type="hidden" name="amount" value="<?php echo round($order_details[0]->order_total * 100, 2) ?>"
		       style="width: 200px;"/>
		<input type="hidden" name="currency" style="width: 200px;"
		       value="<?php echo calculateePayCurrency($data['order_id']) ?>"/>
		<input type="hidden" name="orderid" style="width: 200px;" value="<?php echo $data['order_id'] ?>"/>
		<input type="hidden" name="MD5Key"
		       value="<?php if ($this->_params->get("epay_md5") == 2) echo md5(calculateePayCurrency($data['order_id']) . round(($order_details[0]->order_total) * 100, 2) . $data['order_id'] . $this->_params->get("epay_paymentkey")) ?>">
		<input type="hidden" name="use3D" value="<?php echo $this->_params->get("epay_3dsecure"); ?>">
		<input type="hidden" name="language" style="width: 200px;"
		       value="<?php echo $this->_params->get("language"); ?>"/>
		<input type="hidden" name="instantcapture" style="width: 200px;"
		       value="<?php echo $this->_params->get("auth_type"); ?>"/>
		<input type="hidden" name="instantcallback" value="1">
		<input type="hidden" name="group" value="<?php echo $this->_params->get("payment_group"); ?>">
		<input type="hidden" name="transfee" value="0"/>
		<tr>
			<td><?php echo JText::_("COM_REDSHOP_CARD_NO"); ?></td>
			<td>
				<input type="text" name="cardno" value="" <?php if ($trans_fee == 1)
				{ ?>   onchange="getFee(this.value, 1)" <?php }?> autocomplete="off"/>
			</td>

		</tr>
		<tr>
			<td><?php echo JText::_("COM_REDSHOP_EXP_MONTH"); ?></td>
			<td>
				<select name="expmonth">
					<option value="01">01</option>
					<option value="02">02</option>
					<option value="03">03</option>
					<option value="04">04</option>
					<option value="05">05</option>
					<option value="06">06</option>
					<option value="07">07</option>
					<option value="08">08</option>
					<option value="09">09</option>
					<option value="10">10</option>
					<option value="11">11</option>
					<option value="12">12</option>
				</select>
			</td>

		</tr>
		<tr>
			<td><?php echo JText::_("COM_REDSHOP_EXP_YEAR"); ?></td>
			<td>
				<select name="expyear">
					<option value="09">09</option>
					<option value="10">10</option>
					<option value="11">11</option>
					<option value="12">12</option>
					<option value="13">13</option>
					<option value="14">14</option>
					<option value="15">15</option>
					<option value="16">16</option>
					<option value="17">17</option>
					<option value="18">18</option>
					<option value="19">19</option>
					<option value="20">20</option>
				</select>
			</td>

		</tr>
		<tr>
			<td><?php echo JText::_("COM_REDSHOP_CVC"); ?></td>
			<td>
				<input type="password" name="cvc" value=""/>
			</td>

		</tr>

		<input type="hidden" name="declineurl"
		       value="<?php echo $url ?>index.php?tmpl=component&option=com_redshop&view=order_detail&task=notify_payment&payment_plugin=rs_payment_epayrelay&Itemid=<?php echo $_REQUEST['Itemid'] ?>&orderid=<?php echo $data['order_id'] ?>"/>

		<?php

		if ($this->_params->get("activate_callback") == "1")
		{
			?>
			<input type="hidden" name="callbackurl"
			       value="<?php echo $url ?>index.php?tmpl=component&option=com_redshop&view=order_detail&task=notify_payment&payment_plugin=rs_payment_epayrelay&orderid=<?php echo $data['order_id'] ?>">
			<input type="hidden" name="accepturl"
			       value="<?php echo $url ?>index.php?option=com_redshop&view=order_detail&Itemid=<?php echo $_REQUEST['Itemid'] ?>&oid=<?php echo $data['order_id'] ?>">
		<?
		}
		else
		{
			?>
			<input type="hidden" name="accepturl"
			       value="<?php echo $url ?>index.php?tmpl=component&option=com_redshop&view=order_detail&task=notify_payment&payment_plugin=rs_payment_epayrelay&Itemid=<?php echo $_REQUEST['Itemid'] ?>&orderid=<?php echo $data['order_id'] ?>"/>
		<?php } ?>
		<tr>
			<td>&nbsp;</td>
			<td><input type="submit" value="<?php echo JText::_('COM_REDSHOP_PAY'); ?>" class="button"
			           style="width: 200px;"></td>

		</tr>
	</table>
</form>

<script type="text/javascript" src="https://relay.ditonlinebetalingssystem.dk/relay/v2/replace_relay_urls.js"></script>

