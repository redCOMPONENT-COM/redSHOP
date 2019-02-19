<?php
/**
 * @package     Redshop\Repositories
 * @subpackage  Product
 *
 * @copyright   Copyright (C) 2008 - 2019 redCOMPONENT.com. All rights reserved.
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
	 * @param   string $productNumber Order number
	 *
	 * @return  mixed
	 *
	 * @since   2.1.0.0
	 */
	public static function getProductByNumber($productNumber)
	{
		$db    = \JFactory::getDbo();
		$query = $db->getQuery(true)
			->select('*')
			->from($db->quoteName('#__redshop_product'))
			->where($db->quoteName('product_number') . ' = ' . $db->quote($productNumber));

		return $db->setQuery($query)->loadObject();
	}
}
