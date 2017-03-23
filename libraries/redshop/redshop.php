<?php
/**
 * @package     Redshop.Library
 * @subpackage  Redshop
 *
 * @copyright   Copyright (C) 2008 - 2017 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later, see LICENSE.
 */

defined('_JEXEC') or die;

/**
 * Redshop
 *
 * @package     Redshop.Library
 * @subpackage  Config
 * @since       1.5
 */
abstract class Redshop
{
	/**
	 * Component configuration
	 *
	 * @var  RedshopHelperConfig
	 */
	protected static $config;

	/**
	 * Gets the current redSHOP configuration
	 *
	 * @return  RedshopHelperConfig
	 */
	public static function getConfig()
	{
		if (null === self::$config)
		{
			self::$config = new RedshopHelperConfig;
		}

		return self::$config;
	}

	/**
	 * Gets product object
	 *
	 * @param   int  $id  ID of product
	 *
	 * @return  RedshopProduct object
	 */
	public static function product($id)
	{
		return RedshopProduct::getInstance($id);
	}
}
