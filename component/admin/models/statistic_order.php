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
 * @subpackage  Models.Statistic Order
 * @since       2.0.0.2
 */
class RedshopModelStatistic_Order extends RedshopModelList
{
	/**
	 * Constructor
	 *
	 * @deprecated  2.0.0.3
	 */
	public function __construct()
	{
		parent::__construct();
		$input                 = JFactory::getApplication()->input;
		$this->filterStartDate = $input->getString('filter_start_date', '');
		$this->filterEndDate   = $input->getString('filter_end_date', '');
		$this->filterDateLabel = $input->getString('filter_date_label', '');
	}

	/**
	 * get Order data for statistic
	 *
	 * @return  object.
	 *
	 * @since   2.0.0.3
	 */
	public function getOrders()
	{
		$format = $this->getDateFormat();
		$db     = $this->getDbo();
		$query = $db->getQuery(true)
			->select('FROM_UNIXTIME(cdate,"' . $format . '") AS viewdate')
			->select('SUM(order_total) AS order_total')
			->select('COUNT(*) AS count')
			->from($db->qn('#__redshop_orders'))
			->order($db->qn('cdate') . ' DESC')
			->group($db->qn('viewdate'));

		if (!empty($this->filterStartDate) && !empty($this->filterEndDate))
		{
			$query->where($db->qn('cdate') . ' > ' . $db->q(strtotime($this->filterStartDate)))
			->where($db->qn('cdate') . ' <= ' . $db->q(strtotime($this->filterEndDate) + 86400));
		}

		return $db->setQuery($query)->loadObjectList();
	}

	/**
	 * get Order data for export
	 *
	 * @return  object.
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
			->order($db->qn('o.order_id') . ' DESC');

		if (!empty($this->filterStartDate) && !empty($this->filterEndDate))
		{
			$query->where($db->qn('o.cdate') . ' > ' . $db->q(strtotime($this->filterStartDate)))
				->where($db->qn('o.cdate') . ' <= ' . $db->q(strtotime($this->filterEndDate) + 86400));
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
	 * @return  object.
	 *
	 * @since   2.0.0.3
	 */
	public function getDateFormat()
	{
		$return = "";
		$startDate = strtotime($this->filterStartDate);
		$endDate = strtotime($this->filterEndDate);
		$interval = $endDate - $startDate;

		if ($interval == 0 && ($this->filterDateLabel == 'Today' || $this->filterDateLabel == 'Yesterday'))
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

		return $return;
	}
}
