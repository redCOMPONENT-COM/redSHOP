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
		'title' => JText::_('COM_REDSHOP_SHOW_PRICE_LBL'),
		'desc'  => JText::_('COM_REDSHOP_TOOLTIP_SHOW_PRICE_LBL'),
		'field' => $this->lists['show_price']
	)
);
echo RedshopLayoutHelper::render(
	'config.config',
	array(
		'title'  => JText::_('COM_REDSHOP_CURRENCY_NAME'),
		'desc'   => JText::_('COM_REDSHOP_TOOLTIP_CURRENCY_NAME'),
		'id'     => 'currency_data',
		'showOn' => 'show_price:1',
		'field'  => $this->lists['currency_data']
	)
);
echo RedshopLayoutHelper::render(
	'config.config',
	array(
		'title'  => JText::_('COM_REDSHOP_CURRENCY_SYMBOL'),
		'desc'   => JText::_('COM_REDSHOP_TOOLTIP_CURRENCY_SYMBOL'),
		'id'     => 'currency_symbol',
		'showOn' => 'show_price:1',
		'field'  => '<input type="text" name="currency_symbol" id="currency_symbol" class="form-control"
            value="' . $this->config->get('REDCURRENCY_SYMBOL') . '" />'
	)
);
echo RedshopLayoutHelper::render(
	'config.config',
	array(
		'title'  => JText::_('COM_REDSHOP_CURRENCY_SYMBOL_POSITION_LBL'),
		'desc'   => JText::_('COM_REDSHOP_TOOLTIP_CURRENCY_SYMBOL_POSITION'),
		'id'     => 'currency_symbol_position',
		'showOn' => 'show_price:1',
		'field'  => $this->lists['currency_symbol_position']
	)
);
echo RedshopLayoutHelper::render(
	'config.config',
	array(
		'title'  => JText::_('COM_REDSHOP_PRICE_SEPERATOR_LBL'),
		'desc'   => JText::_('COM_REDSHOP_TOOLTIP_PRICE_SEPERATOR_LBL'),
		'id'     => 'price_seperator',
		'showOn' => 'show_price:1',
		'field'  => '<input type="text" name="price_seperator" id="price_seperator" class="form-control"
            value="' . $this->config->get('PRICE_SEPERATOR') . '" />'
	)
);
echo RedshopLayoutHelper::render(
	'config.config',
	array(
		'title'  => JText::_('COM_REDSHOP_THOUSAND_SEPERATOR_LBL'),
		'desc'   => JText::_('COM_REDSHOP_TOOLTIP_THOUSAND_SEPERATOR_LBL'),
		'id'     => 'thousand_seperator',
		'showOn' => 'show_price:1',
		'field'  => '<input type="text" name="thousand_seperator" id="thousand_seperator" class="form-control"
            value="' . $this->config->get('THOUSAND_SEPERATOR') . '" />'
	)
);
echo RedshopLayoutHelper::render(
	'config.config',
	array(
		'title'  => JText::_('COM_REDSHOP_PRICE_DECIMAL_LBL'),
		'desc'   => JText::_('COM_REDSHOP_TOOLTIP_PRICE_DECIMAL_LBL'),
		'id'     => 'price_decimal',
		'showOn' => 'show_price:1',
		'field'  => '<input type="number" name="price_decimal" id="price_decimal" class="form-control"
            value="' . $this->config->get('PRICE_DECIMAL') . '" />'
	)
);
echo RedshopLayoutHelper::render(
	'config.config',
	array(
		'title'  => JText::_('COM_REDSHOP_CALCULATION_PRICE_DECIMAL_LBL'),
		'desc'   => JText::_('COM_REDSHOP_TOOLTIP_CALCULATION_PRICE_DECIMAL'),
		'id'     => 'calculation_price_decimal',
		'showOn' => 'show_price:1',
		'field'  => '<input type="number" name="calculation_price_decimal" id="calculation_price_decimal" class="form-control"
            value="' . $this->config->get('CALCULATION_PRICE_DECIMAL') . '" />'
	)
);
echo RedshopLayoutHelper::render(
	'config.config',
	array(
		'title' => JText::_('COM_REDSHOP_USE_TAX_EXEMPT_LBL'),
		'desc'  => JText::_('COM_REDSHOP_TOOLTIP_USE_TAX_EXEMPT_LBL'),
		'field' => $this->lists['use_tax_exempt']
	)
);
echo RedshopLayoutHelper::render(
	'config.config',
	array(
		'title'  => JText::_('COM_REDSHOP_SHOW_TAX_EXEMPT_INFRONT_LBL'),
		'desc'   => JText::_('COM_REDSHOP_TOOLTIP_SHOW_TAX_EXEMPT_INFRONT_LBL'),
		'id'     => 'show_tax_exempt_infront',
		'showOn' => 'use_tax_exempt:1',
		'field'  => $this->lists['show_tax_exempt_infront']
	)
);
echo RedshopLayoutHelper::render(
	'config.config',
	array(
		'title'  => JText::_('COM_REDSHOP_TAX_EXEMPT_APPLY_VAT_LBL'),
		'desc'   => JText::_('COM_REDSHOP_TOOLTIP_TAX_EXEMPT_APPLY_VAT_LBL'),
		'id'     => 'tax_exempt_apply_vat',
		'showOn' => 'use_tax_exempt:1',
		'field'  => $this->lists['tax_exempt_apply_vat']
	)
);
echo RedshopLayoutHelper::render(
	'config.config',
	array(
		'title' => JText::_('COM_REDSHOP_USE_AS_CATALOG_LBL'),
		'desc'  => JText::_('COM_REDSHOP_TOOLTIP_USE_AS_CATALOG_LBL'),
		'line'  => false,
		'field' => $this->lists['use_as_catalog']
	)
);
?>
<script type='text/javascript'>
    function changeRedshopCurrencyList(obj) {
        var x = (obj.value || obj.options[obj.selectedIndex].value);

        jQuery('#currency_symbol').val(x);
    }
</script>
