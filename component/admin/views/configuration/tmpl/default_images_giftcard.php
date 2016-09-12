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
<legend><?php echo JText::_('COM_REDSHOP_GIFTCARD_IMAGE_SETTING_TAB'); ?></legend>

<div class="form-group">
	<span class="editlinktip hasTip"
		      title="<?php echo JText::_('COM_REDSHOP_GIFTCARD_THUMB_WIDTH_LBL'); ?>::<?php echo JText::_('COM_REDSHOP_TOOLTIP_GIFTCARD_THUMB_WIDTH_LBL'); ?>">
		<label for="name">
			<?php echo JText::_('COM_REDSHOP_GIFTCARD_THUMB_WIDTH_HEIGHT');?></label></span>
	<input type="text" name="giftcard_thumb_width" id="giftcard_thumb_width"
			       value="<?php echo $this->config->get('GIFTCARD_THUMB_WIDTH'); ?>">
	<input type="text" name="giftcard_thumb_height" id="giftcard_thumb_height"
			       value="<?php echo $this->config->get('GIFTCARD_THUMB_HEIGHT'); ?>">
</div>

<div class="form-group">
	<span class="editlinktip hasTip"
		      title="<?php echo JText::_('COM_REDSHOP_GIFTCARD_LIST_THUMB_WIDTH_LBL'); ?>::<?php echo JText::_('COM_REDSHOP_TOOLTIP_GIFTCARD_LIST_THUMB_WIDTH_LBL'); ?>">
		<label for="name"><?php echo JText::_('COM_REDSHOP_GIFTCARD_LIST_THUMB_WIDTH_HEIGHT');?></label>
	</span>
	<input type="text" name="giftcard_list_thumb_width" id="giftcard_list_thumb_width"
			       value="<?php echo $this->config->get('GIFTCARD_LIST_THUMB_WIDTH'); ?>">
	<input type="text" name="giftcard_list_thumb_height" id="giftcard_list_thumb_height"
			       value="<?php echo $this->config->get('GIFTCARD_LIST_THUMB_HEIGHT'); ?>">
</div>

<hr/>

<div class="form-group">
	<span class="editlinktip hasTip"
		      title="<?php echo JText::_('COM_REDSHOP_WATERMARK_GIFTCARD_IMAGE'); ?>::<?php echo JText::_('COM_REDSHOP_TOOLTIP_WATERMARK_GIFTCARD_IMAGE_LBL'); ?>">
		<label for="name"><?php echo JText::_('COM_REDSHOP_WATERMARK_GIFTCARD_IMAGE_LBL');?></label>
	</span>
	<?php echo $this->lists ['watermark_giftcart_image'];?>
</div>

<div class="form-group">
	<span class="editlinktip hasTip"
		      title="<?php echo JText::_('COM_REDSHOP_WATERMARK_GIFTCARD_THUMB_IMAGE'); ?>::<?php echo JText::_('COM_REDSHOP_TOOLTIP_WATERMARK_GIFTCARD_THUMB_IMAGE_LBL'); ?>">
		<label for="name">
			<?php echo JText::_('COM_REDSHOP_WATERMARK_GIFTCARD_THUMB_IMAGE_LBL');?>
		</label></span>
	<?php echo $this->lists ['watermark_giftcart_thumb_image'];?>
</div>
