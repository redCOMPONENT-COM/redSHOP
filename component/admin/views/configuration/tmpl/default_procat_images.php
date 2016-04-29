<?php
/**
 * @package     RedSHOP.Backend
 * @subpackage  Template
 *
 * @copyright   Copyright (C) 2008 - 2016 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */
defined('_JEXEC') or die;
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
			<?php $categoryDefaultImage = $this->config->get('CATEGORY_DEFAULT_IMAGE'); ?>
			<div>
				<div>
					<input class="text_area" type="file" name="categoryImg" id="categoryImg" size="40"/>
					<input type="hidden" name="category_default_image" id="category_default_image"
					       value="<?php echo $categoryDefaultImage; ?>"/>
					<a href="#123"
					   onclick="delimg('<?php echo $categoryDefaultImage ?>','catdiv','<?php echo $cat_path ?>');"><?php echo JText::_('COM_REDSHOP_REMOVE_FILE');?></a>
				</div>

				<?php  if (is_file(REDSHOP_FRONT_IMAGES_RELPATH . 'category/' . $categoryDefaultImage))
				{ ?>
					<div id="catdiv">
						<a class="modal" href="<?php
						echo REDSHOP_FRONT_IMAGES_ABSPATH . 'category/' . $categoryDefaultImage;
						?>"
						   title="<?php
						   echo $categoryDefaultImage;
						   ?>"
						   rel="{handler: 'image', size: {}}"><img height="40" width="40"
						                                           alt="<?php
						                                           echo $categoryDefaultImage;
						                                           ?>"
						                                           src="<?php
						                                           echo REDSHOP_FRONT_IMAGES_ABSPATH . 'category/' . $categoryDefaultImage;
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
			<?php $productDefaultImage = $this->config->get('PRODUCT_DEFAULT_IMAGE'); ?>
			<div>
				<div>
					<input class="text_area" type="file" name="productImg" id="productImg" size="40"/>
					<input type="hidden" name="product_default_image" id="product_default_image"
					       value="<?php echo $productDefaultImage; ?>"/>
					<a href="#123"
					   onclick="delimg('<?php echo $productDefaultImage ?>','proddiv','<?php echo $prod_path ?>');"><?php echo JText::_('COM_REDSHOP_REMOVE_FILE');?></a>
				</div>
				<?php
				if (is_file(REDSHOP_FRONT_IMAGES_RELPATH . 'product/' . $productDefaultImage))
				{
					?>
					<div id="proddiv"><a class="modal"
					                     href="<?php
					                     echo REDSHOP_FRONT_IMAGES_ABSPATH . 'product/' . $productDefaultImage;
					                     ?>"
					                     title="<?php
					                     echo $productDefaultImage;
					                     ?>"
					                     rel="{handler: 'image', size: {}}"><img height="40" width="40"
					                                                             alt="<?php
					                                                             echo $productDefaultImage;
					                                                             ?>"
					                                                             src="<?php
					                                                             echo REDSHOP_FRONT_IMAGES_ABSPATH . 'product/' . $productDefaultImage;
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
			<?php $watermarkImage = $this->config->get('WATERMARK_IMAGE'); ?>
			<div>
				<div>

					<input class="text_area" type="file" name="watermarkImg" id="watermarkImg" size="40"/>
					<input type="hidden" name="watermark_image" id="watermark_image"
					       value="<?php echo $watermarkImage; ?>"/>
					<a href="#123"
					   onclick="delimg('<?php echo $watermarkImage ?>','prodwaterdiv','<?php echo $prod_path ?>');"><?php echo JText::_('COM_REDSHOP_REMOVE_FILE');?></a>
				</div>
				<?php
				if (is_file(REDSHOP_FRONT_IMAGES_RELPATH . 'product/' . $watermarkImage))
				{
					?>
					<div id="prodwaterdiv"><a class="modal"
					                          href="<?php
					                          echo REDSHOP_FRONT_IMAGES_ABSPATH . 'product/' . $watermarkImage;
					                          ?>"
					                          title="<?php
					                          echo $watermarkImage;
					                          ?>"
					                          rel="{handler: 'image', size: {}}"><img height="40" width="40"
					                                                                  alt="<?php
					                                                                  echo Redshop::getConfig()->get('PRODUCT_DEFAULT_IMAGE');
					                                                                  ?>"
					                                                                  src="<?php
					                                                                  echo REDSHOP_FRONT_IMAGES_ABSPATH . 'product/' . $watermarkImage;
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
			<?php $productOutofstockImage = $this->config->get('PRODUCT_OUTOFSTOCK_IMAGE'); ?>
			<div>
				<div>
					<input class="text_area" type="file" name="productoutofstockImg" id="categoryImg" size="40"/>
					<input type="hidden" name="product_outofstock_image" id="product_outofstock_image"
					       value="<?php echo $productOutofstockImage; ?>"/>
					<a href="#123"
					   onclick="delimg('<?php echo $productOutofstockImage ?>','prodstockdiv','<?php echo $prod_path ?>');"><?php echo JText::_('COM_REDSHOP_REMOVE_FILE');?></a>
				</div>
				<?php
				if (is_file(REDSHOP_FRONT_IMAGES_RELPATH . 'product/' . $productOutofstockImage))
				{
					?>
					<div id="prodstockdiv"><a class="modal"
					                          href="<?php
					                          echo REDSHOP_FRONT_IMAGES_ABSPATH . 'product/' . $productOutofstockImage;
					                          ?>"
					                          title="<?php
					                          echo $productOutofstockImage;
					                          ?>"
					                          rel="{handler: 'image', size: {}}"><img height="40" width="40"
					                                                                  alt="<?php
					                                                                  echo $productOutofstockImage;
					                                                                  ?>"
					                                                                  src="<?php
					                                                                  echo REDSHOP_FRONT_IMAGES_ABSPATH . 'product/' . $productOutofstockImage;
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
			<?php $productDetailLightboxCloseBtnImage = $this->config->get('PRODUCT_DETAIL_LIGHTBOX_CLOSE_BUTTON_IMAGE'); ?>
			<div>
				<div>
					<input class="text_area" type="file" name="imgslimbox" id="imgslimbox" size="40"/>
					<input type="hidden" name="product_detail_lighbox_close_button_image"
					       id="product_detail_lighbox_close_button_image"
					       value="<?php echo $productDetailLightboxCloseBtnImage; ?>"/>
					<?php
					$slimbox_image_path = REDSHOP_FRONT_IMAGES_RELPATH . 'slimbox/' . $productDetailLightboxCloseBtnImage;
					?>
				</div>
				<?php  if (is_file($slimbox_image_path))
				{ ?>
					<div id="slimboximglinkdiv">
					<div style='float:right'>
						<a href="#123"
						   onclick="delimg('<?php echo $productDetailLightboxCloseBtnImage ?>','slimboximglinkdiv','<?php echo $slimbox_img_path; ?>');"><?php echo JText::_('COM_REDSHOP_REMOVE_FILE');?></a></span>
					</div>
					<div><a class="modal"
					        href="<?php echo REDSHOP_FRONT_IMAGES_ABSPATH . 'slimbox/' . $productDetailLightboxCloseBtnImage; ?>"
					        title="<?php echo $productDetailLightboxCloseBtnImage; ?>"
					        rel="{handler: 'image', size: {}}"><img
								alt="<?php echo $productDetailLightboxCloseBtnImage; ?>"
								src="<?php echo REDSHOP_FRONT_IMAGES_ABSPATH . 'slimbox/' . $productDetailLightboxCloseBtnImage; ?>"/></a>
					</div>
					</div><?php } ?>
			</div>
		</td>
	</tr>
</table>
