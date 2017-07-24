<?php
/**
 * @package     RedSHOP.Library
 * @subpackage  Helper
 *
 * @copyright   Copyright (C) 2008 - 2017 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

defined('_JEXEC') or die;

/**
 * Class Redshop Helper Product Tag
 *
 * @since  __DEPLOY_VERSION__
 */
class RedshopHelperProductTag
{
	/**
	 * @var array
	 */
	protected static $productSpecialPrices = array();

	/**
	 * Parse extra fields for template for according to section.
	 *
	 * @param   array   $fieldNames      List of field names
	 * @param   integer $productId       ID of product
	 * @param   integer $section         Section
	 * @param   string  $templateContent Template content
	 * @param   integer $categoryPage    Argument for product section extra field for category page
	 *
	 * @return  string
	 *
	 * @since   __DEPLOY_VERSION__
	 */
	public static function getExtraSectionTag($fieldNames = array(), $productId = 0, $section = 0, $templateContent = '', $categoryPage = 0)
	{
		$fieldName = RedshopHelperTemplate::getExtraFieldsForCurrentTemplate($fieldNames, $templateContent, $categoryPage);

		if (empty($fieldName))
		{
			return $templateContent;
		}

		$templateContent = RedshopHelperExtrafields::extraFieldDisplay($section, $productId, $fieldName, $templateContent, $categoryPage);

		return $templateContent;
	}

