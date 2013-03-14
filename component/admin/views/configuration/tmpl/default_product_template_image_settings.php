<?php
/**
 * @package     RedSHOP.Backend
 * @subpackage  Template
 *
 * @copyright   Copyright (C) 2005 - 2013 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */
defined('_JEXEC') or die ('Restricted access');
$ord_path = "/components/com_redshop/assets/images/";
?>
<table class="admintable" width="100%">

<!-- Product Template Settings  Start -->
<tr valign="top">
	<td>
		<fieldset class="adminform">
			<table class="admintable" width="100%">
				<tr>
					<td class="config_param"><?php echo JText::_('COM_REDSHOP_PRODUCT_TEMPLATE'); ?></td>
				</tr>
				<tr>
					<td width="100" align="right" class="key">
			<span class="editlinktip hasTip"
			      title="<?php echo JText::_('COM_REDSHOP_DEFAULT_PRODUCT_TEMPLATE_LBL'); ?>::<?php echo JText::_('COM_REDSHOP_TOOLTIP_DEFAULT_PRODUCT_TEMPLATE_FOR_VM_LBL'); ?>">
			<label
				for="producttemplate"><?php echo JText::_('COM_REDSHOP_DEFAULT_PRODUCT_TEMPLATE_LBL');?></label></span>
					</td>
					<td><?php echo $this->lists ['product_template'];?></td>
				</tr>
				<tr>
					<td width="100" align="right" class="key">
		<span class="editlinktip hasTip"
		      title="<?php echo JText::_('COM_REDSHOP_DISPLAY_OUT_OF_STOCK_ATTRIBUTE_DATA'); ?>::<?php echo JText::_('COM_REDSHOP_DISPLAY_OUT_OF_STOCK_ATTRIBUTE_DATA'); ?>">
		<label
			for="display_out_of_stock_attribute_data"><?php echo JText::_('COM_REDSHOP_DISPLAY_OUT_OF_STOCK_ATTRIBUTE_DATA');?></label></span>
					</td>
					<td><?php echo $this->lists ['display_out_of_stock_attribute_data'];?></td>
				</tr>
			</table>
		</fieldset>
	</td>
</tr>

<!-- Product Template Settings End -->

