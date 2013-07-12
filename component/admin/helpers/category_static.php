<?php
/**
 * @package     RedSHOP.Backend
 * @subpackage  Helper
 *
 * @copyright   Copyright (C) 2005 - 2013 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

defined('_JEXEC') or die;

class StaticCategory
{
	protected static $allCat = null;

	protected static $catlist_reverse = array();

	public static $productInCat = array();

	public static function getAllCat()
	{
		if (!self::$allCat)
		{
			$table_prefix = '#__' . TABLE_PREFIX . '_';
			$db = jFactory::getDBO();
			$query = $db->getQuery(true);
			$query->select('c.category_id, c.sef_url, c.category_name, cx.category_child_id, cx.category_parent_id');
			$query->from($table_prefix . 'category AS c');
			$query->leftJoin($table_prefix . 'category_xref as cx ON cx.category_child_id = c.category_id');
			$query->where('c.published = 1');
			$db->setQuery($query);
			self::$allCat = $db->loadObjectList();
		}

		return self::$allCat;
	}

	public static function getCategoryListReverceArray($cid = '0', $level = 0)
	{
		$AllCat = self::getAllCat();
		$cats = array();

		if ($level == 0)
		{
			self::$catlist_reverse = array();
		}

		$level ++;

		if (count($AllCat))
		{
			foreach ($AllCat as $oneKey)
			{
				if ($oneKey->category_child_id == $cid)
				{
					$cats[] = $oneKey;
				}
			}
		}

		for ($x = 0; $x < count($cats); $x++)
		{
			$cat = $cats[$x];
			$parent_id = $cat->category_parent_id;
			self::$catlist_reverse[] = $cat;
			self::getCategoryListReverceArray($parent_id, $level);
		}

		return self::$catlist_reverse;
	}

	public static function setProductSef($products)
	{
		self::$productInCat = $products;
	}
}
