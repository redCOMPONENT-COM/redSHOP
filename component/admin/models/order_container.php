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

class order_containerModelorder_container extends JModel
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
		$this->_context = 'order_id';
		$this->_table_prefix = '#__redshop_';
		$limit = $app->getUserStateFromRequest($this->_context . 'limit', 'limit', $app->getCfg('list_limit'), 0);
		$limitstart = $app->getUserStateFromRequest($this->_context . 'limitstart', 'limitstart', 0);

		$filter_status = $app->getUserStateFromRequest($this->_context . 'filter_status', 'filter_status', '', 'word');
		$filter = $app->getUserStateFromRequest($this->_context . 'filter', 'filter', 0);
		$this->setState('limit', $limit);
		$this->setState('limitstart', $limitstart);
		$this->setState('filter', $filter);
		$this->setState('filter_status', $filter_status);
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
		$where = "";

		$filter = $this->getState('filter');
		$filter_status = $this->getState('filter_status');

		$where = array();

		if ($filter_status)
		{
			$where[] = "o.order_status like '%" . $filter_status . "%'";
		}

		if ($filter)
		{
			$where[] = "(  uf.firstname like '%" . $filter . "%' OR uf.lastname like '%" . $filter . "%')";
		}

		$where = count($where) ? ' AND ' . implode(' AND ', $where) : '';

		$orderby = $this->_buildContentOrderBy();

		$query = ' SELECT * '
			. ' FROM ' . $this->_table_prefix . 'orders as o, ' . $this->_table_prefix . 'users_info as uf WHERE o.order_id IN ( SELECT
			DISTINCT (`order_id`) FROM ' . $this->_table_prefix . 'order_item WHERE `container_id` < 1 ) AND
			o.user_id=uf.user_id and address_type Like "BT" ' . $where . $orderby;

		return $query;
	}

	public function _buildContentOrderBy()
	{
		$app = JFactory::getApplication();

		$filter_order = $app->getUserStateFromRequest($this->_context . 'filter_order', 'filter_order', 'order_id');
		$filter_order_Dir = $app->getUserStateFromRequest($this->_context . 'filter_order_Dir', 'filter_order_Dir', '');

		$orderby = ' ORDER BY ' . $filter_order . ' ' . $filter_order_Dir;

		return $orderby;
	}

	public function update_status()
	{
		require_once JPATH_COMPONENT . '/helpers/order.php';
		$order_function = new order_functions;

		$order_function->update_status();
	}

	public function export_data()
	{
		$query = ' SELECT * '
			. ' FROM ' . $this->_table_prefix . 'orders as o, ' . $this->_table_prefix . 'users_info as uf WHERE
			o.user_id=uf.user_id and address_type Like "BT" ';

		$query = $this->_buildQuery();

		return $this->_getList($query);
	}
}
