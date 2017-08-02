<?php
/**
 * @package     RedSHOP.Frontend
 * @subpackage  mod_redshop_category_scroller
 *
 * @copyright   Copyright (C) 2008 - 2015 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

defined('_JEXEC') or die;
JHTML::_('behavior.tooltip');
JHTML::_('behavior.modal');

$uri = JURI::getInstance();
$url = $uri->root();

$Itemid = JRequest::getInt('Itemid');
$user   = JFactory::getUser();

JHtml::_('redshopjquery.framework');
$document = JFactory::getDocument();
$document->addStyleSheet(JURI::base() . 'modules/mod_redshop_products/css/products.css');

// Light-box Java-script
JHtml::script('com_redshop/redbox.js', false, true);
JHtml::script('com_redshop/attribute.js', false, true);
JHtml::script('com_redshop/common.js', false, true);

$config = Redconfiguration::getInstance();
$producthelper = productHelper::getInstance();
$redhelper     = redhelper::getInstance();

$view      = JRequest::getCmd('view', 'category');
$module_id = "mod_" . $module->id;

$document = JFactory::getDocument();
$document->addStyleSheet("modules/mod_redshop_category_scroller/css/jquery.css");
$document->addStyleSheet("modules/mod_redshop_category_scroller/css/skin_002.css");


if ($view == 'category')
{
	if (!$GLOBALS['product_price_slider'])
	{
		JHtml::script('com_redshop/jquery.tools.min.js', false, true);
	}
}
else
{
	JHtml::script('com_redshop/jquery.tools.min.js', false, true);
}

JHTML::script('com_redshop/carousel.js', false, true);
$document->addScriptDeclaration("jQuery(document).ready(function () {
    jQuery('#rs_category_scroller_" . $module->id . "').red_product({
        wrap: 'last',
        scroll: 1,
        auto: 6,
        animation: 'slow',
        easing: 'swing',
        itemLoadCallback: jQuery.noConflict()
    });
});");

echo $pretext;
echo "<div style='height:" . $scrollerheight . "px;'>";
echo "<div>
		<div class='red_product-skin-produkter'>
		<div style='display: block;' class='red_product-container red_product-container-horizontal'>
		<div style='display: block;' class='red_product-prev red_product-prev-horizontal'></div>
		<div style='display: block;left: " . ($scrollerwidth + 20) . "px;' class='red_product-next red_product-next-horizontal'></div>
		<div class='red_product-clip red_product-clip-horizontal' style='width: " . $scrollerwidth . "px;'>
		<ul id='rs_category_scroller_" . $module->id . "' class='red_product-list red_product-list-horizontal'>";

for ($i = 0, $countRows = count($rows); $i < $countRows; $i++)
{
	$row = $rows[$i];

	$ItemData = $producthelper->getMenuInformation(0, 0, '', 'product&pid=' . $row->product_id);

	if (count($ItemData) > 0)
	{
		$Itemid = $ItemData->id;
	}
	else
	{
		$Itemid = RedshopHelperUtility::getItemId($row->product_id);
	}

	$catattach = '';

	if ($row->category_id)
	{
		$catattach = '&cid=' . $row->category_id;
	}

	$link = JRoute::_('index.php?option=com_redshop&view=product&pid=' . $row->product_id . $catattach . '&Itemid=' . $Itemid);
	$url  = JURI::base();
	echo "<li red_productindex='" . $i . "' class='red_product-item red_product-item-horizontal'><div class='listing-item'><div class='product-shop'>";

	if ($show_product_name)
	{
		$pname = $config->maxchar($row->product_name, $product_title_max_chars, $product_title_end_suffix);
		echo "<a href='" . $link . "' title='" . $row->product_name . "'>" . $pname . "</a>";
	}

	if (Redshop::getConfig()->get('SHOW_PRICE') && !Redshop::getConfig()->get('USE_AS_CATALOG') && !Redshop::getConfig()->get('DEFAULT_QUOTATION_MODE') && $show_price && !$row->not_for_sale)
	{
		$productArr           = $producthelper->getProductNetPrice($row->product_id);
		$product_price        = $producthelper->getPriceReplacement($productArr['product_price']);
		$product_price_saving = $producthelper->getPriceReplacement($productArr['product_price_saving']);
		$product_old_price    = $producthelper->getPriceReplacement($productArr['product_old_price']);

		if ($show_discountpricelayout)
		{
			echo "<div id='mod_redoldprice' class='mod_redoldprice'><span style='text-decoration:line-through;'>" . $product_old_price . "</span></div>";
			echo "<div id='mod_redmainprice' class='mod_redmainprice'>" . $product_price . "</div>";
			echo "<div id='mod_redsavedprice' class='mod_redsavedprice'>" . JText::_('COM_REDSHOP_PRODCUT_PRICE_YOU_SAVED') . ' ' . $product_price_saving . "</div>";
		}
		else
		{
			echo "<div class='mod_redproducts_price'>" . $product_price . "</div>";
		}
	}

	if ($show_readmore)
	{
		echo "<div class='mod_redshop_category_scroller_readmore'><a href='" . $link . "'>" . JText::_('COM_REDSHOP_TXT_READ_MORE') . "</a></div>";
	}

	echo "</div>";

	if ($show_image)
	{
		$prod_img = "";

		if (JFile::exists(REDSHOP_FRONT_IMAGES_RELPATH . "/product/" . $row->product_full_image))
		{
			$prod_img = RedShopHelperImages::getImagePath(
							$row->product_full_image,
							'',
							'thumb',
							'product',
							$thumbwidth,
							$thumbheight,
							Redshop::getConfig()->get('USE_IMAGE_SIZE_SWAPPING')
						);
		}
		elseif (JFile::exists(REDSHOP_FRONT_IMAGES_RELPATH . "/product/" . $row->product_thumb_image))
		{
			$prod_img = RedShopHelperImages::getImagePath(
							$row->product_thumb_image,
							'',
							'thumb',
							'product',
							$thumbwidth,
							$thumbheight,
							Redshop::getConfig()->get('USE_IMAGE_SIZE_SWAPPING')
						);
		}
		else
		{
			$prod_img = REDSHOP_FRONT_IMAGES_ABSPATH . "noimage.jpg";
		}

		$thum_image = "<a href='" . $link . "'><img style='width:" . $thumbwidth . "px;height:" . $thumbheight . "px;' src='" . $prod_img . "'></a>";
		echo "<div class='product-image' style='width:" . $thumbwidth . "px;height:" . $thumbheight . "px;'>" . $thum_image . "</div>";
	}

	if ($show_addtocart)
	{
		// Product attribute  Start
		$attributes_set = array();

		if ($row->attribute_set_id > 0)
		{
			$attributes_set = $producthelper->getProductAttribute(0, $row->attribute_set_id, 0, 1);
		}

		$attributes = $producthelper->getProductAttribute($row->product_id);
		$attributes = array_merge($attributes, $attributes_set);
		$totalatt   = count($attributes);

		// Product accessory Start
		$accessory      = $producthelper->getProductAccessory(0, $row->product_id);
		$totalAccessory = count($accessory);

		$addtocart_data = $producthelper->replaceCartTemplate($row->product_id, 0, 0, 0, "", false, array(), $totalatt, $totalAccessory, 0, $module_id);
		echo "<div class='form-button'>" . $addtocart_data . "<div>";
	}

	echo "</div></li>";
}

echo "</ul></div></div></div></div></div>";
