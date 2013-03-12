<?php
/**
 * @package     RedSHOP.Backend
 * @subpackage  Model
 *
 * @copyright   Copyright (C) 2005 - 2013 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

defined('_JEXEC') or die('Restricted access');

jimport('joomla.application.component.model');

class fieldsModelfields extends JModel
{
	var $_data = null;
	var $_total = null;
	var $_pagination = null;
	var $_table_prefix = null;
	var $_context = null;

	function __construct()
	{
		parent::__construct();

		global $mainframe;
		$this->_context = 'field_id';
		$this->_table_prefix = '#__redshop_';
		$limit = $mainframe->getUserStateFromRequest($this->_context . 'limit', 'limit', $mainframe->getCfg('list_limit'), 0);
		$limitstart = $mainframe->getUserStateFromRequest($this->_context . 'limitstart', 'limitstart', 0);
		$filter = $mainframe->getUserStateFromRequest($this->_context . 'filter', 'filter', 0);
		$filtertype = $mainframe->getUserStateFromRequest($this->_context . 'filtertypes', 'filtertypes', 0);
		$filtersection = $mainframe->getUserStateFromRequest($this->_context . 'filtersection', 'filtersection', 0);
		$limitstart = ($limit != 0 ? (floor($limitstart / $limit) * $limit) : 0);

		$this->setState('filter', $filter);
		$this->setState('limit', $limit);
		$this->setState('limitstart', $limitstart);
		$this->setState('filtertype', $filtertype);
		$this->setState('filtersection', $filtersection);
	}

	function getData()
	{
		if (empty($this->_data))
		{
			$query = $this->_buildQuery();
			$this->_data = $this->_getList($query, $this->getState('limitstart'), $this->getState('limit'));
		}
		return $this->_data;
	}

	function getTotal()
	{
		if (empty($this->_total))
		{
			$query = $this->_buildQuery();
			$this->_total = $this->_getListCount($query);
		}
		return $this->_total;
	}

	function getPagination()
	{
		if (empty($this->_pagination))
		{
			jimport('joomla.html.pagination');
			$this->_pagination = new JPagination($this->getTotal(), $this->getState('limitstart'), $this->getState('limit'));
		}
		return $this->_pagination;
	}

	function _buildQuery()
	{
		$orderby = $this->_buildContentOrderBy();
		$filter = $this->getState('filter');
		$filtertype = $this->getState('filtertype');
		$filtersection = $this->getState('filtersection');

		$where = '';
		if ($filter)
		{
			$where .= " AND f.field_title like '%" . $filter . "%' ";
		}
		if ($filtertype)
		{
			$where .= " AND f.field_type='" . $filtertype . "' ";
		}
		if ($filtersection)
		{
			$where .= " AND f.field_section='" . $filtersection . "' ";
		}
		$query = "SELECT * FROM " . $this->_table_prefix . "fields AS f "
			. "WHERE 1=1 "
			. $where
			. $orderby;
		return $query;
	}

	function _buildContentOrderBy()
	{
		global $mainframe;

		$filter_order = $mainframe->getUserStateFromRequest($this->_context . 'filter_order', 'filter_order', 'ordering');
		$filter_order_Dir = $mainframe->getUserStateFromRequest($this->_context . 'filter_order_Dir', 'filter_order_Dir', '');

		if ($filter_order == 'ordering')
		{
			$orderby = ' ORDER BY field_section, ordering ' . $filter_order_Dir;
		}
		else
		{
			$orderby = ' ORDER BY ' . $filter_order . ' ' . $filter_order_Dir . ', field_section, ordering';
		}

		return $orderby;
	}

	function saveorder($cid = array(), $order)
	{
		$row =& $this->getTable('fields_detail');
		$groupings = array();
		$conditions = array();

		// update ordering values
		for ($i = 0; $i < count($cid); $i++)
		{
			$row->load((int) $cid[$i]);
			// track categories
			$groupings[] = $row->field_id;

			if ($row->ordering != $order[$i])
			{
				$row->ordering = $order[$i];
				if (!$row->store())
				{
					$this->setError($this->_db->getErrorMsg());
					return false;
				}
				// remember to updateOrder this group
				$condition = 'field_section = ' . (int) $row->field_section;
				$found = false;
				foreach ($conditions as $cond)
					if ($cond[1] == $condition)
					{
						$found = true;
						break;
					}
				if (!$found)
					$conditions[] = array($row->field_id, $condition);
			}
		}
		// execute updateOrder for each group
		foreach ($conditions as $cond)
		{
			$row->load($cond[0]);
			$row->reorder($cond[1]);
		}
//		// execute updateOrder for each parent group
//		$groupings = array_unique( $groupings );
//		foreach ($groupings as $group){
//			$row->reorder((int) $group);
//		}
		return true;
	}

}

