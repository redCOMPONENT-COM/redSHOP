<?php
/**
 * @package     RedSHOP.Library
 * @subpackage  Helper
 *
 * @copyright   Copyright (C) 2008 - 2016 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 *
 * @since       2.0.0.3
 */

defined('_JEXEC') or die;

/**
 * Constants will be defined when this file autoload to application.
 * Because *RedshopAppConfiguration* will be used as static library,
 * Constructor will not neccessary anymore.
 */
/**
 * Define Site's Image Path
 *
 * @since       2.0.0.3
 */
if (!defined('JSYSTEM_IMAGES_PATH'))
{
	define('JSYSTEM_IMAGES_PATH', JURI::root() . 'media/system/images/');
}

/**
 * Define Admin's Image Path
 *
 * @since       2.0.0.3
 */
if (!defined('REDSHOP_ADMIN_IMAGES_ABSPATH'))
{
	define('REDSHOP_ADMIN_IMAGES_ABSPATH', JURI::root() . 'administrator/components/com_redshop/assets/images/');
}

/**
 * Define Front Store's Image Path
 *
 * @since       2.0.0.3
 */
if (!defined('REDSHOP_FRONT_IMAGES_ABSPATH'))
{
	define('REDSHOP_FRONT_IMAGES_ABSPATH', JURI::root() . 'components/com_redshop/assets/images/');
}

/**
 * Define Assets's Image Path
 *
 * @since       2.0.0.3
 */
if (!defined('REDSHOP_FRONT_IMAGES_RELPATH'))
{
	define('REDSHOP_FRONT_IMAGES_RELPATH', JPATH_ROOT . '/components/com_redshop/assets/images/');
}

/**
 * Define Front Store's Document Path
 *
 * @since       2.0.0.3
 */
if (!defined('REDSHOP_FRONT_DOCUMENT_ABSPATH'))
{
	define('REDSHOP_FRONT_DOCUMENT_ABSPATH', JURI::root() . 'components/com_redshop/assets/document/');
}

/**
 * Define Assets's Document Path
 *
 * @since       2.0.0.3
 */
if (!defined('REDSHOP_FRONT_DOCUMENT_RELPATH'))
{
	define('REDSHOP_FRONT_DOCUMENT_RELPATH', JPATH_ROOT . '/components/com_redshop/assets/document/');
}

/**
 * Configuration for application, this library help to store configuration in database,
 * getting and converting date time, support to get country and state information...
 * Using: RedshopAppConfiguration::<method>
 *
 * @since  2.0.0.3
 */
class RedshopAppConfiguration
{
	/**
	 * Load redshop.cfg.php in /administrator/components/com_redshop/helpers/
	 * If success return true, else return false.
	 *
	 * @return  boolean
	 *
	 * @since  2.0.0.3
	 */
	public static function loadConfigFile()
	{
		if (!file_exists(JPATH_SITE . '/administrator/components/com_redshop/helpers/redshop.cfg.php'))
		{
			return false;
		}

		require_once JPATH_SITE . '/administrator/components/com_redshop/helpers/redshop.cfg.php';

		return true;
	}

	/**
	 * Check table: redshop_configuration is existed in database or not,
	 * return true if it is existed, else return false.
	 *
	 * @return  boolean
	 *
	 * @since  2.0.0.3
	 */
	public static function checkConfigTableExist()
	{
		$db    = JFactory::getDbo();
		$query = 'SHOW TABLES LIKE ' . $db->qn('#__redshop_configuration');
		$db->setQuery($query);

		if (count($db->loadResult()) <= 0)
		{
			return false;
		}

		return true;
	}
}
