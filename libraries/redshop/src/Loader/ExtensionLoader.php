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
use Twig\Loader\FilesystemLoader;
use Twig\Error\LoaderError;

/**
 * redSHOP extension file system loader.
 *
 * @since  __DEPLOY_VERSION__
 */
abstract class ExtensionLoader extends FilesystemLoader
{
	/**
	 * @var
	 * @since __DEPLOY_VERSION__
	 */
	protected $extensionNamespace;

	/**
	 * ExtensionLoader constructor.
	 *
	 * @param   array  $paths
	 *
	 * @return void
	 *
	 * @since __DEPLOY_VERSION__
	 */
	public function __construct(array $paths = [])
	{
		$this->setPaths($this->getTemplatePaths(), $this->extensionNamespace);

		parent::__construct($paths);
	}

	/**
	 *
	 * @return string
	 *
	 * @throws \Exception
	 * @since  __DEPLOY_VERSION__
	 */
	protected function getBaseAppPath() : string
	{
		if (Factory::getApplication()->isAdmin())
		{
			return JPATH_ADMINISTRATOR;
		}

		return JPATH_SITE;
	}

	/**
	 *
	 * @return array
	 *
	 * @since  __DEPLOY_VERSION__
	 */
	abstract protected function getTemplatePaths() : array;

	/**
	 * @param   string  $name
	 * @param   bool    $throw
	 *
	 * @return bool|false|mixed|string|null
	 *
	 * @throws LoaderError
	 * @since  __DEPLOY_VERSION__
	 */
	protected function findTemplate($name, $throw = true)
	{
		if (!$this->nameInExtensionNamespace($name))
		{
			return false;
		}

		try
		{
			$result = parent::findTemplate($name, true);
		}
		catch (LoaderError $e)
		{
			$result = $this->findParsedNameTemplate($name);
		}

		if (!$result && $throw)
		{
			throw new LoaderError($name);
		}

		return $result;
	}

	/**
	 * @param   string  $name
	 *
	 * @return bool|false|string|null
	 *
	 * @throws LoaderError
	 * @since  __DEPLOY_VERSION__
	 */
	protected function findParsedNameTemplate(string $name)
	{
		$parsedName = $this->parseExtensionName($name);

		if ($name === $parsedName)
		{
			return false;
		}

		return parent::findTemplate($parsedName, false);
	}

	/**
	 * @param   string  $name
	 *
	 * @return bool
	 *
	 * @since __DEPLOY_VERSION__
	 */
	protected function nameInExtensionNamespace(string $name) : bool
	{
		$nameParts = explode('/', $name);

		return isset($nameParts[0]) && $nameParts[0] === '@' . $this->extensionNamespace;
	}

	/**
	 * @param   string  $name
	 *
	 * @return string
	 *
	 * @since __DEPLOY_VERSION__
	 */
	protected function parseExtensionName(string $name) : string
	{
		return $name;
	}
}
