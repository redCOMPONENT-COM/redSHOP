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
<legend><?php echo JText::_('COM_REDSHOP_IMAGE_SETTINGS'); ?></legend>

<div class="form-group">
	<span class="editlinktip hasTip"
		      title="<?php echo JText::_('COM_REDSHOP_CAT_IS_LIGHTBOX'); ?>::<?php echo JText::_('COM_REDSHOP_TOOLTIP_CAT_IS_LIGHTBOX'); ?>">
		<label for="name"><?php echo JText::_('COM_REDSHOP_CAT_IS_LIGHTBOX');?></label></span>
	<?php echo $this->lists ['cat_is_lightbox'];?>
</div>

<hr/>

<div class="form-group">
	<span class="editlinktip hasTip"
		      title="<?php echo JText::_('COM_REDSHOP_TOOLTIP_THUMB_WIDTH'); ?>::<?php echo JText::_('COM_REDSHOP_THUMB_WIDTH'); ?>">
		<label for="name"><?php echo JText::_('COM_REDSHOP_CATEGORY_THUMB_WIDTH_HEIGHT');?></label></span>
	<input type="text" name="thumb_width" id="thumb_width" value="<?php echo $this->config->get('THUMB_WIDTH'); ?>">
			<input type="text" name="thumb_height" id="thumb_height" value="<?php echo $this->config->get('THUMB_HEIGHT'); ?>">
</div>

<div class="form-group">
	<span class="editlinktip hasTip"
		      title="<?php echo JText::_('COM_REDSHOP_TOOLTIP_THUMB_WIDTH_TWO'); ?>::<?php echo JText::_('COM_REDSHOP_THUMB_WIDTH_TWO'); ?>">
		<label for="name"><?php echo JText::_('COM_REDSHOP_CATEGORY_THUMB_WIDTH_HEIGHT_TWO');?></label></span>
	<input type="text" name="thumb_width_2" id="thumb_width_2" value="<?php echo $this->config->get('THUMB_WIDTH_2'); ?>">
	<input type="text" name="thumb_height_2" id="thumb_height_2" value="<?php echo $this->config->get('THUMB_HEIGHT_2'); ?>">
</div>

<div class="form-group">
	<span class="editlinktip hasTip"
		      title="<?php echo JText::_('COM_REDSHOP_TOOLTIP_THUMB_WIDTH_THREE'); ?>::<?php echo JText::_('COM_REDSHOP_THUMB_WIDTH_THREE'); ?>">
		<label for="name"><?php echo JText::_('COM_REDSHOP_CATEGORY_THUMB_WIDTH_HEIGHT_THREE');?></label>
	</span>
	<input type="text" name="thumb_width_3" id="thumb_width_3" value="<?php echo $this->config->get('THUMB_WIDTH_3'); ?>">
	<input type="text" name="thumb_height_3" id="thumb_height_3" value="<?php echo $this->config->get('THUMB_HEIGHT_3'); ?>">
</div>

<div class="form-group">
	<span class="editlinktip hasTip"
		      title="<?php echo JText::_('COM_REDSHOP_TOOLTIP_CATEGORY_PRODUCT_THUMB_WIDTH'); ?>::<?php echo JText::_('COM_REDSHOP_CATEGORY_PRODUCT_THUMB_WIDTH_LBL'); ?>">
		<label for="name">
			<?php
			echo JText::_('COM_REDSHOP_CATEGORY_PRODUCT_THUMB_WIDTH_HEIGHT_LBL');
			?>
		</label>
	</span>
	<input type="text" name="category_product_thumb_width" id="category_product_thumb_width"
			       value="<?php echo $this->config->get('CATEGORY_PRODUCT_THUMB_WIDTH'); ?>">
	<input type="text" name="category_product_thumb_height" id="category_product_thumb_height"
			       value="<?php echo $this->config->get('CATEGORY_PRODUCT_THUMB_HEIGHT'); ?>">
</div>

<div class="form-group">
	<span class="editlinktip hasTip"
		      title="<?php echo JText::_('COM_REDSHOP_TOOLTIP_CATEGORY_PRODUCT_THUMB_WIDTH_TWO'); ?>::<?php echo JText::_('COM_REDSHOP_CATEGORY_PRODUCT_THUMB_WIDTH_LBL_TWO'); ?>">
		<label for="name"><?php echo JText::_('COM_REDSHOP_CATEGORY_PRODUCT_THUMB_WIDTH_HEIGHT_TWO');?></label>
	</span>
	<input type="text" name="category_product_thumb_width_2" id="category_product_thumb_width_2"
			       value="<?php echo $this->config->get('CATEGORY_PRODUCT_THUMB_WIDTH_2'); ?>">
	<input type="text" name="category_product_thumb_height_2" id="category_product_thumb_height_2"
			       value="<?php echo $this->config->get('CATEGORY_PRODUCT_THUMB_HEIGHT_2'); ?>">
</div>

<div class="form-group">
	<span class="editlinktip hasTip"
		      title="<?php echo JText::_('COM_REDSHOP_TOOLTIP_CATEGORY_PRODUCT_THUMB_WIDTH_THREE'); ?>::<?php echo JText::_('COM_REDSHOP_CATEGORY_PRODUCT_THUMB_WIDTH_LBL_THREE'); ?>">
		<label for="name"><?php echo JText::_('COM_REDSHOP_CATEGORY_PRODUCT_THUMB_WIDTH_HEIGHT_THREE');?></label>
	</span>
	<input type="text" name="category_product_thumb_width_3" id="category_product_thumb_width_3"
			       value="<?php echo $this->config->get('CATEGORY_PRODUCT_THUMB_WIDTH_3'); ?>">
	<input type="text" name="category_product_thumb_height_3" id="category_product_thumb_height_3"
			       value="<?php echo $this->config->get('CATEGORY_PRODUCT_THUMB_HEIGHT_3'); ?>">
</div>

<hr/>

<div class="form-group">
	<span class="editlinktip hasTip"
		      title="<?php echo JText::_('COM_REDSHOP_TOOLTIP_WATERMARK_CATEGORY_IMAGE_LBL'); ?>::<?php echo JText::_('COM_REDSHOP_TOOLTIP_WATERMARK_CATEGORY_IMAGE'); ?>">
		<label for="name">
			<?php
			echo JText::_('COM_REDSHOP_WATERMARK_CATEGORY_IMAGE_LBL');
			?>
		</label>
	</span>
	<?php echo $this->lists ['watermark_category_image']; ?>
</div>

<div class="form-group">
	<span class="editlinktip hasTip"
		      title="<?php echo JText::_('COM_REDSHOP_TOOLTIP_WATERMARK_CATEGORY_THUMB_IMAGE_LBL'); ?>::<?php echo JText::_('COM_REDSHOP_TOOLTIP_WATERMARK_CATEGORY_THUMB_IMAGE'); ?>">
		<label for="name">
			<?php
			echo JText::_('COM_REDSHOP_WATERMARK_CATEGORY_THUMB_IMAGE_LBL');
			?>
		</label>
	</span>
	<?php echo $this->lists ['watermark_category_thumb_image']; ?>
</div>
