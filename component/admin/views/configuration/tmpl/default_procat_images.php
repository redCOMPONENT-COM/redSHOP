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

$uri = JURI::getInstance();
$url = $uri->root();
$cat_path = "/components/com_redshop/assets/images/category/";
$prod_path = "/components/com_redshop/assets/images/product/";
$slimbox_img_path = "/components/com_redshop/assets/images/slimbox/";
?>

<legend><?php echo JText::_('COM_REDSHOP_DEFAULT_IMAGES'); ?></legend>

<div class="form-group">
	<span class="editlinktip hasTip"
		      title="<?php echo JText::_('COM_REDSHOP_TOOLTIP_CATEGORY_DEFAULT_IMAGE_LBL'); ?>::<?php echo JText::_('COM_REDSHOP_TOOLTIP_CATEGORY_DEFAULT_IMAGE'); ?>">
		<?php echo JText::_('COM_REDSHOP_CATEGORY_DEFAULT_IMAGE_LBL');?>:
	</span>
	<?php $categoryDefaultImage = $this->config->get('CATEGORY_DEFAULT_IMAGE'); ?>
	<input class="text_area" type="file" name="categoryImg" id="categoryImg" size="40"/>
	<input type="hidden" name="category_default_image" id="category_default_image"
			       value="<?php echo $categoryDefaultImage; ?>"/>

	<?php  if (is_file(REDSHOP_FRONT_IMAGES_RELPATH . 'category/' . $categoryDefaultImage))
	{ ?>
		<div class="divimages" id="catdiv">
			<a class="joom-box" href="<?php
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
			                                           ?>"/></a>

			<a class="remove_link" href="#" onclick="delimg('<?php echo $categoryDefaultImage ?>','catdiv','<?php echo $cat_path ?>');"><?php echo JText::_('COM_REDSHOP_REMOVE_FILE');?></a>
		 </div>
	<?php
	}
	?>
</div>

<div class="form-group">
	<span class="editlinktip hasTip"
		      title="<?php echo JText::_('COM_REDSHOP_PRODUCT_DEFAULT_IMAGE_LBL'); ?>::<?php echo JText::_('COM_REDSHOP_TOOLTIP_PRODUCT_DEFAULT_IMAGE_LBL'); ?>">
		<?php echo JText::_('COM_REDSHOP_PRODUCT_DEFAULT_IMAGE_LBL');?>:
	</span>
	<?php $productDefaultImage = $this->config->get('PRODUCT_DEFAULT_IMAGE'); ?>
	<input class="text_area" type="file" name="productImg" id="productImg" size="40"/>
		<input type="hidden" name="product_default_image" id="product_default_image"
		       value="<?php echo $productDefaultImage; ?>"/>
	<?php
	if (is_file(REDSHOP_FRONT_IMAGES_RELPATH . 'product/' . $productDefaultImage))
	{
		?>
		<div class="divimages" id="proddiv">
			<a class="joom-box"
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
		                                                             ?>"/></a>
		    <a class="remove_link" href="#" onclick="delimg('<?php echo $productDefaultImage ?>','proddiv','<?php echo $prod_path ?>');"><?php echo JText::_('COM_REDSHOP_REMOVE_FILE');?></a>
		 </div>
	<?php
	}
	?>
</div>

<div class="form-group">
	<span class="editlinktip hasTip"
		      title="<?php echo JText::_('COM_REDSHOP_TOOLTIP_WATERMARK_IMAGE_LBL'); ?>::<?php echo JText::_('COM_REDSHOP_TOOLTIP_WATERMARK_IMAGE'); ?>">
	<?php echo JText::_('COM_REDSHOP_WATERMARK_IMAGE_LBL'); ?>:</span>
	<?php $watermarkImage = $this->config->get('WATERMARK_IMAGE'); ?>
	<input class="text_area" type="file" name="watermarkImg" id="watermarkImg" size="40"/>
		<input type="hidden" name="watermark_image" id="watermark_image"
		       value="<?php echo $watermarkImage; ?>"/>
	<?php
	if (is_file(REDSHOP_FRONT_IMAGES_RELPATH . 'product/' . $watermarkImage))
	{
		?>
		<div class="divimages" id="prodwaterdiv"><a class="joom-box"
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
		                                                                  ?>"/></a>
			<a class="remove_link" href="#" onclick="delimg('<?php echo $watermarkImage ?>','prodwaterdiv','<?php echo $prod_path ?>');"><?php echo JText::_('COM_REDSHOP_REMOVE_FILE');?></a>
		</div>
	<?php
	}
		?>
