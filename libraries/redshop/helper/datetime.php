<?php
/**
 * @package     RedSHOP.Library
 * @subpackage  Helper
 *
 * @copyright   Copyright (C) 2008 - 2016 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 *
 * @since       2.0.3
 */

defined('_JEXEC') or die;

/**
 * Class Redshop Helper for Datetime
 *
 * @since  2.0.3
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

	/**
	 * Method to convert date according to format
	 *
	 * @param   int  $date  Date time (Unix format).
	 *
	 * @return  string
	 *
	 * @since    2.0.3
	 */
	public static function convertDateFormat($date = 0)
	{
		if ($date <= 0)
		{
			$date = time();
		}

		$format = Redshop::getConfig()->get('DEFAULT_DATEFORMAT', 'Y-m-d');
		$convertFormat = date($format, $date);

		if (strpos($format, "M") !== false)
		{
			$convertFormat = str_replace("Jan", JText::_('COM_REDSHOP_JAN'), $convertFormat);
			$convertFormat = str_replace("Feb", JText::_('COM_REDSHOP_FEB'), $convertFormat);
			$convertFormat = str_replace("Mar", JText::_('COM_REDSHOP_MAR'), $convertFormat);
			$convertFormat = str_replace("Apr", JText::_('COM_REDSHOP_APR'), $convertFormat);
			$convertFormat = str_replace("May", JText::_('COM_REDSHOP_MAY'), $convertFormat);
			$convertFormat = str_replace("Jun", JText::_('COM_REDSHOP_JUN'), $convertFormat);
			$convertFormat = str_replace("Jul", JText::_('COM_REDSHOP_JUL'), $convertFormat);
			$convertFormat = str_replace("Aug", JText::_('COM_REDSHOP_AUG'), $convertFormat);
			$convertFormat = str_replace("Sep", JText::_('COM_REDSHOP_SEP'), $convertFormat);
			$convertFormat = str_replace("Oct", JText::_('COM_REDSHOP_OCT'), $convertFormat);
			$convertFormat = str_replace("Nov", JText::_('COM_REDSHOP_NOV'), $convertFormat);
			$convertFormat = str_replace("Dec", JText::_('COM_REDSHOP_DEC'), $convertFormat);
		}

		if (strpos($format, "F") !== false)
		{
			$convertFormat = str_replace("January", JText::_('COM_REDSHOP_JANUARY'), $convertFormat);
			$convertFormat = str_replace("February", JText::_('COM_REDSHOP_FEBRUARY'), $convertFormat);
			$convertFormat = str_replace("March", JText::_('COM_REDSHOP_MARCH'), $convertFormat);
			$convertFormat = str_replace("April", JText::_('COM_REDSHOP_APRIL'), $convertFormat);
			$convertFormat = str_replace("May", JText::_('COM_REDSHOP_MAY'), $convertFormat);
			$convertFormat = str_replace("June", JText::_('COM_REDSHOP_JUNE'), $convertFormat);
			$convertFormat = str_replace("July", JText::_('COM_REDSHOP_JULY'), $convertFormat);
			$convertFormat = str_replace("August", JText::_('COM_REDSHOP_AUGUST'), $convertFormat);
			$convertFormat = str_replace("September", JText::_('COM_REDSHOP_SEPTEMBER'), $convertFormat);
			$convertFormat = str_replace("October", JText::_('COM_REDSHOP_OCTOBER'), $convertFormat);
			$convertFormat = str_replace("November", JText::_('COM_REDSHOP_NOVEMBER'), $convertFormat);
			$convertFormat = str_replace("December", JText::_('COM_REDSHOP_DECEMBER'), $convertFormat);
		}

		if (strpos($format, "D") !== false)
		{
			$convertFormat = str_replace("Mon", JText::_('COM_REDSHOP_MON'), $convertFormat);
			$convertFormat = str_replace("Tue", JText::_('COM_REDSHOP_TUE'), $convertFormat);
			$convertFormat = str_replace("Wed", JText::_('COM_REDSHOP_WED'), $convertFormat);
			$convertFormat = str_replace("Thu", JText::_('COM_REDSHOP_THU'), $convertFormat);
			$convertFormat = str_replace("Fri", JText::_('COM_REDSHOP_FRI'), $convertFormat);
			$convertFormat = str_replace("Sat", JText::_('COM_REDSHOP_SAT'), $convertFormat);
			$convertFormat = str_replace("Sun", JText::_('COM_REDSHOP_SUN'), $convertFormat);
		}

		if (strpos($format, "l") !== false)
		{
			$convertFormat = str_replace("Monday", JText::_('COM_REDSHOP_MONDAY'), $convertFormat);
			$convertFormat = str_replace("Tuesday", JText::_('COM_REDSHOP_TUESDAY'), $convertFormat);
			$convertFormat = str_replace("Wednesday", JText::_('COM_REDSHOP_WEDNESDAY'), $convertFormat);
			$convertFormat = str_replace("Thursday", JText::_('COM_REDSHOP_THURSDAY'), $convertFormat);
			$convertFormat = str_replace("Friday", JText::_('COM_REDSHOP_FRIDAY'), $convertFormat);
			$convertFormat = str_replace("Saturday", JText::_('COM_REDSHOP_SATURDAY'), $convertFormat);
			$convertFormat = str_replace("Sunday", JText::_('COM_REDSHOP_SUNDAY'), $convertFormat);
		}

		return $convertFormat;
	}
}
