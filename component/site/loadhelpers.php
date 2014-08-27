<?php
/**
 * @package     RedSHOP.Frontend
 * @subpackage  redSHOP
 *
 * @copyright   Copyright (C) 2005 - 2013 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

defined('_JEXEC') or die;

/**
 * Class loadhelpers
 *
 * @since  1.4
 */
class loadhelpers
{
	protected static $helpersDiscovered = false;

	/**
	 * Discover Helpers
	 *
	 * @return  void
	 */
	public static function discoverHelpers()
	{
		if (self::$helpersDiscovered)
		{
			return;
		}

		JLoader::discover('RedshopHelper', JPATH_SITE . '/components/com_redshop/helpers', false);
		JLoader::discover('RedshopHelperAdmin', JPATH_ADMINISTRATOR . '/components/com_redshop/helpers', false);
		self::$helpersDiscovered = true;
	}
}

loadHelpers::discoverHelpers();
