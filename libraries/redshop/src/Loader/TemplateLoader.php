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
 * redSHOP template file system loader.
 *
 * @since  __DEPLOY_VERSION__
 */
final class TemplateLoader extends ExtensionLoader
{
	/**
	 * @var string
	 * @since __DEPLOY_VERSION__
	 */
	protected $extensionNamespace = 'template';

	/**
	 *
	 * @return array
	 *
	 * @throws \Exception
	 * @since  __DEPLOY_VERSION__
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
