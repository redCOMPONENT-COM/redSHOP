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
 * Model Coupons
 *
 * @package     RedSHOP.Backend
 * @subpackage  Model
 * @since       2.0.0.4
 */

class RedshopModelCoupons extends RedshopModelList
{
	/**
	 * Name of the filter form to load
	 *
	 * @var  string
	 */
	protected $filterFormName = 'filter_coupons';

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
				'id',
				'coupon_code',
				'percent_or_total',
				'coupon_value',
				'start_date',
				'end_date',
				'coupon_type',
				'userid',
				'coupon_left',
				'published',
				'subtotal',
				'order_id',
				'free_shipping'
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
	protected function populateState($ordering = null, $direction = null)
	{
		$search = $this->getUserStateFromRequest($this->context . '.filter.search', 'filter_search');
		$this->setState('filter.search', $search);

		// List state information.
		parent::populateState('coupon_code', 'asc');
	}

	/**
	 * Method to build an SQL query to load the list data.
	 *
	 * @return      string  An SQL query
	 */

	public function getListQuery()
	{
		$filter = $this->getState('filter.search');
		$db = JFactory::getDbo();
		$where = '';

		if ($filter)
		{
			if ($filter == "Percentage" || $filter == "percentage")
			{
				$percentage = 1;
			}

			if ($filter == "Total" || $filter == "total")
			{
				$percentage = 0;
			}

			if ($filter == "User Specific" || $filter == "user specific")
			{
				$coupon_type = 1;
			}

			if ($filter == "Global" || $filter == "global")
			{
				$coupon_type = 0;
			}

			$where = $db->qn("coupon_code") . " LIKE " . $db->q("%" . $filter . "%");

			if (isset($percentage))
			{
				$where .= " OR " . $db->qn("percent_or_total") . " = '" . $db->q($percentage) . "'";
			}

			if (isset($coupon_type))
			{
				$where .= " OR " . $db->qn("coupon_type") . " ='" . $db->q($coupon_type) . "'";
			}
		}

		$query = $db->getQuery(true);
		$query->select($db->qn(['id', 'coupon_code', 'percent_or_total', 'coupon_value', 'published', 'userid', 'coupon_type', 'coupon_left', 'subtotal', 'order_id', 'free_shipping']))
			->from($db->qn('#__redshop_coupons'));

		if ($where)
		{
			$query->where($where);
		}

		$query->order($db->qn('id') . ' DESC');

		return $query;
	}
}
