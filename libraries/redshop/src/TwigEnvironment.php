<?php
/**
 * @package     Redshop.Library
 * @subpackage  Twig
 *
 * @copyright   Copyright (C) 2008 - 2020 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later, see LICENSE.
 */
namespace Redshop;

defined('_JEXEC') or die;

use Joomla\CMS\Application\CMSApplication;
use Joomla\CMS\Factory;
use Joomla\CMS\Plugin\PluginHelper;
use Twig\Loader\LoaderInterface;
use Twig\Environment as BaseTwigEnvironment;

final class TwigEnvironment extends BaseTwigEnvironment
{
	/**
	 * @var CMSApplication
	 * @since 2.1.5
	 */
	private $app;

	/**
	 * @var array
	 * @since 2.1.5
	 */
	private $importablePluginTypes = ['twig'];

	/**
	 * @var array
	 * @since 2.1.5
	 */
	private $importedPluginTypes = [];

	/**
	 * TwigEnvironment constructor.
	 *
	 * @param   LoaderInterface      $loader
	 * @param   array                $options
	 * @param   CMSApplication|null  $app
	 *
	 * @return  mixed
	 * @since   2.1.5
	 */
	public function __construct(LoaderInterface $loader, array $options = [], CMSApplication $app = null)
	{
		$this->app = $app ?: $this->activeApplication();
		$this->trigger('onTwigBeforeLoad', [&$loader, &$options]);

		parent::__construct($loader, $options);

		$this->trigger('onTwigAfterLoad', [$options]);
	}

	/**
	 *
	 * @return CMSApplication
	 *
	 * @throws \Exception
	 * @since  2.1.5
	 */
	private function activeApplication() : CMSApplication
	{
		return Factory::getApplication();
	}

	/**
	 * Import plugin of Twigs
	 *
	 * @return void
	 * @since  version
	 */
	private function importPlugins()
	{
		$importablePluginTypes = array_diff($this->importablePluginTypes, $this->importedPluginTypes);

		foreach ($importablePluginTypes as $pluginType)
		{
			PluginHelper::importPlugin($pluginType);

			$this->importedPluginTypes[] = $pluginType;
		}
	}

	/**
	 * @param   string  $event
	 * @param   array   $params
	 *
	 * @return  array
	 *
	 * @since   2.1.5
	 */
	public function trigger(string $event, array $params = []) : array
	{
		$this->importPlugins();

		// Always send environment as first param
		array_unshift($params, $this);

		return (array) $this->app->triggerEvent($event, $params);
	}
}