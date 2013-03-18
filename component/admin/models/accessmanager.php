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

class accessmanagerModelaccessmanager extends JModel
{
	public $_context = null;

	public $_data = null;

	public $_total = null;

	public $_pagination = null;

	public $_table_prefix = null;

	function __construct()
	{
		parent::__construct();

		global $mainframe;
		$this->_context = 'question_id';

		$this->_table_prefix = '#__redshop_';
		$array = JRequest::getVar('parent_id', 0, '', 'array');
		$this->setId((int) $array[0]);
		$limit = $mainframe->getUserStateFromRequest($this->_context . 'limit', 'limit', $mainframe->getCfg('list_limit'), 0);
		$limitstart = $mainframe->getUserStateFromRequest($this->_context . 'limitstart', 'limitstart', 0);

		$filter = $mainframe->getUserStateFromRequest($this->_context . 'filter', 'filter', 0);
		$product_id = $mainframe->getUserStateFromRequest($this->_context . 'product_id', 'product_id', 0);

		$this->setState('limit', $limit);
		$this->setState('limitstart', $limitstart);
		$this->setState('filter', $filter);
		$this->setState('product_id', $product_id);
	}

	function setId($id)
	{
		$this->_id = $id;
		$this->_data = null;
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

	function getProduct()
	{
		$query = "SELECT * FROM " . $this->_table_prefix . "product ";
		$list = $this->_data = $this->_getList($query);
		return $list;
	}

	function _buildQuery()
	{
		$where = "";
		$filter = $this->getState('filter');
		$product_id = $this->getState('product_id');
		if ($filter)
		{
			$where .= " AND q.question LIKE '%" . $filter . "%' ";
		}
		if ($product_id != 0)
		{
			$where .= " AND q.product_id ='" . $product_id . "' ";
		}
		$orderby = $this->_buildContentOrderBy();

		$query = "SELECT q.* FROM " . $this->_table_prefix . "customer_question AS q "
			. "WHERE q.parent_id='" . $this->_id . "' "
			. $where
			. $orderby;
		return $query;
	}

	function _buildContentOrderBy()
	{
		global $mainframe;

		$filter_order = $mainframe->getUserStateFromRequest($this->_context . 'filter_order', 'filter_order', 'question_date');
		$filter_order_Dir = $mainframe->getUserStateFromRequest($this->_context . 'filter_order_Dir', 'filter_order_Dir', 'DESC');

		$orderby = " ORDER BY " . $filter_order . " " . $filter_order_Dir;
		return $orderby;
	}
}

?>
