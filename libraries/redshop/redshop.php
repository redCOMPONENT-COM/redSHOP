<?php
/**
 * @package     Redshop.Library
 * @subpackage  Redshop
 *
 * @copyright   Copyright (C) 2008 - 2017 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later, see LICENSE.
 */

defined('_JEXEC') or die;

use Redshop\Twig\Environment;

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

	/**
	 * Get the twig renderer.
	 *
	 * @param   Twig_LoaderInterface  $loader   Twig loader
	 * @param   array                 $options  Options for the environment
	 *
	 * @return  Environment
	 *
	 * @since   __DEPLOY_VERSION__
	 */
	public static function getTwig(Twig_LoaderInterface $loader, array $options = array())
	{
		if (!isset($options['debug']))
		{
			// @TODO: Add confg for Twig debug
			$options['debug'] = (bool) self::getConfig()->get('twig_debug', false);
		}

		if (!isset($options['cache']) && (bool) static::getConfig()->get('twig_cache', false))
		{
			// @TODO: Add confg for Twig cache
			$options['cache'] = JPATH_ROOT . '/cache/redshop_twig/';
		}

		$options['debug'] = true;

		$twig = new Environment($loader, $options);

		if ($options['debug'])
		{
			$twig->loadDebugExtension();
		}

		return $twig;
	}
}
