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

class stateModelstate extends JModel
{
	public $_data = null;

	public $_total = null;

	public $_pagination = null;

	public $_table_prefix = null;

	public $_context = null;

	public function __construct()
	{
		parent::__construct();

		$app = JFactory::getApplication();

		$this->_context = 'state_id';

		$this->_table_prefix = '#__' . TABLE_PREFIX . '_';
		$limit = $app->getUserStateFromRequest($this->_context . 'limit', 'limit', $app->getCfg('list_limit'), 0);
		$limitstart = $app->getUserStateFromRequest($this->_context . 'limitstart', 'limitstart', 0);
		$country_id_filter = $app->getUserStateFromRequest($this->_context . 'country_id_filter', 'country_id_filter', 0);
		$country_main_filter = $app->getUserStateFromRequest($this->_context . 'country_main_filter', 'country_main_filter', '');
		$limitstart = ($limit != 0 ? (floor($limitstart / $limit) * $limit) : 0);

		$this->setState('country_id_filter', $country_id_filter);
		$this->setState('country_main_filter', $country_main_filter);
		$this->setState('limit', $limit);
		$this->setState('limitstart', $limitstart);
	}

	public function getData()
	{
		if (empty($this->_data))
		{
			$this->_data = $this->_buildQuery();
		}

		return $this->_data;
	}

	public function getTotal()
	{
		if (empty($this->_total))
		{
			/*
			 * @ToDo: _buildQuery method was expected to return a query and not the items. But is returning items.
			 * so to quick fix this method getTotal() I have commented the following lines and added the third one
			 */
			// $query = $this->_buildQuery();
			// $this->_data = $this->_getListCount($query);
			$this->_total = count($this->_buildQuery());
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
		$country_id_filter = $this->getState('country_id_filter');
		$country_main_filter = $this->getState('country_main_filter');
		$limitstart = $this->getState('limitstart');
		$limit = $this->getState('limit');
		$andcondition = '1=1';
		$country_main_filter = addslashes($country_main_filter);

		if ($country_id_filter > 0 && $country_main_filter == '')
		{
			$andcondition = 'c.country_id = ' . $country_id_filter;
		}

		elseif ($country_id_filter > 0 && $country_main_filter != '')
		{
			$andcondition = "c.country_id = " . $country_id_filter . " and (s.state_name like '" . $country_main_filter . "%' || s.state_3_code = '"
				. $country_main_filter . "' || s.state_2_code = '" . $country_main_filter . "')";
		}
		elseif ($country_id_filter == 0 && $country_main_filter != '')
		{
			$andcondition = "s.state_name like '" . $country_main_filter . "%' || s.state_3_code = '" . $country_main_filter
				. "' || s.state_2_code='" . $country_main_filter . "'";
		}
		$query = 'SELECT distinct(s.state_id),s . * , c.country_name FROM `' . $this->_table_prefix . 'state` AS s '
			. 'LEFT JOIN ' . $this->_table_prefix . 'country AS c ON s.country_id = c.country_id WHERE ' . $andcondition . $orderby;

		$this->_db->setQuery($query);
		$rows = $this->_db->loadObjectlist();
		$list = $rows;
		$total = count($list);

		jimport('joomla.html.pagination');
		$this->_pagination = new JPagination($total, $limitstart, $limit);

		// Slice out elements based on limits
		$list = array_slice($list, $this->_pagination->limitstart, $this->_pagination->limit);
		$items = $list;

		return $items;
	}

	public function _buildContentOrderBy()
	{
		$app = JFactory::getApplication();

		$filter_order = $app->getUserStateFromRequest($this->_context . 'filter_order', 'filter_order', 'state_id');
		$filter_order_Dir = $app->getUserStateFromRequest($this->_context . 'filter_order_Dir', 'filter_order_Dir', '');
		$orderby = ' ORDER BY ' . $filter_order . ' ' . $filter_order_Dir;

		return $orderby;
	}

	public function getCountryName($country_id)
	{
		$query = "SELECT  c.country_name from " . $this->_table_prefix . "country AS c where c.country_id=" . $country_id;
		$this->_db->setQuery($query);

		return $this->_db->loadResult();
	}
}
