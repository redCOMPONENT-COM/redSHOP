<?php
/**
 * @package     Redshop\Repositories
 * @subpackage  Product
 *
 * @copyright   Copyright (C) 2012 - 2017 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later, see LICENSE.
 */

namespace Redshop\Repositories;

/**
 * @package     Redshop\Repositories
 *
 * @since       2.1.0
 */
class Product
{
	/**
	 * @param   integer $productId ProductId
	 *
	 * @return  mixed
	 *
	 * @since   2.1.0
	 */
	public static function getRelatedProductIds($productId)
	{
		$db    = \JFactory::getDbo();
		$query = $db->getQuery(true);

		$query->select($db->quoteName('related_id'))
			->from($db->quoteName('#__redshop_product_related'))
			->where($db->quoteName('product_id') . ' = ' . (int) $productId);

		return $db->setQuery($query)->loadColumn();
	}

	/**
	 * @param   integer $productId ProductId
	 *
	 * @return  mixed
	 *
	 * @since   2.1.0
	 */
	public static function getCategoryIds($productId)
	{
		$db    = \JFactory::getDbo();
		$query = $db->getQuery(true)
			->select($db->qn('category_id'))
			->from($db->qn('#__redshop_product_category_xref'))
			->where($db->qn('product_id') . ' = ' . (int) $productId);

		return $db->setQuery($query)->loadColumn();
	}

	/**
	 * @param   integer $productId ProductId
	 *
	 * @return  mixed
	 *
	 * @since   2.1.0
	 */
	public static function getPrices($productId)
	{
		$db    = \JFactory::getDbo();
		$query = $db->getQuery(true)
			->select($db->qn('product_price'))
			->from($db->qn('#__redshop_product_price'))
			->where($db->qn('product_id') . ' = ' . $db->q((int) $productId));

		return $db->setQuery($query)->loadColumn();
	}

	/**
	 * @param   string $productNumber Product number
	 *
	 * @return  mixed
	 *
	 * @since   2.1.0
	 */
	public static function getProductIdFromNumber($productNumber)
	{
		$db    = \JFactory::getDbo();
		$query = $db->getQuery(true)
			->select('product_id')
			->from($db->qn('#__redshop_product'))
			->where($db->qn('product_number') . ' = ' . $db->q($productNumber));
		$db->setQuery($query);

		return $db->setQuery($query)->loadResult();
	}

	/**
	 * @param   integer $productId Product id
	 *
	 * @return  array
	 *
	 * @since   2.1.0
	 */
	public static function getAttributes($productId)
	{
		$db    = \JFactory::getDbo();
		$query = $db->getQuery(true)
			->select('*')
			->from($db->quoteName('#__redshop_product_attribute'))
			->where($db->quoteName('product_id') . ' = ' . (int) $productId)
			->order($db->quoteName('ordering') . ' ASC');

		return $db->setQuery($query)->loadObjectList();
	}

	/**
	 * @param   integer $attributeId Attribute id
	 *
	 * @return  array
	 *
	 * @since   2.1.0
	 */
	public static function getProperties($attributeId)
	{
		$db    = \JFactory::getDbo();
		$query = $db->getQuery(true)
			->select('*')
			->from($db->quoteName('#__redshop_product_attribute_property'))
			->where($db->quoteName('attribute_id') . ' = ' . (int) $attributeId)
			->order($db->quoteName('ordering') . ' ASC');

		return $db->setQuery($query)->loadObjectList();
	}

	/**
	 * @param   integer $propertyId Property id
	 *
	 * @return  array
	 *
	 * @since   2.1.0
	 */
	public static function getSubAttributes($propertyId)
	{
		$db    = \JFactory::getDbo();
		$query = $db->getQuery(true)
			->select('*')
			->from($db->quoteName('#__redshop_product_subattribute_color'))
			->where($db->quoteName('subattribute_id') . ' = ' . (int) $propertyId)
			->order($db->quoteName('ordering') . ' ASC');

		return $db->setQuery($query)->loadObjectList();
	}
}
