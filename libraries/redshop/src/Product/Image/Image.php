<?php
/**
 * @package     RedShop
 * @subpackage  Helper
 *
 * @copyright   Copyright (C) 2008 - 2017 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

namespace Redshop\Product\Image;

defined('_JEXEC') or die;

/**
 * Product image helper
 *
 * @since  2.1.0
 */
class Image
{
	/**
	 * Method for get display main image
	 *
	 * @param   integer $id            Product Id
	 * @param   integer $propertyId    Property Id
	 * @param   integer $subPropertyId Sub-property Id
	 * @param   integer $thumbWidth    Width of thumb
	 * @param   integer $thumbHeight   Height of thumb
	 * @param   string  $view          Red view
	 *
	 * @return  array
	 * @throws  \Exception
	 *
	 * @since   2.1.0
	 */
	public static function getDisplayMain($id = 0, $propertyId = 0, $subPropertyId = 0, $thumbWidth = 0, $thumbHeight = 0, $view = '')
	{
		$aHrefImageResponse  = '';
		$imageName           = '';
		$aTitleImageResponse = '';
		$mainImageResponse   = '';
		$productMainImg      = '';
		$product             = \RedshopHelperProduct::getProductById($id);
		$type                = '';
		$productSKU          = $product->product_number;
		$attributeImage      = '';

		/*$refererpath=explode("view=",$_SERVER['HTTP_REFERER']);
		$getview=explode("&",$refererpath[1]);*/

		if (\JFile::exists(REDSHOP_FRONT_IMAGES_RELPATH . 'product/' . $product->product_thumb_image))
		{
			$type                = 'product';
			$imageName           = $product->product_thumb_image;
			$aTitleImageResponse = $product->product_name;
			$attributeImage      = REDSHOP_FRONT_IMAGES_ABSPATH . 'product/' . $product->product_thumb_image;
		}
		elseif (\JFile::exists(REDSHOP_FRONT_IMAGES_RELPATH . 'product/' . $product->product_full_image))
		{
			$altText = $product->product_name;
			\RedshopHelperUtility::getDispatcher()->trigger('onChangeMainProductImageAlternateText', array(&$product, &$altText));

			$type                = 'product';
			$imageName           = $product->product_full_image;
			$aTitleImageResponse = $altText;
			$attributeImage      = REDSHOP_FRONT_IMAGES_ABSPATH . 'product/' . $product->product_full_image;
		}
		else
		{
			if (\Redshop::getConfig()->get('PRODUCT_DEFAULT_IMAGE')
				&& \JFile::exists(REDSHOP_FRONT_IMAGES_RELPATH . 'product/' . \Redshop::getConfig()->get('PRODUCT_DEFAULT_IMAGE')))
			{
				$type                = 'product';
				$imageName           = \Redshop::getConfig()->get('PRODUCT_DEFAULT_IMAGE');
				$aTitleImageResponse = \Redshop::getConfig()->get('PRODUCT_DEFAULT_IMAGE');
				$attributeImage      = REDSHOP_FRONT_IMAGES_ABSPATH . 'product/' . \Redshop::getConfig()->get('PRODUCT_DEFAULT_IMAGE');
			}
		}

		if ($propertyId > 0)
		{
			$property   = \RedshopHelperProduct_Attribute::getAttributeProperties($propertyId);
			$productSKU = $property[0]->property_number;

			if (count($property) > 0 && \JFile::exists(REDSHOP_FRONT_IMAGES_RELPATH . 'property/' . $property[0]->property_main_image))
			{
				$type                = 'property';
				$imageName           = $property[0]->property_main_image;
				$aTitleImageResponse = $property[0]->text;
			}

			// Display attribute image in cart
			if (count($property) > 0 && \JFile::exists(REDSHOP_FRONT_IMAGES_RELPATH . 'product_attributes/' . $property[0]->property_image))
			{
				$attributeImage = REDSHOP_FRONT_IMAGES_ABSPATH . 'product_attributes/' . $property[0]->property_image;
			}
		}

		if ($subPropertyId > 0)
		{
			$subproperty = \RedshopHelperProduct_Attribute::getAttributeSubProperties($subPropertyId);
			$productSKU  = $subproperty[0]->subattribute_color_number;

			// Display Sub-Property Number
			if (count($subproperty) > 0
				&& \JFile::exists(REDSHOP_FRONT_IMAGES_RELPATH . 'subproperty/' . $subproperty[0]->subattribute_color_main_image))
			{
				$type                = 'subproperty';
				$imageName           = $subproperty[0]->subattribute_color_main_image;
				$aTitleImageResponse = $subproperty[0]->text;

				// $attrbimg = REDSHOP_FRONT_IMAGES_ABSPATH."subproperty/".$subproperty[0]->subattribute_color_image;
			}

			// Subproperty image in cart
			if (!empty($subproperty)
				&& \JFile::exists(REDSHOP_FRONT_IMAGES_RELPATH . 'subcolor/' . $subproperty[0]->subattribute_color_image))
			{
				$attributeImage = REDSHOP_FRONT_IMAGES_ABSPATH . 'subcolor/' . $subproperty[0]->subattribute_color_image;
			}

		}

		if (!empty($imageName) && !empty($type))
		{
			if ($type === 'product' && \Redshop::getConfig()->get('WATERMARK_PRODUCT_THUMB_IMAGE'))
			{
				$productMainImg = \RedshopHelperMedia::watermark(
					'product', $imageName, $thumbWidth, $thumbHeight, \Redshop::getConfig()->get('WATERMARK_PRODUCT_THUMB_IMAGE')
				);
			}
			else
			{
				$productMainImg = \RedshopHelperMedia::getImagePath(
					$imageName,
					'',
					'thumb',
					$type,
					$thumbWidth,
					$thumbHeight,
					\Redshop::getConfig()->get('USE_IMAGE_SIZE_SWAPPING')
				);
			}

			if ($type === 'product' && \Redshop::getConfig()->get('WATERMARK_PRODUCT_IMAGE'))
			{
				$aHrefImageResponse = \RedshopHelperMedia::watermark(
					'product', $imageName, '', '', \Redshop::getConfig()->get('WATERMARK_PRODUCT_IMAGE')
				);
			}
			else
			{
				$aHrefImageResponse = REDSHOP_FRONT_IMAGES_ABSPATH . $type . '/' . $imageName;
			}

			$altText = $product->product_name;

			\RedshopHelperUtility::getDispatcher()->trigger('onChangeMainProductImageAlternateText', array(&$product, &$altText));

			$mainImageResponse = "<img id='main_image" . $id . "' src='" . $productMainImg . "' alt='"
				. $altText . "' title='" . $altText . "'>";

			if ($view === 'category'
				|| (!\Redshop::getConfig()->get('PRODUCT_ADDIMG_IS_LIGHTBOX') || !\Redshop::getConfig()->get('PRODUCT_DETAIL_IS_LIGHTBOX')))
			{
				$mainImageResponse = $productMainImg;
			}
		}

		return array(
			'aHrefImageResponse'  => $aHrefImageResponse,
			'mainImageResponse'   => $mainImageResponse,
			'productmainimg'      => $productMainImg,
			'aTitleImageResponse' => $aTitleImageResponse,
			'imagename'           => $imageName,
			'type'                => $type,
			'attrbimg'            => $attributeImage,
			'pr_number'           => $productSKU
		);
	}

