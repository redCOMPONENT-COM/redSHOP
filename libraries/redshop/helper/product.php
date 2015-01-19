<?php
/**
 * @package     RedSHOP.Library
 * @subpackage  Helper
 *
 * @copyright   Copyright (C) 2008 - 2015 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

defined('_JEXEC') or die;

/**
 * Class Redshop Helper Product
 *
 * @since  1.5
 */
class RedshopHelperProduct
{
	protected static $products = array();

	/**
	 * Get product information
	 *
	 * @param   int  $productId  Product id
	 * @param   int  $userId     User id
	 *
	 * @return mixed
	 */
	public static function getProductById($productId, $userId = 0)
	{
		if (!$userId)
		{
			$user = JFactory::getUser();
			$userId = $user->id;
		}

		if (!array_key_exists($productId . '.' . $userId, self::$products))
		{
			$db = JFactory::getDbo();
			$query = self::getMainProductQuery(false, $userId);

			// Select product
			$query->where($db->qn('p.product_id') . ' = ' . (int) $productId);

			$db->setQuery($query);

			if (self::$products[$productId . '.' . $userId] = $db->loadObject())
			{
				self::setProductRelates(array($productId . '.' . $userId => self::$products[$productId . '.' . $userId]), $userId);
			}
		}

		return self::$products[$productId . '.' . $userId];
	}

	/**
	 * Get Main Product Query
	 *
	 * @param   bool|JDatabaseQuery  $query   Get query or false
	 * @param   int                  $userId  User id
	 *
	 * @return JDatabaseQuery
	 */
	public static function getMainProductQuery($query = false, $userId = 0)
	{
		$userHelper = new rsUserhelper;
		$shopperGroupId = $userHelper->getShopperGroup($userId);
		$db = JFactory::getDbo();

		if (!$query)
		{
			$query = $db->getQuery(true);
		}

		$query->select(array('p.*', 'p.product_id'))
			->from($db->qn('#__redshop_product', 'p'));

		// Require condition
		$query->group('p.product_id');

		// Select price
		$query->select(
			array(
				'pp.price_id', $db->qn('pp.product_price', 'price_product_price'),
				$db->qn('pp.product_currency', 'price_product_currency'), $db->qn('pp.discount_price', 'price_discount_price'),
				$db->qn('pp.discount_start_date', 'price_discount_start_date'), $db->qn('pp.discount_end_date', 'price_discount_end_date')
			)
		)
			->leftJoin(
				$db->qn('#__redshop_product_price', 'pp')
				. ' ON p.product_id = pp.product_id AND ((pp.price_quantity_start <= 1 AND pp.price_quantity_end >= 1)'
				. ' OR (pp.price_quantity_start = 0 AND pp.price_quantity_end = 0)) AND pp.shopper_group_id = ' . (int) $shopperGroupId
			)
			->order('pp.price_quantity_start ASC');

		// Select category
		$query->select(array('pc.category_id'))
			->leftJoin($db->qn('#__redshop_product_category_xref', 'pc') . ' ON pc.product_id = p.product_id');

		// Select media
		$query->select(array('media.media_alternate_text', 'media.media_id'))
			->leftJoin(
				$db->qn('#__redshop_media', 'media')
				. ' ON media.section_id = p.product_id AND media.media_section = ' . $db->q('product')
				. ' AND media.media_type = ' . $db->q('images') . ' AND media.media_name = p.product_full_image'
			);

		// Select ratings
		$subQuery = $db->getQuery(true)
			->select('COUNT(pr1.rating_id)')
			->from($db->qn('#__redshop_product_rating', 'pr1'))
			->where('pr1.product_id = p.product_id')
			->where('pr1.published = 1');

		$query->select('(' . $subQuery . ') AS count_rating');

		$subQuery = $db->getQuery(true)
			->select('SUM(pr2.user_rating)')
			->from($db->qn('#__redshop_product_rating', 'pr2'))
			->where('pr2.product_id = p.product_id')
			->where('pr2.published = 1');

		$query->select('(' . $subQuery . ') AS sum_rating');

		// Count Accessories
		$subQuery = $db->getQuery(true)
			->select('COUNT(pa.accessory_id)')
			->from($db->qn('#__redshop_product_accessory', 'pa'))
			->leftJoin($db->qn('#__redshop_product', 'parent_product') . ' ON parent_product.product_id = pa.child_product_id')
			->where('pa.product_id = p.product_id')
			->where('parent_product.published = 1');

		$query->select('(' . $subQuery . ') AS total_accessories');

		// Count child products
		$subQuery = $db->getQuery(true)
			->select('COUNT(child.product_id) AS count_child_products, child.product_parent_id')
			->from($db->qn('#__redshop_product', 'child'))
			->where('child.product_parent_id > 0')
			->where('child.published = 1')
			->group('child.product_parent_id');

		$query->select('child_product_table.count_child_products')
			->leftJoin('(' . $subQuery . ') AS child_product_table ON child_product_table.product_parent_id = p.product_id');

		// Sum quantity
		if (USE_STOCKROOM == 1)
		{
			$subQuery = $db->getQuery(true)
				->select('SUM(psx.quantity)')
				->from($db->qn('#__redshop_product_stockroom_xref', 'psx'))
				->where('psx.product_id = p.product_id')
				->where('psx.quantity >= 0');

			$query->select('(' . $subQuery . ') AS sum_quanity');
		}

		return $query;
	}

