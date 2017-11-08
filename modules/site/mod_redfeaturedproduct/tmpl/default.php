<?php
/**
 * @package     RedSHOP.Module
 * @subpackage  mod_redfeaturedproduct
 *
 * @copyright   Copyright (C) 2008 - 2015 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

defined('_JEXEC') or die;

$Redconfiguration = Redconfiguration::getInstance();
$uri = JURI::getInstance();
$url = $uri->root();
$user = JFactory::getUser();
$producthelper = productHelper::getInstance();
$redhelper = redhelper::getInstance();
$app = JFactory::getApplication();
$Itemid = $app->input->getInt('Itemid', 0);
$view = $app->input->getCmd('view', 'category');
$cid = $app->input->getInt('cid');

$document = JFactory::getDocument();
JHTML::stylesheet('modules/mod_redfeaturedproduct/css/jquery.css');
JHTML::stylesheet('modules/mod_redfeaturedproduct/css/skin_002.css');
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

if (count($list) > 0)
{
	$rightarrow = $scrollerWidth + 20; ?>
	<div class="red_product-skin-produkter">
		<div class="red_product-container red_product-container-horizontal">
			<div class="red_product-prev red_product-prev-horizontal"></div>
			<div style="left:<?php echo $rightarrow;?>px;"
				 class="red_product-next red_product-next-horizontal"></div>
			<div style="width:<?php echo $scrollerWidth;?>px;" class="red_product-clip red_product-clip-horizontal">
				<ul id="produkt_carousel_mod_<?php echo $module->id; ?>"
					class="red_product-list red_product-list-horizontal">
					<?php $i = 0;

					foreach ($list as $row)
					{
						$ItemData = $producthelper->getMenuInformation(0, 0, '', 'product&pid=' . $row->product_id);

						if (count($ItemData) > 0)
						{
							$Itemid = $ItemData->id;
						}
						else
						{
							$Itemid = RedshopHelperUtility::getItemId($row->product_id);
						}

						if (!$cid)
						{
							$cid = $producthelper->getCategoryProduct($row->product_id);
						}

						$link = JRoute::_('index.php?option=com_redshop&view=product&pid=' . $row->product_id . '&cid=' . $cid . '&Itemid=' . $Itemid);
						$prod_img = "";

						if (JFile::exists(REDSHOP_FRONT_IMAGES_RELPATH . "product/" . $row->product_full_image))
						{
							$prod_img = RedShopHelperImages::getImagePath(
								$row->product_full_image,
								'',
								'thumb',
								'product',
								$thumbWidth,
								$thumbHeight,
								Redshop::getConfig()->get('USE_IMAGE_SIZE_SWAPPING')
							);
						}
						elseif (JFile::exists(REDSHOP_FRONT_IMAGES_RELPATH . "product/" . $row->product_thumb_image))
						{
							$prod_img = RedShopHelperImages::getImagePath(
								$row->product_thumb_image,
								'',
								'thumb',
								'product',
								$thumbWidth,
								$thumbHeight,
								Redshop::getConfig()->get('USE_IMAGE_SIZE_SWAPPING')
							);
						}
						else
						{
							$prod_img = REDSHOP_FRONT_IMAGES_ABSPATH . 'noimage.jpg';
						}

						$thum_image = "<a href=\"" . $link . "\" title=\"\" ><img src=\"" . $prod_img . "\"></a>";
						?>

						<li red_productindex="<?php echo $i;?>" class="red_product-item red_product-item-horizontal">
							<div class="listing-item">
								<div class="product-shop">
									<?php
									if ($params->get('show_product_name', 1))
									{
										echo "<div class=\"mod_redproducts_title\"><a href=\"" . $link . "\" title=\"" . $row->product_name . "\">" . $row->product_name . "</a></div>";
									}

									if (!$row->not_for_sale && $params->get('show_price', 1))
									{
										$productArr = $producthelper->getProductNetPrice($row->product_id);

										if ($params->get('show_vatprice', "0"))
										{
											$product_price = $productArr['product_main_price'];
											$product_price_discount = $productArr['productPrice'] + $productArr['productVat'];
										}
										else
										{
											$product_price = $productArr['product_price_novat'];
											$product_price_discount = $productArr['productPrice'];
										}

										if (Redshop::getConfig()->get('SHOW_PRICE') && !Redshop::getConfig()->get('USE_AS_CATALOG') && (!Redshop::getConfig()->get('DEFAULT_QUOTATION_MODE') || (Redshop::getConfig()->get('DEFAULT_QUOTATION_MODE') && Redshop::getConfig()->get('SHOW_QUOTATION_PRICE'))))
										{
											if (!$product_price)
											{
												$product_price_dis = $producthelper->getPriceReplacement($product_price);
											}
											else
											{
												$product_price_dis = $producthelper->getProductFormattedPrice($product_price);
											}

											$disply_text = "<div class=\"mod_redproducts_price\">" . $product_price_dis . "</div>";

											if ($row->product_on_sale && $product_price_discount > 0)
											{
												if ($product_price > $product_price_discount)
												{
													$disply_text = "";
													$s_price = $product_price - $product_price_discount;

													if ($params->get('show_discountpricelayout', "100"))
													{
														echo "<div id=\"mod_redoldprice\" class=\"mod_redoldprice\"><span>" . $producthelper->getProductFormattedPrice($product_price) . "</span></div>";
														echo "<div id=\"mod_redmainprice\" class=\"mod_redmainprice\">" . $producthelper->getProductFormattedPrice($product_price_discount) . "</div>";
														echo "<div id=\"mod_redsavedprice\" class=\"mod_redsavedprice\">" . JText::_('MOD_REDFEATUREDPRODUCT_PRODUCT_PRICE_YOU_SAVED') . ' ' . $producthelper->getProductFormattedPrice($s_price) . "</div>";
													}
													else
													{
														echo "<div class=\"mod_redproducts_price\">" . $producthelper->getProductFormattedPrice($product_price_discount) . "</div>";
													}
												}
											}

											echo $disply_text;
										}
									}
									?>
								</div>
							</div>
							<div class="product-image"
								 style="width:<?php echo $thumbWidth;?>px;height:<?php echo $thumbHeight;?>px;">
								<?php echo $thum_image;?>
							</div>
							<?php
							if ($params->get('show_addtocart', 1))
							{
								$attributes = $producthelper->getProductAttribute($row->product_id);
								$totalatt   = count($attributes);
								$addtocart_data = $producthelper->replaceCartTemplate($row->product_id, 0, 0, 0, "", false, array(), $totalatt, 0, 0, $module->id);
								echo "<div class=\"form-button\">" . $addtocart_data . "</div>";
							}
							?>
						</li>
						<?php $i++;
					}
					?>
				</ul>
			</div>
		</div>
	</div>
<?php
}
else
{
	echo "<div>" . JText::_("MOD_REDFEATUREDPRODUCT_NO_FEATURED_PRODUCTS_TO_DISPLAY") . "</div>";
}
