<?php
/**
 * @package     RedSHOP.Backend
 * @subpackage  Model
 *
 * @copyright   Copyright (C) 2008 - 2015 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

defined('_JEXEC') or die;

class RedshopModelQuotation extends RedshopModelList
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
		$this->_context = 'quotation_id';

		$this->_table_prefix = '#__redshop_';
		$limit = $app->getUserStateFromRequest($this->_context . 'limit', 'limit', $app->getCfg('list_limit'), 0);
		$limitstart = $app->getUserStateFromRequest($this->_context . 'limitstart', 'limitstart', 0);

		$filter_status = $app->getUserStateFromRequest($this->_context . 'filter_status', 'filter_status', 0);
		$filter = $app->getUserStateFromRequest($this->_context . 'filter', 'filter', 0);

		$this->setState('limit', $limit);
		$this->setState('limitstart', $limitstart);
		$this->setState('filter', $filter);
		$this->setState('filter_status', $filter_status);
	}

	/**
	 * Get the columns for the csv file.
	 *
	 * @return  array  An associative array of column names as key and the title as value.
	 */
	public function getCsvColumns()
	{
		return array(
			'quotation_id' => JText::_('COM_REDSHOP_QUOTATION_ID'),
			'full_name' => JText::_('COM_REDSHOP_FULLNAME'),
			'user_email' => JText::_('COM_REDSHOP_USEREMAIL'),
			'phone' => JText::_('COM_REDSHOP_PHONE'),
			'quotation_status' => JText::_('COM_REDSHOP_QUOTATION_STATUS'),
			'quotation_note' => JText::_('COM_REDSHOP_QUOTATION_NOTE'),
			'product_name' => JText::_('COM_REDSHOP_PRODUCT_NAME'),
			'product_final_price' => JText::_('COM_REDSHOP_PRODUCT_PRICE'),
			'product_attribute' => JText::_('COM_REDSHOP_PRODUCT_ATTRIBUTE')
		);
	}

	/**
	 * Method to get an array of data items.
	 *
	 * @return  mixed  An array of data items on success, false on failure.
	 */
	public function getItemsCsv()
	{
		// Get a storage key.
		$store = $this->getStoreId();

		// Try to load the data from internal storage.
		if (isset($this->cache[$store]))
		{
			return $this->cache[$store];
		}

		$app = JFactory::getApplication();
		$db = $this->getDbo();
		$query = $db->getQuery(true)
			->select(
				array(
					'q.*', 'uf.*', 'qi.quotation_item_id', 'qi.product_name', 'qi.product_final_price', 'qi.product_id',
					'(CONCAT_WS(' . $db->q(' ') . ', uf.firstname, uf.lastname)) AS full_name'
				)
			)
			->from($db->qn('#__redshop_quotation', 'q'))
			->leftjoin($db->qn('#__redshop_users_info', 'uf') . ' ON q.user_id = uf.user_id AND uf.address_type = ' . $db->q('BT'))
			->leftJoin($db->qn('#__redshop_quotation_item', 'qi') . ' ON qi.quotation_id = q.quotation_id');

		if ($filter = $this->getState('filter'))
		{
			$query->where('(uf.firstname LIKE ' . $db->q('%' . $filter . '%') . ' OR uf.lastname LIKE ' . $db->q('%' . $filter . '%') . ')');
		}

		if ($filterStatus = $this->getState('filter_status'))
		{
			$query->where('q.quotation_status = ' . $db->q($filterStatus));
		}

		$filterOrder = $app->getUserStateFromRequest($this->_context . 'filter_order', 'filter_order', 'quotation_cdate');
		$filterOrderDir = $app->getUserStateFromRequest($this->_context . 'filter_order_Dir', 'filter_order_Dir', 'DESC');

		$query->order($db->qn($db->escape($filterOrder)) . ' ' . $db->escape($filterOrderDir));

		$items = $this->_getList($query);

		// Check for a database error.
		if ($this->_db->getErrorNum())
		{
			$this->setError($this->_db->getErrorMsg());

			return false;
		}

		if ($items)
		{
			$quotationHelper = new quotationHelper;
			$productHelper = new producthelper;

			foreach ($items as $key => $item)
			{
				$items[$key]->quotation_status = $quotationHelper->getQuotationStatusName($item->quotation_status);
				$items[$key]->product_final_price = $productHelper->getProductFormattedPrice($item->product_final_price);

				$productAttribute = $productHelper->makeAttributeQuotation($item->quotation_item_id, 0, $item->product_id);
				$productAttribute = preg_replace('#<[^>]+>#', ' ', $productAttribute);
				$items[$key]->product_attribute = $productAttribute;
			}
		}

		// Add the items to the internal cache.
		$this->cache[$store] = $items;

		return $this->cache[$store];
	}

	/**
	 * Method to get a JDatabaseQuery object for retrieving the data set from a database.
	 *
	 * @return  JDatabaseQuery   A JDatabaseQuery object to retrieve the data set.
	 */
	public function getListQuery()
	{
		return $this->_buildQuery();
	}

	/**
	 * Method to get an array of data items.
	 *
	 * @return  mixed  An array of data items on success, false on failure.
	 */
	public function getItems()
	{
		if ($this->getState('streamOutput', '') == 'csv')
		{
			return $this->getItemsCsv();
		}
		else
		{
			return parent::getItems();
		}
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

	public function _buildQuery()
	{
		$where = "";

		$filter = $this->getState('filter');
		$filter_status = $this->getState('filter_status');

		if ($filter)
		{
			$where .= " AND (uf.firstname LIKE '%" . $filter . "%' OR uf.lastname LIKE '%" . $filter . "%')";
		}
		if ($filter_status != 0)
		{
			$where .= " AND q.quotation_status ='" . $filter_status . "' ";
		}
		$orderby = $this->_buildContentOrderBy();

		$query = "SELECT q.* FROM " . $this->_table_prefix . "quotation AS q "
			. "LEFT JOIN " . $this->_table_prefix . "users_info AS uf ON q.user_id=uf.user_id "
			. "WHERE uf.address_type Like 'BT' "
			. $where
			. "UNION SELECT q.* FROM " . $this->_table_prefix . "quotation AS q WHERE q.user_id=0 "
			. $orderby;

		return $query;
	}

	public function _buildContentOrderBy()
	{
		$db  = JFactory::getDbo();
		$app = JFactory::getApplication();

		$filter_order = $app->getUserStateFromRequest($this->_context . 'filter_order', 'filter_order', 'quotation_cdate');
		$filter_order_Dir = $app->getUserStateFromRequest($this->_context . 'filter_order_Dir', 'filter_order_Dir', 'DESC');

		$orderby = " ORDER BY " . $db->escape($filter_order . " " . $filter_order_Dir);

		return $orderby;
	}
}
