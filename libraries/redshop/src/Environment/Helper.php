<?php
/**
 * @package     Redshop\Environment
 * @subpackage
 *
 * @copyright   A copyright
 * @license     A "Slug" license name e.g. GPL2
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
