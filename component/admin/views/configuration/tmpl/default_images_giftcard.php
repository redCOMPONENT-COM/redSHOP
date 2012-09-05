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
<table class="admintable" width="100%">
<tr><td class="config_param"><?php echo JText::_( 'COM_REDSHOP_GIFTCARD_IMAGE_SETTING_TAB' ); ?></td></tr>
	<tr>
		<td width="100" align="right" class="key">
		<span class="editlinktip hasTip" title="<?php echo JText::_( 'COM_REDSHOP_GIFTCARD_THUMB_WIDTH_LBL' ); ?>::<?php echo JText::_( 'COM_REDSHOP_TOOLTIP_GIFTCARD_THUMB_WIDTH_LBL' ); ?>">
		<label for="name">
		<?php echo JText::_ ( 'COM_REDSHOP_GIFTCARD_THUMB_WIDTH_HEIGHT' );?></label></span></td>
		<td>
		<input type="text" name="giftcard_thumb_width" id="giftcard_thumb_width" value="<?php echo GIFTCARD_THUMB_WIDTH;?>">
		<input type="text" name="giftcard_thumb_height" id="giftcard_thumb_height" value="<?php echo GIFTCARD_THUMB_HEIGHT;?>">
		</td>
	</tr>
	<tr>
		<td width="100" align="right" class="key">
		<span class="editlinktip hasTip" title="<?php echo JText::_( 'COM_REDSHOP_GIFTCARD_LIST_THUMB_WIDTH_LBL' ); ?>::<?php echo JText::_( 'COM_REDSHOP_TOOLTIP_GIFTCARD_LIST_THUMB_WIDTH_LBL' ); ?>">
			<label for="name"><?php echo JText::_ ( 'COM_REDSHOP_GIFTCARD_LIST_THUMB_WIDTH_HEIGHT' );?></label></span></td>
			<td>
			<input type="text" name="giftcard_list_thumb_width" id="giftcard_list_thumb_width" value="<?php echo GIFTCARD_LIST_THUMB_WIDTH;?>">
			<input type="text" name="giftcard_list_thumb_height" id="giftcard_list_thumb_height" value="<?php echo GIFTCARD_LIST_THUMB_HEIGHT;?>">
			</td>
	</tr>
	<tr><td colspan="2"><hr /></td></tr>
	<tr>
		<td width="100" align="right" class="key">
		<span class="editlinktip hasTip" title="<?php echo JText::_( 'COM_REDSHOP_WATERMARK_GIFTCARD_IMAGE' ); ?>::<?php echo JText::_( 'COM_REDSHOP_TOOLTIP_WATERMARK_GIFTCARD_IMAGE_LBL' ); ?>">
		<label for="name"><?php echo JText::_ ( 'COM_REDSHOP_WATERMARK_GIFTCARD_IMAGE_LBL' );?></label></span>
		</td>
		<td><?php echo $this->lists ['watermark_giftcart_image'];?></td>
	</tr>
	<tr>
		<td width="100" align="right" class="key">
		<span class="editlinktip hasTip" title="<?php echo JText::_( 'COM_REDSHOP_WATERMARK_GIFTCARD_THUMB_IMAGE' ); ?>::<?php echo JText::_( 'COM_REDSHOP_TOOLTIP_WATERMARK_GIFTCARD_THUMB_IMAGE_LBL' ); ?>">
		<label for="name">
		<?php echo JText::_ ( 'COM_REDSHOP_WATERMARK_GIFTCARD_THUMB_IMAGE_LBL' );?>
		</label></span></td>
		<td><?php echo $this->lists ['watermark_giftcart_thumb_image'];?>
		</td>
	</tr>
</table>
