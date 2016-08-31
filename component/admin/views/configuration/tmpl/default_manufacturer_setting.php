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
<legend><?php echo JText::_('COM_REDSHOP_MANUFACTURER_SETTINGS'); ?></legend>

<div class="form-group">
	<span class="editlinktip hasTip"
		      title="<?php echo JText::_('COM_REDSHOP_DEFAULT_MANUFACTURER_ORDERING_METHOD_LBL'); ?>::<?php echo JText::_('COM_REDSHOP_TOOLTIP_DEFAULT_MANUFACTURER_ORDERING_METHOD_LBL'); ?>">
		<label for="name"><?php echo JText::_('COM_REDSHOP_DEFAULT_MANUFACTURER_ORDERING_METHOD_LBL');?></label>
	</span>
	<?php echo $this->lists ['default_manufacturer_ordering_method'];?>
</div>

<div class="form-group">
	<span class="editlinktip hasTip"
		      title="<?php echo JText::_('COM_REDSHOP_DEFAULT_MANUFACTURER_PRODUCT_ORDERING_METHOD_LBL'); ?>::<?php echo JText::_('COM_REDSHOP_TOOLTIP_DEFAULT_MANUFACTURER_PRODUCT_ORDERING_METHOD_LBL'); ?>">
		<label for="name"><?php echo JText::_('COM_REDSHOP_DEFAULT_MANUFACTURER_PRODUCT_ORDERING_METHOD_LBL');?></label>
	</span>
	<?php echo $this->lists ['default_manufacturer_product_ordering_method'];?>
</div>

<div class="form-group">
	<span class="editlinktip hasTip"
		      title="<?php echo JText::_('COM_REDSHOP_TOOLTIP_MANUFACTURER_TITLE_MAX_CHARS'); ?>::<?php echo JText::_('COM_REDSHOP_TOOLTIP_MANUFACTURER_TITLE_MAX_CHARS_LBL'); ?>">
		<label for="name"><?php echo JText::_('COM_REDSHOP_MANUFACTURER_MAX_CHARS_LBL');?></label>
	</span>
	<input type="text" name="manufacturer_title_max_chars" id="manufacturer_title_max_chars"
			       value="<?php echo $this->config->get('MANUFACTURER_TITLE_MAX_CHARS'); ?>">
</div>

<div class="form-group">
	<span class="editlinktip hasTip"
		      title="<?php echo JText::_('COM_REDSHOP_TOOLTIP_MANUFACTURER_TITLE_END_SUFFIX'); ?>::<?php echo JText::_('COM_REDSHOP_TOOLTIP_MANUFACTURER_TITLE_END_SUFFIX_LBL'); ?>">
		<label for="name"><?php echo JText::_('COM_REDSHOP_MANUFACTURER_TITLE_END_SUFFIX_LBL');?></label>
	</span>
	<input type="text" name="manufacturer_title_end_suffix" id="manufacturer_title_end_suffix"
			       value="<?php echo $this->config->get('MANUFACTURER_TITLE_END_SUFFIX'); ?>">
</div>

<div class="form-group">
	<span class="editlinktip hasTip"
			      title="<?php echo JText::_('COM_REDSHOP_ENABLE_MANUFACTURER_EMAIL_LBL'); ?>::<?php echo JText::_('COM_REDSHOP_TOOLTIP_ENABLE_MANUFACTURER_EMAIL_LBL'); ?>">
		<label
			for="allow_pre_order"><?php echo JText::_('COM_REDSHOP_ENABLE_MANUFACTURER_EMAIL_LBL');?></label></span>
	<?php echo $this->lists ['manufacturer_mail_enable'];?>
</div>

<div class="form-group">
	<span class="editlinktip hasTip"
			      title="<?php echo JText::_('COM_REDSHOP_ENABLE_SUPPLIER_EMAIL_LBL'); ?>::<?php echo JText::_('COM_REDSHOP_TOOLTIP_ENABLE_SUPPLIER_EMAIL_LBL'); ?>">
		<label for="allow_pre_order"><?php echo JText::_('COM_REDSHOP_ENABLE_SUPPLIER_EMAIL_LBL');?></label></span>
	<?php echo $this->lists ['supplier_mail_enable'];?>
</div>

<legend><?php echo JText::_('COM_REDSHOP_REDMANUFACTURER_TEMPLATE'); ?></legend>

<div class="form-group">
	<span class="editlinktip hasTip"
		      title="<?php echo JText::_('COM_REDSHOP_DEFAULT_MANUFACTURER_TEMPLATE_LBL'); ?>::<?php echo JText::_('COM_REDSHOP_TOOLTIP_DEFAULT_MANUFACTURER_TEMPLATE_FOR_VM_LBL'); ?>">
		<label
			for="manufacturertemplate"><?php echo JText::_('COM_REDSHOP_DEFAULT_MANUFACTURER_TEMPLATE_LBL');?></label>
	</span>
	<?php echo $this->lists ['manufacturer_template'];?>
</div>
