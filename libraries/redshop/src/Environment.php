<?php
/**
 * @package     Redshop
 * @subpackage  Libraries
 *
 * @copyright   Copyright (C) 2008 - 2019 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later, see LICENSE.
 */

namespace Redshop;

defined('_JEXEC') or die;

/**
 * @package     Redshop
 *
 * @since       2.1.0
 */
class Environment
{
	/**
	 *
	 * @return array|false|string
	 *
	 * @since  2.1.0
	 */
	public static function getUserIp()
	{
		if (getenv('HTTP_CLIENT_IP'))
		{
			$ipAddress = getenv('HTTP_CLIENT_IP');
		}
		elseif (getenv('HTTP_X_FORWARDED_FOR'))
		{
			$ipAddress = getenv('HTTP_X_FORWARDED_FOR');
		}
		elseif (getenv('HTTP_X_FORWARDED'))
		{
			$ipAddress = getenv('HTTP_X_FORWARDED');
		}
		elseif (getenv('HTTP_FORWARDED_FOR'))
		{
			$ipAddress = getenv('HTTP_FORWARDED_FOR');
		}
		elseif (getenv('HTTP_FORWARDED'))
		{
			$ipAddress = getenv('HTTP_FORWARDED');
		}
		elseif (getenv('REMOTE_ADDR'))
		{
			$ipAddress = getenv('REMOTE_ADDR');
		}
		else
		{
			$ipAddress = 'UNKNOWN';
		}

		return $ipAddress;
	}
}
