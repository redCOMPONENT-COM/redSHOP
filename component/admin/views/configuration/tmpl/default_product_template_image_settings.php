<?php
/**
 * @package     RedSHOP.Backend
 * @subpackage  Template
 *
 * @copyright   Copyright (C) 2008 - 2017 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */
defined('_JEXEC') or die;

JHtmlBehavior::modal('.joom-box');
$ord_path = "/components/com_redshop/assets/images/";

echo RedshopLayoutHelper::render(
	'config.config',
	array(
		'title' => JText::_('COM_REDSHOP_DEFAULT_PRODUCT_TEMPLATE_LBL'),
		'desc'  => JText::_('COM_REDSHOP_TOOLTIP_DEFAULT_PRODUCT_TEMPLATE_FOR_VM_LBL'),
		'field' => $this->lists['product_template']
	)
);
echo RedshopLayoutHelper::render(
	'config.config',
	array(
		'title' => JText::_('COM_REDSHOP_DEFAULT_PRODUCT_ORDERING_METHOD_LBL'),
		'desc'  => JText::_('COM_REDSHOP_TOOLTIP_DEFAULT_PRODUCT_ORDERING_METHOD_LBL'),
		'field' => $this->lists['default_product_ordering_method']
	)
);
echo RedshopLayoutHelper::render(
	'config.config',
	array(
		'title' => JText::_('COM_REDSHOP_DISPLAY_OUT_OF_STOCK_ATTRIBUTE_DATA'),
		'desc'  => JText::_('COM_REDSHOP_DISPLAY_OUT_OF_STOCK_ATTRIBUTE_DATA'),
		'field' => $this->lists['display_out_of_stock_attribute_data']
	)
);
?>
<div class="form-group row-fluid">
    <legend class="no-border text-danger"><?php echo JText::_('COM_REDSHOP_IMAGE_SETTINGS'); ?></legend>
</div>
<?php
echo RedshopLayoutHelper::render(
	'config.config',
	array(
		'title' => JText::_('COM_REDSHOP_PRODUCT_IS_LIGHTBOX_LBL'),
		'desc'  => JText::_('COM_REDSHOP_TOOLTIP_PRODUCT_IS_LIGHTBOX'),
		'field' => $this->lists['product_is_lightbox']
	)
);
echo RedshopLayoutHelper::render(
	'config.config',
	array(
		'title' => JText::_('COM_REDSHOP_PRODUCT_DETAIL_IS_LIGHTBOX_LBL'),
		'desc'  => JText::_('COM_REDSHOP_TOOLTIP_PRODUCT_DETAIL_IS_LIGHTBOX'),
		'field' => $this->lists['product_detail_is_lightbox']
	)
);
echo RedshopLayoutHelper::render(
	'config.config',
	array(
		'title' => JText::_('COM_REDSHOP_PRODUCT_ADDIMG_IS_LIGHTBOX_LBL'),
		'desc'  => JText::_('COM_REDSHOP_TOOLTIP_PRODUCT_ADDIMG_IS_LIGHTBOX_LBL'),
		'field' => $this->lists['product_addimg_is_lightbox']
	)
);
?>
<div class="form-group row-fluid">
    <label class="col-md-4 hasPopover" data-content="<?php echo JText::_('COM_REDSHOP_TOOLTIP_PRODUCT_MAIN_IMAGE_LBL'); ?>">
		<?php echo JText::_('COM_REDSHOP_PRODUCT_MAIN_IMAGE'); ?>
    </label>
    <div class="col-md-8">
        <div class="row">
            <div class="col-sm-6">
                <input type="number" name="product_main_image" id="product_main_image" class="form-control"
                       value="<?php echo $this->config->get('PRODUCT_MAIN_IMAGE'); ?>"/>
            </div>
            <div class="col-sm-6">
                <input type="number" name="product_main_image_height" id="product_main_image_height" class="form-control"
                       value="<?php echo $this->config->get('PRODUCT_MAIN_IMAGE_HEIGHT'); ?>"/>
            </div>
        </div>
    </div>
</div>
<div class="form-group row-fluid">
    <label class="col-md-4 hasPopover" data-content="<?php echo JText::_('COM_REDSHOP_TOOLTIP_PRODUCT_MAIN_IMAGE_LBL_TWO'); ?>">
		<?php echo JText::_('COM_REDSHOP_PRODUCT_MAIN_IMAGE_TWO'); ?>
    </label>
    <div class="col-md-8">
        <div class="row">
            <div class="col-sm-6">
                <input type="number" name="product_main_image_2" id="product_main_image_2" class="form-control"
                       value="<?php echo $this->config->get('PRODUCT_MAIN_IMAGE_2'); ?>"/>
            </div>
            <div class="col-sm-6">
                <input type="number" name="product_main_image_height_2" id="product_main_image_height_2" class="form-control"
                       value="<?php echo $this->config->get('PRODUCT_MAIN_IMAGE_HEIGHT_2'); ?>"/>
            </div>
        </div>
    </div>