<!-- Image  Settings  Start -->
<tr valign="top">
	<td>
		<fieldset class="adminform">
			<table class="admintable" width="100%">
				<tr>
					<td class="config_param"><?php echo JText::_('COM_REDSHOP_IMAGE_SETTINGS'); ?></td>
				</tr>
				<tr>
					<td width="100" align="right" class="key">
		<span class="editlinktip hasTip"
		      title="<?php echo JText::_('COM_REDSHOP_PRODUCT_MAIN_IMAGE'); ?>::<?php echo JText::_('COM_REDSHOP_TOOLTIP_PRODUCT_MAIN_IMAGE_LBL'); ?>">
		<label for="name"><?php echo JText::_('COM_REDSHOP_PRODUCT_MAIN_IMAGE_HEIGHT_WIDTH');?></label></span>
					</td>
					<td>
						<input type="text" name="product_main_image" id="product_main_image"
						       value="<?php echo PRODUCT_MAIN_IMAGE; ?>">
						<input type="text" name="product_main_image_height" id="product_main_image_height"
						       value="<?php echo PRODUCT_MAIN_IMAGE_HEIGHT; ?>">
					</td>
				</tr>
				<tr>
					<td width="100" align="right" class="key">
		<span class="editlinktip hasTip"
		      title="<?php echo JText::_('COM_REDSHOP_PRODUCT_MAIN_IMAGE_TWO'); ?>::<?php echo JText::_('COM_REDSHOP_TOOLTIP_PRODUCT_MAIN_IMAGE_LBL_TWO'); ?>">
		<label for="name"><?php echo JText::_('COM_REDSHOP_PRODUCT_MAIN_IMAGE_HEIGHT_WIDTH_TWO');?></label></span></td>
					<td>
						<input type="text" name="product_main_image_2" id="product_main_image_2"
						       value="<?php echo PRODUCT_MAIN_IMAGE_2; ?>">
						<input type="text" name="product_main_image_height_2" id="product_main_image_height_2"
						       value="<?php echo PRODUCT_MAIN_IMAGE_HEIGHT_2; ?>">
					</td>
				</tr>
				<tr>
					<td width="100" align="right" class="key">
		<span class="editlinktip hasTip"
		      title="<?php echo JText::_('COM_REDSHOP_PRODUCT_MAIN_IMAGE_THREE'); ?>::<?php echo JText::_('COM_REDSHOP_TOOLTIP_PRODUCT_MAIN_IMAGE_LBL_THREE'); ?>">
		<label for="name"><?php echo JText::_('COM_REDSHOP_PRODUCT_MAIN_IMAGE_HEIGHT_WIDTH_THREE');?></label></span>
					</td>
					<td>
						<input type="text" name="product_main_image_3" id="product_main_image_3"
						       value="<?php echo PRODUCT_MAIN_IMAGE_3; ?>">
						<input type="text" name="product_main_image_height_3" id="product_main_image_height_3"
						       value="<?php echo PRODUCT_MAIN_IMAGE_HEIGHT_3; ?>">
					</td>
				</tr>
				<tr>
					<td colspan="2">
						<hr/>
					</td>
				</tr>
				<tr>
					<td width="100" align="right" class="key">
		<span class="editlinktip hasTip"
		      title="<?php echo JText::_('COM_REDSHOP_TOOLTIP_PRODUCT_ADDITIONAL_IMAGE_LBL'); ?>::<?php echo JText::_('COM_REDSHOP_TOOLTIP_PRODUCT_ADDITIONAL_IMAGE'); ?>">
		<label for="name"><?php echo JText::_('COM_REDSHOP_PRODUCT_ADDITIONAL_IMAGE_HEIGHT_WIDTH'); ?> </label></span>
					</td>
					<td>
						<input type="text" name="product_additional_image" id="product_additional_image"
						       value="<?php echo PRODUCT_ADDITIONAL_IMAGE; ?>">
						<input type="text" name="product_additional_image_height" id="product_additional_image_height"
						       value="<?php echo PRODUCT_ADDITIONAL_IMAGE_HEIGHT; ?>">
					</td>
				</tr>
				<tr>
					<td width="100" align="right" class="key">
		<span class="editlinktip hasTip"
		      title="<?php echo JText::_('COM_REDSHOP_PRODUCT_ADDITIONAL_IMAGE_LBL_TWO'); ?>::<?php echo JText::_('COM_REDSHOP_TOOLTIP_PRODUCT_ADDITIONAL_IMAGE_TWO'); ?>">
		<label
			for="name"><?php echo JText::_('COM_REDSHOP_PRODUCT_ADDITIONAL_IMAGE_HEIGHT_WIDTH_TWO'); ?></label></span>
					</td>
					<td>
						<input type="text" name="product_additional_image_2" id="product_additional_image_2"
						       value="<?php echo PRODUCT_ADDITIONAL_IMAGE_2; ?>">
						<input type="text" name="product_additional_image_height_2"
						       id="product_additional_image_height_2"
						       value="<?php echo PRODUCT_ADDITIONAL_IMAGE_HEIGHT_2; ?>">
					</td>
				</tr>
				<tr>
					<td width="100" align="right" class="key">
		<span class="editlinktip hasTip"
		      title="<?php echo JText::_('COM_REDSHOP_PRODUCT_ADDITIONAL_IMAGE_THREE'); ?>::<?php echo JText::_('COM_REDSHOP_TOOLTIP_PRODUCT_ADDITIONAL_IMAGE_LBL_THREE'); ?>">
		<label
			for="name"><?php echo JText::_('COM_REDSHOP_PRODUCT_ADDITIONAL_IMAGE_WIDTH_HEIGHT_THREE');?></label></span>
					</td>
					<td>
						<input type="text" name="product_additional_image_3" id="product_additional_image_3"
						       value="<?php echo PRODUCT_ADDITIONAL_IMAGE_3; ?>">
						<input type="text" name="product_additional_image_height_3"
						       id="product_additional_image_height_3"
						       value="<?php echo PRODUCT_ADDITIONAL_IMAGE_HEIGHT_3; ?>">
					</td>
				</tr>
				<tr>
					<td colspan="2">
						<hr/>
					</td>
				</tr>
				<tr>
					<td width="100" align="right" class="key">
		<span class="editlinktip hasTip"
		      title="<?php echo JText::_('COM_REDSHOP_WATERMARK_PRODUCT_IMAGE_LBL'); ?>::<?php echo JText::_('COM_REDSHOP_TOOLTIP_WATERMARK_PRODUCT_IMAGE'); ?>">
		<label for="name"><?php echo JText::_('COM_REDSHOP_WATERMARK_PRODUCT_IMAGE_LBL');?></label></span>
					</td>
					<td><?php echo $this->lists ['watermark_product_image'];?></td>
				</tr>
				<tr>
					<td width="100" align="right" class="key">
		<span class="editlinktip hasTip"
		      title="<?php echo JText::_('COM_REDSHOP_WATERMARK_PRODUCT_THUMB_IMAGE_LBL'); ?>::<?php echo JText::_('COM_REDSHOP_TOOLTIP_WATERMARK_PRODUCT_THUMB_IMAGE'); ?>">
		<label for="name">
			<?php echo JText::_('COM_REDSHOP_WATERMARK_PRODUCT_THUMB_IMAGE_LBL');?></label></span></td>
					<td><?php echo $this->lists ['watermark_product_thumb_image'];?></td>
				</tr>
				<tr>
					<td width="100" align="right" class="key">
		<span class="editlinktip hasTip"
		      title="<?php echo JText::_('COM_REDSHOP_WATERMARK_PRODUCT_ADDITIONAL_IMAGE_LBL'); ?>::<?php echo JText::_('COM_REDSHOP_TOOLTIP_WATERMARK_PRODUCT_ADDITIONAL_IMAGE'); ?>">
		<label for="name"><?php echo JText::_('COM_REDSHOP_WATERMARK_PRODUCT_ADDITIONAL_IMAGE_LBL');?></label></span>
					</td>
					<td><?php echo $this->lists ['watermark_product_additional_image'];?></td>
				</tr>
				<tr>
					<td colspan="2">
						<hr/>
					</td>
				</tr>
				<tr>
					<td width="100" align="right" class="key">
		<span class="editlinktip hasTip"
		      title="<?php echo JText::_('COM_REDSHOP_TOOLTIP_PRODUCT_HOVER_IMAGE_ENABLE_LBL'); ?>::<?php echo JText::_('COM_REDSHOP_TOOLTIP_PRODUCT_HOVER_IMAGE_ENABLE'); ?>">
		<?php
			echo JText::_('COM_REDSHOP_PRODUCT_HOVER_IMAGE_ENABLE_LBL');
			?></span></td>
					<td><?php echo $this->lists ['product_hover_image_enable'];?>
					</td>
				</tr>
				<tr>
					<td width="100" align="right" class="key">
		<span class="editlinktip hasTip"
		      title="<?php echo JText::_('COM_REDSHOP_TOOLTIP_PRODUCT_HOVER_IMAGE_WIDTH_LBL'); ?>::<?php echo JText::_('COM_REDSHOP_TOOLTIP_PRODUCT_HOVER_IMAGE_WIDTH'); ?>">
		<?php echo JText::_('COM_REDSHOP_PRODUCT_HOVER_IMAGE_WIDTH_HEIGHT');?></span>
					</td>
					<td>
						<input type="text" name="product_hover_image_width" id="product_hover_image_width"
						       value="<?php echo PRODUCT_HOVER_IMAGE_WIDTH; ?>">
						<input type="text" name="product_hover_image_height" id="product_hover_image_height"
						       value="<?php echo PRODUCT_HOVER_IMAGE_HEIGHT; ?>">
					</td>
				</tr>
				<tr>
					<td colspan="2">
						<hr/>
					</td>
				</tr>
				<tr>
					<td width="100" align="right" class="key">
						<span class="editlinktip hasTip"
						      title="<?php echo JText::_('COM_REDSHOP_TOOLTIP_ADDITIONAL_HOVER_IMAGE_ENABLE_LBL'); ?>::<?php echo JText::_('COM_REDSHOP_TOOLTIP_ADDITIONAL_HOVER_IMAGE_ENABLE'); ?>"> <?php echo JText::_('COM_REDSHOP_ADDITIONAL_HOVER_IMAGE_ENABLE_LBL');?></span>
					</td>
					<td><?php echo $this->lists ['additional_hover_image_enable'];?></td>
				</tr>
				<tr>
					<td width="100" align="right" class="key"><span class="editlinktip hasTip"
					                                                title="<?php echo JText::_('COM_REDSHOP_TOOLTIP_ADDITIONAL_HOVER_IMAGE_WIDTH_LBL'); ?>::<?php echo JText::_('COM_REDSHOP_TOOLTIP_ADDITIONAL_HOVER_IMAGE_WIDTH'); ?>">
		<?php echo JText::_('COM_REDSHOP_ADDITIONAL_HOVER_IMAGE_WIDTH_HEIGHT');?></span></td>
					<td>
						<input type="text" name="additional_hover_image_width" id="additional_hover_image_width"
						       value="<?php echo ADDITIONAL_HOVER_IMAGE_WIDTH; ?>">
						<input type="text" name="additional_hover_image_height" id="additional_hover_image_height"
						       value="<?php echo ADDITIONAL_HOVER_IMAGE_HEIGHT; ?>">
					</td>
				</tr>
				<tr>
					<td colspan="2">
						<hr/>
					</td>
				</tr>
				<tr>
					<td width="100" align="right" class="key">
		<span class="editlinktip hasTip"
		      title="<?php echo JText::_('COM_REDSHOP_TOOLTIP_PRODUCT_PREVIEW_IMAGE_WIDTH_LBL'); ?>::<?php echo JText::_('COM_REDSHOP_TOOLTIP_PRODUCT_PREVIEW_IMAGE_WIDTH'); ?>">
		<label for="product_preview_image_width">
			<?php echo JText::_('COM_REDSHOP_PRODUCT_PREVIEW_IMAGE_WIDTH_HEIGHT_LBL');?>
		</label></span>
					</td>
					<td>
						<input type="text" name="product_preview_image_width" id="product_preview_image_width"
						       value="<?php echo PRODUCT_PREVIEW_IMAGE_WIDTH; ?>">
						<input type="text" name="product_preview_image_height" id="product_preview_image_height"
						       value="<?php echo PRODUCT_PREVIEW_IMAGE_HEIGHT; ?>">
					</td>
				</tr>
				<tr>
					<td width="100" align="right" class="key">
		<span class="editlinktip hasTip"
		      title="<?php echo JText::_('COM_REDSHOP_CATEGORY_PRODUCT_PREVIEW_IMAGE_WIDTH_LBL'); ?>::<?php echo JText::_('COM_REDSHOP_TOOLTIP_CATEGORY_PRODUCT_PREVIEW_IMAGE_WIDTH_LBL'); ?>">
		<label
			for="category_product_preview_image_width"><?php echo JText::_('COM_REDSHOP_CATEGORY_PRODUCT_PREVIEW_IMAGE_WIDTH_HEIGHT_LBL');?></label></span>
					</td>
					<td>
						<input type="text" name="category_product_preview_image_width"
						       id="category_product_preview_image_width"
						       value="<?php echo CATEGORY_PRODUCT_PREVIEW_IMAGE_WIDTH; ?>">
						<input type="text" name="category_product_preview_image_height"
						       id="category_product_preview_image_height"
						       value="<?php echo CATEGORY_PRODUCT_PREVIEW_IMAGE_HEIGHT; ?>">
					</td>
				</tr>
				<tr>
					<td colspan="2">
						<hr/>
					</td>
				</tr>
				<tr>
					<td width="100" align="right" class="key">
		<span class="editlinktip hasTip"
		      title="<?php echo JText::_('COM_REDSHOP_ATTRIBUTE_SCROLLER_THUMB_WIDTH_LBL'); ?>::<?php echo JText::_('COM_REDSHOP_TOOLTIP_ATTRIBUTE_SCROLLER_THUMB_WIDTH_LBL'); ?>">
		<label for="attribute_scroller_thumb_width">
			<?php echo JText::_('COM_REDSHOP_ATTRIBUTE_SCROLLER_THUMB_WIDTH_HEIGHT_LBL');?>
		</label></span></td>
					<td>
						<input type="text" name="attribute_scroller_thumb_width" id="attribute_scroller_thumb_width"
						       value="<?php echo defined('ATTRIBUTE_SCROLLER_THUMB_WIDTH') ? ATTRIBUTE_SCROLLER_THUMB_WIDTH : '50'; ?>">
						<input type="text" name="attribute_scroller_thumb_height" id="attribute_scroller_thumb_height"
						       value="<?php echo defined('ATTRIBUTE_SCROLLER_THUMB_HEIGHT') ? ATTRIBUTE_SCROLLER_THUMB_HEIGHT : '50'; ?>">
					</td>
				</tr>
				<tr>
					<td width="100" align="right" class="key">
			<span class="editlinktip hasTip"
			      title="<?php echo JText::_('COM_REDSHOP_NOOF_THUMB_FOR_SCROLLER_LBL'); ?>::<?php echo JText::_('COM_REDSHOP_TOOLTIP_NOOF_THUMB_FOR_SCROLLER_LBL'); ?>">
			<label><?php echo JText::_('COM_REDSHOP_NOOF_THUMB_FOR_SCROLLER_LBL'); ?></label></span>
					</td>
					<td><input type="text" name="noof_thumb_for_scroller" id="noof_thumb_for_scroller"
					           value="<?php echo NOOF_THUMB_FOR_SCROLLER; ?>"></td>
				</tr>
				<tr>
					<td width="100" align="right" class="key">
			<span class="editlinktip hasTip"
			      title="<?php echo JText::_('COM_REDSHOP_TOOLTIP_NOOF_SUBATTRIB_THUMB_FOR_SCROLLER_LBL'); ?>::<?php echo JText::_('COM_REDSHOP_TOOLTIP_NOOF_SUBATTRIB_THUMB_FOR_SCROLLER'); ?>">
			<label><?php echo JText::_('COM_REDSHOP_NOOF_SUBATTRIB_THUMB_FOR_SCROLLER_LBL'); ?></label></span>
					</td>
					<td><input type="text" name="noof_subattrib_thumb_for_scroller"
					           id="noof_subattrib_thumb_for_scroller"
					           value="<?php echo NOOF_SUBATTRIB_THUMB_FOR_SCROLLER; ?>"></td>
				</tr>
				<tr>
					<td align="right" class="key">
			<span class="editlinktip hasTip"
			      title="<?php echo JText::_('COM_REDSHOP_TOOLTIP_PRE_ORDER_IMAGE_LBL'); ?>::<?php echo JText::_('COM_REDSHOP_TOOLTIP_PRE_ORDER_IMAGE'); ?>">
		<?php echo JText::_('COM_REDSHOP_PRE_ORDER_IMAGE_LBL');?>:</span></td>
					<td>
						<div>
							<div>
								<input class="text_area" type="file" name="file_pre_order_image"
								       id="file_pre_order_image" size="40"/>
								<input type="hidden" name="pre_order_image" id="pre_order_image"
								       value="<?php echo PRE_ORDER_IMAGE; ?>"/>
								<a href="#123"
								   onclick="delimg('<?php echo PRE_ORDER_IMAGE ?>','preorddiv','<?php echo $ord_path ?>');"><?php echo JText::_('COM_REDSHOP_REMOVE_FILE');?></a>
							</div>
							<?php if (is_file(REDSHOP_FRONT_IMAGES_RELPATH . PRE_ORDER_IMAGE))
							{ ?>
								<div id="preorddiv">
									<a class="modal"
									   href="<?php echo REDSHOP_FRONT_IMAGES_ABSPATH . PRE_ORDER_IMAGE; ?>"
									   title="<?php echo PRE_ORDER_IMAGE; ?>" rel="{handler: 'image', size: {}}">
										<img alt="<?php echo PRE_ORDER_IMAGE; ?>"
										     src="<?php echo REDSHOP_FRONT_IMAGES_ABSPATH . PRE_ORDER_IMAGE; ?>"/>
									</a>
								</div>
							<?php } ?>
						</div>
					</td>
				</tr>
			</table>
		</fieldset>
	</td>
</tr>
<!-- Image  Settings  End -->
</table>
