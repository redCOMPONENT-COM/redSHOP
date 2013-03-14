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

class discountModeldiscount extends JModel
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

		$layout = JRequest::getVar('layout');

		if (isset($layout) && $layout == 'product')
			$this->_context = 'discount_product_id';
		else
			$this->_context = 'discount_id';

		$this->_table_prefix = '#__redshop_';
		$limit = $mainframe->getUserStateFromRequest($this->_context . 'limit', 'limit', $mainframe->getCfg('list_limit'), 0);
		$limitstart = $mainframe->getUserStateFromRequest($this->_context . 'limitstart', 'limitstart', 0);
		$spgrpdis_filter = $mainframe->getUserStateFromRequest($this->_context . 'spgrpdis_filter', 'spgrpdis_filter', 0);
		$limitstart = ($limit != 0 ? (floor($limitstart / $limit) * $limit) : 0);
		$this->setState('limit', $limit);
		$this->setState('limitstart', $limitstart);
		$this->setState('spgrpdis_filter', $spgrpdis_filter);
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
		$where = '';
		$layout = JRequest::getVar('layout');
		$spgrpdis_filter = $this->getState('spgrpdis_filter');


		if (isset($layout) && $layout == 'product')
		{
			$query = ' SELECT * FROM ' . $this->_table_prefix . 'discount_product ' . $orderby;
		}
		else
		{
			if ($spgrpdis_filter)
			{
				$where = " where ds.shopper_group_id = '" . $spgrpdis_filter . "' ";

				$query = ' SELECT d.* FROM ' . $this->_table_prefix . 'discount d left outer join ' . $this->_table_prefix . 'discount_shoppers ds on d.discount_id=ds.discount_id '
					. $where
					. $orderby;
			}
			else
			{
				$query = ' SELECT * FROM ' . $this->_table_prefix . 'discount ' . $orderby;
			}
		}
		return $query;
	}

	function _buildContentOrderBy()
	{
		global $mainframe;

		$layout = JRequest::getVar('layout');

		if (isset($layout) && $layout == 'product')
			$filter_order = $mainframe->getUserStateFromRequest($this->_context . 'filter_order', 'filter_order', 'discount_product_id');
		else
			$filter_order = $mainframe->getUserStateFromRequest($this->_context . 'filter_order', 'filter_order', 'discount_id');

		$filter_order_Dir = $mainframe->getUserStateFromRequest($this->_context . 'filter_order_Dir', 'filter_order_Dir', '');

		$orderby = ' ORDER BY ' . $filter_order . ' ' . $filter_order_Dir;

		return $orderby;
	}
}