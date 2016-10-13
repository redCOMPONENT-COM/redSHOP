<?php
/**
 * @package     RedSHOP.Backend
 * @subpackage  Model
 *
 * @copyright   Copyright (C) 2008 - 2016 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

defined('_JEXEC') or die;

/**
 * Model Countries
 *
 * @package     RedSHOP.Backend
 * @subpackage  Model
 * @since       [version> [<description>]
 */

class RedshopModelCountries extends RedshopModel
{
	public $data = null;

	public $total = null;

	public $pagination = null;

	public $tablePrefix = null;

	public $context = null;

	/**
	 * Construct class
	 *
	 * @since 1.x
	 */

	public function __construct()
	{
		parent::__construct();

		$app = JFactory::getApplication();

		$this->context = 'id';
		$this->tablePrefix = '#__redshop_';
		$limit = $app->getUserStateFromRequest($this->context . 'limit', 'limit', $app->getCfg('list_limit'), 0);
		$limitStart = $app->getUserStateFromRequest($this->context . 'limitstart', 'limitstart', 0);
		$filter = $app->getUserStateFromRequest($this->context . 'filter', 'filter', '');
		$limitStart = ($limit != 0 ? (floor($limitStart / $limit) * $limit) : 0);
		$this->setState('limit', $limit);
		$this->setState('limitstart', $limitStart);
		$this->setState('filter', $filter);
	}

	/**
	 * Function getData
	 *
	 * @return void
	 * 
	 * @since 1.x
	 */

	public function getData()
	{
		if (empty($this->data))
		{
			$query = $this->buildQuery();
			$this->data = $this->getList($query, $this->getState('limitstart'), $this->getState('limit'));
		}

		return $this->data;
	}

	/**
	 * Get count total countries in db
	 * 
	 * @return int total countries
	 *
	 * @since 1.x
	 */

	public function getTotal()
	{
		if (empty($this->total))
		{
			$query = $this->buildQuery();
			$this->total = $this->getListCount($query);
		}

		return $this->total;
	}

	/**
	 * Construct class
	 * 
	 * @return   pagination
	 *
	 * @since 1.x
	 */

	public function getPagination()
	{
		if (empty($this->pagination))
		{
			jimport('joomla.html.pagination');
			$this->pagination = new JPagination($this->getTotal(), $this->getState('limitstart'), $this->getState('limit'));
		}

		return $this->pagination;
	}

	/**
	 * Build up query string
	 * 
	 * @return   string query
	 *
	 * @since 1.x
	 */

	public function buildQuery()
	{
		$filter = $this->getState('filter');
		$where = '';

		if ($filter)
		{
			$where = " WHERE country_name like '%" . $filter . "%' ";
		}

		$orderby = $this->buildContentOrderBy();
		$query = " SELECT distinct(c.id),c.*  FROM " . $this->tablePrefix . "country c " . $where . $orderby;

		return $query;
	}

	/**
	 * Build content order by substring
	 * 
	 * @return   string orderby
	 *
	 * @since 1.x
	 */

	public function buildContentOrderBy()
	{
		$db  = JFactory::getDbo();
		$app = JFactory::getApplication();

		$filterOrder = $app->getUserStateFromRequest($this->context . 'filter_order', 'filter_order', 'id');
		$filterOrderDir = $app->getUserStateFromRequest($this->context . 'filter_order_Dir', 'filter_order_Dir', '');

		$orderBy = ' ORDER BY ' . $db->escape($filterOrder . ' ' . $filterOrderDir);

		return $orderBy;
	}
}
