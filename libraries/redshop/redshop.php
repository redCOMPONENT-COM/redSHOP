<?php
/**
 * @package     Redshop.Library
 * @subpackage  Redshop
 *
 * @copyright   Copyright (C) 2008 - 2016 redCOMPONENT.com. All rights reserved.
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
	 * Component manifest
	 *
	 * @var  SimpleXMLElement
	 */
	protected static $manifest;

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

	/**
	 * Gets the redSHOP manifest
	 *
	 * @return  SimpleXMLElement
	 */
	public static function getManifest()
	{
		if (null === static::$manifest)
		{
			$manifestFile = JPATH_ADMINISTRATOR . '/components/' . self::$component . '/redshop.xml';

			if (file_exists($manifestFile))
			{
				static::$manifest = simplexml_load_file($manifestFile);
			}
			else
			{
				throw new Exception('Unable to find redSHOP manifest file.');
			}
		}

		return static::$manifest;
	}

	/**
	 * Gets the redSHOP version
	 *
	 * @return  string
	 */
	public static function getVersion()
	{
		return (string) static::getManifest()->version;
	}

	/**
	 * Gets product object
	 *
	 * @return  RedshopProduct object
	 */
	public static function product($id)
	{
		return RedshopProduct::getInstance($id);
	}
}
