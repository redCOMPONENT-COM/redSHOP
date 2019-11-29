<?php
/**
 * @package     RedSHOP.Backend
 * @subpackage  Model
 *
 * @copyright   Copyright (C) 2008 - 2019 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

defined('_JEXEC') or die;

/**
 * Model Coupons
 *
 * @package     RedSHOP.Backend
 * @subpackage  Model
 * @since       2.1.0
 */
class RedshopModelCoupons extends RedshopModelList
{
	/**
	 * Construct class
	 *
	 * @param   array  $config  An optional associative array of configuration settings.
	 *
	 * @since   2.1.0
	 */
	public function __construct($config = array())
	{
		if (empty($config['filter_fields']))
		{
			$config['filter_fields'] = array(
				'id', 'c.id',
				'code', 'c.code',
				'type', 'c.type',
				'value', 'c.value',
				'start_date', 'c.start_date',
				'end_date', 'c.end_date',
				'effect', 'c.effect',
				'userid', 'c.userid',
				'amount_left', 'c.amount_left',
				'published', 'c.published',
				'subtotal', 'c.subtotal',
				'order_id', 'c.order_id',
				'free_shipping', 'c.free_shipping'
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
	 * @since   2.1.0
	 */
	protected function populateState($ordering = 'c.id', $direction = 'asc')
	{
		$search = $this->getUserStateFromRequest((string) $this->context . '.filter.search', 'filter_search');
		$this->setState('filter.search', $search);

		$type = $this->getUserStateFromRequest((string) $this->context . '.filter.type', 'filter_type');
		$this->setState('filter.type', $type);

		$couponType = $this->getUserStateFromRequest((string) $this->context . '.filter.coupon_type', 'filter_coupon_type');
		$this->setState('filter.coupon_type', $couponType);

		$filterPublished = $this->getUserStateFromRequest((string) $this->context . '.filter.published', 'filter_published');
		$this->setState('filter.published', $filterPublished);

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
	 * @since   2.1.0
	 */
	protected function getStoreId($id = '')
	{
		// Compile the store id.
		$id .= ':' . $this->getState('filter.search');
		$id .= ':' . $this->getState('filter.type');
		$id .= ':' . $this->getState('filter.coupon_type');
		$id .= ':' . $this->getState('filter.published');

		return parent::getStoreId($id);
	}

	/**
	 * Method to build an SQL query to load the list data.
	 *
	 * @return  JDatabaseQuery  An SQL query
	 *
	 * @since   2.1.0
	 */
	public function getListQuery()
	{
		$db    = JFactory::getDbo();
		$query = $db->getQuery(true);

		$query->select('c.*')
			->from($db->qn('#__redshop_coupons', 'c'));

		// Filter by search in name.
		$search = $this->getState('filter.search');

		if (!empty($search))
		{
			$query->where($db->qn('c.code') . ' LIKE ' . $db->quote('%' . $search . '%'));
		}

		// Filter: type
		$filterType = $this->getState('filter.type', null);

		if (is_numeric($filterType))
		{
			$query->where($db->qn('c.type') . ' = ' . $filterType);
		}
		else
		{
			$query->where($db->qn('c.type') . ' IN (0,1)');
		}

		// Filter: Effect
		$filterEffect = $this->getState('filter.effect');

		if (is_numeric($filterEffect))
		{
			$query->where($db->qn('c.effect') . ' = ' . (int) $filterEffect);
		}
		elseif ($filterEffect === '')
		{
			$query->where($db->qn('c.effect') . ' IN (0,1)');
		}

		// Filter: Published
		$filterPublished = $this->getState('filter.published');

		if (is_numeric($filterPublished))
		{
			$query->where($db->qn('c.published') . ' = ' . (int) $filterPublished);
		}
		elseif ($filterPublished === '')
		{
			$query->where($db->qn('c.published') . ' IN (0,1)');
		}

		// Add the list ordering clause.
		$orderCol       = $this->state->get('list.ordering', 'c.id');
		$orderDirection = $this->state->get('list.direction', 'asc');

		$query->order($db->escape($orderCol . ' ' . $orderDirection));

		return $query;
	}
}