</div>
<div class="form-group row-fluid">
    <label class="col-md-4 hasPopover" data-content="<?php echo JText::_('COM_REDSHOP_TOOLTIP_PRODUCT_MAIN_IMAGE_LBL_THREE'); ?>">
		<?php echo JText::_('COM_REDSHOP_PRODUCT_MAIN_IMAGE_THREE'); ?>
    </label>
    <div class="col-md-8">
        <div class="row">
            <div class="col-sm-6">
                <input type="number" name="product_main_image_3" id="product_main_image_3" class="form-control"
                       value="<?php echo $this->config->get('PRODUCT_MAIN_IMAGE_3'); ?>"/>
            </div>
            <div class="col-sm-6">
                <input type="number" name="product_main_image_height_3" id="product_main_image_height_3" class="form-control"
                       value="<?php echo $this->config->get('PRODUCT_MAIN_IMAGE_HEIGHT_3'); ?>"/>
            </div>
        </div>
    </div>
</div>
<div class="form-group row-fluid">
    <label class="col-md-4 hasPopover" data-content="<?php echo JText::_('COM_REDSHOP_TOOLTIP_PRODUCT_ADDITIONAL_IMAGE_LBL'); ?>">
		<?php echo JText::_('COM_REDSHOP_PRODUCT_ADDITIONAL_IMAGE_HEIGHT_WIDTH'); ?>
    </label>
    <div class="col-md-8">
        <div class="row">
            <div class="col-sm-6">
                <input type="number" name="product_additional_image" id="product_additional_image" class="form-control"
                       value="<?php echo $this->config->get('PRODUCT_ADDITIONAL_IMAGE'); ?>"/>
            </div>
            <div class="col-sm-6">
                <input type="number" name="product_additional_image_height" id="product_additional_image_height" class="form-control"
                       value="<?php echo $this->config->get('PRODUCT_ADDITIONAL_IMAGE_HEIGHT'); ?>"/>
            </div>
        </div>
    </div>
</div>
<div class="form-group row-fluid">
    <label class="col-md-4 hasPopover" data-content="<?php echo JText::_('COM_REDSHOP_TOOLTIP_PRODUCT_ADDITIONAL_IMAGE_TWO'); ?>">
		<?php echo JText::_('COM_REDSHOP_PRODUCT_ADDITIONAL_IMAGE_LBL_TWO'); ?>
    </label>
    <div class="col-md-8">
        <div class="row">
            <div class="col-sm-6">
                <input type="number" name="product_additional_image_2" id="product_additional_image_2" class="form-control"
                       value="<?php echo $this->config->get('PRODUCT_ADDITIONAL_IMAGE_2'); ?>"/>
            </div>
            <div class="col-sm-6">
                <input type="number" name="product_additional_image_height_2" id="product_additional_image_height_2" class="form-control"
                       value="<?php echo $this->config->get('PRODUCT_ADDITIONAL_IMAGE_HEIGHT_2'); ?>"/>
            </div>
        </div>
    </div>
</div>
<div class="form-group row-fluid">
    <label class="col-md-4 hasPopover" data-content="<?php echo JText::_('COM_REDSHOP_TOOLTIP_PRODUCT_ADDITIONAL_IMAGE_LBL_THREE'); ?>">
		<?php echo JText::_('COM_REDSHOP_PRODUCT_ADDITIONAL_IMAGE_WIDTH_HEIGHT_THREE'); ?>
    </label>
    <div class="col-md-8">
        <div class="row">
            <div class="col-sm-6">
                <input type="number" name="product_additional_image_3" id="product_additional_image_3" class="form-control"
                       value="<?php echo $this->config->get('PRODUCT_ADDITIONAL_IMAGE_3'); ?>"/>
            </div>
            <div class="col-sm-6">
                <input type="number" name="product_additional_image_height_3" id="product_additional_image_height_3" class="form-control"
                       value="<?php echo $this->config->get('PRODUCT_ADDITIONAL_IMAGE_HEIGHT_3'); ?>"/>
            </div>
        </div>
    </div>
