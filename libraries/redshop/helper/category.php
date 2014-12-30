<?php
/**
 * @package     RedSHOP.Library
 * @subpackage  Helper
 *
 * @copyright   Copyright (C) 2005 - 2014 redCOMPONENT.com. All rights reserved.
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
}
