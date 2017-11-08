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
		'title' => JText::_('COM_REDSHOP_DEFAULT_CATEGORY_ORDERING_METHOD_LBL'),
		'desc'  => JText::_('COM_REDSHOP_TOOLTIP_DEFAULT_CATEGORY_ORDERING_METHOD_LBL'),
		'field' => $this->lists['default_category_ordering_method']
	)
);

echo RedshopLayoutHelper::render(
	'config.config',
	array(
		'title' => JText::_('COM_REDSHOP_MAXCATEGORY_LBL'),
		'desc'  => JText::_('COM_REDSHOP_TOOLTIP_MAXCATEGORY_LBL'),
		'field' => '<input type="number" class="form-control" name="maxcategory" id="maxcategory" value="' . $this->config->get('MAXCATEGORY') . '"/>'
	)
);

echo RedshopLayoutHelper::render(
	'config.config',
	array(
		'title' => JText::_('COM_REDSHOP_PRODUCT_EXPIRE_LBL'),
		'desc'  => JText::_('COM_REDSHOP_TOOLTIP_PRODUCT_EXPIRE'),
		'field' => '<textarea class="form-control" type="text" name="product_expire_text" id="product_expire_text" rows="4"'
			. 'cols="40"/>' . stripslashes($this->config->get('PRODUCT_EXPIRE_TEXT')) . '</textarea>'
	)
);

echo RedshopLayoutHelper::render(
	'config.config',
	array(
		'title' => JText::_('COM_REDSHOP_FRONTPAGE_CATEGORY_PAGE_INTROTEXT'),
		'desc'  => JText::_('COM_REDSHOP_TOOLTIP_FRONTPAGE_CATEGORY_PAGE_INTROTEXT'),
		'field' => '<textarea class="form-control" type="text" name="category_frontpage_introtext" id="category_frontpage_introtext" rows="4"'
			. 'cols="40" />' . stripslashes($this->config->get('CATEGORY_FRONTPAGE_INTROTEXT')) . '</textarea>'
	)
);

