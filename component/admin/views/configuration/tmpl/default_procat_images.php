<?php
/**
 * @package     RedSHOP.Backend
 * @subpackage  Template
 *
 * @copyright   Copyright (C) 2005 - 2013 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */
defined('_JEXEC') or die ('Restricted access');
$uri = JURI::getInstance();
$url = $uri->root();
$cat_path = "/components/com_redshop/assets/images/category/";
$prod_path = "/components/com_redshop/assets/images/product/";
$slimbox_img_path = "/components/com_redshop/assets/images/slimbox/";
?>
<table class="admintable" id="measurement">
	<tr>
		<td class="config_param"><?php echo JText::_('COM_REDSHOP_DEFAULT_IMAGES'); ?></td>
	</tr>
	<tr>
		<td align="right" class="key">
		<span class="editlinktip hasTip"
		      title="<?php echo JText::_('COM_REDSHOP_TOOLTIP_CATEGORY_DEFAULT_IMAGE_LBL'); ?>::<?php echo JText::_('COM_REDSHOP_TOOLTIP_CATEGORY_DEFAULT_IMAGE'); ?>">
		<?php echo JText::_('COM_REDSHOP_CATEGORY_DEFAULT_IMAGE_LBL');?>:</span></td>
		<td>
			<div>
				<div>
					<input class="text_area" type="file" name="categoryImg" id="categoryImg" size="40"/>
					<input type="hidden" name="category_default_image" id="category_default_image"
					       value="<?php echo CATEGORY_DEFAULT_IMAGE; ?>"/>
					<a href="#123"
					   onclick="delimg('<?php echo CATEGORY_DEFAULT_IMAGE ?>','catdiv','<?php echo $cat_path ?>');"><?php echo JText::_('COM_REDSHOP_REMOVE_FILE');?></a>
				</div>

				<?php  if (is_file(REDSHOP_FRONT_IMAGES_RELPATH . 'category/' . CATEGORY_DEFAULT_IMAGE))
				{ ?>
					<div id="catdiv">
						<a class="modal" href="<?php
						echo REDSHOP_FRONT_IMAGES_ABSPATH . 'category/' . CATEGORY_DEFAULT_IMAGE;
						?>"
						   title="<?php
						   echo CATEGORY_DEFAULT_IMAGE;
						   ?>"
						   rel="{handler: 'image', size: {}}"><img height="40" width="40"
						                                           alt="<?php
						                                           echo CATEGORY_DEFAULT_IMAGE;
						                                           ?>"
						                                           src="<?php
						                                           echo REDSHOP_FRONT_IMAGES_ABSPATH . 'category/' . CATEGORY_DEFAULT_IMAGE;
						                                           ?>"/></a></div>
				<?php
				}
				?>
			</div>
		</td>
	</tr>
	<tr>
		<td align="right" class="key">
		<span class="editlinktip hasTip"
		      title="<?php echo JText::_('COM_REDSHOP_PRODUCT_DEFAULT_IMAGE_LBL'); ?>::<?php echo JText::_('COM_REDSHOP_TOOLTIP_PRODUCT_DEFAULT_IMAGE_LBL'); ?>">
		<?php echo JText::_('COM_REDSHOP_PRODUCT_DEFAULT_IMAGE_LBL');?>:</span></td>
		<td>

			<div>
				<div>
					<input class="text_area" type="file" name="productImg" id="productImg" size="40"/>
					<input type="hidden" name="product_default_image" id="product_default_image"
					       value="<?php echo PRODUCT_DEFAULT_IMAGE; ?>"/>
					<a href="#123"
					   onclick="delimg('<?php echo PRODUCT_DEFAULT_IMAGE ?>','proddiv','<?php echo $prod_path ?>');"><?php echo JText::_('COM_REDSHOP_REMOVE_FILE');?></a>
				</div>
				<?php
				if (is_file(REDSHOP_FRONT_IMAGES_RELPATH . 'product/' . PRODUCT_DEFAULT_IMAGE))
				{
					?>
					<div id="proddiv"><a class="modal"
					                     href="<?php
					                     echo REDSHOP_FRONT_IMAGES_ABSPATH . 'product/' . PRODUCT_DEFAULT_IMAGE;
					                     ?>"
					                     title="<?php
					                     echo PRODUCT_DEFAULT_IMAGE;
					                     ?>"
					                     rel="{handler: 'image', size: {}}"><img height="40" width="40"
					                                                             alt="<?php
					                                                             echo PRODUCT_DEFAULT_IMAGE;
					                                                             ?>"
					                                                             src="<?php
					                                                             echo REDSHOP_FRONT_IMAGES_ABSPATH . 'product/' . PRODUCT_DEFAULT_IMAGE;
					                                                             ?>"/></a></div>
				<?php
				}
				?>
			</div>
		</td>
	</tr>
	<tr>
		<td align="right" class="key">
		<span class="editlinktip hasTip"
		      title="<?php echo JText::_('COM_REDSHOP_TOOLTIP_WATERMARK_IMAGE_LBL'); ?>::<?php echo JText::_('COM_REDSHOP_TOOLTIP_WATERMARK_IMAGE'); ?>">
	<?php echo JText::_('COM_REDSHOP_WATERMARK_IMAGE_LBL'); ?>:</span></td>
		<td>
			<div>
				<div>

					<input class="text_area" type="file" name="watermarkImg" id="watermarkImg" size="40"/>
					<input type="hidden" name="watermark_image" id="watermark_image"
					       value="<?php echo WATERMARK_IMAGE; ?>"/>
					<a href="#123"
					   onclick="delimg('<?php echo WATERMARK_IMAGE ?>','prodwaterdiv','<?php echo $prod_path ?>');"><?php echo JText::_('COM_REDSHOP_REMOVE_FILE');?></a>
				</div>
				<?php
				if (is_file(REDSHOP_FRONT_IMAGES_RELPATH . 'product/' . WATERMARK_IMAGE))
				{
					?>
					<div id="prodwaterdiv"><a class="modal"
					                          href="<?php
					                          echo REDSHOP_FRONT_IMAGES_ABSPATH . 'product/' . WATERMARK_IMAGE;
					                          ?>"
					                          title="<?php
					                          echo WATERMARK_IMAGE;
					                          ?>"
					                          rel="{handler: 'image', size: {}}"><img height="40" width="40"
					                                                                  alt="<?php
					                                                                  echo PRODUCT_DEFAULT_IMAGE;
					                                                                  ?>"
					                                                                  src="<?php
					                                                                  echo REDSHOP_FRONT_IMAGES_ABSPATH . 'product/' . WATERMARK_IMAGE;
					                                                                  ?>"/></a></div>
				<?php
				}
				?>
			</div>
		</td>
	</tr>
	<tr>
		<td align="right" class="key">
		<span class="editlinktip hasTip"
		      title="<?php echo JText::_('COM_REDSHOP_TOOLTIP_PRODUCT_OUTOFSTOCK_IMAGE_LBL'); ?>::<?php echo JText::_('COM_REDSHOP_TOOLTIP_PRODUCT_OUTOFSTOCK_IMAGE'); ?>">
