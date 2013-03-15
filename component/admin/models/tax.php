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

class taxModeltax extends JModel
{
	var $_data = null;
	var $_total = null;
	var $_pagination = null;
	var $_table_prefix = null;
	var $_tax_group_id = null;
	var $_context = null;

	function __construct()
	{
		parent::__construct();

		global $mainframe;

		$this->_context = 'tax_rate_id';

		$this->_table_prefix = '#__' . TABLE_PREFIX . '_';
		$limit               = $mainframe->getUserStateFromRequest($this->_context . 'limit', 'limit', $mainframe->getCfg('list_limit'), 0);
		$limitstart          = $mainframe->getUserStateFromRequest($this->_context . 'limitstart', 'limitstart', 0);

		$this->setState('limit', $limit);
		$this->setState('limitstart', $limitstart);
		$tax_group_id = JRequest::getVar('tax_group_id');
		$this->setProductId((int) $tax_group_id);
	}

	function setProductId($id)
	{
		// Set employees_detail id and wipe data
		$this->_tax_group_id = $id;
		$this->_data         = null;
	}

	function getProductId()
	{
		return $this->_tax_group_id;
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
		$query = ' SELECT tr.*,tg.tax_group_name  '
			. ' FROM ' . $this->_table_prefix . 'tax_rate as tr'
			. ' LEFT JOIN ' . $this->_table_prefix . 'tax_group as tg ON tr.tax_group_id = tg.tax_group_id '
			. 'WHERE tg.tax_group_id = \'' . $this->_tax_group_id . '\' ';

		return $query;
	}
}

?>