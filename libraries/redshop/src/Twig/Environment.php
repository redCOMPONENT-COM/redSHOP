<?php
/**
 * @package     RedSHOP
 * @subpackage  Base
 *
 * @copyright   Copyright (C) 2008 - 2017 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later, see LICENSE.
 */

namespace Redshop\Twig;

defined('_JEXEC') or die;

/**
 * RedSHOP Twig Environment
 *
 * @package     Redshop
 * @subpackage  Base
 * @since       __DEPLOY_VERSION__
 **/
class Environment extends \Twig_Environment
{
	/**
	 * Plugins connected to the events triggered by this class.
	 *
	 * @var  array
	 */
	private $importedPluginTypes = array(
		'redshop',
	);

	/**
	 * Constructor.
	 *
	 * @param   \Twig_LoaderInterface  $loader   A Twig_LoaderInterface instance
	 * @param   array                  $options  An array of options
	 */
	public function __construct(\Twig_LoaderInterface $loader = null, $options = array())
	{
		$this->trigger('onRedshopBeforeTwigLoad', array(&$loader, &$options));

		parent::__construct($loader, $options);

		$this->injectCoreExtensions();
		$this->injectGlobals();

		$this->trigger('onRedshopAfterTwigLoad', array($loader, $options));
	}

	/**
	 * Inject core twig extensions.
	 *
	 * @return  void
	 */
	private function injectCoreExtensions()
	{
		$files = glob(__DIR__ . '/Extension/*.php');

		foreach ($files as $file)
		{
			$className = "\\Redshop\\Twig\\Extension\\" . basename($file, ".php");

			if (class_exists($className))
			{
				$this->addExtension(new $className);
			}
		}
	}

	/**
	 * Inject variables that will be available in all the templates.
	 *
	 * @return  void
	 *
	 * @since   __DEPLOY_VERSION__
	 */
	private function injectGlobals()
	{
		$this->addGlobal('juser', \JFactory::getUser());
	}

	/**
	 * Import available plugins.
	 *
	 * @return  void
	 */
	private function importPlugins()
	{
		foreach ($this->importedPluginTypes as $pluginType)
		{
			\JPluginHelper::importPlugin($pluginType);
		}
	}

	/**
	 * Load the debug extension.
	 *
	 * @return  void
	 */
	public function loadDebugExtension()
	{
		$this->addExtension(new \Twig_Extension_Debug);
	}

	/**
	 * Trigger an event on the attached twig instance.
	 *
	 * @param   string  $event   Event to trigger
	 * @param   array   $params  Params for the event triggered
	 *
	 * @return  mixed
	 */
	public function trigger($event, $params = array())
	{
		$dispatcher = \JEventDispatcher::getInstance();

		$this->importPlugins();

		// Always send environment as first param
		array_unshift($params, $this);

		return $dispatcher->trigger($event, $params);
	}
}
