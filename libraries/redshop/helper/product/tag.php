<?php
/**
 * @package     RedSHOP.Library
 * @subpackage  Helper
 *
 * @copyright   Copyright (C) 2008 - 2017 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 *
 * @since       2.0.7
 */
defined('_JEXEC') or die;

/**
 * Class Redshop Helper for Product - Tag replacer
 *
 * @since  2.0.7
 */
class RedshopHelperProductTag
{
	/**
	 * @param   int     $productId          Product ID
	 * @param   int     $propertyId         Property ID
	 * @param   int     $subPropertyId      Sub property ID
	 * @param   string  $template           Template
	 * @param   array   $stockStatusArray   Stock status array
	 *
	 * @return  string
	 *
	 * @since   2.0.7
	 */
	public static function replaceProductStockData($productId, $propertyId, $subPropertyId, $template, $stockStatusArray)
	{
		if (strpos($template, "{stock_status") !== false)
		{
			$product = RedshopProduct::getInstance($productId);

			$stockTag     = strstr($template, "{stock_status");
			$newstocktag  = explode("}", $stockTag);
			$realStockTag = $newstocktag[0] . "}";

			$stockTagT = substr($newstocktag[0], 1);
			$stsArray = explode(":", $stockTagT);

			$availClass = "available_stock_cls";

			if (isset($stsArray[1]) && $stsArray[1] != "")
			{
				$availClass = $stsArray[1];
			}

			$out_stock_class = "out_stock_cls";

			if (isset($stsArray[2]) && $stsArray[2] != "")
			{
				$out_stock_class = $stsArray[2];
			}

			$pre_order_class = "pre_order_cls";

			if (isset($stsArray[3]) && $stsArray[3] != "")
			{
				$pre_order_class = $stsArray[3];
			}

			if ($product->not_for_sale == 1)
			{
				$stockStatus = '';
			}
			elseif (!isset($stockStatusArray['regular_stock']) || !$stockStatusArray['regular_stock'])
			{
				if (($stockStatusArray['preorder'] && !$stockStatusArray['preorder_stock']) || !$stockStatusArray['preorder'])
				{
					$stockStatus = "<span id='stock_status_div" . $productId . "'><div id='" . $out_stock_class
						. "' class='" . $out_stock_class . "'>" . JText::_('COM_REDSHOP_OUT_OF_STOCK') . "</div></span>";
				}
				else
				{
					$stockStatus = "<span id='stock_status_div" . $productId . "'><div id='" . $pre_order_class
						. "' class='" . $pre_order_class . "'>" . JText::_('COM_REDSHOP_PRE_ORDER') . "</div></span>";
				}
			}
			else
			{
				$stockStatus = "<span id='stock_status_div" . $productId . "'><div id='" . $availClass . "' class='"
					. $availClass . "'>" . JText::_('COM_REDSHOP_AVAILABLE_STOCK') . "</div></span>";
			}

			$template = str_replace($realStockTag, $stockStatus, $template);
		}

		RedshopLayoutHelper::renderTag(
			'{stock_notify_flag}', $template, 'product', array(
				'productId'          => $productId, 'propertyId' => $propertyId, 'subPropertyId' => $subPropertyId,
				'productStockStatus' => $stockStatusArray
			)
		);

		if (strpos($template, "{product_availability_date}") !== false)
		{
			$product       = RedshopHelperProduct::getProductById($productId);

			if ((!isset($stockStatusArray['regular_stock']) || !$stockStatusArray['regular_stock']) && $stockStatusArray['preorder'])
			{
				if ($product->product_availability_date)
				{
					$replace = "<span id='stock_availability_date_lbl" . $productId . "'>"
						. JText::_('COM_REDSHOP_PRODUCT_AVAILABILITY_DATE_LBL')
						. ": </span>";
					$template = str_replace("{product_availability_date_lbl}", $replace, $template);

					$replace = "<span id='stock_availability_date" . $productId
						. "'>" . RedshopHelperDatetime::convertDateFormat($product->product_availability_date) . "</span>";
					$template = str_replace("{product_availability_date}", $replace, $template);
				}
				else
				{
					$replace = "<span id='stock_availability_date_lbl" . $productId . "'></span>";
					$template = str_replace("{product_availability_date_lbl}", $replace, $template);

					$replace = "<span id='stock_availability_date" . $productId . "'></span>";
					$template = str_replace("{product_availability_date}", $replace, $template);
				}

			}
			else
			{
				$replace = "<span id='stock_availability_date_lbl" . $productId . "'></span>";
				$template = str_replace("{product_availability_date_lbl}", $replace, $template);

				$replace = "<span id='stock_availability_date" . $productId . "'></span>";
				$template = str_replace("{product_availability_date}", $replace, $template);
			}
		}

		return $template;
	}
}
