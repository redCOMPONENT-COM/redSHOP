<?php
/**
 * @package     RedSHOP
 * @subpackage  Plugin
 *
 * @copyright   Copyright (C) 2005 - 2013 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

require_once JPATH_SITE . '/components/com_redshop/helpers/product.php';
require_once JPATH_SITE . '/components/com_redshop/helpers/currency.php';

$producthelper  = new producthelper;
$CurrencyHelper = new CurrencyHelper;
$uri            = JURI::getInstance();
$url            = $uri->root();
$user           = JFactory::getUser();
$sessionid      = session_id();
$db             = JFactory::getDbo();

require_once JPATH_ADMINISTRATOR . '/components/com_redshop/helpers/redshop.cfg.php';

$sql = "SELECT op.*,o.order_total,o.user_id FROM #__redshop_order_payment AS op LEFT JOIN #__redshop_orders AS o ON op.order_id = o.order_id  WHERE o.order_id='" . $data['order_id'] . "'";
$db->setQuery($sql);
$order_details = $db->loadObjectList();

function calculateePayCurrency($order_id)
{
	$currency_code = $CurrencyHelper->get_iso_code(CURRENCY_CODE);

	return $currency_code;
}

$trans_fee = $this->params->get("transaction_fee");

?>
<script type="text/javascript">
	function getFee(cardno, acq)
	{
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
		<input type="hidden" name="merchantnumber" value="<?php echo $this->params->get("merchant_id"); ?>"
		       style="width: 200px;"/>
		<input type="hidden" name="amount" value="<?php echo round($order_details[0]->order_total * 100, 2) ?>"
		       style="width: 200px;"/>
		<input type="hidden" name="currency" style="width: 200px;"
		       value="<?php echo calculateePayCurrency($data['order_id']) ?>"/>
		<input type="hidden" name="orderid" style="width: 200px;" value="<?php echo $data['order_id'] ?>"/>
		<input type="hidden" name="MD5Key"
		       value="<?php if ($this->params->get("epay_md5") == 2) echo md5(calculateePayCurrency($data['order_id']) . round(($order_details[0]->order_total) * 100, 2) . $data['order_id'] . $this->params->get("epay_paymentkey")) ?>">
		<input type="hidden" name="use3D" value="<?php echo $this->params->get("epay_3dsecure"); ?>">
		<input type="hidden" name="language" style="width: 200px;"
		       value="<?php echo $this->params->get("language"); ?>"/>
		<input type="hidden" name="instantcapture" style="width: 200px;"
		       value="<?php echo $this->params->get("auth_type"); ?>"/>
		<input type="hidden" name="instantcallback" value="1">
		<input type="hidden" name="group" value="<?php echo $this->params->get("payment_group"); ?>">
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

		if ($this->params->get("activate_callback") == "1")
		{
			?>
			<input type="hidden" name="callbackurl"
			       value="<?php echo $url ?>index.php?tmpl=component&option=com_redshop&view=order_detail&task=notify_payment&payment_plugin=rs_payment_epayrelay&orderid=<?php echo $data['order_id'] ?>">
			<input type="hidden" name="accepturl"
			       value="<?php echo $url ?>index.php?option=com_redshop&view=order_detail&Itemid=<?php echo $_REQUEST['Itemid'] ?>&oid=<?php echo $data['order_id'] ?>">
		<?php
		}
		else
		{
			?>
			<input type="hidden" name="accepturl"
			       value="<?php echo $url ?>index.php?tmpl=component&option=com_redshop&view=order_detail&task=notify_payment&payment_plugin=rs_payment_epayrelay&Itemid=<?php echo $_REQUEST['Itemid'] ?>&orderid=<?php echo $data['order_id'] ?>"/>
		<?php
		}
		?>
		<tr>
			<td>&nbsp;</td>
			<td><input type="submit" value="<?php echo JText::_('COM_REDSHOP_PAY'); ?>" class="button"
			           style="width: 200px;"></td>

		</tr>
	</table>
</form>
<script
	type="text/javascript"
	src="https://relay.ditonlinebetalingssystem.dk/relay/v2/replace_relay_urls.js"></script>
