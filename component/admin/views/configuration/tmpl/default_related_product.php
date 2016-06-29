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
		<fieldset class="adminform">
			<legend><?php echo JText::_('COM_REDSHOP_RELATED_PRODUCT_SETTINGS'); ?></legend>

			<div class="form-group">
				<span class="editlinktip hasTip"
							      title="<?php echo JText::_('COM_REDSHOP_TWOWAY_RELATED_PRODUCT_LBL'); ?>::<?php echo JText::_('COM_REDSHOP_TOOLTIP_TWOWAY_RELATED_PRODUCT_LBL'); ?>">
					<label
								for="twoway_related_product"><?php echo JText::_('COM_REDSHOP_TWOWAY_RELATED_PRODUCT_LBL');?></label></span>
				<?php echo $this->lists ['twoway_related_product'];?>
			</div>

			<div class="form-group">
				<span class="editlinktip hasTip"
								      title="<?php echo JText::_('COM_REDSHOP_CHILDPRODUCT_DROPDOWN_LBL'); ?>::<?php echo JText::_('COM_REDSHOP_TOOLTIP_CHILDPRODUCT_DROPDOWN_LBL'); ?>">
					<label
									for="childproductdropdown"><?php echo JText::_('COM_REDSHOP_CHILDPRODUCT_DROPDOWN_LBL');?></label></span>
				<?php echo $this->lists ['childproduct_dropdown'];?>
			</div>

			<div class="form-group">
				<span class="editlinktip hasTip"
							      title="<?php echo JText::_('COM_REDSHOP_PURCHASE_PARENT_WITH_CHILD_LBL'); ?>::<?php echo JText::_('COM_REDSHOP_TOOLTIP_PURCHASE_PARENT_WITH_CHILD_LBL'); ?>">
					<label
								for="parentchildtoggle"><?php echo JText::_('COM_REDSHOP_PURCHASE_PARENT_WITH_CHILD_LBL');?></label></span>
				<?php echo $this->lists ['purchase_parent_with_child'];?>
			</div>

			<div class="form-group">
				<span class="editlinktip hasTip"
							      title="<?php echo JText::_('COM_REDSHOP_TOOLTIP_RELATED_PRODUCT_ORDERING_METHOD_LBL'); ?>::<?php echo JText::_('COM_REDSHOP_RELATED_PRODUCT_ORDERING_METHOD_LBL'); ?>">
							<label
								for="name"><?php echo JText::_('COM_REDSHOP_RELATED_PRODUCT_ORDERING_METHOD_LBL');?></label></span>
				<?php echo $this->lists['default_related_ordering_method'];?>
			</div>

			<div class="form-group">
				<span class="editlinktip hasTip"
							      title="<?php echo JText::_('COM_REDSHOP_RELATED_PRODUCT_DESC_MAX_CHARS_LBL'); ?>::<?php echo JText::_('COM_REDSHOP_TOOLTIP_RELATED_PRODUCT_DESC_MAX_CHARS_LBL'); ?>">
							<label
								for="name"><?php echo JText::_('COM_REDSHOP_RELATED_PRODUCT_DESC_MAX_CHARS_LBL');?></label>
				</span>
				<input type="text" name="related_product_desc_max_chars" id="related_product_desc_max_chars"
							       value="<?php echo $this->config->get('RELATED_PRODUCT_DESC_MAX_CHARS'); ?>">
			</div>

			<div class="form-group">
				<span class="editlinktip hasTip"
							      title="<?php echo JText::_('COM_REDSHOP_RELATED_PRODUCT_DESC_END_SUFFIX_LBL'); ?>::<?php echo JText::_('COM_REDSHOP_TOOLTIP_RELATED_PRODUCT_DESC_END_SUFFIX_LBL'); ?>">
							<label
								for="name"><?php echo JText::_('COM_REDSHOP_RELATED_PRODUCT_DESC_END_SUFFIX_LBL');?></label></span>
				<input type="text" name="related_product_desc_end_suffix"
							       id="related_product_desc_end_suffix"
							       value="<?php echo $this->config->get('RELATED_PRODUCT_DESC_END_SUFFIX'); ?>">
			</div>

			<div class="form-group">
				<span class="editlinktip hasTip"
							      title="<?php echo JText::_('COM_REDSHOP_RELATED_PRODUCT_SHORT_DESC_MAX_CHARS_LBL'); ?>::<?php echo JText::_('COM_REDSHOP_TOOLTIP_RELATED_PRODUCT_SHORT_DESC_MAX_CHARS_LBL'); ?>">
							<label
								for="name"><?php echo JText::_('COM_REDSHOP_RELATED_PRODUCT_SHORT_DESC_MAX_CHARS_LBL');?></label></span>
				<input type="text" name="related_product_short_desc_max_chars"
							       id="related_product_short_desc_max_chars"
							       value="<?php echo $this->config->get('RELATED_PRODUCT_SHORT_DESC_MAX_CHARS'); ?>">
			</div>

			<div class="form-group">
				<span class="editlinktip hasTip"
							      title="<?php echo JText::_('COM_REDSHOP_RELATED_PRODUCT_SHORT_DESC_END_SUFFIX_LBL'); ?>::<?php echo JText::_('COM_REDSHOP_TOOLTIP_RELATED_PRODUCT_SHORT_DESC_END_SUFFIX_LBL'); ?>">
							<label
								for="name"><?php echo JText::_('COM_REDSHOP_RELATED_PRODUCT_SHORT_DESC_END_SUFFIX_LBL');?></label></span>
				<input type="text" name="related_product_short_desc_end_suffix"
							       id="related_product_short_desc_end_suffix"
							       value="<?php echo $this->config->get('RELATED_PRODUCT_SHORT_DESC_END_SUFFIX'); ?>">
			</div>

			<div class="form-group">
				<span class="editlinktip hasTip"
							      title="<?php echo JText::_('COM_REDSHOP_RELATED_PRODUCT_TITLE_MAX_CHARS_LBL'); ?>::<?php echo JText::_('COM_REDSHOP_TOOLTIP_RELATED_PRODUCT_TITLE_MAX_CHARS_LBL'); ?>">
							<label
								for="name"><?php echo JText::_('COM_REDSHOP_RELATED_PRODUCT_TITLE_MAX_CHARS_LBL'); ?></label></span>
				<input type="text" name="related_product_title_max_chars"
							       id="related_product_title_max_chars"
							       value="<?php echo $this->config->get('RELATED_PRODUCT_TITLE_MAX_CHARS'); ?>">
			</div>

			<div class="form-group">
				<span class="editlinktip hasTip"
							      title="<?php echo JText::_('COM_REDSHOP_RELATED_PRODUCT_TITLE_END_SUFFIX_LBL'); ?>::<?php echo JText::_('COM_REDSHOP_TOOLTIP_RELATED_PRODUCT_TITLE_END_SUFFIX_LBL'); ?>">
							<label
								for="name"><?php echo JText::_('COM_REDSHOP_RELATED_PRODUCT_TITLE_END_SUFFIX_LBL');?></label></span>
				<input type="text" name="related_product_title_end_suffix"
							       id="related_product_title_end_suffix"
							       value="<?php echo $this->config->get('RELATED_PRODUCT_TITLE_END_SUFFIX'); ?>">
			</div>

		</fieldset>
	</div>
	<div class="col-sm-6">
		<fieldset class="adminform">
			<legend><?php echo JText::_('COM_REDSHOP_RELATED_PRODUCT_IMAGE_SETTINGS'); ?></legend>

			<div class="form-group">
				<span class="editlinktip hasTip"
			      title="<?php echo JText::_('COM_REDSHOP_TOOLTIP_RELATED_PRODUCT_THUMB_WIDTH'); ?>::<?php echo JText::_('COM_REDSHOP_RELATED_PRODUCT_THUMB_WIDTH_LBL'); ?>">
					<label for="name"><?php echo JText::_('COM_REDSHOP_RELATED_PRODUCT_THUMB_WIDTH_HEIGHT'); ?></label>
				</span>
				<input type="text" name="related_product_thumb_width" id="related_product_thumb_width"
							       value="<?php echo $this->config->get('RELATED_PRODUCT_THUMB_WIDTH'); ?>">
				<input type="text" name="related_product_thumb_height" id="related_product_thumb_height"
							       value="<?php echo $this->config->get('RELATED_PRODUCT_THUMB_HEIGHT'); ?>">
			</div>

			<div class="form-group">
				<span class="editlinktip hasTip"
			      title="<?php echo JText::_('COM_REDSHOP_TOOLTIP_RELATED_PRODUCT_THUMB_WIDTH_TWO'); ?>::<?php echo JText::_('COM_REDSHOP_RELATED_PRODUCT_THUMB_WIDTH_LBL_TWO'); ?>">
					<label for="name"><?php echo JText::_('COM_REDSHOP_RELATED_PRODUCT_THUMB_WIDTH_HEIGHT_TWO'); ?></label>
				</span>
				<input type="text" name="related_product_thumb_width_2" id="related_product_thumb_width_2"
							       value="<?php echo $this->config->get('RELATED_PRODUCT_THUMB_WIDTH_2'); ?>">
				<input type="text" name="related_product_thumb_height_2" id="related_product_thumb_height_2"
							       value="<?php echo $this->config->get('RELATED_PRODUCT_THUMB_HEIGHT_2'); ?>">
			</div>

			<div class="form-group">
				<span class="editlinktip hasTip"
			      title="<?php echo JText::_('COM_REDSHOP_TOOLTIP_RELATED_PRODUCT_THUMB_WIDTH_THREE'); ?>::<?php echo JText::_('COM_REDSHOP_RELATED_PRODUCT_THUMB_WIDTH_LBL_THREE'); ?>">
					<label for="name"><?php echo JText::_('COM_REDSHOP_RELATED_PRODUCT_THUMB_WIDTH_HEIGHT_THREE');?></label></span>
				<input type="text" name="related_product_thumb_width_3" id="related_product_thumb_width_3"
							       value="<?php echo $this->config->get('RELATED_PRODUCT_THUMB_WIDTH_3'); ?>">
				<input type="text" name="related_product_thumb_height_3" id="related_product_thumb_height_3"
							       value="<?php echo $this->config->get('RELATED_PRODUCT_THUMB_HEIGHT_3'); ?>">
			</div>
		</fieldset>
	</div>
</div>

