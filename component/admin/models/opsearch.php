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

class opsearchModelopsearch extends JModel
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

		$this->_context = 'order_item_name';
		$this->_table_prefix = '#__redshop_';
		$limit = $mainframe->getUserStateFromRequest($this->_context . 'limit', 'limit', $mainframe->getCfg('list_limit'), 0);
		$limitstart = $mainframe->getUserStateFromRequest($this->_context . 'limitstart', 'limitstart', 0);
		$limitstart = ($limit != 0 ? (floor($limitstart / $limit) * $limit) : 0);
		$filter_user = $mainframe->getUserStateFromRequest($this->_context . 'filter_user', 'filter_user', 0);
		$filter_product = $mainframe->getUserStateFromRequest($this->_context . 'filter_product', 'filter_product', 0);
		$filter_status = $mainframe->getUserStateFromRequest($this->_context . 'filter_status', 'filter_status', 0);

		$this->setState('filter_user', $filter_user);
		$this->setState('filter_product', $filter_product);
		$this->setState('filter_status', $filter_status);
		$this->setState('limit', $limit);
		$this->setState('limitstart', $limitstart);
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
		$query = $this->_buildQuery();
		if (empty($this->_total))
		{
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
		$filter_user = $this->getState('filter_user', '');
		$filter_product = $this->getState('filter_product', '');
		$filter_status = $this->getState('filter_status', '');

		$where = '';
		if ($filter_user)
		{
			$where .= 'AND op.user_info_id="' . $filter_user . '" ';
		}
		if ($filter_product)
		{
			$where .= 'AND op.product_id ="' . $filter_product . '" ';
		}
		if ($filter_status)
		{
			$where .= 'AND op.order_status="' . $filter_status . '" ';
		}
		$query = 'SELECT op.*, CONCAT(ouf.firstname," ",ouf.lastname) AS fullname, ouf.company_name FROM ' . $this->_table_prefix . 'order_item AS op '
			. 'LEFT JOIN ' . $this->_table_prefix . 'order_users_info as ouf ON ouf.order_id=op.order_id AND ouf.address_type="BT" '
			. 'WHERE 1=1 '
			. $where
			. $orderby;
		return $query;
	}

	function _buildContentOrderBy()
	{
		global $mainframe;

		$filter_order = $mainframe->getUserStateFromRequest($this->_context . 'filter_order', 'filter_order', 'order_item_name');
		$filter_order_Dir = $mainframe->getUserStateFromRequest($this->_context . 'filter_order_Dir', 'filter_order_Dir', '');

		$orderby = ' ORDER BY ' . $filter_order . ' ' . $filter_order_Dir;

		return $orderby;
	}

	function getuserlist($name = 'userlist', $selected = '', $attributes = ' class="inputbox" size="1" ')
	{
		$query = "SELECT uf.users_info_id AS value, CONCAT(uf.firstname,' ',uf.lastname) AS text FROM " . $this->_table_prefix . "users_info AS uf "
			. "WHERE uf.address_type='BT' "
			. "ORDER BY text ";
		$userlist = $this->_getList($query);
		$types[] = JHTML::_('select.option', '0', '- ' . JText::_('COM_REDSHOP_SELECT_USER') . ' -');
		$types = array_merge($types, $userlist);
		$mylist['userlist'] = JHTML::_('select.genericlist', $types, $name, $attributes, 'value', 'text', $selected);

		return $mylist['userlist'];

	}
}

?>