<?php

if (!defined('_JEXEC')) die('Direct Access to ' . basename(__FILE__) . ' is not allowed.');

class Shop_Menu
{
	var $menuObj;
	var $_params = null;
	var $_db = null;
	var $children = null;
	var $open = null;

	function Shop_Menu(&$database, &$params, $shopper_group_id)
	{
		$this->_params = $params;
		$this->_db     = $database;

		$this->loadMenu($shopper_group_id);
		$this->createmenuObj();
	}

	function createmenuObj()
	{
		switch ($this->_params->get('menutype'))
		{
			default:
				include_once "transmenu.php";
				$this->menuObj = new TransMenu($this);
				break;
		}
	}

	function  loadMenu($shopper_group_id)
	{
		global $my, $database, $cur_template, $Itemid, $urlpath;
		$redproduct_menu = new modProMenuHelper;

		if ($this->_params->get('categorysorttype') == "catnameasc")
		{
			$sortparam = "category_name ASC";
		}
		if ($this->_params->get('categorysorttype') == "catnamedesc")
		{
			$sortparam = "category_name DESC";
		}
		if ($this->_params->get('categorysorttype') == "newest")
		{
			$sortparam = "category_id DESC";
		}
		if ($this->_params->get('categorysorttype') == "catorder")
		{
			$sortparam = "ordering ASC";
		}
		if ($shopper_group_id)
		{
			$shoppergroup_cat = $redproduct_menu->get_shoppergroup_cat($shopper_group_id);
		}

		$query = "SELECT category_id as id, category_parent_id as parent, category_name as name, '' as type,
							CONCAT('index.php?option=com_redshop&view=category&layout=detail&cid=', category_id ) AS link,
							'-1' as browserNav, ordering
								FROM #__redshop_category, #__redshop_category_xref
								WHERE #__redshop_category.published='1'
								AND #__redshop_category.category_id=#__redshop_category_xref.category_child_id ";
		if ($shopper_group_id && count($shoppergroup_cat) > 0)
		{
			$query .= " and category_id in (" . $shoppergroup_cat[0] . ")";
		}
		$query .= " ORDER BY " . $sortparam . "";

		$this->_db->setQuery($query);

		$rows = $this->_db->loadObjectList('id');

		// establish the hierarchy of the menu
		$this->children = array();
		// first pass - collect children
		foreach ($rows as $v)
		{
			$pt   = $v->parent;
			$list = @$this->children[$pt] ? $this->children[$pt] : array();
			array_push($list, $v);
			$this->children[$pt] = $list;
		}

		// second pass - collect 'open' menus
		$this->open = array(@$_REQUEST['cid']);
	}

	function genMenu()
	{
		$this->beginMenu();
		$this->menuObj->beginMenu();
		$this->genMenuItems(0, 0);
		$this->menuObj->endMenu();
		$this->endMenu();
	}

	/*
	$pid: parent id
	$level: menu level
	$pos: position of parent
	*/
	function genMenuItems($pid, $level)
	{
		if (@$this->children[$pid])
		{
			$i = 0;
			foreach ($this->children[$pid] as $row)
			{

				$this->menuObj->genMenuItem($row, $level, $i);

				// show menu with menu expanded - submenus visible
				$this->genMenuItems($row->id, $level + 1);
				$i++;
			}
		}

	}

	function beginMenu()
	{
		echo "<!-- Begin menu -->\n";
	}

	function endMenu()
	{
		echo "<!-- End menu -->\n";
	}

	function hasSubItems($id)
	{
		if (@$this->children[$id]) return true;

		return false;
	}
}
?>
