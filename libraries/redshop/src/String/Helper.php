<?php
/**
 * @package     RedShop
 * @subpackage  Plugin
 *
 * @copyright   Copyright (C) 2008 - 2017 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

namespace Redshop\String;

/**
 * String helper class
 *
 * @package     Redshop\String
 *
 * @since       2.0.7
 */
class Helper
{
	/**
	 * @param   string   $key    Session key
	 * @param   boolean  $renew  Renew random string
	 *
	 * @return  string
	 *
	 * @since   2.0.7
	 */
	public static function getUserRandomStringByKey($key = 'default', $renew = false)
	{
		$session = \JFactory::getSession();
		$randomString = $session->get($key, false);

		// Generate new key
		if ($randomString === false || $renew === true)
		{
			$randomString = self::getUserRandomString();
			$session->set($key, $randomString);
		}

		return $randomString;
	}

	/**
	 *
	 * @return  string
	 *
	 * @since   2.0.7
	 */
	public static function getUserRandomString()
	{
		return md5(\JFactory::getUser()->id . time() . uniqid());
	}

	/**
	 * @param   float  $fileSize  Filesize
	 *
	 * @return  string
	 *
	 * @since   2.0.7
	 */
	public static function getFilesize($fileSize)
	{
		$size = \JText::_('COM_REDSHOP_FILESIZE_BYTES');

		// File size bytes larger than 10KB
		if ($fileSize >= 10240)
		{
			// Convert to KB
			$fileSize = $fileSize / 1024;

			$size = \JText::_('COM_REDSHOP_FILESIZE_KILOBYTES');
		}

		// File size bytes larger than 10MB
		if ($fileSize >= 10240)
		{
			// Convert to MB
			$fileSize = $fileSize / 1024;

			$size = \JText::_('COM_REDSHOP_FILESIZE_MEGABYTES');
		}

		return number_format(floatval($fileSize)) . ' ' . $size;
	}
}
