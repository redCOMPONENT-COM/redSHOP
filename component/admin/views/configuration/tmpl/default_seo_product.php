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
		'title' => JText::_('COM_REDSHOP_SEO_PAGE_TITLE_LBL'),
		'desc'  => JText::_('COM_REDSHOP_TOOLTIP_SEO_PAGE_TITLE'),
		'field' => '<textarea class="form-control" name="seo_page_title" id="seo_page_title" rows="4" cols="40"/>'
			. stripslashes($this->config->get('SEO_PAGE_TITLE')) . '</textarea>'
	)
);
echo RedshopLayoutHelper::render(
	'config.config',
	array(
		'title' => JText::_('COM_REDSHOP_SEO_PAGE_HEADING_LBL'),
		'desc'  => JText::_('COM_REDSHOP_TOOLTIP_SEO_PAGE_HEADING_LBL'),
		'field' => '<textarea class="form-control" name="seo_page_heading" id="seo_page_heading" rows="4" cols="40"/>'
			. stripslashes($this->config->get('SEO_PAGE_HEADING')) . '</textarea>'
	)
);
echo RedshopLayoutHelper::render(
	'config.config',
	array(
		'title' => JText::_('COM_REDSHOP_SEO_PAGE_DESCRIPTION_LBL'),
		'desc'  => JText::_('COM_REDSHOP_TOOLTIP_SEO_PAGE_DESCRIPTION'),
		'field' => '<textarea class="form-control" name="seo_page_description" id="seo_page_description" rows="4" cols="40"/>'
			. stripslashes($this->config->get('SEO_PAGE_DESCRIPTION')) . '</textarea>'
	)
);
echo RedshopLayoutHelper::render(
	'config.config',
	array(
		'title' => JText::_('COM_REDSHOP_SEO_PAGE_KEYWORDS_LBL'),
		'desc'  => JText::_('COM_REDSHOP_TOOLTIP_SEO_PAGE_KEYWORDS'),
		'field' => '<textarea class="form-control" name="seo_page_keywords" id="seo_page_keywords" rows="4" cols="40"/>'
			. stripslashes($this->config->get('SEO_PAGE_KEYWORDS')) . '</textarea>',
		'line'  => false
	)
);
