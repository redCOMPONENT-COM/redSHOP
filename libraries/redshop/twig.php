<?php
/**
 * @package     Redshop.Library
 * @subpackage  Twig
 *
 * @copyright   Copyright (C) 2008 - 2019 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later, see LICENSE.
 */

defined('_JEXEC') or die;

use Joomla\Registry\Registry;

/**
 * Redshop Configuration
 *
 * @package     Redshop.Library
 * @subpackage  Config
 * @since       1.5
 */
class RedshopHelperTwig
{
	/**
	 * @var object twig loader object
	 */
	public $loader;

	/**
	 * @var object instance of $twig
	 */
	public $twig;

	public function __construct($path = '')
	{
		$this->loader = $this->getLoader($path);
		$this->twig = $this->getTwig($this->loader);
	}

	/**
	 * @param  string  $pathToFolder
	 *
	 * @return object|\Twig\Loader\FilesystemLoader
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
	 * @param  object $loader
	 *
	 * @return object|\Twig\Environment
	 */
	public function getTwig($loader)
	{
		$this->twig = new Redshop\TwigEnvironment($loader, [
			'auto_reload' => true
		]);

		return $this->twig;
	}
}
