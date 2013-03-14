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
<div id="config-document">
	<table width="100%" cellpadding="0" cellspacing="0">
		<tr valign="top">
			<td width="50%">
				<fieldset class="adminform">
					<table class="admintable">
						<tr>
							<td class="config_param"><?php echo JText::_('COM_REDSHOP_COMPARISON_SETTINGS'); ?></td>
						</tr>
						<tr>
							<td width="100" align="right" class="key">
						<span class="editlinktip hasTip"
						      title="<?php echo JText::_('COM_REDSHOP_PRODUCT_COMPARE_LIMIT_LBL'); ?>::<?php echo JText::_('COM_REDSHOP_TOOLTIP_PRODUCT_COMPARE_LIMIT_LBL'); ?>">
						<label for="name"><?php echo JText::_('COM_REDSHOP_PRODUCT_COMPARE_LIMIT_LBL');?></label></span>
							</td>
							<td>
								<input type="text" name="product_compare_limit" id="product_compare_limit"
								       value="<?php echo PRODUCT_COMPARE_LIMIT; ?>">
							</td>
						</tr>
						<tr>
							<td width="100" align="right" class="key">
						<span class="editlinktip hasTip"
						      title="<?php echo JText::_('COM_REDSHOP_PRODUCT_COMPARISON_TYPE_LBL'); ?>::<?php echo JText::_('COM_REDSHOP_TOOLTIP_PRODUCT_COMPARISON_TYPE_LBL'); ?>">
						<label
							for="name"><?php echo JText::_('COM_REDSHOP_PRODUCT_COMPARISON_TYPE_LBL');?></label></span>
							</td>
							<td><?php echo $this->lists ['product_comparison_type'];?></td>
						</tr>
					</table>
				</fieldset>
			</td>
			<td width="50%">
				<fieldset class="adminform">
					<table class="admintable">
						<tr>
							<td class="config_param"><?php echo JText::_('COM_REDSHOP_COMPARISON_LAYOUT'); ?></td>
						</tr>
						<tr>
							<td width="100" align="right" class="key">
						<span class="editlinktip hasTip"
						      title="<?php echo JText::_('COM_REDSHOP_COMPARE_PRODUCT_TEMPLATE_LBL'); ?>::<?php echo JText::_('TOOLTIP_COMPARE_PRODUCT_TEMPLATE'); ?>">
						<label
							for="name"><?php echo JText::_('COM_REDSHOP_COMPARE_PRODUCT_TEMPLATE_LBL');?></label></span>
							</td>
							<td><?php echo $this->lists ['compare_template_id'];?></td>
						</tr>
						<tr>
							<td width="100" align="right" class="key">
					<span class="editlinktip hasTip"
					      title="<?php echo JText::_('COM_REDSHOP_TOOLTIP_COMPARE_PRODUCT_THUMB_WIDTH_LBL'); ?>::<?php echo JText::_('COM_REDSHOP_TOOLTIP_COMPARE_PRODUCT_THUMB_WIDTH'); ?>">
					<label for="name"><?php echo JText::_('COM_REDSHOP_COMPARE_PRODUCT_THUMB_WIDTH_HEIGHT'); ?></label></span>
							</td>
							<td>
								<input type="text" name="compare_product_thumb_width" id="compare_product_thumb_width"
								       value="<?php echo COMPARE_PRODUCT_THUMB_WIDTH; ?>">
								<input type="text" name="compare_product_thumb_height" id="compare_product_thumb_height"
								       value="<?php echo COMPARE_PRODUCT_THUMB_HEIGHT; ?>">
							</td>
					</table>
				</fieldset>
			</td>
		</tr>
	</table>
</div>
