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
<div id="config-document">
<table width="100%" cellpadding="0" cellspacing="0">
<tr valign="top">
	<td width="50%">
		<fieldset class="adminform">
			<table class="admintable">
			<tr><td class="config_param"><?php echo JText::_( 'COM_REDSHOP_STOCKROOM_SETTINGS' ); ?></td></tr>
			<tr>
				<td width="100" align="right" class="key">
					<span class="editlinktip hasTip" title="<?php echo JText::_( 'COM_REDSHOP_USE_BLANK_AS_INFINITE_LBL' ); ?>::<?php echo JText::_( 'COM_REDSHOP_TOOLTIP_USE_BLANK_AS_INFINITE_LBL' ); ?>">
					<label for="container"><?php echo JText::_ ( 'COM_REDSHOP_USE_BLANK_AS_INFINITE_LBL' );?></label></span>
				</td>
				<td><?php echo $this->lists ['use_blank_as_infinite'];?></td>
			</tr>
			<tr>
				<td width="100" align="right" class="key">
				<span class="editlinktip hasTip" title="<?php echo JText::_( 'COM_REDSHOP_TOOLTIP_DEDAULT_STOCKROOM_LBL' ); ?>::<?php echo JText::_( 'COM_REDSHOP_TOOLTIP_DEDAULT_STOCKROOM' ); ?>">
				<label for="default_stockroom"><?php echo JText::_ ( 'COM_REDSHOP_DEDAULT_STOCKROOM_LBL' );?></label></span>
				</td>
				<td><?php echo $this->lists ['default_stockroom'];?></td>
			</tr>
			<tr>
				<td width="100" align="right" class="key">
				<span class="editlinktip hasTip" title="<?php echo JText::_( 'COM_REDSHOP_DEFAULT_STOCKAMOUNT_IMAGE_THUMB_WIDTH_HEIGHT_LBL' ); ?>::<?php echo JText::_( 'COM_REDSHOP_TOOLTIP_DEFAULT_STOCKAMOUNT_IMAGE_THUMB_WIDTH_HEIGHT_LBL' ); ?>">
				<label for="default_stockroom"><?php echo JText::_ ( 'COM_REDSHOP_DEFAULT_STOCKAMOUNT_IMAGE_THUMB_WIDTH_HEIGHT_LBL' );?></label></span>
				</td>
				<td><input type="text" name="default_stockamount_thumb_width" value="<?php echo DEFAULT_STOCKAMOUNT_THUMB_WIDTH; ?>" />
				<input type="text" name="default_stockamount_thumb_height" value="<?php echo DEFAULT_STOCKAMOUNT_THUMB_HEIGHT;?>" /></td>
			</tr>
			</table>
		</fieldset>
	</td>
	<td width="50%">
	</td>
</tr>
</table>
</div>
