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
		'title' => JText::_('COM_REDSHOP_DISCOUNT_MAIL_SEND_LBL'),
		'desc'  => JText::_('COM_REDSHOP_TOOLTIP_DISCOUNT_MAIL_SEND_LBL'),
		'field' => $this->lists['discount_mail_send']
	)
);
echo RedshopLayoutHelper::render(
	'config.config',
	array(
		'title'  => JText::_('COM_REDSHOP_MAIL1_AFTER_ORDER_LBL'),
		'desc'   => JText::_('COM_REDSHOP_TOOLTIP_MAIL1_AFTER_ORDER_LBL'),
		'field'  => '<input type="number" class="form-control" name="days_mail1" id="days_mail1" value="' . $this->config->get('DAYS_MAIL1') . '" />',
		'id'     => 'days_mail1',
		'showOn' => 'discount_mail_send:1'
	)
);
echo RedshopLayoutHelper::render(
	'config.config',
	array(
		'title'  => JText::_('COM_REDSHOP_MAIL2_AFTER_ORDER_LBL'),
		'desc'   => JText::_('COM_REDSHOP_TOOLTIP_MAIL2_AFTER_ORDER_LBL'),
		'field'  => '<input type="number" class="form-control" name="days_mail2" id="days_mail2" value="' . $this->config->get('DAYS_MAIL2') . '" />',
		'id'     => 'days_mail2',
		'showOn' => 'discount_mail_send:1'
	)
);
echo RedshopLayoutHelper::render(
	'config.config',
	array(
		'title'  => JText::_('COM_REDSHOP_MAIL3_AFTER_ORDER_LBL'),
		'desc'   => JText::_('COM_REDSHOP_TOOLTIP_MAIL3_AFTER_ORDER_LBL'),
		'field'  => '<input type="number" class="form-control" name="days_mail3" id="days_mail3" value="' . $this->config->get('DAYS_MAIL3') . '" />',
		'id'     => 'days_mail3',
		'showOn' => 'discount_mail_send:1'
	)
);
echo RedshopLayoutHelper::render(
	'config.config',
	array(
		'title'  => JText::_('COM_REDSHOP_DISCOUNT_COUPON_DURATION_LBL'),
		'desc'   => JText::_('COM_REDSHOP_TOOLTIP_DISCOUNT_COUPON_DURATION'),
		'field'  => '<input type="number" class="form-control" name="discoupon_duration" id="discoupon_duration"
		        value="' . $this->config->get('DISCOUPON_DURATION') . '" />',
		'id'     => 'discoupon_duration',
		'showOn' => 'discount_mail_send:1'
	)
);
