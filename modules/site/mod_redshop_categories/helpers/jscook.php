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
abstract class RedshopJscookCategoryMenuHelper
{
	/**
	 * traverseTreeDown description
	 * 
	 * @param   [type]  &$mymenuContent  [description]
	 * @param   string  $categoryId      [description]
	 * @param   string  $level           [description]
	 * @param   string  $params          [description]
	 * @param   [type]  $shopperGroupId  [description]
	 * @param   string  $iconName        icon's name
	 * 
	 * @return  [type]                  [description]
	 */
	public static function traverseTreeDown(&$mymenuContent, $categoryId = '0', $level = '0', $params = '', $shopperGroupId = '0', $iconName = "categories.png")
	{
		static $ibg = 0;

		$uri = JURI::getInstance();
		$urlpath = $uri->root();
		$jscookStyle = $params->get('jscook_style', 'ThemeOffice');
		$liveModulePath     = $urlpath . 'modules/mod_redshop_categories';

		$db = JFactory::getDbo();
		$query = $db->getQuery(true);

		$query->select($db->qn(['c.category_name', 'c.category_id', 'xf.category_child_id']))
			->from($db->qn('#__redshop_category', 'c'))
			->leftJoin($db->qn('#__redshop_category_xref', 'xf') . ' ON ' . $db->qn('c.category_id') . ' = ' . $db->qn('xf.category_child_id'))
			->where($db->qn('c.published') . ' = 1')
			->where($db->qn('xf.category_parent_id') . ' = ' . $db->q((int) $categoryId));

		$level++;

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
			$shoppergroupCat = ModProMenuHelper::get_shoppergroup_cat($shopperGroupId);
		}
		else
		{
			$shoppergroupCat = 0;
		}

		if ($shopperGroupId && $shoppergroup_cat)
		{
			$query->where($db->qn('c.category_id') . ' IN(' . $db->q($shoppergroupCat) . ')');
		}

		$db->setQuery($query);
		$traverseResults = $db->loadObjectList();
		$objhelper        = redhelper::getInstance();
		$Itemid           = JRequest::getInt('Itemid');

		foreach ($traverseResults as $traverseResult)
		{
			$cItemid = $objhelper->getCategoryItemid($traverseResult->category_id);

			if ($cItemid != "")
			{
				$tmpItemid = $cItemid;
			}
			else
			{
				$tmpItemid = $Itemid;
			}

			if ($ibg != 0)
				$mymenuContent .= ",";

			$mymenuContent .= "\n[ '<img src=\"" . JURI::root() . "media/mod_redshop_categories/$jscookStyle/$iconName\" alt=\"arr\" />','" . $traverseResult->category_name . "','" . JRoute::_('index.php?option=com_redshop&view=category&layout=detail&cid=' . $traverseResult->category_id . '&Itemid=' . $tmpItemid) . "',null,'" . $traverseResult->category_name . "'\n ";

			$ibg++;

			/* recurse through the subcategories */
			self::traverseTreeDown($mymenuContent, $traverseResult->category_child_id, $level, $params, $shopperGroupId);

			/* let's see if the loop has reached its end */
			$mymenuContent .= "]";
		}
	}
}
