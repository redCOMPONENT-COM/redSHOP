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
<table class="adminlist" width="100%" cellpadding="0" cellspacing="0">
<tr valign="top">
<td width="50%">
		<fieldset class="adminform">
					<table class="admintable">
					<tr><td class="config_param"><?php echo JText::_( 'RELATED_PRODUCT_SETTINGS' ); ?></td></tr>
						<tr>
							<td width="100" align="right" class="key">
							<span class="editlinktip hasTip" title="<?php echo JText::_( 'TWOWAY_RELATED_PRODUCT_LBL' ); ?>::<?php echo JText::_( 'TOOLTIP_TWOWAY_RELATED_PRODUCT_LBL' ); ?>">
							<label for="twoway_related_product"><?php echo JText::_ ( 'TWOWAY_RELATED_PRODUCT_LBL' );?></label></span>
							</td>
							<td><?php echo $this->lists ['twoway_related_product'];?></td>
						</tr>

						<tr>
							<td width="100" align="right" class="key">
								<span class="editlinktip hasTip" title="<?php echo JText::_( 'CHILDPRODUCT_DROPDOWN_LBL' ); ?>::<?php echo JText::_( 'TOOLTIP_CHILDPRODUCT_DROPDOWN_LBL' ); ?>">
								<label for="childproductdropdown"><?php echo JText::_ ( 'CHILDPRODUCT_DROPDOWN_LBL' );?></label></span>
							</td>
							<td><?php echo $this->lists ['childproduct_dropdown'];?></td>
						</tr>

						<tr>
							<td width="100" align="right" class="key">
							<span class="editlinktip hasTip" title="<?php echo JText::_( 'PURCHASE_PARENT_WITH_CHILD_LBL' ); ?>::<?php echo JText::_( 'TOOLTIP_PURCHASE_PARENT_WITH_CHILD_LBL' ); ?>">
							<label for="parentchildtoggle"><?php echo JText::_ ( 'PURCHASE_PARENT_WITH_CHILD_LBL' );?></label></span>
							</td>
							<td><?php echo $this->lists ['purchase_parent_with_child'];?></td>
						</tr>
						<tr>
							<td width="100" align="right" class="key">
							<span class="editlinktip hasTip" title="<?php echo JText::_( 'TOOLTIP_RELATED_PRODUCT_ORDERING_METHOD_LBL' ); ?>::<?php echo JText::_( 'RELATED_PRODUCT_ORDERING_METHOD_LBL' ); ?>">
							<label for="name"><?php echo JText::_ ( 'RELATED_PRODUCT_ORDERING_METHOD_LBL' );?></label></span>
							</td>
							<td><?php echo $this->lists['default_related_ordering_method'];?>
							</td>
						</tr>
						<tr>
							<td width="100" align="right" class="key">
							<span class="editlinktip hasTip" title="<?php echo JText::_( 'RELATED_PRODUCT_DESC_MAX_CHARS_LBL' ); ?>::<?php echo JText::_( 'TOOLTIP_RELATED_PRODUCT_DESC_MAX_CHARS_LBL' ); ?>">
							<label for="name"><?php echo JText::_ ( 'RELATED_PRODUCT_DESC_MAX_CHARS_LBL' );?></label></span>
							</td>
							<td>
							<input type="text" name="related_product_desc_max_chars" id="related_product_desc_max_chars" value="<?php echo RELATED_PRODUCT_DESC_MAX_CHARS;?>">
							</td>
						</tr>
						<tr>
							<td width="100" align="right" class="key">
							<span class="editlinktip hasTip" title="<?php echo JText::_( 'RELATED_PRODUCT_DESC_END_SUFFIX_LBL' ); ?>::<?php echo JText::_( 'TOOLTIP_RELATED_PRODUCT_DESC_END_SUFFIX_LBL' ); ?>">
							<label for="name"><?php echo JText::_ ( 'RELATED_PRODUCT_DESC_END_SUFFIX_LBL' );?></label></span></td>
							<td>
							<input type="text" name="related_product_desc_end_suffix" id="related_product_desc_end_suffix" value="<?php echo RELATED_PRODUCT_DESC_END_SUFFIX;?>">
							</td>
						</tr>
						<tr>
							<td width="100" align="right" class="key">
							<span class="editlinktip hasTip" title="<?php echo JText::_( 'RELATED_PRODUCT_SHORT_DESC_MAX_CHARS_LBL' ); ?>::<?php echo JText::_( 'TOOLTIP_RELATED_PRODUCT_SHORT_DESC_MAX_CHARS_LBL' ); ?>">
							<label for="name"><?php echo JText::_ ( 'RELATED_PRODUCT_SHORT_DESC_MAX_CHARS_LBL' );?></label></span></td>
							<td>
							<input type="text" name="related_product_short_desc_max_chars" id="related_product_short_desc_max_chars" value="<?php echo RELATED_PRODUCT_SHORT_DESC_MAX_CHARS;?>">
							</td>
						</tr>
						<tr>
							<td width="100" align="right" class="key">
							<span class="editlinktip hasTip" title="<?php echo JText::_( 'RELATED_PRODUCT_SHORT_DESC_END_SUFFIX_LBL' ); ?>::<?php echo JText::_( 'TOOLTIP_RELATED_PRODUCT_SHORT_DESC_END_SUFFIX_LBL' ); ?>">
							<label for="name"><?php echo JText::_ ( 'RELATED_PRODUCT_SHORT_DESC_END_SUFFIX_LBL' );?></label></span></td>
							<td>
							<input type="text" name="related_product_short_desc_end_suffix" id="related_product_short_desc_end_suffix" value="<?php echo RELATED_PRODUCT_SHORT_DESC_END_SUFFIX;?>">
							</td>
						</tr>
						<tr>
							<td width="100" align="right" class="key">
							<span class="editlinktip hasTip" title="<?php echo JText::_( 'RELATED_PRODUCT_TITLE_MAX_CHARS_LBL' ); ?>::<?php echo JText::_( 'TOOLTIP_RELATED_PRODUCT_TITLE_MAX_CHARS_LBL' ); ?>">
							<label for="name"><?php echo JText::_ ( 'RELATED_PRODUCT_TITLE_MAX_CHARS_LBL' ); ?></label></span>
							</td>
							<td>
							<input type="text" name="related_product_title_max_chars" id="related_product_title_max_chars" value="<?php echo RELATED_PRODUCT_TITLE_MAX_CHARS; ?>">
							</td>
						</tr>
						<tr>
							<td width="100" align="right" class="key">
							<span class="editlinktip hasTip" title="<?php echo JText::_( 'RELATED_PRODUCT_TITLE_END_SUFFIX_LBL' ); ?>::<?php echo JText::_( 'TOOLTIP_RELATED_PRODUCT_TITLE_END_SUFFIX_LBL' ); ?>">
							<label for="name"><?php echo JText::_ ( 'RELATED_PRODUCT_TITLE_END_SUFFIX_LBL' );?></label></span>
							</td>
							<td>
							<input type="text" name="related_product_title_end_suffix" id="related_product_title_end_suffix" value="<?php echo RELATED_PRODUCT_TITLE_END_SUFFIX;?>">
							</td>
						</tr>
					</table>