	/**
	 * Method for get additional images of product.
	 *
	 * @param   integer  $productId         Id of product
	 * @param   integer  $accessoryId       Accessory Id
	 * @param   integer  $relatedProductId  Related product ID
	 * @param   integer  $propertyId        Property ID
	 * @param   integer  $subPropertyId     Sub-property ID
	 * @param   integer  $mainImgWidth      Main image width
	 * @param   integer  $mainImgHeight     Main image height
	 * @param   string   $redView           redshop View
	 * @param   string   $redLayout         redshop layout
	 *
	 * @return  array
	 *
	 * @since   __DEPLOY_VERSION__
	 */
	public static function displayAdditionalImage(
		$productId = 0, $accessoryId = 0, $relatedProductId = 0, $propertyId = 0, $subPropertyId = 0,
		$mainImgWidth = 0, $mainImgHeight = 0, $redView = "", $redLayout = ""
	)
	{
		if ($accessoryId != 0)
		{
			$accessory = RedshopHelperAccessory::getProductAccessories($accessoryId);
			$productId = $accessory[0]->child_product_id;
		}

		$product = RedshopHelperProduct::getProductById($productId);

		$productTemplate = RedshopHelperTemplate::getTemplate("product", $product->product_template);

		// Get template for stockroom status
		if ($accessoryId != 0)
		{
			$templateHtml = RedshopHelperTemplate::getTemplate("accessory_product");
			$templateHtml = $templateHtml[0]->template_desc;
		}
		elseif ($relatedProductId != 0)
		{
			$templateHtml = RedshopHelperTemplate::getTemplate("related_product");
			$templateHtml = $templateHtml[0]->template_desc;
		}
		else
		{
			$templateHtml = $productTemplate[0]->template_desc;
		}

		$productTemplate = $productTemplate[0]->template_desc;

		if ($redLayout == 'categoryproduct' || $redLayout == 'detail')
		{
			if (strpos($productTemplate, "{product_thumb_image_3}") !== false)
			{
				$productImgTag         = '{product_thumb_image_3}';
				$productImgThumbHeight = Redshop::getConfig()->get('CATEGORY_PRODUCT_THUMB_HEIGHT_3');
				$productImgThumbWidth  = Redshop::getConfig()->get('CATEGORY_PRODUCT_THUMB_WIDTH_3');
			}
			elseif (strpos($productTemplate, "{product_thumb_image_2}") !== false)
			{
				$productImgTag         = '{product_thumb_image_2}';
				$productImgThumbHeight = Redshop::getConfig()->get('CATEGORY_PRODUCT_THUMB_HEIGHT_2');
				$productImgThumbWidth  = Redshop::getConfig()->get('CATEGORY_PRODUCT_THUMB_WIDTH_2');
			}
			elseif (strpos($productTemplate, "{product_thumb_image_1}") !== false)
			{
				$productImgTag         = '{product_thumb_image_1}';
				$productImgThumbHeight = Redshop::getConfig()->get('CATEGORY_PRODUCT_THUMB_HEIGHT');
				$productImgThumbWidth  = Redshop::getConfig()->get('CATEGORY_PRODUCT_THUMB_WIDTH');
			}
			else
			{
				$productImgTag         = '{product_thumb_image}';
				$productImgThumbHeight = Redshop::getConfig()->get('CATEGORY_PRODUCT_THUMB_HEIGHT');
				$productImgThumbWidth  = Redshop::getConfig()->get('CATEGORY_PRODUCT_THUMB_WIDTH');
			}
		}
		else
		{
			if (strpos($productTemplate, "{product_thumb_image_3}") !== false)
			{
				$productImgTag         = '{product_thumb_image_3}';
				$productImgThumbHeight = Redshop::getConfig()->get('PRODUCT_MAIN_IMAGE_HEIGHT_3');
				$productImgThumbWidth  = Redshop::getConfig()->get('PRODUCT_MAIN_IMAGE_3');
			}
			elseif (strpos($productTemplate, "{product_thumb_image_2}") !== false)
			{
				$productImgTag         = '{product_thumb_image_2}';
				$productImgThumbHeight = Redshop::getConfig()->get('PRODUCT_MAIN_IMAGE_HEIGHT_2');
				$productImgThumbWidth  = Redshop::getConfig()->get('PRODUCT_MAIN_IMAGE_2');
			}
			elseif (strpos($productTemplate, "{product_thumb_image_1}") !== false)
			{
				$productImgTag         = '{product_thumb_image_1}';
				$productImgThumbHeight = Redshop::getConfig()->get('PRODUCT_MAIN_IMAGE_HEIGHT');
				$productImgThumbWidth  = Redshop::getConfig()->get('PRODUCT_MAIN_IMAGE');
			}
			else
			{
				$productImgTag         = '{product_thumb_image}';
				$productImgThumbHeight = Redshop::getConfig()->get('PRODUCT_MAIN_IMAGE_HEIGHT');
				$productImgThumbWidth  = Redshop::getConfig()->get('PRODUCT_MAIN_IMAGE');
			}
		}

		if (strpos($productTemplate, "{more_images_3}") !== false)
		{
			$moreProductsImgThumbHeight = Redshop::getConfig()->get('PRODUCT_ADDITIONAL_IMAGE_HEIGHT_3');
			$moreProductsImgThumbWidth  = Redshop::getConfig()->get('PRODUCT_ADDITIONAL_IMAGE_3');
		}
		elseif (strpos($productTemplate, "{more_images_2}") !== false)
		{
			$moreProductsImgThumbHeight = Redshop::getConfig()->get('PRODUCT_ADDITIONAL_IMAGE_HEIGHT_2');
			$moreProductsImgThumbWidth  = Redshop::getConfig()->get('PRODUCT_ADDITIONAL_IMAGE_2');
		}
		elseif (strpos($productTemplate, "{more_images_1}") !== false)
		{
			$moreProductsImgThumbHeight = Redshop::getConfig()->get('PRODUCT_ADDITIONAL_IMAGE_HEIGHT');
			$moreProductsImgThumbWidth  = Redshop::getConfig()->get('PRODUCT_ADDITIONAL_IMAGE');
		}
		else
		{
			$moreProductsImgThumbHeight = Redshop::getConfig()->get('PRODUCT_ADDITIONAL_IMAGE_HEIGHT');
			$moreProductsImgThumbWidth  = Redshop::getConfig()->get('PRODUCT_ADDITIONAL_IMAGE');
		}

		if ($mainImgWidth != 0 || $mainImgHeight != 0)
		{
			$productImgThumbWidth  = $mainImgWidth;
			$productImgThumbHeight = $mainImgHeight;
		}

		$imageAttributes = productHelper::getInstance()->getdisplaymainImage(
			$productId, $propertyId, $subPropertyId, $productImgThumbWidth, $productImgThumbHeight, $redView
		);

		$aHrefImageResponse  = $imageAttributes['aHrefImageResponse'];
		$mainImageResponse   = $imageAttributes['mainImageResponse'];
		$productMainImg      = $imageAttributes['productmainimg'];
		$aTitleImageResponse = $imageAttributes['aTitleImageResponse'];
		$imgName             = $imageAttributes['imagename'];
		// $ImageAttributes['type'] = $type;
		$attributeImg  = $imageAttributes['attrbimg'];
		$productNumber = $imageAttributes['pr_number'];
		// $view = $ImageAttributes['view'];

		$propertyAdditionalImages     = "";
		$subPropertyAdditionalImages  = "";
		$propertyAdditionalVideos     = "";
		$subPropertyAdditionalVideos  = "";
		$productAvailabilityDateLabel = '';
		$productAvailabilityDate      = '';
		$mediaImages                  = RedshopHelperMedia::getAdditionMediaImage($productId, "product");
		$mediaVideos                  = RedshopHelperMedia::getAdditionMediaImage($productId, "product", "youtube");

		// Prepare additional media images.
		$productAdditionalImages = self::prepareAdditionalImages(
			$mediaImages, $product, $productImgThumbWidth, $productImgThumbHeight, $moreProductsImgThumbWidth, $moreProductsImgThumbHeight
		);
		$tmpProductImages        = $productAdditionalImages;

		// Prepare additional media videos.
		$productAdditionalVideos = self::prepareAdditionalVideos($mediaVideos);
		$tmpProductVideos        = $productAdditionalVideos;

		// Prepare for property if necessary
		if ($propertyId > 0)
		{
			$mediaImages = RedshopHelperMedia::getAdditionMediaImage($propertyId, "property");
			$mediaVideos = RedshopHelperMedia::getAdditionMediaImage($propertyId, "property", "youtube");

			$propertyAdditionalImages = empty($mediaImages) ? $tmpProductImages : self::preparePropertyAdditionalImages(
				$mediaImages, $product, $productImgThumbWidth, $productImgThumbHeight, $moreProductsImgThumbWidth, $moreProductsImgThumbHeight
			);

			$propertyAdditionalVideos = empty($mediaVideos) ? $tmpProductVideos : self::preparePropertyAdditionalVideos($mediaVideos);
		}

		// Prepare for sub-property if necessary
		if ($subPropertyId > 0)
		{
			$mediaImages = RedshopHelperMedia::getAdditionMediaImage($subPropertyId, "subproperty");
			$mediaVideos = RedshopHelperMedia::getAdditionMediaImage($subPropertyId, "subproperty", "youtube");

			$subPropertyAdditionalImages = self::prepareSubPropertyAdditionalImages(
				$mediaImages, $product, $productImgThumbWidth, $productImgThumbHeight, $moreProductsImgThumbWidth, $moreProductsImgThumbHeight
			);

			$subPropertyAdditionalVideos = self::prepareSubPropertyAdditionalVideos($mediaVideos);
		}

		$response         = "";
		$additionalVideos = "";

		if (!empty($subPropertyAdditionalImages))
		{
			$response = "<div>" . $subPropertyAdditionalImages . "</div>";
		}
		elseif (!empty($propertyAdditionalImages))
		{
			$response = "<div>" . $propertyAdditionalImages . "</div>";
		}
		elseif (!empty($productAdditionalImages))
		{
			$response = "<div>" . $productAdditionalImages . "</div>";
		}

		if (!empty($subPropertyAdditionalVideos))
		{
			$additionalVideos = $subPropertyAdditionalVideos;
		}
		elseif (!empty($propertyAdditionalVideos))
		{
			$additionalVideos = $propertyAdditionalVideos;
		}
		elseif (!empty($productAdditionalVideos))
		{
			$additionalVideos = $productAdditionalVideos;
		}

		$productAttributeDelivery = "";
		$attributeFlag            = false;

		if (empty($accessoryId))
		{
			if ($subPropertyId)
			{
				$productAttributeDelivery = productHelper::getInstance()->getProductMinDeliveryTime($productId, $subPropertyId, "subproperty", 0);

				$attributeFlag = !empty($productAttributeDelivery) ? true : false;
			}

			if ($propertyId && !$attributeFlag)
			{
				$productAttributeDelivery = productHelper::getInstance()->getProductMinDeliveryTime($productId, $propertyId, "property", 0);

				$attributeFlag = !empty($productAttributeDelivery) ? true : false;
			}

			if ($productId && !$attributeFlag)
			{
				$productAttributeDelivery = productHelper::getInstance()->getProductMinDeliveryTime($productId);
			}
		}

		$stockStatus        = '';
		$stockAmountTooltip = "";
		$productInStock     = 0;
		$stockAmountSrc     = "";
		$stockImgFlag       = false;
		$notifyStock        = '';

		if (Redshop::getConfig()->get('USE_STOCKROOM') == 1 && empty($accessoryId))
		{
			$stockAmounts = array();

			if ($subPropertyId)
			{
				$productInStock = RedshopHelperStockroom::getStockAmountWithReserve($subPropertyId, "subproperty");
				$stockAmounts   = RedshopHelperStockroom::getStockAmountImage($subPropertyId, "subproperty", $productInStock);
				$stockImgFlag   = true;
			}

			if ($propertyId && $stockImgFlag == false)
			{
				$productInStock = RedshopHelperStockroom::getStockAmountWithReserve($propertyId, "property");
				$stockAmounts   = RedshopHelperStockroom::getStockAmountImage($propertyId, "property", $productInStock);
				$stockImgFlag   = true;
			}

			if ($productId && $stockImgFlag == false)
			{
				$productInStock = RedshopHelperStockroom::getStockAmountWithReserve($productId);
				$stockAmounts   = RedshopHelperStockroom::getStockAmountImage($productId, "product", $productInStock);
			}

			if (!empty($stockAmounts))
			{
				$stockAmountTooltip = $stockAmounts[0]->stock_amount_image_tooltip;
				$stockAmountSrc     = REDSHOP_FRONT_IMAGES_ABSPATH . 'stockroom/' . $stockAmounts[0]->stock_amount_image;
			}
		}

		// Stockroom status code
		if (strpos($templateHtml, "{stock_status") !== false
			|| strpos($templateHtml, "{stock_notify_flag}") !== false
			|| strpos($templateHtml, "{product_availability_date}") !== false)
		{
			// For current attributes
			$attributeSets = array();

			if ($product->attribute_set_id > 0)
			{
				$attributeSets = RedshopHelperProduct_Attribute::getProductAttribute(0, $product->attribute_set_id, 0, 1);
			}

			$attributes         = RedshopHelperProduct_Attribute::getProductAttribute($product->product_id);
			$attributes         = array_merge($attributes, $attributeSets);
			$productStockStatus = productHelper::getInstance()->getproductStockStatus(
				$product->product_id, count($attributes), $propertyId, $subPropertyId
			);

			if (strpos($templateHtml, "{stock_status") !== false)
			{
				$stockTags   = strstr($templateHtml, "{stock_status:");
				$newStockTag = explode("}", $stockTags);

				$stockTag     = substr($newStockTag[0], 1);
				$stockTagList = explode(":", $stockTag);

				$availableClass = "available_stock_cls";

				if (isset($stockTagList[1]) && $stockTagList[1] != "")
				{
					$availableClass = $stockTagList[1];
				}

				$outStockClass = "out_stock_cls";

				if (isset($stockTagList[2]) && $stockTagList[2] != "")
				{
					$outStockClass = $stockTagList[2];
				}

				$preOrderClass = "pre_order_cls";

				if (isset($stockTagList[3]) && $stockTagList[3] != "")
				{
					$preOrderClass = $stockTagList[3];
				}

				if (!isset($productStockStatus['regular_stock']) || !$productStockStatus['regular_stock'])
				{
					if (($productStockStatus['preorder'] && !$productStockStatus['preorder_stock'])
						|| !$productStockStatus['preorder'])
					{
						$stockStatus = '<span id="stock_status_div' . $productId . '"><div id="' . $outStockClass
							. '" class="' . $outStockClass . '">' . JText::_('COM_REDSHOP_OUT_OF_STOCK') . '</div></span>';
					}
					else
					{
						$stockStatus = "<span id='stock_status_div" . $productId . "'><div id='" . $preOrderClass
							. "' class='" . $preOrderClass . "'>" . JText::_('COM_REDSHOP_PRE_ORDER') . "</div></span>";
					}

				}
				else
				{
					$stockStatus = "<span id='stock_status_div" . $productId . "'><div id='" . $availableClass
						. "' class='" . $availableClass . "'>" . JText::_('COM_REDSHOP_AVAILABLE_STOCK') . "</div></span>";
				}
			}

			RedshopLayoutHelper::renderTag(
				'{stock_notify_flag}',
				$templateHtml,
				'product',
				array(
					'productId'          => $productId,
					'propertyId'         => $propertyId,
					'subPropertyId'      => $subPropertyId,
					'productStockStatus' => $productStockStatus,
					'isAjax'             => true
				)
			);

			if (strpos($templateHtml, "{product_availability_date}") !== false)
			{
				$productAvailabilityDateLabel = "";
				$productAvailabilityDate      = "";

				if ((!isset($productStockStatus['regular_stock']) || !$productStockStatus['regular_stock']) && $productStockStatus['preorder']
					&& $product->product_availability_date != "")
				{
					$productAvailabilityDateLabel = JText::_('COM_REDSHOP_PRODUCT_AVAILABILITY_DATE_LBL') . ": ";
					$productAvailabilityDate      = RedshopHelperDatetime::convertDateFormat($product->product_availability_date);
				}
			}
		}

		return array(
			'response'                      => $response,
			'aHrefImageResponse'            => $aHrefImageResponse,
			'aTitleImageResponse'           => $aTitleImageResponse,
			'mainImageResponse'             => $mainImageResponse,
			'stockamountSrc'                => $stockAmountSrc,
			'stockamountTooltip'            => $stockAmountTooltip,
			'ProductAttributeDelivery'      => $productAttributeDelivery,
			'attrbimg'                      => $attributeImg,
			'pr_number'                     => $productNumber,
			'productinstock'                => $productInStock,
			'stock_status'                  => $stockStatus,
			'product_mainimg'               => $productMainImg,
			'ImageName'                     => $imgName,
			'notifyStock'                   => $notifyStock,
			'product_availability_date_lbl' => $productAvailabilityDateLabel,
			'product_availability_date'     => $productAvailabilityDate,
			'additional_vids'               => $additionalVideos
		);
	}

