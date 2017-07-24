<?php
/**
 * @package     RedShop
 * @subpackage  Plugin
 *
 * @copyright   Copyright (C) 2008 - 2017 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

namespace Redshop\String;

class Helper
{
	/**
	 *
	 * @return  string
	 *
	 * @since   2.0.7
	 */
	public static function getUserRandomString()
	{
		return md5(\JFactory::getUser()->id . time());
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
