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
		'title' => JText::_('COM_REDSHOP_CATALOG_REMAINDER_1_LBL'),
		'desc'  => JText::_('COM_REDSHOP_TOOLTIP_CATALOG_REMAINDER_1'),
		'field' => '<input type="number" name="catalog_reminder_1" id="catalog_reminder_1" class="form-control"'
			. ' value="' . $this->config->get('CATALOG_REMINDER_1') . '" />'
	)
);
echo RedshopLayoutHelper::render(
	'config.config',
	array(
		'title' => JText::_('COM_REDSHOP_CATALOG_REMAINDER_2_LBL'),
		'desc'  => JText::_('COM_REDSHOP_TOOLTIP_CATALOG_REMAINDER_2'),
		'field' => '<input type="number" name="catalog_reminder_2" id="catalog_reminder_2" class="form-control"'
			. ' value="' . $this->config->get('CATALOG_REMINDER_2') . '" />'
	)
);
echo RedshopLayoutHelper::render(
	'config.config',
	array(
		'title' => JText::_('COM_REDSHOP_DISCOUNT_DURATION_LBL'),
		'desc'  => JText::_('COM_REDSHOP_TOOLTIP_DISCOUNT_DURATION'),
		'field' => '<input type="number" name="discount_duration" id="discount_duration" class="form-control"'
			. ' value="' . $this->config->get('DISCOUNT_DURATION') . '" />'
	)
);
echo RedshopLayoutHelper::render(
	'config.config',
	array(
		'title' => JText::_('COM_REDSHOP_DISCOUNT_PERCENTAGE_LBL'),
		'desc'  => JText::_('COM_REDSHOP_TOOLTIP_DISCOUNT_PERCENTAGE'),
		'field' => '<input type="number" name="discount_percentage" id="discount_percentage" class="form-control"'
			. ' value="' . $this->config->get('DISCOUNT_PERCENTAGE') . '" />'
	)
);
echo '<div class="hidden">' . RedshopLayoutHelper::render(
	'config.config',
	array(
		'title' => JText::_('COM_REDSHOP_CATALOG_DAYS_LBL'),
		'desc'  => JText::_('COM_REDSHOP_TOOLTIP_CATALOG_DAYS_LBL'),
		'field' => '<input type="number" name="catalog_days" id="catalog_days" class="form-control"'
			. ' value="' . $this->config->get('CATALOG_DAYS') . '" />'
	)
) . '</div>';
echo RedshopLayoutHelper::render(
	'config.config',
	array(
		'title' => JText::_('COM_REDSHOP_SEND_CATALOG_REMINDER_MAIL_LBL'),
		'desc'  => JText::_('COM_REDSHOP_TOOLTIP_SEND_CATALOG_REMINDER_MAI_LBL'),
		'line'  => false,
		'field' => $this->lists['send_catalog_reminder_mail']
	)
);
