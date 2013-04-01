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

class fieldsModelfields extends JModel
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
		$this->_context = 'field_id';
		$this->_table_prefix = '#__redshop_';
		$limit = $app->getUserStateFromRequest($this->_context . 'limit', 'limit', $app->getCfg('list_limit'), 0);
		$limitstart = $app->getUserStateFromRequest($this->_context . 'limitstart', 'limitstart', 0);
		$filter = $app->getUserStateFromRequest($this->_context . 'filter', 'filter', 0);
		$filtertype = $app->getUserStateFromRequest($this->_context . 'filtertypes', 'filtertypes', 0);
		$filtersection = $app->getUserStateFromRequest($this->_context . 'filtersection', 'filtersection', 0);
		$limitstart = ($limit != 0 ? (floor($limitstart / $limit) * $limit) : 0);

		$this->setState('filter', $filter);
		$this->setState('limit', $limit);
		$this->setState('limitstart', $limitstart);
		$this->setState('filtertype', $filtertype);
		$this->setState('filtersection', $filtersection);
	}

	public function getData()
	{
		if (empty($this->_data))
		{
			$query = $this->_buildQuery();
			$this->_data = $this->_getList($query, $this->getState('limitstart'), $this->getState('limit'));
		}

		return $this->_data;
	}

	public function getTotal()
	{
		if (empty($this->_total))
		{
			$query = $this->_buildQuery();
			$this->_total = $this->_getListCount($query);
		}

		return $this->_total;
	}

	public function getPagination()
	{
		if (empty($this->_pagination))
		{
			jimport('joomla.html.pagination');
			$this->_pagination = new JPagination($this->getTotal(), $this->getState('limitstart'), $this->getState('limit'));
		}

		return $this->_pagination;
	}

	public function _buildQuery()
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

	public function _buildContentOrderBy()
	{
		$app = JFactory::getApplication();

		$filter_order = $app->getUserStateFromRequest($this->_context . 'filter_order', 'filter_order', 'ordering');
		$filter_order_Dir = $app->getUserStateFromRequest($this->_context . 'filter_order_Dir', 'filter_order_Dir', '');

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

	public function saveorder($cid = array(), $order)
	{
		$row =& $this->getTable('fields_detail');
		$groupings = array();
		$conditions = array();

		// Update ordering values
		for ($i = 0; $i < count($cid); $i++)
		{
			$row->load((int) $cid[$i]);

			// Track categories
			$groupings[] = $row->field_id;

			if ($row->ordering != $order[$i])
			{
				$row->ordering = $order[$i];

				if (!$row->store())
				{
					$this->setError($this->_db->getErrorMsg());

					return false;
				}

				// Remember to updateOrder this group
				$condition = 'field_section = ' . (int) $row->field_section;
				$found = false;

				foreach ($conditions as $cond)
				{
					if ($cond[1] == $condition)
					{
						$found = true;
						break;
					}
				}

				if (!$found)
				{
					$conditions[] = array($row->field_id, $condition);
				}
			}
		}

		// Execute updateOrder for each group
		foreach ($conditions as $cond)
		{
			$row->load($cond[0]);
			$row->reorder($cond[1]);
		}

		return true;
	}
}

