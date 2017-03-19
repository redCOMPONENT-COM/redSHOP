<?php
/**
 * @package     RedSHOP.Plugins
 * @subpackage  Template
 *
 * @copyright   Copyright (C) 2008 - 2016 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */
defined('_JEXEC') or die;
?>
<form action="<?php echo $paypalurl ?>" method="post" name="paypalfrm" id="paypalfrm">
	<h3><?php echo JText::_('PLG_RS_PAYMENT_PAYPAL_WAIT_MESSAGE') ?></h3>

	<?php foreach ($paypalPostData as $name => $value): ?>
		<input type='hidden' name='<?php echo $name ?>' value='<?php echo $value ?>' />
	<?php endforeach ?>

	<?php if (is_array($paypalCartItems) && count($paypalCartItems)): ?>
		<?php foreach ($paypalCartItems as $name => $value): ?>
			<input type="hidden" name="<?php echo $name ?>" value="<?php echo $value ?>" />
		<?php endforeach ?>
	<?php endif ?>

	<?php if (Redshop::getConfig()->get('SHIPPING_METHOD_ENABLE')): ?>
		<?php if (is_array($paypalShippingData) && count($paypalShippingData)): ?>
			<?php foreach ($paypalShippingData as $name => $value): ?>
				<input type="hidden" name="<?php echo $name ?>" value="<?php echo $value ?>" />
			<?php endforeach ?>
		<?php endif ?>
	<?php endif ?>

	<input type="hidden" name="charset" value="utf-8">
</form>

<script type='text/javascript'>
	document.getElementById('paypalfrm').submit();
</script>

