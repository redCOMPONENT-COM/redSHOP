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

class RedshopModelFields extends JModel
{
	public $_data = null;

	public $_total = null;

	public $_pagination = null;

	public $_context = null;

	public function __construct()
	{
		parent::__construct();

		$app            = JFactory::getApplication();
		$this->_context = 'field_id';
		$limit          = $app->getUserStateFromRequest($this->_context . 'limit', 'limit', $app->getCfg('list_limit'), 0);
		$limitstart     = $app->getUserStateFromRequest($this->_context . 'limitstart', 'limitstart', 0);
		$filter         = $app->getUserStateFromRequest($this->_context . 'filter', 'filter', 0);
		$filtertype     = $app->getUserStateFromRequest($this->_context . 'filtertypes', 'filtertypes', 0);
		$filtersection  = $app->getUserStateFromRequest($this->_context . 'filtersection', 'filtersection', 0);
		$limitstart     = ($limit != 0 ? (floor($limitstart / $limit) * $limit) : 0);

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

		$query = "SELECT * FROM #__redshop_fields AS f "
			. "WHERE 1=1 "
			. $where
			. $orderby;

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
			$orderby = ' ORDER BY field_section, ordering ' . $filter_order_Dir;
		}
		else
		{
			$orderby = ' ORDER BY ' . $db->escape($filter_order . ' ' . $filter_order_Dir) . ', field_section, ordering';
		}

		return $orderby;
	}

	/**
	 * Save Custom Field order
	 *
	 * @param   array  $cid    List Index Id
	 * @param   array  $order  Order Number
	 *
	 * @return  boolean
	 */
	public function saveorder($cid = array(), $order = array())
	{
		$row = $this->getTable('fields_detail');

		// Update ordering values
		for ($i = 0; $i < count($cid); $i++)
		{
			$row->load((int) $cid[$i]);

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

	/**
	 * Get Fields information from Sections Ids.
	 * 		Note: This will return non-published fields also
	 *
	 * @param   array  $section  Sections Ids in index array
	 *
	 * @return  mixed  Object information array of Fields
	 */
	public function getFieldInfoBySection($section)
	{
		if (!is_array($section))
		{
			throw new InvalidArgumentException(__FUNCTION__ . 'only accepts Array. Input was ' . $section);
		}

		JArrayHelper::toInteger($section);
		$sections = implode(',', $section);

		// Initialiase variables.
		$db    = JFactory::getDbo();
		$query = $db->getQuery(true);

		// Create the base select statement.
		$query->select('field_name,field_type,field_section')
			->from($db->qn('#__redshop_fields'))
			->where($db->qn('field_section') . ' IN(' . $sections . ')');

		// Set the query and load the result.
		$db->setQuery($query);

		try
		{
			$fields = $db->loadObjectList();
		}
		catch (RuntimeException $e)
		{
			throw new RuntimeException($e->getMessage(), $e->getCode());
		}

		return $fields;
	}
}

