<?php
/**
 * @package     RedSHOP.Backend
 * @subpackage  Template
 *
 * @copyright   Copyright (C) 2005 - 2013 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */
defined('_JEXEC') or die ('Restricted access');

?>
<table class="admintable">
	<tr>
		<td width="100" align="right" class="key">
		<span class="editlinktip hasTip"
		      title="<?php echo JText::_('COM_REDSHOP_SEO_PAGE_TITLE_CATEGORY_LBL'); ?>::<?php echo JText::_('COM_REDSHOP_TOOLTIP_SEO_PAGE_TITLE_CATEGORY_LBL'); ?>">
		<label
			for="seo_page_title_category"><?php
			echo JText::_('COM_REDSHOP_SEO_PAGE_TITLE_CATEGORY_LBL');
			?></label></span></td>
		<td><textarea class="text_area" type="text"
		              name="seo_page_title_category" id="seo_page_title_category" rows="4"
		              cols="40"/><?php
			echo stripslashes(SEO_PAGE_TITLE_CATEGORY);
			?></textarea>
		</td>
	</tr>
	<tr>
		<td width="100" align="right" class="key">
		<span class="editlinktip hasTip"
		      title="<?php echo JText::_('COM_REDSHOP_SEO_PAGE_HEADING_CATEGORY_LBL'); ?>::<?php echo JText::_('COM_REDSHOP_TOOLTIP_SEO_PAGE_HEADING_CATEGORY_LBL'); ?>">
		<label
			for="seo_page_heading_category"><?php
			echo JText::_('COM_REDSHOP_SEO_PAGE_HEADING_CATEGORY_LBL');
			?></label></span></td>
		<td><textarea class="text_area" type="text"
		              name="seo_page_heading_category" id="seo_page_heading_category"
		              rows="4" cols="40"/><?php
			echo stripslashes(SEO_PAGE_HEADING_CATEGORY);
			?></textarea>
		</td>
	</tr>

	<tr>
		<td width="100" align="right" class="key">
		<span class="editlinktip hasTip"
		      title="<?php echo JText::_('COM_REDSHOP_SEO_PAGE_DESCRIPTION_CATEGORY_LBL'); ?>::<?php echo JText::_('COM_REDSHOP_TOOLTIP_SEO_PAGE_DESCRIPTION_CATEGORY_LBL'); ?>">
		<label
			for="seo_page_description_category"><?php
			echo JText::_('COM_REDSHOP_SEO_PAGE_DESCRIPTION_CATEGORY_LBL');
			?></label></span></td>
		<td><textarea class="text_area" type="text"
		              name="seo_page_description_category"
		              id="seo_page_description_category" rows="4" cols="40"/><?php
			echo stripslashes(SEO_PAGE_DESCRIPTION_CATEGORY);
			?></textarea>
		</td>
	</tr>
	<tr>
		<td width="100" align="right" class="key">
		<span class="editlinktip hasTip"
		      title="<?php echo JText::_('COM_REDSHOP_SEO_PAGE_KEYWORDS_CATEGORY_LBL'); ?>::<?php echo JText::_('COM_REDSHOP_TOOLTIP_SEO_PAGE_KEYWORDS_CATEGORY_LBL'); ?>">
		<label
			for="seo_page_keywords_category"><?php
			echo JText::_('COM_REDSHOP_SEO_PAGE_KEYWORDS_CATEGORY_LBL');
			?></label></span></td>
		<td><textarea class="text_area" type="text"
		              name="seo_page_keywords_category" id="seo_page_keywords_category"
		              rows="4" cols="40"/><?php
			echo stripslashes(SEO_PAGE_KEYWORDS_CATEGORY);
			?></textarea>
		</td>
	</tr>
</table>