	/**
	 * Get Product image
	 *
	 * @param   integer $id                Product Id
	 * @param   string  $link              Product link
	 * @param   integer $width             Product image width
	 * @param   integer $height            Product image height
	 * @param   integer $isLight           Product detail is light
	 * @param   integer $enableHover       Enable hover
	 * @param   integer $suffixId          Suffix id
	 * @param   array   $preSelectedResult Preselected result
	 *
	 * @return  string   Product Image
	 * @throws  \Exception
	 *
	 * @since   2.1.0
	 */
	public static function getImage($id = 0, $link = '', $width, $height, $isLight = 2, $enableHover = 0, $suffixId = 0, $preSelectedResult = array())
	{
		$thumbImage    = '';
		$result        = \RedshopHelperProduct::getProductById($id);
		$isStockExists = \RedshopHelperStockroom::isStockExists($id);
		$middlePath    = REDSHOP_FRONT_IMAGES_RELPATH . 'product/';

		if (empty($result->product_full_image) && $result->product_parent_id > 0)
		{
			$result = \productHelper::getInstance()->getProductparentImage($result->product_parent_id);
		}

		$productImage = $result->product_full_image;

		if ($isLight !== 2 && $result->product_thumb_image && \JFile::exists($middlePath . $result->product_thumb_image))
		{
			$productImage = $result->product_thumb_image;
		}

		\JPluginHelper::importPlugin('redshop_product');

		// Trigger to change product image.
		\RedshopHelperUtility::getDispatcher()->trigger(
			'changeProductImage',
			array(&$thumbImage, $result, $link, $width, $height, $isLight, $enableHover, $suffixId)
		);

		if (!empty($thumbImage))
		{
			return $thumbImage;
		}

		$imageName            = '';
		$linkImageName        = '';
		$productImageExist    = $productImage && \JFile::exists($middlePath . $productImage);
		$productFullImgExist  = $result->product_full_image && \JFile::exists($middlePath . $result->product_full_image);
		$productThumbImgExist = $result->product_thumb_image && \JFile::exists($middlePath . $result->product_thumb_image);

		if (!$isStockExists && \Redshop::getConfig()->getInt('USE_PRODUCT_OUTOFSTOCK_IMAGE') === 1)
		{
			if (\Redshop::getConfig()->get('PRODUCT_OUTOFSTOCK_IMAGE')
				&& \JFile::exists($middlePath . \Redshop::getConfig()->get('PRODUCT_OUTOFSTOCK_IMAGE')))
			{
				$imageName = \Redshop::getConfig()->get('PRODUCT_OUTOFSTOCK_IMAGE');
			}
			elseif ($productImageExist)
			{
				if ($productFullImgExist && $productThumbImgExist)
				{
					$imageName     = $productImage;
					$linkImageName = $result->product_thumb_image;
				}
				elseif ($productFullImgExist || $productThumbImgExist)
				{
					$imageName = $productImage;
				}
			}
			else
			{
				return '';
			}
		}
		elseif ($productImageExist)
		{
			if ($productFullImgExist && $productThumbImgExist)
			{
				$imageName     = $productImage;
				$linkImageName = $result->product_thumb_image;
			}
			elseif ($productFullImgExist || $productThumbImgExist)
			{
				$imageName = $productImage;
			}
			else
			{
				return '';
			}
		}
		else
		{
			if (\Redshop::getConfig()->get('PRODUCT_DEFAULT_IMAGE')
				&& \JFile::exists($middlePath . \Redshop::getConfig()->get('PRODUCT_DEFAULT_IMAGE')))
			{
				$imageName = \Redshop::getConfig()->get('PRODUCT_DEFAULT_IMAGE');
			}
			else
			{
				return '';
			}
		}

		return \Redshop\Product\Image\Render::replace(
			$result,
			$imageName,
			$linkImageName,
			$link,
			$width,
			$height,
			$isLight,
			$enableHover,
			$preSelectedResult,
			$suffixId
		);
	}

