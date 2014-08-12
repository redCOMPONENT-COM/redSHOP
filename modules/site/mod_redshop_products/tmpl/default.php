<?php
/**
 * @package     RedSHOP.Frontend
 * @subpackage  mod_redshop_products
 *
 * @copyright   Copyright (C) 2005 - 2013 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

defined('_JEXEC') or die('Restricted access');
JHTML::_('behavior.tooltip');
JHTML::_('behavior.modal');

$uri = JURI::getInstance();
$url = $uri->root();

$Itemid = JRequest::getInt('Itemid');
$user   = JFactory::getUser();
$option = 'com_redshop';

$document = JFactory::getDocument();
$document->addStyleSheet('modules/mod_redshop_products/css/products.css');

// Include redshop js file.
require_once JPATH_SITE . '/components/com_redshop/helpers/redshop.js.php';
JLoader::import('images', JPATH_ADMINISTRATOR . '/components/com_redshop/helpers');

// Lightbox Javascript
JHTML::Script('attribute.js', 'components/com_redshop/assets/js/', false);
JHTML::Script('common.js', 'components/com_redshop/assets/js/', false);
JHTML::Script('redbox.js', 'components/com_redshop/assets/js/', false);
JHTML::Stylesheet('fetchscript.css', 'components/com_redshop/assets/css/');


$producthelper   = new producthelper;
$redhelper       = new redhelper;
$redTemplate     = new Redtemplate;
$extraField      = new extraField;
$stockroomhelper = new rsstockroomhelper;


echo "<div class='mod_redshop_products_wrapper'>";

$module_id = "mod_" . $module->id;

for ($i = 0; $i < count($rows); $i++)
{
	$row = $rows[$i];

	if ($showStockroomStatus == 1)
	{
		$isStockExists = $stockroomhelper->isStockExists($row->product_id);

		if (!$isStockExists)
		{
			$isPreorderStockExists = $stockroomhelper->isPreorderStockExists($row->product_id);
		}

		if (!$isStockExists)
		{
			$product_preorder = $row->preorder;

			if (($product_preorder == "global" && ALLOW_PRE_ORDER) || ($product_preorder == "yes") || ($product_preorder == "" && ALLOW_PRE_ORDER))
			{
				if (!$isPreorderStockExists)
				{
					$stock_status = "<div  class='mod_product_outstock' align='center'>" . JText::_('COM_REDSHOP_OUT_OF_STOCK') . "</div>";
				}
				else
				{
					$stock_status = "<div  class='mod_product_preorder' align='center'>" . JText::_('COM_REDSHOP_PRE_ORDER') . "</div>";
				}
			}
			else
			{
				$stock_status = "<div  class='mod_product_outstock' align='center'>" . JText::_('COM_REDSHOP_OUT_OF_STOCK') . "</div>";
			}
		}
		else
		{
			$stock_status = "<div  class='mod_product_instock' align='center'>" . JText::_('COM_REDSHOP_AVAILABLE_STOCK') . "</div>";
		}
	}

	$category_id = $producthelper->getCategoryProduct($row->product_id);

	$ItemData = $producthelper->getMenuInformation(0, 0, '', 'product&pid=' . $row->product_id);

	if (count($ItemData) > 0)
	{
		$Itemid = $ItemData->id;
	}
	else
	{
		$Itemid = $redhelper->getItemid($row->product_id, $category_id);
	}

	$link = JRoute::_('index.php?option=' . $option . '&view=product&pid=' . $row->product_id . '&cid=' . $category_id . '&Itemid=' . $Itemid);

	if ($showProductVertically)
		echo "<div class='mod_redshop_products'>";
	else
		echo "<div class='mod_redshop_products_horizontal'>";

	$productInfo = $producthelper->getProductById($row->product_id);

	if ($image)
	{
		$thumb = $productInfo->product_full_image;

		if (WATERMARK_PRODUCT_IMAGE)
		{
			$thum_image = $redhelper->watermark('product', $thumb, $thumbWidth, $thumbHeight, WATERMARK_PRODUCT_THUMB_IMAGE, '0');
			echo "<div class='mod_redshop_products_image'><img src=" . $thum_image . "></div>";
		}
		else
		{
			$thum_image = RedShopHelperImages::getImagePath(
							$thumb,
							'',
							'thumb',
							'product',
							$thumbWidth,
							$thumbHeight,
							USE_IMAGE_SIZE_SWAPPING
						);
			echo "<div class='mod_redshop_products_image'><a href='" . $link . "' title='$row->product_name'><img src=" . $thum_image . "></a></div>";
		}
	}

	if (!empty($stock_status))
	{
		echo $stock_status;
	}

	echo "<div class='mod_redshop_products_title'><a href='" . $link . "' title=''>" . $row->product_name . "</a></div>";

	if ($showShortDescription)
	{
		echo "<div class='mod_redshop_products_desc'>" . $row->product_s_desc . "</div>";
	}

	if (!$row->not_for_sale && $showPrice)
	{
		$productArr = $producthelper->getProductNetPrice($row->product_id);

		if ($showVat != '0' || $showVatprice != 0)
		{
			$product_price          = $productArr['product_main_price'];
			$product_price_discount = $productArr['productPrice'] + $productArr['productVat'];
		}
		else
		{
			$product_price          = $productArr['product_price_novat'];
			$product_price_discount = $productArr['productPrice'];
		}

		if (SHOW_PRICE && (!DEFAULT_QUOTATION_MODE || (DEFAULT_QUOTATION_MODE && SHOW_QUOTATION_PRICE)))
		{
			if (!$product_price)
			{
				$product_price_dis = $producthelper->getPriceReplacement($product_price);
			}
			else
			{
				$product_price_dis = $producthelper->getProductFormattedPrice($product_price);
			}

			$disply_text = "<div class='mod_redshop_products_price'>" . $product_price_dis . "</div>";

			if ($row->product_on_sale && $product_price_discount > 0)
			{
				if ($product_price > $product_price_discount)
				{
					$disply_text = "";
					$s_price     = $product_price - $product_price_discount;

					if ($showDiscountPriceLayout)
					{
						echo "<div id='mod_redoldprice' class='mod_redoldprice'><span style='text-decoration:line-through;'>" . $producthelper->getProductFormattedPrice($product_price) . "</span></div>";
						$product_price = $product_price_discount;
						echo "<div id='mod_redmainprice' class='mod_redmainprice'>" . $producthelper->getProductFormattedPrice($product_price_discount) . "</div>";
						echo "<div id='mod_redsavedprice' class='mod_redsavedprice'>" . JText::_('COM_REDSHOP_PRODCUT_PRICE_YOU_SAVED') . ' ' . $producthelper->getProductFormattedPrice($s_price) . "</div>";
					}
					else
					{
						$product_price = $product_price_discount;
						echo "<div class='mod_redshop_products_price'>" . $producthelper->getProductFormattedPrice($product_price) . "</div>";
					}
				}
			}

			echo $disply_text;
		}
	}

	if ($showReadmore)
	{
		echo "<div class='mod_redshop_products_readmore'><a href='" . $link . "'>" . JText::_('COM_REDSHOP_TXT_READ_MORE') . "</a>&nbsp;</div>";
	}

	if ($showAddToCart)
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

		// Product attribute  End


		// Product accessory Start
		$accessory      = $producthelper->getProductAccessory(0, $row->product_id);
		$totalAccessory = count($accessory);

		// Product accessory End


		/*
		 * collecting extra fields
		 */
		$count_no_user_field = 0;
		$hidden_userfield    = "";
		$userfieldArr        = array();

		if (AJAX_CART_BOX)
		{
			$ajax_detail_template_desc = "";
			$ajax_detail_template      = $producthelper->getAjaxDetailboxTemplate($row);

			if (count($ajax_detail_template) > 0)
			{
				$ajax_detail_template_desc = $ajax_detail_template->template_desc;
			}

			$returnArr          = $producthelper->getProductUserfieldFromTemplate($ajax_detail_template_desc);
			$template_userfield = $returnArr[0];
			$userfieldArr       = $returnArr[1];

			if ($template_userfield != "")
			{
				$ufield = "";

				for ($ui = 0; $ui < count($userfieldArr); $ui++)
				{
					$product_userfileds = $extraField->list_all_user_fields($userfieldArr[$ui], 12, '', '', 0, $row->product_id);
					$ufield .= $product_userfileds[1];

					if ($product_userfileds[1] != "")
					{
						$count_no_user_field++;
					}

					$template_userfield = str_replace('{' . $userfieldArr[$ui] . '_lbl}', $product_userfileds[0], $template_userfield);
					$template_userfield = str_replace('{' . $userfieldArr[$ui] . '}', $product_userfileds[1], $template_userfield);
				}

				if ($ufield != "")
				{
					$hidden_userfield = "<div style='display:none;'><form method='post' action='' id='user_fields_form_" . $row->product_id . "' name='user_fields_form_" . $row->product_id . "'>" . $template_userfield . "</form></div>";
				}
			}
		}

		// End

		$addtocart = $producthelper->replaceCartTemplate($row->product_id, $category_id, 0, 0, "", false, $userfieldArr, $totalatt, $totalAccessory, $count_no_user_field, $module_id);
		echo "<div class='mod_redshop_products_addtocart'>" . $addtocart . $hidden_userfield . "</div>";
	}

	echo "</div>";
}

echo "</div>";