	/**
	 * Set product relates
	 *
	 * @param   array  $products  Products
	 * @param   int    $userId    User id
	 *
	 * @return  void
	 */
	public static function setProductRelates($products, $userId = 0)
	{
		if (!$userId)
		{
			$user = JFactory::getUser();
			$userId = $user->id;
		}

		$keys = array();

		foreach ((array) $products  as $product)
		{
			$keys[] = $product->product_id;
			self::$products[$product->product_id . '.' . $userId]->attributes = array();
			self::$products[$product->product_id . '.' . $userId]->extraFields = array();
		}

		if (count($keys) > 0)
		{
			$db = JFactory::getDbo();
			$query = $db->getQuery(true)
				->select(
					array(
						'a.attribute_id AS value', 'a.attribute_name AS text', 'a.*',
						'ast.attribute_set_name', 'ast.published AS attribute_set_published'
					)
				)
				->from($db->qn('#__redshop_product_attribute', 'a'))
				->leftJoin($db->qn('#__redshop_attribute_set', 'ast') . ' ON ast.attribute_set_id = a.attribute_set_id')
				->where('a.attribute_name != ' . $db->q(''))
				->where('a.attribute_published = 1')
				->where('a.product_id IN (' . implode(',', $keys) . ')')
				->order('a.ordering ASC');
			$db->setQuery($query);

			if ($results = $db->loadObjectList())
			{
				foreach ($results as $result)
				{
					self::$products[$result->product_id . '.' . $userId]->attributes[$result->attribute_id] = $result;
					self::$products[$result->product_id . '.' . $userId]->attributes[$result->attribute_id]->properties = array();
				}

				$query->clear()
					->select(
						array('ap.property_id AS value', 'ap.property_name AS text', 'ap.*', 'a.attribute_name', 'a.attribute_id', 'a.product_id', 'a.attribute_set_id')
					)
					->from($db->qn('#__redshop_product_attribute_property', 'ap'))
					->leftJoin($db->qn('#__redshop_product_attribute', 'a') . ' ON a.attribute_id = ap.attribute_id')
					->where('a.product_id IN (' . implode(',', $keys) . ')')
					->where('ap.property_published = 1')
					->where('a.attribute_published = 1')
					->where('a.attribute_name != ' . $db->q(''))
					->order('ap.ordering ASC');
				$db->setQuery($query);

				if ($results = $db->loadObjectList())
				{
					foreach ($results as $result)
					{
						self::$products[$result->product_id . '.' . $userId]->attributes[$result->attribute_id]->properties[$result->property_id] = $result;
					}
				}
			}

			$query = $db->getQuery(true)
				->select('fd.*, f.field_title')
				->from($db->qn('#__redshop_fields_data', 'fd'))
				->leftJoin($db->qn('#__redshop_fields', 'f') . ' ON fd.fieldid = f.field_id')
				->where('fd.itemid IN (' . implode(',', $keys) . ')')
				->where('fd.section = 1');

			if ($results = $db->setQuery($query)->loadObjectList())
			{
				foreach ($results as $result)
				{
					self::$products[$result->itemid . '.' . $userId]->extraFields[$result->fieldid] = $result;
				}
			}
		}
	}

	/**
	 * Set product array
	 *
	 * @param   array  $products  Array product/s values
	 *
	 * @return void
	 */
	public static function setProduct($products)
	{
		self::$products = $products + self::$products;
		self::setProductRelates($products);
	}
}
