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
		<span class="editlinktip hasTip" title="<?php echo JText::_('COM_REDSHOP_SEO_PAGE_TITLE_MANUFACTUR_LBL' ); ?>::<?php echo JText::_('COM_REDSHOP_TOOLTIP_SEO_PAGE_TITLE_MANUFACTUR_LBL' ); ?>">
		<label
			for="seo_page_title_manufactur"><?php
			echo JText::_('COM_REDSHOP_SEO_PAGE_TITLE_MANUFACTUR_LBL' );
			?></label></span></td>
		<td><textarea class="text_area" type="text"
			name="seo_page_title_manufactur" id="seo_page_title_manufactur"
			rows="4" cols="40" /><?php
			echo stripslashes(SEO_PAGE_TITLE_MANUFACTUR);
			?></textarea>
		</td>
	</tr>
	<tr>
		<td width="100" align="right" class="key">
		<span class="editlinktip hasTip" title="<?php echo JText::_('COM_REDSHOP_TOOLTIP_SEO_PAGE_HEADING_MANUFACTUR_LBL' ); ?>::<?php echo JText::_('COM_REDSHOP_TOOLTIP_SEO_PAGE_HEADING_MANUFACTUR' ); ?>">
		<label
			for="seo_page_heading_manufactur"><?php
			echo JText::_('COM_REDSHOP_SEO_PAGE_HEADING_MANUFACTUR_LBL' );
			?></label></span></td>
		<td><textarea class="text_area" type="text"
			name="seo_page_heading_manufactur" id="seo_page_heading_manufactur"
			rows="4" cols="40" /><?php
			echo stripslashes(SEO_PAGE_HEADING_MANUFACTUR);
			?></textarea>
		</td>
	</tr>
	<tr>
		<td width="100" align="right" class="key">
		<span class="editlinktip hasTip" title="<?php echo JText::_('COM_REDSHOP_SEO_PAGE_DESCRIPTION_MANUFACTUR_LBL' ); ?>::<?php echo JText::_('COM_REDSHOP_TOOLTIP_SEO_PAGE_DESCRIPTION_MANUFACTUR_LBL' ); ?>">
		<label
			for="seo_page_description_manufactur"><?php
			echo JText::_('COM_REDSHOP_SEO_PAGE_DESCRIPTION_MANUFACTUR_LBL' );
			?></label></span></td>
		<td><textarea class="text_area" type="text"
			name="seo_page_description_manufactur"
			id="seo_page_description_manufactur" rows="4" cols="40" /><?php
			echo stripslashes(SEO_PAGE_DESCRIPTION_MANUFACTUR);
			?></textarea>
		</td>
	</tr>
	<tr>
		<td width="100" align="right" class="key">
		<span class="editlinktip hasTip" title="<?php echo JText::_('COM_REDSHOP_SEO_PAGE_KEYWORDS_MANUFACTUR_LBL' ); ?>::<?php echo JText::_('COM_REDSHOP_TOOLTIP_SEO_PAGE_KEYWORDS_MANUFACTUR_LBL' ); ?>">
		<label
			for="seo_page_keywords_manufactur"><?php
			echo JText::_('COM_REDSHOP_SEO_PAGE_KEYWORDS_MANUFACTUR_LBL' );
			?></label></span></td>
		<td><textarea class="text_area" type="text"
			name="seo_page_keywords_manufactur" id="seo_page_keywords_manufactur"
			rows="4" cols="40" /><?php
			echo stripslashes(SEO_PAGE_KEYWORDS_MANUFACTUR);
			?></textarea>
		</td>
	</tr>
	<tr>
		<td width="100" align="right" class="key">
		<span class="editlinktip hasTip" title="<?php echo JText::_( 'SEO_PAGE_CANONICAL_MANUFACTUR_LBL' ); ?>::<?php echo JText::_( 'TOOLTIP_SEO_PAGE_CANONICAL_MANUFACTUR_LBL' ); ?>">
		<label
			for="seo_page_keywords_manufactur"><?php
			echo JText::_ ( 'SEO_PAGE_CANONICAL_MANUFACTUR_LBL' );
			?></label></span></td>
		<td><textarea class="text_area" type="text"
			name="seo_page_canonical_manufactur" id="seo_page_canonical_manufactur"
			rows="4" cols="40" /><?php
			echo stripslashes(SEO_PAGE_CANONICAL_MANUFACTUR);
			?></textarea>
		</td>
	</tr>
</table>