	/**
	 * Method for get hidden attributes cart image
	 *
	 * @param   integer $productId     Product Id
	 * @param   integer $propertyId    Property Id
	 * @param   integer $subPropertyId Sub-property Id
	 *
	 * @return  string
	 *
	 * @since   2.1.0
	 */
	public static function getHiddenAttributeCartImage($productId, $propertyId = 0, $subPropertyId = 0)
	{
		if ($propertyId)
		{
			$property = \RedshopHelperProduct_Attribute::getAttributeProperties($propertyId);

			// Display attribute image in cart
			if (!empty($property) && \JFile::exists(REDSHOP_FRONT_IMAGES_RELPATH . 'product_attributes/' . $property[0]->property_image))
			{
				return REDSHOP_FRONT_IMAGES_ABSPATH . 'product_attributes/' . $property[0]->property_image;
			}
		}

		if ($subPropertyId)
		{
			$subproperty = \RedshopHelperProduct_Attribute::getAttributeSubProperties($subPropertyId);

			if (!empty($subproperty) && \JFile::exists(REDSHOP_FRONT_IMAGES_RELPATH . 'subcolor/' . $subproperty[0]->subattribute_color_image))
			{
				return REDSHOP_FRONT_IMAGES_ABSPATH . 'subcolor/' . $subproperty[0]->subattribute_color_image;
			}
		}

		return '';
	}

	/**
	 * Method for get product parent image
	 *
	 * @param   integer $productParentId Product parent ID
	 *
	 * @return  mixed
	 * @throws  \Exception
	 *
	 * @since   __DEPLOY_VERSION__
	 */
	public static function getParentImage($productParentId)
	{
		$result = \RedshopHelperProduct::getProductById($productParentId);

		if (empty($result->product_full_image) && $result->product_parent_id > 0)
		{
			$result = self::getParentImage($result->product_parent_id);
		}

		return $result;
	}

	/**
	 * Method for get category image of specific product
	 *
	 * @param   integer $productId  Product Id
	 * @param   integer $categoryId Category id
	 * @param   string  $link       Link
	 * @param   integer $width      Width
	 * @param   integer $height     Height
	 *
	 * @return  string
	 * @throws  \Exception
	 *
	 * @since   __DEPLOY_VERSION__
	 */
	public static function getCategoryImage($productId, $categoryId = 0, $link = '', $width = 0, $height = 0)
	{
		if (!$productId || !$categoryId)
		{
			return '';
		}

		$category = \RedshopEntityCategory::getInstance($categoryId);
		$media    = $category->getMedia();

		if ($media->isEmpty())
		{
			return '';
		}

		$categoryFull = null;

		/** @var \RedshopEntityMediaImage $item */
		foreach ($media->getAll() as $item)
		{
			if ($item->get('scope') === 'full')
			{
				$categoryFull = $item;

				break;
			}
		}

		if (null === $categoryFull || !$categoryFull->isImageExist())
		{
			return '';
		}

		$result = \RedshopHelperProduct::getProductById($productId);
		$title  = " title='" . $result->product_name . "' ";
		$alt    = " alt='" . $result->product_name . "' ";
		$image  = $category->getId() . '/' . $categoryFull->get('media_name');

		if (\Redshop::getConfig()->getBool('PRODUCT_IS_LIGHTBOX'))
		{
			$productImage = \RedshopHelperMedia::watermark('category', $image, $width, $height);

			return "<a id='a_main_image" . $productId . "' href='" . \RedshopHelperMedia::watermark('category', $image) . "' "
				. $title . " rel='myallimg'>"
				. "<img id='main_image" . $productId . "' src='" . $productImage . "' " . $title . $alt . ' />'
				. '</a>';
		}

		$productImage = \RedshopHelperMedia::watermark('category', $image, $width, $height);

		return '<a id="a_main_image' . $productId . '" href="' . $link . '" ' . $title . '>'
			. '<img id="main_image' . $productId . '" src="' . $productImage . '" ' . $title . $alt . ' />'
			. '</a>';
	}
}
