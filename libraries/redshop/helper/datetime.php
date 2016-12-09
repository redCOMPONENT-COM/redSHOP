<?php
/**
 * @package     RedSHOP.Library
 * @subpackage  Helper
 *
 * @copyright   Copyright (C) 2008 - 2016 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 *
 * @since       __DEPLOY_VERSION__
 */

defined('_JEXEC') or die;

/**
 * Class Redshop Helper for Datetime
 *
 * @since  __DEPLOY_VERSION__
 */
class RedshopHelperDatetime
{
	/**
	 * Generate timestamp to middle night or early morning
	 *
	 * @param   int   $inputTimestamp  Input timestamp
	 * @param   bool  $night           At middle night
	 *
	 * @return  int
	 */
	public static function generateTimestamp($inputTimestamp, $night = true)
	{
		// Convert to date string
		$date = date('Y-m-d', $inputTimestamp);

		if ($night)
		{
			$date = $date . ' 23:59:59';
			$date = new DateTime($date);
		}
		else
		{
			$date = $date . ' 00:00:01';
			$date = new DateTime($date);
		}

		return $date->getTimestamp();
	}
}
