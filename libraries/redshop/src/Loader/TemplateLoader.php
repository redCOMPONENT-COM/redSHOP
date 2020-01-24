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
 * Joomla template file system loader.
 *
 * @since  1.0.0
 */
final class TemplateLoader extends ExtensionLoader
{
	/**
	 * Namespace applicable to this extension.
	 *
	 * @var  string
	 */
	protected $extensionNamespace = 'template';

	/**
	 * Get the paths to search for templates.
	 *
	 * @return  array
	 */
	protected function getTemplatePaths() : array
	{
		$paths = [];

		$tplOverrides = JPATH_THEMES . '/' . Factory::getApplication()->getTemplate() . '/html';

		if (is_dir($tplOverrides))
		{
			$paths[] = $tplOverrides;
		}

		return $paths;
	}
}
