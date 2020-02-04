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
 * redSHOP Twig component file system loader.
 *
 * @since  2.1.5
 */
final class ComponentLoader extends ExtensionLoader
{
	/**
	 * @var string
	 * @since 2.1.5
	 */
	protected $extensionNamespace = 'component';

	/**
	 *
	 * @return array
	 *
	 * @throws \Exception
	 * @since 2.1.5
	 */
	protected function getTemplatePaths() : array
	{
		$paths = [];

		$tplOverrides = JPATH_THEMES . '/' . Factory::getApplication()->getTemplate() . '/html';

		if (is_dir($tplOverrides))
		{
			$paths[] = $tplOverrides;
		}

		$paths[] = $this->getBaseAppPath() . '/components';

		return $paths;
	}

	/**
	 * @param   string  $name
	 *
	 * @return string
	 *
	 * @since 2.1.5
	 */
	protected function parseExtensionName(string $name) : string
	{
		$nameParts = explode('/', $name);

		if (!isset($nameParts[2]))
		{
			return $name;
		}

		array_splice($nameParts, 2, 1, ['views', $nameParts[2], 'tmpl']);

		return implode('/', $nameParts);
	}
}
