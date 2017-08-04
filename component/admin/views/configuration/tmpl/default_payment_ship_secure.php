<?php
/**
 * @package     RedSHOP.Backend
 * @subpackage  Template
 *
 * @copyright   Copyright (C) 2008 - 2017 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */
defined('_JEXEC') or die;

?>
    <legend class="no-border text-danger"><?php echo JText::_('COM_REDSHOP_PAYMENT_SETTINGS'); ?></legend>
<?php
echo RedshopLayoutHelper::render(
	'config.config',
	array(
		'title' => JText::_('COM_REDSHOP_PAYMENT_CALCULATION_ON_LBL'),
		'desc'  => JText::_('COM_REDSHOP_TOOLTIP_PAYMENT_CALCULATION_ON'),
		'field' => $this->lists['payment_calculation_on']
	)
);
?>
    <legend class="no-border text-danger"><?php echo JText::_('COM_REDSHOP_SHIPPING_SETTINGS') ?></legend>
<?php
echo RedshopLayoutHelper::render(
	'config.config',
	array(
		'title' => JText::_('COM_REDSHOP_OPTIONAL_SHIPPING_ADDRESS_LBL'),
		'desc'  => JText::_('COM_REDSHOP_TOOLTIP_OPTIONAL_SHIPPING_ADDRESS'),
		'field' => $this->lists['optional_shipping_address']
	)
);
echo RedshopLayoutHelper::render(
	'config.config',
	array(
		'title' => JText::_('COM_REDSHOP_SHIPPING_METHOD_ENABLE_LBL'),
		'desc'  => JText::_('COM_REDSHOP_TOOLTIP_SHIPPING_METHOD_ENABLE'),
		'field' => $this->lists['shipping_method_enable']
	)
);
echo RedshopLayoutHelper::render(
	'config.config',
	array(
		'title' => JText::_('COM_REDSHOP_SPLIT_DELIVERY_COST'),
		'desc'  => JText::_('COM_REDSHOP_TOOLTIP_SPLIT_DELIVERY_COST'),
		'field' => '<input type="number" name="split_delivery_cost" id="split_delivery_cost" class="form-control"
            value="' . $this->config->get('SPLIT_DELIVERY_COST') . '" />'
	)
);
echo RedshopLayoutHelper::render(
	'config.config',
	array(
		'title' => JText::_('COM_REDSHOP_TIME_DIFF_SPILT_CALCULATION'),
		'desc'  => JText::_('COM_REDSHOP_TOOLTIP_TIME_DIFF_SPILT_CALCULATION'),
		'field' => '<input type="number" name="time_diff_split_delivery" id="time_diff_split_delivery" class="form-control"
            value="' . $this->config->get('TIME_DIFF_SPLIT_DELIVERY') . '" />'
	)
);
echo RedshopLayoutHelper::render(
	'config.config',
	array(
		'title' => JText::_('COM_REDSHOP_DELIVERY_RULE'),
		'desc'  => JText::_('COM_REDSHOP_TOOLTIP_DELIVERY_RULE'),
		'field' => '<input type="number" name="delivery_rule" id="delivery_rule" class="form-control"
            value="' . $this->config->get('DELIVERY_RULE') . '" />'
	)
);
?>
    <legend class="no-border text-danger"><?php echo JText::_('COM_REDSHOP_SECURING_SETTINGS') ?></legend>
<?php
echo RedshopLayoutHelper::render(
	'config.config',
	array(
		'title' => JText::_('COM_REDSHOP_SSL_ENABLE_IN_CHECKOUT_LBL'),
		'desc'  => JText::_('COM_REDSHOP_TOOLTIP_SSL_ENABLE_IN_CHECKOUT_LBL'),
		'field' => $this->lists['ssl_enable_in_checkout']
	)
);
echo RedshopLayoutHelper::render(
	'config.config',
	array(
		'title' => JText::_('COM_REDSHOP_SSL_ENABLE_IN_BACKEND_LBL'),
		'desc'  => JText::_('COM_REDSHOP_TOOLTIP_SSL_ENABLE_IN_BACKEND'),
		'field' => $this->lists['ssl_enable_in_backend'],
		'line'  => false
	)
);
