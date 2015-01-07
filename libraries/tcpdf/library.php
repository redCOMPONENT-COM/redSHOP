<?php
/**
 * TCPDF Library file.
 * Including this file into your application will make redSHOP available to use.
 *
 * @package    TCPDF.Library
 * @copyright  Copyright (C) 2008 - 2015 redCOMPONENT.com. All rights reserved.
 * @license    GNU General Public License version 2 or later, see LICENSE.
 */

if (!defined('JPATH_TCPDF_LIBRARY'))
{
	// Define TCPDF Library Folder Path
	define('JPATH_TCPDF_LIBRARY', __DIR__);
}

// Load library language
$lang = JFactory::getLanguage();
$lang->load('lib_tcpdf', JPATH_SITE);

global $l;
$l = array(
	'a_meta_charset' => JText::_('LIB_TCPDF_META_CHARSET'),
	'a_meta_dir' => JText::_('LIB_TCPDF_META_DIR'),
	'a_meta_language' => JText::_('LIB_TCPDF_META_LANGUAGE'),
	'w_page' => JText::_('LIB_TCPDF_PAGE')
);

JLoader::import('tcpdf', JPATH_TCPDF_LIBRARY);