	/**
	 * Method for prepare additional images for product.
	 *
	 * @param   array    $images           Array of media images.
	 * @param   object   $product          Product data.
	 * @param   integer  $thumbWidth       Product image thumb width.
	 * @param   integer  $thumbHeight      Product image thumb height.
	 * @param   integer  $moreThumbWidth   More image thumb width.
	 * @param   integer  $moreThumbHeight  More image thumb height.
	 *
	 * @return  string
	 *
	 * @since   __DEPLOY_VERSION__
	 */
	public static function prepareAdditionalImages($images, $product, $thumbWidth, $thumbHeight, $moreThumbWidth, $moreThumbHeight)
	{
		if (empty($images))
		{
			return '';
		}

		$return = '';

		$isWaterMarkProductAdditionalImage = Redshop::getConfig()->get('WATERMARK_PRODUCT_ADDITIONAL_IMAGE');
		$isWaterMarkProductThumbImage      = Redshop::getConfig()->get('WATERMARK_PRODUCT_THUMB_IMAGE');
		$additionalHoverImgWidth           = Redshop::getConfig()->get('ADDITIONAL_HOVER_IMAGE_WIDTH');
		$additionalHoverImgHeight          = Redshop::getConfig()->get('ADDITIONAL_HOVER_IMAGE_WIDTH');
		$isUseImageSizeSwapping            = Redshop::getConfig()->get('USE_IMAGE_SIZE_SWAPPING');
		$productAddingIsLightbox           = Redshop::getConfig()->get('PRODUCT_ADDIMG_IS_LIGHTBOX');
		$defaultProductImage               = Redshop::getConfig()->get('PRODUCT_DEFAULT_IMAGE');
		$isAdditionalHoverImage            = Redshop::getConfig()->get('ADDITIONAL_HOVER_IMAGE_ENABLE');

		// Prepare additional media images.
		foreach ($images as $image)
		{
			$thumb = $image->media_name;

			if (empty($thumb) || $thumb == $image->product_full_image || !JFile::exists(REDSHOP_FRONT_IMAGES_RELPATH . "product/" . $thumb))
			{
				continue;
			}

			$altText = productHelper::getInstance()->getAltText('product', $image->section_id, '', $image->media_id);
			$altText = !$altText ? $image->media_name : $altText;

			if ($isWaterMarkProductAdditionalImage)
			{
				$productImg     = RedshopHelperMedia::watermark('product', $thumb, $moreThumbWidth, $moreThumbHeight, "1");
				$linkImage      = RedshopHelperMedia::watermark('product', $thumb, '', '', "0");
				$imageHoverPath = RedshopHelperMedia::watermark('product', $thumb, $additionalHoverImgWidth, $additionalHoverImgHeight, '2');
			}
			else
			{
				$productImg = RedshopHelperMedia::getImagePath(
					$thumb,
					'',
					'thumb',
					'product',
					$moreThumbWidth,
					$moreThumbHeight,
					$isUseImageSizeSwapping
				);

				$linkImage = REDSHOP_FRONT_IMAGES_ABSPATH . "product/" . $thumb;

				$imageHoverPath = RedshopHelperMedia::getImagePath(
					$thumb,
					'',
					'thumb',
					'product',
					$additionalHoverImgWidth,
					$additionalHoverImgHeight,
					$isUseImageSizeSwapping
				);
			}

			if ($productAddingIsLightbox)
			{
				$productAdditionalImageDivStart = '<div class="additional_image"><a href="' . $linkImage . '" title="' . $altText . '" '
					. 'rel="myallimg">';
				$productAdditionalImageDivEnd   = "</a></div>";
				$return                         .= $productAdditionalImageDivStart;
				$return                         .= '<img src="' . $productImg . '" alt="' . $altText . '" title="' . $altText . '">';
				$productHrefEnd                 = "";
			}
			else
			{
				if ($isWaterMarkProductAdditionalImage)
				{
					$imagePath = RedshopHelperMedia::watermark('product', $thumb, $thumbWidth, $thumbHeight, '0');
				}
				else
				{
					$imagePath = RedshopHelperMedia::getImagePath(
						$thumb,
						'',
						'thumb',
						'product',
						$thumbWidth,
						$thumbHeight,
						$isUseImageSizeSwapping
					);
				}

				$thumbFileName    = REDSHOP_FRONT_IMAGES_RELPATH . "product/" . $product->product_thumb_image;
				$originalFileName = REDSHOP_FRONT_IMAGES_RELPATH . "product/" . $image->product_full_image;

				if (JFile::exists($thumbFileName))
				{
					$thumbOriginal = $product->product_thumb_image;
				}
				elseif (JFile::exists($originalFileName))
				{
					$thumbOriginal = $image->product_full_image;
				}
				else
				{
					$thumbOriginal = $defaultProductImage;
				}

				if ($isWaterMarkProductThumbImage)
				{
					$imagePathOriginal = RedshopHelperMedia::watermark('product', $thumbOriginal, $thumbWidth, $thumbHeight, '0');
				}
				else
				{
					$imagePathOriginal = RedShopHelperImages::getImagePath(
						$thumbOriginal,
						'',
						'thumb',
						'product',
						$thumbWidth,
						$thumbHeight,
						$isUseImageSizeSwapping
					);
				}

				$productAdditionalImageDivStart = '<div class="additional_image" onmouseover="display_image_add(\''
					. $imagePath . '\',' . $product->product_id . ');" onmouseout="display_image_add_out(\'' . $imagePathOriginal
					. '\',' . $product->product_id . ');">';
				$productAdditionalImageDivEnd   = "</div>";
				$return                         .= $productAdditionalImageDivStart;
				$return                         .= '<a href="javascript:void(0)"><img src="' . $productImg . '" alt="' . $altText . '" title="' . $altText . '" style="cursor: auto;">';
				$productHrefEnd                 = "</a>";
			}

			if ($isAdditionalHoverImage)
			{
				$return .= '<img src="' . $imageHoverPath . '" alt="' . $altText . '" title="' . $altText . '" class="redImagepreview" />';
			}

			$return .= $productHrefEnd;
			$return .= $productAdditionalImageDivEnd;
		}

		return $return;
	}

