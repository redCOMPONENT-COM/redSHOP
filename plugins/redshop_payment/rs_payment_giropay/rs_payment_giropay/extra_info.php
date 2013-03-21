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
require_once JPATH_SITE . DS . 'components' . DS . 'com_redshop' . DS . 'helpers' . DS . 'currency.php';

$uri =& JURI::getInstance();
$url = $uri->root();
$user = JFactory::getUser();
$sessionid = session_id();

$currencyClass = new convertPrice;
$amount = $currencyClass->convert($data['carttotal'], '', "EUR");

$parameter['sourceId'] = $this->_params->get("source_id");
$parameter['merchantId'] = $this->_params->get("merchant_id");
$parameter['projectId'] = $this->_params->get("project_id");
$parameter['transactionId'] = $data['order_id'];
$parameter['amount'] = number_format($amount, 2, '.', '');
$parameter['vwz'] = "Order";
$parameter['bankcode'] = '';
$parameter['urlRedirect'] = JURI::base() . "index.php?option=com_redshop&view=order_detail&oid=" . $data['order_id'];
$parameter['urlNotify'] = JURI::base() . "index2.php?option=com_redshop&view=order_detail&controller=order_detail&task=notify_payment&payment_plugin=rs_payment_giropay&orderid=" . $data['order_id'];

$secret_password = $this->_params->get("secret_password");
$hash = $gsGiropay->generateHash(implode('', $parameter), $secret_password);

?>

<form action="https://payment.girosolution.de/payment/start" method="post" name="giropayfrm" id="giropayfrm">
	<input type="hidden" name="sourceId" value="<?php echo $parameter['sourceId']; ?>">
	<input type="hidden" name="merchantId" value="<?php echo $parameter['merchantId']; ?>">
	<input type="hidden" name="projectId" value="<?php echo $parameter['projectId']; ?>">
	<input type="hidden" name="transactionId" value="<?php echo $data['order_id']; ?>">
	<input type="hidden" name="amount" value="<?php echo $parameter['amount']; ?>"/>
	<input type="hidden" name="vwz" value="<?php echo $parameter['vwz']; ?>">
	<input type="hidden" name="bankcode" value="<?php echo $parameter['bankcode']; ?>">
	<input type="hidden" name="urlNotify" value="<?php echo $parameter['urlNotify']; ?>">
	<input type="hidden" name="urlRedirect" value="<?php echo $parameter['urlRedirect']; ?>">
	<input type="hidden" name="hash" value="<?php echo $hash; ?>"/>

</form>

<script type='text/javascript'>
	window.onload = function () {
		document.giropayfrm.submit();
	}
</script>
