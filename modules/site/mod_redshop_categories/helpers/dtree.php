<?php
/**
 * @package     RedSHOP.Frontend
 * @subpackage  mod_redshop_categories
 *
 * @copyright   Copyright (C) 2008 - 2015 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

defined('_JEXEC') or die;

/**
 * Helper for mod_articles_latest
 *
 * @since  1.5.3
 */

abstract class ModDtreeMenuHelper
{
	/**
	 * traverseTreeDown description
	 * 
	 * @param   [type]  &$mymenuContent  [description]
	 * @param   string  $categoryId      [description]
	 * @param   string  $level           [description]
	 * @param   string  $params          [description]
	 * @param   [type]  $shopperGroupId  [description]
	 * 
	 * @return  [type]                  [description]
	 */
	public static function traverseTreeDown(&$mymenuContent, $categoryId = '0', $level = '0', $params = '', $shopperGroupId = '0')
	{
		$db              = JFactory::getDbo();

		if ($params->get('categorysorttype') == "catnameasc")
		{
			$sortparam = "category_name ASC";
		}

		if ($params->get('categorysorttype') == "catnamedesc")
		{
			$sortparam = "category_name DESC";
		}

		if ($params->get('categorysorttype') == "newest")
		{
			$sortparam = "category_id DESC";
		}

		if ($params->get('categorysorttype') == "catorder")
		{
			$sortparam = "ordering ASC";
		}

		if ($shopperGroupId)
		{
			$shopperGroupCat = ModProMenuHelper::getShoppergroupCat($shopperGroupId);
		}
		else
		{
			$shopperGroupCat = 0;
		}

		// Select menu items from database
		$query = "SELECT category_id,category_parent_id,category_name FROM #__redshop_category AS c "
			. "LEFT JOIN #__redshop_category_xref AS cx ON c.category_id=cx.category_child_id "
			. "WHERE c.published=1 ";

		if ($shopperGroupId && $shopperGroupCat)
		{
			$query .= " and category_id IN(" . $shopperGroupCat . ")";
		}

		$query .= " ORDER BY " . $sortparam . "";
		$db->setQuery($query);
		$catdatas = $db->loadObjectList();

		return $catdatas;
	}
}
