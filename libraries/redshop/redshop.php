<?php
/**
 * @package     Redshop.Library
 * @subpackage  Redshop
 *
 * @copyright   Copyright (C) 2014 - 2015 redCOMPONENT.com. All rights reserved.
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
	 * Component option.
	 *
	 * @var  string
	 */
	protected static $component = 'com_redshop';

	/**
	 * Component configuration
	 *
	 * @var  JRegistry
	 */
	protected static $config;

	/**
	 * Gets the current redSHOP configuration
	 *
	 * @return  RedshopHelperConfig
	 */
	public static function getConfig()
	{
		if (null === static::$config)
		{
			static::$config = new RedshopHelperConfig;
		}

		return static::$config;
	}
}
