<?php
/**
 * @package     RedSHOP.Frontend
 * @subpackage  Plugin
 *
 * @copyright   Copyright (C) 2008 - 2016 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

defined('_JEXEC') or die;
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
