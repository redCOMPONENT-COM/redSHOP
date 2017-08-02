<?php
/**
 * @package     RedSHOP.Frontend
 * @subpackage  Model
 *
 * @copyright   Copyright (C) 2008 - 2017 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

defined('_JEXEC') or die;


/**
 * Class ordersModelorders
 *
 * @package     RedSHOP.Frontend
 * @subpackage  Model
 * @since       1.0
 */
class RedshopModelOrders extends RedshopModel
{
	public $_id = null;

	public $_data = null;

	public $_table_prefix = null;

	public $_template = null;

	public $_limitstart = null;

	public $_limit = null;

	public function __construct()
	{
		global $context;

		parent::__construct();

		$app = JFactory::getApplication();

		$this->_table_prefix = '#__redshop_';
		$this->_limitstart   = JRequest::getVar('limitstart', 0);
		$this->_limit        = $app->getUserStateFromRequest($context . 'limit', 'limit', 10, 'int');
	}

	public function _buildQuery()
	{
		$user  = JFactory::getUser();
		$db = $this->getDbo();
		$query = $db->getQuery(true);

		$query->select('*');
		$query->from($this->_table_prefix . "orders");
		$query->where('user_id = ' . (int) $user->id);
		$query->order('cdate DESC');

		return $query;
	}

	public function getData()
	{
		$query       = $this->_buildQuery();
		$this->_data = $this->_getList($query, $this->_limitstart, $this->_limit);

		return $this->_data;
	}

	public function getPagination()
	{
		if (empty($this->_pagination))
		{
			JLoader::import('joomla.html.pagination');
			$this->_pagination = new JPagination($this->getTotal(), $this->_limitstart, $this->_limit);
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
