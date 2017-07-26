<?php
/**
 * @package     RedShop
 * @subpackage  Libraries
 *
 * @copyright   Copyright (C) 2008 - 2017 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

namespace Redshop\Environment;

/**
 * @package     Redshop\Environment
 *
 * @since       version
 */
class Helper
{
	protected static function getDetector()
	{
		static $detector;

		if (empty($detector))
		{
			$detector = new \Mobile_Detect;
		}

		return $detector;
	}

	/**
	 *
	 * @return  boolean
	 *
	 * @since   2.0.7
	 */
	public static function isIe()
	{
		return (bool) self::getDetector()->is('IE');
	}

	/**
	 *
	 * @return  boolean
	 *
	 * @since   2.0.7
	 */
	public static function isOpera()
	{
		return (bool) self::getDetector()->is('IE');
	}
}
