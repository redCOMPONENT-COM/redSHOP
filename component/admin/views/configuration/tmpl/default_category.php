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
<tr><td class="config_param"><?php echo JText::_( 'MAIN_CATEGORY_SETTINGS' ); ?></td></tr>
	<tr>
		<td width="100" align="right" class="key">
			<span class="editlinktip hasTip" title="<?php echo JText::_( 'DEFAULT_CATEGORY_ORDERING_METHOD_LBL' ); ?>::<?php echo JText::_( 'TOOLTIP_DEFAULT_CATEGORY_ORDERING_METHOD_LBL' ); ?>">
			<label for="name"><?php echo JText::_ ( 'DEFAULT_CATEGORY_ORDERING_METHOD_LBL' );?></label></span>
		</td>
		<td><?php echo $this->lists ['default_category_ordering_method'];?></td>
	</tr>
	<tr>
		<td width="100" align="right" class="key">
			<span class="editlinktip hasTip" title="<?php echo JText::_( 'MAXCATEGORY_LBL' ); ?>::<?php echo JText::_( 'TOOLTIP_MAXCATEGORY_LBL' ); ?>">
			<?php echo JText::_ ( 'MAXCATEGORY_LBL' );?></span>
		</td>
		<td>
		<input type="text" name="maxcategory" id="maxcategory" value="<?php echo MAXCATEGORY;?>">
		</td>
	</tr>
	<tr>
		<td align="right" class="key">
		<span class="editlinktip hasTip" title="<?php echo JText::_( 'TOOLTIP_PRODUCT_EXPIRE_LBL' ); ?>::<?php echo JText::_( 'TOOLTIP_PRODUCT_EXPIRE' ); ?>">
		<?php echo JText::_ ( 'PRODUCT_EXPIRE_LBL' );?>:</span>
		</td>
		<td>
		<textarea class="text_area" type="text" name="product_expire_text" id="product_expire_text" rows="4" cols="40" /><?php echo stripslashes(PRODUCT_EXPIRE_TEXT);?></textarea>
		</td>
	</tr>
	<tr>
		<td align="right" class="key">
		<span class="editlinktip hasTip" title="<?php echo JText::_( 'FRONTPAGE_CATEGORY_PAGE_INTROTEXT' ); ?>::<?php echo JText::_( 'TOOLTIP_FRONTPAGE_CATEGORY_PAGE_INTROTEXT' ); ?>">
		<?php echo JText::_ ( 'FRONTPAGE_CATEGORY_PAGE_INTROTEXT' );?>:</span>
		</td>
		<td>
		<textarea class="text_area" type="text" name="category_frontpage_introtext" id="category_frontpage_introtext" rows="4" cols="40" /><?php echo stripslashes(CATEGORY_FRONTPAGE_INTROTEXT);	?></textarea>
		</td>
	</tr>
</table>