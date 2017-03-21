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
		'title' => JText::_('COM_REDSHOP_DEFAULT_VOLUME_UNIT_LBL'),
		'desc'  => JText::_('COM_REDSHOP_TOOLTIP_DEFAULT_VOLUME_UNIT_LBL'),
		'field' => $this->lists['default_volume_unit']
	)
);
echo RedshopLayoutHelper::render(
	'config.config',
	array(
		'title' => JText::_('COM_REDSHOP_DEFAULT_WEIGHT_UNIT_LBL'),
		'desc'  => JText::_('COM_REDSHOP_TOOLTIP_DEFAULT_WEIGHT_UNIT_LBL'),
		'field' => $this->lists['default_weight_unit']
	)
);
echo RedshopLayoutHelper::render(
	'config.config',
	array(
		'title' => JText::_('COM_REDSHOP_UNIT_DECIMAL_LBL'),
		'desc'  => JText::_('COM_REDSHOP_TOOLTIP_UNIT_DECIMAL_LBL'),
		'field' => '<input type="number" name="unit_decimal" id="unit_decimal" value="'
			. $this->config->get('UNIT_DECIMAL') . '" class="form-control" />'
	)
);
