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

// Live Url
$actionUrl = "https://secure.ogone.com/ncol/prod/orderstandard.asp";

// Sandbox Url
if ((int) $params->get('is_test'))
{
	$actionUrl = "https://secure.ogone.com/ncol/test/orderstandard.asp";
}
?>
<h3><?php echo JText::_('PLG_REDSHOP_PAYMENT_INGENICO_WAIT_MESSAGE'); ?></h3>
<form action="<?php echo $actionUrl; ?>" method="POST" id="ingenicoForm" name="ingenicoForm">
	<?php foreach ($formInput as $name => $value) :?>
		<input
			type="hidden"
			name="<?php echo $name; ?>"
			value="<?php echo $value; ?>"
		/>
	<?php endforeach; ?>
</form>
<script>
	document.getElementById("ingenicoForm").submit();
</script>
