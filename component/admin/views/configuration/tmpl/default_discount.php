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
		'title' => JText::_('COM_REDSHOP_DISCOUNT_ENABLE_LBL'),
		'desc'  => JText::_('COM_REDSHOP_TOOLTIP_DISCOUNT_ENABLE_LBL'),
		'field' => $this->lists['discount_enable']
	)
);
echo RedshopLayoutHelper::render(
	'config.config',
	array(
		'title'  => JText::_('COM_REDSHOP_DISCOUNT_TYPE_LBL'),
		'desc'   => JText::_('COM_REDSHOP_DISCOUNT_TYPE_LBL'),
		'field'  => $this->lists['discount_type'],
		'id'     => 'discount_type',
		'showOn' => 'discount_enable:1'
	)
);
echo RedshopLayoutHelper::render(
	'config.config',
	array(
		'title'  => JText::_('COM_REDSHOP_COUPONS_ENABLE_LBL'),
		'desc'   => JText::_('COM_REDSHOP_TOOLTIP_COUPONS_ENABLE_LBL'),
		'field'  => $this->lists['coupons_enable'],
		'id'     => 'coupons_enable',
		'showOn' => 'discount_enable:1'
	)
);
echo RedshopLayoutHelper::render(
	'config.config',
	array(
		'title'  => JText::_('COM_REDSHOP_COUPON_INFO_LBL'),
		'desc'   => JText::_('COM_REDSHOP_TOOLTIP_COUPON_INFO_LBL'),
		'field'  => $this->lists['couponinfo'],
		'id'     => 'couponinfo',
		'showOn' => 'discount_enable:1'
	)
);
echo RedshopLayoutHelper::render(
	'config.config',
	array(
		'title'  => JText::_('COM_REDSHOP_VOUCHERS_ENABLE_LBL'),
		'desc'   => JText::_('COM_REDSHOP_TOOLTIP_VOUCHERS_ENABLE_LBL'),
		'field'  => $this->lists['vouchers_enable'],
		'id'     => 'vouchers_enable',
		'showOn' => 'discount_enable:1'
	)
);
echo RedshopLayoutHelper::render(
	'config.config',
	array(
		'title'  => JText::_('COM_REDSHOP_SPECIAL_DISCOUNT_MAIL_SEND_LBL'),
		'desc'   => JText::_('COM_REDSHOP_TOOLTIP_SPECIAL_DISCOUNT_MAIL_SEND_LBL'),
		'field'  => $this->lists['special_discount_mail_send'],
		'id'     => 'special_discount_mail_send',
		'showOn' => 'discount_enable:1'
	)
);
echo RedshopLayoutHelper::render(
	'config.config',
	array(
		'title'  => JText::_('COM_REDSHOP_APPLY_VOUCHER_COUPON_ALREADY_DISCOUNT_LBL'),
		'desc'   => JText::_('COM_REDSHOP_TOOLTIP_APPLY_VOUCHER_COUPON_ALREADY_DISCOUNT_LBL'),
		'field'  => $this->lists['apply_voucher_coupon_already_discount'],
		'id'     => 'apply_voucher_coupon_already_discount',
		'showOn' => 'discount_enable:1'
	)
);
echo RedshopLayoutHelper::render(
	'config.config',
	array(
		'title'  => JText::_('COM_REDSHOP_SHIPPING_AFTER_LBL'),
		'desc'   => JText::_('COM_REDSHOP_TOOLTIP_SHIPPING_AFTER'),
		'field'  => $this->lists['shipping_after'],
		'id'     => 'shipping_after',
		'showOn' => 'discount_enable:1'
	)
);
echo RedshopLayoutHelper::render(
	'config.config',
	array(
		'title'  => JText::_('COM_REDSHOP_DISCOUNT_PERCENT_OR_TOTAL_LBL'),
		'desc'   => JText::_('COM_REDSHOP_TOOLTIP_DISCOUNT_PERCENT_OR_TOTAL'),
		'field'  => $this->lists['discoupon_percent_or_total'],
		'id'     => 'discoupon_percent_or_total',
		'showOn' => 'discount_enable:1'
	)
);
echo RedshopLayoutHelper::render(
	'config.config',
	array(
		'title'  => JText::_('COM_REDSHOP_DISCOUNT_COUPON_VALUE_LBL'),
		'desc'   => JText::_('COM_REDSHOP_TOOLTIP_DISCOUNT_COUPON_VALUE_LBL'),
		'line'   => false,
		'field'  => '<input type="number" class="form-control" name="discoupon_value" id="discoupon_value"
		        value="' . $this->config->get('DISCOUPON_VALUE') . '" />',
		'id'     => 'discoupon_value',
		'showOn' => 'discount_enable:1'
	)
);
