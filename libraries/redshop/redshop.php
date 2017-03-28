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
	 * Component option.
	 *
	 * @var  string
	 */
	protected static $component = 'com_redshop';

	/**
	 * Component configuration
	 *
	 * @var  RedshopHelperConfig
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
		if (null === self::$config)
		{
			self::$config = new RedshopHelperConfig;
		}

		return self::$config;
	}

	/**
	 * Gets the redSHOP manifest
	 *
	 * @throws  Exception
	 * @return  SimpleXMLElement
	 */
	public static function getManifest()
	{
		if (null === self::$manifest)
		{
			$manifestFile = JPATH_ADMINISTRATOR . '/components/' . self::$component . '/redshop.xml';

			if (file_exists($manifestFile))
			{
				self::$manifest = simplexml_load_file($manifestFile);
			}
			else
			{
				throw new Exception('Unable to find redSHOP manifest file.');
			}
		}

		return self::$manifest;
	}

	/**
	 * Gets the redSHOP version
	 *
	 * @return  string
	 */
	public static function getVersion()
	{
		return (string) self::getManifest()->version;
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
