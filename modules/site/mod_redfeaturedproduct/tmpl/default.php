<?php
/**
 * @package     RedSHOP.Module
 * @subpackage  mod_redfeaturedproduct
 *
 * @copyright   Copyright (C) 2008 - 2015 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

defined('_JEXEC') or die;

$document = JFactory::getDocument();
JHtml::stylesheet('mod_redfeaturedproduct/jquery.css', false, true);
JHtml::stylesheet('mod_redfeaturedproduct/skin_002.css', false, true);
JHtml::_('redshopjquery.framework');

if ($view == 'category')
{
	if (!isset($GLOBALS['product_price_slider']))
	{
		JHtml::script('com_redshop/jquery.tools.min.js', false, true);
	}
}
else
{
	JHTML::script('com_redshop/redbox.js', false, true);
	JHtml::script('com_redshop/attribute.js', false, true);
	JHtml::script('com_redshop/common.js', false, true);
	JHtml::script('com_redshop/jquery.tools.min.js', false, true);
}

JHTML::script('com_redshop/carousel.js', false, true);
$document->addScriptDeclaration("jQuery(document).ready(function() {
	jQuery('#produkt_carousel_mod_" . $module->id . "').red_product({
		wrap: 'last',
		scroll: 1,
		auto: 6,
		animation: 'slow',
		easing: 'swing',
		itemLoadCallback: jQuery.noConflict()
	});
});");

echo $params->get('pretext', "");
?>

<?php if (count($list) > 0): ?>
	<?php $rightarrow = $scrollerWidth + 20; ?>
	<div class="red_product-skin-produkter">
		<div class="red_product-container red_product-container-horizontal">
			<div class="red_product-prev red_product-prev-horizontal"></div>
			<div style="left:<?php echo $rightarrow;?>px;"
				 class="red_product-next red_product-next-horizontal"></div>
			<div style="width:<?php echo $scrollerWidth;?>px;" class="red_product-clip red_product-clip-horizontal">
				<ul id="produkt_carousel_mod_<?php echo $module->id; ?>"
					class="red_product-list red_product-list-horizontal">
					<?php $i = 0; ?>

					<?php foreach ($list as $row): ?>
						<?php $ItemData = $producthelper->getMenuInformation(0, 0, '', 'product&pid=' . $row->product_id); ?>
						<?php if (count($ItemData) > 0): ?>
							<?php $Itemid = $ItemData->id; ?>
						<?php else: ?>
							<?php $Itemid = $redhelper->getItemid($row->product_id); ?>
						<?php endif; ?>

						<?php if (!$cid): ?>
							<?php $cid = $producthelper->getCategoryProduct($row->product_id); ?>
						<?php endif; ?>

						<?php $link = JRoute::_('index.php?option=com_redshop&view=product&pid=' . $row->product_id . '&cid=' . $cid . '&Itemid=' . $Itemid); ?>
						<?php $prod_img = ""; ?>

						<?php if (is_file(REDSHOP_FRONT_IMAGES_RELPATH . "product/" . $row->product_full_image)): ?>
							<?php $prod_img = RedShopHelperImages::getImagePath(
								$row->product_full_image,
								'',
								'thumb',
								'product',
								$thumbWidth,
								$thumbHeight,
								Redshop::getConfig()->get('USE_IMAGE_SIZE_SWAPPING')
							); ?>
						<?php elseif (is_file(REDSHOP_FRONT_IMAGES_RELPATH . "product/" . $row->product_thumb_image)): ?>
							<?php $prod_img = RedShopHelperImages::getImagePath(
								$row->product_thumb_image,
								'',
								'thumb',
								'product',
								$thumbWidth,
								$thumbHeight,
								Redshop::getConfig()->get('USE_IMAGE_SIZE_SWAPPING')
							); ?>
						<?php else: ?>
							$prod_img = REDSHOP_FRONT_IMAGES_ABSPATH . 'noimage.jpg';
						<?php endif; ?>

						<?php $thum_image = "<a href=\"" . $link . "\" title=\"\" ><img src=\"" . $prod_img . "\"></a>"; ?>

						<li red_productindex="<?php echo $i;?>" class="red_product-item red_product-item-horizontal">
							<div class="listing-item">
								<div class="product-shop">
									<?php if ($params->get('show_product_name', 1)): ?>
										<div class="mod_redproducts_title"><a href="<?php echo $link ?>" title="<?php echo $row->product_name ?>">"<?php echo $row->product_name ?></a></div>
									<?php endif; ?>

									<?php if (!$row->not_for_sale && $params->get('show_price', 1)): ?>
										<?php $productArr = $producthelper->getProductNetPrice($row->product_id); ?>

										<?php if ($params->get('show_vatprice', "0")): ?>
											<?php $product_price = $productArr['product_main_price']; ?>
											<?php $product_price_discount = $productArr['productPrice'] + $productArr['productVat']; ?>
										<?php else: ?>
											<?php $product_price = $productArr['product_price_novat']; ?>
											<?php $product_price_discount = $productArr['productPrice']; ?>
										<?php endif; ?>

										<?php if (Redshop::getConfig()->get('SHOW_PRICE') && !Redshop::getConfig()->get('USE_AS_CATALOG') && (!Redshop::getConfig()->get('DEFAULT_QUOTATION_MODE') || (Redshop::getConfig()->get('DEFAULT_QUOTATION_MODE') && Redshop::getConfig()->get('SHOW_QUOTATION_PRICE')))): ?>
											
											<?php if (!$product_price): ?>
												<?php $product_price_dis = $producthelper->getPriceReplacement($product_price); ?>
											<?php else: ?>
												<?php $product_price_dis = $producthelper->getProductFormattedPrice($product_price); ?>
											<?php endif; ?>

											<?php $disply_text = "<div class=\"mod_redproducts_price\">" . $product_price_dis . "</div>"; ?>

											<?php if ($row->product_on_sale && $product_price_discount > 0): ?>
												<?php if ($product_price > $product_price_discount): ?>
													<?php $disply_text = ""; ?>
													<?php $s_price = $product_price - $product_price_discount; ?>

													<?php if ($params->get('show_discountpricelayout', "100")): ?>
														<div id="mod_redoldprice" class="mod_redoldprice"><span><?php echo $producthelper->getProductFormattedPrice($product_price) ?></span></div>
														<div id="mod_redmainprice" class="mod_redmainprice"><?php echo $producthelper->getProductFormattedPrice($product_price_discount) ?></div>
														<div id="mod_redsavedprice" class="mod_redsavedprice"><?php echo JText::_('MOD_REDFEATUREDPRODUCT_PRODUCT_PRICE_YOU_SAVED') . ' ' . $producthelper->getProductFormattedPrice($s_price) ?></div>
													<?php else: ?>
														<div class="mod_redproducts_price"><?php echo $producthelper->getProductFormattedPrice($product_price_discount) ?></div>
													<?php endif; ?>
												<?php endif; ?>
											<?php endif; ?>

											<?php echo $disply_text; ?>
										<?php endif; ?>
									<?php endif; ?>
								</div>
							</div>
							<div class="product-image"
								 style="width:<?php echo $thumbWidth;?>px;height:<?php echo $thumbHeight;?>px;">
								<?php echo $thum_image;?>
							</div>
							<?php if ($params->get('show_addtocart', 1)): ?>
								<?php $attributes = $producthelper->getProductAttribute($row->product_id); ?>
								<?php $totalatt   = count($attributes); ?>
								<?php $addtocart_data = $producthelper->replaceCartTemplate($row->product_id, 0, 0, 0, "", false, array(), $totalatt, 0, 0, $module->id); ?>
								<div class="form-button"><?php echo $addtocart_data ?></div>;
							<?php endif; ?>
						</li>
						<?php $i++; ?>
					<?php endforeach; ?>
				</ul>
			</div>
		</div>
	</div>
<?php else: ?>
	<div><?php echo JText::_("MOD_REDFEATUREDPRODUCT_NO_FEATURED_PRODUCTS_TO_DISPLAY") ?></div>
<?php endif; ?>
