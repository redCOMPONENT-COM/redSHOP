<?php
/**
 * @package     RedShop
 * @subpackage  Libraries
 *
 * @copyright   Copyright (C) 2008 - 2017 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

namespace Redshop\Helper;

defined('_JEXEC') or die;

class Categories
{
	public static function getCategories()
	{
		$db    = \JFactory::getDbo();
		$query = $db->getQuery(true)
			->select($db->qn(array('id', 'parent_id', 'level')))
			->select($db->qn('name', 'title'))
			->from($db->qn('#__redshop_category'))
			->where($db->qn('published') . ' = 1')
			->where($db->qn('level') . ' > 0')
			->order($db->qn('lft'));

		$rows = $db->setQuery($query)->loadObjectList();

		// Establish the hierarchy of the menu
		$children = array();

		// First pass - collect children
		foreach ($rows as $v)
		{
			$pt   = $v->parent_id;
			$list = @$children[$pt] ? $children[$pt] : array();
			array_push($list, $v);
			$children[$pt] = $list;
		}

		// Get first key to generate tree recursive
		$firstKey = current(array_keys($children));

		// Second pass - get an indent list of the items
		$list = self::treerecurse($firstKey, '- ', array(), $children);

		if (!empty($list))
		{
			return $list;
		}

		return array();
	}

	protected static function treerecurse($id, $indent, $list, &$children, $maxlevel = 9999, $level = 0)
	{
		if (@$children[$id] && $level <= $maxlevel)
		{
			foreach ($children[$id] as $v)
			{
				$id = $v->id;

				if ($v->parent_id == 0)
				{
					$txt = $v->title;
				}
				else
				{
					$txt = str_repeat($indent, $v->level) . $v->title;
				}

				$list[$id]           = $v;
				$list[$id]->treename = $txt;
				$list[$id]->children = count(@$children[$id]);
				$list                = self::treerecurse($id, $indent, $list, $children, $maxlevel, $level + 1);
			}
		}

		return $list;
	}
}