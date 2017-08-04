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
		'title' => JText::_('COM_REDSHOP_CAT_IS_LIGHTBOX'),
		'desc'  => JText::_('COM_REDSHOP_TOOLTIP_CAT_IS_LIGHTBOX'),
		'field' => $this->lists['cat_is_lightbox']
	)
);
echo RedshopLayoutHelper::render(
	'config.config',
	array(
		'title' => JText::_('COM_REDSHOP_TOOLTIP_WATERMARK_CATEGORY_IMAGE_LBL'),
		'desc'  => JText::_('COM_REDSHOP_TOOLTIP_WATERMARK_CATEGORY_IMAGE'),
		'field' => $this->lists['watermark_category_image']
	)
);
echo RedshopLayoutHelper::render(
	'config.config',
	array(
		'title' => JText::_('COM_REDSHOP_TOOLTIP_WATERMARK_CATEGORY_THUMB_IMAGE_LBL'),
		'desc'  => JText::_('COM_REDSHOP_TOOLTIP_WATERMARK_CATEGORY_THUMB_IMAGE'),
		'field' => $this->lists['watermark_category_thumb_image']
	)
);
?>
<div class="form-group row-fluid">
    <label class="col-md-4">
		<?php echo JText::_('COM_REDSHOP_CATEGORY_THUMB_WIDTH_HEIGHT'); ?>
    </label>
    <div class="col-md-8">
        <div class="row">
            <div class="col-md-6">
                <input type="number" class="form-control" name="thumb_width" id="thumb_width"
                       value="<?php echo $this->config->get('THUMB_WIDTH'); ?>"/>
            </div>
            <div class="col-md-6">
                <input type="number" class="form-control" name="thumb_height" id="thumb_height"
                       value="<?php echo $this->config->get('THUMB_HEIGHT'); ?>"/>
            </div>
        </div>
    </div>
</div>
<div class="form-group row-fluid">
    <label class="col-md-4">
		<?php echo JText::_('COM_REDSHOP_CATEGORY_THUMB_WIDTH_HEIGHT_TWO'); ?>
    </label>
    <div class="col-md-8">
        <div class="row">
            <div class="col-md-6">
                <input type="number" class="form-control" name="thumb_width_2" id="thumb_width_2"
                       value="<?php echo $this->config->get('THUMB_WIDTH_2'); ?>"/>
            </div>
            <div class="col-md-6">
                <input type="number" class="form-control" name="thumb_height_2" id="thumb_height_2"
                       value="<?php echo $this->config->get('THUMB_HEIGHT_2'); ?>"/>
            </div>
        </div>
    </div>
</div>
<div class="form-group row-fluid">
    <label class="col-md-4">
		<?php echo JText::_('COM_REDSHOP_CATEGORY_THUMB_WIDTH_HEIGHT_THREE'); ?>
    </label>
    <div class="col-md-8">
        <div class="row">
            <div class="col-md-6">
                <input type="number" class="form-control" name="thumb_width_3" id="thumb_width_3"
                       value="<?php echo $this->config->get('THUMB_WIDTH_3'); ?>"/>
            </div>
            <div class="col-md-6">
                <input type="number" class="form-control" name="thumb_height_3" id="thumb_height_3"
                       value="<?php echo $this->config->get('THUMB_HEIGHT_3'); ?>"/>
            </div>
        </div>
    </div>
</div>
<div class="form-group row-fluid">
    <label class="col-md-4">
		<?php echo JText::_('COM_REDSHOP_CATEGORY_PRODUCT_THUMB_WIDTH_HEIGHT_LBL'); ?>
    </label>
    <div class="col-md-8">
        <div class="row">
            <div class="col-md-6">
                <input type="number" class="form-control" name="category_product_thumb_width" id="category_product_thumb_width"
                       value="<?php echo $this->config->get('CATEGORY_PRODUCT_THUMB_WIDTH'); ?>"/>
            </div>
            <div class="col-md-6">
                <input type="number" class="form-control" name="category_product_thumb_height" id="category_product_thumb_height"
                       value="<?php echo $this->config->get('CATEGORY_PRODUCT_THUMB_HEIGHT'); ?>"/>
            </div>
        </div>
    </div>
</div>
<div class="form-group row-fluid">
    <label class="col-md-4">
		<?php echo JText::_('COM_REDSHOP_CATEGORY_PRODUCT_THUMB_WIDTH_HEIGHT_TWO'); ?>
    </label>
    <div class="col-md-8">
        <div class="row">
            <div class="col-md-6">
                <input type="number" class="form-control" name="category_product_thumb_width_2" id="category_product_thumb_width_2"
                       value="<?php echo $this->config->get('CATEGORY_PRODUCT_THUMB_WIDTH_2'); ?>"/>
            </div>
            <div class="col-md-6">
                <input type="number" class="form-control" name="category_product_thumb_height_2" id="category_product_thumb_height_2"
                       value="<?php echo $this->config->get('CATEGORY_PRODUCT_THUMB_HEIGHT_2'); ?>"/>
            </div>
        </div>
    </div>
</div>
<div class="form-group row-fluid">
    <label class="col-md-4">
		<?php echo JText::_('COM_REDSHOP_CATEGORY_PRODUCT_THUMB_WIDTH_HEIGHT_THREE'); ?>
    </label>
    <div class="col-md-8">
        <div class="row">
            <div class="col-md-6">
                <input type="number" class="form-control" name="category_product_thumb_width_3" id="category_product_thumb_width_3"
                       value="<?php echo $this->config->get('CATEGORY_PRODUCT_THUMB_WIDTH_3'); ?>"/>
            </div>
            <div class="col-md-6">
                <input type="number" class="form-control" name="category_product_thumb_height_3" id="category_product_thumb_height_3"
                       value="<?php echo $this->config->get('CATEGORY_PRODUCT_THUMB_HEIGHT_3'); ?>"/>
            </div>
        </div>
    </div>
</div>
