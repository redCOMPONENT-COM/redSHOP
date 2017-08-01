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
		'title' => JText::_('COM_REDSHOP_SEO_PAGE_TITLE_MANUFACTUR_LBL'),
		'desc'  => JText::_('COM_REDSHOP_TOOLTIP_SEO_PAGE_TITLE_MANUFACTUR_LBL'),
		'field' => '<textarea class="form-control" name="seo_page_title_manufactur" id="seo_page_title_manufactur" rows="4" cols="40"/>'
			. stripslashes($this->config->get('SEO_PAGE_TITLE_MANUFACTUR')) . '</textarea>'
	)
);
echo RedshopLayoutHelper::render(
	'config.config',
	array(
		'title' => JText::_('COM_REDSHOP_SEO_PAGE_HEADING_MANUFACTUR_LBL'),
		'desc'  => JText::_('COM_REDSHOP_TOOLTIP_SEO_PAGE_HEADING_MANUFACTUR'),
		'field' => '<textarea class="form-control" name="seo_page_heading_manufactur" id="seo_page_heading_manufactur" rows="4" cols="40"/>'
			. stripslashes($this->config->get('SEO_PAGE_HEADING_MANUFACTUR')) . '</textarea>'
	)
);
echo RedshopLayoutHelper::render(
	'config.config',
	array(
		'title' => JText::_('COM_REDSHOP_SEO_PAGE_DESCRIPTION_MANUFACTUR_LBL'),
		'desc'  => JText::_('COM_REDSHOP_TOOLTIP_SEO_PAGE_DESCRIPTION_MANUFACTUR_LBL'),
		'field' => '<textarea class="form-control" name="seo_page_description_manufactur" id="seo_page_description_manufactur" rows="4" cols="40"/>'
			. stripslashes($this->config->get('SEO_PAGE_DESCRIPTION_MANUFACTUR')) . '</textarea>'
	)
);
echo RedshopLayoutHelper::render(
	'config.config',
	array(
		'title' => JText::_('COM_REDSHOP_SEO_PAGE_KEYWORDS_MANUFACTUR_LBL'),
		'desc'  => JText::_('COM_REDSHOP_TOOLTIP_SEO_PAGE_KEYWORDS_MANUFACTUR_LBL'),
		'field' => '<textarea class="form-control" name="seo_page_keywords_manufactur" id="seo_page_keywords_manufactur" rows="4" cols="40"/>'
			. stripslashes($this->config->get('SEO_PAGE_KEYWORDS_MANUFACTUR')) . '</textarea>'
	)
);
echo RedshopLayoutHelper::render(
	'config.config',
	array(
		'title' => JText::_('COM_REDSHOP_SEO_PAGE_CANONICAL_MANUFACTUR_LBL'),
		'desc'  => JText::_('COM_REDSHOP_TOOLTIP_SEO_PAGE_CANONICAL_MANUFACTUR_LBL'),
		'field' => '<textarea class="form-control" name="seo_page_canonical_manufactur" id="seo_page_canonical_manufactur" rows="4" cols="40"/>'
			. stripslashes($this->config->get('SEO_PAGE_CANONICAL_MANUFACTUR')) . '</textarea>',
		'line'  => false
	)
);
