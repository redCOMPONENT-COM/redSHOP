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
 * Class product_miniModelproduct_mini
 *
 * @package     RedSHOP.Frontend
 * @subpackage  Model
 * @since       1.0
 */
class RedshopModelProduct_mini extends RedshopModel
{
	public $_data = null;

	public $_total = null;

	public $_pagination = null;

	public $_table_prefix = null;

	public function __construct()
	{
		global $context;

		parent::__construct();

		$app = JFactory::getApplication();

		$context             = 'product_id';
		$this->_table_prefix = '#__redshop_';

		$limit      = $app->getUserStateFromRequest($context . 'limit', 'limit', $app->getCfg('list_limit'), 0);
		$limitstart = $app->getUserStateFromRequest($context . 'limitstart', 'limitstart', 0);

		$this->setState('limit', $limit);
		$this->setState('limitstart', $limitstart);
	}

	public function getData()
	{
		if (empty($this->_data))
		{
			$query       = $this->_buildQuery();
			$this->_data = $this->_getList($query);
		}

		return $this->_data;
	}

	public function getTotal()
	{
		global $context;

		$app = JFactory::getApplication();

		$orderby      = $this->_buildContentOrderBy();
		$search_field = $app->getUserStateFromRequest($context . 'search_field', 'search_field', '');
		$keyword      = $app->getUserStateFromRequest($context . 'keyword', 'keyword', '');
		$category_id  = $app->getUserStateFromRequest($context . 'category_id', 'category_id', 0);

		$where = '';

		if (trim($keyword) != '')
		{
			$where .= " AND " . $this->_db->quoteName($search_field) . " LIKE " . $this->_db->quote('%' . $keyword . '%') . "  ";
		}

		if ($category_id)
		{
			$where .= " AND c.category_id = " . (int) $category_id . " ";
		}

		if ($where != '')
		{
			$query = 'SELECT count(distinct(p.product_id)) '
				. 'FROM ' . $this->_table_prefix . 'product p '
				. 'LEFT JOIN ' . $this->_table_prefix . 'product_category_xref x ON x.product_id = p.product_id '
				. 'LEFT JOIN ' . $this->_table_prefix . 'category c ON x.category_id = c.category_id '
				. 'WHERE 1=1 '
				. $where;
		}
		else
		{
			$query = 'SELECT count(*) FROM ' . $this->_table_prefix . 'product p ';
		}

		if (empty($this->_total))
		{
			$this->_db->setQuery($query);
			$this->_total = $this->_db->loadResult();
		}

		return $this->_total;
	}

	public function getPagination()
	{
		if (empty($this->_pagination))
		{
			$this->_pagination = new JPagination($this->getTotal(), $this->getState('limitstart'), $this->getState('limit'));
		}

		return $this->_pagination;
	}

	public function _buildQuery()
	{
		global $context;

		$query = null;

		$app = JFactory::getApplication();

		$orderby      = $this->_buildContentOrderBy();
		$search_field = $app->getUserStateFromRequest($context . 'search_field', 'search_field', '');
		$keyword      = $app->getUserStateFromRequest($context . 'keyword', 'keyword', '');
		$category_id  = $app->getUserStateFromRequest($context . 'category_id', 'category_id', 0);

		$where = '';

		if (trim($keyword) != '')
		{
			$where .= " AND " . $this->_db->quoteName($search_field) . " LIKE " . $this->_db->quote('%' . $keyword . '%') . "  ";
		}

		if ($category_id)
		{
			$where .= " AND c.category_id = " . (int) $category_id . " ";
		}

		// Change limit condition for all issue
		$limit = "";

		if ($this->getState('limit') > 0)
		{
			$limit = " LIMIT " . (int) $this->getState('limitstart') . "," . (int) $this->getState('limit');
		}

		if ($where != '')
		{
			$query = 'SELECT distinct(p.product_id),p.*, x.ordering , x.category_id FROM ' . $this->_table_prefix . 'product p '
				. 'LEFT JOIN ' . $this->_table_prefix . 'product_category_xref x ON x.product_id = p.product_id '
				. 'LEFT JOIN ' . $this->_table_prefix . 'category c ON x.category_id = c.category_id '
				. 'WHERE 1=1 ' . $where . ' '
				. $orderby;
		}

		return $query;
	}

	public function _buildContentOrderBy()
	{
		$db = JFactory::getDbo();

		global $context;

		$app = JFactory::getApplication();

		$filter_order     = urldecode($app->getUserStateFromRequest($context . 'filter_order', 'filter_order', 'product_id'));
		$filter_order_Dir = urldecode($app->getUserStateFromRequest($context . 'filter_order_Dir', 'filter_order_Dir', ''));

		$orderby = ' ORDER BY ' . $db->escape($filter_order . ' ' . $filter_order_Dir);

		return $orderby;
	}
}
