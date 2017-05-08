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
		'title' => JText::_('COM_REDSHOP_WATERMARK_MANUFACTURER_IMAGE_LBL'),
		'desc'  => JText::_('COM_REDSHOP_TOOLTIP_WATERMARK_MANUFACTURER_IMAGE'),
		'field' => $this->lists['watermark_manufacturer_image']
	)
);
echo RedshopLayoutHelper::render(
	'config.config',
	array(
		'title' => JText::_('COM_REDSHOP_WATERMARK_MANUFACTURER_THUMB_IMAGE_LBL'),
		'desc'  => JText::_('COM_REDSHOP_TOOLTIP_WATERMARK_MANUFACTURER_THUMB_IMAGE'),
		'field' => $this->lists['watermark_manufacturer_thumb_image']
	)
);
?>
<div class="form-group row-fluid">
    <label class="col-md-4 hasPopover" data-content="<?php echo JText::_('COM_REDSHOP_TOOLTIP_MANUFACTURER_THUMB_WIDTH') ?>">
		<?php echo JText::_('COM_REDSHOP_MANUFACTURER_THUMB_WIDTH_HEIGHT'); ?>
    </label>
    <div class="col-md-8">
        <div class="row">
            <div class="col-md-6">
                <input type="number" class="form-control" name="manufacturer_thumb_width" id="manufacturer_thumb_width"
                       value="<?php echo $this->config->get('MANUFACTURER_THUMB_WIDTH'); ?>"/>
            </div>
            <div class="col-md-6">
                <input type="number" class="form-control" name="manufacturer_thumb_height" id="manufacturer_thumb_height"
                       value="<?php echo $this->config->get('MANUFACTURER_THUMB_HEIGHT'); ?>"/>
            </div>
        </div>
    </div>
</div>
<div class="form-group row-fluid">
    <label class="col-md-4 hasPopover" data-content="<?php echo JText::_('COM_REDSHOP_TOOLTIP_MANUFACTURER_PRODUCT_THUMB_WIDTH_LBL') ?>">
		<?php echo JText::_('COM_REDSHOP_TOOLTIP_MANUFACTURER_PRODUCT_THUMB_WIDTH'); ?>
    </label>
    <div class="col-md-8">
        <div class="row">
            <div class="col-md-6">
                <input type="number" class="form-control" name="manufacturer_product_thumb_width" id="manufacturer_product_thumb_width"
                       value="<?php echo $this->config->get('MANUFACTURER_PRODUCT_THUMB_WIDTH'); ?>"/>
            </div>
            <div class="col-md-6">
                <input type="number" class="form-control" name="manufacturer_product_thumb_height" id="manufacturer_product_thumb_height"
                       value="<?php echo $this->config->get('MANUFACTURER_PRODUCT_THUMB_HEIGHT'); ?>"/>
            </div>
        </div>
    </div>
</div>
<div class="form-group row-fluid">
    <label class="col-md-4 hasPopover" data-content="<?php echo JText::_('COM_REDSHOP_TOOLTIP_MANUFACTURER_PRODUCT_THUMB_WIDTH_LBL_TWO_LBL') ?>">
		<?php echo JText::_('COM_REDSHOP_MANUFACTURER_PRODUCT_THUMB_WIDTH_HEIGHT_TWO'); ?>
    </label>
    <div class="col-md-8">
        <div class="row">
            <div class="col-md-6">
                <input type="number" class="form-control" name="manufacturer_product_thumb_width_2" id="manufacturer_product_thumb_width_2"
                       value="<?php echo $this->config->get('MANUFACTURER_PRODUCT_THUMB_WIDTH_2'); ?>"/>
            </div>
            <div class="col-md-6">
                <input type="number" class="form-control" name="manufacturer_product_thumb_height_2" id="manufacturer_product_thumb_height_2"
                       value="<?php echo $this->config->get('MANUFACTURER_PRODUCT_THUMB_HEIGHT_2'); ?>"/>
            </div>
        </div>
    </div>
</div>
<div class="form-group row-fluid">
    <label class="col-md-4 hasPopover" data-content="<?php echo JText::_('COM_REDSHOP_TOOLTIP_MANUFACTURER_PRODUCT_THUMB_WIDTH_THREE') ?>">
		<?php echo JText::_('COM_REDSHOP_MANUFACTURER_PRODUCT_THUMB_WIDTH_LBL_THREE'); ?>
    </label>
    <div class="col-md-8">
        <div class="row">
            <div class="col-md-6">
                <input type="number" class="form-control" name="manufacturer_product_thumb_width_3" id="manufacturer_product_thumb_width_3"
                       value="<?php echo $this->config->get('MANUFACTURER_PRODUCT_THUMB_WIDTH_3'); ?>"/>
            </div>
            <div class="col-md-6">
                <input type="number" class="form-control" name="manufacturer_product_thumb_height_3" id="manufacturer_product_thumb_height_3"
                       value="<?php echo $this->config->get('MANUFACTURER_PRODUCT_THUMB_HEIGHT_3'); ?>"/>
            </div>
        </div>
    </div>
</div>
