<?php
/**
 * @package     RedSHOP.Backend
 * @subpackage  Template
 *
 * @copyright   Copyright (C) 2008 - 2016 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */
defined('_JEXEC') or die;

?>
<legend><?php echo JText::_('COM_REDSHOP_TAX_TAB'); ?></legend>

<div class="form-group">
	<span class="editlinktip hasTip"
		      title="<?php echo JText::_('COM_REDSHOP_DEFAULT_VAT_COUNTRY_LBL'); ?>::<?php echo JText::_('COM_REDSHOP_TOOLTIP_DEFAULT_VAT_COUNTRY'); ?>">
		<label for="name">
			<?php
			echo JText::_('COM_REDSHOP_DEFAULT_VAT_COUNTRY_LBL');
			?></label></span>
	<?php echo $this->lists ['default_vat_country']; ?>
</div>

<div class="form-group">
	<span class="editlinktip hasTip"
		      title="<?php echo JText::_('COM_REDSHOP_DEFAULT_VAT_STATE_LBL'); ?>::<?php echo JText::_('COM_REDSHOP_TOOLTIP_DEFAULT_VAT_STATE'); ?>">
		<label for="name">
			<?php
			echo JText::_('COM_REDSHOP_DEFAULT_VAT_STATE_LBL');
			?></label></span>
	<?php echo $this->lists ['default_vat_state']; ?>
</div>

<div class="form-group">
	<span class="editlinktip hasTip"
		      title="<?php echo JText::_('COM_REDSHOP_DEFAULT_VAT_GROUP_LBL'); ?>::<?php echo JText::_('COM_REDSHOP_TOOLTIP_DEFAULT_VAT_GROUP'); ?>">
		<label for="name">
			<?php
			echo JText::_('COM_REDSHOP_DEFAULT_VAT_GROUP_LBL');
			?></label></span>
	<?php
			echo $this->lists ['default_vat_group'];
			?>
</div>

<div class="form-group">
	<span class="editlinktip hasTip"
		      title="<?php echo JText::_('COM_REDSHOP_DEFAULT_VAT_CALCULATION_BASED_LBL'); ?>::<?php echo JText::_('COM_REDSHOP_TOOLTIP_DEFAULT_VAT_CALCULATION_BASED'); ?>">
		<label for="name">
			<?php
			echo JText::_('COM_REDSHOP_DEFAULT_VAT_CALCULATION_BASED_LBL');
			?></label></span>
	<?php
			echo $this->lists ['vat_based_on'];
			?>
</div>

<div class="form-group">
	<span class="editlinktip hasTip"
		      title="<?php echo JText::_('COM_REDSHOP_APPLY_VAT_ON_DISCOUNT_LBL'); ?>::<?php echo JText::_('COM_REDSHOP_TOOLTIP_APPLY_VAT_ON_DISCOUNT'); ?>">
		<label for="name">
			<?php
			echo JText::_('COM_REDSHOP_APPLY_VAT_ON_DISCOUNT_LBL');
			?></label></span>
	<?php
			echo $this->lists ['apply_vat_on_discount'];
			?>
</div>

<div class="form-group">
	<span class="editlinktip hasTip"
		      title="<?php echo JText::_('COM_REDSHOP_TOOLTIP_VAT_RATE_AFTER_DISCOUNT'); ?>::<?php echo JText::_('COM_REDSHOP_TOOLTIP_VAT_RATE_AFTER_DISCOUNT_LBL'); ?>">
		<label for="name">
			<?php
			echo JText::_('COM_REDSHOP_VAT_RATE_AFTER_DISCOUNT_LBL');
			?></label></span>
	<input type="text" name="vat_rate_after_discount"
		           id="vat_rate_after_discount"
		           value="<?php
		           echo $this->config->get('VAT_RATE_AFTER_DISCOUNT');
		           ?>">
</div>

<div class="form-group">
	<span class="editlinktip hasTip"
		      title="<?php echo JText::_('COM_REDSHOP_CALCULATE_VAT_BASED_ON_LBL'); ?>::<?php echo JText::_('COM_REDSHOP_TOOLTIP_CALCULATE_VAT_BASED_ON_LBL'); ?>">
		<label for="name">
			<?php
			echo JText::_('COM_REDSHOP_CALCULATE_VAT_BASED_ON_LBL');
			?></label></span>
	<?php
			echo $this->lists ['calculate_vat_on'];
			?>
</div>

<div class="form-group">
	<span class="editlinktip hasTip"
		      title="<?php echo JText::_('COM_REDSHOP_REQUIRED_VAT_NUMBER_LBL'); ?>::<?php echo JText::_('COM_REDSHOP_TOOLTIP_REQUIRED_VAT_NUMBER_LBL'); ?>">
		<label for="new_customer_selection"><?php echo JText::_('COM_REDSHOP_REQUIRED_VAT_NUMBER_LBL');?></label>
	</span>
	<?php echo $this->lists ['required_vat_number'];?>
</div>

<hr/>

<div class="form-group">
	<span class="editlinktip hasTip"
		      title="<?php echo JText::_('COM_REDSHOP_VAT_INTRO_LBL'); ?>::<?php echo JText::_('COM_REDSHOP_TOOLTIP_VAT_INTRO_LBL'); ?>">
		<?php echo JText::_('COM_REDSHOP_VAT_INTRO_LBL');?>:</span>
	<textarea class="form-control" type="text" name="vat_introtext" id="vat_introtext" rows="4"
			          cols="40"/><?php echo stripslashes($this->config->get('VAT_INTROTEXT')); ?></textarea>
</div>

<div class="form-group">
	<span class="editlinktip hasTip"
	        title="<?php echo JText::_('COM_REDSHOP_TOOLTIP_WITH_VAT_TEXT_INFO_LBL'); ?>::<?php echo JText::_('COM_REDSHOP_TOOLTIP_WITH_VAT_TEXT_INFO'); ?>">
		<?php echo JText::_('COM_REDSHOP_WITH_VAT_TEXT_INFO_LBL');?>:</span>
	<textarea class="form-control" type="text" name="with_vat_text_info" id="with_vat_text_info" rows="4"
			          cols="40"/><?php echo stripslashes($this->config->get('WITH_VAT_TEXT_INFO')); ?></textarea>
</div>

<div class="form-group">
	<span class="editlinktip hasTip"
	      title="<?php echo JText::_('COM_REDSHOP_TOOLTIP_WITHOUT_VAT_TEXT_INFO_LBL'); ?>::<?php echo JText::_('COM_REDSHOP_TOOLTIP_WITHOUT_VAT_TEXT_INFO'); ?>">
		<?php echo JText::_('COM_REDSHOP_WITHOUT_VAT_TEXT_INFO_LBL');?>:</span>
	<textarea class="form-control" type="text" name="without_vat_text_info" id="without_vat_text_info" rows="4"
			          cols="40"/><?php echo stripslashes($this->config->get('WITHOUT_VAT_TEXT_INFO')); ?></textarea>
</div>
