<?php
/**
 * @package     RedSHOP.Library
 * @subpackage  Helper
 *
 * @copyright   Copyright (C) 2008 - 2016 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

defined('_JEXEC') or die;

use Behat\Transliterator\Transliterator;

/**
 * Utility functions for redSHOP
 *
 * @since  1.5
 */
class RedshopHelperUtility
{
	/**
	 * The dispatcher.
	 *
	 * @var  JEventDispatcher
	 */
	public static $dispatcher = null;

	/**
	 * Get SSL link for backend or applied for ssl link
	 *
	 * @param   string   $link      Link to be converted into ssl
	 * @param   integer  $applySSL  SSL should be apply or not
	 *
	 * @return  string   Return converted
	 */
	public static function getSSLLink($link, $applySSL = 1)
	{
		$link = JUri::getInstance(JUri::base() . $link);

		if (Redshop::getConfig()->get('SSL_ENABLE_IN_BACKEND') && $applySSL)
		{
			$link->setScheme('https');
		}
		else
		{
			$link->setScheme('http');
		}

		return $link;
	}

	/**
	 * Get the event dispatcher
	 *
	 * @return  JEventDispatcher
	 */
	public static function getDispatcher()
	{
		if (!self::$dispatcher)
		{
			self::$dispatcher = version_compare(JVERSION, '3.0', 'lt') ? JDispatcher::getInstance() : JEventDispatcher::getInstance();
		}

		return self::$dispatcher;
	}

	/**
	 * Quote an array of values.
	 *
	 * @param   array  $values  The values.
	 *
	 * @return  array  The quoted values
	 */
	public static function quote(array $values)
	{
		$db = JFactory::getDbo();

		return array_map(
			function ($value) use ($db) {
				return $db->quote($value);
			},
			$values
		);
	}

	/**
	 * Method for convert utf8 string with special chars to normal ASCII char.
	 *
	 * @param   string   $text         String for convert
	 * @param   boolean  $isUrlEncode  Target for convert. True for url alias, False for normal.
	 *
	 * @return  string         Normal ASCI string.
	 *
	 * @since  __DEPLOY_VERSION__
	 */
	public static function convertToNonSymbol($text = '', $isUrlEncode = true)
	{
		if (empty($text))
		{
			return '';
		}

		if ($isUrlEncode === false)
		{
			return Transliterator::utf8ToAscii($text);
		}

		return Transliterator::transliterate($text);
	}
}
