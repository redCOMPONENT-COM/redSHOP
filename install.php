<?php
/**
 * @package    RedSHOP.Installer
 *
 * @copyright  Copyright (C) 2008 - 2015 redCOMPONENT.com. All rights reserved.
 * @license    GNU General Public License version 2 or later; see LICENSE
 */

defined('_JEXEC') or die;

/**
 * Script file of redSHOP component
 *
 * @package  RedSHOP.Installer
 *
 * @since    1.2
 */
class Com_RedshopInstallerScript
{
	/**
	 * Status of the installation
	 *
	 * @var  object
	 */
	public $status = null;

	/**
	 * The common JInstaller instance used to install all the extensions
	 *
	 * @var  object
	 */
	public $installer = null;

	/**
	 * Install type
	 *
	 * @var   string
	 */
	protected $type = null;

	/**
	 * Method to install the component
	 *
	 * @param   object  $parent  Class calling this method
	 *
	 * @return  void
	 */
	public function install($parent)
	{
		// Install extensions
		$this->installLibraries($parent);
		$this->installModules($parent);
		$this->installPlugins($parent);
	}

	/**
	 * method to run after an install/update/uninstall method
	 *
	 * @param   string  $type    Type of method
	 * @param   object  $parent  Parent class call this method
	 *
	 * @return  void
	 */
	public function postflight($type, $parent)
	{
		JFactory::getApplication()->redirect('index.php?option=com_redshop&view=install&install_type=' . $type);
	}

	/**
	 * method to uninstall the component
	 *
	 * @param   object  $parent  Class calling this method
	 *
	 * @return  void
	 */
	public function uninstall($parent)
	{
		// Uninstall extensions
		$this->uninstallPlugins($parent);
		$this->uninstallModules($parent);
		$this->uninstallLibraries($parent);
	}

	/**
	 * Method to update the component
	 *
	 * @param   object  $parent  Class calling this method
	 *
	 * @return  void
	 */
	public function update($parent)
	{
		$this->installLibraries($parent);
		$this->installModules($parent);
		$this->installPlugins($parent);
	}

	/**
	 * method to run before an install/update/uninstall method
	 *
	 * @param   object  $type    Type of change (install, update or discover_install)
	 * @param   object  $parent  Class calling this method
	 *
	 * @return void
	 */
	public function preflight($type, $parent)
	{
		$this->type = $type;

		if ($type == 'update' || $type == 'discover_install')
		{
			if (!class_exists('RedshopHelperJoomla'))
			{
				require_once __DIR__ . '/libraries/redshop/helper/joomla.php';
			}

			// Store redSHOP old version.
			JFactory::getApplication()->setUserState('redshop.old_version', RedshopHelperJoomla::getManifestValue('version'));
		}
	}

	/**
	 * Get the common JInstaller instance used to install all the extensions
	 *
	 * @return JInstaller The JInstaller object
	 */
	public function getInstaller()
	{
		$this->installer = new JInstaller;

		return $this->installer;
	}

	/**
	 * Install the package libraries
	 *
	 * @param   object  $parent  Class calling this method
	 *
	 * @return  void
	 */
	protected function installLibraries($parent)
	{
		// Required objects
		$manifest = $parent->get('manifest');
		$src      = $parent->getParent()->getPath('source');

		if ($nodes = $manifest->libraries->library)
		{
			$installer = $this->getInstaller();

			foreach ($nodes as $node)
			{
				$extName = $node->attributes()->name;
				$extPath = $src . '/libraries/' . $extName;

				// Standard install
				if (is_dir($extPath))
				{
					$installer->install($extPath);
				}
				// Discover install
				elseif ($extId = $this->searchExtension($extName, 'library', '-1'))
				{
					$installer->discover_install($extId);
				}
			}
		}
	}

	/**
	 * Install the package modules
	 *
	 * @param   object  $parent  Class calling this method
	 *
	 * @return  void
	 */
	protected function installModules($parent)
	{
		// Required objects
		$manifest = $parent->get('manifest');
		$src      = $parent->getParent()->getPath('source');

		if ($nodes = $manifest->modules->module)
		{
			foreach ($nodes as $node)
			{
				$extName   = (string) $node->attributes()->name;
				$extClient = (string) $node->attributes()->client;
				$extPath   = $src . '/modules/' . $extClient . '/' . $extName;

				if (is_dir($extPath))
				{
					$this->getInstaller()->install($extPath);
				}
				// Discover install
				elseif ($extId = $this->searchExtension($extName, 'module', '-1'))
				{
					$this->getInstaller()->discover_install($extId);
				}
			}
		}
	}

