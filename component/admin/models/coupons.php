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
 * Model Coupons
 *
 * @package     RedSHOP.Backend
 * @subpackage  Model
 * @since       __DEPLOY_VERSION__
 */
class RedshopModelCoupons extends RedshopModelList
{
	/**
	 * Construct class
	 *
	 * @param   array  $config  An optional associative array of configuration settings.
	 *
	 * @since   __DEPLOY_VERSION__
	 */
	public function __construct($config = array())
	{
		if (empty($config['filter_fields']))
		{
			$config['filter_fields'] = array(
				'coupon_id', 'c.coupon_id',
				'coupon_code', 'c.coupon_code',
				'percent_or_total', 'c.percent_or_total',
				'coupon_value', 'c.coupon_value',
				'start_date', 'c.start_date',
				'end_date', 'c.end_date',
				'coupon_type', 'c.coupon_type',
				'userid', 'c.userid',
				'coupon_left', 'c.coupon_left',
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
	 * @since   __DEPLOY_VERSION__
	 */
	protected function populateState($ordering = 'c.coupon_id', $direction = 'asc')
	{
		$search = $this->getUserStateFromRequest($this->context . '.filter.search', 'filter_search');
		$this->setState('filter.search', $search);

		$type = $this->getUserStateFromRequest($this->context . '.filter.type', 'filter_type');
		$this->setState('filter.type', $type);

		$couponType = $this->getUserStateFromRequest($this->context . '.filter.coupon_type', 'filter_coupon_type');
		$this->setState('filter.coupon_type', $couponType);

		$filterPublished = $this->getUserStateFromRequest($this->context . '.filter.published', 'filter_published');
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
	 * @since   __DEPLOY_VERSION__
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
	 * @since   __DEPLOY_VERSION__
	 */
	public function getListQuery()
	{
		$db    = JFactory::getDbo();
		$query = $db->getQuery(true);

		$query->select('c.*')
			->from($db->qn('#__redshop_voucher', 'c'));

		// Filter by search in name.
		$search = $this->getState('filter.search');

		if (!empty($search))
		{
			$query->where($db->qn('c.coupon_code') . ' LIKE ' . $db->quote('%' . $search . '%'));
		}

		// Filter: type
		$filterType = $this->getState('filter.type', null);

		if ($filterType == 'percent')
		{
			$query->where($db->qn('percent_or_total') . ' = 1');
		}
		elseif ($filterType == 'total')
		{
			$query->where($db->qn('percent_or_total') . ' = 0');
		}

		// Filter: Coupon type
		$filterCouponType = $this->getState('filter.coupon_type');

		if (is_numeric($filterCouponType))
		{
			$query->where($db->qn('c.coupon_type') . ' = ' . (int) $filterCouponType);
		}
		elseif ($filterCouponType === '')
		{
			$query->where($db->qn('c.coupon_type') . ' IN (0,1)');
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
		$orderCol       = $this->state->get('list.ordering', 'c.coupon_id');
		$orderDirection = $this->state->get('list.direction', 'asc');

		$query->order($db->escape($orderCol . ' ' . $orderDirection));

		return $query;
	}
}
