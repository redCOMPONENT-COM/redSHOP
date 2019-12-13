<?php
/**
 * @package     Redshop.Libraries
 *
 * @copyright   Copyright (C) 2008 - 2019 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

namespace Redshop;

defined('_JEXEC') || die;

use Redshop\Loader\ChainLoader;

/**
 * Twig rendering class
 *
 * @since  1.0.0
 */
final class Twig
{
	/**
	 * Renderer instance.
	 *
	 * @var  $this
	 */
	private static $instance;

	/**
	 * Twig environment.
	 *
	 * @var  self
	 */
	private $environment;

	/**
	 * Constructor
	 */
	private function __construct()
	{
		$loader = new ChainLoader(
			[
				new Loader\ComponentLoader,
				new Loader\LibraryLoader,
				new Loader\ModuleLoader,
				new Loader\PluginLoader,
				new Loader\TemplateLoader
			]
		);

		$this->environment = new Environment($loader);
	}

	/**
	 * Clear the cached instance.
	 *
	 * @return  void
	 */
	public static function clear()
	{
		self::$instance = null;
	}

	/**
	 * Get the cached instance
	 *
	 * @return  static
	 */
	public static function instance() : Twig
	{
		if (null === self::$instance)
		{
			self::$instance = new self;
		}

		return self::$instance;
	}

	/**
	 * Render a layout.
	 *
	 * @param   string  $layout  Template to render
	 * @param   array   $data    Optional data for the layout
	 *
	 * @return  string
	 */
	public static function render(string $layout, array $data = []) : string
	{
		return self::instance()->environment()->render($layout, $data);
	}

	/**
	 * Retrieve the environment.
	 *
	 * @return  self
	 */
	public function environment() : Environment
	{
		return $this->environment;
	}
}
