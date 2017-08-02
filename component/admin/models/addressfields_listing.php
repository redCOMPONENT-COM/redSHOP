<?php
/**
 * @package     RedSHOP.Backend
 * @subpackage  Model
 *
 * @copyright   Copyright (C) 2008 - 2017 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

defined('_JEXEC') or die;


class RedshopModelAddressfields_listing extends RedshopModel
{
	public $_context = null;

	public $_data = null;

	public $_total = null;

	public $_pagination = null;

	public $_table_prefix = null;

	public function __construct()
	{
		parent::__construct();

		$app = JFactory::getApplication();
		$this->_context = 'ordering';
		$this->_table_prefix = '#__redshop_';
		$limit = $app->getUserStateFromRequest($this->_context . 'limit', 'limit', $app->getCfg('list_limit'), 0);
		$limitstart = $app->getUserStateFromRequest($this->_context . 'limitstart', 'limitstart', 0);
		$field_section_drop = $app->getUserStateFromRequest($this->_context . 'section_id', 'section_id', 0);
		$limitstart = ($limit != 0 ? (floor($limitstart / $limit) * $limit) : 0);
		$this->setState('section_id', $field_section_drop);
		$this->setState('limit', $limit);
		$this->setState('limitstart', $limitstart);
	}

	public function getData()
	{
		if (empty($this->_data))
		{
			$query = $this->_buildQuery();
			$this->_data = $this->_getList($query);
		}

		return $this->_data;
	}

	public function getTotal()
	{
		$query = $this->_buildQuerycount();

		if (empty($this->_total))
		{
			$this->_db->setQuery($query);
			$this->_total = $this->_db->loadResult();
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

	public function _buildQuerycount()
	{
		$filter = $this->getState('section_id');
		$where = '';

		if ($filter)
		{
			$where = " WHERE section = '" . $filter . "'";
		}

		if ($where == '')
		{
			$query = "SELECT count(*)  FROM " . $this->_table_prefix . "fields f WHERE 1=1";
		}
		else
		{
			$query = " SELECT count(*)  FROM " . $this->_table_prefix . "fields f" . $where;
		}

		return $query;
	}


	public function _buildQuery()
	{
		$filter = $this->getState('section_id');
		$orderby = $this->_buildContentOrderBy();
		$where = '';
		$limit = "";

		if ($this->getState('limit') > 0)
		{
			$limit = " LIMIT " . $this->getState('limitstart') . "," . $this->getState('limit');
		}

		if ($filter)
		{
			$where = " WHERE section = '" . $filter . "'";
		}

		if ($where == '')
		{
			$query = "SELECT distinct(f.id),f.*  FROM " . $this->_table_prefix . "fields f WHERE 1=1" . $orderby . $limit;
		}
		else
		{
			$query = " SELECT distinct(f.id),f.*  FROM " . $this->_table_prefix . "fields f" . $where . $orderby . $limit;
		}

		return $query;
	}

	public function _buildContentOrderBy()
	{
		$db  = JFactory::getDbo();
		$app = JFactory::getApplication();

		$filter_order = $app->getUserStateFromRequest($this->_context . 'filter_order', 'filter_order', 'ordering');
		$filter_order_Dir = $app->getUserStateFromRequest($this->_context . 'filter_order_Dir', 'filter_order_Dir', '');

		if ($filter_order == 'ordering')
		{
			$orderby = ' ORDER BY section, ordering ' . $filter_order_Dir;
		}
		else
		{
			$orderby = ' ORDER BY ' . $db->escape($filter_order . ' ' . $filter_order_Dir) . ', section, ordering';
		}

		return $orderby;
	}

	public function saveorder($cid = array(), $order)
	{
		$row = $this->getTable("field");
		$groupings = array();
		$conditions = array();

		// Update ordering values
		for ($i = 0, $in = count($cid); $i < $in; $i++)
		{
			$row->load((int) $cid[$i]);

			// Track categories
			$groupings[] = $row->id;

			if ($row->ordering != $order[$i])
			{
				$row->ordering = $order[$i];

				if (!$row->store())
				{
					$this->setError($this->_db->getErrorMsg());

					return false;
				}

				// Remember to updateOrder this group
				$condition = 'section = ' . (int) $row->section;
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
					$conditions[] = array($row->id, $condition);
				}
			}
		}

		foreach ($conditions as $cond)
		{
			$row->load($cond[0]);
			$row->reorder($cond[1]);
		}

		return true;
	}

	public function MaxOrdering()
	{
		$query = "SELECT (count(*)+1) FROM " . $this->_table_prefix . "fields";
		$this->_db->setQuery($query);

		return $this->_db->loadResult();
	}

	/**
	 * Method to move
	 *
	 * @access  public
	 * @return  boolean True on success
	 * @since 0.9
	 */
	public function move($direction, $field_id)
	{
		$row = $this->getTable("field");

		if (!$row->load($field_id))
		{
			$this->setError($this->_db->getErrorMsg());

			return false;
		}

		if (!$row->move($direction, 'section = ' . (int) $row->section))
		{
			$this->setError($this->_db->getErrorMsg());

			return false;
		}

		return true;
	}
}
