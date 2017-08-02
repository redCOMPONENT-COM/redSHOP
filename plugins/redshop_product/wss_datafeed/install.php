<?php
/**
 * @package     Redshop.Plugin
 * @subpackage  ShopperGroup_Tags
 *
 * @copyright   Copyright (C) 2008 - 2017 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later, see LICENSE.
 */
defined('_JEXEC') or die();

use Joomla\Registry\Registry;

/**
 * PlgRedshop_ProductWss_Datafeed installer class.
 *
 * @package  Redshopb.Plugin
 * @since    1.6.0
 */
class PlgRedshop_ProductWss_DatafeedInstallerScript
{
	/**
	 * Method to run before an install/update/uninstall method
	 *
	 * @param   string  $type  The type of change (install, update or discover_install)
	 *
	 * @return  void
	 */
	public function postflight($type)
	{
		if ($type == 'install' || $type == 'update')
		{
			$path = JPATH_PLUGINS . '/redshop_product/wss_datafeed/cli';
			$dest = JPATH_SITE . '/cli/';

			if (JFolder::exists($path))
			{
				if (JFolder::copy($path, $dest, '', true))
				{
					JFolder::delete($path);
				}
			}
		}
	}
}
