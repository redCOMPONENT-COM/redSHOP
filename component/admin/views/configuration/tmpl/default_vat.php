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
		'title' => JText::_('COM_REDSHOP_DEFAULT_VAT_COUNTRY_LBL'),
		'desc'  => JText::_('COM_REDSHOP_TOOLTIP_DEFAULT_VAT_COUNTRY'),
		'field' => $this->lists['default_vat_country']
	)
);
echo RedshopLayoutHelper::render(
	'config.config',
	array(
		'title' => JText::_('COM_REDSHOP_DEFAULT_VAT_STATE_LBL'),
		'desc'  => JText::_('COM_REDSHOP_TOOLTIP_DEFAULT_VAT_STATE'),
		'field' => $this->lists['default_vat_state']
	)
);
echo RedshopLayoutHelper::render(
	'config.config',
	array(
		'title' => JText::_('COM_REDSHOP_DEFAULT_VAT_GROUP_LBL'),
		'desc'  => JText::_('COM_REDSHOP_TOOLTIP_DEFAULT_VAT_GROUP'),
		'field' => $this->lists['default_vat_group']
	)
);
echo RedshopLayoutHelper::render(
	'config.config',
	array(
		'title' => JText::_('COM_REDSHOP_DEFAULT_VAT_CALCULATION_BASED_LBL'),
		'desc'  => JText::_('COM_REDSHOP_TOOLTIP_DEFAULT_VAT_CALCULATION_BASED'),
		'field' => $this->lists['vat_based_on']
	)
);
echo RedshopLayoutHelper::render(
	'config.config',
	array(
		'title' => JText::_('COM_REDSHOP_APPLY_VAT_ON_DISCOUNT_LBL'),
		'desc'  => JText::_('COM_REDSHOP_TOOLTIP_APPLY_VAT_ON_DISCOUNT'),
		'field' => $this->lists['apply_vat_on_discount']
	)
);
echo RedshopLayoutHelper::render(
	'config.config',
	array(
		'title' => JText::_('COM_REDSHOP_VAT_RATE_AFTER_DISCOUNT_LBL'),
		'desc'  => JText::_('COM_REDSHOP_TOOLTIP_VAT_RATE_AFTER_DISCOUNT_LBL'),
		'field' => '<input type="number" name="vat_rate_after_discount" id="vat_rate_after_discount" class="form-control"
            value="' . $this->config->get('VAT_RATE_AFTER_DISCOUNT') . '" />'
	)
);
echo RedshopLayoutHelper::render(
	'config.config',
	array(
		'title' => JText::_('COM_REDSHOP_CALCULATE_VAT_BASED_ON_LBL'),
		'desc'  => JText::_('COM_REDSHOP_TOOLTIP_CALCULATE_VAT_BASED_ON_LBL'),
		'field' => $this->lists['calculate_vat_on']
	)
);
echo RedshopLayoutHelper::render(
	'config.config',
	array(
		'title' => JText::_('COM_REDSHOP_REQUIRED_VAT_NUMBER_LBL'),
		'desc'  => JText::_('COM_REDSHOP_TOOLTIP_REQUIRED_VAT_NUMBER_LBL'),
		'field' => $this->lists['required_vat_number']
	)
);
echo RedshopLayoutHelper::render(
	'config.config',
	array(
		'title' => JText::_('COM_REDSHOP_VAT_INTRO_LBL'),
		'desc'  => JText::_('COM_REDSHOP_TOOLTIP_VAT_INTRO_LBL'),
		'field' => '<textarea class="form-control" type="text" name="vat_introtext" id="vat_introtext" rows="4" cols="40"/>'
			. stripslashes($this->config->get('VAT_INTROTEXT')) . '</textarea>'
	)
);
echo RedshopLayoutHelper::render(
	'config.config',
	array(
		'title' => JText::_('COM_REDSHOP_WITH_VAT_TEXT_INFO_LBL'),
		'desc'  => JText::_('COM_REDSHOP_TOOLTIP_WITH_VAT_TEXT_INFO'),
		'field' => '<textarea class="form-control" type="text" name="with_vat_text_info" id="with_vat_text_info" rows="4" cols="40"/>'
			. stripslashes($this->config->get('WITH_VAT_TEXT_INFO')) . '</textarea>'
	)
);
echo RedshopLayoutHelper::render(
	'config.config',
	array(
		'title' => JText::_('COM_REDSHOP_WITHOUT_VAT_TEXT_INFO_LBL'),
		'desc'  => JText::_('COM_REDSHOP_TOOLTIP_WITHOUT_VAT_TEXT_INFO'),
		'line'  => false,
		'field' => '<textarea class="form-control" type="text" name="without_vat_text_info" id="without_vat_text_info" rows="4" cols="40"/>'
			. stripslashes($this->config->get('WITHOUT_VAT_TEXT_INFO')) . '</textarea>'
	)
);
