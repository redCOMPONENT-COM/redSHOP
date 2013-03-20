<?php
/**
 * @package     RedSHOP.Frontend
 * @subpackage  Model
 *
 * @copyright   Copyright (C) 2005 - 2013 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

defined('_JEXEC') or die;

jimport('joomla.application.component.model');

/**
 * Class ordersModelorders
 *
 * @package     RedSHOP.Frontend
 * @subpackage  Model
 * @since       1.0
 *
 */
class ordersModelorders extends JModel
{
	public $_id = null;
	public $_data = null;
	public $_table_prefix = null;
	public $_template = null;
	public $_limitstart = null;
	public $_limit = null;

	public function __construct()
	{
		parent::__construct();
		global $mainframe, $option;

		$this->_table_prefix = '#__redshop_';
		$this->_limitstart   = JRequest::getVar('limitstart', 0);
		$this->_limit        = $mainframe->getUserStateFromRequest($option . 'limit', 'limit', 10, 'int');
	}

	public function _buildQuery()
	{
		$user  = JFactory::getUser();
		$query = "SELECT * FROM  " . $this->_table_prefix . "orders "
			. "WHERE user_id='" . $user->id . "' ";

		return $query;
	}

	public function getData()
	{
//		if (empty( $this->_data ))
//		{
		$query       = $this->_buildQuery();
		$this->_data = $this->_getList($query, $this->_limitstart, $this->_limit);

//		}
		return $this->_data;
	}

	public function getPagination()
	{
		if (empty($this->_pagination))
		{
			jimport('joomla.html.pagination');
			$this->_pagination = new redPagination($this->getTotal(), $this->_limitstart, $this->_limit);
		}

		return $this->_pagination;
	}

	public function getTotal()
	{
		if (empty($this->_total))
		{
			$query        = $this->_buildQuery();
			$this->_total = $this->_getListCount($query);
		}

		return $this->_total;
	}
}
