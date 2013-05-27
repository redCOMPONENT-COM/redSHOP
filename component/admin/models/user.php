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

class userModeluser extends JModel
{
	public $_data = null;

	public $_id = null;

	public $_total = null;

	public $_pagination = null;

	public $_table_prefix = null;

	public $_context = null;

	public function __construct()
	{
		parent::__construct();

		$app = JFactory::getApplication();
		$this->_context = 'user_info_id';
		$this->_table_prefix = '#__redshop_';
		$limit = $app->getUserStateFromRequest($this->_context . 'limit', 'limit', $app->getCfg('list_limit'), 0);
		$limitstart = $app->getUserStateFromRequest($this->_context . 'limitstart', 'limitstart', 0);

		$filter = $app->getUserStateFromRequest($this->_context . 'filter', 'filter', 0);
		$filter_by = $app->getUserStateFromRequest($this->_context . 'filter_by', 'filter_by', 0);

		$spgrp_filter = $app->getUserStateFromRequest($this->_context . 'spgrp_filter', 'spgrp_filter', 0);

		$approved_filter = $app->getUserStateFromRequest($this->_context . 'approved_filter', 'approved_filter', 0);

		$tax_exempt_request_filter = $app->getUserStateFromRequest($this->_context . 'tax_exempt_request_filter', 'tax_exempt_request_filter', 0);


		$array = JRequest::getVar('user_id', 0, '', 'array');

		$this->setId((int) $array[0]);
		$this->setState('limit', $limit);
		$this->setState('limitstart', $limitstart);
		$this->setState('filter', $filter);
		$this->setState('filter_by', $filter_by);
		$this->setState('spgrp_filter', $spgrp_filter);
		$this->setState('approved_filter', $approved_filter);
		$this->setState('tax_exempt_request_filter', $tax_exempt_request_filter);
	}

	public function setId($id)
	{
		$this->_id = $id;
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
		$filter = $this->getState('filter');
		$filter_by = $this->getState('filter_by');
		$spgrp_filter = $this->getState('spgrp_filter');
		$approved_filter = $this->getState('approved_filter');
		$tax_exempt_request_filter = $this->getState('tax_exempt_request_filter');

		$where = '';

		if ($filter)
		{
			$filter = str_replace(' ', '', $filter);

			if($filter_by == 'fullname')
			{
				$where .= " AND (REPLACE(CONCAT(uf.firstname, uf.lastname), ' ', '') like '%" . $filter . "%')";
			}
			else if($filter_by == 'username')
			{
				$where .= " AND (u.username LIKE '%" . $filter . "%')";
			}
			else // $filter_by == all
			{
				$where .= " AND (u.username LIKE '%" . $filter . "%' ";
				$where .= " OR (REPLACE(CONCAT(uf.firstname, uf.lastname), ' ', '') like '%" . $filter . "%'))";
			}
		}

		if ($spgrp_filter)
		{
			$where .= " AND sp.shopper_group_id = '" . $spgrp_filter . "' ";
		}

		if ($approved_filter != 'select')
		{
			$where .= " AND uf.approved='" . $approved_filter . "' ";
		}

		if ($tax_exempt_request_filter != 'select')
		{
			$where .= " AND uf.tax_exempt='" . $tax_exempt_request_filter . "' "
				. "AND tax_exempt_approved=0 ";
		}

		$orderby = $this->_buildContentOrderBy();

		if ($this->_id != 0)
		{
			$query = ' SELECT * FROM  #__users AS u '
				. 'LEFT JOIN ' . $this->_table_prefix . 'users_info AS uf ON u.id=uf.user_id '
				. 'LEFT JOIN ' . $this->_table_prefix . 'shopper_group AS sp ON uf.shopper_group_id=sp.shopper_group_id '
				. 'WHERE uf.address_type="ST" '
				. 'AND uf.user_id="' . $this->_id . '" '
				. $where
				. $orderby;
		}
		else
		{
			$query = ' SELECT uf.user_id, uf.*,u.username,u.name,sp.shopper_group_name '
				. 'FROM ' . $this->_table_prefix . 'users_info AS uf '
				. 'LEFT JOIN #__users AS u ON u.id = uf.user_id '
				. 'LEFT JOIN ' . $this->_table_prefix . 'shopper_group AS sp ON sp.shopper_group_id = uf.shopper_group_id '
				. 'WHERE uf.address_type="BT" '
				. $where
				. $orderby;
		}

		return $query;
	}

	public function _buildContentOrderBy()
	{
		$app = JFactory::getApplication();

		$filter_order = $app->getUserStateFromRequest($this->_context . 'filter_order', 'filter_order', 'users_info_id');
		$filter_order_Dir = $app->getUserStateFromRequest($this->_context . 'filter_order_Dir', 'filter_order_Dir', '');
		$orderby = ' ORDER BY ' . $filter_order . ' ' . $filter_order_Dir;

		return $orderby;
	}

	public function customertotalsales($uid)
	{
		$query = 'SELECT SUM(order_total) FROM ' . $this->_table_prefix . 'orders WHERE user_id=' . $uid;
		$this->_db->setQuery($query);
		$re = $this->_db->loadResult();

		if (!$re)
		{
			$re = 0;
		}

		return $re;
	}
}
