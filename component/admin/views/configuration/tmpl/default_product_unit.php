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

<table class="admintable" id="measurement">
	<tr>
		<td class="config_param"><?php echo JText::_('COM_REDSHOP_PRODUCT_UNIT'); ?></td>
	</tr>
	<tr>
		<td class="key">
		<span class="editlinktip hasTip"
		      title="<?php echo JText::_('COM_REDSHOP_TOOLTIP_DEFAULT_VOLUME_UNIT_LBL'); ?>::<?php echo JText::_('COM_REDSHOP_DEFAULT_VOLUME_UNIT_LBL'); ?>">
		<label for="name"><?php
			echo JText::_('COM_REDSHOP_DEFAULT_VOLUME_UNIT_LBL');
			?></label></span></td>
		<td><?php echo $this->lists ['default_volume_unit'];?>
		</td>
	</tr>
	<tr>
		<td class="key">
		<span class="editlinktip hasTip"
		      title="<?php echo JText::_('COM_REDSHOP_TOOLTIP_DEFAULT_WEIGHT_UNIT_LBL'); ?>::<?php echo JText::_('COM_REDSHOP_DEFAULT_WEIGHT_UNIT_LBL'); ?>">
		<label for="name"><?php
			echo JText::_('COM_REDSHOP_DEFAULT_WEIGHT_UNIT_LBL');
			?></label></span></td>
		<td>
			<?php
			echo $this->lists ['default_weight_unit'];?>
		</td>
	</tr>
	<tr>
		<td width="100" align="right" class="key">
			<span class="editlinktip hasTip"
			      title="<?php echo JText::_('COM_REDSHOP_TOOLTIP_UNIT_DECIMAL_LBL'); ?>::<?php echo JText::_('COM_REDSHOP_PRICE_DECIMAL_LBL'); ?>">
			<label for="name"><?php echo JText::_('COM_REDSHOP_UNIT_DECIMAL_LBL');?></label></span>
		</td>
		<td>
			<input type="text" name="unit_decimal" id="unit_decimal" value="<?php echo UNIT_DECIMAL; ?>">
		</td>
	</tr>
</table>
