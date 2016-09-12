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

<legend><?php echo JText::_('COM_REDSHOP_MANUFACTURER_IMAGE_SETTINGS'); ?></legend>

<div class="form-group">
	<span class="editlinktip hasTip"
		      title="<?php echo JText::_('COM_REDSHOP_TOOLTIP_MANUFACTURER_THUMB_WIDTH'); ?>::<?php echo JText::_('COM_REDSHOP_MANUFACTURER_THUMB_WIDTH_LBL'); ?>">
		<label>
			<?php echo JText::_('COM_REDSHOP_MANUFACTURER_THUMB_WIDTH_HEIGHT');?>
		</label>
	</span>
	<input type="text" name="manufacturer_thumb_width" id="manufacturer_thumb_width"
			           value="<?php echo $this->config->get('MANUFACTURER_THUMB_WIDTH'); ?>">
	<input type="text" name="manufacturer_thumb_height" id="manufacturer_thumb_height"
				       value="<?php echo $this->config->get('MANUFACTURER_THUMB_HEIGHT'); ?>">
</div>

<div class="form-group">
	<span class="editlinktip hasTip"
		      title="<?php echo JText::_('COM_REDSHOP_TOOLTIP_MANUFACTURER_PRODUCT_THUMB_WIDTH_LBL'); ?>::<?php echo JText::_('COM_REDSHOP_TOOLTIP_MANUFACTURER_PRODUCT_THUMB_WIDTH'); ?>">
		<label><?php
			echo JText::_('COM_REDSHOP_MANUFACTURER_PRODUCT_THUMB_WIDTH_HEIGHT');
			?></label>
	</span>
	<input type="text" name="manufacturer_product_thumb_width" id="manufacturer_product_thumb_width"
				       value="<?php echo $this->config->get('MANUFACTURER_PRODUCT_THUMB_WIDTH'); ?>">
	<input type="text" name="manufacturer_product_thumb_height" id="manufacturer_product_thumb_height"
				       value="<?php echo $this->config->get('MANUFACTURER_PRODUCT_THUMB_HEIGHT'); ?>">
</div>

<div class="form-group">
	<span class="editlinktip hasTip"
		      title="<?php echo JText::_('COM_REDSHOP_TOOLTIP_MANUFACTURER_PRODUCT_THUMB_WIDTH_LBL_TWO_LBL'); ?>::<?php echo JText::_('COM_REDSHOP_TOOLTIP_MANUFACTURER_PRODUCT_THUMB_WIDTH_LBL_TWO'); ?>">
		<label><?php echo JText::_('COM_REDSHOP_MANUFACTURER_PRODUCT_THUMB_WIDTH_HEIGHT_TWO');?></label></span>
	<input type="text" name="manufacturer_product_thumb_width_2" id="manufacturer_product_thumb_width_2"
				       value="<?php echo $this->config->get('MANUFACTURER_PRODUCT_THUMB_WIDTH_2'); ?>">
	<input type="text" name="manufacturer_product_thumb_height_2" id="manufacturer_product_thumb_height_2"
				       value="<?php echo $this->config->get('MANUFACTURER_PRODUCT_THUMB_HEIGHT_2'); ?>">
</div>

<div class="form-group">
	<span class="editlinktip hasTip"
		      title="<?php echo JText::_('COM_REDSHOP_TOOLTIP_MANUFACTURER_PRODUCT_THUMB_WIDTH_THREE'); ?>::<?php echo JText::_('COM_REDSHOP_MANUFACTURER_PRODUCT_THUMB_WIDTH_LBL_THREE'); ?>">
		<label
			><?php echo JText::_('COM_REDSHOP_MANUFACTURER_PRODUCT_THUMB_WIDTH_LBL_THREE');?></label>
	</span>
	<input type="text" name="manufacturer_product_thumb_width_3" id="manufacturer_product_thumb_width_3"
				       value="<?php echo $this->config->get('MANUFACTURER_PRODUCT_THUMB_WIDTH_3'); ?>">
	<input type="text" name="manufacturer_product_thumb_height_3" id="manufacturer_product_thumb_height_3"
				       value="<?php echo $this->config->get('MANUFACTURER_PRODUCT_THUMB_HEIGHT_3'); ?>">
</div>

<div class="form-group">
	<span class="editlinktip hasTip"
		      title="<?php echo JText::_('COM_REDSHOP_TOOLTIP_WATERMARK_MANUFACTURER_IMAGE'); ?>::<?php echo JText::_('COM_REDSHOP_WATERMARK_MANUFACTURER_IMAGE_LBL'); ?>">
		<label><?php echo JText::_('COM_REDSHOP_WATERMARK_MANUFACTURER_IMAGE_LBL');?></label>
	</span>

	<?php echo $this->lists ['watermark_manufacturer_image']; ?>
</div>

<div class="form-group">
	<span class="editlinktip hasTip"
		      title="<?php echo JText::_('COM_REDSHOP_TOOLTIP_WATERMARK_MANUFACTURER_THUMB_IMAGE'); ?>::<?php echo JText::_('COM_REDSHOP_WATERMARK_MANUFACTURER_THUMB_IMAGE_LBL'); ?>">
		<label><?php echo JText::_('COM_REDSHOP_WATERMARK_MANUFACTURER_THUMB_IMAGE_LBL');?></label>
	</span>
	<?php echo $this->lists ['watermark_manufacturer_thumb_image']; ?>
</div>
