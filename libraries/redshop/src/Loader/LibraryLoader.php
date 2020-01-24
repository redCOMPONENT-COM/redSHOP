<?php
/**
 * @package     Redshop.Library
 * @subpackage  Loader
 *
 * @copyright   Copyright (C) 2008 - 2019 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later, see LICENSE.
 */

namespace Redshop\Loader;

defined('_JEXEC') || die;

use Joomla\CMS\Factory;

/**
 * Joomla library file system loader.
 *
 * @since  1.0.0
 */
final class LibraryLoader extends ExtensionLoader
{
	/**
	 * Namespace applicable to this extension.
	 *
	 * @var  string
	 */
	protected $extensionNamespace = 'library';

	/**
	 * Get the paths to search for templates.
	 *
	 * @return  array
	 */
	protected function getTemplatePaths() : array
	{
		$paths = [];

		$tplOverrides = JPATH_THEMES . '/' . Factory::getApplication()->getTemplate() . '/html/libraries';

		if (is_dir($tplOverrides))
		{
			$paths[] = $tplOverrides;
		}

		$paths[] = JPATH_LIBRARIES;

		return $paths;
	}

	/**
	 * Parse a received extension name.
	 *
	 * @param   string  $name  Name of the template to search
	 *
	 * @return  string
	 */
	protected function parseExtensionName(string $name) : string
	{
		$nameParts = explode('/', $name);

		if (!isset($nameParts[1]))
		{
			return $name;
		}

		array_splice($nameParts, 1, 1, [$nameParts[1], 'layouts']);

		return implode('/', $nameParts);
	}
}
