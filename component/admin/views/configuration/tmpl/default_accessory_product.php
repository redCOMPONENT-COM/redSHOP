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
<table class="adminlist" width="100%" cellpadding="0" cellspacing="0">

	<tr valign="top">
		<td width="50%">
			<fieldset class="adminform">
				<table class="admintable">
					<tr>
						<td class="config_param"
						    colspan="2"><?php echo JText::_('COM_REDSHOP_ACCESSORY_PRODUCT_SETTINGS'); ?></td>
					</tr>
					<tr>
						<td width="100" align="right" class="key">
						<span class="editlinktip hasTip"
						      title="<?php echo JText::_('COM_REDSHOP_TOOLTIP_ACCESSORY_PRODUCT_IN_LIGHTBOX_LBL'); ?>::<?php echo JText::_('COM_REDSHOP_ACCESSORY_PRODUCT_IN_LIGHTBOX_TOOLTIP'); ?>">
						<label for="name">
							<?php
							echo JText::_('COM_REDSHOP_ACCESSORY_PRODUCT_IN_LIGHTBOX_LBL');
							?>
						</label></span></td>
						<td><?php
							echo $this->lists ['accessory_product_in_lightbox'];
							?>
						</td>
					</tr>
					<tr>
						<td width="100" align="right" class="key">
					<span class="editlinktip hasTip"
					      title="<?php echo JText::_('COM_REDSHOP_ACCESSORY_PRODUCT_ORDERING_METHOD_LBL'); ?>::<?php echo JText::_('COM_REDSHOP_TOOLTIP_ACCESSORY_PRODUCT_ORDERING_METHOD_LBL'); ?>">
					<label
						for="name"><?php echo JText::_('COM_REDSHOP_ACCESSORY_PRODUCT_ORDERING_METHOD_LBL');?></label></span>
						</td>
						<td><?php echo $this->lists['default_accessory_ordering_method'];?>
						</td>
					</tr>
					<tr>
						<td width="100" align="right" class="key">
					<span class="editlinktip hasTip"
					      title="<?php echo JText::_('COM_REDSHOP_ACCESSORY_PRODUCT_DESC_MAX_CHARS_LBL'); ?>::<?php echo JText::_('COM_REDSHOP_TOOLTIP_ACCESSORY_PRODUCT_DESC_MAX_CHARS_LBL'); ?>">
					<label
						for="name"><?php echo JText::_('COM_REDSHOP_ACCESSORY_PRODUCT_DESC_MAX_CHARS_LBL'); ?> </label></span>
						</td>
						<td>
							<input type="text" name="accessory_product_desc_max_chars"
							       id="accessory_product_desc_max_chars"
							       value="<?php echo ACCESSORY_PRODUCT_DESC_MAX_CHARS; ?>">
						</td>
					</tr>
					<tr>
						<td width="100" align="right" class="key">
					<span class="editlinktip hasTip"
					      title="<?php echo JText::_('COM_REDSHOP_ACCESSORY_PRODUCT_DESC_END_SUFFIX_LBL'); ?>::<?php echo JText::_('COM_REDSHOP_TOOLTIP_ACCESSORY_PRODUCT_DESC_END_SUFFIX_LBL'); ?>">
					<label
						for="name"><?php echo JText::_('COM_REDSHOP_ACCESSORY_PRODUCT_DESC_END_SUFFIX_LBL'); ?></label></span>
						</td>
						<td>
							<input type="text" name="accessory_product_desc_end_suffix"
							       id="accessory_product_desc_end_suffix"
							       value="<?php echo ACCESSORY_PRODUCT_DESC_END_SUFFIX; ?>">
						</td>
					</tr>
					<tr>
						<td width="100" align="right" class="key">
					<span class="editlinktip hasTip"
					      title="<?php echo JText::_('COM_REDSHOP_TOOLTIP_ACCESSORY_PRODUCT_TITLE_MAX_CHARS_LBL'); ?>::<?php echo JText::_('COM_REDSHOP_TOOLTIP_ACCESSORY_PRODUCT_TITLE_MAX_CHARS'); ?>">
					<label
						for="name"><?php echo JText::_('COM_REDSHOP_ACCESSORY_PRODUCT_TITLE_MAX_CHARS_LBL');?></label></span>
						</td>
						<td>
							<input type="text" name="accessory_product_title_max_chars"
							       id="accessory_product_title_max_chars"
							       value="<?php echo ACCESSORY_PRODUCT_TITLE_MAX_CHARS; ?>">
						</td>
					</tr>
					<tr>
						<td width="100" align="right" class="key">
					<span class="editlinktip hasTip"
					      title="<?php echo JText::_('COM_REDSHOP_ACCESSORY_PRODUCT_TITLE_END_SUFFIX_LBL'); ?>::<?php echo JText::_('COM_REDSHOP_TOOLTIP_ACCESSORY_PRODUCT_TITLE_END_SUFFIX_LBL'); ?>">
					<label
						for="name"><?php echo JText::_('COM_REDSHOP_ACCESSORY_PRODUCT_TITLE_END_SUFFIX_LBL');?></label></span>
						</td>
						<td>
							<input type="text" name="accessory_product_title_end_suffix"
							       id="accessory_product_title_end_suffix"
							       value="<?php echo ACCESSORY_PRODUCT_TITLE_END_SUFFIX; ?>">
						</td>
					</tr>
				</table>
			</fieldset>
		</td>
		<td>
			<fieldset class="adminform">
				<table class="admintable">
					<tr>
						<td class="config_param"
						    colspan="2"><?php echo JText::_('COM_REDSHOP_ACCESSORY_PRODUCT_IMAGE_SETTINGS'); ?></td>
					</tr>

					<tr>
						<td width="100" align="right" class="key">
							<span class="editlinktip hasTip"
							      title="<?php echo JText::_('COM_REDSHOP_ACCESSORY_THUMB_WIDTH_LBL'); ?>::<?php echo JText::_('TOOLTIP_ACCESSORY_THUMB_WIDTH_LBL'); ?>">
							<label
								for="name"><?php echo JText::_('COM_REDSHOP_ACCESSORY_THUMB_WIDTH_HEIGHT'); ?></label></span>
						</td>
						<td>
							<input type="text" name="accessory_thumb_width" id="accessory_thumb_width"
							       value="<?php echo ACCESSORY_THUMB_WIDTH; ?>">
							<input type="text" name="accessory_thumb_height" id="accessory_thumb_height"
							       value="<?php echo ACCESSORY_THUMB_HEIGHT; ?>">
						</td>
					</tr>
					<tr>
						<td width="100" align="right" class="key">
							<span class="editlinktip hasTip"
							      title="<?php echo JText::_('COM_REDSHOP_ACCESSORY_THUMB_WIDTH_LBL_TWO'); ?>::<?php echo JText::_('COM_REDSHOP_TOOLTIP_ACCESSORY_THUMB_WIDTH_LBL_TWO'); ?>">
							<label
								for="name"><?php echo JText::_('COM_REDSHOP_ACCESSORY_THUMB_WIDTH_HEIGHT_TWO');?></label></span>
						</td>
						<td>
							<input type="text" name="accessory_thumb_width_2" id="accessory_thumb_width_2"
							       value="<?php echo ACCESSORY_THUMB_WIDTH_2; ?>">
							<input type="text" name="accessory_thumb_height_2" id="accessory_thumb_height_2"
							       value="<?php echo ACCESSORY_THUMB_HEIGHT_2; ?>">
						</td>
					</tr>
					<tr>
						<td width="100" align="right" class="key">
							<span class="editlinktip hasTip"
							      title="<?php echo JText::_('COM_REDSHOP_ACCESSORY_THUMB_WIDTH_LBL_THREE'); ?>::<?php echo JText::_('COM_REDSHOP_TOOLTIP_ACCESSORY_THUMB_WIDTH_LBL_THREE'); ?>">
							<label
								for="name"><?php echo JText::_('COM_REDSHOP_ACCESSORY_THUMB_WIDTH_HEIGHT_THREE');?></label></span>
						</td>
						<td>
							<input type="text" name="accessory_thumb_width_3" id="accessory_thumb_width_3"
							       value="<?php echo ACCESSORY_THUMB_WIDTH_3; ?>">
							<input type="text" name="accessory_thumb_height_3" id="accessory_thumb_height_3"
							       value="<?php echo ACCESSORY_THUMB_HEIGHT_3; ?>">
						</td>
					</tr>
				</table>
			</fieldset>
		</td>
	</tr>
</table>
