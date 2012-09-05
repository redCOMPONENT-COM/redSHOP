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
<tr><td class="config_param"><?php echo JText::_( 'NEXT_PREVIOUS' ); ?></td></tr>
	<tr>
		<td width="100" align="right" class="key">
		<span class="editlinktip hasTip" title="<?php echo JText::_( 'CATEGORY_DESC_MAX_CHARS_LBL' ); ?>::<?php echo JText::_( 'TOOLTIP_CATEGORY_DESC_MAX_CHARS_LBL' ); ?>">
		<label for="name"><?php echo JText::_ ( 'CATEGORY_DESC_MAX_CHARS_LBL' );?></label></span>
		</td>
		<td>
		<input type="text" name="category_desc_max_chars" id="category_desc_max_chars" value="<?php echo CATEGORY_DESC_MAX_CHARS;	?>">
		</td>
	</tr>
	<tr>
		<td width="100" align="right" class="key">
		<span class="editlinktip hasTip" title="<?php echo JText::_( 'CATEGORY_DESC_END_SUFFIX_LBL' ); ?>::<?php echo JText::_( 'TOOLTIP_CATEGORY_DESC_END_SUFFIX_LBL' ); ?>">
		<label for="name"><?php echo JText::_ ( 'CATEGORY_DESC_END_SUFFIX_LBL' );?></label></span>
		</td>
		<td>
		<input type="text" name="category_desc_end_suffix" id="category_desc_end_suffix" value="<?php echo CATEGORY_DESC_END_SUFFIX;?>">
		</td>
	</tr>


	<tr>
		<td width="100" align="right" class="key">
		<span class="editlinktip hasTip" title="<?php echo JText::_( 'TOOLTIP_CATEGORY_SHORT_DESC_MAX_CHARS_LBL' ); ?>::<?php echo JText::_( 'TOOLTIP_CATEGORY_SHORT_DESC_MAX_CHARS' ); ?>">
		<label for="name"><?php echo JText::_ ( 'CATEGORY_SHORT_DESC_MAX_CHARS_LBL' );?></label></span>
		</td>
		<td>
		<input type="text" name="category_short_desc_max_chars" id="category_short_desc_max_chars" value="<?php echo CATEGORY_SHORT_DESC_MAX_CHARS;	?>">
		</td>
	</tr>
	<tr>
		<td width="100" align="right" class="key">
		<span class="editlinktip hasTip" title="<?php echo JText::_( 'CATEGORY_SHORT_DESC_END_SUFFIX_LBL' ); ?>::<?php echo JText::_( 'TOOLTIP_CATEGORY_SHORT_DESC_END_SUFFIX_LBL' ); ?>">
		<label for="name"><?php echo JText::_ ( 'CATEGORY_SHORT_DESC_END_SUFFIX_LBL' );?></label></span>
		</td>
		<td>
		<input type="text" name="category_short_desc_end_suffix" id="category_short_desc_end_suffix" value="<?php echo CATEGORY_SHORT_DESC_END_SUFFIX;?>">
		</td>
	</tr>
	<tr>
		<td width="100" align="right" class="key">
		<span class="editlinktip hasTip" title="<?php echo JText::_( 'CATEGORY_TITLE_MAX_CHARS_LBL' ); ?>::<?php echo JText::_( 'TOOLTIP_CATEGORY_TITLE_MAX_CHARS_LBL' ); ?>">
		<label for="name"><?php echo JText::_ ( 'CATEGORY_TITLE_MAX_CHARS_LBL' );?></label></span>
		</td>
		<td>
		<input type="text" name="category_title_max_chars" id="category_title_max_chars" value="<?php echo CATEGORY_TITLE_MAX_CHARS;?>">
		</td>
	</tr>
	<tr>
		<td width="100" align="right" class="key">
		<span class="editlinktip hasTip" title="<?php echo JText::_( 'CATEGORY_TITLE_END_SUFFIX_LBL' ); ?>::<?php echo JText::_( 'TOOLTIP_CATEGORY_TITLE_END_SUFFIX_LBL' ); ?>">
		<label for="name"><?php echo JText::_ ( 'CATEGORY_TITLE_END_SUFFIX_LBL' );?></label></span>
		</td>
		<td>
		<input type="text" name="category_title_end_suffix" id="category_title_end_suffix" value="<?php echo CATEGORY_TITLE_END_SUFFIX;?>">
		</td>
	</tr>
	<tr>
		<td width="100" align="right" class="key">
		<span class="editlinktip hasTip" title="<?php echo JText::_( 'CATEGORY_PRODUCT_TITLE_MAX_CHARS_LBL' ); ?>::<?php echo JText::_( 'TOOLTIP_CATEGORY_PRODUCT_TITLE_MAX_CHARS_LBL' ); ?>">
		<label for="name"><?php echo JText::_ ( 'CATEGORY_PRODUCT_TITLE_MAX_CHARS_LBL' );?></label></span>
		</td>
		<td>
		<input type="text" name="category_product_title_max_chars" id="category_product_title_max_chars" value="<?php echo CATEGORY_PRODUCT_TITLE_MAX_CHARS;?>">
		</td>
	<tr>
		<td width="100" align="right" class="key">
		<span class="editlinktip hasTip" title="<?php echo JText::_( 'CATEGORY_PRODUCT_TITLE_END_SUFFIX_LBL' ); ?>::<?php echo JText::_( 'TOOLTIP_CATEGORY_PRODUCT_TITLE_END_SUFFIX_LBL' ); ?>">
		<label for="name"><?php echo JText::_ ( 'CATEGORY_PRODUCT_TITLE_END_SUFFIX_LBL' );?></label></span>
		</td>
		<td>
		<input type="text" name="category_product_title_end_suffix" id="category_product_title_end_suffix" value="<?php echo CATEGORY_PRODUCT_TITLE_END_SUFFIX;?>">
		</td>
	</tr>
	<tr>
		<td width="100" align="right" class="key">
		<span class="editlinktip hasTip" title="<?php echo JText::_( 'CATEGORY_PRODUCT_DESC_MAX_CHARS_LBL' ); ?>::<?php echo JText::_( 'TOOLTIP_CATEGORY_PRODUCT_DESC_MAX_CHARS_LBL' ); ?>">
		<label for="name"><?php echo JText::_ ( 'CATEGORY_PRODUCT_DESC_MAX_CHARS_LBL' );?></label></span>
		</td>
		<td>
		<input type="text" name="category_product_desc_max_chars" id="category_product_desc_max_chars" value="<?php echo CATEGORY_PRODUCT_DESC_MAX_CHARS;?>">
		</td>
	</tr>
	<tr>
		<td width="100" align="right" class="key">
		<span class="editlinktip hasTip" title="<?php echo JText::_( 'CATEGORY_PRODUCT_DESC_MAX_SUFFIX_LBL' ); ?>::<?php echo JText::_( 'TOOLTIP_CATEGORY_PRODUCT_DESC_MAX_SUFFIX_LBL' ); ?>">
		<label for="name"><?php echo JText::_ ( 'CATEGORY_PRODUCT_DESC_MAX_SUFFIX_LBL' );?></label></span>
		</td>
		<td>
		<input type="text" name="category_product_desc_end_suffix" id="category_product_desc_end_suffix" value="<?php echo CATEGORY_PRODUCT_DESC_END_SUFFIX;?>">
		</td>
	</tr>
	<tr>
		<td width="100" align="right" class="key">
		<span class="editlinktip hasTip" title="<?php echo JText::_( 'CATEGORY_PRODUCT_SHORT_DESC_MAX_CHARS_LBL' ); ?>::<?php echo JText::_( 'TOOLTIP_CATEGORY_PRODUCT_SHORT_DESC_MAX_CHARS_LBL' ); ?>">
		<label for="name"><?php echo JText::_ ( 'CATEGORY_PRODUCT_SHORT_DESC_MAX_CHARS_LBL' );?></label></span>
		</td>
		<td><input type="text" name="category_product_short_desc_max_chars" id="category_product_short_desc_max_chars" value="<?php echo CATEGORY_PRODUCT_SHORT_DESC_MAX_CHARS;?>">
		</td>
	</tr>
	<tr>
		<td width="100" align="right" class="key">
		<span class="editlinktip hasTip" title="<?php echo JText::_( 'CATEGORY_PRODUCT_SHORT_DESC_END_SUFFIX_LBL' ); ?>::<?php echo JText::_( 'TOOLTIP_CATEGORY_PRODUCT_SHORT_DESC_END_SUFFIX_LBL' ); ?>">
		<label for="name"><?php echo JText::_ ( 'CATEGORY_PRODUCT_SHORT_DESC_END_SUFFIX_LBL' );?></label></span>
		</td>
		<td><input type="text" name="category_product_short_desc_end_suffix" id="category_product_short_desc_end_suffix" value="<?php echo CATEGORY_PRODUCT_SHORT_DESC_END_SUFFIX;?>">
		</td>
	</tr>
</table>