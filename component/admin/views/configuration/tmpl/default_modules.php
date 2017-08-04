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
		'title' => JText::_('COM_REDSHOP_STATISTICS_ENABLE_TEXT'),
		'desc'  => JText::_('COM_REDSHOP_TOOLTIP_STATISTICS_ENABLE'),
		'field' => $this->lists['statistics_enable']
	)
);

echo RedshopLayoutHelper::render(
	'config.config',
	array(
		'title' => JText::_('COM_REDSHOP_MY_TAGS_LBL'),
		'desc'  => JText::_('COM_REDSHOP_TOOLTIP_MY_TAGS'),
		'field' => $this->lists['my_tags']
	)
);

echo RedshopLayoutHelper::render(
	'config.config',
	array(
		'title' => JText::_('COM_REDSHOP_ENABLE_ADDRESS_DETAIL_IN_SHIPPING_LBL'),
		'desc'  => JText::_('COM_REDSHOP_TOOLTIP_ENABLE_ADDRESS_DETAIL_IN_SHIPPING'),
		'field' => $this->lists['enable_address_detail_in_shipping']
	)
);

echo RedshopLayoutHelper::render(
	'config.config',
	array(
		'title' => JText::_('COM_REDSHOP_USE_PRODUCT_RESERVE_LBL'),
		'desc'  => JText::_('COM_REDSHOP_TOOLTIP_USE_PRODUCT_RESERVE_LBL'),
		'field' => $this->lists['is_product_reserve']
	)
);

echo RedshopLayoutHelper::render(
	'config.config',
	array(
		'title' => JText::_('COM_REDSHOP_CART_RESERVATION_MESSAGE_LBL'),
		'desc'  => JText::_('COM_REDSHOP_TOOLTIP_CART_RESERVATION_MESSAGE'),
		'line'  => false,
		'field' => '<textarea class="form-control" type="text" name="cart_reservation_message"'
			. 'id="cart_reservation_message" rows="4"'
			. 'cols="40"/>' . stripslashes($this->config->get('CART_RESERVATION_MESSAGE')) . '</textarea>'
	)
);
