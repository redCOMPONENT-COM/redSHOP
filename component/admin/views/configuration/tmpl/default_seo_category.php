<?php
/**
 * @package     RedSHOP.Backend
 * @subpackage  Template
 *
 * @copyright   Copyright (C) 2008 - 2017 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */
defined('_JEXEC') or die;

echo RedshopLayoutHelper::render(
	'config.config',
	array(
		'title' => JText::_('COM_REDSHOP_SEO_PAGE_TITLE_CATEGORY_LBL'),
		'desc'  => JText::_('COM_REDSHOP_TOOLTIP_SEO_PAGE_TITLE_CATEGORY_LBL'),
		'field' => '<textarea class="form-control" name="seo_page_title_category" id="seo_page_title_category" rows="4" cols="40"/>'
			. stripslashes($this->config->get('SEO_PAGE_TITLE_CATEGORY')) . '</textarea>'
	)
);
echo RedshopLayoutHelper::render(
	'config.config',
	array(
		'title' => JText::_('COM_REDSHOP_SEO_PAGE_HEADING_CATEGORY_LBL'),
		'desc'  => JText::_('COM_REDSHOP_TOOLTIP_SEO_PAGE_HEADING_CATEGORY_LBL'),
		'field' => '<textarea class="form-control" name="seo_page_heading_category" id="seo_page_heading_category" rows="4" cols="40"/>'
			. stripslashes($this->config->get('SEO_PAGE_HEADING_CATEGORY')) . '</textarea>'
	)
);
echo RedshopLayoutHelper::render(
	'config.config',
	array(
		'title' => JText::_('COM_REDSHOP_SEO_PAGE_DESCRIPTION_CATEGORY_LBL'),
		'desc'  => JText::_('COM_REDSHOP_TOOLTIP_SEO_PAGE_DESCRIPTION_CATEGORY_LBL'),
		'field' => '<textarea class="form-control" name="seo_page_description_category" id="seo_page_description_category" rows="4" cols="40"/>'
			. stripslashes($this->config->get('SEO_PAGE_DESCRIPTION_CATEGORY')) . '</textarea>'
	)
);
echo RedshopLayoutHelper::render(
	'config.config',
	array(
		'title' => JText::_('COM_REDSHOP_SEO_PAGE_KEYWORDS_CATEGORY_LBL'),
		'desc'  => JText::_('COM_REDSHOP_TOOLTIP_SEO_PAGE_KEYWORDS_CATEGORY_LBL'),
		'line'  => false,
		'field' => '<textarea class="form-control" name="seo_page_keywords_category" id="seo_page_keywords_category" rows="4" cols="40"/>'
			. stripslashes($this->config->get('SEO_PAGE_KEYWORDS_CATEGORY')) . '</textarea>'
	)
);
