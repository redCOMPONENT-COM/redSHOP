<?php
/**
 * @package     RedShop
 * @subpackage  Model
 * @copyright   Copyright (C) 2008 - 2019 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

namespace Redshop\Model\Traits;

/**
 * Trait support start_date end_date
 *
 * @since    2.1.2
 */
trait HasDateTimeRange
{
	/**
	 * Handle start end date
	 *
	 * @param   string  $startDate  Start date
	 * @param   string  $endDate    End date
	 *
	 * @return void
	 */
	protected function handleDateTimeRange(&$startDate, &$endDate)
	{
		if (empty($startDate) && empty($endDate))
		{
			return;
		}

		$tz     = new \DateTimeZone(\JFactory::getConfig()->get('offset'));
		$UTC    = new \DateTimeZone('UTC');
		$format = \Redshop::getConfig()->get('DEFAULT_DATEFORMAT');

		if ($startDate == $endDate)
		{
			$startDate = date_create_from_format($format, $startDate, $tz)->setTime(0, 0, 0)->setTimezone($UTC)->getTimestamp();
			$endDate   = date_create_from_format($format, $endDate, $tz)->setTime(23, 59, 59)->setTimezone($UTC)->getTimestamp();

			return;
		}

		if (!empty($startDate) && !is_numeric($startDate))
		{
			$startDate = date_create_from_format($format, $startDate, $tz)->setTimezone($UTC)->getTimestamp();
		}

		if (!empty($endDate) && !is_numeric($endDate))
		{
			$endDate = date_create_from_format($format, $endDate, $tz)->setTimezone($UTC)->getTimestamp();
		}
	}
}
