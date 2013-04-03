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
require_once(JPATH_COMPONENT . DS . 'helpers/order.php');

class orderreddesignModelorderreddesign extends JModelLegacy
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
		$order_id = array();
		$filter = $this->getState('filter');
		$filter_status = $this->getState('filter_status');
		$cid = JRequest::getVar('cid', array(0), 'method', 'array');
		$order_id = implode(',', $cid);

		$where = array();

		if ($filter_status)
		{
			$where[] = "o.order_status like '%" . $filter_status . "%'";
		}
		if ($filter)
		{
			$where[] = "(  uf.firstname like '%" . $filter . "%' OR uf.lastname like '%" . $filter . "%')";
		}

		$query = "SELECT order_id FROM  #__reddesign_order ";
		$this->_db->setQuery($query);
		$designorder = $this->_db->loadResultArray();
		$designorderstr = join(",", $designorder);

		if (count($designorder) > 0)
		{
			$where[] = " o.order_id IN (" . $designorderstr . ") ";
		}
		else
		{
			$where[] = "o.order_id IN (0) ";
		}

		if ($cid[0] != 0)
		{
			$where[] = " o.order_id IN (" . $order_id . ")";
		}

		$where = count($where) ? ' AND ' . implode(' AND ', $where) : '';
		$orderby = $this->_buildContentOrderBy();

		$query = ' SELECT * FROM ' . $this->_table_prefix . 'orders as o, ' . $this->_table_prefix
			. 'users_info as uf WHERE  o.user_id=uf.user_id and address_type Like "BT" ' . $where . $orderby;

		return $query;
	}

	public function _buildContentOrderBy()
	{
		$app = JFactory::getApplication();

		$filter_order = $app->getUserStateFromRequest($this->_context . 'filter_order', 'filter_order', ' cdate ');
		$filter_order_Dir = $app->getUserStateFromRequest($this->_context . 'filter_order_Dir', 'filter_order_Dir', ' DESC ');

		$orderby = ' ORDER BY ' . $filter_order . ' ' . $filter_order_Dir;

		return $orderby;
	}

	public function update_status()
	{
		$order_function = new order_functions;
		$order_function->update_status();
	}

	public function update_status_all()
	{
		$order_function = new order_functions;
		$order_function->update_status_all();
	}

	public function export_data()
	{

		$cid = JRequest::getVar('cid', array(0), 'method', 'array');
		$order_id = implode(',', $cid);
		$query1 = $this->_buildQuery();

		return $this->_getList($query1);
	}

	public function getdesignorder()
	{
		$query = "SELECT order_id FROM #__reddesign_order ";
		$this->_db->setQuery($query);
		$designorder = $this->_db->loadResultArray();

		return $designorder;
	}

	public function getorderdesign($order_id)
	{
		$query = "SELECT * FROM #__reddesign_order where order_id=" . $order_id;
		$this->_db->setQuery($query);
		$orderdesign = $this->_db->loadObjectlist();

		return $orderdesign;
	}
}