<?php
			echo JText::_('COM_REDSHOP_PRODUCT_OUTOFSTOCK_IMAGE_LBL');
			?>:
		</td>
		<td>
			<div>
				<div>
					<input class="text_area" type="file" name="productoutofstockImg" id="categoryImg" size="40"/>
					<input type="hidden" name="product_outofstock_image" id="product_outofstock_image"
					       value="<?php echo PRODUCT_OUTOFSTOCK_IMAGE; ?>"/>
					<a href="#123"
					   onclick="delimg('<?php echo PRODUCT_OUTOFSTOCK_IMAGE ?>','prodstockdiv','<?php echo $prod_path ?>');"><?php echo JText::_('COM_REDSHOP_REMOVE_FILE');?></a>
				</div>
				<?php
				if (is_file(REDSHOP_FRONT_IMAGES_RELPATH . 'product/' . PRODUCT_OUTOFSTOCK_IMAGE))
				{
					?>
					<div id="prodstockdiv"><a class="modal"
					                          href="<?php
					                          echo REDSHOP_FRONT_IMAGES_ABSPATH . 'product/' . PRODUCT_OUTOFSTOCK_IMAGE;
					                          ?>"
					                          title="<?php
					                          echo PRODUCT_OUTOFSTOCK_IMAGE;
					                          ?>"
					                          rel="{handler: 'image', size: {}}"><img height="40" width="40"
					                                                                  alt="<?php
					                                                                  echo PRODUCT_OUTOFSTOCK_IMAGE;
					                                                                  ?>"
					                                                                  src="<?php
					                                                                  echo REDSHOP_FRONT_IMAGES_ABSPATH . 'product/' . PRODUCT_OUTOFSTOCK_IMAGE;
					                                                                  ?>"/></a></div>
				<?php
				}
				?>
			</div>
		</td>
	</tr>
	<tr>
		<td align="right" class="key">
			<span class="editlinktip hasTip"
			      title="<?php echo JText::_('COM_REDSHOP_TOOLTIP_PRODUCT_DETAIL_LIGHTBOX_CLOSE_BUTTON_IMAGE_LBL'); ?>::<?php echo JText::_('COM_REDSHOP_TOOLTIP_PRODUCT_DETAIL_LIGHTBOX_CLOSE_BUTTON_IMAGE'); ?>">
