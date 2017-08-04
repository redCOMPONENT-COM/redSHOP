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
		'title' => JText::_('COM_REDSHOP_DEFAULT_CATEGORY_TEMPLATE_LBL'),
		'desc'  => JText::_('COM_REDSHOP_TOOLTIP_DEFAULT_CATEGORY_TEMPLATE_FOR_VM_LBL'),
		'field' => $this->lists['category_template']
	)
);

echo RedshopLayoutHelper::render(
	'config.config',
	array(
		'title' => JText::_('COM_REDSHOP_DEFAULT_CATEGORYLIST_TEMPLATE_LBL'),
		'desc'  => JText::_('COM_REDSHOP_TOOLTIP_DEFAULT_CATEGORY_TEMPLATELIST_LBL'),
		'field' => $this->lists['default_categorylist_template'],
		'line'  => false
	)
);
