<?php
/**
 * @package     RedSHOP.Backend
 * @subpackage  Helper
 *
 * @copyright   Copyright (C) 2008 - 2017 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 *
 * @deprecated  2.0.3  Use RedshopMenu instead
 */

defined('_JEXEC') or die;

/**
 * Generate admin menu list
 *
 * @since       1.6.1
 *
 * @deprecated  2.0.3  Use RedshopMenu instead
 */
class RedshopAdminMenu extends RedshopMenu
{
	protected static $instance = null;

	/**
	 * Returns the RedshopAdminMenu object, only creating it if it doesn't already exist.
	 *
	 * @return  RedshopAdminMenu  The RedshopAdminMenu object
	 *
	 * @since   1.6.1
	 *
	 * @deprecated  2.0.3  Use new RedshopMenu instead
	 */
	public static function getInstance()
	{
		if (self::$instance === null)
		{
			self::$instance = new static;
		}

		return self::$instance;
	}

	/**
	 * Returns the RedshopAdminMenu object, only creating it if it doesn't already exist.
	 *
	 * @return  RedshopAdminMenu  The RedshopAdminMenu object
	 *
	 * @since   1.6.1
	 *
	 * @deprecated  2.0.3  Use new RedshopMenu instead
	 */
	public function init()
	{
		$this->menuhide = explode(",", Redshop::getConfig()->get('MENUHIDE', ''));

		return $this;
	}
}