<?php
				echo JText::_('COM_REDSHOP_PRODUCT_DETAIL_LIGHTBOX_CLOSE_BUTTON_IMAGE_LBL');
				?>:
</span></td>
		<td>
			<div>
				<div>
					<input class="text_area" type="file" name="imgslimbox" id="imgslimbox" size="40"/>
					<input type="hidden" name="product_detail_lighbox_close_button_image"
					       id="product_detail_lighbox_close_button_image"
					       value="<?php echo PRODUCT_DETAIL_LIGHTBOX_CLOSE_BUTTON_IMAGE; ?>"/>
					<?php
					$slimbox_image_path = REDSHOP_FRONT_IMAGES_RELPATH . 'slimbox/' . PRODUCT_DETAIL_LIGHTBOX_CLOSE_BUTTON_IMAGE;
					?>
				</div>
				<?php  if (is_file($slimbox_image_path))
				{ ?>
					<div id="slimboximglinkdiv">
					<div style='float:right'>
						<a href="#123"
						   onclick="delimg('<?php echo PRODUCT_DETAIL_LIGHTBOX_CLOSE_BUTTON_IMAGE ?>','slimboximglinkdiv','<?php echo $slimbox_img_path; ?>');"><?php echo JText::_('COM_REDSHOP_REMOVE_FILE');?></a></span>
					</div>
					<div><a class="modal"
					        href="<?php echo REDSHOP_FRONT_IMAGES_ABSPATH . 'slimbox/' . PRODUCT_DETAIL_LIGHTBOX_CLOSE_BUTTON_IMAGE; ?>"
					        title="<?php echo PRODUCT_DETAIL_LIGHTBOX_CLOSE_BUTTON_IMAGE; ?>"
					        rel="{handler: 'image', size: {}}"><img
								alt="<?php echo PRODUCT_DETAIL_LIGHTBOX_CLOSE_BUTTON_IMAGE; ?>"
								src="<?php echo REDSHOP_FRONT_IMAGES_ABSPATH . 'slimbox/' . PRODUCT_DETAIL_LIGHTBOX_CLOSE_BUTTON_IMAGE; ?>"/></a>
					</div>
					</div><?php } ?>
			</div>
		</td>
	</tr>

</table>
