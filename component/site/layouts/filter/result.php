<?php
/**
 * @package    RedPRODUCTFINDER.Backend
 *
 * @copyright  Copyright (C) 2008 - 2015 redCOMPONENT.com. All rights reserved.
 * @license    GNU General Public License version 2 or later; see LICENSE
 */
defined('_JEXEC') or die;

JLoader::import('redshop.library');

$products   = $displayData["products"];
$templateId = $displayData['template_id'];
$pk         = $displayData["post"];
$cid        = $pk["cid"] ? $pk["cid"] : 0;
$model      = $displayData["model"];
$app        = JFactory::getApplication();
$input      = $app->input;

$productHelper    = productHelper::getInstance();
$objHelper        = redhelper::getInstance();
RedshopHelperUtility::defineDynamicVariables();

$extraField      = extraField::getInstance();
$stockroomHelper = rsstockroomhelper::getInstance();
$redTemplate     = Redtemplate::getInstance();

$list = array(
	JHtml::_('select.option', '', JText::_('COM_REDSHOP_SELECT')),
	JHtml::_('select.option', 'p.product_price', JText::_('COM_REDSHOP_PRODUCT_PRICE_ASC')),
	JHtml::_('select.option', 'p.product_price desc', JText::_('COM_REDSHOP_PRODUCT_PRICE_DESC')),
	JHtml::_('select.option', 'p.product_id', JText::_('COM_REDSHOP_NEWEST'))
);

$getOrderBy = $input->get('order_by', DEFAULT_PRODUCT_ORDERING_METHOD);

$lists['order_select'] = JHtml::_('select.genericlist', $list, 'orderBy', 'class="inputbox" size="1" onchange="order(this);" ', 'value', 'text', $getOrderBy);

$count_no_user_field = 0;
$productData         = '';
$extraFieldName      = $extraField->getSectionFieldNameArray(1, 1, 1);

JPluginHelper::importPlugin('redshop_product');

$dispatcher = RedshopHelperUtility::getDispatcher();
$params     = $app->getParams('com_redshop');

// Check Itemid on pagination
$Itemid = $input->get('Itemid', 0, "int");

$start = $input->get('limitstart', 0, '', 'int');

$fieldArray = RedshopHelperExtrafields::getSectionFieldList(17, 0, 0);

$templateArray     = RedshopHelperTemplate::getTemplate("redproductfinder", $templateId);
$templateDesc      = $templateArray[0]->template_desc;
$attributeTemplate = $productHelper->getAttributeTemplate($templateDesc);

// Begin replace template
$templateDesc = str_replace("{total_product_lbl}", JText::_('COM_REDSHOP_TOTAL_PRODUCT'), $templateDesc);
$templateDesc = str_replace("{total_product}", $displayData['total'], $templateDesc);

