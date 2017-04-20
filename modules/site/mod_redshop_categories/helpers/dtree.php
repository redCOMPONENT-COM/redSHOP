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
		$db = JFactory::getDbo();
		$query = $db->getQuery(true);

		$query->select($db->qn(['c.category_name', 'c.category_id', 'xf.category_parent_id']))
			->from($db->qn('#__redshop_category', 'c'))
			->leftJoin($db->qn('#__redshop_category_xref', 'xf') . ' ON ' . $db->qn('c.category_id') . ' = ' . $db->qn('xf.category_child_id'))
			->where($db->qn('c.published') . ' = 1');

		switch ($params->get('categorysorttype'))
		{
			case 'catnameasc':
				$query->order($db->qn('c.category_name') . ' ASC');
				break;
			case 'catnamedesc':
				$query->order($db->qn('c.category_name') . ' DESC');
				break;
			case 'newest':
				$query->order($db->qn('c.category_id') . ' DESC');
				break;
			case 'catorder':
				$query->order($db->qn('c.ordering') . ' ASC');
				break;
		}

		if ($shopperGroupId)
		{
			$shoppergroupCat = ModProMenuHelper::getShopperGroupCat($shopperGroupId);
		}
		else
		{
			$shoppergroupCat = 0;
		}

		if ($shopperGroupId && $shoppergroupCat)
		{
			$query->where($db->qn('c.category_id') . ' IN(' . $db->q($shoppergroupCat) . ')');
		}

		$db->setQuery($query);
		$catdatas = $db->loadObjectList();

		return $catdatas;
	}
}
