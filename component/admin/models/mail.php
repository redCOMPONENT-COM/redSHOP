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

class mailModelmail extends JModel
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

		$this->_context = 'mail_id';
		$this->_table_prefix = '#__' . TABLE_PREFIX . '_';

		$limit = $mainframe->getUserStateFromRequest($this->_context . 'limit', 'limit', $mainframe->getCfg('list_limit'), 0);
		$limitstart = $mainframe->getUserStateFromRequest($this->_context . 'limitstart', 'limitstart', 0);
		$filter = $mainframe->getUserStateFromRequest($this->_context . 'filter', 'filter', 0);
		$filter_section = $mainframe->getUserStateFromRequest($this->_context . 'filter_section', 'filter_section', 0);

		$this->setState('filter', $filter);
		$this->setState('filter_section', $filter_section);
		$this->setState('limit', $limit);
		$this->setState('limitstart', $limitstart);
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
		$filter_section = $this->getState('filter_section');
		$orderby = $this->_buildContentOrderBy();
		$where = '';
		$limit = "";

		if ($filter)
		{
			$where .= "AND mail_name LIKE '" . $filter . "%' ";
		}
		if ($filter_section)
		{
			$where .= "AND mail_section='" . $filter_section . "' ";
		}
		$query = "SELECT distinct(m.mail_id),m.* FROM " . $this->_table_prefix . "mail AS m "
			. "WHERE 1=1 "
			. $where
			. $orderby;

		return $query;
	}

	public function _buildContentOrderBy()
	{
		global $mainframe;

		$filter_order = $mainframe->getUserStateFromRequest($this->_context . 'filter_order', 'filter_order', 'm.mail_id');
		$filter_order_Dir = $mainframe->getUserStateFromRequest($this->_context . 'filter_order_Dir', 'filter_order_Dir', '');
		$orderby = ' ORDER BY ' . $filter_order . ' ' . $filter_order_Dir;

		return $orderby;
	}
}
