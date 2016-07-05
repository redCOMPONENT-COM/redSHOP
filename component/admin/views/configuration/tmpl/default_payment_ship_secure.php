<?php
/**
 * @package     RedSHOP.Backend
 * @subpackage  Template
 *
 * @copyright   Copyright (C) 2008 - 2016 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */
defined('_JEXEC') or die;
$uri = JURI::getInstance();
$url = $uri->root();
$addtocart_path = "/components/com_redshop/assets/images/";
?>

<legend><?php echo JText::_('COM_REDSHOP_PAYMENT_SETTINGS'); ?></legend>

<div class="form-group">
	<span class="editlinktip hasTip"
					      title="<?php echo JText::_('COM_REDSHOP_TOOLTIP_PAYMENT_CALCULATION_ON_LBL'); ?>::<?php echo JText::_('COM_REDSHOP_TOOLTIP_PAYMENT_CALCULATION_ON'); ?>">
		<label for="name"><?php echo JText::_('COM_REDSHOP_PAYMENT_CALCULATION_ON_LBL');?></label></span>
	<?php echo $this->lists ['payment_calculation_on']; ?>
</div>

<legend><?php echo JText::_('COM_REDSHOP_SHIPPING_SETTINGS'); ?></legend>

<div class="form-group">
	<span class="editlinktip hasTip"
					      title="<?php echo JText::_('COM_REDSHOP_TOOLTIP_OPTIONAL_SHIPPING_ADDRESS_LBL'); ?>::<?php echo JText::_('COM_REDSHOP_TOOLTIP_OPTIONAL_SHIPPING_ADDRESS'); ?>">
		<label
						for=optional_shipping_address><?php echo JText::_('COM_REDSHOP_OPTIONAL_SHIPPING_ADDRESS_LBL');?></label></span>
	<?php echo $this->lists ['optional_shipping_address'];?>
</div>

<div class="form-group">
	<span class="editlinktip hasTip"
					      title="<?php echo JText::_('COM_REDSHOP_SHIPPING_METHOD_ENABLE_LBL'); ?>::<?php echo JText::_('COM_REDSHOP_TOOLTIP_SHIPPING_METHOD_ENABLE'); ?>">
		<label
						for=shipping_method_enable><?php echo JText::_('COM_REDSHOP_SHIPPING_METHOD_ENABLE_LBL');?></label></span>
	<?php echo $this->lists ['shipping_method_enable'];?>
</div>

<div class="form-group">
	<span class="editlinktip hasTip"
					      title="<?php echo JText::_('COM_REDSHOP_SPLIT_DELIVERY_COST'); ?>::<?php echo JText::_('COM_REDSHOP_TOOLTIP_SPLIT_DELIVERY_COST'); ?>">
		<label for="name"><?php echo JText::_('COM_REDSHOP_SPLIT_DELIVERY_COST');?></label></span>
	<input type="text" name="split_delivery_cost" id="split_delivery_cost"
							       value="<?php echo $this->config->get('SPLIT_DELIVERY_COST'); ?>">
</div>

<div class="form-group">
	<span class="editlinktip hasTip"
					      title="<?php echo JText::_('COM_REDSHOP_TOOLTIP_TIME_DIFF_SPILT_CALCULATION'); ?>::<?php echo JText::_('COM_REDSHOP_TIME_DIFF_SPILT_CALCULATION'); ?>">
		<label for="name"><?php echo JText::_('COM_REDSHOP_TIME_DIFF_SPILT_CALCULATION');?></label></span>
	<input type="text" name="time_diff_split_delivery" id="time_diff_split_delivery"
							       value="<?php echo $this->config->get('TIME_DIFF_SPLIT_DELIVERY'); ?>">
</div>

<div class="form-group">
	<span class="editlinktip hasTip"
					      title="<?php echo JText::_('COM_REDSHOP_TOOLTIP_DELIVERY_RULE_LBL'); ?>::<?php echo JText::_('COM_REDSHOP_TOOLTIP_DELIVERY_RULE'); ?>">
		<label for="name"><?php echo JText::_('COM_REDSHOP_DELIVERY_RULE');?></label></span>
	<input type="text" name="delivery_rule" id="delivery_rule"
							       value="<?php echo $this->config->get('DELIVERY_RULE'); ?>">
</div>

<legend><?php echo JText::_('COM_REDSHOP_SECURING_SETTINGS'); ?></legend>

<div class="form-group">
	<span class="editlinktip hasTip"
					      title="<?php echo JText::_('COM_REDSHOP_SSL_ENABLE_IN_CHECKOUT_LBL'); ?>::<?php echo JText::_('COM_REDSHOP_TOOLTIP_SSL_ENABLE_IN_CHECKOUT_LBL'); ?>">
		<label
						for="ssl_enable_in_checkout"><?php echo JText::_('COM_REDSHOP_SSL_ENABLE_IN_CHECKOUT_LBL'); ?></label></span>
	<?php echo $this->lists ['ssl_enable_in_checkout'];?>
</div>

<div class="form-group">
	<span class="editlinktip hasTip"
					      title="<?php echo JText::_('COM_REDSHOP_TOOLTIP_SSL_ENABLE_IN_BACKEND_LBL'); ?>::<?php echo JText::_('COM_REDSHOP_TOOLTIP_SSL_ENABLE_IN_BACKEND'); ?>">
					<?php echo JText::_('COM_REDSHOP_SSL_ENABLE_IN_BACKEND_LBL');?></span>
	<?php echo $this->lists ['ssl_enable_in_backend'];?>
</div>
