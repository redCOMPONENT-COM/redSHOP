<?php
/**
 * @package     RedShop
 * @subpackage  Helper
 *
 * @copyright   Copyright (C) 2008 - 2019 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

namespace Redshop\Helper;

defined('_JEXEC') or die;

/**
 * Stockroom helper
 *
 * @since  2.1.0
 */
class Stockroom
{
	/**
	 * Method for get attribute with stock
	 *
	 * @param   array $properties List of property
	 *
	 * @return  array
	 *
	 * @since   2.1.0
	 */
	public static function getAttributePropertyWithStock($properties = array())
	{
		if (empty($properties))
		{
			return array();
		}

		$propertiesWithStock = array();

		foreach ($properties as $property)
		{
			if (\RedshopHelperStockroom::isStockExists($property->property_id, 'property'))
			{
				$propertiesWithStock[] = $property;

				continue;
			}

			$subProperties = \RedshopHelperProduct_Attribute::getAttributeSubProperties(0, $property->value);

			if (empty($subProperties))
			{
				continue;
			}

			foreach ($subProperties as $subProperty)
			{
				if (\RedshopHelperStockroom::isStockExists($subProperty->subattribute_color_id, 'subproperty'))
				{
					$propertiesWithStock[] = $property;
					break;
				}
			}
		}

		return $propertiesWithStock;
	}

	/**
	 * Method for get sub-attribute with stock
	 *
	 * @param   array $subProperties List of property
	 *
	 * @return  array
	 *
	 * @since   2.1.0
	 */
	public static function getAttributeSubPropertyWithStock($subProperties)
	{
		if (empty($subProperties))
		{
			return array();
		}

		$results = array();

		foreach ($subProperties as $subProperty)
		{
			if (\RedshopHelperStockroom::isStockExists($subProperty->subattribute_color_id, 'subproperty'))
			{
				$results[] = $subProperty;
			}
		}

		return $results;
	}

	/**
	 * Method for replace product stock data.
	 *
	 * @param   integer $productId     Product ID
	 * @param   integer $propertyId    Property ID
	 * @param   integer $subPropertyId Sub-property ID
	 * @param   string  $html          Template HTML
	 * @param   array   $stockStatuses Stock status data.
	 *
	 * @return  string
	 * @throws  \Exception
	 *
	 * @since   2.1.0
	 */
	public static function replaceProductStockData($productId, $propertyId, $subPropertyId, $html, $stockStatuses)
	{
		if (strpos($html, '{stock_status') !== false)
		{
			$product = \RedshopProduct::getInstance($productId);

			$stockTag     = strstr($html, "{stock_status");
			$newStockTag  = explode("}", $stockTag);
			$realStockTag = $newStockTag[0] . "}";

			$tagConfig = substr($newStockTag[0], 1);
			$tagConfig = explode(":", $tagConfig);

			$availableClass = "available_stock_cls";

			if (isset($tagConfig[1]) && $tagConfig[1] != "")
			{
				$availableClass = $tagConfig[1];
			}

			$outStockClass = "out_stock_cls";

			if (isset($tagConfig[2]) && $tagConfig[2] != "")
			{
				$outStockClass = $tagConfig[2];
			}

			$preOrderClass = "pre_order_cls";

			if (isset($tagConfig[3]) && $tagConfig[3] != "")
			{
				$preOrderClass = $tagConfig[3];
			}

			if ($product->not_for_sale == 1)
			{
				$stockStatus = '';
			}
			elseif (!isset($stockStatuses['regular_stock']) || !$stockStatuses['regular_stock'])
			{
				if (($stockStatuses['preorder'] && !$stockStatuses['preorder_stock']) || !$stockStatuses['preorder'])
				{
					$stockStatus = "<span id='stock_status_div" . $productId . "'><div id='" . $outStockClass
						. "' class='" . $outStockClass . "'>" . \JText::_('COM_REDSHOP_OUT_OF_STOCK') . "</div></span>";
				}
				else
				{
					$stockStatus = "<span id='stock_status_div" . $productId . "'><div id='" . $preOrderClass
						. "' class='" . $preOrderClass . "'>" . \JText::_('COM_REDSHOP_PRE_ORDER') . "</div></span>";
				}
			}
			else
			{
				$stockStatus = "<span id='stock_status_div" . $productId . "'><div id='" . $availableClass . "' class='"
					. $availableClass . "'>" . \JText::_('COM_REDSHOP_AVAILABLE_STOCK') . "</div></span>";
			}

			$html = str_replace($realStockTag, $stockStatus, $html);
		}

		\RedshopLayoutHelper::renderTag(
			'{stock_notify_flag}',
			$html,
			'product',
			array(
				'productId'          => $productId,
				'propertyId'         => $propertyId,
				'subPropertyId'      => $subPropertyId,
				'productStockStatus' => $stockStatuses
			)
		);

		if (strpos($html, "{product_availability_date}") !== false)
		{
			$product = \RedshopHelperProduct::getProductById($productId);

			if ((!isset($stockStatuses['regular_stock']) || !$stockStatuses['regular_stock']) && $stockStatuses['preorder'])
			{
				if ($product->product_availability_date)
				{
					$html = str_replace(
						"{product_availability_date_lbl}",
						"<span id='stock_availability_date_lbl" . $productId . "'>"
						. \JText::_('COM_REDSHOP_PRODUCT_AVAILABILITY_DATE_LBL') . ": </span>",
						$html
					);
					$html = str_replace(
						"{product_availability_date}",
						"<span id='stock_availability_date" . $productId . "'>"
						. \RedshopHelperDatetime::convertDateFormat($product->product_availability_date) . "</span>",
						$html
					);
				}
				else
				{
					$html = str_replace(
						"{product_availability_date_lbl}",
						"<span id='stock_availability_date_lbl" . $productId . "'></span>",
						$html
					);
					$html = str_replace(
						"{product_availability_date}",
						"<span id='stock_availability_date" . $productId . "'></span>",
						$html
					);
				}
			}
			else
			{
				$html = str_replace(
					"{product_availability_date_lbl}",
					"<span id='stock_availability_date_lbl" . $productId . "'></span>",
					$html
				);
				$html = str_replace(
					"{product_availability_date}",
					"<span id='stock_availability_date" . $productId . "'></span>",
					$html
				);
			}
		}

		return $html;
	}
}
