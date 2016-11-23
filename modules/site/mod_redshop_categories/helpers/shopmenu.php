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
 * Helper for mod_redshop_categories
 *
 * @since  1.5.3
 */
class ShopMenu
{
	/**
	 * [$menuObj description]
	 * @var [type]
	 */
	var $menuObj;

	/**
	 * [$params description]
	 * @var null
	 */
	var $params = null;

	/**
	 * [$db description]
	 * @var null
	 */
	var $db = null;

	/**
	 * [$children description]
	 * @var null
	 */
	var $children = null;

	/**
	 * [$open description]
	 * @var null
	 */
	var $open = null;

	/**
	 * [ShopMenu description]
	 * 
	 * @param   [type]  &$database       [description]
	 * @param   [type]  &$params         [description]
	 * @param   [type]  $shopperGroupId  [description]
	 *
	 * @return   void
	 */
	function ShopMenu(&$database, &$params, $shopperGroupId)
	{
		$this->params =& $params;
		$this->db     =& $database;

		$this->loadMenu($shopperGroupId);
		$this->createmenuObj($params);
	}

	/**
	 * [createmenuObj description]
	 * 
	 * @param   [type]  $params  [description]
	 * 
	 * @return [type] [descriptsion]
	 */
	function createmenuObj($params)
	{
		switch ($this->params->get('menutype'))
		{
			default:
				require JModuleHelper::getLayoutPath('mod_redshop_categories', $params->get('layout', 'transmenu'));
				$this->menuObj = new TransMenu($this);
				break;
		}
	}

	/**
	 * [loadMenu description]
	 * 
	 * @param   [type]  $shopperGroupId  [description]
	 * 
	 * @return  [type]                   [description]
	 */
	function  loadMenu($shopperGroupId)
	{
		$db =& $this->db;
		$query = $db->getQuery(true);

		$query->select(
			[
				$db->qn('c.category_id', 'id'),
				$db->qn('xf.category_parent_id', 'parent'),
				$db->qn('c.category_name', 'name'),
				"'' AS " . $db->qn('type'),
				"CONCAT('index.php?option=com_redshop&view=category&layout=detail&cid=', " . $db->qn('c.category_id') . " ) AS " . $db->qn('link'),
				$db->q('-1') . ' AS ' . $db->qn('browserNav'),
				$db->qn('ordering')
			]
		)
		->from($db->qn('#__redshop_category', 'c'))
		->innerJoin($db->qn('#__redshop_category_xref', 'xf') . ' ON ' . $db->qn('c.category_id') . ' = ' . $db->qn('xf.category_child_id'))
		->where($db->qn('c.published') . ' = ' . $db->q('1'));

		switch ($this->params->get('categorysorttype'))
		{
			case "catnameasc":
				$query->order($db->qn('c.category_name') . ' ASC');
				break;
			case "catnamedesc":
				$query->order($db->qn('c.category_name') . ' DESC');
				break;
			case "newest":
				$query->order($db->qn('c.category_id') . ' DESC');
				break;
			case "catorder":
				$query->order($db->qn('c.ordering') . ' ASC');
				break;
		}

		if ($shopperGroupId)
		{
			$shopperGroupCat = ModProMenuHelper::getShopperGroupCat($shopperGroupId);
		}
		else
		{
			$shopperGroupCat = 0;
		}

		if ($shopperGroupId && $shopperGroupCat)
		{
			$query->where($db->qn('c.category_id') . ' IN (' . $db->q($shopperGroupCat) . ')');
		}

		$this->db->setQuery($query);

		$rows = $this->db->loadObjectList('id');

		// Establish the hierarchy of the menu
		$this->children = array();

		// First pass - collect children
		foreach ($rows as $v)
		{
			$pt   = $v->parent;
			$list = @$this->children[$pt] ? $this->children[$pt] : array();
			array_push($list, $v);
			$this->children[$pt] = $list;
		}

		// Second pass - collect 'open' menus
		$this->open = array(@$_REQUEST['cid']);
	}

	/**
	 * [genMenu description]
	 * 
	 * @return [type] [description]
	 */
	function genMenu()
	{
		$this->beginMenu();
		$this->menuObj->beginMenu();
		$this->genMenuItems(0, 0);
		$this->menuObj->endMenu();
		$this->endMenu();
	}

	/**
	 * [genMenuItems description]
	 * 
	 * @param   [type]  $pid    [description]
	 * @param   [type]  $level  [description]
	 * 
	 * @return  [type]        [description]
	 */
	function genMenuItems($pid, $level)
	{
		if (@$this->children[$pid])
		{
			$i = 0;

			foreach ($this->children[$pid] as $row)
			{
				$this->menuObj->genMenuItem($row, $level, $i);

				// Show menu with menu expanded - submenus visible
				$this->genMenuItems($row->id, $level + 1);
				$i++;
			}
		}
	}

	/**
	 * [beginMenu description]
	 * 
	 * @return [type] [description]
	 */
	function beginMenu()
	{
		echo "<!-- Begin menu -->\n";
	}

	/**
	 * [endMenu description]
	 * 
	 * @return [type] [description]
	 */
	function endMenu()
	{
		echo "<!-- End menu -->\n";
	}

	/**
	 * [hasSubItems description]
	 * 
	 * @param   [type]  $id  [description]
	 * 
	 * @return  boolean      [description]
	 */
	function hasSubItems($id)
	{
		if (@$this->children[$id])
		{
			return true;
		}

		return false;
	}
}