</div>
<?php
echo RedshopLayoutHelper::render(
	'config.config',
	array(
		'title' => JText::_('COM_REDSHOP_WATERMARK_PRODUCT_IMAGE_LBL'),
		'desc'  => JText::_('COM_REDSHOP_TOOLTIP_WATERMARK_PRODUCT_IMAGE'),
		'field' => $this->lists['watermark_product_image']
	)
);
echo RedshopLayoutHelper::render(
	'config.config',
	array(
		'title' => JText::_('COM_REDSHOP_WATERMARK_PRODUCT_THUMB_IMAGE_LBL'),
		'desc'  => JText::_('COM_REDSHOP_TOOLTIP_WATERMARK_PRODUCT_THUMB_IMAGE'),
		'field' => $this->lists['watermark_product_thumb_image']
	)
);
echo RedshopLayoutHelper::render(
	'config.config',
	array(
		'title' => JText::_('COM_REDSHOP_WATERMARK_PRODUCT_ADDITIONAL_IMAGE_LBL'),
		'desc'  => JText::_('COM_REDSHOP_TOOLTIP_WATERMARK_PRODUCT_ADDITIONAL_IMAGE'),
		'field' => $this->lists['watermark_product_additional_image']
	)
);
echo RedshopLayoutHelper::render(
	'config.config',
	array(
		'title' => JText::_('COM_REDSHOP_PRODUCT_HOVER_IMAGE_ENABLE_LBL'),
		'desc'  => JText::_('COM_REDSHOP_TOOLTIP_PRODUCT_HOVER_IMAGE_ENABLE'),
		'field' => $this->lists['product_hover_image_enable'],
		'line'  => false
	)
);
?>
<div class="form-group row-fluid">
    <label class="col-md-4 hasPopover" data-content="<?php echo JText::_('COM_REDSHOP_TOOLTIP_PRODUCT_HOVER_IMAGE_WIDTH'); ?>">
		<?php echo JText::_('COM_REDSHOP_PRODUCT_HOVER_IMAGE_WIDTH_HEIGHT'); ?>
    </label>
    <div class="col-md-8">
        <div class="row">
            <div class="col-sm-6">
                <input type="number" name="product_hover_image_width" id="product_hover_image_width" class="form-control"
                       value="<?php echo $this->config->get('PRODUCT_HOVER_IMAGE_WIDTH'); ?>"/>
            </div>
            <div class="col-sm-6">
                <input type="number" name="product_hover_image_height" id="product_hover_image_height" class="form-control"
                       value="<?php echo $this->config->get('PRODUCT_HOVER_IMAGE_HEIGHT'); ?>"/>
            </div>
        </div>
    </div>
</div>
<?php
echo RedshopLayoutHelper::render(
	'config.config',
	array(
		'title' => JText::_('COM_REDSHOP_ADDITIONAL_HOVER_IMAGE_ENABLE_LBL'),
		'desc'  => JText::_('COM_REDSHOP_TOOLTIP_ADDITIONAL_HOVER_IMAGE_ENABLE_LBL'),
		'field' => $this->lists['additional_hover_image_enable']
	)
);
?>
<div class="form-group row-fluid">
    <label class="col-md-4 hasPopover" data-content="<?php echo JText::_('COM_REDSHOP_TOOLTIP_ADDITIONAL_HOVER_IMAGE_WIDTH_LBL'); ?>">
		<?php echo JText::_('COM_REDSHOP_ADDITIONAL_HOVER_IMAGE_WIDTH_HEIGHT'); ?>
    </label>
    <div class="col-md-8">
        <div class="row">
            <div class="col-sm-6">
                <input type="number" name="additional_hover_image_width" id="additional_hover_image_width" class="form-control"
                       value="<?php echo $this->config->get('ADDITIONAL_HOVER_IMAGE_WIDTH'); ?>"/>
            </div>
            <div class="col-sm-6">
                <input type="number" name="additional_hover_image_height" id="additional_hover_image_height" class="form-control"
                       value="<?php echo $this->config->get('ADDITIONAL_HOVER_IMAGE_HEIGHT'); ?>"/>
            </div>
        </div>
    </div>
</div>
<div class="form-group row-fluid">
    <label class="col-md-4 hasPopover" data-content="<?php echo JText::_('COM_REDSHOP_TOOLTIP_PRODUCT_PREVIEW_IMAGE_WIDTH'); ?>">
		<?php echo JText::_('COM_REDSHOP_PRODUCT_PREVIEW_IMAGE_WIDTH_HEIGHT_LBL'); ?>
    </label>
    <div class="col-md-8">
        <div class="row">
            <div class="col-sm-6">
                <input type="number" name="product_preview_image_width" id="product_preview_image_width" class="form-control"
                       value="<?php echo $this->config->get('PRODUCT_PREVIEW_IMAGE_WIDTH'); ?>"/>
            </div>
            <div class="col-sm-6">
                <input type="number" name="product_preview_image_height" id="product_preview_image_height" class="form-control"
                       value="<?php echo $this->config->get('PRODUCT_PREVIEW_IMAGE_HEIGHT'); ?>"/>
            </div>
        </div>
    </div>
