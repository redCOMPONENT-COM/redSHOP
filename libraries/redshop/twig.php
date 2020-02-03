<?php
/**
 * @package     Redshop.Library
 * @subpackage  Twig
 *
 * @copyright   Copyright (C) 2008 - 2020 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later, see LICENSE.
 */

defined('_JEXEC') or die;

use Joomla\Registry\Registry;

/**
 * Redshop Configuration
 *
 * @package     Redshop.Library
 * @subpackage  Twig
 * @since       __DEPLOY_VERSION__
 */
class RedshopHelperTwig
{
	/**
	 * @var object|\Twig\Loader\FilesystemLoader
	 * @since __DEPLOY_VERSION__
	 */
	public $loader;

	/**
	 * @var object|\Twig\Environment
	 * @since __DEPLOY_VERSION__
	 */
	public $twig;

	public function __construct($path = '')
	{
		$this->loader = $this->getLoader($path);
		$this->twig = $this->getTwig($this->loader);
	}

	/**
	 * @param  $pathToFolder
	 *
	 * @return mixed|object|\Twig\Loader\FilesystemLoader
	 *
	 * @since  __DEPLOY_VERSION__
	 */
	public function getLoader($pathToFolder)
	{

		if(null === $this->loader)
		{
			$this->loader = new \Twig\Loader\FilesystemLoader($pathToFolder);;
		}

		return $this->loader;
	}

	/**
	 * @param  $loader
	 *
	 * @return mixed|object|\Redshop\TwigEnvironment|\Twig\Environment
	 *
	 * @since  __DEPLOY_VERSION__
	 */
	public function getTwig($loader)
	{
		$this->twig = new Redshop\TwigEnvironment($loader, [
			'auto_reload' => true
		]);

		return $this->twig;
	}
}
