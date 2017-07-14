<?php
/**
 * @package     RedSHOP.Backend
 * @subpackage  Model
 *
 * @copyright   Copyright (C) 2008 - 2017 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

defined('_JEXEC') or die;

/**
 * Model Suppliers
 *
 * @package     RedSHOP.Backend
 * @subpackage  Model
 * @since       2.0.4
 */
class RedshopModelVouchers extends RedshopModelList
{
	/**
	 * Name of the filter form to load
	 *
	 * @var  string
	 */
	protected $filterFormName = 'filter_vouchers';

	/**
	 * Construct class
	 *
	 * @since   __DEPLOY_VERSION__
	 */
	public function __construct()
	{
		if (empty($config['filter_fields']))
		{
			$config['filter_fields'] = array(
				'id', 'v.id',
				'code', 'v.code',
				'published', 'v.published',
				'type', 'v.type',
				'start_date', 'v.start_date',
				'end_date', 'v.end_date',
				'free_ship', 'v.free_ship',
				'voucher_left', 'v.voucher_left'
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
	protected function populateState($ordering = 'v.id', $direction = 'asc')
	{
		$search = $this->getUserStateFromRequest($this->context . '.filter.search', 'filter_search');
		$this->setState('filter.search', $search);

		$type = $this->getUserStateFromRequest($this->context . '.filter.type', 'filter_type');
		$this->setState('filter.type', $type);

		$freeShip = $this->getUserStateFromRequest($this->context . '.filter.free_ship', 'filter_free_ship');
		$this->setState('filter.free_ship', trim($freeShip));

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
		$id .= $this->getState('filter.type');
		$id .= $this->getState('filter.free_ship');

		return parent::getStoreId($id);
	}

	/**
	 * Method to build an SQL query to load the list data.
	 *
	 * @return      string  An SQL query
	 */
	public function getListQuery()
	{
		$db = JFactory::getDbo();
		$query = $db->getQuery(true);

		$query->select('v.*')
			->from($db->qn('#__redshop_voucher', 'v'));

		// Filter by search in name.
		$search = $this->getState('filter.search');

		if (!empty($search))
		{
			if (stripos($search, 'id:') === 0)
			{
				$query->where($db->qn('v.id') . ' = ' . (int) substr($search, 3));
			}
			else
			{
				$search = $db->q('%' . str_replace(' ', '%', $db->escape(trim($search), true) . '%'));
				$query->where($db->qn('code') . ' LIKE ' . $search);
			}
		}

		// Filter: type
		$filterType = $this->getState('filter.type', null);

		if (!empty($filterType))
		{
			$query->where($db->qn('type') . ' = ' . $db->quote($filterType));
		}

		// Filter: type
		$filterFreeShip = $this->getState('filter.free_ship');

		if (is_numeric($filterFreeShip))
		{
			$query->where($db->qn('free_ship') . ' = ' . (int) $filterFreeShip);
		}
		elseif ($filterFreeShip === '')
		{
			$query->where($db->qn('free_ship') . ' IN (0,1)');
		}

		// Add the list ordering clause.
		$orderCol  = $this->state->get('list.ordering', 'id');
		$orderDirn = $this->state->get('list.direction', 'asc');

		$query->order($db->escape($orderCol . ' ' . $orderDirn));

		return $query;
	}
}
