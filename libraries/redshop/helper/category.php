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
 * Class Redshop Helper Category
 *
 * @since  1.5
 */
class RedshopHelperCategory
{
	protected static $categoriesData = array();

	protected static $categoryListReverse = array();

	protected static $categoryChildListReverse = array();

	/**
	 * Get category data
	 *
	 * @param   int  $cid  Category id
	 *
	 * @return mixed
	 */
	public static function getCategoryById($cid)
	{
		if (!$cid)
		{
			return null;
		}

		if (!array_key_exists($cid, self::$categoriesData))
		{
			$db = JFactory::getDbo();
			$query = $db->getQuery(true)
				->select(array('c.*', 'cx.category_parent_id'))
				->from($db->qn('#__redshop_category', 'c'))
				->leftJoin($db->qn('#__redshop_category_xref', 'cx') . ' ON cx.category_child_id = c.category_id')
				->where('c.category_id = ' . (int) $cid)
				->group('c.category_id');
			self::$categoriesData[$cid] = $db->setQuery($query)->loadObject();
		}

		return self::$categoriesData[$cid];
	}

	/**
	 * Get Category List Reverse Array
	 *
	 * @param   string  $cid  Category id
	 *
	 * @return array
	 */
	public static function getCategoryListReverseArray($cid = '0')
	{
		self::$categoryListReverse = array();

		if ($category = self::getCategoryById($cid))
		{
			if (isset($category->category_parent_id))
			{
				self::getCategoryListRecursion($category->category_parent_id);
			}
		}

		return self::$categoryListReverse;
	}

	/**
	 * Get Category List Recursion
	 *
	 * @param   string  $cid  Category id
	 *
	 * @return void
	 */
	private static function getCategoryListRecursion($cid = '0')
	{
		if ($category = self::getCategoryById($cid))
		{
			if (isset($category->category_parent_id))
			{
				self::$categoryListReverse[] = $category;
				self::getCategoryListRecursion($category->category_parent_id);
			}
		}
	}

	/**
	 * Get Category List Array
	 *
	 * @param   int  $categoryId  First category level in filter
	 * @param   int  $cid         Current category id
	 *
	 * @return   array|mixed
	 */
	public static function getCategoryListArray($categoryId = 0, $cid = 0)
	{
		global $context;
		$app = JFactory::getApplication();
		$db = JFactory::getDbo();
		$view = $app->input->get('view', '');
		$categoryMainFilter = $app->getUserStateFromRequest($context . 'category_main_filter', 'category_main_filter', 0);

		if ($categoryId)
		{
			$cid = $categoryId;
		}

		$key = $context . '_' . $view . '_' . $categoryMainFilter . '_' . $cid;

		if (array_key_exists($key, self::$categoryChildListReverse))
		{
			return self::$categoryChildListReverse[$key];
		}

		$query = $db->getQuery(true)
			->select('c.category_id, cx.category_child_id, cx.category_parent_id, c.category_name, c.category_description, c.published, c.ordering')
			->from($db->qn('#__redshop_category', 'c'))
			->leftJoin($db->qn('#__redshop_category_xref', 'cx') . ' ON c.category_id = cx.category_child_id');

		if ($view == 'category')
		{
			$filter_order = urldecode($app->getUserStateFromRequest($context . 'filter_order', 'filter_order', 'c.ordering'));
			$filter_order_Dir = urldecode($app->getUserStateFromRequest($context . 'filter_order_Dir', 'filter_order_Dir', ''));
			$query->order($db->escape($filter_order . ' ' . $filter_order_Dir));
		}
		else
		{
			$query->order('c.category_name');
		}

		if ($categoryMainFilter)
		{
			$query->where('c.category_name LIKE ' . $db->quote('%' . $categoryMainFilter . '%'));
		}
		else
		{
			$query->where('cx.category_parent_id = ' . (int) $cid);
		}

		self::$categoryChildListReverse[$key] = null;

		if ($cats = $db->setQuery($query)->loadObjectList())
		{
			if ($categoryMainFilter)
			{
				self::$categoryChildListReverse[$key] = $cats;

				return $cats;
			}

			self::$categoryChildListReverse[$key] = array();

			foreach ($cats as $cat)
			{
				$cat->category_name = '- ' . $cat->category_name;
				self::$categoryChildListReverse[$key][] = $cat;
				self::getCategoryChildListRecursion($key, $cat->category_child_id);
			}
		}

		return self::$categoryChildListReverse[$key];
	}

	/**
	 * Get Category Child List Recursion
	 *
	 * @param   string  $key    Key in array Child List
	 * @param   int     $cid    Category id
	 * @param   int     $level  Level current category
	 *
	 * @return  void
	 */
	protected static function getCategoryChildListRecursion($key, $cid, $level = 1)
	{
		$db = JFactory::getDbo();
		$query = $db->getQuery(true)
			->select('c.category_id, cx.category_child_id, cx.category_parent_id, c.category_name, c.category_description, c.published, c.ordering')
			->from($db->qn('#__redshop_category', 'c'))
			->leftJoin($db->qn('#__redshop_category_xref', 'cx') . ' ON c.category_id = cx.category_child_id')
			->where('cx.category_parent_id = ' . (int) $cid);
		$level++;

		if ($cats = $db->setQuery($query)->loadObjectList())
		{
			foreach ($cats as $cat)
			{
				$cat->category_name = str_repeat('- ', $level) . $cat->category_name;
				self::$categoryChildListReverse[$key][] = $cat;
				self::getCategoryChildListRecursion($key, $cat->category_child_id, $level);
			}
		}
	}
}
