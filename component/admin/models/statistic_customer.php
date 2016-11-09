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
 * @subpackage  Models.Statistic Customer
 * @since       2.0.0.2
 */
class RedshopModelStatistic_Customer extends RedshopModelList
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
	 * get Customer data for statistic
	 *
	 * @return  object.
	 *
	 * @since   2.0.0.3
	 */
	public function getCustomers()
	{
		$format = $this->getDateFormat();
		$db     = $this->getDBo();
		$query  = $db->getQuery(true)
			->select('DATE_FORMAT(u.registerDate,"' . $format . '") AS viewdate')
			->select($db->qn('ui.user_email'))
			->select($db->qn('ui.firstname'))
			->select($db->qn('ui.lastname'))
			->select($db->qn('ui.users_info_id'))
			->select($db->qn('ui.user_id'))
			->from($db->qn('#__redshop_users_info', 'ui'))
			->leftjoin($db->qn('#__users', 'u') . ' ON ' . $db->qn('u.id') . ' = ' . $db->qn('ui.user_id'))
			->where($db->qn('ui.address_type') . ' = ' . $db->q('BT'))
			->order($db->qn('u.registerDate') . ' DESC')
			->group($db->qn('ui.users_info_id'));

		if (!empty($this->filterStartDate) && !empty($this->filterEndDate))
		{
			$query->where($db->qn('u.registerDate') . ' > ' . $db->q(date('Y-m-d H:i:s', strtotime($this->filterStartDate))))
				->where($db->qn('u.registerDate') . ' <= ' . $db->q(date('Y-m-d H:i:s', strtotime($this->filterEndDate) + 86400)));
		}

		$customers = $db->setQuery($query)->loadObjectList();

		$query = $db->getQuery(true)
			->select('COUNT(*) AS count')
			->select('SUM(order_total) AS total_sale')
			->select($db->qn('user_info_id'))
			->from($db->qn('#__redshop_orders'))
			->where($db->qn('order_payment_status') . ' = ' . $db->q('Paid'))
			->group($db->qn('user_info_id'));

		$orderCount = $db->setQuery($query)->loadObjectList();

		foreach ($customers as $key => $customer)
		{
			$customers[$key]->total_sale = 0;
			$customers[$key]->count = 0;

			foreach ($orderCount as $value)
			{
				if ($customer->users_info_id == $value->user_info_id)
				{
					$customers[$key]->total_sale = $value->total_sale;
					$customers[$key]->count = $value->count;
				}
			}
		}

		return $customers;
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
