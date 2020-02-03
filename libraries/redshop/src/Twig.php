<?php
/**
 * @package     Redshop.Library
 *
 * @copyright   Copyright (C) 2008 - 2020 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later, see LICENSE.
 */

namespace Redshop;

defined('_JEXEC') || die;

use Twig\Loader\ChainLoader;

/**
 * Twig rendering class
 *
 * @since  __DEPLOY_VERSION__
 */
final class Twig
{
	/**
	 * @var
	 * @since __DEPLOY_VERSION__
	 */
	private static $instance;

	/**
	 * @var TwigEnvironment
	 * @since __DEPLOY_VERSION__
	 */
	private $twigEnvironment;

	/**
	 * Twig constructor.
	 *
	 * @since __DEPLOY_VERSION__
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

		$this->twigEnvironment = new TwigEnvironment($loader);
	}

	/**
	 * self destroy
	 *
	 * @since __DEPLOY_VERSION__
	 */
	public static function clear()
	{
		self::$instance = null;
	}

	/**
	 * Create Twig instance
	 * @return Twig
	 *
	 * @since __DEPLOY_VERSION__
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
	 * Render function as path and data provided
	 *
	 * @param   string  $layout
	 * @param   array   $data
	 *
	 * @return string
	 *
	 * @throws \Twig\Error\LoaderError
	 * @throws \Twig\Error\RuntimeError
	 * @throws \Twig\Error\SyntaxError
	 * @since __DEPLOY_VERSION__
	 */
	public static function render(string $layout, array $data = []) : string
	{
		return self::instance()->environment()->render($layout, $data);
	}

	/**
	 *
	 * @return TwigEnvironment
	 *
	 * @since __DEPLOY_VERSION__
	 */
	public function environment() : TwigEnvironment
	{
		return $this->twigEnvironment;
	}
}
