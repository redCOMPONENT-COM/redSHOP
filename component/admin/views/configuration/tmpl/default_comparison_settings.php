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

<div class="row">
	<div class="col-sm-6">
		<legend><?php echo JText::_('COM_REDSHOP_COMPARISON_SETTINGS'); ?></legend>

		<div class="form-group">
			<span class="editlinktip hasTip"
					  title="<?php echo JText::_('COM_REDSHOP_COMPARE_PRODUCTS_LBL'); ?>::<?php echo JText::_('COM_REDSHOP_TOOLTIP_COMPARE_PRODUCTS'); ?>">
				<label for="name">
					<?php echo JText::_('COM_REDSHOP_COMPARE_PRODUCTS_LBL');?>
				</label>
			 </span>
			 <?php echo $this->lists ['compare_products'];?>
		</div>
		<div class="form-group">
			<span class="editlinktip hasTip"
					      title="<?php echo JText::_('COM_REDSHOP_PRODUCT_COMPARE_LIMIT_LBL'); ?>::<?php echo JText::_('COM_REDSHOP_TOOLTIP_PRODUCT_COMPARE_LIMIT_LBL'); ?>">
					<label for="name"><?php echo JText::_('COM_REDSHOP_PRODUCT_COMPARE_LIMIT_LBL');?></label></span>
			<input type="text" name="product_compare_limit" id="product_compare_limit"
							       value="<?php echo $this->config->get('PRODUCT_COMPARE_LIMIT'); ?>">
		</div>

		<div class="form-group">
			<span class="editlinktip hasTip"
						      title="<?php echo JText::_('COM_REDSHOP_PRODUCT_COMPARISON_TYPE_LBL'); ?>::<?php echo JText::_('COM_REDSHOP_TOOLTIP_PRODUCT_COMPARISON_TYPE_LBL'); ?>">
						<label
							for="name"><?php echo JText::_('COM_REDSHOP_PRODUCT_COMPARISON_TYPE_LBL');?></label></span>
			<?php echo $this->lists ['product_comparison_type'];?>
		</div>

	</div>

	<div class="col-sm-6">
		<legend><?php echo JText::_('COM_REDSHOP_COMPARISON_LAYOUT'); ?></legend>
		<div class="form-group">
			<span class="editlinktip hasTip"
						      title="<?php echo JText::_('COM_REDSHOP_COMPARE_PRODUCT_TEMPLATE_LBL'); ?>::<?php echo JText::_('TOOLTIP_COMPARE_PRODUCT_TEMPLATE'); ?>">
						<label
							for="name"><?php echo JText::_('COM_REDSHOP_COMPARE_PRODUCT_TEMPLATE_LBL');?></label></span>
			<?php echo $this->lists ['compare_template_id'];?>
		</div>

		<div class="form-group">
			<span class="editlinktip hasTip"
					      title="<?php echo JText::_('COM_REDSHOP_TOOLTIP_COMPARE_PRODUCT_THUMB_WIDTH_LBL'); ?>::<?php echo JText::_('COM_REDSHOP_TOOLTIP_COMPARE_PRODUCT_THUMB_WIDTH'); ?>">
					<label for="name"><?php echo JText::_('COM_REDSHOP_COMPARE_PRODUCT_THUMB_WIDTH_HEIGHT'); ?></label></span>
			<input type="text" name="compare_product_thumb_width" id="compare_product_thumb_width"
								       value="<?php echo $this->config->get('COMPARE_PRODUCT_THUMB_WIDTH'); ?>">
			<input type="text" name="compare_product_thumb_height" id="compare_product_thumb_height"
								       value="<?php echo $this->config->get('COMPARE_PRODUCT_THUMB_HEIGHT'); ?>">
		</div>
	</div>
</div>
