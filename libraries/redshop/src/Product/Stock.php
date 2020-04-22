<?php
/**
 * @package     RedShop
 * @subpackage  Helper
 *
 * @copyright   Copyright (C) 2008 - 2020 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

namespace Redshop\Product;

defined('_JEXEC') or die;

/**
 * Product stock helper
 *
 * @since  2.1.0
 */
class Stock
{
	/**
	 * Method for replace instock tag
	 *
	 * @param   integer $productId         Product id
	 * @param   string  $content           Template content
	 * @param   array   $attributes        Attributes
	 * @param   mixed   $attributeTemplate Attribute template
	 *
	 * @return  mixed
	 *
	 * @since   2.1.0
	 */
	public static function replaceInStock($productId = 0, $content = '', $attributes = array(), $attributeTemplate = array())
	{
		if (empty($attributeTemplate))
		{
			$attributes = array();
		}

		$stockStatusFlag       = false;
		$totalAttributes       = count($attributes);
		$sectionId             = $productId;
		$section               = "product";
		$selectedPropertyId    = 0;
		$selectedSubPropertyId = 0;

		foreach ($attributes as $attribute)
		{
			$selectedId = array();
			$properties = \RedshopHelperProduct_Attribute::getAttributeProperties(0, $attribute->attribute_id, $productId);

			if (!empty($attribute->text) && !empty($properties))
			{
				foreach ($properties as $property)
				{
					if ($property->setdefault_selected)
					{
						$selectedId[] = $property->property_id;
					}
				}

				if (!empty($selectedId))
				{
					if ($attribute->allow_multiple_selection)
					{
						$selectedPropertyId = implode(",", $selectedId);
					}
					else
					{
						$selectedPropertyId = $selectedId[count($selectedId) - 1];
					}

					$sectionId = $selectedPropertyId;
					$section   = "property";
				}

				if (!empty($selectedId))
				{
					$stockStatusFlag = true;
					$i               = count($selectedId) - 1;
					$subProperties   = \RedshopHelperProduct_Attribute::getAttributeSubProperties(0, $selectedId[$i]);
					$selectedId      = array();

					foreach ($subProperties as $subProperty)
					{
						if ($subProperty->setdefault_selected)
						{
							$selectedId[] = $subProperty->subattribute_color_id;
						}
					}

					if (!empty($selectedId))
					{
						if ($subProperties[0]->setmulti_selected)
						{
							$selectedSubPropertyId = implode(",", $selectedId);
						}
						else
						{
							$selectedSubPropertyId = $selectedId[count($selectedId) - 1];
						}

						$sectionId = $selectedSubPropertyId;
						$section   = "subproperty";
					}
				}
			}
		}

		$productInStock = \RedshopHelperStockroom::getStockAmountWithReserve($sectionId, $section);

		if ($productInStock == 0)
		{
			$product         = \Redshop\Product\Product::getProductById($productId);
			$productPreOrder = $product->preorder;

			if (($productPreOrder == "global" && \Redshop::getConfig()->get('ALLOW_PRE_ORDER'))
				|| ($productPreOrder == "yes")
				|| ($productPreOrder == "" && \Redshop::getConfig()->get('ALLOW_PRE_ORDER')))
			{
				$productInStock = \RedshopHelperStockroom::getPreorderStockAmountwithReserve($sectionId, $section);
			}
		}

		if (strpos($content, "{products_in_stock}") !== false)
		{
			$content = str_replace(
				"{products_in_stock}",
				\JText::_('COM_REDSHOP_PRODUCT_IN_STOCK_LBL')
				. ' <span id="displayProductInStock' . $productId . '">' . $productInStock . '</span>',
				$content
			);
		}

		if (strpos($content, "{product_stock_amount_image}") !== false)
		{
			$stockAmounts     = \RedshopHelperStockroom::getStockAmountImage($sectionId, $section, $productInStock);
			$stockAmountImage = "";

			if (!empty($stockAmounts))
			{
				$stockAmountImage = \RedshopLayoutHelper::render(
					'product.stock_amount_image',
					array(
						'product_id'       => $productId,
						'stockamountImage' => $stockAmounts[0]
					)
				);
			}

			$content = str_replace("{product_stock_amount_image}", $stockAmountImage, $content);
		}

		return $content;
	}
}
