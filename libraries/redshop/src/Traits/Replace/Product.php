<?php
/**
 * @package     Redshop.Libraries
 *
 * @copyright   Copyright (C) 2008 - 2019 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

namespace Redshop\Traits\Replace;

use Joomla\CMS\Factory;
use Joomla\CMS\Session\Session;
use Joomla\CMS\Plugin\PluginHelper;
use Joomla\CMS\Router\Route;
use Joomla\CMS\Language\Text;

defined('_JEXEC') || die;

/**
 * For classes extends class RedshopTagsAbstract
 *
 * @since  __DEPLOY_VERSION__
 */
trait Product
{
	/**
	 * Replace manufacturer
	 *
	 * @param string  $template
	 * @param integer $productId
	 *
	 * @return  string
	 *
	 * @since   __DEPLOY_VERSION
	 */
	protected function replaceCommonProduct($template, $productId)
	{
		$productData    = \Redshop\Product\Product::getProductById($productId);
		$replaceProduct = [];
		$link           = Route::_(
			'index.php?option=com_redshop&view=product&pid='
			. $productId . '&cid=' . $productData->cat_in_sefurl
		);
		$extraFieldName = \Redshop\Helper\ExtraFields::getSectionFieldNames(
			\RedshopHelperExtrafields::SECTION_PRODUCT,
			1,
			1
		);

		$replaceProduct['{product_name}'] = \RedshopLayoutHelper::render(
			'tags.common.link',
			[
				'link'    => $link,
				'content' => $productData->product_name
			],
			'',
			$this->optionLayout
		);

		$template     = \RedshopHelperProduct::getProductOnSaleComment($productData, $template);
		$template     = \RedshopHelperProduct::getProductNotForSaleComment($productData, $template);
		$template     = \RedshopHelperProduct::getSpecialProductComment($productData, $template);
		$childProduct = \RedshopHelperProduct::getChildProduct($productId);

		if (count($childProduct) > 0) {
			$isChilds   = true;
			$attributes = array();
		} else {
			$isChilds = false;

			// Get attributes
			$attributesSet = array();

			if ($productData->attribute_set_id > 0) {
				$attributesSet = \Redshop\Product\Attribute::getProductAttribute(
					0,
					$productData->attribute_set_id,
					0,
					1
				);
			}

			$attributes = \Redshop\Product\Attribute::getProductAttribute($productId);
			$attributes = array_merge($attributes, $attributesSet);
		}

		/////////////////////////////////// Product attribute  Start /////////////////////////////////
		$totaltAtr = count($attributes);

		// Check product for not for sale
		$template = \RedshopHelperProductTag::getExtraSectionTag($extraFieldName, $productId, "1", $template, 1);

		$attributeTemplate = \Redshop\Template\Helper::getAttribute($template);
		$template          = \Redshop\Product\Stock::replaceInStock(
			$productId,
			$template,
			$attributes,
			$attributeTemplate
		);

		$template = \RedshopTagsReplacer::_(
			'attributes',
			$template,
			array(
				'productId'         => $productId,
				'attributes'        => $attributes,
				'attributeTemplate' => $attributeTemplate,
				'isChild'           => $isChilds,
				'displayIndCart'    => $totaltAtr
			)
		);
		// Get cart tempalte
		$template = \Redshop\Cart\Render::replace($productId, 0, 0, 0, $template, $isChilds);

		$replaceProduct['{product_id_lbl}']     = Text::_('COM_REDSHOP_PRODUCT_ID_LBL');
		$replaceProduct['{product_id}']         = $productData->product_id;
		$replaceProduct['{product_number_lbl}'] = Text::_('COM_REDSHOP_PRODUCT_NUMBER_LBL');
		$replaceProduct['{product_number}']     = $productData->product_number;
		$replaceProduct['{product_s_desc}']     = $productData->product_s_desc;

		if (strstr($template, '{product_desc}')) {
			$replaceProduct['{product_desc}'] = \RedshopHelperUtility::maxChars(
				$productData->product_desc,
				\Redshop::getConfig()->get(
					'CATEGORY_PRODUCT_DESC_MAX_CHARS'
				),
				\Redshop::getConfig()->get(
					'CATEGORY_PRODUCT_DESC_END_SUFFIX'
				)
			);
		}

		$infoImg = $this->getWidthHeight(
			$template,
			'product_thumb_image',
			$this->height,
			$this->width
		);

		$prodThumbImage = \Redshop\Product\Image\Image::getImage(
			$productData->product_id,
			$link,
			$infoImg['width'],
			$infoImg['height']
		);

		$replaceProduct[$infoImg['imageTag']] = $prodThumbImage;

		$template = \RedshopHelperProductTag::getExtraSectionTag(
			$extraFieldName,
			$productData->product_id,
			"1",
			$template
		);

		$productAvailabilityDate = strstr($template, "{product_availability_date}");
		$stockNotifyFlag         = strstr($template, "{stock_notify_flag}");
		$stockStatus             = strstr($template, "{stock_status");

		$attributeproductStockStatus = array();

		if ($productAvailabilityDate || $stockNotifyFlag || $stockStatus) {
			$attributeproductStockStatus = \RedshopHelperProduct::getproductStockStatus(
				$productData->product_id,
				$totaltAtr
			);
		}

		$template = \Redshop\Helper\Stockroom::replaceProductStockData(
			$productData->product_id,
			0,
			0,
			$template,
			$attributeproductStockStatus
		);

		return $this->strReplace($replaceProduct, $template);
	}
}