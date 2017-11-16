<?php
/**
 * @package     RedSHOP.Frontend
 * @subpackage  mod_redshop_products
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
$option = 'com_redshop';

$document = JFactory::getDocument();
$document->addStyleSheet('modules/mod_redshop_products/css/products.css');

JLoader::load('RedshopHelperAdminImages');

// Lightbox Javascript
JHtml::script('com_redshop/attribute.js', false, true);
JHtml::script('com_redshop/common.js', false, true);
JHtml::script('com_redshop/redbox.js', false, true);

$producthelper   = new producthelper;
$redhelper       = new redhelper;
$redTemplate     = new Redtemplate;
$extraField      = new extraField;
$stockroomhelper = new rsstockroomhelper;


echo "<div class=\"mod_redshop_products_wrapper\">";

$moduleId = "mod_" . $module->id;

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
			$productPreorder = $row->preorder;

			if (($productPreorder == "global" && ALLOW_PRE_ORDER) || ($productPreorder == "yes") || ($productPreorder == "" && ALLOW_PRE_ORDER))
			{
				if (!$isPreorderStockExists)
				{
					$stockStatus = "<div class=\"modProductStockStatus mod_product_outstock\"><span></span>" . JText::_('COM_REDSHOP_OUT_OF_STOCK') . "</div>";
				}
				else
				{
					$stockStatus = "<div class=\"modProductStockStatus mod_product_preorder\"><span></span>" . JText::_('COM_REDSHOP_PRE_ORDER') . "</div>";
				}
			}
			else
			{
				$stockStatus = "<div class=\"modProductStockStatus mod_product_outstock\"><span></span>" . JText::_('COM_REDSHOP_OUT_OF_STOCK') . "</div>";
			}
		}
		else
		{
			$stockStatus = "<div class=\"modProductStockStatus mod_product_instock\"><span></span>" . JText::_('COM_REDSHOP_AVAILABLE_STOCK') . "</div>";
		}
	}

	$categoryId = $producthelper->getCategoryProduct($row->product_id);

	$ItemData = $producthelper->getMenuInformation(0, 0, '', 'product&pid=' . $row->product_id);

	if (count($ItemData) > 0)
	{
		$Itemid = $ItemData->id;
	}
	else
	{
		$Itemid = RedshopHelperUtility::getItemId($row->product_id, $categoryId);
	}

	$link = JRoute::_('index.php?option=com_redshop&view=product&pid=' . $row->product_id . '&cid=' . $categoryId . '&Itemid=' . $Itemid);

	if (isset($verticalProduct) && $verticalProduct)
		echo "<div class=\"mod_redshop_products\">";
	else
		echo "<div class=\"mod_redshop_products_horizontal\">";

	$productInfo = $producthelper->getProductById($row->product_id);

	if ($image)
	{
		$thumb = $productInfo->product_full_image;

		if (WATERMARK_PRODUCT_IMAGE)
		{
			$thumImage = RedshopHelperMedia::watermark('product', $thumb, $thumbWidth, $thumbHeight, WATERMARK_PRODUCT_THUMB_IMAGE, '0');
			echo "<div class=\"mod_redshop_products_image\"><img src=\"" . $thumImage . "\"></div>";
		}
		else
		{
			$thumImage = RedShopHelperImages::getImagePath(
							$thumb,
							'',
							'thumb',
							'product',
							$thumbWidth,
							$thumbHeight,
							USE_IMAGE_SIZE_SWAPPING
						);
			echo "<div class=\"mod_redshop_products_image\"><a href=\"" . $link . "\" title=\"$row->product_name\"><img src=\"" . $thumImage . "\"></a></div>";
		}
	}

	if (!empty($stockStatus))
	{
		echo $stockStatus;
	}

	echo "<div class=\"mod_redshop_products_title\"><a href=\"" . $link . "\" title=\"\">" . $row->product_name . "</a></div>";

	if ($showShortDescription)
	{
		echo "<div class=\"mod_redshop_products_desc\">" . $row->product_s_desc . "</div>";
	}

	if (!$row->not_for_sale && $showPrice)
	{
		$productArr = $producthelper->getProductNetPrice($row->product_id);

		if ($showVat != '0')
		{
			$productPrice           = $productArr['product_main_price'];
			$productPriceDiscount   = $productArr['productPrice'] + $productArr['productVat'];
			$productOldPrice 		= $productArr['product_old_price'];
		}
		else
		{
			$productPrice          = $productArr['product_price_novat'];
			$productPriceDiscount = $productArr['productPrice'];
			$productOldPrice 		= $productArr['product_old_price_excl_vat'];
		}

		if (SHOW_PRICE && (!DEFAULT_QUOTATION_MODE || (DEFAULT_QUOTATION_MODE && SHOW_QUOTATION_PRICE)))
		{
			if (!$productPrice)
			{
				$productDiscountPrice = $producthelper->getPriceReplacement($productPrice);
			}
			else
			{
				$productDiscountPrice = $producthelper->getProductFormattedPrice($productPrice);
			}

			$displyText = "<div class=\"mod_redshop_products_price\">" . $productDiscountPrice . "</div>";

			if ($row->product_on_sale && $productPriceDiscount > 0)
			{
				if ($productOldPrice > $productPriceDiscount)
				{
					$displyText = "";
					$savingPrice     = $productOldPrice - $productPriceDiscount;

					if ($showDiscountPriceLayout)
					{
						echo "<div id=\"mod_redoldprice\" class=\"mod_redoldprice\">" . $producthelper->getProductFormattedPrice($productOldPrice) . "</div>";
						$productPrice = $productPriceDiscount;
						echo "<div id=\"mod_redmainprice\" class=\"mod_redmainprice\">" . $producthelper->getProductFormattedPrice($productPriceDiscount) . "</div>";
						echo "<div id=\"mod_redsavedprice\" class=\"mod_redsavedprice\">" . JText::_('COM_REDSHOP_PRODCUT_PRICE_YOU_SAVED') . ' ' . $producthelper->getProductFormattedPrice($savingPrice) . "</div>";
					}
					else
					{
						$productPrice = $productPriceDiscount;
						echo "<div class=\"mod_redshop_products_price\">" . $producthelper->getProductFormattedPrice($productPrice) . "</div>";
					}
				}
			}

			echo $displyText;
		}
	}

	if ($showReadmore)
	{
		echo "<div class=\"mod_redshop_products_readmore\"><a href=\"" . $link . "\">" . JText::_('COM_REDSHOP_TXT_READ_MORE') . "</a>&nbsp;</div>";
	}

	if (isset($showAddToCart) && $showAddToCart)
	{
		// Product attribute  Start
		$attributesSet = array();

		if ($row->attribute_set_id > 0)
		{
			$attributesSet = $producthelper->getProductAttribute(0, $row->attribute_set_id, 0, 1);
		}

		$attributes = $producthelper->getProductAttribute($row->product_id);
		$attributes = array_merge($attributes, $attributesSet);
		$totalatt   = count($attributes);

		// Product attribute  End


		// Product accessory Start
		$accessory      = $producthelper->getProductAccessory(0, $row->product_id);
		$totalAccessory = count($accessory);

		// Product accessory End


		/*
		 * collecting extra fields
		 */
		$countNoUserField = 0;
		$hiddenUserField = '';
		$userfieldArr = array();

		if (AJAX_CART_BOX)
		{
			$ajaxDetailTemplateDesc = "";
			$ajaxDetailTemplate      = $producthelper->getAjaxDetailboxTemplate($row);

			if (count($ajaxDetailTemplate) > 0)
			{
				$ajaxDetailTemplateDesc = $ajaxDetailTemplate->template_desc;
			}

			$returnArr          = $producthelper->getProductUserfieldFromTemplate($ajaxDetailTemplateDesc);
			$templateUserfield = $returnArr[0];
			$userfieldArr       = $returnArr[1];

			if ($templateUserfield != "")
			{
				$ufield = "";

				for ($ui = 0; $ui < count($userfieldArr); $ui++)
				{
					$productUserfileds = $extraField->list_all_user_fields($userfieldArr[$ui], 12, '', '', 0, $row->product_id);
					$ufield .= $productUserfileds[1];

					if ($productUserfileds[1] != "")
					{
						$countNoUserField++;
					}

					$templateUserfield = str_replace('{' . $userfieldArr[$ui] . '_lbl}', $productUserfileds[0], $templateUserfield);
					$templateUserfield = str_replace('{' . $userfieldArr[$ui] . '}', $productUserfileds[1], $templateUserfield);
				}

				if ($ufield != "")
				{
					$hiddenUserField = "<div class=\"hiddenFields\"><form method=\"post\" action=\"\" id=\"user_fields_form_" . $row->product_id . "\" name=\"user_fields_form_" . $row->product_id . "\">" . $templateUserfield . "</form></div>";
				}
			}
		}

		// End

		$addtocart = $producthelper->replaceCartTemplate($row->product_id, $categoryId, 0, 0, "", false, $userfieldArr, $totalatt, $totalAccessory, $countNoUserField, $moduleId);
		echo "<div class=\"mod_redshop_products_addtocart\">" . $addtocart . $hiddenUserField . "</div>";
	}

	echo "</div>";
}

echo "</div>";
