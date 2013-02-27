<?php
/**
 * @copyright Copyright (C) 2010 redCOMPONENT.com. All rights reserved.
 * @license GNU/GPL, see license.txt or http://www.gnu.org/copyleft/gpl.html
 * Developed by email@recomponent.com - redCOMPONENT.com
 *
 * redSHOP can be downloaded from www.redcomponent.com
 * redSHOP is free software; you can redistribute it and/or
 * modify it under the terms of the GNU General Public License 2
 * as published by the Free Software Foundation.
 *
 * You should have received a copy of the GNU General Public License
 * along with redSHOP; if not, write to the Free Software
 * Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston, MA  02110-1301, USA.
 */
defined ( '_JEXEC' ) or die ( 'Restricted access' );

?>
<table class="admintable">
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
			<span class="editlinktip hasTip" title="<?php echo JText::_('COM_REDSHOP_TOOLTIP_CURRENCY_NAME' ); ?>::<?php echo JText::_('COM_REDSHOP_CURRENCY_NAME' ); ?>">
			<label for="name"><?php	echo JText::_('COM_REDSHOP_CURRENCY_NAME' );?></label>
		</td>
		<td>
			<?php echo $this->lists ['currency_data'];?>
		</td>
	</tr>
	<tr>
		<td width="100" align="right" class="key">
			<span class="editlinktip hasTip" title="<?php echo JText::_('COM_REDSHOP_TOOLTIP_CURRENCY_SYMBOL' ); ?>::<?php echo JText::_('COM_REDSHOP_CURRENCY_SYMBOL' ); ?>">
			<label for="name"><?php	echo JText::_('COM_REDSHOP_CURRENCY_SYMBOL' );?></label>
		</td>
		<td>
			<input type="text" name="currency_symbol" id="currency_symbol" value="<?php	echo $this->temparray['currency_symbol'];?>">
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
			<span class="editlinktip hasTip" title="<?php echo JText::_('COM_REDSHOP_TOOLTIP_PRICE_SEPERATOR_LBL' ); ?>::<?php echo JText::_('COM_REDSHOP_PRICE_SEPERATOR_LBL' ); ?>">
			<label for="name"><?php	echo JText::_('COM_REDSHOP_PRICE_SEPERATOR_LBL' );?></label>
		</td>
		<td>
			<input type="text" name="price_seperator" id="price_seperator" value="<?php echo $this->temparray['price_seperator'];?>">
		</td>
	</tr>

	<tr>
		<td width="100" align="right" class="key">
			<span class="editlinktip hasTip" title="<?php echo JText::_('COM_REDSHOP_TOOLTIP_THOUSAND_SEPERATOR_LBL' ); ?>::<?php echo JText::_('COM_REDSHOP_THOUSAND_SEPERATOR_LBL' ); ?>">
			<label for="name"><?php echo JText::_('COM_REDSHOP_THOUSAND_SEPERATOR_LBL' );?></label>
		</td>
		<td>
			<input type="text" name="thousand_seperator" id="thousand_seperator" value="<?php echo $this->temparray['thousand_seperator'];?>">
		</td>
	</tr>
	<tr>
		<td width="100" align="right" class="key">
			<span class="editlinktip hasTip" title="<?php echo JText::_('COM_REDSHOP_TOOLTIP_PRICE_DECIMAL_LBL' ); ?>::<?php echo JText::_('COM_REDSHOP_PRICE_DECIMAL_LBL' ); ?>">
			<label for="name"><?php echo JText::_('COM_REDSHOP_PRICE_DECIMAL_LBL' );?></label>
		</td>
		<td>
			<input type="text" name="price_decimal" id="price_decimal" value="<?php echo $this->temparray['price_decimal'];?>">
		</td>
	</tr>
</table>