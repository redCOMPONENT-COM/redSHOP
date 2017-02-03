<?php
/**
 * @package     RedSHOP.Backend
 * @subpackage  Template
 *
 * @copyright   Copyright (C) 2008 - 2016 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */
defined('_JEXEC') or die;

?>
<legend class="no-border text-danger"><?php echo JText::_('COM_REDSHOP_COMPARISON_SETTINGS'); ?></legend>
<?php
echo RedshopLayoutHelper::render(
	'config.config',
	array(
		'title' => JText::_('COM_REDSHOP_COMPARE_PRODUCTS_LBL'),
		'desc'  => JText::_('COM_REDSHOP_TOOLTIP_COMPARE_PRODUCTS'),
		'field' => $this->lists['compare_products']
	)
);
echo RedshopLayoutHelper::render(
	'config.config',
	array(
		'title' => JText::_('COM_REDSHOP_PRODUCT_COMPARE_LIMIT_LBL'),
		'desc'  => JText::_('COM_REDSHOP_TOOLTIP_PRODUCT_COMPARE_LIMIT_LBL'),
		'field' => '<input type="number" name="product_compare_limit" id="product_compare_limit" class="form-control"
					value="' . $this->config->get('PRODUCT_COMPARE_LIMIT') . '" />'
	)
);
echo RedshopLayoutHelper::render(
	'config.config',
	array(
		'title' => JText::_('COM_REDSHOP_PRODUCT_COMPARISON_TYPE_LBL'),
		'desc'  => JText::_('COM_REDSHOP_TOOLTIP_PRODUCT_COMPARISON_TYPE_LBL'),
		'field' => $this->lists['product_comparison_type']
	)
);
?>
<legend class="no-border text-danger"><?php echo JText::_('COM_REDSHOP_COMPARISON_LAYOUT'); ?></legend>
<?php
echo RedshopLayoutHelper::render(
	'config.config',
	array(
		'title' => JText::_('COM_REDSHOP_COMPARE_PRODUCT_TEMPLATE_LBL'),
		'desc'  => JText::_('COM_REDSHOP_TOOLTIP_COMPARE_PRODUCT_TEMPLATE'),
		'field' => $this->lists['compare_template_id']
	)
);
?>
<div class="row">
    <div class="form-group">
        <label class="col-md-4 hasPopover" data-content="<?php echo JText::_('COM_REDSHOP_TOOLTIP_COMPARE_PRODUCT_THUMB_WIDTH_LBL'); ?>">
			<?php echo JText::_('COM_REDSHOP_COMPARE_PRODUCT_THUMB_WIDTH_HEIGHT'); ?>
        </label>
        <div class="col-md-8">
            <div class="row">
                <div class="col-sm-6">
                    <input type="number" name="compare_product_thumb_width" id="compare_product_thumb_width" class="form-control"
                           value="<?php echo $this->config->get('COMPARE_PRODUCT_THUMB_WIDTH'); ?>"/>
                </div>
                <div class="col-sm-6">
                    <input type="number" name="compare_product_thumb_height" id="compare_product_thumb_height" class="form-control"
                           value="<?php echo $this->config->get('COMPARE_PRODUCT_THUMB_HEIGHT'); ?>"/>
                </div>
            </div>
        </div>
    </div>
</div>
