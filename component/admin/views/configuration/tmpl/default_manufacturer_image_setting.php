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
<fieldset class="adminform">
	<table class="admintable">
		<tr>
			<td class="config_param"><?php echo JText::_('COM_REDSHOP_REDMANUFACTURER_TEMPLATE'); ?></td>
		</tr>
		<tr>
			<td width="100" align="right" class="key">
		<span class="editlinktip hasTip"
		      title="<?php echo JText::_('COM_REDSHOP_DEFAULT_MANUFACTURER_TEMPLATE_LBL'); ?>::<?php echo JText::_('COM_REDSHOP_TOOLTIP_DEFAULT_MANUFACTURER_TEMPLATE_FOR_VM_LBL'); ?>">
		<label
			for="manufacturertemplate"><?php echo JText::_('COM_REDSHOP_DEFAULT_MANUFACTURER_TEMPLATE_LBL');?></label></span>
			</td>
			<td><?php echo $this->lists ['manufacturer_template'];?></td>
		</tr>
	</table>
</fieldset>
<fieldset class="adminform">
	<table class="admintable" width="100%">
		<tr>
			<td class="config_param"><?php echo JText::_('COM_REDSHOP_MANUFACTURER_IMAGE_SETTINGS'); ?></td>
		</tr>
		<tr>
			<td width="100" align="right" class="key">
		<span class="editlinktip hasTip"
		      title="<?php echo JText::_('COM_REDSHOP_TOOLTIP_MANUFACTURER_THUMB_WIDTH'); ?>::<?php echo JText::_('COM_REDSHOP_MANUFACTURER_THUMB_WIDTH_LBL'); ?>">
		<label for="name">
			<?php echo JText::_('COM_REDSHOP_MANUFACTURER_THUMB_WIDTH_HEIGHT');?>
		</label></span></td>
			<td><input type="text" name="manufacturer_thumb_width" id="manufacturer_thumb_width"
			           value="<?php echo MANUFACTURER_THUMB_WIDTH; ?>">
				<input type="text" name="manufacturer_thumb_height" id="manufacturer_thumb_height"
				       value="<?php echo MANUFACTURER_THUMB_HEIGHT; ?>">
			</td>
		</tr>
		<tr>
			<td align="right" class="key">
		<span class="editlinktip hasTip"
		      title="<?php echo JText::_('COM_REDSHOP_TOOLTIP_MANUFACTURER_PRODUCT_THUMB_WIDTH_LBL'); ?>::<?php echo JText::_('COM_REDSHOP_TOOLTIP_MANUFACTURER_PRODUCT_THUMB_WIDTH'); ?>">
		<?php
			echo JText::_('COM_REDSHOP_MANUFACTURER_PRODUCT_THUMB_WIDTH_HEIGHT');
			?></span></td>
			<td>
				<input type="text" name="manufacturer_product_thumb_width" id="manufacturer_product_thumb_width"
				       value="<?php echo MANUFACTURER_PRODUCT_THUMB_WIDTH; ?>">
				<input type="text" name="manufacturer_product_thumb_height" id="manufacturer_product_thumb_height"
				       value="<?php echo MANUFACTURER_PRODUCT_THUMB_HEIGHT; ?>">
			</td>
		</tr>
		<tr>
			<td align="right" class="key">
		<span class="editlinktip hasTip"
		      title="<?php echo JText::_('COM_REDSHOP_TOOLTIP_MANUFACTURER_PRODUCT_THUMB_WIDTH_LBL_TWO_LBL'); ?>::<?php echo JText::_('COM_REDSHOP_TOOLTIP_MANUFACTURER_PRODUCT_THUMB_WIDTH_LBL_TWO'); ?>">
		<?php echo JText::_('COM_REDSHOP_MANUFACTURER_PRODUCT_THUMB_WIDTH_HEIGHT_TWO');?></span></td>
			<td>
				<input type="text" name="manufacturer_product_thumb_width_2" id="manufacturer_product_thumb_width_2"
				       value="<?php echo MANUFACTURER_PRODUCT_THUMB_WIDTH_2; ?>">
				<input type="text" name="manufacturer_product_thumb_height_2" id="manufacturer_product_thumb_height_2"
				       value="<?php echo MANUFACTURER_PRODUCT_THUMB_HEIGHT_2; ?>">
			</td>
		</tr>
		<tr>
			<td align="right" class="key">
		<span class="editlinktip hasTip"
		      title="<?php echo JText::_('COM_REDSHOP_TOOLTIP_MANUFACTURER_PRODUCT_THUMB_WIDTH_THREE'); ?>::<?php echo JText::_('COM_REDSHOP_MANUFACTURER_PRODUCT_THUMB_WIDTH_LBL_THREE'); ?>">
		<label
			for="name"><?php echo JText::_('COM_REDSHOP_MANUFACTURER_PRODUCT_THUMB_WIDTH_LBL_THREE');?></label></span>
			</td>
			<td>
				<input type="text" name="manufacturer_product_thumb_width_3" id="manufacturer_product_thumb_width_3"
				       value="<?php echo MANUFACTURER_PRODUCT_THUMB_WIDTH_3; ?>">
				<input type="text" name="manufacturer_product_thumb_height_3" id="manufacturer_product_thumb_height_3"
				       value="<?php echo MANUFACTURER_PRODUCT_THUMB_HEIGHT_3; ?>">
			</td>
		</tr>
		<tr>
			<td colspan="2">
				<hr/>
			</td>
		</tr>
		<tr>
			<td align="right" class="key">
		<span class="editlinktip hasTip"
		      title="<?php echo JText::_('COM_REDSHOP_TOOLTIP_WATERMARK_MANUFACTURER_IMAGE'); ?>::<?php echo JText::_('COM_REDSHOP_WATERMARK_MANUFACTURER_IMAGE_LBL'); ?>">
		<label for="name"><?php echo JText::_('COM_REDSHOP_WATERMARK_MANUFACTURER_IMAGE_LBL');?></label></span></td>
			<td><?php
				echo $this->lists ['watermark_manufacturer_image'];
				?>
			</td>
		</tr>
		<tr>
			<td align="right" class="key">
		<span class="editlinktip hasTip"
		      title="<?php echo JText::_('COM_REDSHOP_TOOLTIP_WATERMARK_MANUFACTURER_THUMB_IMAGE'); ?>::<?php echo JText::_('COM_REDSHOP_WATERMARK_MANUFACTURER_THUMB_IMAGE_LBL'); ?>">
		<label for="name"><?php echo JText::_('COM_REDSHOP_WATERMARK_MANUFACTURER_THUMB_IMAGE_LBL');?></label></span>
			</td>
			<td><?php
				echo $this->lists ['watermark_manufacturer_thumb_image'];
				?>
			</td>
		</tr>
	</table>
</fieldset>
