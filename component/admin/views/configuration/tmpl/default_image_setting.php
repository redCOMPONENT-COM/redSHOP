<?php
/**
 * @package     RedSHOP.Backend
 * @subpackage  Template
 *
 * @copyright   Copyright (C) 2005 - 2013 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */
defined('_JEXEC') or die ('Restricted access');

?>
<table class="admintable" id="measurement" width="100%">
	<tr>
		<td class="config_param"><?php echo JText::_('COM_REDSHOP_IMAGE_SETTINGS'); ?></td>
	</tr>
	<tr>
		<td width="100" align="right" class="key">
		<span class="editlinktip hasTip"
		      title="<?php echo JText::_('COM_REDSHOP_TOOLTIP_USE_IMAGE_SIZE_SWAPPING_LBL'); ?>::<?php echo JText::_('COM_REDSHOP_TOOLTIP_USE_IMAGE_SIZE_SWAPPING'); ?>">
		<label for="name"><?php echo JText::_('COM_REDSHOP_USE_IMAGE_SIZE_SWAPPING_LBL');?></label></span>
		</td>
		<td><?php echo $this->lists ['use_image_size_swapping'];?></td>
	</tr>
	<tr>
		<td colspan="2">
			<hr/>
		</td>
	</tr>
	<tr>
		<td width="100" align="right" class="key">
		<span class="editlinktip hasTip"
		      title="<?php echo JText::_('COM_REDSHOP_TOOLTIP_THUMB_WIDTH'); ?>::<?php echo JText::_('COM_REDSHOP_THUMB_WIDTH'); ?>">
		<label for="name"><?php echo JText::_('COM_REDSHOP_CATEGORY_THUMB_WIDTH_HEIGHT');?></label></span>
		</td>
		<td>
			<input type="text" name="thumb_width" id="thumb_width" value="<?php echo THUMB_WIDTH; ?>">
			<input type="text" name="thumb_height" id="thumb_height" value="<?php echo THUMB_HEIGHT; ?>">
		</td>
	</tr>
	<tr>
		<td width="100" align="right" class="key">
		<span class="editlinktip hasTip"
		      title="<?php echo JText::_('COM_REDSHOP_TOOLTIP_THUMB_WIDTH_TWO'); ?>::<?php echo JText::_('COM_REDSHOP_THUMB_WIDTH_TWO'); ?>">
		<label for="name"><?php echo JText::_('COM_REDSHOP_CATEGORY_THUMB_WIDTH_HEIGHT_TWO');?></label></span>
		</td>
		<td>
			<input type="text" name="thumb_width_2" id="thumb_width_2" value="<?php echo THUMB_WIDTH_2; ?>">
			<input type="text" name="thumb_height_2" id="thumb_height_2" value="<?php echo THUMB_HEIGHT_2; ?>">
		</td>
	</tr>
	<tr>
		<td width="100" align="right" class="key">
		<span class="editlinktip hasTip"
		      title="<?php echo JText::_('COM_REDSHOP_TOOLTIP_THUMB_WIDTH_THREE'); ?>::<?php echo JText::_('COM_REDSHOP_THUMB_WIDTH_THREE'); ?>">
		<label for="name"><?php echo JText::_('COM_REDSHOP_CATEGORY_THUMB_WIDTH_HEIGHT_THREE');?></label></span>
		</td>
		<td>
			<input type="text" name="thumb_width_3" id="thumb_width_3" value="<?php echo THUMB_WIDTH_3; ?>">
			<input type="text" name="thumb_height_3" id="thumb_height_3" value="<?php echo THUMB_HEIGHT_3; ?>">
		</td>
	</tr>
	<tr>
		<td colspan="2">
			<hr/>
		</td>
	</tr>
	<tr>
		<td width="100" align="right" class="key">
		<span class="editlinktip hasTip"
		      title="<?php echo JText::_('COM_REDSHOP_TOOLTIP_CATEGORY_PRODUCT_THUMB_WIDTH'); ?>::<?php echo JText::_('COM_REDSHOP_CATEGORY_PRODUCT_THUMB_WIDTH_LBL'); ?>">
		<label for="name">
			<?php
			echo JText::_('COM_REDSHOP_CATEGORY_PRODUCT_THUMB_WIDTH_HEIGHT_LBL');
			?>
		</label></span>
		</td>
		<td>
			<input type="text" name="category_product_thumb_width" id="category_product_thumb_width"
			       value="<?php echo CATEGORY_PRODUCT_THUMB_WIDTH; ?>">
			<input type="text" name="category_product_thumb_height" id="category_product_thumb_height"
			       value="<?php echo CATEGORY_PRODUCT_THUMB_HEIGHT; ?>">
		</td>
	</tr>
	<tr>
		<td width="100" align="right" class="key">
		<span class="editlinktip hasTip"
		      title="<?php echo JText::_('COM_REDSHOP_TOOLTIP_CATEGORY_PRODUCT_THUMB_WIDTH_TWO'); ?>::<?php echo JText::_('COM_REDSHOP_CATEGORY_PRODUCT_THUMB_WIDTH_LBL_TWO'); ?>">
		<label for="name"><?php echo JText::_('COM_REDSHOP_CATEGORY_PRODUCT_THUMB_WIDTH_HEIGHT_TWO');?></label></span>
		</td>
		<td>
			<input type="text" name="category_product_thumb_width_2" id="category_product_thumb_width_2"
			       value="<?php echo CATEGORY_PRODUCT_THUMB_WIDTH_2; ?>">
			<input type="text" name="category_product_thumb_height_2" id="category_product_thumb_height_2"
			       value="<?php echo CATEGORY_PRODUCT_THUMB_HEIGHT_2; ?>">
		</td>
	</tr>
	<tr>
		<td width="100" align="right" class="key">
		<span class="editlinktip hasTip"
		      title="<?php echo JText::_('COM_REDSHOP_TOOLTIP_CATEGORY_PRODUCT_THUMB_WIDTH_THREE'); ?>::<?php echo JText::_('COM_REDSHOP_CATEGORY_PRODUCT_THUMB_WIDTH_LBL_THREE'); ?>">
		<label for="name"><?php echo JText::_('COM_REDSHOP_CATEGORY_PRODUCT_THUMB_WIDTH_HEIGHT_THREE');?></label></span>
		</td>
		<td>
			<input type="text" name="category_product_thumb_width_3" id="category_product_thumb_width_3"
			       value="<?php echo CATEGORY_PRODUCT_THUMB_WIDTH_3; ?>">
			<input type="text" name="category_product_thumb_height_3" id="category_product_thumb_height_3"
			       value="<?php echo CATEGORY_PRODUCT_THUMB_HEIGHT_3; ?>">
		</td>
	</tr>
	<tr>
		<td colspan="2">
			<hr/>
		</td>
	</tr>
	<tr>
		<td width="100" align="right" class="key">
		<span class="editlinktip hasTip"
		      title="<?php echo JText::_('COM_REDSHOP_TOOLTIP_WATERMARK_CATEGORY_IMAGE_LBL'); ?>::<?php echo JText::_('COM_REDSHOP_TOOLTIP_WATERMARK_CATEGORY_IMAGE'); ?>">
		<label for="name">
			<?php
			echo JText::_('COM_REDSHOP_WATERMARK_CATEGORY_IMAGE_LBL');
			?>
		</label></span></td>
		<td><?php
			echo $this->lists ['watermark_category_image'];
			?>
		</td>
	</tr>
	<tr>
		<td width="100" align="right" class="key">
		<span class="editlinktip hasTip"
		      title="<?php echo JText::_('COM_REDSHOP_TOOLTIP_WATERMARK_CATEGORY_THUMB_IMAGE_LBL'); ?>::<?php echo JText::_('COM_REDSHOP_TOOLTIP_WATERMARK_CATEGORY_THUMB_IMAGE'); ?>">
		<label for="name">
			<?php
			echo JText::_('COM_REDSHOP_WATERMARK_CATEGORY_THUMB_IMAGE_LBL');
			?>
		</label></span></td>
		<td><?php
			echo $this->lists ['watermark_category_thumb_image'];
			?>
		</td>
	</tr>
</table>
