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
		'title' => JText::_('COM_REDSHOP_CLICKTELL_ENABLE_LBL'),
		'desc'  => JText::_('COM_REDSHOP_TOOLTIP_CLICKTELL_ENABLE_LBL'),
		'field' => $this->lists['clickatell_enable']
	)
);

echo RedshopLayoutHelper::render(
	'config.config',
	array(
		'title'  => JText::_('COM_REDSHOP_CLICKATELL_USERNAME_LBL'),
		'desc'   => JText::_('COM_REDSHOP_TOOLTIP_CLICKATELL_USERNAME_LBL'),
		'id'     => 'clickatell_username',
		'showOn' => 'clickatell_enable:1',
		'field'  => '<input type="text" name="clickatell_username" id="clickatell_username"
            value="' . $this->config->get('CLICKATELL_USERNAME') . '" class="form-control" />'
	)
);

echo RedshopLayoutHelper::render(
	'config.config',
	array(
		'title'  => JText::_('COM_REDSHOP_CLICKATELL_PASSWORD_LBL'),
		'desc'   => JText::_('COM_REDSHOP_TOOLTIP_CLICKATELL_PASSWORD_LBL'),
		'id'     => 'clickatell_password',
		'showOn' => 'clickatell_enable:1',
		'field'  => '<input type="password" name="clickatell_password" id="clickatell_password"
            value="' . $this->config->get('CLICKATELL_PASSWORD') . '" class="form-control" />'
	)
);

echo RedshopLayoutHelper::render(
	'config.config',
	array(
		'title'  => JText::_('COM_REDSHOP_CLICKATELL_API_ID_LBL'),
		'desc'   => JText::_('COM_REDSHOP_TOOLTIP_CLICKATELL_API_ID_LBL'),
		'id'     => 'clickatell_api_id',
		'showOn' => 'clickatell_enable:1',
		'field'  => '<input type="text" name="clickatell_api_id" id="clickatell_api_id"
            value="' . $this->config->get('CLICKATELL_API_ID') . '" class="form-control" />'
	)
);

echo RedshopLayoutHelper::render(
	'config.config',
	array(
		'title'  => JText::_('COM_REDSHOP_CLICKTELL_ORDER_STATUS_LBL'),
		'desc'   => JText::_('COM_REDSHOP_TOOLTIP_CLICKTELL_ORDER_STATUS_LBL'),
		'id'     => 'clickatell_order_status',
		'showOn' => 'clickatell_enable:1',
		'field'  => $this->lists['clickatell_order_status']
	)
);
