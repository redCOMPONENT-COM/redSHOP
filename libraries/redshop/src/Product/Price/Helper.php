<?php
/**
 * @package     RedShop
 * @subpackage  Plugin
 *
 * @copyright   Copyright (C) 2008 - 2017 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

namespace Redshop\Product\Price;

defined('_JEXEC') or die;

/**
 * Product' price helper
 *
 * @package     Redshop\Product\Price
 *
 * @since       2.0.7
 */
class Helper
{
	/**
	 * @param   int    $productId  Product ID
	 *
	 * @return  mixed  The return value or null if the query failed.
	 *
	 * @since   2.0.7
	 */
	public static function getPrices($productId)
	{
		if (!empty($productId))
		{
			$db = \JFactory::getDbo();
			$query = $db->getQuery(true)
				->select($db->qn('product_price'))
				->from($db->qn('#__redshop_product_price'))
				->where($db->qn('product_id') . ' = ' . $db->q((int) $productId));

			return $db->setQuery($query)->loadColumn();
		}

		return null;
	}

	/**
	 * Get Max and Min of Product Price
	 *
	 * @param   int  $productId  Product Id
	 *
	 * @return  array
	 */
	public static function getMinMax($productId)
	{
		$attributes = RedshopHelperProduct_Attribute::getProductAttribute($productId);
		$propertyIds = array();
		$subPropertyIds = array();
		$propertyPriceList = array();
		$subPropertyPriceList = array();

		foreach ($attributes as $key => $attribute)
		{
			foreach ($attribute->properties as $property)
			{
				$propertyIds[] = $property->property_id;
				$subProperties = RedshopHelperProduct_Attribute::getAttributeSubProperties(0, $property->property_id);

				foreach ($subProperties as $subProperty)
				{
					$subPropertyIds[] = $subProperty->value;
				}
			}
		}

		$db = JFactory::getDbo();

		if (!empty($productId))
		{
			$productPriceList = self::getPrices($productId);
		}

		if (!empty($propertyIds))
		{
			$query = $db->getQuery(true)
				->select($db->qn('product_price'))
				->from($db->qn('#__redshop_product_attribute_price'))
				->where($db->qn('section') . ' = ' . $db->q('property'))
				->where($db->qn('section_id') . ' IN (' . implode(',', $propertyIds) . ')');
			$propertyPriceList = $db->setQuery($query)->loadColumn();
		}

		if (!empty($subPropertyIds))
		{
			$query = $db->getQuery(true)
				->select($db->qn('product_price'))
				->from($db->qn('#__redshop_product_attribute_price'))
				->where($db->qn('section') . ' = ' . $db->q('subproperty'))
				->where($db->qn('section_id') . ' IN (' . implode(',', $subPropertyIds) . ')');
			$subPropertyPriceList = $db->setQuery($query)->loadColumn();
		}

		$productPriceList = array_unique(array_merge($productPriceList, $propertyPriceList, $subPropertyPriceList));
		$productPrice['min'] = min($productPriceList);
		$productPrice['max'] = max($productPriceList);

		return $productPrice;
	}
}
