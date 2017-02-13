<?php
/**
 * @package     RedSHOP.Backend
 * @subpackage  Template
 *
 * @copyright   Copyright (C) 2008 - 2016 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */
defined('_JEXEC') or die;

JHtmlBehavior::modal('.joom-box');

$ord_path = "/components/com_redshop/assets/images/";
?>

<legend><?php echo JText::_('COM_REDSHOP_PRODUCT_TEMPLATE'); ?></legend>
<div class="form-group">
	<span class="editlinktip hasTip"
			      title="<?php echo JText::_('COM_REDSHOP_DEFAULT_PRODUCT_TEMPLATE_LBL'); ?>::<?php echo JText::_('COM_REDSHOP_TOOLTIP_DEFAULT_PRODUCT_TEMPLATE_FOR_VM_LBL'); ?>">
		<label for="producttemplate"><?php echo JText::_('COM_REDSHOP_DEFAULT_PRODUCT_TEMPLATE_LBL');?></label>
	</span>
	<?php echo $this->lists ['product_template'];?>
</div>


<div class="form-group">
	<span class="editlinktip hasTip"
		      title="<?php echo JText::_('COM_REDSHOP_DEFAULT_PRODUCT_ORDERING_METHOD_LBL'); ?>::<?php echo JText::_('COM_REDSHOP_TOOLTIP_DEFAULT_PRODUCT_ORDERING_METHOD_LBL'); ?>">
		<label for="name"><?php echo JText::_('COM_REDSHOP_DEFAULT_PRODUCT_ORDERING_METHOD_LBL');?></label>
	</span>
	<?php echo $this->lists ['default_product_ordering_method'];?>
</div>

<div class="form-group">
	<span class="editlinktip hasTip"
		      title="<?php echo JText::_('COM_REDSHOP_DISPLAY_OUT_OF_STOCK_ATTRIBUTE_DATA'); ?>::<?php echo JText::_('COM_REDSHOP_DISPLAY_OUT_OF_STOCK_ATTRIBUTE_DATA'); ?>">
		<label for="display_out_of_stock_attribute_data"><?php echo JText::_('COM_REDSHOP_DISPLAY_OUT_OF_STOCK_ATTRIBUTE_DATA');?></label>
	</span>
	<?php echo $this->lists ['display_out_of_stock_attribute_data'];?>
</div>

<legend><?php echo JText::_('COM_REDSHOP_IMAGE_SETTINGS'); ?></legend>
<div class="form-group">
	<span class="editlinktip hasTip"
		      title="<?php echo JText::_('COM_REDSHOP_PRODUCT_IS_LIGHTBOX_LBL'); ?>::<?php echo JText::_('COM_REDSHOP_TOOLTIP_PRODUCT_IS_LIGHTBOX'); ?>">
		<label for="name"><?php echo JText::_('COM_REDSHOP_PRODUCT_IS_LIGHTBOX_LBL');?></label></span>
	<?php echo $this->lists ['product_is_lightbox'];?>
</div>

<div class="form-group">
	<span class="editlinktip hasTip"
		      title="<?php echo JText::_('COM_REDSHOP_PRODUCT_DETAIL_IS_LIGHTBOX_LBL'); ?>::<?php echo JText::_('COM_REDSHOP_TOOLTIP_PRODUCT_DETAIL_IS_LIGHTBOX'); ?>">
		<label for="name"><?php echo JText::_('COM_REDSHOP_PRODUCT_DETAIL_IS_LIGHTBOX_LBL');?></label></span>
	<?php echo $this->lists ['product_detail_is_lightbox'];?>
</div>

<div class="form-group">
	<span class="editlinktip hasTip"
		      title="<?php echo JText::_('COM_REDSHOP_PRODUCT_ADDIMG_IS_LIGHTBOX_LBL'); ?>::<?php echo JText::_('COM_REDSHOP_TOOLTIP_PRODUCT_ADDIMG_IS_LIGHTBOX_LBL'); ?>">
		<label for="name"><?php echo JText::_('COM_REDSHOP_PRODUCT_ADDIMG_IS_LIGHTBOX_LBL');?></label></span>
	<?php echo $this->lists ['product_addimg_is_lightbox'];?>
</div>

<hr/>

