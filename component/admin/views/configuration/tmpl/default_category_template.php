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

<legend><?php echo JText::_('COM_REDSHOP_CATEGORY_TEMPLATE_TAB'); ?></legend>

<div class="form-group">
	<span class="editlinktip hasTip"
			      title="<?php echo JText::_('COM_REDSHOP_DEFAULT_CATEGORY_TEMPLATE_LBL'); ?>::<?php echo JText::_('COM_REDSHOP_TOOLTIP_DEFAULT_CATEGORY_TEMPLATE_FOR_VM_LBL'); ?>">
		<label
				for="categorytemplate"><?php echo JText::_('COM_REDSHOP_DEFAULT_CATEGORY_TEMPLATE_LBL');?></label>
	</span>
	<?php echo $this->lists ['category_template'];?>
</div>

<div class="form-group">
	<span class="editlinktip hasTip"
			      title="<?php echo JText::_('COM_REDSHOP_DEFAULT_CATEGORYLIST_TEMPLATE_LBL'); ?>::<?php echo JText::_('COM_REDSHOP_TOOLTIP_DEFAULT_CATEGORY_TEMPLATELIST_LBL'); ?>">
		<label
				for="categorytemplate"><?php echo JText::_('COM_REDSHOP_DEFAULT_CATEGORYLIST_TEMPLATE_LBL');?></label>
	</span>
	<?php echo $this->lists ['default_categorylist_template'];?>
</div>
