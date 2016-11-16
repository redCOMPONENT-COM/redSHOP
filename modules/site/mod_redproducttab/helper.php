<?php
/**
 * @package     RedSHOP.module
 * @subpackage  mod_redfeaturedproduct
 *
 * @copyright   Copyright (C) 2008 - 2016 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

defined('_JEXEC') or die;

/**
 * Helper for mod_redproducttab
 *
 * @since  1.5
 */
abstract class ModRedProductTabHelper
{
	public static $categories = array();

	/**
	 * Get categories
	 *
	 * @param   JRegistry  &$params   Module parameters
	 * @param   int        $moduleId  Id Current module
	 *
	 * @return  null|string
	 */
	public static function getCategories(&$params, $moduleId = 0)
	{
		if (!array_key_exists($moduleId, self::$categories))
		{
			if ($params->get('adjust_category', 1) == 0)
			{
				$category = $params->get('category', 0);

				if (is_array($category))
				{
					self::$categories[$moduleId] = implode(',', $category);
				}
				else
				{
					self::$categories[$moduleId] = trim($category);
				}
			}
			else
			{
				$input = JFactory::getApplication()->input;
				$cid = $input->getInt('cid', 0);
				$catIds = array($cid);

				if ($input->getCmd('option', '') == 'com_redshop' && $input->getCmd('view', '') == 'category' && $cid)
				{
					if ($categories = RedshopHelperCategory::getCategoryListArray('', $cid))
					{
						foreach ($categories as $oneCategory)
						{
							$catIds[] = $oneCategory->category_id;
						}
					}
				}

				self::$categories[$moduleId] = implode(',', $catIds);
			}
		}

		return self::$categories[$moduleId];
	}

	/**
	 * Get Newest Product List
	 *
	 * @param   JRegistry  &$params   Module parameters
	 * @param   string     $type      Type query selection
	 * @param   int        $moduleId  Id Current module
	 *
	 * @return  mixed
	 *
	 * @since   1.5
	 */
	public static function getList(&$params, $type = '', $moduleId = 0)
	{
		$db = JFactory::getDbo();
		$query = $db->getQuery(true)
			->select($db->qn('p.product_id'))
			->from($db->qn('#__redshop_product', 'p'))
			->where($db->qn('p.published') . ' = 1')
			->group($db->qn('p.product_id'));

		if ($categories = self::getCategories($params, $moduleId))
		{
			$query->leftJoin(
						$db->qn('#__redshop_product_category_xref', 'cpx')
						. ' ON ' . $db->qn('cpx.product_id') . ' = ' . $db->qn('p.product_id')
					)
				->leftJoin(
						$db->qn('#__redshop_category', 'c')
						. ' ON ' . $db->qn('c.category_id') . ' = ' . $db->qn('cpx.category_id')
					)
				->where($db->qn('c.published') . ' = 1')
				->where($db->qn('cpx.category_id') . ' IN (' . $db->q($categories) . ')');
		}

		switch ($type)
		{
			case 'special':
				$query->where($db->qn('p.product_special') . ' = 1')
					->order($db->qn('publish_date') . ' DESC');
				break;
			case 'most_sold':
				$subQuery = $db->getQuery(true)
					->select('SUM(' . $db->qn('oi.product_quantity') . ') AS ' . $db->qn('qty') . ', ' . $db->qn('oi.product_id'))
					->from($db->qn('#__redshop_order_item', 'oi'))
					->group($db->qn('oi.product_id'));
				$query->leftJoin(
						'(' . $subQuery . ') ' . $db->qn('orderItems')
						. ' ON ' . $db->qn('orderItems.product_id') . ' = ' . $db->qn('p.product_id')
					)
					->order($db->qn('orderItems.qty') . ' DESC');
				break;
			case 'latest':
				$query->order($db->qn('p.product_id') . ' DESC');
				break;
			case 'newest':
			default:
				$query->order($db->qn('p.publish_date') . ' DESC');
				break;
		}

		$rows = array();

		if ($productIds = $db->setQuery($query, 0, (int) $params->get('count', 1))->loadColumn())
		{
			// Third steep get all product relate info
			$query->clear()
				->where($db->qn('p.product_id') . ' IN (' . implode(',', $db->q($productIds)) . ')')
				->order('FIELD(' . $db->qn('p.product_id') . ', ' . implode(',', $db->q($productIds)) . ')');

			$user = JFactory::getUser();
			$query = RedshopHelperProduct::getMainProductQuery($query, $user->id)
				->select('CONCAT_WS(' . $db->q('.') . ', ' . $db->qn('p.product_id') . ', ' . (int) $user->id . ') AS ' . $db->qn('concat_id'));

			if ($rows = $db->setQuery($query)->loadObjectList('concat_id'))
			{
				RedshopHelperProduct::setProduct($rows);
				$rows = array_values($rows);
			}
		}

		return $rows;
	}
}
