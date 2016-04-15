<?php
/**
 * @package     RedSHOP.Library
 * @subpackage  Helper
 *
 * @copyright   Copyright (C) 2008 - 2016 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

defined('_JEXEC') or die;

/**
 * Utility functions for redSHOP
 *
 * @since  1.5
 */
class RedshopHelperUtility
{
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

		if (SSL_ENABLE_IN_BACKEND && $applySSL)
		{
			$link->setScheme('https');
		}
		else
		{
			$link->setScheme('http');
		}

		return $link;
	}
}
