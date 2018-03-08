<?php
/**
 * @package     RedSHOP.Backend
 * @subpackage  Model
 *
 * @copyright   Copyright (C) 2008 - 2017 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

defined('_JEXEC') or die;


class RedshopModelCatalog extends RedshopModel
{
	/**
	 * @var null
	 */
	public $_data = null;

	/**
	 * @var integer
	 */
	public $_total = null;

	/**
	 * @var JPagination
	 */
	public $_pagination = null;

	/**
	 * @var null|string
	 */
	public $_table_prefix = null;

	/**
	 * @var null|string
	 */
	public $_context = null;

	/**
	 * RedshopModelCatalog constructor.
	 * @throws Exception
	 */
	public function __construct()
	{
		parent::__construct();

		$app                 = JFactory::getApplication();
		$this->_context      = 'catalog_id';
		$this->_table_prefix = '#__redshop_';
		$limit               = $app->getUserStateFromRequest($this->_context . 'limit', 'limit', $app->getCfg('list_limit'), 0);
		$limitstart          = $app->getUserStateFromRequest($this->_context . 'limitstart', 'limitstart', 0);
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
			$query        = $this->_buildQuery();
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
		$orderby = $this->_buildContentOrderBy();
		$query   = 'SELECT  distinct(c.catalog_id),c.* FROM ' . $this->_table_prefix . 'catalog c ' . $orderby;

		return $query;
	}

	public function _buildContentOrderBy()
	{
		$db  = JFactory::getDbo();
		$app = JFactory::getApplication();

		$filter_order = $app->getUserStateFromRequest($this->_context . 'filter_order', 'filter_order', 'catalog_id');

		$filter_order_Dir = $app->getUserStateFromRequest($this->_context . 'filter_order_Dir', 'filter_order_Dir', '');

		$orderby = ' ORDER BY ' . $db->escape($filter_order . ' ' . $filter_order_Dir);

		return $orderby;
	}

	public function MediaDetail($pid)
	{
		$query = 'SELECT * FROM ' . $this->_table_prefix . 'media  WHERE section_id =' . $pid . ' AND media_section = "catalog"';
		$this->_db->setQuery($query);

		return $this->_db->loadObjectlist();
	}
}
