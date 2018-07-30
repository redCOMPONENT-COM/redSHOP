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
}
