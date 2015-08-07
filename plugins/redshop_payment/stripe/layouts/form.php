<?php
/**
 * @package     Redshop.Layouts
 * @subpackage  Payment.stripe
 * @copyright   Copyright (C) 2008-2015 redCOMPONENT.com. All rights reserved.
 * @license     GNU/GPL, see LICENSE
 */

defined('_JEXEC') or die;

$action  = $displayData['action'];
$data    = $displayData['data'];
$params  = $displayData['params'];
$price   = $data['order']->order_total;

$name        = $params->get('dataName', SHOP_NAME);
$description = JText::sprintf('PLG_REDSHOP_PAYMENT_STRIPE_PAYMENT_DESCRIPTION', $data['order_id']);
?>
<script type="text/javascript">
	jQuery(document).ready(function($) {
		jQuery('.stripe-button-el').hide().click();
		setTimeout('jQuery(".stripe-button-el").show();', 8000);
	});
</script>
<h3><?php echo $name; ?></h3>
<form id="stripeCreatePayment" action="<?php echo $action; ?>" method="POST">
	<script
		src="https://checkout.stripe.com/checkout.js" class="stripe-button"
		data-key="<?php echo $params->get('publishableKey'); ?>"
		data-amount="<?php echo round($price * 100); ?>"
		data-currency="<?php echo CURRENCY_CODE; ?>"
		data-name="<?php echo $name; ?>"
		data-description="<?php echo $description; ?>"
		data-image="<?php echo $params->get('logo', 'plugins/redshop_payment/stripe/library/128.png'); ?>"
		data-label="<?php echo JText::_('PLG_REDSHOP_PAYMENT_STRIPE_PAYMENT_PAY_BUTTON_TEXT'); ?>"
		data-panel-label="<?php echo JText::_('PLG_REDSHOP_PAYMENT_STRIPE_PAYMENT_PAY_PANEL_BUTTON_TEXT'); ?>"
	>
	</script>
</form>
