<?php
/**
 * @package     RedSHOP.Frontend
 * @subpackage  mod_redshop_redmanufacturer
 *
 * @copyright   Copyright (C) 2008 - 2015 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

defined('_JEXEC') or die;

JLoader::import('redshop.library');

/**
 * Helper for mod_redmanufacturer
 *
 * @since  1.6.1
 */
abstract class ModRedshopProductsHelper
{
	/**
	 * Retrieve a list of article
	 *
	 * @param   \Joomla\Registry\Registry  &$params  Module parameters
	 *
	 * @return  mixed
	 */
	public static function getList(&$params)
	{
		$app                     = JFactory::getApplication();
		$db                      = JFactory::getDbo();
		$user 					 = JFactory::getUser();
		$type                    = trim($params->get('type', 0));
		$count                   = trim($params->get('count', 5));
		$showFeaturedProduct     = trim($params->get('featured_product', 0));
		$showChildProducts       = trim($params->get('show_childproducts', 1));
		$isUrlCategoryId         = trim($params->get('urlCategoryId', 0));

		$query = $db->getQuery(true)
			->select($db->qn('p.product_id'))
			->from($db->qn('#__redshop_product', 'p'))
			->leftJoin($db->qn('#__redshop_product_category_xref', 'pc') . ' ON ' . $db->qn('pc.product_id') . ' = ' . $db->qn('p.product_id'))
			->where($db->qn('p.published') . ' = 1')
			->group($db->qn('p.product_id'));

		switch ((int) $type)
		{
			// Newest Product
			case 0:
				$query->order($db->qn('p.product_id') . ' DESC');
			break;

			// Latest Product
			case 1:

				$query->leftjoin(
							$db->qn('#__redshop_product_attribute', 'a')
							. ' ON ' . $db->qn('a.product_id') . ' = ' . $db->qn('p.product_id')
						)
					->leftjoin(
							$db->qn('#__redshop_product_attribute_property', 'ap')
							. ' ON ' . $db->qn('a.attribute_id') . ' = ' . $db->qn('ap.attribute_id')
						)
					->order($db->qn('ap.property_id') . ' DESC')
					->order($db->qn('p.product_id') . ' DESC');

			break;

			// Most Sold Product
			case 2:

				$subQuery = $db->getQuery(true)
					->select('SUM(' . $db->qn('oi.product_quantity') . ') AS ' . $db->qn('qty') . ', ' . $db->qn('oi.product_id'))
					->from($db->qn('#__redshop_order_item', 'oi'))
					->group($db->qn('oi.product_id'));
				$query->select($db->qn('orderItems.qty'))
					->leftJoin('(' . $subQuery . ') ' . $db->qn('orderItems') . ' ON ' . $db->qn('orderItems.product_id') . ' = ' . $db->qn('p.product_id') . ')')
					->order($db->qn('orderItems.qty') . ' DESC');

				break;

			// Random Product
			case 3:

				$query->order('rand()');

				break;

			// Product On Sale
			case 4:

				$query->where($db->qn('p.product_on_sale') . '= 1')
					->order($db->qn('p.product_name'));

				break;

			// Product On Sale and discount date check
			case 5:
				$time = time();
				$query->where($db->qn('p.product_on_sale') . ' = 1')
					->where(
						'((' . $db->qn('p.discount_stratdate') . ' = 0 AND ' . $db->qn('p.discount_enddate') . ' = 0) OR (' . $db->qn('p.discount_stratdate') . ' <= '
							. $db->q($time) . ' AND ' . $db->qn('p.discount_enddate') . ' >= ' . $db->q($time) . ') OR (' . $db->qn('p.discount_stratdate') . ' <= '
							. $db->q($time) . ' AND ' . $db->qn('p.discount_enddate') . ' = 0))'
						)
					->order($db->qn('p.product_name'));
				break;
		}

		// Only Display Feature Product
		if ($showFeaturedProduct)
		{
			$query->where($db->qn('p.product_special') . '=1');
		}

		// Show Child Products or Parent Products
		if ($showChildProducts != 1)
		{
			$query->where($db->qn('p.product_parent_id') . '=0');
		}

		$category = $params->get('category', '');

		if (is_array($category))
		{
			$category = implode(',', $category);
		}
		else
		{
			$category = trim($category);
		}

		if ($isUrlCategoryId)
		{
			// Get Category id from menu params if not found in URL
			$urlCategoryId = (int) $app->input->getInt('cid', $app->getParams('com_redshop')->get('cid', ''));

			if ($category)
			{
				$categoryArray = explode(",", $category);
				array_push($categoryArray, $urlCategoryId);
				JArrayHelper::toInteger($categoryArray);

				$category = implode(",", $categoryArray);
			}
			else
			{
				$category = $urlCategoryId;
			}
		}

		// If category is found
		if ($category)
		{
			$query->where($db->qn('pc.category_id') . ' IN (' . $category . ')');
		}
		else
		{
			$query->leftJoin($db->qn('#__redshop_category', 'c') . ' ON ' . $db->qn('c.category_id') . ' = ' . $db->qn('pc.category_id'))
				->where($db->qn('c.published') . ' = 1');
		}

		$rows = array();

		if ($productIds = $db->setQuery($query, 0, $count)->loadColumn())
		{
			// Third steep get all product relate info
			$query->clear()
				->where($db->qn('p.product_id') . ' IN (' . implode(',', $productIds) . ')')
				->order('FIELD(' . $db->qn('p.product_id') . ', ' . implode(',', $productIds) . ')');

			$query = RedshopHelperProduct::getMainProductQuery($query, $user->id)
				->select('CONCAT_WS(' . $db->q('.') . ', ' . $db->qn('p.product_id') . ', ' . $db->q($user->id) . ') AS ' . $db->qn('concat_id'));

			if ($rows = $db->setQuery($query)->loadObjectList('concat_id'))
			{
				RedshopHelperProduct::setProduct($rows);
				$rows = array_values($rows);
			}
		}

		return $rows;
	}
}
