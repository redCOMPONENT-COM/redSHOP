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

?>
<h3><?php echo SHOP_NAME; ?></h3>
<form action="<?php echo $action; ?>" method="POST">
	<script
		src="https://checkout.stripe.com/checkout.js" class="stripe-button"
		data-key="<?php echo $params->get('publishableKey'); ?>"
		data-amount="<?php echo round($price * 100); ?>"
		data-currency="<?php echo CURRENCY_CODE; ?>"
		data-name="<?php echo SHOP_NAME; ?>"
		data-description="<?php echo SHOP_NAME; ?>"
		data-image="<?php echo $params->get('imagePath', 'plugins/redshop_payment/stripe/library/128.png'); ?>">
	</script>
</form>
