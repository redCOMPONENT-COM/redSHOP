<?php
/**
 * @package     RedSHOP.Backend
 * @subpackage  Model
 *
 * @copyright   Copyright (C) 2005 - 2013 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

defined('_JEXEC') or die;

jimport('joomla.application.component.model');

class categoryModelcategory extends JModel
{
	public $_data = null;

	public $_total = null;

	public $_pagination = null;

	public $_table_prefix = null;

	public $_context = null;

	public function __construct()
	{
		parent::__construct();
		$app = JFactory::getApplication();

		$this->_context = 'category_id';
		$this->_table_prefix = '#__' . TABLE_PREFIX . '_';
		$limit = $app->getUserStateFromRequest($this->_context . 'limit', 'limit', $app->getCfg('list_limit'), 0);
		$limitstart = $app->getUserStateFromRequest($this->_context . 'limitstart', 'limitstart', 0);
		$category_main_filter = $app->getUserStateFromRequest($this->_context . 'category_main_filter', 'category_main_filter', 0);
		$category_id = $app->getUserStateFromRequest($this->_context . 'category_id', 'category_id', 0);

		$this->setState('category_main_filter', $category_main_filter);
		$this->setState('limit', $limit);
		$this->setState('limitstart', $limitstart);
		$this->setState('category_id', $category_id);
	}

	public function getData()
	{
		if (empty($this->_data))
		{
			$this->_data = $this->_buildQuery();
		}
		return $this->_data;
	}

	public function getPagination()
	{
		if ($this->_pagination == null)
		{
			$this->_buildQuery();
		}
		return $this->_pagination;
	}

	public function _buildQuery()
	{
		$app = JFactory::getApplication();
		$view = JRequest::getVar('view');
		$db = JFactory::getDBO();

		$category_id = $this->getState('category_id');
		$category_main_filter = $this->getState('category_main_filter');
		$limit = $this->getState('limit');
		$limitstart = $this->getState('limitstart');

		$orderby = $this->_buildContentOrderBy();
		$and = "";

		if ($category_main_filter)
		{
			$and .= " AND category_name like '%" . $category_main_filter . "%' ";
		}
		if ($category_id != 0)
		{
		}
		$q = "SELECT c.category_id, cx.category_child_id, cx.category_child_id AS id, cx.category_parent_id,
		cx.category_parent_id AS parent_id,c.category_name, c.category_name AS title,c.category_description,c.published,ordering "
			. "FROM " . $this->_table_prefix . "category AS c, " . $this->_table_prefix . "category_xref AS cx "
			. "WHERE c.category_id=cx.category_child_id "
			. $and
			. $orderby;

		$db->setQuery($q);
		$rows = $db->loadObjectList();

		if (!$category_main_filter)
		{
			// Establish the hierarchy of the menu
			$children = array();

			// First pass - collect children
			foreach ($rows as $v)
			{
				$pt = $v->parent_id;
				$list = @$children[$pt] ? $children[$pt] : array();
				array_push($list, $v);
				$children[$pt] = $list;
			}

			// Second pass - get an indent list of the items
			$treelist = JHTML::_('menu.treerecurse', $category_id, '', array(), $children, 9999);

			$total = count($treelist);
		}
		else
		{
			$total = count($rows);
			$treelist = $rows;
		}

		jimport('joomla.html.pagination');
		$this->_pagination = new JPagination($total, $limitstart, $limit);

		// Slice out elements based on limits
		$items = array_slice($treelist, $this->_pagination->limitstart, $this->_pagination->limit);

		return $items;
	}

	public function _buildContentOrderBy()
	{
		$app = JFactory::getApplication();

		$filter_order = $app->getUserStateFromRequest($this->_context . 'filter_order', 'filter_order', 'c.ordering');
		$filter_order_Dir = $app->getUserStateFromRequest($this->_context . 'filter_order_Dir', 'filter_order_Dir', '');

		$orderby = ' ORDER BY ' . $filter_order . ' ' . $filter_order_Dir;

		return $orderby;
	}

	public function getProducts($cid)
	{
		$query = 'SELECT count(category_id) FROM ' . $this->_table_prefix . 'product_category_xref WHERE category_id="' . $cid . '" ';
		$this->_db->setQuery($query);

		return $this->_db->loadResult();
	}

	/*
	 * assign template to multiple categories
	 * @prams: $data, post variable	array
	 * @return: boolean
	 */
	public function assignTemplate($data)
	{
		$cid = $data['cid'];

		$category_template = $data['category_template'];

		if (count($cid))
		{
			$cids = implode(',', $cid);
			$query = 'UPDATE ' . $this->_table_prefix . 'category'
				. ' SET `category_template` = "' . intval($category_template) . '" '
				. ' WHERE category_id IN ( ' . $cids . ' )';
			$this->_db->setQuery($query);

			if (!$this->_db->query())
			{
				$this->setError($this->_db->getErrorMsg());

				return false;
			}
		}

		return true;
	}

	public function saveorder($cid = array(), $order)
	{
		$row =& $this->getTable('category_detail');
		$groupings = array();

		// Update ordering values
		for ($i = 0; $i < count($cid); $i++)
		{
			$row->load((int) $cid[$i]);

			// Track categories
			$groupings[] = $row->category_id;

			if ($row->ordering != $order[$i])
			{
				$row->ordering = $order[$i];

				if (!$row->store())
				{
					$this->setError($this->_db->getErrorMsg());

					return false;
				}
			}
		}

		return true;
	}
}
