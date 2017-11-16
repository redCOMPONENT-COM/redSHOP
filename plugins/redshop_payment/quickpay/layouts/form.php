<?php
/**
 * @package     Redshop.Layouts
 * @subpackage  Payment.stripe
 * @copyright   Copyright (C) 2008-2015 redCOMPONENT.com. All rights reserved.
 * @license     GNU/GPL, see LICENSE
 */

defined('_JEXEC') or die;

$formInput = $displayData['formInput'];
$params    = $displayData['params'];

?>
<h3><?php echo JText::_('PLG_REDSHOP_PAYMENT_QUICKPAY_FORM_TITLE'); ?></h3>
<form action="https://payment.quickpay.net" method="POST" id="quickpay">
	<?php foreach ($formInput as $name => $value) :?>
		<input
			type="hidden"
			name="<?php echo $name; ?>"
			value="<?php echo $value; ?>"
		/>
	<?php endforeach; ?>
</form>
<script>
	document.getElementById("quickpay").submit();
</script>
