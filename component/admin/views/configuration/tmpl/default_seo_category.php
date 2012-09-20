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
		<td width="100" align="right" class="key">
		<span class="editlinktip hasTip" title="<?php echo JText::_( 'SEO_PAGE_TITLE_CATEGORY_LBL' ); ?>::<?php echo JText::_( 'TOOLTIP_SEO_PAGE_TITLE_CATEGORY_LBL' ); ?>">
		<label
			for="seo_page_title_category"><?php
			echo JText::_ ( 'SEO_PAGE_TITLE_CATEGORY_LBL' );
			?></label></span></td>
		<td><textarea class="text_area" type="text"
			name="seo_page_title_category" id="seo_page_title_category" rows="4"
			cols="40" /><?php
			echo stripslashes(SEO_PAGE_TITLE_CATEGORY);
			?></textarea>
		</td>
	</tr>
	<tr>
		<td width="100" align="right" class="key">
		<span class="editlinktip hasTip" title="<?php echo JText::_( 'SEO_PAGE_HEADING_CATEGORY_LBL' ); ?>::<?php echo JText::_( 'TOOLTIP_SEO_PAGE_HEADING_CATEGORY_LBL' ); ?>">
		<label
			for="seo_page_heading_category"><?php
			echo JText::_ ( 'SEO_PAGE_HEADING_CATEGORY_LBL' );
			?></label></span></td>
		<td><textarea class="text_area" type="text"
			name="seo_page_heading_category" id="seo_page_heading_category"
			rows="4" cols="40" /><?php
			echo stripslashes(SEO_PAGE_HEADING_CATEGORY);
			?></textarea>
		</td>
	</tr>

	<tr>
		<td width="100" align="right" class="key">
		<span class="editlinktip hasTip" title="<?php echo JText::_( 'SEO_PAGE_DESCRIPTION_CATEGORY_LBL' ); ?>::<?php echo JText::_( 'TOOLTIP_SEO_PAGE_DESCRIPTION_CATEGORY_LBL' ); ?>">
		<label
			for="seo_page_description_category"><?php
			echo JText::_ ( 'SEO_PAGE_DESCRIPTION_CATEGORY_LBL' );
			?></label></span></td>
		<td><textarea class="text_area" type="text"
			name="seo_page_description_category"
			id="seo_page_description_category" rows="4" cols="40" /><?php
			echo stripslashes(SEO_PAGE_DESCRIPTION_CATEGORY);
			?></textarea>
		</td>
	</tr>
	<tr>
		<td width="100" align="right" class="key">
		<span class="editlinktip hasTip" title="<?php echo JText::_( 'SEO_PAGE_KEYWORDS_CATEGORY_LBL' ); ?>::<?php echo JText::_( 'TOOLTIP_SEO_PAGE_KEYWORDS_CATEGORY_LBL' ); ?>">
		<label
			for="seo_page_keywords_category"><?php
			echo JText::_ ( 'SEO_PAGE_KEYWORDS_CATEGORY_LBL' );
			?></label></span></td>
		<td><textarea class="text_area" type="text"
			name="seo_page_keywords_category" id="seo_page_keywords_category"
			rows="4" cols="40" /><?php
			echo stripslashes(SEO_PAGE_KEYWORDS_CATEGORY);
			?></textarea>
		</td>
	</tr>
</table>