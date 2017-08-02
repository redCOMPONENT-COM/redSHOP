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
$templateId = $displayData['templateId'];
$pk         = $displayData["post"];
$cid        = $pk["cid"] ? $pk["cid"] : 0;
$keyword    = $displayData['keyword'];
$model      = $displayData["model"];
$app        = JFactory::getApplication();
$input      = $app->input;

$categoryModel = JModelLegacy::getInstance('Category', 'RedshopModel');
$categoryModel->setId($cid);
$categoryData = $categoryModel->getData();
$mainCategory = $categoryModel->_loadCategory();

RedshopHelperUtility::defineDynamicVariables();
$productHelper    = productHelper::getInstance();
$objHelper        = redhelper::getInstance();
$extraField       = extraField::getInstance();
$stockroomHelper  = rsstockroomhelper::getInstance();
$redTemplate      = Redtemplate::getInstance();
$redconfiguration = Redconfiguration::getInstance();

$list = array(
	JHtml::_('select.option', '', JText::_('COM_REDSHOP_SELECT')),
	JHtml::_('select.option', 'p.product_price', JText::_('COM_REDSHOP_PRODUCT_PRICE_ASC')),
	JHtml::_('select.option', 'p.product_price desc', JText::_('COM_REDSHOP_PRODUCT_PRICE_DESC')),
	JHtml::_('select.option', 'p.product_id', JText::_('COM_REDSHOP_NEWEST'))
);

$orderBy = JHtml::_(
	'select.genericlist',
	$list, 'orderBy',
	'class="inputbox" size="1" onchange="order(this);" ',
	'value',
	'text',
	$displayData['orderBy']
);

$productData    = '';
$extraFieldName = $extraField->getSectionFieldNameArray(1, 1, 1);

JPluginHelper::importPlugin('redshop_product');

$dispatcher = RedshopHelperUtility::getDispatcher();
$params     = $app->getParams('com_redshop');
$itemId     = $input->get('Itemid', 0, "int");
$fieldArray = RedshopHelperExtrafields::getSectionFieldList(17, 0, 0);

$templateArray     = RedshopHelperTemplate::getTemplate("category", $templateId);
$templateDesc      = $templateArray[0]->template_desc;
$attributeTemplate = $productHelper->getAttributeTemplate($templateDesc);

// Begin replace template
$templateDesc   = str_replace("{total_product_lbl}", JText::_('COM_REDSHOP_TOTAL_PRODUCT'), $templateDesc);
$templateDesc   = str_replace("{total_product}", $displayData['total'], $templateDesc);
$categoryDetail = RedshopHelperCategory::getCategoryById($cid);

if (strpos($templateDesc, "{template_selector_category}") !== false)
{
	$categoryTemplate = $categoryDetail->template . ',' . $categoryDetail->more_template;
	$template         = RedshopHelperTemplate::getTemplate('category', $categoryTemplate);

	$renderTemplate = JHtml::_(
		'select.genericlist',
		$template,
		'category_template',
		'class="inputbox" size="1" onchange="loadTemplate(this);"',
		'template_id',
		'template_name',
		$templateId
	);

	if ($renderTemplate != "")
	{
		$templateDesc = str_replace("{template_selector_category_lbl}", JText::_('COM_REDSHOP_TEMPLATE_SELECTOR_CATEGORY_LBL'), $templateDesc);
		$templateDesc = str_replace("{template_selector_category}", $renderTemplate, $templateDesc);
	}

	$templateDesc = str_replace("{template_selector_category_lbl}", "", $templateDesc);
	$templateDesc = str_replace("{template_selector_category}", "", $templateDesc);
}

if (strpos($templateDesc, "{load_more}") !== false)
{
	$loadMore = '<button class="btn btn-success" name="load-more" id="load-more" total="' . $displayData['total'] . '" onclick="loadMore(this);">' . JText::_('COM_REDSHOP_LOAD_MORE') . '</button>';
	$templateDesc = str_replace("{load_more}", $loadMore, $templateDesc);
}

