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
<tr><td class="config_param"><?php echo JText::_( 'COM_REDSHOP_WRAPPING_MANAGEMENT' ); ?></td></tr>
	<tr>
		<td width="100" align="right" class="key">
		<span class="editlinktip hasTip" title="<?php echo JText::_( 'COM_REDSHOP_TOOLTIP_DEFAULT_WRAPPER_THUMB_WIDTH_LBL' ); ?>::<?php echo JText::_( 'COM_REDSHOP_TOOLTIP_DEFAULT_WRAPPER_THUMB_WIDTH' ); ?>">
		<?php echo JText::_ ( 'COM_REDSHOP_DEFAULT_WRAPPER_THUMB_WIDTH_HEIGHT' );?></span></td>
		<td>
		<input type="text" name="default_wrapper_thumb_width" value="<?php echo DEFAULT_WRAPPER_THUMB_WIDTH; ?>" />
		<input type="text" name="default_wrapper_thumb_height" value="<?php echo DEFAULT_WRAPPER_THUMB_HEIGHT;?>" /></td>
	</tr>
	<tr>
		<td width="100" align="right" class="key">
	 <span class="editlinktip hasTip" title="<?php echo JText::_( 'COM_REDSHOP_TOOLTIP_AUTO_SCROLL_FOR_WRAPPER_LBL' ); ?>::<?php echo JText::_( 'COM_REDSHOP_TOOLTIP_AUTO_SCROLL_FOR_WRAPPER' ); ?>">
		<?php
		echo JText::_ ( 'COM_REDSHOP_AUTO_SCROLL_FOR_WRAPPER_LBL' );
		?></td>
		<td><?php
		echo $this->lists ['auto_scroll_wrapper'];
		?>
			</td>
	</tr>
</table>
