<?php
/**
 * @package     RedSHOP.Frontend
 * @subpackage  mod_redshop_who_bought
 *
 * @copyright   Copyright (C) 2008 - 2015 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

defined('_JEXEC') or die;

$Itemid = JRequest::getInt('Itemid');
$user = JFactory::getUser();

$document = JFactory::getDocument();
JHtml::stylesheet("modules/mod_redshop_who_bought/assets/css/skin.css");
$document->addStyleDeclaration('
	.jcarousel-skin-tango .jcarousel-container-horizontal {
		width:' . $sliderwidth . 'px;
	}
	.jcarousel-skin-tango .jcarousel-item {
		width:' . ($sliderwidth / 2 - 8) . 'px;
	}
');

JHtml::_('redshopjquery.framework');
JHtml::script('modules/mod_redshop_who_bought/assets/js/jquery.jcarousel.min.js');

$producthelper = productHelper::getInstance();
$redhelper = redhelper::getInstance();
$redTemplate = Redtemplate::getInstance();
$extraField = extraField::getInstance();
$module_id = "mod_" . $module->id;

JHtml::script('com_redshop/common.js', false, true);
JHtml::script('com_redshop/redbox.js', false, true);

JFactory::getDocument()->addScriptDeclaration('
	jQuery(document).ready(function () {
		jQuery(\'#mycarousel_' . $module->id . '\').jcarousel();
	});');

echo '<ul id="mycarousel_' . $module->id . '" class="jcarousel-skin-tango">';

if (count($rows))
{
	foreach ($rows as $product)
	{
		$category_id = $producthelper->getCategoryProduct($product->product_id);

		$attributes_set = array();

		if ($product->attribute_set_id > 0)
		{
			$attributes_set = $producthelper->getProductAttribute(0, $product->attribute_set_id, 0, 1);
		}

		$attributes = $producthelper->getProductAttribute($product->product_id);
		$attributes = array_merge($attributes, $attributes_set);
		$totalatt   = count($attributes);

		$accessory      = $producthelper->getProductAccessory(0, $product->product_id);
		$totalAccessory = count($accessory);

		/*
		 * collecting extra fields
		 */
		$count_no_user_field = 0;
		$hidden_userfield    = "";
		$userfieldArr        = array();

		if (Redshop::getConfig()->get('AJAX_CART_BOX'))
		{
			$ajax_detail_template_desc = "";
			$ajax_detail_template      = $producthelper->getAjaxDetailboxTemplate($product);

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
					$productUserFields = $extraField->list_all_user_fields($userfieldArr[$ui], 12, '', '', 0, $product->product_id);
					$ufield .= $productUserFields[1];

					if ($productUserFields[1] != "")
					{
						$count_no_user_field++;
					}

					$template_userfield = str_replace('{' . $userfieldArr[$ui] . '_lbl}', $productUserFields[0], $template_userfield);
					$template_userfield = str_replace('{' . $userfieldArr[$ui] . '}', $productUserFields[1], $template_userfield);
				}

				if ($ufield != "")
				{
					$hidden_userfield = "<div style='display:none;'><form method='post' action='' id='user_fields_form_" . $product->product_id . "' name='user_fields_form_" . $product->product_id . "'>" . $template_userfield . "</form></div>";
				}
			}
		}


		echo " <li>";

		if ($show_product_image)
		{
			if (!JFile::exists(REDSHOP_FRONT_IMAGES_RELPATH . 'product/' . $product->product_full_image))
			{
				$file_path = JPATH_SITE . '/components/com_redshop/assets/images/noimage.jpg';
				$filename = RedShopHelperImages::generateImages(
					$file_path, '', $thumbwidth, $thumbheight, 'thumb', Redshop::getConfig()->get('USE_IMAGE_SIZE_SWAPPING')
				);
				$filename_path_info = pathinfo($filename);
				$thumbImage = REDSHOP_FRONT_IMAGES_ABSPATH . 'thumb/' . $filename_path_info['basename'];
			}
			elseif (Redshop::getConfig()->get('WATERMARK_PRODUCT_IMAGE'))
			{
				$thumbImage = RedshopHelperMedia::watermark('product', $product->product_full_image, $thumbwidth, $thumbheight, Redshop::getConfig()->get('WATERMARK_PRODUCT_THUMB_IMAGE'), '0');
			}
			else
			{
				$thumbImage = RedShopHelperImages::getImagePath(
					$product->product_full_image,
					'',
					'thumb',
					'product',
					$thumbwidth,
					$thumbheight,
					Redshop::getConfig()->get('USE_IMAGE_SIZE_SWAPPING')
				);
			}

			echo "<div class=\"imageWhoBought\" style=\"min-height:" . $thumbheight . "px\"><img src='" . $thumbImage . "' /></div>";
		}

		if ($show_addtocart_button)
		{
			echo "<div>&nbsp;</div>";
			$addtocart = $producthelper->replaceCartTemplate($product->product_id, $category_id, 0, 0, "", false, $userfieldArr, $totalatt, $totalAccessory, $count_no_user_field, $module_id);
			echo "<div class='mod_redshop_products_addtocart addToCartWhoBought'>" . $addtocart . $hidden_userfield . "</div>";
		}

		if ($show_product_name)
		{
			$pItemid = RedshopHelperUtility::getItemId($product->product_id);
			$link = JRoute::_(
					'index.php?option=com_redshop&view=product&pid=' . $product->product_id . '&Itemid=' . $pItemid
			);

			echo "<div>&nbsp;</div>";

			if ($product_title_linkable)
			{
				echo "<div style='text-align:center;'>";
					echo "<a href='" . $link . "'>";
						echo $product->product_name;
					echo "</a>";
				echo "</div>";
			}
			else
			{
				echo "<div style='text-align:center;'>" . $product->product_name . "</div>";
			}
		}

		if ($show_product_price && $product->product_price)
		{
			if (Redshop::getConfig()->get('SHOW_PRICE') && (!Redshop::getConfig()->get('DEFAULT_QUOTATION_MODE') || (Redshop::getConfig()->get('DEFAULT_QUOTATION_MODE') && SHOW_QUOTATION_PRICE)))
			{
				echo "<div class=\"priceWhoBought\">" . $producthelper->getProductFormattedPrice($product->product_price) . "</div>";
			}
		}
	}

	echo "</li>";
}

echo "</ul>";
