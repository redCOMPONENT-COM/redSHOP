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

$paymentUrl = 'https://ssl.dotpay.pl/test_payment/';

if (!$params->get('testMode'))
{
	$paymentUrl = 'https://ssl.dotpay.pl/pay.php';
}

?>
<h3><?php echo JText::_('PLG_REDSHOP_PAYMENT_DOTPAY_FORM_TITLE'); ?></h3>
<form action="<?php echo $paymentUrl; ?>" method="post"id="dotpay">
	<div style="text-align: center; margin-top: 25px; margin-bottom: 25px;">
		<input
			type="image"
			name="submit"
		    src="<?php echo JUri::root(); ?>/plugins/redshop_payment/dotpay/media/dotpay.jpg"
		    border="0"
		    alt=""
		>
	</div>
	<?php foreach ($formInput as $name => $value) :?>
		<input
			type="hidden"
			name="<?php echo $name; ?>"
			value="<?php echo $value; ?>"
		/>
	<?php endforeach; ?>
</form>
<script>
	document.getElementById("dotpay").submit();
</script>
