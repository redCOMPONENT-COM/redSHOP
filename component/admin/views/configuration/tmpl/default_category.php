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
<legend><?php echo JText::_('COM_REDSHOP_MAIN_CATEGORY_SETTINGS'); ?></legend>

<div class="form-group">
	<span class="editlinktip hasTip"
			      title="<?php echo JText::_('COM_REDSHOP_DEFAULT_CATEGORY_ORDERING_METHOD_LBL'); ?>::<?php echo JText::_('COM_REDSHOP_TOOLTIP_DEFAULT_CATEGORY_ORDERING_METHOD_LBL'); ?>">
		<label for="name"><?php echo JText::_('COM_REDSHOP_DEFAULT_CATEGORY_ORDERING_METHOD_LBL');?></label></span>
	<?php echo $this->lists ['default_category_ordering_method'];?>
</div>

<div class="form-group">
	<span class="editlinktip hasTip"
			      title="<?php echo JText::_('COM_REDSHOP_MAXCATEGORY_LBL'); ?>::<?php echo JText::_('COM_REDSHOP_TOOLTIP_MAXCATEGORY_LBL'); ?>">
		<label><?php echo JText::_('COM_REDSHOP_MAXCATEGORY_LBL');?></label></span>
	<input type="text" name="maxcategory" id="maxcategory" value="<?php echo $this->config->get('MAXCATEGORY'); ?>">
</div>

<div class="form-group">
	<span class="editlinktip hasTip"
		      title="<?php echo JText::_('COM_REDSHOP_TOOLTIP_PRODUCT_EXPIRE_LBL'); ?>::<?php echo JText::_('COM_REDSHOP_TOOLTIP_PRODUCT_EXPIRE'); ?>">
		<label><?php echo JText::_('COM_REDSHOP_PRODUCT_EXPIRE_LBL');?>:</label></span>
	<textarea class="form-control" type="text" name="product_expire_text" id="product_expire_text" rows="4"
			          cols="40"/><?php echo stripslashes($this->config->get('PRODUCT_EXPIRE_TEXT')); ?></textarea>
</div>

<div class="form-group">
	<span class="editlinktip hasTip"
		      title="<?php echo JText::_('COM_REDSHOP_FRONTPAGE_CATEGORY_PAGE_INTROTEXT'); ?>::<?php echo JText::_('COM_REDSHOP_TOOLTIP_FRONTPAGE_CATEGORY_PAGE_INTROTEXT'); ?>">
		<label><?php echo JText::_('COM_REDSHOP_FRONTPAGE_CATEGORY_PAGE_INTROTEXT');?>:</label></span>
	<textarea class="form-control" type="text" name="category_frontpage_introtext"
			          id="category_frontpage_introtext" rows="4"
			          cols="40"/><?php echo stripslashes($this->config->get('CATEGORY_FRONTPAGE_INTROTEXT')); ?></textarea>
</div>