</div>

<div class="form-group">
	<span class="editlinktip hasTip"
		      title="<?php echo JText::_('COM_REDSHOP_TOOLTIP_PRODUCT_OUTOFSTOCK_IMAGE_LBL'); ?>::<?php echo JText::_('COM_REDSHOP_TOOLTIP_PRODUCT_OUTOFSTOCK_IMAGE'); ?>">
		<label><?php echo JText::_('COM_REDSHOP_PRODUCT_OUTOFSTOCK_IMAGE_LBL'); ?>:</label>
	<?php $productOutofstockImage = $this->config->get('PRODUCT_OUTOFSTOCK_IMAGE'); ?>
	<input class="text_area" type="file" name="productoutofstockImg" id="categoryImg" size="40"/>
		<input type="hidden" name="product_outofstock_image" id="product_outofstock_image"
		       value="<?php echo $productOutofstockImage; ?>"/>
	<?php
	if (is_file(REDSHOP_FRONT_IMAGES_RELPATH . 'product/' . $productOutofstockImage))
	{
		?>
		<div class="divimages" id="prodstockdiv"><a class="joom-box"
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
		                                                                  ?>"/></a>
		<a class="remove_link" href="#" onclick="delimg('<?php echo $productOutofstockImage ?>','prodstockdiv','<?php echo $prod_path ?>');"><?php echo JText::_('COM_REDSHOP_REMOVE_FILE');?></a>
		</div>
	<?php
	}
	?>

</div>

<div class="form-group">
	<span class="editlinktip hasTip"
			      title="<?php echo JText::_('COM_REDSHOP_TOOLTIP_PRODUCT_DETAIL_LIGHTBOX_CLOSE_BUTTON_IMAGE_LBL'); ?>::<?php echo JText::_('COM_REDSHOP_TOOLTIP_PRODUCT_DETAIL_LIGHTBOX_CLOSE_BUTTON_IMAGE'); ?>">
		<label><?php echo JText::_('COM_REDSHOP_PRODUCT_DETAIL_LIGHTBOX_CLOSE_BUTTON_IMAGE_LBL'); ?>:</label>
	</span>
	<?php $productDetailLightboxCloseBtnImage = $this->config->get('PRODUCT_DETAIL_LIGHTBOX_CLOSE_BUTTON_IMAGE'); ?>
	<input class="text_area" type="file" name="imgslimbox" id="imgslimbox" size="40"/>
	<input type="hidden" name="product_detail_lighbox_close_button_image"
	       id="product_detail_lighbox_close_button_image"
	       value="<?php echo $productDetailLightboxCloseBtnImage; ?>"/>
	<?php
	$slimbox_image_path = REDSHOP_FRONT_IMAGES_RELPATH . 'slimbox/' . $productDetailLightboxCloseBtnImage;
	?>
	<?php  if (is_file($slimbox_image_path))
	{ ?>
	<div class="divimages" id="slimboximglinkdiv">
		<a class="joom-box"
		        href="<?php echo REDSHOP_FRONT_IMAGES_ABSPATH . 'slimbox/' . $productDetailLightboxCloseBtnImage; ?>"
		        title="<?php echo $productDetailLightboxCloseBtnImage; ?>"
		        rel="{handler: 'image', size: {}}"><img
					alt="<?php echo $productDetailLightboxCloseBtnImage; ?>"
					src="<?php echo REDSHOP_FRONT_IMAGES_ABSPATH . 'slimbox/' . $productDetailLightboxCloseBtnImage; ?>"/></a>
		<a class="remove_link" href="#"
			   onclick="delimg('<?php echo $productDetailLightboxCloseBtnImage ?>','slimboximglinkdiv','<?php echo $slimbox_img_path; ?>');"><?php echo JText::_('COM_REDSHOP_REMOVE_FILE');?></a>

	</div><?php } ?>
</div>
