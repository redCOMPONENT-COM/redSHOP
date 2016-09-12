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

<legend><?php echo JText::_('COM_REDSHOP_PRODUCT_UNIT'); ?></legend>

<div class="form-group">
	<span class="editlinktip hasTip"
		      title="<?php echo JText::_('COM_REDSHOP_TOOLTIP_DEFAULT_VOLUME_UNIT_LBL'); ?>::<?php echo JText::_('COM_REDSHOP_DEFAULT_VOLUME_UNIT_LBL'); ?>">
		<label for="name"><?php echo JText::_('COM_REDSHOP_DEFAULT_VOLUME_UNIT_LBL'); ?></label>
	</span>
	<?php echo $this->lists ['default_volume_unit'];?>
</div>

<div class="form-group">
	<span class="editlinktip hasTip"
		      title="<?php echo JText::_('COM_REDSHOP_TOOLTIP_DEFAULT_WEIGHT_UNIT_LBL'); ?>::<?php echo JText::_('COM_REDSHOP_DEFAULT_WEIGHT_UNIT_LBL'); ?>">
		<label for="name"><?php echo JText::_('COM_REDSHOP_DEFAULT_WEIGHT_UNIT_LBL'); ?></label>
	</span>
	<?php echo $this->lists ['default_weight_unit'];?>
</div>

<div class="form-group">
	<span class="editlinktip hasTip"
			      title="<?php echo JText::_('COM_REDSHOP_TOOLTIP_UNIT_DECIMAL_LBL'); ?>::<?php echo JText::_('COM_REDSHOP_PRICE_DECIMAL_LBL'); ?>">
		<label for="name"><?php echo JText::_('COM_REDSHOP_UNIT_DECIMAL_LBL');?></label>
	</span>
	<input type="text" name="unit_decimal" id="unit_decimal" value="<?php echo $this->config->get('UNIT_DECIMAL'); ?>">
</div>