// Replace Sub Category
if (strpos($templateDesc, "{category_loop_start}") !== false && strpos($templateDesc, "{category_loop_end}") !== false)
{
	$templateD1     = explode("{category_loop_start}", $templateDesc);
	$templateD2     = explode("{category_loop_end}", $templateD1[1]);
	$subcatTemplate = $templateD2[0];

	if (strpos($subcatTemplate, '{category_thumb_image_2}') !== false)
	{
		$tag    = '{category_thumb_image_2}';
		$hThumb = Redshop::getConfig()->get('THUMB_HEIGHT_2');
		$wThumb = Redshop::getConfig()->get('THUMB_WIDTH_2');
	}
	elseif (strpos($subcatTemplate, '{category_thumb_image_3}') !== false)
	{
		$tag    = '{category_thumb_image_3}';
		$hThumb = Redshop::getConfig()->get('THUMB_HEIGHT_3');
		$wThumb = Redshop::getConfig()->get('THUMB_WIDTH_3');
	}
	elseif (strpos($subcatTemplate, '{category_thumb_image_1}') !== false)
	{
		$tag    = '{category_thumb_image_1}';
		$hThumb = Redshop::getConfig()->get('THUMB_HEIGHT');
		$wThumb = Redshop::getConfig()->get('THUMB_WIDTH');
	}
	else
	{
		$tag    = '{category_thumb_image}';
		$hThumb = Redshop::getConfig()->get('THUMB_HEIGHT');
		$wThumb = Redshop::getConfig()->get('THUMB_WIDTH');
	}

	$catDetail = "";
	$extraFieldsForCurrentTemplate = RedshopHelperTemplate::getExtraFieldsForCurrentTemplate($extraFieldName, $subcatTemplate);

	for ($i = 0, $nc = count($categoryData); $i < $nc; $i++)
	{
		$row = $categoryData[$i];

		// Filter categories based on Shopper group category ACL
		$checkCid = RedshopHelperAccess::checkPortalCategoryPermission($row->id);
		$sgportal = RedshopHelperShopper_Group::getShopperGroupPortal();
		$portal   = 0;

		if (count($sgportal) > 0)
		{
			$portal = $sgportal->shopper_group_portal;
		}

		if (!$checkCid && (Redshop::getConfig()->get('PORTAL_SHOP') == 1 || $portal == 1))
		{
			continue;
		}

		$dataAdd = $subcatTemplate;

		$categoryItemId = RedshopHelperUtility::getCategoryItemid($row->id);

		$link = JRoute::_(
			'index.php?option=com_redshop&view=category&cid='
			. $row->id . '&manufacturer_id='
			. $categoryModel->getState('manufacturer_id') . '&layout=detail&Itemid='
			. $categoryItemId
		);

		$middlePath   = REDSHOP_FRONT_IMAGES_RELPATH . 'category/';
		$title        = " title='" . $row->name . "' ";
		$alt          = " alt='" . $row->name . "' ";
		$productImage = REDSHOP_FRONT_IMAGES_ABSPATH . "noimage.jpg";
		$linkImage    = $productImage;

		if ($row->category_full_image && JFile::exists($middlePath . $row->category_full_image))
		{
			$categoryFullImage = $row->category_full_image;
			$productImage      = RedshopHelperMedia::watermark(
				'category',
				$row->category_full_image,
				$wThumb,
				$hThumb,
				Redshop::getConfig()->get('WATERMARK_CATEGORY_THUMB_IMAGE'),
				'0'
			);

			$linkImage = RedshopHelperMedia::watermark(
				'category',
				$row->category_full_image,
				'',
				'',
				Redshop::getConfig()->get('WATERMARK_CATEGORY_IMAGE'),
				'0'
			);
		}
		elseif (Redshop::getConfig()->get('CATEGORY_DEFAULT_IMAGE') && JFile::exists($middlePath . Redshop::getConfig()->get('CATEGORY_DEFAULT_IMAGE')))
		{
			$categoryFullImage = Redshop::getConfig()->get('CATEGORY_DEFAULT_IMAGE');
			$productImage      = RedshopHelperMedia::watermark(
				'category',
				Redshop::getConfig()->get('CATEGORY_DEFAULT_IMAGE'),
				$wThumb,
				$hThumb,
				Redshop::getConfig()->get('WATERMARK_CATEGORY_THUMB_IMAGE'),
				'0'
			);

			$linkImage = RedshopHelperMedia::watermark(
				'category',
				Redshop::getConfig()->get('CATEGORY_DEFAULT_IMAGE'),
				'',
				'',
				Redshop::getConfig()->get('WATERMARK_CATEGORY_IMAGE'),
				'0'
			);
		}

		if (Redshop::getConfig()->get('CAT_IS_LIGHTBOX'))
		{
			$catThumb = "<a class='modal' href='" . REDSHOP_FRONT_IMAGES_ABSPATH . 'category/' . $categoryFullImage . "' rel=\"{handler: 'image', size: {}}\" " . $title . ">";
		}
		else
		{
			$catThumb = "<a href='" . $link . "' " . $title . ">";
		}

		$catThumb .= "<img src='" . $productImage . "' " . $alt . $title . ">";
		$catThumb .= "</a>";
		$dataAdd = str_replace($tag, $catThumb, $dataAdd);

		if (strpos($dataAdd, '{category_name}') !== false)
		{
			$catName = '<a href="' . $link . '" ' . $title . '>' . $row->name . '</a>';
			$dataAdd = str_replace("{category_name}", $catName, $dataAdd);
		}

		if (strpos($dataAdd, '{category_readmore}') !== false)
		{
			$catName = '<a href="' . $link . '" ' . $title . '>' . JText::_('COM_REDSHOP_READ_MORE') . '</a>';
			$dataAdd = str_replace("{category_readmore}", $catName, $dataAdd);
		}

		if (strpos($dataAdd, '{category_description}') !== false)
		{
			$catDesc = $redconfiguration->maxchar(
				$row->description,
				Redshop::getConfig()->get('CATEGORY_SHORT_DESC_MAX_CHARS'),
				Redshop::getConfig()->get('CATEGORY_SHORT_DESC_END_SUFFIX')
			);

			$dataAdd = str_replace("{category_description}", $catDesc, $dataAdd);
		}

		if (strpos($dataAdd, '{category_short_desc}') !== false)
		{
			$catShortDesc = $redconfiguration->maxchar(
				$row->short_description,
				Redshop::getConfig()->get('CATEGORY_SHORT_DESC_MAX_CHARS'),
				Redshop::getConfig()->get('CATEGORY_SHORT_DESC_END_SUFFIX')
			);

			$dataAdd   = str_replace("{category_short_desc}", $catShortDesc, $dataAdd);
		}

		if (strpos($dataAdd, '{category_total_product}') !== false)
		{
			$totalprd = $producthelper->getProductCategory($row->id);
			$dataAdd = str_replace("{category_total_product}", count($totalprd), $dataAdd);
			$dataAdd = str_replace("{category_total_product_lbl}", JText::_('COM_REDSHOP_TOTAL_PRODUCT'), $dataAdd);
		}

		/*
		 * Category template extra field
		 * "2" argument is set for category
		 */
		if ($extraFieldsForCurrentTemplate)
		{
			$dataAdd = $extraField->extra_field_display(2, $row->id, $extraFieldsForCurrentTemplate, $dataAdd);
		}

		$catDetail .= $dataAdd;
	}

	$templateDesc = str_replace("{category_loop_start}", "", $templateDesc);
	$templateDesc = str_replace("{category_loop_end}", "", $templateDesc);
	$templateDesc = str_replace($subcatTemplate, $catDetail, $templateDesc);
}

