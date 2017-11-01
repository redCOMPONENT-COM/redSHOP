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
 * @subpackage  Models.Statistic Order
 * @since       2.0.0.2
 */
class RedshopModelStatistic_Order extends RedshopModelList
{
	/**
	 * Construct class
	 *
	 * @param   array  $config  An optional associative array of configuration settings.
	 *
	 * @since   2.x
	 */
	public function __construct($config = array())
	{
		parent::__construct();

		if (empty($config['filter_fields']))
		{
			$config['filter_fields'] = array(
				'viewdate',
				'orderdate',
				'count',
				'order_total'
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
		$id .= ':' . $this->getState('filter.date_group');

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
	protected function populateState($ordering = 'cdate', $direction = 'asc')
	{
		$dateRange = $this->getUserStateFromRequest($this->context . '.filter.date_range', 'filter_date_range');
		$this->setState('filter.date_range', $dateRange);

		$dateGroup = $this->getUserStateFromRequest($this->context . '.filter.date_group', 'filter_date_group');
		$this->setState('filter.date_group', $dateGroup);

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
		$query = $db->getQuery(true)
			->select('FROM_UNIXTIME(cdate, "' . $format . '") AS viewdate')
			->select('FROM_UNIXTIME(cdate, "%Y%m%d") AS orderdate')
			->select('SUM(order_total) AS order_total')
			->select('COUNT(*) AS count')
			->from($db->qn('#__redshop_orders'))
			->where($db->qn('order_payment_status') . ' = ' . $db->quote('Paid'))
			->group($db->qn('viewdate'));

		// Filter: Date Range
		$filterDateRange = $this->state->get('filter.date_range', '');

		if (!empty($filterDateRange))
		{
			$filterDateRange = explode('-', $filterDateRange);

			$startDate = (isset($filterDateRange[0])) ? (int) $filterDateRange[0] : '';
			$endDate   = (isset($filterDateRange[1])) ? (int) $filterDateRange[1] : '';

			$query->where($db->qn('cdate') . ' >= ' . $startDate)
				->where($db->qn('cdate') . ' <= ' . $endDate);
		}

		// Add the list ordering clause.
		$orderCol = $this->state->get('list.ordering', 'cdate');
		$orderDirn = $this->state->get('list.direction', 'asc');

		$query->order($db->escape($orderCol . ' ' . $orderDirn));

		return $query;
	}

	/**
	 * get Order data for export
	 *
	 * @return  array.
	 *
	 * @since   2.0.0.3
	 */
	public function exportOrder()
	{
		$db = $this->getDbo();
		$query = $db->getQuery(true)
			->select('DISTINCT(o.cdate)')
			->select('o.*')
			->select('ouf.*')
			->from($db->qn('#__redshop_orders', 'o'))
			->leftjoin($db->qn('#__redshop_order_users_info', 'ouf') . ' ON ' . $db->qn('o.order_id') . ' = ' . $db->qn('ouf.order_id'))
			->where($db->qn('ouf.address_type') . ' = ' . $db->q('BT'))
			->where($db->qn('o.order_payment_status') . ' = ' . $db->quote('Paid'))
			->order($db->qn('o.order_id') . ' DESC');

		// Filter: Date Range
		$filterDateRange = JFactory::getApplication()->input->getString('date_range', "");

		if (!empty($filterDateRange))
		{
			$filterDateRange = explode('-', $filterDateRange);

			$startDate = (isset($filterDateRange[0])) ? (int) $filterDateRange[0] : '';
			$endDate   = (isset($filterDateRange[1])) ? (int) $filterDateRange[1] : '';

			$query->where($db->qn('o.cdate') . ' >= ' . $startDate)
				->where($db->qn('o.cdate') . ' <= ' . $endDate);
		}

		return $this->_getList($query);
	}

	/**
	 * Count product by order
	 *
	 * @return  object.
	 *
	 * @since   2.0.0.3
	 */
	public function countProductByOrder()
	{
		$db = $this->getDbo();
		$query = $db->getQuery(true)
			->select($db->qn('order_id'))
			->select('COUNT(order_item_id) AS noproduct')
			->from($db->qn('#__redshop_order_item'))
			->group($db->qn('order_id'));

		return $db->setQuery($query)->loadObjectList();
	}

	/**
	 * get date Format for new statistic
	 *
	 * @return  string.
	 *
	 * @since   2.0.0.3
	 */
	public function getDateFormat()
	{
		$startDate = 0;
		$endDate = 0;
		$filterDateRange = $this->state->get('filter.date_range', '');
		$filterDateGroup = $this->state->get('filter.date_group', '');

		if (!empty($filterDateRange))
		{
			$filterDateRange = explode('-', $filterDateRange);

			$startDate = (isset($filterDateRange[0])) ? (int) $filterDateRange[0] : '';
			$endDate   = (isset($filterDateRange[1])) ? (int) $filterDateRange[1] : '';
		}

		if ($filterDateGroup == 3)
		{
			return '%Y';
		}
		elseif ($filterDateGroup == 2)
		{
			return '%M %Y';
		}
		elseif ($filterDateGroup == 1)
		{
			return JText::_('COM_REDSHOP_WEEK') . ' %v - %x';
		}
		else
		{
			return '%d %M %Y';
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
			if ($filterDateGroup == 1)
			{
				$return = "%d %b. %Y";
			}
			else
			{
				$return = "%b. %Y";
			}
		}
		elseif ($interval <= 31536000)
		{
			if ($filterDateGroup == 1)
			{
				$return = "%d %b. %Y";
			}
			elseif ($filterDateGroup == 2)
			{
				$return = "%b. %Y";
			}
			else
			{
				$return = "%Y";
			}
		}
		else
		{
			$return = "%d %b %Y";
		}

		return $return;
	}
}
