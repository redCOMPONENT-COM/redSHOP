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
require_once JPATH_COMPONENT . DS . 'helpers' . DS . 'order.php';
class orderreddesignModelorderreddesign extends JModel
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
		$this->_context = 'order_id';
		$this->_table_prefix = '#__redshop_';
		$limit = $mainframe->getUserStateFromRequest($this->_context . 'limit', 'limit', $mainframe->getCfg('list_limit'), 0);
		$limitstart = $mainframe->getUserStateFromRequest($this->_context . 'limitstart', 'limitstart', 0);

		$filter_status = $mainframe->getUserStateFromRequest($this->_context . 'filter_status', 'filter_status', '', 'word');
		$filter = $mainframe->getUserStateFromRequest($this->_context . 'filter', 'filter', 0);
		$this->setState('limit', $limit);
		$this->setState('limitstart', $limitstart);
		$this->setState('filter', $filter);
		$this->setState('filter_status', $filter_status);

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

		$query = ' SELECT * FROM ' . $this->_table_prefix . 'orders as o, ' . $this->_table_prefix . 'users_info as uf WHERE  o.user_id=uf.user_id and address_type Like "BT" ' . $where . $orderby;

		return $query;
	}

	function _buildContentOrderBy()
	{
		global $mainframe;

		$filter_order = $mainframe->getUserStateFromRequest($this->_context . 'filter_order', 'filter_order', ' cdate ');
		$filter_order_Dir = $mainframe->getUserStateFromRequest($this->_context . 'filter_order_Dir', 'filter_order_Dir', ' DESC ');

		$orderby = ' ORDER BY ' . $filter_order . ' ' . $filter_order_Dir;

		return $orderby;
	}

	function update_status()
	{
		$order_function = new order_functions();
		$order_function->update_status();
	}

	function update_status_all()
	{
		$order_function = new order_functions();
		$order_function->update_status_all();
	}

	function export_data()
	{

		$cid = JRequest::getVar('cid', array(0), 'method', 'array');
		$order_id = implode(',', $cid);
		$query1 = $this->_buildQuery();
		return $this->_getList($query1);
	}

	// reddesign
	function getdesignorder()
	{
		$query = "SELECT order_id FROM #__reddesign_order ";
		$this->_db->setQuery($query);
		$designorder = $this->_db->loadResultArray();
		//$designorderstr = join(",",$designorder);
		return $designorder;
	}

	function getorderdesign($order_id)
	{
		$query = "SELECT * FROM #__reddesign_order where order_id=" . $order_id;
		$this->_db->setQuery($query);
		$orderdesign = $this->_db->loadObjectlist();

		return $orderdesign;
	}
	// reddesign end
}

?>