if (strpos($templateDesc, "{if subcats}") !== false && strpos($templateDesc, "{subcats end if}") !== false)
{
	$templateD1 = explode("{if subcats}", $templateDesc);
	$templateD2 = explode("{subcats end if}", $templateD1[1]);

	if (count($categoryData) > 0)
	{
		$templateDesc = str_replace("{if subcats}", "", $templateDesc);
		$templateDesc = str_replace("{subcats end if}", "", $templateDesc);
	}
	else
	{
		$templateDesc = $templateD1[0] . $templateD2[1];
	}
}

// End replace sub category

// Replace Main Category
if (strpos($templateDesc, '{category_main_description}') !== false)
{
	$mainCategoryDesc = $redconfiguration->maxchar(
		$mainCategory->description,
		Redshop::getConfig()->get('CATEGORY_SHORT_DESC_MAX_CHARS'),
		Redshop::getConfig()->get('CATEGORY_SHORT_DESC_END_SUFFIX')
	);

	$templateDesc = str_replace("{category_main_description}", $mainCategoryDesc, $templateDesc);
}

if (strpos($templateDesc, '{category_main_short_desc}') !== false)
{
	$mainCategoryShortDesc = $redconfiguration->maxchar(
		$mainCategory->short_description,
		Redshop::getConfig()->get('CATEGORY_SHORT_DESC_MAX_CHARS'),
		Redshop::getConfig()->get('CATEGORY_SHORT_DESC_END_SUFFIX')
	);

	$templateDesc = str_replace("{category_main_short_desc}", $mainCategoryShortDesc, $templateDesc);
}

