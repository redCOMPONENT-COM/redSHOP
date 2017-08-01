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
		'title' => JText::_('COM_REDSHOP_DEFAULT_MANUFACTURER_TEMPLATE_LBL'),
		'desc'  => JText::_('COM_REDSHOP_TOOLTIP_DEFAULT_MANUFACTURER_TEMPLATE_FOR_VM_LBL'),
		'field' => $this->lists['manufacturer_template']
	)
);
echo RedshopLayoutHelper::render(
	'config.config',
	array(
		'title' => JText::_('COM_REDSHOP_DEFAULT_MANUFACTURER_ORDERING_METHOD_LBL'),
		'desc'  => JText::_('COM_REDSHOP_TOOLTIP_DEFAULT_MANUFACTURER_ORDERING_METHOD_LBL'),
		'field' => $this->lists['default_manufacturer_ordering_method']
	)
);
echo RedshopLayoutHelper::render(
	'config.config',
	array(
		'title' => JText::_('COM_REDSHOP_DEFAULT_MANUFACTURER_PRODUCT_ORDERING_METHOD_LBL'),
		'desc'  => JText::_('COM_REDSHOP_TOOLTIP_DEFAULT_MANUFACTURER_PRODUCT_ORDERING_METHOD_LBL'),
		'field' => $this->lists['default_manufacturer_product_ordering_method']
	)
);
echo RedshopLayoutHelper::render(
	'config.config',
	array(
		'title' => JText::_('COM_REDSHOP_MANUFACTURER_MAX_CHARS_LBL'),
		'desc'  => JText::_('COM_REDSHOP_TOOLTIP_MANUFACTURER_TITLE_MAX_CHARS_LBL'),
		'field' => '<input type="number" name="manufacturer_title_max_chars" id="manufacturer_title_max_chars" class="form-control"
                   value="' . $this->config->get('MANUFACTURER_TITLE_MAX_CHARS') . '"/>'
	)
);
echo RedshopLayoutHelper::render(
	'config.config',
	array(
		'title' => JText::_('COM_REDSHOP_MANUFACTURER_TITLE_END_SUFFIX_LBL'),
		'desc'  => JText::_('COM_REDSHOP_TOOLTIP_MANUFACTURER_TITLE_END_SUFFIX_LBL'),
		'field' => '<input type="text" name="manufacturer_title_end_suffix" id="manufacturer_title_end_suffix" class="form-control"
                   value="' . $this->config->get('MANUFACTURER_TITLE_END_SUFFIX') . '"/>'
	)
);
echo RedshopLayoutHelper::render(
	'config.config',
	array(
		'title' => JText::_('COM_REDSHOP_ENABLE_MANUFACTURER_EMAIL_LBL'),
		'desc'  => JText::_('COM_REDSHOP_TOOLTIP_ENABLE_MANUFACTURER_EMAIL_LBL'),
		'field' => $this->lists['manufacturer_mail_enable']
	)
);
echo RedshopLayoutHelper::render(
	'config.config',
	array(
		'title' => JText::_('COM_REDSHOP_ENABLE_SUPPLIER_EMAIL_LBL'),
		'desc'  => JText::_('COM_REDSHOP_TOOLTIP_ENABLE_SUPPLIER_EMAIL_LBL'),
		'field' => $this->lists['supplier_mail_enable'],
		'line'  => false
	)
);
