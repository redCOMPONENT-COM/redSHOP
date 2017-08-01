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
		'title' => JText::_('COM_REDSHOP_SHOP_NAME_LBL'),
		'desc'  => JText::_('COM_REDSHOP_TOOLTIP_SHOP_NAME_LBL'),
		'field' => '<input type="text" name="shop_name" id="shop_name" value="' . $this->config->get('SHOP_NAME') . '" class="form-control"/>'
	)
);

echo RedshopLayoutHelper::render(
	'config.config',
	array(
		'title' => JText::_('COM_REDSHOP_SHOP_COUNTRY_LBL'),
		'desc'  => JText::_('COM_REDSHOP_TOOLTIP_SHOP_COUNTRY'),
		'field' => $this->lists['shop_country']
	)
);

echo RedshopLayoutHelper::render(
	'config.config',
	array(
		'title' => JText::_('COM_REDSHOP_DEFAULT_SHIPPING_COUNTRY_LBL'),
		'desc'  => JText::_('COM_REDSHOP_TOOLTIP_DEFAULT_SHIPPING_COUNTRY_LBL'),
		'field' => $this->lists['default_shipping_country']
	)
);

echo RedshopLayoutHelper::render(
	'config.config',
	array(
		'title' => JText::_('COM_REDSHOP_DEFAULT_DATEFORMAT_LBL'),
		'desc'  => JText::_('COM_REDSHOP_TOOLTIP_DEFAULT_DATEFORMAT_LBL'),
		'field' => $this->lists['default_dateformat']
	)
);

echo RedshopLayoutHelper::render(
	'config.config',
	array(
		'title' => JText::_('COM_REDSHOP_WELCOME_MESSAGE'),
		'desc'  => JText::_('COM_REDSHOP_TOOLTIP_WELCOME_MESSAGE'),
		'field' => '<input type="text" name="welcome_msg" value="' . $this->config->get('WELCOME_MSG') . '" class="form-control"/>'
	)
);

echo RedshopLayoutHelper::render(
	'config.config',
	array(
		'title' => JText::_('COM_REDSHOP_ADMINISTRATOR_EMAIL_LBL'),
		'desc'  => JText::_('COM_REDSHOP_TOOLTIP_ADMINISTRATOR_EMAIL_LBL'),
		'field' => '<input type="text" name="administrator_email" value="' . $this->config->get('ADMINISTRATOR_EMAIL') . '" class="form-control"/>'
	)
);

echo RedshopLayoutHelper::render(
	'config.config',
	array(
		'title' => JText::_('COM_REDSHOP_USE_ENCODING_LBL'),
		'desc'  => JText::_('COM_REDSHOP_TOOLTIP_USE_ENCODING'),
		'field' => $this->lists['use_encoding']
	)
);

echo RedshopLayoutHelper::render(
	'config.config',
	array(
		'title' => JText::_('COM_REDSHOP_CURRENCY_LIBRARIES_LBL'),
		'desc'  => JText::_('COM_REDSHOP_CURRENCY_LIBRARIES_DESC'),
		'field' => $this->lists['currency_libraries']
	)
);

echo RedshopLayoutHelper::render(
	'config.config',
	array(
		'title' => JText::_('COM_REDSHOP_CURRENCY_LAYER_ACCESS_KEY_LBL'),
		'desc'  => JText::_('COM_REDSHOP_CURRENCY_LAYER_ACCESS_KEY_DESC'),
		'showOn' => 'currency_libraries:1',
		'id'     => 'currency_layer_access_key',
		'field' => '<input type="text" id="currency_layer_access_key" name="currency_layer_access_key" value="' . $this->config->get('CURRENCY_LAYER_ACCESS_KEY') . '" class="form-control"/>'
	)
);

echo RedshopLayoutHelper::render(
	'config.config',
	array(
		'title' => JText::_('COM_REDSHOP_COUNTRY_LIST_LBL'),
		'desc'  => JText::_('COM_REDSHOP_TOOLTIP_COUNTRY_LIST_LBL'),
		'field' => $this->lists['country_list'],
		'line'  => false
	)
);
