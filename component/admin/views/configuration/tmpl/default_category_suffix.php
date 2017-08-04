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
		'title' => JText::_('COM_REDSHOP_CATEGORY_DESC_MAX_CHARS_LBL'),
		'desc'  => JText::_('COM_REDSHOP_TOOLTIP_CATEGORY_DESC_MAX_CHARS_LBL'),
		'field' => '<input type="number" name="category_desc_max_chars" id="category_desc_max_chars" class="form-control"'
			. ' value="' . $this->config->get('CATEGORY_DESC_MAX_CHARS') . '"/>'
	)
);
echo RedshopLayoutHelper::render(
	'config.config',
	array(
		'title' => JText::_('COM_REDSHOP_CATEGORY_DESC_END_SUFFIX_LBL'),
		'desc'  => JText::_('COM_REDSHOP_TOOLTIP_CATEGORY_DESC_END_SUFFIX_LBL'),
		'field' => '<input type="text" name="category_desc_end_suffix" id="category_desc_end_suffix" class="form-control"'
			. ' value="' . $this->config->get('CATEGORY_DESC_END_SUFFIX') . '"/>'
	)
);
echo RedshopLayoutHelper::render(
	'config.config',
	array(
		'title' => JText::_('COM_REDSHOP_CATEGORY_SHORT_DESC_MAX_CHARS_LBL'),
		'desc'  => JText::_('COM_REDSHOP_TOOLTIP_CATEGORY_SHORT_DESC_MAX_CHARS'),
		'field' => '<input type="number" name="category_short_desc_max_chars" id="category_short_desc_max_chars" class="form-control"'
			. ' value="' . $this->config->get('CATEGORY_SHORT_DESC_MAX_CHARS') . '"/>'
	)
);
echo RedshopLayoutHelper::render(
	'config.config',
	array(
		'title' => JText::_('COM_REDSHOP_CATEGORY_SHORT_DESC_END_SUFFIX_LBL'),
		'desc'  => JText::_('COM_REDSHOP_TOOLTIP_CATEGORY_SHORT_DESC_END_SUFFIX_LBL'),
		'field' => '<input type="text" name="category_short_desc_end_suffix" id="category_short_desc_end_suffix" class="form-control"'
			. ' value="' . $this->config->get('CATEGORY_SHORT_DESC_END_SUFFIX') . '"/>'
	)
);
echo RedshopLayoutHelper::render(
	'config.config',
	array(
		'title' => JText::_('COM_REDSHOP_CATEGORY_TITLE_MAX_CHARS_LBL'),
		'desc'  => JText::_('COM_REDSHOP_TOOLTIP_CATEGORY_TITLE_MAX_CHARS_LBL'),
		'field' => '<input type="number" name="category_title_max_chars" id="category_title_max_chars" class="form-control"'
			. ' value="' . $this->config->get('CATEGORY_TITLE_MAX_CHARS') . '"/>'
	)
);
echo RedshopLayoutHelper::render(
	'config.config',
	array(
		'title' => JText::_('COM_REDSHOP_CATEGORY_TITLE_END_SUFFIX_LBL'),
		'desc'  => JText::_('COM_REDSHOP_TOOLTIP_CATEGORY_TITLE_END_SUFFIX_LBL'),
		'field' => '<input type="text" name="category_title_end_suffix" id="category_title_end_suffix" class="form-control"'
			. ' value="' . $this->config->get('CATEGORY_TITLE_END_SUFFIX') . '"/>'
	)
);
echo RedshopLayoutHelper::render(
	'config.config',
	array(
		'title' => JText::_('COM_REDSHOP_CATEGORY_PRODUCT_TITLE_MAX_CHARS_LBL'),
		'desc'  => JText::_('COM_REDSHOP_TOOLTIP_CATEGORY_PRODUCT_TITLE_MAX_CHARS_LBL'),
		'field' => '<input type="number" name="category_product_title_max_chars" id="category_product_title_max_chars" class="form-control"'
			. ' value="' . $this->config->get('CATEGORY_PRODUCT_TITLE_MAX_CHARS') . '"/>'
	)
);
echo RedshopLayoutHelper::render(
	'config.config',
	array(
		'title' => JText::_('COM_REDSHOP_CATEGORY_PRODUCT_TITLE_END_SUFFIX_LBL'),
		'desc'  => JText::_('COM_REDSHOP_TOOLTIP_CATEGORY_PRODUCT_TITLE_END_SUFFIX_LBL'),
		'field' => '<input type="text" name="category_product_title_end_suffix" id="category_product_title_end_suffix" class="form-control"'
			. ' value="' . $this->config->get('CATEGORY_PRODUCT_TITLE_END_SUFFIX') . '"/>'
	)
);
echo RedshopLayoutHelper::render(
	'config.config',
	array(
		'title' => JText::_('COM_REDSHOP_CATEGORY_PRODUCT_DESC_MAX_CHARS_LBL'),
		'desc'  => JText::_('COM_REDSHOP_TOOLTIP_CATEGORY_PRODUCT_DESC_MAX_CHARS_LBL'),
		'field' => '<input type="number" name="category_product_desc_max_chars" id="category_product_desc_max_chars" class="form-control"'
			. ' value="' . $this->config->get('CATEGORY_PRODUCT_DESC_MAX_CHARS') . '"/>'
	)
);
echo RedshopLayoutHelper::render(
	'config.config',
	array(
		'title' => JText::_('COM_REDSHOP_CATEGORY_PRODUCT_DESC_MAX_SUFFIX_LBL'),
		'desc'  => JText::_('COM_REDSHOP_TOOLTIP_CATEGORY_PRODUCT_DESC_MAX_SUFFIX_LBL'),
		'field' => '<input type="text" name="category_product_desc_end_suffix" id="category_product_desc_end_suffix" class="form-control"'
			. ' value="' . $this->config->get('CATEGORY_PRODUCT_DESC_END_SUFFIX') . '"/>'
	)
);
echo RedshopLayoutHelper::render(
	'config.config',
	array(
		'title' => JText::_('COM_REDSHOP_CATEGORY_PRODUCT_SHORT_DESC_MAX_CHARS_LBL'),
		'desc'  => JText::_('COM_REDSHOP_TOOLTIP_CATEGORY_PRODUCT_SHORT_DESC_MAX_CHARS_LBL'),
		'field' => '<input type="number" name="category_product_short_desc_max_chars" id="category_product_short_desc_max_chars" class="form-control"'
			. ' value="' . $this->config->get('CATEGORY_PRODUCT_SHORT_DESC_MAX_CHARS') . '"/>'
	)
);
echo RedshopLayoutHelper::render(
	'config.config',
	array(
		'title' => JText::_('COM_REDSHOP_CATEGORY_PRODUCT_SHORT_DESC_END_SUFFIX_LBL'),
		'desc'  => JText::_('COM_REDSHOP_TOOLTIP_CATEGORY_PRODUCT_SHORT_DESC_END_SUFFIX_LBL'),
		'line'  => false,
		'field' => '<input type="text" name="category_product_short_desc_end_suffix" id="category_product_short_desc_end_suffix" class="form-control"'
			. ' value="' . $this->config->get('CATEGORY_PRODUCT_SHORT_DESC_END_SUFFIX') . '"/>'
	)
);