<div class="form-group">
	<span class="editlinktip hasTip"
		      title="<?php echo JText::_('COM_REDSHOP_PRODUCT_MAIN_IMAGE'); ?>::<?php echo JText::_('COM_REDSHOP_TOOLTIP_PRODUCT_MAIN_IMAGE_LBL'); ?>">
		<label for="name"><?php echo JText::_('COM_REDSHOP_PRODUCT_MAIN_IMAGE_HEIGHT_WIDTH');?></label>
	</span>
	<input type="text" name="product_main_image" id="product_main_image"
						       value="<?php echo $this->config->get('PRODUCT_MAIN_IMAGE'); ?>">
	<input type="text" name="product_main_image_height" id="product_main_image_height"
						       value="<?php echo $this->config->get('PRODUCT_MAIN_IMAGE_HEIGHT'); ?>">
</div>

<div class="form-group">
	<span class="editlinktip hasTip"
		      title="<?php echo JText::_('COM_REDSHOP_PRODUCT_MAIN_IMAGE_TWO'); ?>::<?php echo JText::_('COM_REDSHOP_TOOLTIP_PRODUCT_MAIN_IMAGE_LBL_TWO'); ?>">
		<label for="name"><?php echo JText::_('COM_REDSHOP_PRODUCT_MAIN_IMAGE_HEIGHT_WIDTH_TWO');?></label>
	</span>
	<input type="text" name="product_main_image_2" id="product_main_image_2"
						       value="<?php echo $this->config->get('PRODUCT_MAIN_IMAGE_2'); ?>">
	<input type="text" name="product_main_image_height_2" id="product_main_image_height_2"
						       value="<?php echo $this->config->get('PRODUCT_MAIN_IMAGE_HEIGHT_2'); ?>">
</div>

<div class="form-group">
	<span class="editlinktip hasTip"
		      title="<?php echo JText::_('COM_REDSHOP_PRODUCT_MAIN_IMAGE_THREE'); ?>::<?php echo JText::_('COM_REDSHOP_TOOLTIP_PRODUCT_MAIN_IMAGE_LBL_THREE'); ?>">
		<label for="name"><?php echo JText::_('COM_REDSHOP_PRODUCT_MAIN_IMAGE_HEIGHT_WIDTH_THREE');?></label>
	</span>
	<input type="text" name="product_main_image_3" id="product_main_image_3"
						       value="<?php echo $this->config->get('PRODUCT_MAIN_IMAGE_3'); ?>">
	<input type="text" name="product_main_image_height_3" id="product_main_image_height_3"
						       value="<?php echo $this->config->get('PRODUCT_MAIN_IMAGE_HEIGHT_3'); ?>">
</div>

<hr/>

<div class="form-group">
	<span class="editlinktip hasTip"
		      title="<?php echo JText::_('COM_REDSHOP_TOOLTIP_PRODUCT_ADDITIONAL_IMAGE_LBL'); ?>::<?php echo JText::_('COM_REDSHOP_TOOLTIP_PRODUCT_ADDITIONAL_IMAGE'); ?>">
		<label for="name"><?php echo JText::_('COM_REDSHOP_PRODUCT_ADDITIONAL_IMAGE_HEIGHT_WIDTH'); ?> </label>
	</span>
	<input type="text" name="product_additional_image" id="product_additional_image"
						       value="<?php echo $this->config->get('PRODUCT_ADDITIONAL_IMAGE'); ?>">
	<input type="text" name="product_additional_image_height" id="product_additional_image_height"
						       value="<?php echo $this->config->get('PRODUCT_ADDITIONAL_IMAGE_HEIGHT'); ?>">
</div>

<div class="form-group">
	<span class="editlinktip hasTip"
		      title="<?php echo JText::_('COM_REDSHOP_PRODUCT_ADDITIONAL_IMAGE_LBL_TWO'); ?>::<?php echo JText::_('COM_REDSHOP_TOOLTIP_PRODUCT_ADDITIONAL_IMAGE_TWO'); ?>">
		<label
			for="name"><?php echo JText::_('COM_REDSHOP_PRODUCT_ADDITIONAL_IMAGE_HEIGHT_WIDTH_TWO'); ?></label>
	</span>
	<input type="text" name="product_additional_image_2" id="product_additional_image_2"
						       value="<?php echo $this->config->get('PRODUCT_ADDITIONAL_IMAGE_2'); ?>">
	<input type="text" name="product_additional_image_height_2"
						       id="product_additional_image_height_2"
						       value="<?php echo $this->config->get('PRODUCT_ADDITIONAL_IMAGE_HEIGHT_2'); ?>">
