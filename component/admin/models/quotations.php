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
 * Model Quotations
 *
 * @package     RedSHOP.Backend
 * @subpackage  Model
 * @since       __DEPLOY_VERSION__
 */
class RedshopModelQuotations extends RedshopModelList
{
	/**
	 * Name of the filter form to load
	 *
	 * @var  string
	 */
	protected $filterFormName = 'filter_quotations';

	/**
	 * Construct class
	 *
	 * @since 1.x
	 */

	public function __construct()
	{
		if (empty($config['filter_fields']))
		{
			$config['filter_fields'] = array(
				'q.quotation_cdate', 'quotation_cdate',
				'id', 'q.id',
				'number', 'q.number',
				'status', 'q.status',
				'total', 'q.total'
			);
		}

		parent::__construct($config);
	}

	/**
	 * Method to auto-populate the model state.
	 *
	 * Note. Calling getState in this method will result in recursion.
	 *
	 * @param   string  $ordering   An optional ordering field.
	 * @param   string  $direction  An optional direction (asc|desc).
	 *
	 * @return  void
	 *
	 * @since   1.6
	 */
	protected function populateState($ordering = 'q.quotation_cdate', $direction = 'asc')
	{
		$app = JFactory::getApplication();

		$this->setState('filter.search', $app->getUserState($this->context . 'filter.search'));
		$this->setState('filter.status', $app->getUserState($this->context . 'filter.status'));

		// List state information.
		parent::populateState($ordering, $direction);
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
	 * @since   1.6
	 */
	protected function getStoreId($id = '')
	{
		// Compile the store id.
		$id .= ':' . $this->getState('filter.search');
		$id .= ':' . $this->getState('filter.status');

		return parent::getStoreId($id);
	}

	/**
	 * Method to build an SQL query to load the list data.
	 *
	 * @return  JDatabaseQuery  An SQL query
	 */
	public function getListQuery()
	{
		$db    = $this->_db;
		$query = $db->getQuery(true)
			->select('q.*')
			->from($db->qn('#__redshop_quotation', 'q'))
			->leftJoin($db->qn('#__redshop_users_info', 'uf') . ' ON ' . $db->qn('q.user_id') . ' = ' . $db->qn('uf.user_id'))
			->where('(' . $db->qn('uf.address_type') . ' = ' . $db->q('BT') . ' OR ' . $db->qn('q.user_id') . ' = 0)')
			->group($db->qn('q.id'));

		$search = $this->getState('filter.search', null);

		if (!empty($search))
		{
			$query->where('('
				. $db->qn('uf.firstname') . ' LIKE ' . $db->q('%' . $search . '%')
				. ' OR ' . $db->qn('uf.lastname') . ' LIKE ' . $db->q('%' . $search . '%') . ')');
		}

		// Filter: Order status
		$status = $this->getState('filter.status');

		if (!empty($status))
		{
			$query->where($db->qn('q.status') . ' = ' . $db->quote($status));
		}

		$filterOrder    = $this->getState('list.ordering', 'q.quotation');
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

		return parent::getItems();
	}

	/**
	 * Get the columns for the csv file.
	 *
	 * @return  array  An associative array of column names as key and the title as value.
	 */
	public function getCsvColumns()
	{
		return array(
			'id'                  => JText::_('COM_REDSHOP_QUOTATION_ID'),
			'full_name'           => JText::_('COM_REDSHOP_FULLNAME'),
			'user_email'          => JText::_('COM_REDSHOP_USEREMAIL'),
			'phone'               => JText::_('COM_REDSHOP_PHONE'),
			'status'              => JText::_('COM_REDSHOP_QUOTATION_STATUS'),
			'note'                => JText::_('COM_REDSHOP_QUOTATION_NOTE'),
			'product_name'        => JText::_('COM_REDSHOP_PRODUCT_NAME'),
			'product_final_price' => JText::_('COM_REDSHOP_PRODUCT_PRICE'),
			'product_attribute'   => JText::_('COM_REDSHOP_PRODUCT_ATTRIBUTE')
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

		$db    = $this->getDbo();
		$query = $db->getQuery(true)
			->select(
				array(
					'q.*', 'uf.*', 'qi.quotation_item_id', 'qi.product_name', 'qi.product_final_price', 'qi.product_id',
					'(CONCAT_WS(' . $db->q(' ') . ', uf.firstname, uf.lastname)) AS full_name'
				)
			)
			->from($db->qn('#__redshop_quotation', 'q'))
			->leftJoin($db->qn('#__redshop_users_info', 'uf') . ' ON q.user_id = uf.user_id AND uf.address_type = ' . $db->q('BT'))
			->leftJoin($db->qn('#__redshop_quotation_item', 'qi') . ' ON qi.quotation_id = q.id');

		if ($filter = $this->getState('filter'))
		{
			$query->where('(uf.firstname LIKE ' . $db->q('%' . $filter . '%') . ' OR uf.lastname LIKE ' . $db->q('%' . $filter . '%') . ')');
		}

		if ($filterStatus = $this->getState('filter_status'))
		{
			$query->where('q.status = ' . $db->q($filterStatus));
		}

		$filterOrder    = $this->getState('list.ordering', 'q.quotation_cdate');
		$filterOrderDir = $this->getState('list.direction', 'desc');

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
			$productHelper   = productHelper::getInstance();

			foreach ($items as $key => $item)
			{
				$items[$key]->status              = RedshopHelperQuotation::getQuotationStatusName($item->quotation_status);
				$items[$key]->product_final_price = $productHelper->getProductFormattedPrice($item->product_final_price);

				$productAttribute               = $productHelper->makeAttributeQuotation($item->quotation_item_id, 0, $item->product_id);
				$productAttribute               = preg_replace('#<[^>]+>#', ' ', $productAttribute);
				$items[$key]->product_attribute = $productAttribute;
			}
		}

		// Add the items to the internal cache.
		$this->cache[$store] = $items;

		return $this->cache[$store];
	}
}
