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
 * Redshop statistics Model
 *
 * @package     Redshop.Backend
 * @subpackage  Models.Statistic Product
 * @since       2.0.0.2
 */
class RedshopModelStatistic_Product extends RedshopModelList
{
	/**
	 * Name of the filter form to load
	 *
	 * @var  string
	 */
	protected $filterFormName = 'filter_statistic_product';

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
				'view_date',
				'p.product_name', 'product_name',
				'p.product_number', 'product_number',
				'count',
				'm.manufacturer_name', 'manufacturer_name'
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
	protected function populateState($ordering = 'p.publish_date', $direction = '')
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
		$format = $this->getDateFormat();
		$db     = $this->getDbo();
		$subQuery = $db->getQuery(true)
			->select('SUM(product_final_price) AS total_sale')
			->select('COUNT(*) AS unit_sold')
			->select($db->qn('product_id'))
			->from($db->qn('#__redshop_order_item'))
			->where($db->qn('order_status') . ' = ' . $db->q('S'))
			->group($db->qn('product_id'));

		$query = $db->getQuery(true)
			->select('DATE_FORMAT(p.publish_date,"' . $format . '") AS viewdate')
			->select('p.*')
			->select('COUNT(*) AS count')
			->select('m.manufacturer_name')
			->select($db->qn('oi.total_sale'))
			->select($db->qn('oi.unit_sold'))
			->from($db->qn('#__redshop_product', 'p'))
			->leftjoin($db->qn('#__redshop_manufacturer', 'm') . ' ON ' . $db->qn('m.manufacturer_id') . ' = ' . $db->qn('p.manufacturer_id'))
			->leftjoin('(' . $subQuery . ') AS oi ' . ' ON ' . $db->qn('oi.product_id') . ' = ' . $db->qn('p.product_id')
				)
			->group($db->qn('p.product_id'));

		// Filter: Date Range
		$filterDateRange = $this->state->get('filter.date_range', '');

		if (!empty($filterDateRange))
		{
			$filterDateRange = explode('-', $filterDateRange);

			$startDate = (isset($filterDateRange[0])) ? (int) $filterDateRange[0] : '';
			$endDate   = (isset($filterDateRange[1])) ? (int) $filterDateRange[1] : '';

			$query->where($db->qn('p.publish_date') . ' >= ' . date($startDate, 'Y-m-d H:i:s'))
				->where($db->qn('p.publish_date') . ' <= ' . date($endDate, 'Y-m-d H:i:s'));
		}

		// Add the list ordering clause.
		$orderCol = $this->state->get('list.ordering', 'p.publish_date');
		$orderDirn = $this->state->get('list.direction', 'desc');

		$query->order($db->escape($orderCol . ' ' . $orderDirn));

		return $query;
	}

	/**
	 * get date Format for new statistic
	 *
	 * @return  object.
	 *
	 * @since   2.0.0.3
	 */
	public function getDateFormat()
	{
		$return = "";
		$startDate = 0;
		$endDate = 0;
		$filterDateRange = $this->state->get('filter.date_range', '');

		if (!empty($filterDateRange))
		{
			$filterDateRange = explode('-', $filterDateRange);

			$startDate = (isset($filterDateRange[0])) ? (int) $filterDateRange[0] : '';
			$endDate   = (isset($filterDateRange[1])) ? (int) $filterDateRange[1] : '';
		}

		$interval = $endDate - $startDate;

		if ($interval == 86399)
		{
			$return = "%d %b %Y";
		}
		elseif ($interval <= 1209600)
		{
			$return = "%d %b. %Y";
		}
		elseif ($interval <= 7689600)
		{
			$return = "%b. %Y";
		}
		elseif ($interval <= 31536000)
		{
			$return = "%Y";
		}
		else
		{
			$return = "%d %b %Y";
		}

		return $return;
	}
}
