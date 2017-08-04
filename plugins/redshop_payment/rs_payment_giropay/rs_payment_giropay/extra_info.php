<?php
/**
 * @package     RedSHOP
 * @subpackage  Plugin
 *
 * @copyright   Copyright (C) 2008 - 2015 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

JLoader::import('redshop.library');

$uri       = JURI::getInstance();
$url       = $uri->root();
$user      = JFactory::getUser();
$sessionid = session_id();
$amount    = RedshopHelperCurrency::convert($data['carttotal'], '', "EUR");

$parameter['sourceId']      = $this->params->get("source_id");
$parameter['merchantId']    = $this->params->get("merchant_id");
$parameter['projectId']     = $this->params->get("project_id");
$parameter['transactionId'] = $data['order_id'];
$parameter['amount']        = number_format($amount, 2, '.', '');
$parameter['vwz']           = "Order";
$parameter['bankcode']      = '';
$parameter['urlRedirect']   = JURI::base() . "index.php?option=com_redshop&view=order_detail&oid=" . $data['order_id'];
$parameter['urlNotify']     = JURI::base() . "index.php?option=com_redshop&view=order_detail&tmpl=component&controller=order_detail&task=notify_payment&payment_plugin=rs_payment_giropay&orderid=" . $data['order_id'];

$secret_password = $this->params->get("secret_password");
$hash            = $gsGiropay->generateHash(implode('', $parameter), $secret_password);
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