</fieldset>
</td>
<td>
<fieldset class="adminform">
			<table class="admintable">
			<tr><td class="config_param"><?php echo JText::_( 'RELATED_PRODUCT_IMAGE_SETTINGS' ); ?></td></tr>
			<tr>
			<td width="100" align="right" class="key">
			<span class="editlinktip hasTip" title="<?php echo JText::_( 'TOOLTIP_RELATED_PRODUCT_THUMB_WIDTH' ); ?>::<?php echo JText::_( 'RELATED_PRODUCT_THUMB_WIDTH_LBL' ); ?>">
			<label for="name"><?php echo JText::_ ( 'RELATED_PRODUCT_THUMB_WIDTH_HEIGHT' ); ?></label></span></td>
			<td>
			<input type="text" name="related_product_thumb_width" id="related_product_thumb_width" value="<?php echo RELATED_PRODUCT_THUMB_WIDTH; ?>">
			<input type="text" name="related_product_thumb_height" id="related_product_thumb_height" value="<?php echo RELATED_PRODUCT_THUMB_HEIGHT;?>">
			</td>
			</tr>
			<tr>
			<td width="100" align="right" class="key">
			<span class="editlinktip hasTip" title="<?php echo JText::_( 'TOOLTIP_RELATED_PRODUCT_THUMB_WIDTH_TWO' ); ?>::<?php echo JText::_( 'RELATED_PRODUCT_THUMB_WIDTH_LBL_TWO' ); ?>">
			<label for="name"><?php echo JText::_ ( 'RELATED_PRODUCT_THUMB_WIDTH_HEIGHT_TWO' ); ?></label></span></td>
			<td>
			<input type="text" name="related_product_thumb_width_2" id="related_product_thumb_width_2" value="<?php echo RELATED_PRODUCT_THUMB_WIDTH_2;?>">
			<input type="text" name="related_product_thumb_height_2" id="related_product_thumb_height_2" value="<?php echo RELATED_PRODUCT_THUMB_HEIGHT_2;?>">
			</td>
			</tr>
			<tr>
			<td width="100" align="right" class="key">
			<span class="editlinktip hasTip" title="<?php echo JText::_( 'TOOLTIP_RELATED_PRODUCT_THUMB_WIDTH_THREE' ); ?>::<?php echo JText::_( 'RELATED_PRODUCT_THUMB_WIDTH_LBL_THREE' ); ?>">
			<label for="name"><?php echo JText::_ ( 'RELATED_PRODUCT_THUMB_WIDTH_HEIGHT_THREE' );?></label></span></td>
			<td>
			<input type="text" name="related_product_thumb_width_3" id="related_product_thumb_width_3" value="<?php echo RELATED_PRODUCT_THUMB_WIDTH_3;?>">
			<input type="text" name="related_product_thumb_height_3" id="related_product_thumb_height_3" value="<?php echo RELATED_PRODUCT_THUMB_HEIGHT_3; ?>">
			</td>
			</tr>
			</table>
		</fieldset>
</td>
</tr>
</table>