</div>

<div class="form-group">
	<span class="editlinktip hasTip"
		      title="<?php echo JText::_('COM_REDSHOP_PRODUCT_ADDITIONAL_IMAGE_THREE'); ?>::<?php echo JText::_('COM_REDSHOP_TOOLTIP_PRODUCT_ADDITIONAL_IMAGE_LBL_THREE'); ?>">
		<label
			for="name"><?php echo JText::_('COM_REDSHOP_PRODUCT_ADDITIONAL_IMAGE_WIDTH_HEIGHT_THREE');?></label>
	</span>
	<input type="text" name="product_additional_image_3" id="product_additional_image_3"
						       value="<?php echo $this->config->get('PRODUCT_ADDITIONAL_IMAGE_3'); ?>">
	<input type="text" name="product_additional_image_height_3"
						       id="product_additional_image_height_3"
						       value="<?php echo $this->config->get('PRODUCT_ADDITIONAL_IMAGE_HEIGHT_3'); ?>">
</div>

<hr/>

<div class="form-group">
	<span class="editlinktip hasTip"
		      title="<?php echo JText::_('COM_REDSHOP_WATERMARK_PRODUCT_IMAGE_LBL'); ?>::<?php echo JText::_('COM_REDSHOP_TOOLTIP_WATERMARK_PRODUCT_IMAGE'); ?>">
		<label for="name"><?php echo JText::_('COM_REDSHOP_WATERMARK_PRODUCT_IMAGE_LBL');?></label>
	</span>
	<?php echo $this->lists ['watermark_product_image'];?>
</div>

<div class="form-group">
	<span class="editlinktip hasTip"
		      title="<?php echo JText::_('COM_REDSHOP_WATERMARK_PRODUCT_THUMB_IMAGE_LBL'); ?>::<?php echo JText::_('COM_REDSHOP_TOOLTIP_WATERMARK_PRODUCT_THUMB_IMAGE'); ?>">
		<label for="name">
			<?php echo JText::_('COM_REDSHOP_WATERMARK_PRODUCT_THUMB_IMAGE_LBL');?></label>
	</span>
	<?php echo $this->lists ['watermark_product_thumb_image'];?>
</div>

<div class="form-group">
	<span class="editlinktip hasTip"
		      title="<?php echo JText::_('COM_REDSHOP_WATERMARK_PRODUCT_ADDITIONAL_IMAGE_LBL'); ?>::<?php echo JText::_('COM_REDSHOP_TOOLTIP_WATERMARK_PRODUCT_ADDITIONAL_IMAGE'); ?>">
		<label for="name"><?php echo JText::_('COM_REDSHOP_WATERMARK_PRODUCT_ADDITIONAL_IMAGE_LBL');?></label>
	</span>
	<?php echo $this->lists ['watermark_product_additional_image'];?>
</div>

<hr/>

<div class="form-group">
	<span class="editlinktip hasTip"
		      title="<?php echo JText::_('COM_REDSHOP_TOOLTIP_PRODUCT_HOVER_IMAGE_ENABLE_LBL'); ?>::<?php echo JText::_('COM_REDSHOP_TOOLTIP_PRODUCT_HOVER_IMAGE_ENABLE'); ?>">
		<label><?php
			echo JText::_('COM_REDSHOP_PRODUCT_HOVER_IMAGE_ENABLE_LBL');
			?></label>
	</span>
	<?php echo $this->lists ['product_hover_image_enable'];?>
</div>

<div class="form-group">
	<span class="editlinktip hasTip"
		      title="<?php echo JText::_('COM_REDSHOP_TOOLTIP_PRODUCT_HOVER_IMAGE_WIDTH_LBL'); ?>::<?php echo JText::_('COM_REDSHOP_TOOLTIP_PRODUCT_HOVER_IMAGE_WIDTH'); ?>">
		<label><?php echo JText::_('COM_REDSHOP_PRODUCT_HOVER_IMAGE_WIDTH_HEIGHT');?></label>
	</span>
	<input type="text" name="product_hover_image_width" id="product_hover_image_width"
						       value="<?php echo $this->config->get('PRODUCT_HOVER_IMAGE_WIDTH'); ?>">
	<input type="text" name="product_hover_image_height" id="product_hover_image_height"
						       value="<?php echo $this->config->get('PRODUCT_HOVER_IMAGE_HEIGHT'); ?>">