</div>
<div class="form-group row-fluid">
    <label class="col-md-4 hasPopover" data-content="<?php echo JText::_('COM_REDSHOP_TOOLTIP_CATEGORY_PRODUCT_PREVIEW_IMAGE_WIDTH_LBL'); ?>">
		<?php echo JText::_('COM_REDSHOP_CATEGORY_PRODUCT_PREVIEW_IMAGE_WIDTH_HEIGHT_LBL'); ?>
    </label>
    <div class="col-md-8">
        <div class="row">
            <div class="col-sm-6">
                <input type="number" name="category_product_preview_image_width" id="category_product_preview_image_width" class="form-control"
                       value="<?php echo $this->config->get('CATEGORY_PRODUCT_PREVIEW_IMAGE_WIDTH'); ?>"/>
            </div>
            <div class="col-sm-6">
                <input type="number" name="category_product_preview_image_height" id="category_product_preview_image_height" class="form-control"
                       value="<?php echo $this->config->get('CATEGORY_PRODUCT_PREVIEW_IMAGE_HEIGHT'); ?>"/>
            </div>
        </div>
    </div>
</div>
<div class="form-group row-fluid">
    <label class="col-md-4 hasPopover" data-content="<?php echo JText::_('COM_REDSHOP_TOOLTIP_ATTRIBUTE_SCROLLER_THUMB_WIDTH_LBL'); ?>">
		<?php echo JText::_('COM_REDSHOP_ATTRIBUTE_SCROLLER_THUMB_WIDTH_HEIGHT_LBL'); ?>
    </label>
    <div class="col-md-8">
        <div class="row">
            <div class="col-sm-6">
                <input type="number" name="attribute_scroller_thumb_width" id="attribute_scroller_thumb_width" class="form-control"
                       value="<?php echo $this->config->get('ATTRIBUTE_SCROLLER_THUMB_WIDTH', 50); ?>"/>
            </div>
            <div class="col-sm-6">
                <input type="number" name="category_product_preview_image_height" id="category_product_preview_image_height" class="form-control"
                       value="<?php echo $this->config->get('ATTRIBUTE_SCROLLER_THUMB_HEIGHT', 50); ?>"/>
            </div>
        </div>
    </div>
</div>
<?php
echo RedshopLayoutHelper::render(
	'config.config',
	array(
		'title' => JText::_('COM_REDSHOP_NOOF_THUMB_FOR_SCROLLER_LBL'),
		'desc'  => JText::_('COM_REDSHOP_TOOLTIP_NOOF_THUMB_FOR_SCROLLER_LBL'),
		'field' => '<input type="number" name="noof_thumb_for_scroller" id="noof_thumb_for_scroller" class="form-control"'
			. ' value="' . $this->config->get('NOOF_THUMB_FOR_SCROLLER') . '" />'
	)
);
echo RedshopLayoutHelper::render(
	'config.config',
	array(
		'title' => JText::_('COM_REDSHOP_NOOF_SUBATTRIB_THUMB_FOR_SCROLLER_LBL'),
		'desc'  => JText::_('COM_REDSHOP_TOOLTIP_NOOF_SUBATTRIB_THUMB_FOR_SCROLLER'),
		'field' => '<input type="number" name="noof_subattrib_thumb_for_scroller" id="noof_subattrib_thumb_for_scroller" class="form-control"'
			. ' value="' . $this->config->get('NOOF_SUBATTRIB_THUMB_FOR_SCROLLER') . '" />'
	)
);
?>
<div class="form-group row-fluid">
    <label class="col-md-4 hasPopover" data-content="<?php echo JText::_('COM_REDSHOP_TOOLTIP_PRE_ORDER_IMAGE'); ?>">
		<?php echo JText::_('COM_REDSHOP_PRE_ORDER_IMAGE_LBL'); ?>
    </label>
    <div class="col-md-8">
		<?php $preOrderImage = $this->config->get('PRE_ORDER_IMAGE'); ?>
        <input class="text_area" type="file" name="file_pre_order_image" id="file_pre_order_image" size="40"/>
        <input type="hidden" name="pre_order_image" id="pre_order_image" value="<?php echo $preOrderImage ?>"/>

		<?php if (JFile::exists(REDSHOP_FRONT_IMAGES_RELPATH . $preOrderImage)): ?>
            <div class="divimages" id="preorddiv">
                <a class="joom-box" href="<?php echo REDSHOP_FRONT_IMAGES_ABSPATH . $preOrderImage; ?>"
                   title="<?php echo $preOrderImage; ?>" rel="{handler: 'image', size: {}}">
                    <img alt="<?php echo $preOrderImage ?>" src="<?php echo REDSHOP_FRONT_IMAGES_ABSPATH . $preOrderImage ?>" class="thumbnail"/>
                </a>
                <a class="remove_link" href="#" onclick="delimg('<?php echo $preOrderImage ?>','preorddiv','<?php echo $ord_path ?>');">
					<?php echo JText::_('COM_REDSHOP_REMOVE_FILE'); ?>
                </a>
            </div>
		<?php endif; ?>
    </div>
</div>
