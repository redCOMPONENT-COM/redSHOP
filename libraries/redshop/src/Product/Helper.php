<?php
/**
 * @package     RedShop
 * @subpackage  Plugin
 *
 * @copyright   Copyright (C) 2008 - 2017 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

namespace Redshop\Product;

defined('_JEXEC') or die;

/**
 * @package     Redshop\Product
 *
 * @since       2.0.7
 */
class Helper
{
	/**
	 * @param   int  $productId               Product ID
	 * @param   int  $totalAttribute          Total attribute
	 * @param   int  $selectedPropertyId      Selected property ID
	 * @param   int  $selectedSubPropertyId   Selected sub property ID
	 *
	 * @return  array
	 *
	 * @since   2.0.7
	 */
	public static function getProductStockStatus($productId = 0, $totalAttribute = 0, $selectedPropertyId = 0, $selectedSubPropertyId = 0)
	{
		$producDetail                 = \RedshopHelperProduct::getProductById($productId);
		$productPreOrder              = trim($producDetail->preorder);
		$returnData                   = array();
		$returnData['preorder']       = 0;
		$returnData['preorder_stock'] = 0;

		if ($selectedPropertyId)
		{
			if ($selectedSubPropertyId)
			{
				// Count status for selected subproperty
				$stocksts = \RedshopHelperStockroom::isStockExists($selectedSubPropertyId, "subproperty");

				if (!$stocksts && (($productPreOrder == "global" && \Redshop::getConfig()->get('ALLOW_PRE_ORDER')) || ($productPreOrder == "yes")))
				{
					$prestocksts                  = \RedshopHelperStockroom::isPreorderStockExists($selectedSubPropertyId, "subproperty");
					$returnData['preorder']       = 1;
					$returnData['preorder_stock'] = $prestocksts;
				}
			}
			else
			{
				// Count status for selected property
				$stocksts = \RedshopHelperStockroom::isStockExists($selectedPropertyId, "property");

				if (!$stocksts && (($productPreOrder == "global" && \Redshop::getConfig()->get('ALLOW_PRE_ORDER')) || ($productPreOrder == "yes")))
				{
					$prestocksts                  = \RedshopHelperStockroom::isPreorderStockExists($selectedPropertyId, "property");
					$returnData['preorder']       = 1;
					$returnData['preorder_stock'] = $prestocksts;
				}
			}
		}
		else
		{
			$stocksts = \RedshopHelperStockroom::getFinalStockofProduct($productId, $totalAttribute);

			if (!$stocksts && (($productPreOrder == "global" && \Redshop::getConfig()->get('ALLOW_PRE_ORDER')) || ($productPreOrder == "yes")))
			{
				$prestocksts                  = \RedshopHelperStockroom::getFinalPreorderStockofProduct($productId, $totalAttribute);
				$returnData['preorder']       = 1;
				$returnData['preorder_stock'] = $prestocksts;
			}
		}

		$returnData['regular_stock'] = $stocksts;

		return $returnData;
	}

	/**
	 * @param   array  $products  Array of product object
	 *
	 * @return  array
	 *
	 * @since   2.0.7
	 */
	public static function removeOutofstockProduct($products)
	{
		$filterProducts = array();

		foreach ($products as $product)
		{
			$pid     = $product->product_id;
			$attributesSet = array();

			if ($product->attribute_set_id > 0)
			{
				$attributesSet = \RedshopHelperProduct_Attribute::getProductAttribute(0, $product->attribute_set_id, 0, 1);
			}

			$attributes      = \RedshopHelperProduct_Attribute::getProductAttribute($product->product_id);
			$attributes      = array_merge($attributes, $attributesSet);
			$totalAttributes = count($attributes);

			$stockAmount = \RedshopHelperStockroom::getFinalStockofProduct($pid, $totalAttributes);

			if ($stockAmount)
			{
				$filterProducts[] = $product;
			}
		}

		return $filterProducts;
	}
}
