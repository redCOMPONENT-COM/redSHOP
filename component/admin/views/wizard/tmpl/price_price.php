<?php
/**
 * @package     RedSHOP.Backend
 * @subpackage  Template
 *
 * @copyright   Copyright (C) 2008 - 2017 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */
defined('_JEXEC') or die;

?>
<table class="admintable table">
	<tr>
		<td colspan="2" class="price_intro_text">&nbsp;</td>
	</tr>
	<tr>
		<td colspan="2" class="price_intro_text">
			<?php echo JText::_('COM_REDSHOP_CURRENCY_INTRO_TEXT'); ?>
		</td>
	</tr>
	<tr>
		<td width="100" align="right" class="key">
			<span class="editlinktip hasTip"
			      title="<?php echo JText::_('COM_REDSHOP_TOOLTIP_CURRENCY_NAME'); ?>::<?php echo JText::_('COM_REDSHOP_CURRENCY_NAME'); ?>">
			<label for="name"><?php    echo JText::_('COM_REDSHOP_CURRENCY_NAME');?></label>
		</td>
		<td>
			<?php echo $this->lists ['currency_data'];?>
		</td>
	</tr>
	<tr>
		<td width="100" align="right" class="key">
			<span class="editlinktip hasTip"
			      title="<?php echo JText::_('COM_REDSHOP_TOOLTIP_CURRENCY_SYMBOL'); ?>::<?php echo JText::_('COM_REDSHOP_CURRENCY_SYMBOL'); ?>">
			<label for="name"><?php    echo JText::_('COM_REDSHOP_CURRENCY_SYMBOL');?></label>
		</td>
		<td>
			<input type="text" name="redcurrency_symbol" id="currency_symbol"
			       value="<?php echo $this->temparray['REDCURRENCY_SYMBOL']; ?>">
		</td>
	</tr>
	<tr>
		<td colspan="2" class="price_intro_text">&nbsp;</td>
	</tr>
	<tr>
		<td colspan="2" class="price_intro_text">
			<?php echo JText::_('COM_REDSHOP_PRICE_SEPERATOR_INTRO_TEXT'); ?>
		</td>
	</tr>
	<tr>
		<td width="100" align="right" class="key">
			<span class="editlinktip hasTip"
			      title="<?php echo JText::_('COM_REDSHOP_TOOLTIP_PRICE_SEPERATOR_LBL'); ?>::<?php echo JText::_('COM_REDSHOP_PRICE_SEPERATOR_LBL'); ?>">
			<label for="name"><?php    echo JText::_('COM_REDSHOP_PRICE_SEPERATOR_LBL');?></label>
		</td>
		<td>
			<input type="text" name="price_seperator" id="price_seperator"
			       value="<?php echo $this->temparray['PRICE_SEPERATOR']; ?>">
		</td>
	</tr>

	<tr>
		<td width="100" align="right" class="key">
			<span class="editlinktip hasTip"
			      title="<?php echo JText::_('COM_REDSHOP_TOOLTIP_THOUSAND_SEPERATOR_LBL'); ?>::<?php echo JText::_('COM_REDSHOP_THOUSAND_SEPERATOR_LBL'); ?>">
			<label for="name"><?php echo JText::_('COM_REDSHOP_THOUSAND_SEPERATOR_LBL');?></label>
		</td>
		<td>
			<input type="text" name="thousand_seperator" id="thousand_seperator"
			       value="<?php echo $this->temparray['THOUSAND_SEPERATOR']; ?>">
		</td>
	</tr>
	<tr>
		<td width="100" align="right" class="key">
			<span class="editlinktip hasTip"
			      title="<?php echo JText::_('COM_REDSHOP_TOOLTIP_PRICE_DECIMAL_LBL'); ?>::<?php echo JText::_('COM_REDSHOP_PRICE_DECIMAL_LBL'); ?>">
			<label for="name"><?php echo JText::_('COM_REDSHOP_PRICE_DECIMAL_LBL');?></label>
		</td>
		<td>
			<input type="text" name="price_decimal" id="price_decimal"
			       value="<?php echo $this->temparray['PRICE_DECIMAL']; ?>">
		</td>
	</tr>
</table>