	/**
	 * Install the package libraries
	 *
	 * @param   object  $parent  Class calling this method
	 *
	 * @return  void
	 */
	protected function installPlugins($parent)
	{
		// Required objects
		$manifest = $parent->get('manifest');
		$src      = $parent->getParent()->getPath('source');

		if ($nodes = $manifest->plugins->plugin)
		{
			$installer = $this->getInstaller();

			foreach ($nodes as $node)
			{
				$extName  = (string) $node->attributes()->name;
				$extGroup = (string) $node->attributes()->group;
				$extPath  = $src . '/plugins/' . $extGroup . '/' . $extName;
				$result   = 0;

				// Install or upgrade plugin
				if (is_dir($extPath))
				{
					$installer->setAdapter('plugin');
					$result = $installer->install($extPath);
				}
				// Discover install
				elseif ($extId = $this->searchExtension($extName, 'plugin', '-1', $extGroup))
				{
					$result = $installer->discover_install($extId);
				}

				// We'll not enable plugin for update case
				if ($this->type != 'update' && $result)
				{
					/*
					 * For another rest type cases
					 * Do not change plugin state if it's installed
					 * If plugin is installed successfully and it didn't exist before we enable it.
					 */
					$this->enablePlugin($extName, $extGroup);
				}

				// Force to enable redSHOP - System plugin by anyways
				$this->enablePlugin('redshop', 'system');

				// Force to enable redSHOP PDF - TcPDF plugin by anyways
				$this->enablePlugin('tcpdf', 'redshop_pdf');

				// Force to enable redSHOP Export - Category plugin by anyways
				$this->enablePlugin('category', 'redshop_export');

				// Force to enable redSHOP Export - Product plugin by anyways
				$this->enablePlugin('product', 'redshop_export');

				// Force to enable redSHOP Import - Category plugin by anyways
				$this->enablePlugin('category', 'redshop_import');

				// Force to enable redSHOP Import - Product plugin by anyways
				$this->enablePlugin('product', 'redshop_import');
			}
		}
	}

	/**
	 * Method for enable plugins
	 *
	 * @param   string  $extName   Plugin name
	 * @param   string  $extGroup  Plugin group
	 * @param   int     $state     State of plugins
	 *
	 * @return mixed
	 */
	protected function enablePlugin($extName, $extGroup, $state = 1)
	{
		$db    = JFactory::getDbo();
		$query = $db->getQuery(true);
		$query->update($db->qn("#__extensions"))
			->set("enabled = " . (int) $state)
			->where('type = ' . $db->quote('plugin'))
			->where('element = ' . $db->quote($extName))
			->where('folder = ' . $db->quote($extGroup));

		return $db->setQuery($query)->execute();
	}

	/**
	 * Uninstall the package libraries
	 *
	 * @param   object  $parent  Class calling this method
	 *
	 * @return  void
	 */
	protected function uninstallLibraries($parent)
	{
		// Required objects
		$manifest = $parent->get('manifest');

		if ($nodes = $manifest->libraries->library)
		{
			foreach ($nodes as $node)
			{
				$extName = (string) $node->attributes()->name;

				if ($extId = $this->searchExtension($extName, 'library'))
				{
					$this->getInstaller()->uninstall('library', $extId);
				}
			}
		}
	}

	/**
	 * Uninstall the package modules
	 *
	 * @param   object  $parent  Class calling this method
	 *
	 * @return  void
	 */
	protected function uninstallModules($parent)
	{
		// Required objects
		$manifest = $parent->get('manifest');

		if ($nodes = $manifest->modules->module)
		{
			foreach ($nodes as $node)
			{
				$extName   = (string) $node->attributes()->name;
				$extClient = (string) $node->attributes()->client;

				if ($extId = $this->searchExtension($extName, 'module'))
				{
					$this->getInstaller()->uninstall('module', $extId);
				}
			}
		}
	}

	/**
	 * Uninstall the package plugins
	 *
	 * @param   object  $parent  Class calling this method
	 *
	 * @return  void
	 */
	protected function uninstallPlugins($parent)
	{
		// Required objects
		$manifest = $parent->get('manifest');

		if ($nodes = $manifest->plugins->plugin)
		{
			$installer = $this->getInstaller();

			foreach ($nodes as $node)
			{
				$extName  = (string) $node->attributes()->name;
				$extGroup = (string) $node->attributes()->group;

				if ($extId = $this->searchExtension($extName, 'plugin', null, $extGroup))
				{
					$installer->uninstall('plugin', $extId);
				}
			}
		}
	}

	/**
	 * Search a extension in the database
	 *
	 * @param   string  $element  Extension technical name/alias
	 * @param   string  $type     Type of extension (component, file, language, library, module, plugin)
	 * @param   string  $state    State of the searched extension
	 * @param   string  $folder   Folder name used mainly in plugins
	 *
	 * @return  integer           Extension identifier
	 */
	protected function searchExtension($element, $type, $state = null, $folder = null)
	{
		$db    = JFactory::getDbo();
		$query = $db->getQuery(true)
			->select('extension_id')
			->from($db->quoteName("#__extensions"))
			->where("type = " . $db->quote($type))
			->where("element = " . $db->quote($element));

		if (!is_null($state))
		{
			$query->where("state = " . (int) $state);
		}

		if (!is_null($folder))
		{
			$query->where("folder = " . $db->quote($folder));
		}

		$db->setQuery($query);

		return $db->loadResult();
	}
}