	/**
	 * Method for prepare additional videos for product.
	 *
	 * @param   array  $videos  Array of media images.
	 *
	 * @return  string
	 *
	 * @since   __DEPLOY_VERSION__
	 */
	public static function prepareAdditionalVideos($videos = array())
	{
		if (empty($videos))
		{
			return '';
		}

		$return = '';

		foreach ($videos as $video)
		{
			$altText = !empty($video->media_alternate_text) ? $video->media_alternate_text : $video->media_name;

			$return .= '<div id="additional_vids_' . $video->media_id . '">'
				. '<a class="modal" title="' . $altText . '" href="http://www.youtube.com/embed/' . $video->media_name . '">'
				. '<img src="https://img.youtube.com/vi/' . $video->media_name . '/default.jpg" height="80px" width="80px"/></a></div>';
		}

		return $return;
	}

	/**
	 * Method for prepare additional images for product property.
	 *
	 * @param   array    $images           Array of media images.
	 * @param   object   $product          Product data.
	 * @param   integer  $thumbWidth       Product image thumb width.
	 * @param   integer  $thumbHeight      Product image thumb height.
	 * @param   integer  $moreThumbWidth   More image thumb width.
	 * @param   integer  $moreThumbHeight  More image thumb height.
	 *
	 * @return  string
	 *
	 * @since   __DEPLOY_VERSION__
	 */
	public static function preparePropertyAdditionalImages($images, $product, $thumbWidth, $thumbHeight, $moreThumbWidth, $moreThumbHeight)
	{
		if (empty($images))
		{
			return '';
		}

		$return = '';

		$productAddingIsLightbox  = Redshop::getConfig()->get('PRODUCT_ADDIMG_IS_LIGHTBOX');
		$defaultProductImage      = Redshop::getConfig()->get('PRODUCT_DEFAULT_IMAGE');
		$isUseImageSizeSwapping   = Redshop::getConfig()->get('USE_IMAGE_SIZE_SWAPPING');
		$isAdditionalHoverImage   = Redshop::getConfig()->get('ADDITIONAL_HOVER_IMAGE_ENABLE');
		$additionalHoverImgWidth  = Redshop::getConfig()->get('ADDITIONAL_HOVER_IMAGE_WIDTH');
		$additionalHoverImgHeight = Redshop::getConfig()->get('ADDITIONAL_HOVER_IMAGE_WIDTH');

		foreach ($images as $image)
		{
			$thumb = $image->media_name;

			if (empty($thumb) || $thumb == $image->property_main_image || !JFile::exists(REDSHOP_FRONT_IMAGES_RELPATH . 'property/' . $thumb))
			{
				continue;
			}

			$altText = productHelper::getInstance()->getAltText('property', $image->section_id, '', $image->media_id);
			$altText = !$altText ? $thumb : $altText;

			if ($productAddingIsLightbox)
			{
				$thumbUrl = RedshopHelperMedia::getImagePath(
					$thumb,
					'',
					'thumb',
					'property',
					$moreThumbWidth,
					$moreThumbHeight,
					$isUseImageSizeSwapping
				);

				$propAdditionImgDivStart = '<div class="additional_image"><a href="' . REDSHOP_FRONT_IMAGES_ABSPATH . 'property/' . $thumb . '" '
					. 'title="' . $altText . '" rel="myallimg">';
				$propAdditionImgDivEnd   = "</a></div>";
				$return                  .= $propAdditionImgDivStart;
				$return                  .= "<img src='" . $thumbUrl . "' alt='" . $altText . "' title='" . $altText . "'>";
				$propHrefEnd             = "";
			}
			else
			{
				$imagePath = RedshopHelperMedia::getImagePath(
					$thumb,
					'',
					'thumb',
					'property',
					$thumbWidth,
					$thumbHeight,
					$isUseImageSizeSwapping
				);

				$propertyFileNameOriginal = REDSHOP_FRONT_IMAGES_RELPATH . "property/" . $thumb;

				if (JFile::exists($propertyFileNameOriginal))
				{
					$propertyImgPathOriginal = RedshopHelperMedia::getImagePath(
						$thumb,
						'',
						'thumb',
						'property',
						$thumbWidth,
						$thumbHeight,
						$isUseImageSizeSwapping
					);
				}
				else
				{
					$propertyImgPathOriginal = RedshopHelperMedia::getImagePath(
						$defaultProductImage,
						'',
						'thumb',
						'product',
						$thumbWidth,
						$thumbHeight,
						$isUseImageSizeSwapping
					);
				}

				$propAdditionImgDivStart = '<div class="additional_image" '
					. 'onmouseover="display_image_add(\'' . $imagePath . '\',' . $product->product_id . ');" '
					. 'onmouseout="display_image_add_out(\'' . $propertyImgPathOriginal . '\',' . $product->product_id . ');">';
				$propAdditionImgDivEnd   = "</div>";

				$thumbUrl = RedshopHelperMedia::getImagePath(
					$thumb,
					'',
					'thumb',
					'property',
					$moreThumbWidth,
					$moreThumbHeight,
					$isUseImageSizeSwapping
				);

				$return .= $propAdditionImgDivStart;
				$return .= '<a href="javascript:void(0)"><img src="' . $thumbUrl . '" alt="' . $altText . '" title="' . $altText . '" '
					. 'style="cursor: auto;">';

				$propHrefEnd = "</a>";
			}

			if ($isAdditionalHoverImage)
			{
				$thumbUrl = RedshopHelperMedia::getImagePath(
					$thumb,
					'',
					'thumb',
					'property',
					$additionalHoverImgWidth,
					$additionalHoverImgHeight,
					$isUseImageSizeSwapping
				);

				$return .= '<img src="' . $thumbUrl . '" alt="' . $altText . '" title="' . $altText . '" class="redImagepreview">';
			}

			$return .= $propHrefEnd;
			$return .= $propAdditionImgDivEnd;
		}

		return $return;
	}