$mainCategoryName = "";

if (strpos($templateDesc, '{category_main_name}') !== false)
{
	$mainCategoryName = $redconfiguration->maxchar(
		$mainCategory->name,
		Redshop::getConfig()->get('CATEGORY_TITLE_MAX_CHARS'),
		Redshop::getConfig()->get('CATEGORY_TITLE_END_SUFFIX')
	);

	$templateDesc = str_replace("{category_main_name}", $mainCategoryName, $templateDesc);
}

if (strpos($templateDesc, '{category_main_thumb_image_2}') !== false)
{
	$cTag    = '{category_main_thumb_image_2}';
	$chThumb = Redshop::getConfig()->get('THUMB_HEIGHT_2');
	$cwThumb = Redshop::getConfig()->get('THUMB_WIDTH_2');
}
elseif (strpos($templateDesc, '{category_main_thumb_image_3}') !== false)
{
	$cTag    = '{category_main_thumb_image_3}';
	$chThumb = Redshop::getConfig()->get('THUMB_HEIGHT_3');
	$cwThumb = Redshop::getConfig()->get('THUMB_WIDTH_3');
}
elseif (strpos($templateDesc, '{category_main_thumb_image_1}') !== false)
{
	$cTag    = '{category_main_thumb_image_1}';
	$chThumb = Redshop::getConfig()->get('THUMB_HEIGHT');
	$cwThumb = Redshop::getConfig()->get('THUMB_WIDTH');
}
else
{
	$cTag    = '{category_main_thumb_image}';
	$chThumb = Redshop::getConfig()->get('THUMB_HEIGHT');
	$cwThumb = Redshop::getConfig()->get('THUMB_WIDTH');
}

$catMainThumb = "";

if ($mainCategory->category_full_image && JFile::exists(REDSHOP_FRONT_IMAGES_RELPATH . 'category/' . $mainCategory->category_full_image))
{
	$waterCatImg  = RedshopHelperMedia::watermark(
		'category',
		$mainCategory->category_full_image,
		$cwThumb,
		$chThumb,
		Redshop::getConfig()->get('WATERMARK_CATEGORY_THUMB_IMAGE'),
		'0'
	);

	$catMainThumb = "<a href='" . $link . "' title='" . $mainCategoryName .
						"'><img src='" . $waterCatImg . "' alt='" . $mainCategoryName . "' title='" . $mainCategoryName . "'></a>";
}

$templateDesc = str_replace($cTag, $catMainThumb, $templateDesc);

if (strpos($templateDesc, "{include_product_in_sub_cat}") !== false)
{
	$templateDesc = str_replace("{include_product_in_sub_cat}", '', $templateDesc);
}

// End replace Main Category

