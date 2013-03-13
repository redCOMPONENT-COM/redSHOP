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
		      title="<?php echo JText::_('COM_REDSHOP_SEO_PAGE_TITLE_LBL'); ?>::<?php echo JText::_('COM_REDSHOP_TOOLTIP_SEO_PAGE_TITLE'); ?>">
		<label for="seo_page_title">
			<?php
			echo JText::_('COM_REDSHOP_SEO_PAGE_TITLE_LBL');
			?>
		</label></span></td>
		<td><textarea class="text_area" type="text" name="seo_page_title"
		              id="seo_page_title" rows="4" cols="40"/><?php
			echo stripslashes(SEO_PAGE_TITLE);
			?></textarea>
		</td>
	</tr>
	<tr>
		<td width="100" align="right" class="key">
		<span class="editlinktip hasTip"
		      title="<?php echo JText::_('COM_REDSHOP_SEO_PAGE_HEADING_LBL'); ?>::<?php echo JText::_('COM_REDSHOP_TOOLTIP_SEO_PAGE_HEADING_LBL'); ?>">
		<label
			for="seo_page_heading"><?php
			echo JText::_('COM_REDSHOP_SEO_PAGE_HEADING_LBL');
			?></label></span></td>
		<td><textarea class="text_area" type="text" name="seo_page_heading"
		              id="seo_page_heading" rows="4" cols="40"/><?php
			echo stripslashes(SEO_PAGE_HEADING);
			?></textarea>
		</td>
	</tr>

	<tr>
		<td width="100" align="right" class="key">
		<span class="editlinktip hasTip"
		      title="<?php echo JText::_('COM_REDSHOP_SEO_PAGE_DESCRIPTION_LBL'); ?>::<?php echo JText::_('COM_REDSHOP_TOOLTIP_SEO_PAGE_DESCRIPTION'); ?>">
		<label
			for="seo_page_description"><?php
			echo JText::_('COM_REDSHOP_SEO_PAGE_DESCRIPTION_LBL');
			?></label></span></td>
		<td><textarea class="text_area" type="text"
		              name="seo_page_description" id="seo_page_description" rows="4"
		              cols="40"/><?php
			echo stripslashes(SEO_PAGE_DESCRIPTION);
			?></textarea>
		</td>
	</tr>
	<tr>
		<td width="100" align="right" class="key">
		<span class="editlinktip hasTip"
		      title="<?php echo JText::_('COM_REDSHOP_SEO_PAGE_KEYWORDS_LBL'); ?>::<?php echo JText::_('COM_REDSHOP_TOOLTIP_SEO_PAGE_KEYWORDS'); ?>">
		<label
			for="seo_page_keywords"><?php
			echo JText::_('COM_REDSHOP_SEO_PAGE_KEYWORDS_LBL');
			?></label></span></td>
		<td><textarea class="text_area" type="text" name="seo_page_keywords"
		              id="seo_page_keywords" rows="4" cols="40"/><?php
			echo stripslashes(SEO_PAGE_KEYWORDS);
			?></textarea>
		</td>
	</tr>
</table>
