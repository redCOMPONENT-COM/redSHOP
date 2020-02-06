<?php
/**
 * RedShops Library file.
 * Including this file into your application will make redSHOP available to use.
 *
 * @package    RedShopb.Library
 * @copyright  Copyright (C) 2008 - 2019 redCOMPONENT.com. All rights reserved.
 * @license    GNU General Public License version 2 or later, see LICENSE.
 */

defined('JPATH_PLATFORM') or die;

use Doctrine\Common\Annotations\AnnotationRegistry;

jimport('joomla.filesystem.file');
jimport('joomla.filesystem.folder');

// Define redSHOP Library Folder Path
define('JPATH_REDSHOP_LIBRARY', __DIR__);

// Define redSHOP Constant
define('JPATH_REDSHOP_MEDIA', JPATH_SITE . '/media/com_redshop');
define('REDSHOP_MEDIA_IMAGES_ABSPATH', JUri::root() . '/media/com_redshop/images/');
define('JPATH_REDSHOP_TEMPLATE', JPATH_REDSHOP_MEDIA . '/templates');
define('JSYSTEM_IMAGES_PATH', JUri::root() . 'media/system/images/');
define('REDSHOP_FRONT_IMAGES_ABSPATH', JUri::root() . 'components/com_redshop/assets/images/');
define('REDSHOP_FRONT_IMAGES_RELPATH', JPATH_ROOT . '/components/com_redshop/assets/images/');

// Product
define('REDSHOP_FRONT_IMAGES_RELPATH_PRODUCT', REDSHOP_FRONT_IMAGES_RELPATH . 'product/');
define('REDSHOP_FRONT_DOCUMENT_ABSPATH', JUri::root() . 'components/com_redshop/assets/document/');
define('REDSHOP_FRONT_DOCUMENT_RELPATH', JPATH_ROOT . '/components/com_redshop/assets/document/');

// Video
define('REDSHOP_FRONT_VIDEO_ABSPATH', JUri::root() . 'components/com_redshop/assets/video/');
define('REDSHOP_FRONT_VIDEO_RELPATH', JPATH_ROOT . '/components/com_redshop/assets/video/');


define('REDSHOP_MEDIA_IMAGE_RELPATH', JPATH_ROOT . '/media/com_redshop/images/');
define('REDSHOP_MEDIA_IMAGE_ABSPATH', JUri::root() . 'media/com_redshop/images/');

// Address
define('REDSHOP_ADDRESS_TYPE_SHIPPING', 'ST');

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
JLoader::import('redshop.twig');

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
RedshopHelperUtility::defineDynamicVariables();

// Load backward compatible php defined config.
if (Redshop::getConfig()->getBool('BACKWARD_COMPATIBLE_PHP'))
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