// Replace Products
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
		$dataAdd  = $productHelper->getProductFinderDatepickerValue($templateProduct, $product->product_id, $fieldArray);
		$itemData = $productHelper->getMenuInformation(0, 0, '', 'product&pid=' . $product->product_id);
		$pItemid  = count($itemData) > 0 ? $itemData->id : RedshopHelperUtility::getItemId($product->product_id, $cid);

		$dataAdd = str_replace("{product_price}", $productHelper->getProductFormattedPrice($productPrice), $dataAdd);
		$dataAdd = str_replace("{product_id_lbl}", JText::_('COM_REDSHOP_PRODUCT_ID_LBL'), $dataAdd);
		$dataAdd = str_replace("{product_id}", $product->product_id, $dataAdd);
		$dataAdd = str_replace("{product_number_lbl}", JText::_('COM_REDSHOP_PRODUCT_NUMBER_LBL'), $dataAdd);

		$productNumberOutput = '<span id="product_number_variable' . $product->product_id . '">' . $product->product_number . '</span>';
		$dataAdd             = str_replace("{product_number}", $productNumberOutput, $dataAdd);

		// Replace VAT information
		$dataAdd = RedshopHelperTax::replaceVatInformation($dataAdd);

		$link = JRoute::_('index.php?option=com_redshop&view=product&pid=' . $product->product_id . '&cid=' . $catid . '&Itemid=' . $pItemid);

		$productName = RedshopHelperUtility::maxChars(
			$product->product_name,
			Redshop::getConfig()->get('CATEGORY_PRODUCT_TITLE_MAX_CHARS'),
			Redshop::getConfig()->get('CATEGORY_PRODUCT_TITLE_END_SUFFIX')
		);

		if (!empty($keyword))
		{
			$productName = str_ireplace($keyword, "<b class='search_hightlight'>" . $keyword . "</b>", $productName);
		}

		if (strstr($dataAdd, '{product_name_nolink}'))
		{
			$dataAdd = str_replace("{product_name_nolink}", $productName, $dataAdd);
		}

		if (strstr($dataAdd, '{product_name}'))
		{
			$productName   = "<a href='" . $link . "' title='" . $product->product_name . "'>" . $productName . "</a>";
			$dataAdd = str_replace("{product_name}", $productName, $dataAdd);
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
			$productShortDesc = RedshopHelperUtility::maxChars(
				$product->product_s_desc,
				Redshop::getConfig()->get('CATEGORY_PRODUCT_SHORT_DESC_MAX_CHARS'),
				Redshop::getConfig()->get('CATEGORY_PRODUCT_SHORT_DESC_END_SUFFIX')
			);

			if (!empty($keyword))
			{
				$productShortDesc = str_ireplace($keyword, "<b class='search_hightlight'>" . $keyword . "</b>", $productShortDesc);
			}

			$dataAdd = str_replace("{product_s_desc}", $productShortDesc, $dataAdd);
		}

		if (strstr($dataAdd, '{product_desc}'))
		{
			$productDesc = RedshopHelperUtility::maxChars(
				$product->product_desc,
				Redshop::getConfig()->get('CATEGORY_PRODUCT_DESC_MAX_CHARS'),
				Redshop::getConfig()->get('CATEGORY_PRODUCT_DESC_END_SUFFIX')
			);

			if (!empty($keyword))
			{
				$productDesc = str_ireplace($keyword, "<b class='search_hightlight'>" . $keyword . "</b>", $productDesc);
			}

			$dataAdd = str_replace("{product_desc}", $productDesc, $dataAdd);
		}

		if (strstr($dataAdd, '{product_rating_summary}'))
		{
			// Product Review/Rating Fetching reviews
			$finalAvgReviewData = $productHelper->getProductRating($product->product_id);
			$dataAdd            = str_replace("{product_rating_summary}", $finalAvgReviewData, $dataAdd);
		}

		if (strstr($dataAdd, '{manufacturer_link}'))
		{
			$manufacturerLinkHref = JRoute::_('index.php?option=com_redshop&view=manufacturers&layout=detail&mid=' . $product->manufacturer_id . '&Itemid=' . $itemId);
			$manufacturerLink = '';

			if ($product->manufacturer_name != '')
			{
				$manufacturerLink = '<a href="' . $manufacturerLinkHref . '" title="' . $product->manufacturer_name . '">' . $product->manufacturer_name . '</a>';
			}

			$dataAdd = str_replace("{manufacturer_link}", $manufacturerLink, $dataAdd);

			if (strstr($dataAdd, "{manufacturer_link}"))
			{
				$dataAdd = str_replace("{manufacturer_name}", "", $dataAdd);
			}
		}

		if (strstr($dataAdd, '{manufacturer_product_link}'))
		{
			$manufacturerPLink = "<a href='" . JRoute::_('index.php?option=com_redshop&view=manufacturers&layout=products&mid=' . $product->manufacturer_id . '&Itemid=' . $itemId) . "'>" . JText::_("COM_REDSHOP_VIEW_ALL_MANUFACTURER_PRODUCTS") . " " . $product->manufacturer_name . "</a>";
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
			$dataAdd = $extraField->extra_field_display(
				1,
				$product->product_id,
				$extraFieldsForCurrentTemplate,
				$dataAdd,
				1
			);
		}

		if (strstr($dataAdd, "{product_thumb_image_3}"))
		{
			$pImgTag = '{product_thumb_image_3}';
			$phThumb = Redshop::getConfig()->get('CATEGORY_PRODUCT_THUMB_HEIGHT_3');
			$pwThumb = Redshop::getConfig()->get('CATEGORY_PRODUCT_THUMB_WIDTH_3');
		}
		elseif (strstr($dataAdd, "{product_thumb_image_2}"))
		{
			$pImgTag = '{product_thumb_image_2}';
			$phThumb = Redshop::getConfig()->get('CATEGORY_PRODUCT_THUMB_HEIGHT_2');
			$pwThumb = Redshop::getConfig()->get('CATEGORY_PRODUCT_THUMB_WIDTH_2');
		}
		elseif (strstr($dataAdd, "{product_thumb_image_1}"))
		{
			$pImgTag = '{product_thumb_image_1}';
			$phThumb = Redshop::getConfig()->get('CATEGORY_PRODUCT_THUMB_HEIGHT');
			$pwThumb = Redshop::getConfig()->get('CATEGORY_PRODUCT_THUMB_WIDTH');
		}
		else
		{
			$pImgTag = '{product_thumb_image}';
			$phThumb = Redshop::getConfig()->get('CATEGORY_PRODUCT_THUMB_HEIGHT');
			$pwThumb = Redshop::getConfig()->get('CATEGORY_PRODUCT_THUMB_WIDTH');
		}

		$hiddenThumbImage = "<input type='hidden' name='prd_main_imgwidth' id='prd_main_imgwidth' value='" . $pwThumb . "'><input type='hidden' name='prd_main_imgheight' id='prd_main_imgheight' value='" . $phThumb . "'>";
		$thumbImage       = $productHelper->getProductImage($product->product_id, $link, $pwThumb, $phThumb, 2, 1);

		// Product image flying addwishlist time start
		$thumbImage = "<span class='productImageWrap' id='productImageWrapID_" . $product->product_id . "'>" . $productHelper->getProductImage($product->product_id, $link, $pwThumb, $phThumb, 2, 1) . "</span>";

		// Product image flying addwishlist time end
		$dataAdd = str_replace($pImgTag, $thumbImage . $hiddenThumbImage, $dataAdd);

		// Front-back image tag
		if (strstr($dataAdd, "{front_img_link}") || strstr($dataAdd, "{back_img_link}"))
		{
			if ($this->_data->product_thumb_image)
			{
				$mainsrcPath = REDSHOP_FRONT_IMAGES_ABSPATH . "product/" . $product->product_thumb_image;
			}
			else
			{
				$mainsrcPath = $url . "components/com_redshop/helpers/thumb.php?filename=product/" . $product->product_full_image . "&newxsize=" . $pwThumb . "&newysize=" . $phThumb . "&swap=" . Redshop::getConfig()->get('USE_IMAGE_SIZE_SWAPPING');
			}

			if ($this->_data->product_back_thumb_image)
			{
				$backsrcPath = REDSHOP_FRONT_IMAGES_ABSPATH . "product/" . $product->product_back_thumb_image;
			}
			else
			{
				$backsrcPath = $url . "components/com_redshop/helpers/thumb.php?filename=product/" . $product->product_back_full_image . "&newxsize=" . $pwThumb . "&newysize=" . $phThumb . "&swap=" . Redshop::getConfig()->get('USE_IMAGE_SIZE_SWAPPING');
			}

			$aHrefPath     = REDSHOP_FRONT_IMAGES_ABSPATH . "product/" . $product->product_full_image;
			$aHrefBackPath = REDSHOP_FRONT_IMAGES_ABSPATH . "product/" . $product->product_back_full_image;

			$productFrontImageLink = "<a href='#' onClick='javascript:changeproductImage(" . $product->product_id . ",\"" . $mainsrcPath . "\",\"" . $aHrefPath . "\");'>" . JText::_('COM_REDSHOP_FRONT_IMAGE') . "</a>";
			$productBackImageLink  = "<a href='#' onClick='javascript:changeproductImage(" . $product->product_id . ",\"" . $backsrcPath . "\",\"" . $aHrefBackPath . "\");'>" . JText::_('COM_REDSHOP_BACK_IMAGE') . "</a>";

			$dataAdd = str_replace("{front_img_link}", $productFrontImageLink, $dataAdd);
			$dataAdd = str_replace("{back_img_link}", $productBackImageLink, $dataAdd);
		}
		else
		{
			$dataAdd = str_replace("{front_img_link}", "", $dataAdd);
			$dataAdd = str_replace("{back_img_link}", "", $dataAdd);
		}

		// Front-back image tag end


		// Product preview image.
		if (strstr($dataAdd, '{product_preview_img}'))
		{
			if (JFile::exists(REDSHOP_FRONT_IMAGES_RELPATH . 'product/' . $product->product_preview_image))
			{
				$previewsrcPath = $url . "components/com_redshop/helpers/thumb.php?filename=product/"
				. $product->product_preview_image . "&newxsize=" . Redshop::getConfig()->get('CATEGORY_PRODUCT_PREVIEW_IMAGE_WIDTH')
				. "&newysize=" . Redshop::getConfig()->get('CATEGORY_PRODUCT_PREVIEW_IMAGE_HEIGHT')
				. "&swap=" . Redshop::getConfig()->get('USE_IMAGE_SIZE_SWAPPING');
				$previewImg     = "<img src='" . $previewsrcPath . "' class='rs_previewImg' />";
				$dataAdd        = str_replace("{product_preview_img}", $previewImg, $dataAdd);
			}
			else
			{
				$dataAdd = str_replace("{product_preview_img}", "", $dataAdd);
			}
		}

		// 	Product preview image end.

		// Front-back preview image tag...
		if (strstr($dataAdd, "{front_preview_img_link}") || strstr($dataAdd, "{back_preview_img_link}"))
		{
			if ($product->product_preview_image)
			{
				$mainPreviewSrcPath = REDSHOP_FRONT_IMAGES_ABSPATH . "product/" . $product->product_preview_image
				. "&newxsize=" . Redshop::getConfig()->get('CATEGORY_PRODUCT_PREVIEW_IMAGE_WIDTH') . "&newysize="
				. Redshop::getConfig()->get('CATEGORY_PRODUCT_PREVIEW_IMAGE_HEIGHT')
				. "&swap=" . Redshop::getConfig()->get('USE_IMAGE_SIZE_SWAPPING');
			}

			if ($product->product_preview_back_image)
			{
				$backPreviewSrcPath = REDSHOP_FRONT_IMAGES_ABSPATH . "product/"
				. $product->product_preview_back_image . "&newxsize=" . Redshop::getConfig()->get('CATEGORY_PRODUCT_PREVIEW_IMAGE_WIDTH')
				. "&newysize=" . Redshop::getConfig()->get('CATEGORY_PRODUCT_PREVIEW_IMAGE_HEIGHT')
				. "&swap=" . Redshop::getConfig()->get('USE_IMAGE_SIZE_SWAPPING');
			}

			$productFrontImageLink = "<a href='#' onClick='javascript:changeproductPreviewImage(" . $product->product_id . ",\"" . $mainPreviewSrcPath . "\");'>" . JText::_('COM_REDSHOP_FRONT_IMAGE') . "</a>";
			$productBackImageLink  = "<a href='#' onClick='javascript:changeproductPreviewImage(" . $product->product_id . ",\"" . $backPreviewSrcPath . "\");'>" . JText::_('COM_REDSHOP_BACK_IMAGE') . "</a>";

			$dataAdd = str_replace("{front_preview_img_link}", $productFrontImageLink, $dataAdd);
			$dataAdd = str_replace("{back_preview_img_link}", $productBackImageLink, $dataAdd);
		}
		else
		{
			$dataAdd = str_replace("{front_preview_img_link}", "", $dataAdd);
			$dataAdd = str_replace("{back_preview_img_link}", "", $dataAdd);
		}

		// Front-back preview image tag end

		$dataAdd = $productHelper->getJcommentEditor($product, $dataAdd);

		/*
		 *  Conditional tag
		 *  if product on discount : Yes
		 *  {if product_on_sale} This product is on sale {product_on_sale end if} // OUTPUT : This product is on sale
		 *  NO : // OUTPUT : Display blank
		 */
		$dataAdd = $productHelper->getProductOnSaleComment($product, $dataAdd);

		// Replace Wishlist Button
		$dataAdd = RedshopHelperWishlist::replaceWishlistTag($product->product_id, $dataAdd);

		// Replace compare product button
		$dataAdd = $productHelper->replaceCompareProductsButton($product->product_id, $catid, $dataAdd);

		if (strstr($dataAdd, "{stockroom_detail}"))
		{
			$dataAdd = RedshopHelperStockroom::replaceStockroomAmountDetail($dataAdd, $product->product_id);
		}

		// Checking for child products
		$childProducts = $productHelper->getChildProduct($product->product_id);

		if (count($childProducts) > 0)
		{
			if (Redshop::getConfig()->get('PURCHASE_PARENT_WITH_CHILD') == 1)
			{
				$isChilds = false;

				// Get attributes
				$attributesSet = array();

				if ($product->attribute_set_id > 0)
				{
					$attributesSet = RedshopHelperProduct_Attribute::getProductAttribute(
						0,
						$product->attribute_set_id,
						0,
						1
					);
				}

				$attributes = RedshopHelperProduct_Attribute::getProductAttribute($product->product_id);
				$attributes = array_merge($attributes, $attributesSet);
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

			// Get attributes
			$attributesSet = array();

			if ($product->attribute_set_id > 0)
			{
				$attributesSet = RedshopHelperProduct_Attribute::getProductAttribute(
					0,
					$product->attribute_set_id,
					0,
					1
				);
			}

			$attributes = RedshopHelperProduct_Attribute::getProductAttribute($product->product_id);
			$attributes = array_merge($attributes, $attributesSet);
		}

		$returnArr    = $productHelper->getProductUserfieldFromTemplate($dataAdd);
		$userfieldArr = $returnArr[1];

		// Product attribute  Start
		$totalatt = count($attributes);

		// Check product for not for sale
		$dataAdd = $productHelper->getProductNotForSaleComment($product, $dataAdd, $attributes);

		$dataAdd = $productHelper->replaceProductInStock(
			$product->product_id,
			$dataAdd,
			$attributes,
			$attributeTemplate
		);

		$dataAdd = RedshopHelperAttribute::replaceAttributeData(
			$product->product_id,
			0,
			0,
			$attributes,
			$dataAdd,
			$attributeTemplate,
			$isChilds
		);

		// Replace attribute with null value if it exist
		if (isset($attributeTemplate))
		{
			$templateAttribute = "{attributeTemplate:" . $attributeTemplate->template_name . "}";

			if (strstr($dataAdd, $templateAttribute))
			{
				$dataAdd = str_replace($templateAttribute, "", $dataAdd);
			}
		}

		// Get cart template
		$dataAdd = $productHelper->replaceCartTemplate(
			$product->product_id,
			$catid,
			0,
			0,
			$dataAdd,
			$isChilds,
			$userfieldArr,
			$totalatt,
			$totacc,
			0,
			""
		);

		$results = $dispatcher->trigger('onPrepareProduct', array(&$dataAdd, &$params, $product));

		$productData .= $dataAdd;
	}

	$productTmpl = $productData;

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
	$templateDesc = str_replace("{order_by}", $orderBy, $templateDesc);
	$templateDesc = str_replace("{product_loop_start}", "", $templateDesc);
	$templateDesc = str_replace("{product_loop_end}", "", $templateDesc);
	$templateDesc = str_replace("{category_main_name}", $categoryDetail->name, $templateDesc);
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

	 // Trigger plugin for content redshop
	$templateDesc = RedshopHelperTemplate::parseRedshopPlugin($templateDesc);
	$templateDesc = RedshopHelperText::replaceTexts($templateDesc);
	$templateDesc .= '<div id="new-url" style="display: none">' . $displayData['url'] . '</div>';
}

// End Replace Products
echo $templateDesc;
