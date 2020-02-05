<?php
/**
 * @package     Redshop.Library
 *
 * @copyright   Copyright (C) 2008 - 2019 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later, see LICENSE.
 */

namespace Redshop\Plugin;

defined('_JEXEC') || die;

use Joomla\CMS\Plugin\CMSPlugin;

/**
 * Base twig extension plugin.
 *
 * @since  1.0.0
 */
abstract class BaseTwigPlugin extends CMSPlugin
{
	/**
	 * Path to the plugin folder.
	 *
	 * @var    string
	 */
	protected $pluginPath;

	/**
	 * Get the path to the folder of the current plugin.
	 *
	 * @return  string
	 */
	protected function pluginPath() : string
	{
		if (null === $this->pluginPath)
		{
			$reflection = new \ReflectionClass($this);

			$this->pluginPath = dirname($reflection->getFileName());
		}

		return $this->pluginPath;
	}
}
