<?php
/**
 * RedShops Library file.
 * Including this file into your application will make redSHOP available to use.
 *
 * @package    RedShopb.Library
 * @copyright  Copyright (C) 2012 - 2014 redCOMPONENT.com. All rights reserved.
 * @license    GNU General Public License version 2 or later, see LICENSE.
 */

defined('JPATH_PLATFORM') or die;

if (!defined('JPATH_REDSHOP_LIBRARY'))
{
	// Define redSHOP Library Folder Path
	define('JPATH_REDSHOP_LIBRARY', __DIR__);

	// Load library language
	$lang = JFactory::getLanguage();
	$lang->load('lib_redshop', JPATH_SITE);

	// Register library prefix
	JLoader::registerPrefix('Redshop', JPATH_REDSHOP_LIBRARY);

	// Make available the redSHOP fields
	JFormHelper::addFieldPath(JPATH_REDSHOP_LIBRARY . '/form/fields');

	// Make available the redSHOP form rules
	JFormHelper::addRulePath(JPATH_REDSHOP_LIBRARY . '/form/rules');

	// Load helpers pathes in JLoader
	JLoader::discover('RedshopHelper', JPATH_SITE . '/components/com_redshop/helpers', false);
	JLoader::discover('RedshopHelperAdmin', JPATH_ADMINISTRATOR . '/components/com_redshop/helpers', false);

	// HTML helpers
	JHtml::addIncludePath(JPATH_REDSHOP_LIBRARY . '/html');
}
