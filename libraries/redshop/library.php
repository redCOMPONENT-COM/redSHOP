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

// Define redSHOP Library Folder Path
define('JPATH_REDSHOP_LIBRARY', __DIR__);

// Define redSHOP Template Path
define('JPATH_REDSHOP_TEMPLATE', JPATH_SITE . "/components/com_redshop/templates");

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

// Load redSHOP language
$lang->load('com_redshop', JPATH_SITE);

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

// Load helpers pathes in JLoader
JLoader::discover('', JPATH_SITE . '/components/com_redshop/helpers', false);
JLoader::discover('', JPATH_ADMINISTRATOR . '/components/com_redshop/helpers', false);
JLoader::discover('RedshopSite', JPATH_SITE . '/components/com_redshop/helpers', false);
JLoader::discover('RedshopAdmin', JPATH_ADMINISTRATOR . '/components/com_redshop/helpers', false);

// HTML helpers
JHtml::addIncludePath(JPATH_REDSHOP_LIBRARY . '/html');

// Include all tables
JTable::addIncludePath(JPATH_ADMINISTRATOR . '/components/com_redshop/tables');

// Setup dynamic variables like show price etc...
Redconfiguration::getInstance()->defineDynamicVars();

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
