<?php
/**
 * @package     RedSHOP.Backend
 * @subpackage  Template
 *
 * @copyright   Copyright (C) 2008 - 2016 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */
defined('_JEXEC') or die;

?>

<legend><?php echo JText::_('COM_REDSHOP_SEO_MANUFACTURER_TAB'); ?></legend>

<div class="form-group">
	<span class="editlinktip hasTip"
		      title="<?php echo JText::_('COM_REDSHOP_SEO_PAGE_TITLE_MANUFACTUR_LBL'); ?>::<?php echo JText::_('COM_REDSHOP_TOOLTIP_SEO_PAGE_TITLE_MANUFACTUR_LBL'); ?>">
		<label
			for="seo_page_title_manufactur"><?php
			echo JText::_('COM_REDSHOP_SEO_PAGE_TITLE_MANUFACTUR_LBL');
			?></label></span>
	<textarea class="text_area" type="text"
		              name="seo_page_title_manufactur" id="seo_page_title_manufactur"
		              rows="4" cols="40"/><?php
			echo stripslashes($this->config->get('SEO_PAGE_TITLE_MANUFACTUR'));
			?></textarea>
</div>

<div class="form-group">
	<span class="editlinktip hasTip"
		      title="<?php echo JText::_('COM_REDSHOP_TOOLTIP_SEO_PAGE_HEADING_MANUFACTUR_LBL'); ?>::<?php echo JText::_('COM_REDSHOP_TOOLTIP_SEO_PAGE_HEADING_MANUFACTUR'); ?>">
		<label
			for="seo_page_heading_manufactur"><?php
			echo JText::_('COM_REDSHOP_SEO_PAGE_HEADING_MANUFACTUR_LBL');
			?></label></span>
	<textarea class="text_area" type="text"
		              name="seo_page_heading_manufactur" id="seo_page_heading_manufactur"
		              rows="4" cols="40"/><?php
			echo stripslashes($this->config->get('SEO_PAGE_HEADING_MANUFACTUR'));
			?></textarea>
</div>

<div class="form-group">
	<span class="editlinktip hasTip"
		      title="<?php echo JText::_('COM_REDSHOP_SEO_PAGE_DESCRIPTION_MANUFACTUR_LBL'); ?>::<?php echo JText::_('COM_REDSHOP_TOOLTIP_SEO_PAGE_DESCRIPTION_MANUFACTUR_LBL'); ?>">
		<label
			for="seo_page_description_manufactur"><?php
			echo JText::_('COM_REDSHOP_SEO_PAGE_DESCRIPTION_MANUFACTUR_LBL');
			?></label></span>
	<textarea class="text_area" type="text"
		              name="seo_page_description_manufactur"
		              id="seo_page_description_manufactur" rows="4" cols="40"/><?php
			echo stripslashes($this->config->get('SEO_PAGE_DESCRIPTION_MANUFACTUR'));
			?></textarea>
</div>

<div class="form-group">
	<span class="editlinktip hasTip"
		      title="<?php echo JText::_('COM_REDSHOP_SEO_PAGE_KEYWORDS_MANUFACTUR_LBL'); ?>::<?php echo JText::_('COM_REDSHOP_TOOLTIP_SEO_PAGE_KEYWORDS_MANUFACTUR_LBL'); ?>">
		<label
			for="seo_page_keywords_manufactur"><?php
			echo JText::_('COM_REDSHOP_SEO_PAGE_KEYWORDS_MANUFACTUR_LBL');
			?></label></span>
	<textarea class="text_area" type="text"
		              name="seo_page_keywords_manufactur" id="seo_page_keywords_manufactur"
		              rows="4" cols="40"/><?php
			echo stripslashes($this->config->get('SEO_PAGE_KEYWORDS_MANUFACTUR'));
			?></textarea>
</div>

<div class="form-group">
	<span class="editlinktip hasTip"
		      title="<?php echo JText::_('COM_REDSHOP_SEO_PAGE_CANONICAL_MANUFACTUR_LBL'); ?>::<?php echo JText::_('COM_REDSHOP_TOOLTIP_SEO_PAGE_CANONICAL_MANUFACTUR_LBL'); ?>">
		<label
			for="seo_page_keywords_manufactur"><?php
			echo JText::_('COM_REDSHOP_SEO_PAGE_CANONICAL_MANUFACTUR_LBL');
			?></label></span>
	<textarea class="text_area" type="text"
		              name="seo_page_canonical_manufactur" id="seo_page_canonical_manufactur"
		              rows="4" cols="40"/><?php
			echo stripslashes($this->config->get('SEO_PAGE_CANONICAL_MANUFACTUR'));
			?></textarea>
</div>