	/**
	 * Method for prepare additional videos for product property.
	 *
	 * @param   array  $videos  Array of media images.
	 *
	 * @return  string
	 *
	 * @since   __DEPLOY_VERSION__
	 */
	public static function preparePropertyAdditionalVideos($videos = array())
	{
		if (empty($videos))
		{
			return '';
		}

		$return = '';

		foreach ($videos as $video)
		{
			$altText = !empty($video->media_alternate_text) ? $video->media_alternate_text : $video->media_name;

			$return .= '<div id="additional_vids_' . $video->media_id . '">'
				. '<a class="modal" title="' . $altText . '" href="http://www.youtube.com/embed/' . $video->media_name . '">'
				. '<img src="https://img.youtube.com/vi/' . $video->media_name . '/default.jpg" height="80px" width="80px"/></a></div>';
		}

		return $return;
	}

	/**
	 * Method for prepare additional images for product sub-property.
	 *
	 * @param   array    $images           Array of media images.
	 * @param   object   $product          Product data.
	 * @param   integer  $thumbWidth       Product image thumb width.
	 * @param   integer  $thumbHeight      Product image thumb height.
	 * @param   integer  $moreThumbWidth   More image thumb width.
	 * @param   integer  $moreThumbHeight  More image thumb height.
	 *
	 * @return  string
	 *
	 * @since   __DEPLOY_VERSION__
	 */
	public static function prepareSubPropertyAdditionalImages($images, $product, $thumbWidth, $thumbHeight, $moreThumbWidth, $moreThumbHeight)
	{
		if (empty($images))
		{
			return '';
		}

		$result = '';

		$productAddingIsLightbox  = Redshop::getConfig()->get('PRODUCT_ADDIMG_IS_LIGHTBOX');
		$defaultProductImage      = Redshop::getConfig()->get('PRODUCT_DEFAULT_IMAGE');
		$useImgSizeSwapping       = Redshop::getConfig()->get('USE_IMAGE_SIZE_SWAPPING');
		$isAdditionalHoverImage   = Redshop::getConfig()->get('ADDITIONAL_HOVER_IMAGE_ENABLE');
		$additionalHoverImgWidth  = Redshop::getConfig()->get('ADDITIONAL_HOVER_IMAGE_WIDTH');
		$additionalHoverImgHeight = Redshop::getConfig()->get('ADDITIONAL_HOVER_IMAGE_WIDTH');

		foreach ($images as $image)
		{
			$thumb  = $image->media_name;
			$folder = JFile::exists(REDSHOP_FRONT_IMAGES_RELPATH . "subproperty/" . $thumb) ? 'subproperty' : 'property';

			if (empty($thumb) || $thumb == $image->subattribute_color_main_image
				|| !JFile::exists(REDSHOP_FRONT_IMAGES_RELPATH . $folder . "/" . $thumb))
			{
				continue;
			}

			$altText = productHelper::getInstance()->getAltText('subproperty', $image->section_id, '', $image->media_id);
			$altText = empty($altText) ? $thumb : $altText;

			if ($productAddingIsLightbox)
			{
				$divStart = '<div class="additional_image"><a href="' . REDSHOP_FRONT_IMAGES_ABSPATH . $folder . '/' . $thumb . '" '
					. 'title="' . $altText . '" rel="myallimg">';
				$divEnd   = "</a></div>";

				$thumbUrl = RedshopHelperMedia::getImagePath($thumb, '', 'thumb', $folder, $moreThumbWidth, $moreThumbHeight, $useImgSizeSwapping);

				$result .= $divStart;
				$result .= "<img src='" . $thumbUrl . "' alt='" . $altText . "' title='" . $altText . "'>";

				$hrefEnd = "";
			}
			else
			{
				$imagePath = RedshopHelperMedia::getImagePath($thumb, '', 'thumb', $folder, $thumbWidth, $thumbHeight, $useImgSizeSwapping);

				$subPropertyFileNameOriginal = REDSHOP_FRONT_IMAGES_RELPATH . "subproperty/" . $thumb;

				if (JFile::exists($subPropertyFileNameOriginal))
				{
					$subPropertyImgPath = RedshopHelperMedia::getImagePath(
						$image->subattribute_color_image,
						'',
						'thumb',
						'subproperty',
						$thumbWidth,
						$thumbHeight,
						$useImgSizeSwapping
					);
				}
				else
				{
					$subPropertyImgPath = $defaultProductImage;
				}

				$divStart = '<div class="additional_image" onmouseover="display_image_add(\'' . $imagePath . '\',' . $product->product_id . ');" '
					. 'onmouseout="display_image_add_out(\'' . $subPropertyImgPath . '\',' . $product->product_id . ');">';
				$divEnd   = "</div>";

				$thumbUrl = RedshopHelperMedia::getImagePath(
					$thumb,
					'',
					'thumb',
					$folder,
					$moreThumbWidth,
					$moreThumbHeight,
					$useImgSizeSwapping
				);

				$result  .= $divStart;
				$result  .= '<a href="javascript:void(0)">'
					. '<img src="' . $thumbUrl . '" alt="' . $altText . '" title="' . $altText . '" style="cursor: auto;">';
				$hrefEnd = "</a>";
			}

			if ($isAdditionalHoverImage)
			{
				$thumbUrl = RedshopHelperMedia::getImagePath(
					$thumb,
					'',
					'thumb',
					$folder,
					$additionalHoverImgWidth,
					$additionalHoverImgHeight,
					$useImgSizeSwapping
				);
				$result   .= '<img src="' . $thumbUrl . '" alt="' . $altText . '" title="' . $altText . '" class="redImagepreview" />';
			}

			$result .= $hrefEnd;
			$result .= $divEnd;
		}

		return $result;
	}

	/**
	 * Method for prepare additional videos for product sub-property.
	 *
	 * @param   array  $videos  Array of media images.
	 *
	 * @return  string
	 *
	 * @since   __DEPLOY_VERSION__
	 */
	public static function prepareSubPropertyAdditionalVideos($videos = array())
	{
		if (empty($videos))
		{
			return '';
		}

		$return = '';

		foreach ($videos as $video)
		{
			$altText = !empty($video->media_alternate_text) ? $video->media_alternate_text : $video->media_name;

			$return .= '<div id="additional_vids_' . $video->media_id . '">'
				. '<a class="modal" title="' . $altText . '" href="http://www.youtube.com/embed/' . $video->media_name . '">'
				. '<img src="https://img.youtube.com/vi/' . $video->media_name . '/default.jpg" height="80px" width="80px"/></a></div>';
		}

		return $return;
	}
}
