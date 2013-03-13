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
<table class="admintable">
	<tr>
		<td class="config_param"><?php echo JText::_('COM_REDSHOP_MAIN_CATEGORY_SETTINGS'); ?></td>
	</tr>
	<tr>
		<td width="100" align="right" class="key">
			<span class="editlinktip hasTip"
			      title="<?php echo JText::_('COM_REDSHOP_DEFAULT_CATEGORY_ORDERING_METHOD_LBL'); ?>::<?php echo JText::_('COM_REDSHOP_TOOLTIP_DEFAULT_CATEGORY_ORDERING_METHOD_LBL'); ?>">
			<label for="name"><?php echo JText::_('COM_REDSHOP_DEFAULT_CATEGORY_ORDERING_METHOD_LBL');?></label></span>
		</td>
		<td><?php echo $this->lists ['default_category_ordering_method'];?></td>
	</tr>
	<tr>
		<td width="100" align="right" class="key">
			<span class="editlinktip hasTip"
			      title="<?php echo JText::_('COM_REDSHOP_MAXCATEGORY_LBL'); ?>::<?php echo JText::_('COM_REDSHOP_TOOLTIP_MAXCATEGORY_LBL'); ?>">
			<?php echo JText::_('COM_REDSHOP_MAXCATEGORY_LBL');?></span>
		</td>
		<td>
			<input type="text" name="maxcategory" id="maxcategory" value="<?php echo MAXCATEGORY; ?>">
		</td>
	</tr>
	<tr>
		<td align="right" class="key">
		<span class="editlinktip hasTip"
		      title="<?php echo JText::_('COM_REDSHOP_TOOLTIP_PRODUCT_EXPIRE_LBL'); ?>::<?php echo JText::_('COM_REDSHOP_TOOLTIP_PRODUCT_EXPIRE'); ?>">
		<?php echo JText::_('COM_REDSHOP_PRODUCT_EXPIRE_LBL');?>:</span>
		</td>
		<td>
			<textarea class="text_area" type="text" name="product_expire_text" id="product_expire_text" rows="4"
			          cols="40"/><?php echo stripslashes(PRODUCT_EXPIRE_TEXT); ?></textarea>
		</td>
	</tr>
	<tr>
		<td align="right" class="key">
		<span class="editlinktip hasTip"
		      title="<?php echo JText::_('COM_REDSHOP_FRONTPAGE_CATEGORY_PAGE_INTROTEXT'); ?>::<?php echo JText::_('COM_REDSHOP_TOOLTIP_FRONTPAGE_CATEGORY_PAGE_INTROTEXT'); ?>">
		<?php echo JText::_('COM_REDSHOP_FRONTPAGE_CATEGORY_PAGE_INTROTEXT');?>:</span>
		</td>
		<td>
			<textarea class="text_area" type="text" name="category_frontpage_introtext"
			          id="category_frontpage_introtext" rows="4"
			          cols="40"/><?php echo stripslashes(CATEGORY_FRONTPAGE_INTROTEXT); ?></textarea>
		</td>
	</tr>
</table>
