<?php
/**
 * @package     RedSHOP.Backend
 * @subpackage  Model
 *
 * @copyright   Copyright (C) 2008 - 2017 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

defined('_JEXEC') or die;

class RedshopModelQuotation extends RedshopModelList
{
	/**
	 * Constructor.
	 *
	 * @param   array  $config  An optional associative array of configuration settings.
	 *
	 * @since   1.6
	 * @see     JController
	 */
	public function __construct($config = array())
	{
		if (empty($config['filter_fields']))
		{
			$config['filter_fields'] = array(
				'q.quotation_cdate', 'quotation_cdate',
				'quotation_id', 'quotation_number',
				'quotation_status', 'quotation_total'
			);
		}

		parent::__construct($config);
	}

	/**
	 * Method to get a store id based on model configuration state.
	 *
	 * This is necessary because the model is used by the component and
	 * different modules that might need different sets of data or different
	 * ordering requirements.
	 *
	 * @param   string  $id  A prefix for the store id.
	 *
	 * @return  string  A store id.
	 *
	 * @since   1.5
	 */
	protected function getStoreId($id = '')
	{
		// Compile the store id.
		$id .= ':' . $this->getState('filter');
		$id .= ':' . $this->getState('filter_status');

		return parent::getStoreId($id);
	}

	/**
	 * Method to auto-populate the model state.
	 *
	 * @param   string  $ordering   An optional ordering field.
	 * @param   string  $direction  An optional direction (asc|desc).
	 *
	 * @return  void
	 *
	 * @note    Calling getState in this method will result in recursion.
	 */
	protected function populateState($ordering = 'q.quotation_cdate', $direction = 'desc')
	{
		$filter_status = $this->getUserStateFromRequest($this->context . 'filter_status', 'filter_status', 0);
		$filter = $this->getUserStateFromRequest($this->context . 'filter', 'filter', '');

		$this->setState('filter', $filter);
		$this->setState('filter_status', $filter_status);

		parent::populateState($ordering, $direction);
	}

	/**
	 * Get the columns for the csv file.
	 *
	 * @return  array  An associative array of column names as key and the title as value.
	 */
	public function getCsvColumns()
	{
		$result = array(
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

		JPluginHelper::importPlugin('redshop_quotation');
		RedshopHelperUtility::getDispatcher()->trigger('getQuotationColumn', array(&$result));

		return $result;
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

		$filterOrder = $this->getState('list.ordering', 'q.quotation_cdate');
		$filterOrderDir = $this->getState('list.direction', 'desc');

		$query->order($db->qn($db->escape($filterOrder)) . ' ' . $db->escape($filterOrderDir));

		JPluginHelper::importPlugin('redshop_quotation');
		RedshopHelperUtility::getDispatcher()->trigger('getQuotationItem', array(&$query));

		$items = $this->_getList($query);

		// Check for a database error.
		if ($this->_db->getErrorNum())
		{
			$this->setError($this->_db->getErrorMsg());

			return false;
		}

		if ($items)
		{
			$quotationHelper = quotationHelper::getInstance();
			$productHelper = productHelper::getInstance();

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
		$db = JFactory::getDbo();
		$query = $db->getQuery(true)
			->select('q.*')
			->from($db->qn('#__redshop_quotation', 'q'))
			->leftJoin($db->qn('#__redshop_users_info', 'uf') . ' ON q.user_id = uf.user_id')
			->where('(uf.address_type = ' . $db->q('BT') . ' OR q.user_id = 0)')
			->group('q.quotation_id');

		$filter = $this->getState('filter');
		$filter_status = $this->getState('filter_status');

		if ($filter)
		{
			$query->where('(uf.firstname LIKE ' . $db->q('%' . $filter . '%') . ' OR uf.lastname LIKE ' . $db->q('%' . $filter . '%') . ')');
		}

		if ($filter_status != 0)
		{
			$query->where('q.quotation_status = ' . $db->q($filter_status));
		}

		$filterOrder = $this->getState('list.ordering', 'q.quotation_cdate');
		$filterOrderDir = $this->getState('list.direction', 'desc');

		$query->order($db->qn($db->escape($filterOrder)) . ' ' . $db->escape($filterOrderDir));

		return $query;
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
}
