<?php
/**
 * @package     RedShop
 * @subpackage  Model
 * @copyright   Copyright (C) 2008 - 2015 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

namespace Redshop\Model\Traits;

/**
 * Trait support start_date end_date
 *
 * @since    __DEPLOY_VERSION__
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
		$tz  = \JFactory::getConfig()->get('offset');
		$UTC = new \DateTimeZone('UTC');

		if (!empty($startDate) && !is_numeric($startDate))
		{
			$startDate = \JFactory::getDate($startDate, $tz)->setTimezone($UTC)->toUnix();
		}

		if (!empty($endDate) && !is_numeric($endDate))
		{
			$endDate = \JFactory::getDate($endDate, $tz)->setTimezone($UTC)->toUnix();
		}

		if ($startDate == $endDate)
		{
			$startDate = \RedshopHelperDatetime::generateTimestamp($startDate, false);
			$endDate   = \RedshopHelperDatetime::generateTimestamp($endDate, true);
		}
	}
}
