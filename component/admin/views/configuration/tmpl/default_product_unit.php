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

<table class="admintable" id="measurement">
<tr><td class="config_param"><?php echo JText::_( 'PRODUCT_UNIT' ); ?></td></tr>
	<tr>
		<td class="key">
		<span class="editlinktip hasTip" title="<?php echo JText::_( 'TOOLTIP_DEFAULT_VOLUME_UNIT_LBL' ); ?>::<?php echo JText::_( 'DEFAULT_VOLUME_UNIT_LBL' ); ?>">
		<label for="name"><?php
		echo JText::_ ( 'DEFAULT_VOLUME_UNIT_LBL' );
		?></label></span></td>
		<td><?php echo $this->lists ['default_volume_unit'];?>
		</td>
	</tr>
	<tr>
		<td class="key">
		<span class="editlinktip hasTip" title="<?php echo JText::_( 'TOOLTIP_DEFAULT_WEIGHT_UNIT_LBL' ); ?>::<?php echo JText::_( 'DEFAULT_WEIGHT_UNIT_LBL' ); ?>">
		<label for="name"><?php
		echo JText::_ ( 'DEFAULT_WEIGHT_UNIT_LBL' );
		?></label></span></td>
		<td>
		<?php
		echo $this->lists ['default_weight_unit'];?>
		</td>
	</tr>
	<tr>
		<td width="100" align="right" class="key">
			<span class="editlinktip hasTip" title="<?php echo JText::_( 'TOOLTIP_UNIT_DECIMAL_LBL' ); ?>::<?php echo JText::_( 'PRICE_DECIMAL_LBL' ); ?>">
			<label for="name"><?php echo JText::_ ( 'UNIT_DECIMAL_LBL' );?></label></span>
		</td>
		<td>
			<input type="text" name="unit_decimal" id="unit_decimal" value="<?php echo UNIT_DECIMAL;?>">
		</td>
	</tr>
</table>
