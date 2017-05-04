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
 * @subpackage  Models.Statistic Quotation
 * @since       2.0.0.2
 */
class RedshopModelStatistic_Quotation extends RedshopModelList
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
	 * get Quotation data for statistic
	 *
	 * @return  object.
	 *
	 * @since   2.0.0.3
	 */
	public function getQuotations()
	{
		$format = $this->getDateFormat();
		$db     = $this->getDbo();
		$query  = $db->getQuery(true)
			->select('FROM_UNIXTIME(quotation_cdate,"' . $format . '") AS viewdate')
			->select('SUM(quotation_total) AS quotation_total')
			->select('COUNT(*) AS count')
			->from($db->qn('#__redshop_quotation'))
			->where($db->qn('quotation_status') . ' = 5')
			->order($db->qn('quotation_cdate') . ' DESC')
			->group($db->qn('viewdate'));

		if (!empty($this->filterStartDate) && !empty($this->filterEndDate))
		{
			$query->where($db->qn('quotation_cdate') . ' > ' . $db->q(strtotime($this->filterStartDate)))
			->where($db->qn('quotation_cdate') . ' <= ' . $db->q(strtotime($this->filterEndDate) + 86400));
		}

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