</div>

<hr/>

<div class="form-group">
	<span class="editlinktip hasTip" title="<?php echo JText::_('COM_REDSHOP_TOOLTIP_ADDITIONAL_HOVER_IMAGE_ENABLE_LBL'); ?>::<?php echo JText::_('COM_REDSHOP_TOOLTIP_ADDITIONAL_HOVER_IMAGE_ENABLE'); ?>">
		<label><?php echo JText::_('COM_REDSHOP_ADDITIONAL_HOVER_IMAGE_ENABLE_LBL');?></label>
	</span>
	<?php echo $this->lists ['additional_hover_image_enable'];?>
</div>

<div class="form-group">
	<span class="editlinktip hasTip" title="<?php echo JText::_('COM_REDSHOP_TOOLTIP_ADDITIONAL_HOVER_IMAGE_WIDTH_LBL'); ?>::<?php echo JText::_('COM_REDSHOP_TOOLTIP_ADDITIONAL_HOVER_IMAGE_WIDTH'); ?>">
		<label><?php echo JText::_('COM_REDSHOP_ADDITIONAL_HOVER_IMAGE_WIDTH_HEIGHT');?></label></span>
	<input type="text" name="additional_hover_image_width" id="additional_hover_image_width"
						       value="<?php echo $this->config->get('ADDITIONAL_HOVER_IMAGE_WIDTH'); ?>">
	<input type="text" name="additional_hover_image_height" id="additional_hover_image_height"
						       value="<?php echo $this->config->get('ADDITIONAL_HOVER_IMAGE_HEIGHT'); ?>">
</div>

<hr/>

<div class="form-group">
	<span class="editlinktip hasTip"
		      title="<?php echo JText::_('COM_REDSHOP_TOOLTIP_PRODUCT_PREVIEW_IMAGE_WIDTH_LBL'); ?>::<?php echo JText::_('COM_REDSHOP_TOOLTIP_PRODUCT_PREVIEW_IMAGE_WIDTH'); ?>">
		<label for="product_preview_image_width">
			<?php echo JText::_('COM_REDSHOP_PRODUCT_PREVIEW_IMAGE_WIDTH_HEIGHT_LBL');?>
		</label></span>
	<input type="text" name="product_preview_image_width" id="product_preview_image_width"
						       value="<?php echo $this->config->get('PRODUCT_PREVIEW_IMAGE_WIDTH'); ?>">
	<input type="text" name="product_preview_image_height" id="product_preview_image_height"
						       value="<?php echo $this->config->get('PRODUCT_PREVIEW_IMAGE_HEIGHT'); ?>">
</div>

<div class="form-group">
	<span class="editlinktip hasTip"
		      title="<?php echo JText::_('COM_REDSHOP_CATEGORY_PRODUCT_PREVIEW_IMAGE_WIDTH_LBL'); ?>::<?php echo JText::_('COM_REDSHOP_TOOLTIP_CATEGORY_PRODUCT_PREVIEW_IMAGE_WIDTH_LBL'); ?>">
		<label
			for="category_product_preview_image_width"><?php echo JText::_('COM_REDSHOP_CATEGORY_PRODUCT_PREVIEW_IMAGE_WIDTH_HEIGHT_LBL');?></label></span>
	<input type="text" name="category_product_preview_image_width"
						       id="category_product_preview_image_width"
						       value="<?php echo $this->config->get('CATEGORY_PRODUCT_PREVIEW_IMAGE_WIDTH'); ?>">
						<input type="text" name="category_product_preview_image_height"
						       id="category_product_preview_image_height"
						       value="<?php echo $this->config->get('CATEGORY_PRODUCT_PREVIEW_IMAGE_HEIGHT'); ?>">
</div>

<hr/>

