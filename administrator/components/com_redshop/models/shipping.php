<?php
/**
 * @package     redSHOP
 * @subpackage  Models
 *
 * @copyright   Copyright (C) 2008 - 2012 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later, see LICENSE.
 */

defined('_JEXEC') or die('Restricted access');

class shippingModelShipping extends JModelLegacy
{
	public $_data = null;

	public $_total = null;

	public $_pagination = null;

	public $_table_prefix = null;

	public $_context = null;

	public function __construct()
	{
		parent::__construct();
		global $mainframe;

		$this->_context      = 'shipping_id';
		$this->_table_prefix = '#__redshop_';
		$limit               = $mainframe->getUserStateFromRequest($this->_context . 'limit', 'limit', $mainframe->getCfg('list_limit'), 0);
		$limitstart          = $mainframe->getUserStateFromRequest($this->_context . 'limitstart', 'limitstart', 0);
		$limitstart          = ($limit != 0 ? (floor($limitstart / $limit) * $limit) : 0);
		$this->setState('limit', $limit);
		$this->setState('limitstart', $limitstart);
	}

	public function getData()
	{
		if (empty($this->_data))
		{
			$query       = $this->_buildQuery();
			$this->_data = $this->_getList($query, $this->getState('limitstart'), $this->getState('limit'));
		}
		return $this->_data;
	}

	public function getTotal()
	{
		if (empty($this->_total))
		{
			$query       = $this->_buildQuery();
			$this->_data = $this->_getListCount($query);
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
		$query   = 'SELECT s.* FROM #__extensions AS s ' . 'WHERE s.folder="redshop_shipping" ' . $orderby;
		return $query;
	}

	public function _buildContentOrderBy()
	{
		global $mainframe;

		$filter_order     = $mainframe->getUserStateFromRequest($this->_context . 'filter_order', 'filter_order', 'ordering');
		$filter_order_Dir = $mainframe->getUserStateFromRequest($this->_context . 'filter_order_Dir', 'filter_order_Dir', '');
		$orderby          = ' ORDER BY ' . $filter_order . ' ' . $filter_order_Dir;
		return $orderby;
	}

	public function saveOrder(&$cid)
	{
		global $mainframe;
		//$scope 		= JRequest::getCmd( 'scope' );
		$db  = JFactory::getDBO();
		$row = $this->getTable('shipping_detail');

		$total = count($cid);
		$order = JRequest::getVar('order', array(0), 'post', 'array');
		JArrayHelper::toInteger($order, array(0));

		// update ordering values
		for ($i = 0; $i < $total; $i++)
		{
			$row->load((int)$cid[$i]);
			if ($row->ordering != $order[$i])
			{
				$row->ordering = $order[$i];
				if (!$row->store())
				{
					throw new RuntimeException($db->getErrorMsg());
				}
			}
		}
		$row->reorder();
		return true;
	}
}
