<?php
/**
 * @package     RedShop
 * @subpackage  Libraries
 *
 * @copyright   Copyright (C) 2008 - 2019 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

namespace Redshop\Crypto\Helper;

defined('_JEXEC') or die;

/**
 * @package     Redshop\Crypto\Helper
 *
 * @since       2.1.0
 */
class Encrypt
{
	/**
	 * @param   integer  $pLength  Length
	 *
	 * @return  string
	 *
	 * @since   2.1.0
	 */
	public static function generateCustomRandomEncryptKey($pLength = 30)
	{
		// Generated a unique order number
		$charList = "abcdefghijklmnopqrstuvwxyz";
		$charList .= "1234567890123456789012345678901234567890123456789012345678901234567890";

		$random = "";
		srand((double) microtime() * 1000000);

		for ($i = 0; $i < $pLength; $i++)
		{
			$random .= substr($charList, (rand() % (strlen($charList))), 1);
		}

		return $random;
	}
}
