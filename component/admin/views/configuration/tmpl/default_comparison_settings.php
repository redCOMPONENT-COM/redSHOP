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
		'title' => JText::_('COM_REDSHOP_COMPARE_PRODUCTS_LBL'),
		'desc'  => JText::_('COM_REDSHOP_TOOLTIP_COMPARE_PRODUCTS'),
		'field' => $this->lists['compare_products']
	)
);
echo RedshopLayoutHelper::render(
	'config.config',
	array(
		'title'  => JText::_('COM_REDSHOP_PRODUCT_COMPARE_LIMIT_LBL'),
		'desc'   => JText::_('COM_REDSHOP_TOOLTIP_PRODUCT_COMPARE_LIMIT_LBL'),
		'field'  => '<input type="number" name="product_compare_limit" id="product_compare_limit" class="form-control"
					value="' . $this->config->get('PRODUCT_COMPARE_LIMIT') . '" />',
		'id'     => 'product_compare_limit',
		'showOn' => 'compare_products:1'
	)
);
echo RedshopLayoutHelper::render(
	'config.config',
	array(
		'title'  => JText::_('COM_REDSHOP_PRODUCT_COMPARISON_TYPE_LBL'),
		'desc'   => JText::_('COM_REDSHOP_TOOLTIP_PRODUCT_COMPARISON_TYPE_LBL'),
		'field'  => $this->lists['product_comparison_type'],
		'id'     => 'product_comparison_type',
		'showOn' => 'compare_products:1'
	)
);
echo RedshopLayoutHelper::render(
	'config.config',
	array(
		'title'  => JText::_('COM_REDSHOP_COMPARE_PRODUCT_TEMPLATE_LBL'),
		'desc'   => JText::_('COM_REDSHOP_TOOLTIP_COMPARE_PRODUCT_TEMPLATE'),
		'field'  => $this->lists['compare_template_id'],
		'id'     => 'compare_template_id',
		'showOn' => 'compare_products:1'
	)
);
?>
<script type="text/javascript">
    (function ($) {
        $(document).ready(function () {
            rsConfigShowOn("compare_products", "1", "compare_product_thumb_width_height-wrapper");
        });
    })(jQuery);
</script>
<div class="form-group row-fluid" id="compare_product_thumb_width_height-wrapper">
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