if (strpos($templateDesc, "{product_loop_start}") !== false && strpos($templateDesc, "{product_loop_end}") !== false)
{
	// Get only Product template
	$templateD1      = explode("{product_loop_start}", $templateDesc);
	$templateD2      = explode("{product_loop_end}", $templateD1[1]);
	$templateProduct = $templateD2[0];

	$attributeTemplate = $productHelper->getAttributeTemplate($templateProduct);

	// Loop product lists
	foreach ($products as $k => $pid)
	{
		$product = RedshopHelperProduct::getProductById($pid);
		$catid   = $product->category_id;

		// Count accessory
		$accessorylist = RedshopHelperAccessory::getProductAccessories(0, $product->product_id);
		$totacc        = count($accessorylist);
		$netPrice      = $productHelper->getProductNetPrice($pid);
		$productPrice  = $netPrice['productPrice'];

		$dataAdd = $templateProduct;

		// ProductFinderDatepicker Extra Field Start
		$dataAdd = $productHelper->getProductFinderDatepickerValue($templateProduct, $product->product_id, $fieldArray);

		$ItemData = $productHelper->getMenuInformation(0, 0, '', 'product&pid=' . $product->product_id);

		$catidmain = $input->get('cid');

		if (count($ItemData) > 0)
		{
			$pItemid = $ItemData->id;
		}
		else
		{
			$pItemid = RedshopHelperUtility::getItemId($product->product_id, $catidmain);
		}

		$dataAdd               = str_replace("{product_price}", $productHelper->getProductFormattedPrice($productPrice), $dataAdd);
		$dataAdd               = str_replace("{product_id_lbl}", JText::_('COM_REDSHOP_PRODUCT_ID_LBL'), $dataAdd);
		$dataAdd               = str_replace("{product_id}", $product->product_id, $dataAdd);
		$dataAdd               = str_replace("{product_number_lbl}", JText::_('COM_REDSHOP_PRODUCT_NUMBER_LBL'), $dataAdd);
		$product_number_output = '<span id="product_number_variable' . $product->product_id . '">' . $product->product_number . '</span>';
		$dataAdd               = str_replace("{product_number}", $product_number_output, $dataAdd);

		// Replace VAT information
		$dataAdd = RedshopHelperTax::replaceVatInformation($dataAdd);

		$link = JRoute::_('index.php?option=com_redshop&view=product&pid=' . $product->product_id . '&cid=' . $catid . '&Itemid=' . $pItemid);

		$pname = RedshopHelperUtility::maxChars($product->product_name, CATEGORY_PRODUCT_TITLE_MAX_CHARS, CATEGORY_PRODUCT_TITLE_END_SUFFIX);

		$product_nm = $pname;

		if (strstr($dataAdd, '{product_name_nolink}'))
		{
			$dataAdd = str_replace("{product_name_nolink}", $product_nm, $dataAdd);
		}

		if (strstr($dataAdd, '{product_name}'))
		{
			$pname   = "<a href='" . $link . "' title='" . $product->product_name . "'>" . $pname . "</a>";
			$dataAdd = str_replace("{product_name}", $pname, $dataAdd);
		}

		if (strstr($dataAdd, '{category_product_link}'))
		{
			$dataAdd = str_replace("{category_product_link}", $link, $dataAdd);
		}

		if (strstr($dataAdd, '{read_more}'))
		{
			$rmore   = "<a href='" . $link . "' title='" . $product->product_name . "'>" . JText::_('COM_REDSHOP_READ_MORE') . "</a>";
			$dataAdd = str_replace("{read_more}", $rmore, $dataAdd);
		}

		if (strstr($dataAdd, '{read_more_link}'))
		{
			$dataAdd = str_replace("{read_more_link}", $link, $dataAdd);
		}

		if (strstr($dataAdd, '{product_s_desc}'))
		{
			$p_s_desc = RedshopHelperUtility::maxChars($product->product_s_desc, CATEGORY_PRODUCT_SHORT_DESC_MAX_CHARS, CATEGORY_PRODUCT_SHORT_DESC_END_SUFFIX);
			$dataAdd  = str_replace("{product_s_desc}", $p_s_desc, $dataAdd);
		}

		if (strstr($dataAdd, '{product_desc}'))
		{
			$p_desc  = RedshopHelperUtility::maxChars($product->product_desc, CATEGORY_PRODUCT_DESC_MAX_CHARS, CATEGORY_PRODUCT_DESC_END_SUFFIX);
			$dataAdd = str_replace("{product_desc}", $p_desc, $dataAdd);
		}

		if (strstr($dataAdd, '{product_rating_summary}'))
		{
			// Product Review/Rating Fetching reviews
			$final_avgreview_data = $productHelper->getProductRating($product->product_id);
			$dataAdd              = str_replace("{product_rating_summary}", $final_avgreview_data, $dataAdd);
		}

		if (strstr($dataAdd, '{manufacturer_link}'))
		{
			$manufacturer_link_href = JRoute::_('index.php?option=com_redshop&view=manufacturers&layout=detail&mid=' . $product->manufacturer_id . '&Itemid=' . $Itemid);

			if ($product->manufacturer_name = '')
			{
				$manufacturer_link = '';
			}
			else
			{
				$manufacturer_link = '<a href="' . $manufacturer_link_href . '" title="' . $product->manufacturer_name . '">' . $product->manufacturer_name . '</a>';
			}

			$dataAdd = str_replace("{manufacturer_link}", $manufacturer_link, $dataAdd);

			if (strstr($dataAdd, "{manufacturer_link}"))
			{
				$dataAdd = str_replace("{manufacturer_name}", "", $dataAdd);
			}
		}

		if (strstr($dataAdd, '{manufacturer_product_link}'))
		{
			$manufacturerPLink = "<a href='" . JRoute::_('index.php?option=com_redshop&view=manufacturers&layout=products&mid=' . $product->manufacturer_id . '&Itemid=' . $Itemid) . "'>" . JText::_("COM_REDSHOP_VIEW_ALL_MANUFACTURER_PRODUCTS") . " " . $product->manufacturer_name . "</a>";
			$dataAdd           = str_replace("{manufacturer_product_link}", $manufacturerPLink, $dataAdd);
		}

		if (strstr($dataAdd, '{manufacturer_name}'))
		{
			if ($product->manufacturer_name != "")
			{
				$dataAdd = str_replace("{manufacturer_name}", $product->manufacturer_name, $dataAdd);
			}
			else
			{
				$dataAdd = str_replace("{manufacturer_name}", "", $dataAdd);
			}
		}

		$extraFieldsForCurrentTemplate = RedshopHelperTemplate::getExtraFieldsForCurrentTemplate($extraFieldName, $templateProduct, 1);

		/*
		 * Product loop template extra field
		 * lat arg set to "1" for identify parsing data for product tag loop in category
		 * last arg will parse {producttag:NAMEOFPRODUCTTAG} nameing tags.
		 * "1" is for section as product
		 */
		if ($extraFieldsForCurrentTemplate)
		{
			$dataAdd = $extraField->extra_field_display(1, $product->product_id, $extraFieldsForCurrentTemplate, $dataAdd, 1);
		}

		if (strstr($dataAdd, "{product_thumb_image_3}"))
		{
			$pimg_tag = '{product_thumb_image_3}';
			$ph_thumb = CATEGORY_PRODUCT_THUMB_HEIGHT_3;
			$pw_thumb = CATEGORY_PRODUCT_THUMB_WIDTH_3;
		}
		elseif (strstr($dataAdd, "{product_thumb_image_2}"))
		{
			$pimg_tag = '{product_thumb_image_2}';
			$ph_thumb = CATEGORY_PRODUCT_THUMB_HEIGHT_2;
			$pw_thumb = CATEGORY_PRODUCT_THUMB_WIDTH_2;
		}
		elseif (strstr($dataAdd, "{product_thumb_image_1}"))
		{
			$pimg_tag = '{product_thumb_image_1}';
			$ph_thumb = CATEGORY_PRODUCT_THUMB_HEIGHT;
			$pw_thumb = CATEGORY_PRODUCT_THUMB_WIDTH;
		}
		else
		{
			$pimg_tag = '{product_thumb_image}';
			$ph_thumb = CATEGORY_PRODUCT_THUMB_HEIGHT;
			$pw_thumb = CATEGORY_PRODUCT_THUMB_WIDTH;
		}

		$hidden_thumb_image = "<input type='hidden' name='prd_main_imgwidth' id='prd_main_imgwidth' value='" . $pw_thumb . "'><input type='hidden' name='prd_main_imgheight' id='prd_main_imgheight' value='" . $ph_thumb . "'>";
		$thum_image         = $productHelper->getProductImage($product->product_id, $link, $pw_thumb, $ph_thumb, 2, 1);
		/* product image flying addwishlist time start */
		$thum_image = "<span class='productImageWrap' id='productImageWrapID_" . $product->product_id . "'>" . $productHelper->getProductImage($product->product_id, $link, $pw_thumb, $ph_thumb, 2, 1) . "</span>";

		/* product image flying addwishlist time end*/
		$dataAdd = str_replace($pimg_tag, $thum_image . $hidden_thumb_image, $dataAdd);

		/* front-back image tag */
		if (strstr($dataAdd, "{front_img_link}") || strstr($dataAdd, "{back_img_link}"))
		{
			if ($this->_data->product_thumb_image)
			{
				$mainsrcPath = REDSHOP_FRONT_IMAGES_ABSPATH . "product/" . $product->product_thumb_image;
			}
			else
			{
				$mainsrcPath = $url . "components/com_redshop/helpers/thumb.php?filename=product/" . $product->product_full_image . "&newxsize=" . $pw_thumb . "&newysize=" . $ph_thumb . "&swap=" . USE_IMAGE_SIZE_SWAPPING;
			}

			if ($this->_data->product_back_thumb_image)
			{
				$backsrcPath = REDSHOP_FRONT_IMAGES_ABSPATH . "product/" . $product->product_back_thumb_image;
			}
			else
			{
				$backsrcPath = $url . "components/com_redshop/helpers/thumb.php?filename=product/" . $product->product_back_full_image . "&newxsize=" . $pw_thumb . "&newysize=" . $ph_thumb . "&swap=" . USE_IMAGE_SIZE_SWAPPING;
			}

			$ahrefpath     = REDSHOP_FRONT_IMAGES_ABSPATH . "product/" . $product->product_full_image;
			$ahrefbackpath = REDSHOP_FRONT_IMAGES_ABSPATH . "product/" . $product->product_back_full_image;

			$product_front_image_link = "<a href='#' onClick='javascript:changeproductImage(" . $product->product_id . ",\"" . $mainsrcPath . "\",\"" . $ahrefpath . "\");'>" . JText::_('COM_REDSHOP_FRONT_IMAGE') . "</a>";
			$product_back_image_link  = "<a href='#' onClick='javascript:changeproductImage(" . $product->product_id . ",\"" . $backsrcPath . "\",\"" . $ahrefbackpath . "\");'>" . JText::_('COM_REDSHOP_BACK_IMAGE') . "</a>";

			$dataAdd = str_replace("{front_img_link}", $product_front_image_link, $dataAdd);
			$dataAdd = str_replace("{back_img_link}", $product_back_image_link, $dataAdd);
		}
		else
		{
			$dataAdd = str_replace("{front_img_link}", "", $dataAdd);
			$dataAdd = str_replace("{back_img_link}", "", $dataAdd);
		}
		/* front-back image tag end */


		/* product preview image. */
		if (strstr($dataAdd, '{product_preview_img}'))
		{
			if (JFile::exists(REDSHOP_FRONT_IMAGES_RELPATH . 'product/' . $product->product_preview_image))
			{
				$previewsrcPath = $url . "components/com_redshop/helpers/thumb.php?filename=product/" . $product->product_preview_image . "&newxsize=" . CATEGORY_PRODUCT_PREVIEW_IMAGE_WIDTH . "&newysize=" . CATEGORY_PRODUCT_PREVIEW_IMAGE_HEIGHT . "&swap=" . USE_IMAGE_SIZE_SWAPPING;
				$previewImg     = "<img src='" . $previewsrcPath . "' class='rs_previewImg' />";
				$dataAdd        = str_replace("{product_preview_img}", $previewImg, $dataAdd);
			}
			else
			{
				$dataAdd = str_replace("{product_preview_img}", "", $dataAdd);
			}
		}

		// 	product preview image end.

		/* front-back preview image tag... */
		if (strstr($dataAdd, "{front_preview_img_link}") || strstr($dataAdd, "{back_preview_img_link}"))
		{
			if ($product->product_preview_image)
			{
				$mainpreviewsrcPath = REDSHOP_FRONT_IMAGES_ABSPATH . "product/" . $product->product_preview_image . "&newxsize=" . CATEGORY_PRODUCT_PREVIEW_IMAGE_WIDTH . "&newysize=" . CATEGORY_PRODUCT_PREVIEW_IMAGE_HEIGHT . "&swap=" . USE_IMAGE_SIZE_SWAPPING;
			}

			if ($product->product_preview_back_image)
			{
				$backpreviewsrcPath = REDSHOP_FRONT_IMAGES_ABSPATH . "product/" . $product->product_preview_back_image . "&newxsize=" . CATEGORY_PRODUCT_PREVIEW_IMAGE_WIDTH . "&newysize=" . CATEGORY_PRODUCT_PREVIEW_IMAGE_HEIGHT . "&swap=" . USE_IMAGE_SIZE_SWAPPING;
			}

			$product_front_image_link = "<a href='#' onClick='javascript:changeproductPreviewImage(" . $product->product_id . ",\"" . $mainpreviewsrcPath . "\");'>" . JText::_('COM_REDSHOP_FRONT_IMAGE') . "</a>";
			$product_back_image_link  = "<a href='#' onClick='javascript:changeproductPreviewImage(" . $product->product_id . ",\"" . $backpreviewsrcPath . "\");'>" . JText::_('COM_REDSHOP_BACK_IMAGE') . "</a>";

			$dataAdd = str_replace("{front_preview_img_link}", $product_front_image_link, $dataAdd);
			$dataAdd = str_replace("{back_preview_img_link}", $product_back_image_link, $dataAdd);
		}
		else
		{
			$dataAdd = str_replace("{front_preview_img_link}", "", $dataAdd);
			$dataAdd = str_replace("{back_preview_img_link}", "", $dataAdd);
		}
		/* front-back preview image tag end */

		$dataAdd = $productHelper->getJcommentEditor($product, $dataAdd);

		/************************************
		 *  Conditional tag
		 *  if product on discount : Yes
		 *  {if product_on_sale} This product is on sale {product_on_sale end if} // OUTPUT : This product is on sale
		 *  NO : // OUTPUT : Display blank
		 ************************************/
		$dataAdd = $productHelper->getProductOnSaleComment($product, $dataAdd);

		/* replace Wishlist Button */
		$dataAdd = RedshopHelperWishlist::replaceWishlistTag($product->product_id, $dataAdd);

		/* replace compare product button */
		$dataAdd = $productHelper->replaceCompareProductsButton($product->product_id, $catid, $dataAdd);

		if (strstr($dataAdd, "{stockroom_detail}"))
		{
			$dataAdd = RedshopHelperStockroom::replaceStockroomAmountDetail($dataAdd, $product->product_id);
		}

		/* checking for child products */
		$childProducts = $productHelper->getChildProduct($product->product_id);

		if (count($childProducts) > 0)
		{
			if (PURCHASE_PARENT_WITH_CHILD == 1)
			{
				$isChilds = false;
				/* get attributes */
				$attributes_set = array();

				if ($product->attribute_set_id > 0)
				{
					$attributes_set = RedshopHelperProduct_Attribute::getProductAttribute(0, $product->attribute_set_id, 0, 1);
				}

				$attributes = RedshopHelperProduct_Attribute::getProductAttribute($product->product_id);
				$attributes = array_merge($attributes, $attributes_set);
			}
			else
			{
				$isChilds   = true;
				$attributes = array();
			}
		}
		else
		{
			$isChilds = false;

			/*  get attributes */
			$attributes_set = array();

			if ($product->attribute_set_id > 0)
			{
				$attributes_set = RedshopHelperProduct_Attribute::getProductAttribute(0, $product->attribute_set_id, 0, 1);
			}

			$attributes = RedshopHelperProduct_Attribute::getProductAttribute($product->product_id);
			$attributes = array_merge($attributes, $attributes_set);
		}

		$returnArr    = $productHelper->getProductUserfieldFromTemplate($dataAdd);
		$userfieldArr = $returnArr[1];

		/* Product attribute  Start */
		$totalatt = count($attributes);
		/* check product for not for sale */

		$dataAdd = $productHelper->getProductNotForSaleComment($product, $dataAdd, $attributes);
		/* echo $dataAdd;die(); */
		$dataAdd = $productHelper->replaceProductInStock($product->product_id, $dataAdd, $attributes, $attributeTemplate);

		$dataAdd = RedshopHelperAttribute::replaceAttributeData($product->product_id, 0, 0, $attributes, $dataAdd, $attributeTemplate, $isChilds);

		// Replace attribute with null value if it exist
		if (isset($attributeTemplate))
		{
			$templateAttribute = "{attributeTemplate:" . $attributeTemplate->template_name . "}";

			if (strstr($dataAdd, $templateAttribute))
			{
				$dataAdd = str_replace($templateAttribute, "", $dataAdd);
			}
		}

		/* get cart tempalte */
		$dataAdd = $productHelper->replaceCartTemplate($product->product_id, $catid, 0, 0, $dataAdd, $isChilds, $userfieldArr, $totalatt, $totacc, $count_no_user_field, "");

		$results = $dispatcher->trigger('onPrepareProduct', array(& $dataAdd, & $params, $product));

		$productData .= $dataAdd;
	}

	$productTmpl = $productData;
	$catName     = "";

	if (!empty($cid))
	{
		$db    = JFactory::getDbo();
		$query = $db->getQuery(true)
			->select($db->qn('name'))
			->from($db->qn('#__redshop_category'))
			->where($db->qn('id') . ' = ' . $db->q((int) $cid));

		$catName = $db->setQuery($query)->loadResult();
	}

	if (strstr($templateDesc, "{pagination}"))
	{
		$pagination   = $displayData["pagination"];
		$templateDesc = str_replace("{pagination}", $pagination->getPaginationLinks('pagination.customize'), $templateDesc);
	}

	$usePerPageLimit = false;

	if (strstr($templateDesc, "perpagelimit:"))
	{
		$usePerPageLimit = true;
		$perpage         = explode('{perpagelimit:', $templateDesc);
		$perpage         = explode('}', $perpage[1]);
		$templateDesc    = str_replace("{perpagelimit:" . intval($perpage[0]) . "}", "", $templateDesc);
	}

	if (strstr($templateDesc, "{product_display_limit}"))
	{
		if ($usePerPageLimit == false)
		{
			$limitBox = '';
		}
		else
		{
			$limitBox = $pagination->getLimitBox();
		}

		$templateDesc = str_replace("{product_display_limit}", $limitBox, $templateDesc);
	}

	$templateDesc = str_replace("{order_by_lbl}", JText::_('COM_REDSHOP_SELECT_ORDER_BY'), $templateDesc);
	$templateDesc = str_replace("{order_by}", $lists['order_select'], $templateDesc);
	$templateDesc = str_replace("{product_loop_start}", "", $templateDesc);
	$templateDesc = str_replace("{product_loop_end}", "", $templateDesc);
	$templateDesc = str_replace("{category_main_name}", $catName, $templateDesc);
	$templateDesc = str_replace("{category_main_description}", '', $templateDesc);
	$templateDesc = str_replace($templateProduct, $productTmpl, $templateDesc);
	$templateDesc = str_replace("{with_vat}", "", $templateDesc);
	$templateDesc = str_replace("{without_vat}", "", $templateDesc);
	$templateDesc = str_replace("{attribute_price_with_vat}", "", $templateDesc);
	$templateDesc = str_replace("{attribute_price_without_vat}", "", $templateDesc);
	$templateDesc = str_replace("{redproductfinderfilter_formstart}", "", $templateDesc);
	$templateDesc = str_replace("{product_price_slider1}", "", $templateDesc);
	$templateDesc = str_replace("{redproductfinderfilter_formend}", "", $templateDesc);
	$templateDesc = str_replace("{redproductfinderfilter:rp_myfilter}", "", $templateDesc);

	/** todo: trigger plugin for content redshop**/
	$templateDesc = RedshopHelperTemplate::parseRedshopPlugin($templateDesc);

	$templateDesc = RedshopHelperText::replaceTexts($templateDesc);
}

echo $templateDesc;
