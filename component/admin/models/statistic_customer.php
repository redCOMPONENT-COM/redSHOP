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
 * Redshop statistics Model
 *
 * @package     Redshop.Backend
 * @subpackage  Models.Statistic Customer
 * @since       2.0.3
 */
class RedshopModelStatistic_Customer extends RedshopModelList
{
	/**
	 * Name of the filter form to load
	 *
	 * @var  string
	 */
	protected $filterFormName = 'filter_statistic_customer';

	/**
	 * constructor (registers additional tasks to methods)
	 *
	 * @param   array  $config  config params
	 */
	public function __construct($config = array())
	{
		parent::__construct();

		if (empty($config['filter_fields']))
		{
			$config['filter_fields'] = array(
				'customer_name',
				'count',
				'total_sale',
				'ui.user_email'
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
	 * @since   2.0.0.4
	 */
	protected function getStoreId($id = '')
	{
		$id .= ':' . $this->getState('filter.date_range');

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
	 * @since   2.0.0.4
	 * @note    Calling getState in this method will result in recursion.
	 */
	protected function populateState($ordering = 'ui.users_info_id', $direction = '')
	{
		$dateRange = $this->getUserStateFromRequest($this->context . '.filter.date_range', 'filter_date_range');
		$this->setState('filter.date_range', $dateRange);

		parent::populateState($ordering, $direction);
	}

	/**
	 * Method to buil query string
	 *
	 * @return  String
	 *
	 * @note    Calling getState in this method will result in recursion.
	 */
	public function getListQuery()
	{
		$db    = $this->getDbo();
		$query = $db->getQuery(true);

		$query->select($db->qn('ui.user_email'))
			->select('COUNT(' . $db->qn('o.order_id') . ') AS ' . $db->qn('count'))
			->select('SUM(' . $db->qn('o.order_total') . ') AS ' . $db->qn('total_sale'))
			->select('CONCAT(' . $db->qn('ui.firstname') . ',' . $db->quote(' ') . ',' . $db->qn('ui.lastname') . ') AS ' . $db->qn('customer_name'))
			->select($db->qn('ui.lastname'))
			->select($db->qn('ui.firstname'))
			->select($db->qn('ui.users_info_id'))
			->select($db->qn('ui.user_id'))
			->from($db->qn('#__redshop_users_info', 'ui'))
			->leftjoin($db->qn('#__users', 'u') . ' ON ' . $db->qn('u.id') . ' = ' . $db->qn('ui.user_id'))
			->where($db->qn('ui.address_type') . ' = ' . $db->q('BT'))
			->leftJoin(
				$db->qn('#__redshop_orders', 'o') . ' ON ' . $db->qn('o.user_info_id') . ' = ' . $db->qn('ui.users_info_id')
				. ' AND ' . $db->qn('o.order_payment_status') . ' = ' . $db->quote('Paid')
			);

		// Filter: Date Range
		$filterDateRange = $this->state->get('filter.date_range', '');

		if (!empty($filterDateRange))
		{
			$filterDateRange = explode('-', $filterDateRange);

			$startDate = (isset($filterDateRange[0])) ? (int) $filterDateRange[0] : '';
			$endDate   = (isset($filterDateRange[1])) ? (int) $filterDateRange[1] : '';

			$query->where($db->qn('o.cdate') . ' >= ' . $startDate)
				->where($db->qn('o.cdate') . ' <= ' . $endDate);
		}

		$query->group($db->qn('ui.users_info_id'));
		$query->having($db->qn('count') . ' > 0');

		// Add the list ordering clause.
		$orderCol = $this->state->get('list.ordering', 'ui.users_info_id');
		$orderDirn = $this->state->get('list.direction', 'asc');

		$query->order($db->escape($orderCol . ' ' . $orderDirn));

		return $query;
	}
}
