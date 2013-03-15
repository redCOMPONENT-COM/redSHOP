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

class shipping_rateModelShipping_rate extends JModel
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

		$this->_table_prefix = '#__redshop_';
		$this->_context      = 'shipping_rate_id';
		$limit               = $mainframe->getUserStateFromRequest($this->_context . 'limit', 'limit', $mainframe->getCfg('list_limit'), 0);
		$limitstart          = $mainframe->getUserStateFromRequest($this->_context . 'limitstart', 'limitstart', 0);

//		$array = JRequest::getVar('cid',  0, '', 'array');
		$id = $mainframe->getUserStateFromRequest($this->_context . 'extension_id', 'extension_id', 0); //(int)$array[0]);

		$this->setState('limit', $limit);
		$this->setState('limitstart', $limitstart);
		$this->setState('id', $id);
	}

	function getData()
	{
		if (empty($this->_data))
		{
			$query       = $this->_buildQuery();
			$this->_data = $this->_getList($query, $this->getState('limitstart'), $this->getState('limit'));
		}

		return $this->_data;
	}

	function getTotal()
	{
		if (empty($this->_total))
		{
			$query        = $this->_buildQuery();
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
		$id      = $this->getState('id');

		$query = 'SELECT r.*,p.extension_id,p.element,p.folder FROM ' . $this->_table_prefix . 'shipping_rate AS r '
			. 'LEFT JOIN #__extensions AS p ON CONVERT(p.element USING utf8)= CONVERT(r.shipping_class USING utf8) '
			. 'WHERE p.extension_id="' . $id . '" '
			. $orderby;

		return $query;
	}

	function _buildContentOrderBy()
	{
		global $mainframe;
		$filter_order     = $mainframe->getUserStateFromRequest($this->_context . 'filter_order', 'filter_order', 'shipping_rate_id');
		$filter_order_Dir = $mainframe->getUserStateFromRequest($this->_context . 'filter_order_Dir', 'filter_order_Dir', '');
		$orderby          = ' ORDER BY ' . $filter_order . ' ' . $filter_order_Dir;

		return $orderby;
	}
}

?>