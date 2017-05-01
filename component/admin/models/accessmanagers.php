<?php
/**
 * @package     RedSHOP.Backend
 * @subpackage  Model
 *
 * @copyright   Copyright (C) 2008 - 2016 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

defined('_JEXEC') or die;

class RedshopModelAccessmanagers extends RedshopModel
{
	public $context = null;

	public $data = null;

	public $total = null;

	public $pagination = null;

	/**
	 * RedshopModelAccessmanager constructor.
	 */
	public function __construct()
	{
		parent::__construct();

		$app = JFactory::getApplication();
		$jinput = JFactory::getApplication()->input;
		$this->context = 'question_id';

		$array = $jinput->getArray('parent_id');

		// TODO Should we getInt directly instead this way
		if (isset($array[0]))
		{
			$this->setId((int) $array[0]);
		}

		$limit = $app->getUserStateFromRequest($this->context . 'limit', 'limit', JFactory::getConfig()->get('list_limit'), 0);
		$limitstart = $app->getUserStateFromRequest($this->context . 'limitstart', 'limitstart', 0);

		$filter = $app->getUserStateFromRequest($this->context . 'filter', 'filter', 0);
		$product_id = $app->getUserStateFromRequest($this->context . 'product_id', 'product_id', 0);

		$this->setState('limit', $limit);
		$this->setState('limitstart', $limitstart);
		$this->setState('filter', $filter);
		$this->setState('product_id', $product_id);
	}

	/**
	 * Set Id and reset data
	 *
	 * @param   int  $id  ID
	 *
	 * @return  void
	 */
	public function setId($id)
	{
		$this->id = $id;
		$this->data = null;
	}

	/**
	 * Get data
	 *
	 * @return  array
	 */
	public function getData()
	{
		if (empty($this->data))
		{
			$query = $this->_buildQuery();
			$this->data = $this->_getList($query, $this->getState('limitstart'), $this->getState('limit'));
		}

		return $this->data;
	}

	/**
	 * Get total records
	 *
	 * @return  int
	 */
	public function getTotal()
	{
		if (empty($this->_total))
		{
			$query = $this->_buildQuery();
			$this->total = $this->_getListCount($query);
		}

		return $this->total;
	}

	/**
	 * Get pagination
	 *
	 * @return  object
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
	 * Build query
	 *
	 * @return  string
	 */
	protected function _buildQuery()
	{
		$where = array();
		$filter = $this->getState('filter');
		$product_id = $this->getState('product_id');

		if ($filter)
		{
			$where[] = " AND q.question LIKE '%" . $filter . "%' ";
		}

		if ($product_id != 0)
		{
			$where[] = " AND q.product_id =" . (int) $product_id . " ";
		}

		$orderby = $this->_buildContentOrderBy();

		$query = 'SELECT q.* '
			. ' FROM ' . '$__redshop_customer_question AS q '
			. ' WHERE q.parent_id = ' . (int) $this->_id . ' '
			. $where
			. $orderby;

		return $query;
	}

	/**
	 * Build order query
	 *
	 * @return  string
	 */
	public function _buildContentOrderBy()
	{
		$db  = JFactory::getDbo();
		$app = JFactory::getApplication();

		$filter_order = $app->getUserStateFromRequest($this->_context . 'filter_order', 'filter_order', 'question_date');
		$filter_order_Dir = $app->getUserStateFromRequest($this->_context . 'filter_order_Dir', 'filter_order_Dir', 'DESC');

		$orderby = " ORDER BY " . $db->escape($filter_order . " " . $filter_order_Dir);

		return $orderby;
	}
}
