<?php
/**
 * @package     RedSHOP.Backend
 * @subpackage  Template
 *
 * @copyright   Copyright (C) 2008 - 2019 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */
defined('_JEXEC') or die;

echo RedshopLayoutHelper::render(
	'config.config',
	array(
		'title' => JText::_('COM_REDSHOP_DEFAULT_CATEGORY_LBL'),
		'desc'  => JText::_('COM_REDSHOP_TOOLTIP_DEFAULT_CATEGORY_LBL'),
		'field' => $this->lists['product_default_category']
	)
);
