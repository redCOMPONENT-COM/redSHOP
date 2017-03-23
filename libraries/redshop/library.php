<?php
/**
 * RedShops Library file.
 * Including this file into your application will make redSHOP available to use.
 *
 * @package    RedShopb.Library
 * @copyright  Copyright (C) 2012 - 2015 redCOMPONENT.com. All rights reserved.
 * @license    GNU General Public License version 2 or later, see LICENSE.
 */

defined('JPATH_PLATFORM') or die;

use \Doctrine\Common\Annotations\AnnotationRegistry;

jimport('joomla.filesystem.file');
jimport('joomla.filesystem.folder');

// Define constants
defined('JPATH_REDSHOP_LIBRARY') || define('JPATH_REDSHOP_LIBRARY', __DIR__);
defined('JPATH_REDSHOP_LIBRARY') || define('JPATH_REDSHOP_TEMPLATE', JPATH_SITE . "/components/com_redshop/templates");
defined('JSYSTEM_IMAGES_PATH') || define('JSYSTEM_IMAGES_PATH', JURI::root() . 'media/system/images/');
defined('REDSHOP_ADMIN_IMAGES_ABSPATH') || define('REDSHOP_ADMIN_IMAGES_ABSPATH', JURI::root() . 'administrator/components/com_redshop/assets/images/');
defined('REDSHOP_FRONT_IMAGES_ABSPATH') || define('REDSHOP_FRONT_IMAGES_ABSPATH', JURI::root() . 'components/com_redshop/assets/images/');
defined('REDSHOP_FRONT_IMAGES_RELPATH') || define('REDSHOP_FRONT_IMAGES_RELPATH', JPATH_ROOT . '/components/com_redshop/assets/images/');
defined('REDSHOP_FRONT_DOCUMENT_ABSPATH') || define('REDSHOP_FRONT_DOCUMENT_ABSPATH', JURI::root() . 'components/com_redshop/assets/document/');
defined('REDSHOP_FRONT_DOCUMENT_RELPATH') || define('REDSHOP_FRONT_DOCUMENT_RELPATH', JPATH_ROOT . '/components/com_redshop/assets/document/');

// Require our Composer libraries
$composerAutoload = __DIR__ . '/vendor/autoload.php';

if (file_exists($composerAutoload))
{
	$loader = require_once $composerAutoload;

	if (is_callable(array($loader, 'loadClass')))
	{
		AnnotationRegistry::registerLoader(array($loader, 'loadClass'));
	}
}

// Load library language
$lang = JFactory::getLanguage();
$lang->load('lib_redshop', JPATH_SITE);

// Load Joomla File & Folder Library
jimport('joomla.filesystem.folder');
jimport('joomla.filesystem.file');

// Load redSHOP factory file
JLoader::import('redshop.redshop');

// Register library prefix
JLoader::registerPrefix('Redshop', JPATH_REDSHOP_LIBRARY);

// Make available the redSHOP forms
JForm::addFormPath(JPATH_REDSHOP_LIBRARY . '/form/forms');

// Make available the redSHOP fields
JFormHelper::addFieldPath(JPATH_REDSHOP_LIBRARY . '/form/fields');
JFormHelper::addFieldPath(JPATH_REDSHOP_LIBRARY . '/form/field');

// Make available the redSHOP form rules
JFormHelper::addRulePath(JPATH_REDSHOP_LIBRARY . '/form/rules');

// Load helpers paths in JLoader
JLoader::discover('', JPATH_SITE . '/components/com_redshop/helpers', true, true);
JLoader::discover('', JPATH_ADMINISTRATOR . '/components/com_redshop/helpers', true, true);
JLoader::discover('RedshopSite', JPATH_SITE . '/components/com_redshop/helpers', true, true);
JLoader::discover('RedshopAdmin', JPATH_ADMINISTRATOR . '/components/com_redshop/helpers', true, true);

// HTML helpers
JHtml::addIncludePath(JPATH_REDSHOP_LIBRARY . '/html');

// Include all tables
JTable::addIncludePath(JPATH_ADMINISTRATOR . '/components/com_redshop/tables');

// Setup dynamic variables like show price etc...
if (class_exists('Redconfiguration'))
{
	Redconfiguration::getInstance()->defineDynamicVars();
}

// Load backward compatible php defined config.
if (Redshop::getConfig()->get('BACKWARD_COMPATIBLE_PHP') == 1)
{
	$configs = Redshop::getConfig()->toArray();

	foreach ($configs as $key => $value)
	{
		if (!defined($key))
		{
			define($key, $value);
		}
	}
}