<div class="form-group">
	<span class="editlinktip hasTip"
		      title="<?php echo JText::_('COM_REDSHOP_ATTRIBUTE_SCROLLER_THUMB_WIDTH_LBL'); ?>::<?php echo JText::_('COM_REDSHOP_TOOLTIP_ATTRIBUTE_SCROLLER_THUMB_WIDTH_LBL'); ?>">
		<label for="attribute_scroller_thumb_width">
			<?php echo JText::_('COM_REDSHOP_ATTRIBUTE_SCROLLER_THUMB_WIDTH_HEIGHT_LBL');?>
		</label></span>
	<input type="text" name="attribute_scroller_thumb_width" id="attribute_scroller_thumb_width"
						       value="<?php echo $this->config->get('ATTRIBUTE_SCROLLER_THUMB_WIDTH', 50); ?>">
						<input type="text" name="attribute_scroller_thumb_height" id="attribute_scroller_thumb_height"
						       value="<?php echo $this->config->get('ATTRIBUTE_SCROLLER_THUMB_HEIGHT', 50); ?>">
</div>

<div class="form-group">
	<span class="editlinktip hasTip"
			      title="<?php echo JText::_('COM_REDSHOP_NOOF_THUMB_FOR_SCROLLER_LBL'); ?>::<?php echo JText::_('COM_REDSHOP_TOOLTIP_NOOF_THUMB_FOR_SCROLLER_LBL'); ?>">
			<label><?php echo JText::_('COM_REDSHOP_NOOF_THUMB_FOR_SCROLLER_LBL'); ?></label></span>
	<input type="text" name="noof_thumb_for_scroller" id="noof_thumb_for_scroller"
					           value="<?php echo $this->config->get('NOOF_THUMB_FOR_SCROLLER'); ?>">
</div>

<div class="form-group">
	<span class="editlinktip hasTip"
			      title="<?php echo JText::_('COM_REDSHOP_TOOLTIP_NOOF_SUBATTRIB_THUMB_FOR_SCROLLER_LBL'); ?>::<?php echo JText::_('COM_REDSHOP_TOOLTIP_NOOF_SUBATTRIB_THUMB_FOR_SCROLLER'); ?>">
			<label><?php echo JText::_('COM_REDSHOP_NOOF_SUBATTRIB_THUMB_FOR_SCROLLER_LBL'); ?></label></span>
	<input type="text" name="noof_subattrib_thumb_for_scroller"
					           id="noof_subattrib_thumb_for_scroller"
					           value="<?php echo $this->config->get('NOOF_SUBATTRIB_THUMB_FOR_SCROLLER'); ?>">
</div>

<div class="form-group">
	<span class="editlinktip hasTip"
			      title="<?php echo JText::_('COM_REDSHOP_TOOLTIP_PRE_ORDER_IMAGE_LBL'); ?>::<?php echo JText::_('COM_REDSHOP_TOOLTIP_PRE_ORDER_IMAGE'); ?>">
		<label><?php echo JText::_('COM_REDSHOP_PRE_ORDER_IMAGE_LBL');?>:</label></span>
	<?php $preOrderImage = $this->config->get('PRE_ORDER_IMAGE'); ?>
	<input class="text_area" type="file" name="file_pre_order_image"
			       id="file_pre_order_image" size="40"/>
	<input type="hidden" name="pre_order_image" id="pre_order_image"
	       value="<?php echo $preOrderImage; ?>"/>

	<?php if (is_file(REDSHOP_FRONT_IMAGES_RELPATH . $preOrderImage)) { ?>
	<div class="divimages" id="preorddiv">
		<a class="joom-box"
		   href="<?php echo REDSHOP_FRONT_IMAGES_ABSPATH . $preOrderImage; ?>"
		   title="<?php echo $preOrderImage; ?>" rel="{handler: 'image', size: {}}">
			<img alt="<?php echo $preOrderImage; ?>"
			     src="<?php echo REDSHOP_FRONT_IMAGES_ABSPATH . $preOrderImage; ?>"/>
		</a>
		<a class="remove_link" href="#" onclick="delimg('<?php echo $preOrderImage ?>','preorddiv','<?php echo $ord_path ?>');">
			<?php echo JText::_('COM_REDSHOP_REMOVE_FILE');?>
		</a>
	</div>
	<?php } ?>
</div>

