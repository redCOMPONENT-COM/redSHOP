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

abstract class RedTigraTreeMenuHelper
{
	/**
	 * traverseTreeDown
	 * 
	 * @param   html     &$myMenuContent  content HTML
	 * @param   string   $categoryId      Id of category
	 * @param   string   $level           Tree level
	 * @param   integer  $shopperGroupId  Id of shopper group
	 * 
	 * @return  void
	 */
	public static function traverseTreeDown(&$myMenuContent, $categoryId = '0', $level = '0', $shopperGroupId = 0)
	{
		static $ibg = 0;
		global $itemId, $sortType;

		$db        = JFactory::getDbo();
		$objhelper = redhelper::getInstance();
		$Itemid    = JRequest::getInt('Itemid');
		$level++;

		if ($shopperGroupId)
		{
			$shopperGroupCat = ModProMenuHelper::getShopperGroupCat($shopperGroupId);
		}
		else
		{
			$shopperGroupCat = 0;
		}

		$query = $db->getQuery(true);

		$query->select(
				[
					$db->qn('category_name', 'cname'),
					$db->qn('category_id', 'cid'),
					$db->qn('category_child_id', 'ccid')
				]
			)
			->from($db->qn('#__redshop_category', 'c'))
			->leftJoin($db->qn('#__redshop_category_xref', 'xf') . ' ON ' . $db->qn('c.category_id') . ' = ' . $db->qn('xf.category_child_id'))
			->where($db->qn('c.published') . ' = ' . $db->q('1'))
			->where($db->qn('xf.category_parent_id') . ' = ' . $db->q($categoryId));

		if ($shopperGroupId && $shopperGroupCat)
		{
			/*$query .= " and category_id in (" . $shopperGroupCat . ")";*/
			$query->where($db->qn('c.category_id') . ' IN (' . $db->q($shopperGroupCat) . ')');
		}

		switch ($sortType)
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
			default:
				$query->order($db->qn('c.ordering') . ' ASC');
				break;
		}

		$db->setQuery($query);
		$categories = $db->loadObjectList();

		if (!($categories == null))
		{
			$i = 1;

			foreach ($categories as $category)
			{
				$ibg++;
				$Treeid  = $ibg;
				$cItemid = $objhelper->getCategoryItemid($category->cid);

				if ($cItemid != "")
				{
					$tmpItemid = $cItemid;
				}
				else
				{
					$tmpItemid = $Itemid;
				}

				$myMenuContent .= str_repeat("\t", $level - 1);

				if ($level > 1 && $i == 1)
				{
					$myMenuContent .= ",";
				}

				$myMenuContent .= "['" . $category->cname;

				$myMenuContent .= "','href=\'" . JRoute::_('index.php?option=com_redshop&view=category&layout=detail&cid=' . $category->cid . '&Treeid=' . $Treeid . '&Itemid=' . $tmpItemid) . "\''\n ";

				/* recurse through the subcategories */
				self::traverseTreeDown($myMenuContent, $category->ccid, $level, $shopperGroupId);
				$myMenuContent .= str_repeat("\t", $level - 1);

				/* let's see if the loop has reached its end */
				if ($i == sizeof($categories) && $level == 1)
				{
					$myMenuContent .= "]\n";
				}
				else
				{
					$myMenuContent .= "],\n";
				}

				$i++;
			}
		}
	}
}
