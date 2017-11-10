<?php
/**
 * @package     RedSHOP
 * @subpackage  Plugin
 *
 * @copyright   Copyright (C) 2008 - 2015 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

JLoader::import('redshop.library');

$app    = JFactory::getApplication();
$itemId = $app->input->getInt('Itemid', 0);

$amount = RedshopHelperCurrency::convert($data['carttotal'], '', 'EUR');

$parameter['sourceId']      = $this->params->get('source_id');
$parameter['merchantId']    = $this->params->get('merchant_id');
$parameter['projectId']     = $this->params->get('project_id');
$parameter['transactionId'] = $data['order_id'];
$parameter['amount']        = number_format($amount, 2, '.', '');
$parameter['vwz']           = 'Order';
$parameter['bankcode']      = '';
$parameter['urlRedirect']   = JURI::base() . 'index.php?option=com_redshop&view=order_detail&tmpl=component&controller=order_detail&task=notify_payment&payment_plugin=rs_payment_giropay&orderid=' . $data['order_id'] . '&Itemid=' . $itemId;
$parameter['urlNotify']     = JURI::base() . 'index.php?option=com_redshop&view=order_detail&oid=' . $data['order_id'] . '&Itemid=' . $itemId;

$secret_password = $this->params->get('secret_password', '');
$parameter['hash'] = $gsGiropay->generateHash(implode('', $parameter), $secret_password);
?>
<form action="https://payment.girosolution.de/payment/start" method="post" name="giropayfrm" id="giropayfrm">
    <?php
    foreach ($parameter as $name => $value)
    {
        echo '<input type="hidden" name="' . $name . '" value="' . $value . '" />';
    }
    ?>
</form>
<script type='text/javascript'>
    window.onload = function () {
        document.giropayfrm.submit();
    }
</script>
