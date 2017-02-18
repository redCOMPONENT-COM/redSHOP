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
	 * Old manifest data
	 *
	 * @var  array
	 */
	public static $oldManifest = null;

	/**
	 * Install type
	 *
	 * @var   string
	 */
	protected $type = null;

	protected $installedPlugins = array();

	/**
	 * Method to install the component
	 *
	 * @param   object  $parent  Class calling this method
	 *
	 * @return void
	 */
	public function install($parent)
	{
		// Install extensions
		$this->installLibraries($parent);
		$this->installModules($parent);
		$this->installPlugins($parent);

		JFactory::getApplication()->redirect(JRoute::_('index.php?option=com_redshop&view=install&install_type=install', false));
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
	 * method to update the component
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

		JFactory::getApplication()->redirect(JRoute::_('index.php?option=com_redshop&view=install&install_type=update', false));
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
		$this->getInstalledPlugin($parent);
	}

	/**
	 * Get list array of installed plugins
	 *
	 * @param   object  $parent  Parent data
	 *
	 * @return  array
	 */
	private function getInstalledPlugin($parent)
	{
		// Check if plugins are installed or not. Query here to prevent duplicate query inside another method
		// Required objects
		$manifest = $parent->get('manifest');

		if ($nodes = $manifest->plugins->plugin)
		{
			$db = JFactory::getDbo();

			foreach ($nodes as $node)
			{
				$extName  = (string) $node->attributes()->name;
				$extGroup = (string) $node->attributes()->group;

				$query = $db->getQuery(true)
					->select('*')
					->from($db->qn('#__extensions'))
					->where('type = ' . $db->quote('plugin'))
					->where('element = ' . $db->quote($extName))
					->where('folder = ' . $db->quote($extGroup));

				$this->installedPlugins[$extGroup][$extName] = $db->setQuery($query, 0, 1)->loadObject();
			}
		}

		return $this->installedPlugins;
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
	private function installLibraries($parent)
	{
		// Required objects
		$manifest = $parent->get('manifest');
		$src      = $parent->getParent()->getPath('source');

		if ($nodes = $manifest->libraries->library)
		{
			foreach ($nodes as $node)
			{
				$extName = (string) $node->attributes()->name;
				$extPath = $src . '/libraries/' . $extName;
				$result  = 0;

				if (is_dir($extPath))
				{
					$result = $this->getInstaller()->install($extPath);
				}

				$this->storeStatus('libraries', array('name' => $extName, 'result' => $result));
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
	private function installModules($parent)
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
				$result    = 0;

				if (is_dir($extPath))
				{
					$result = $this->getInstaller()->install($extPath);
				}

				$this->storeStatus(
					'modules',
					array(
						'name'   => $extName,
						'client' => $extClient,
						'result' => $result
					)
				);
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
	private function installPlugins($parent)
	{
		// Required objects
		$manifest = $parent->get('manifest');
		$src      = $parent->getParent()->getPath('source');

		if ($nodes = $manifest->plugins->plugin)
		{
			foreach ($nodes as $node)
			{
				$extName  = (string) $node->attributes()->name;
				$extGroup = (string) $node->attributes()->group;
				$extPath  = $src . '/plugins/' . $extGroup . '/' . $extName;
				$result   = 0;

				$extensionId = 0;

				if (isset($this->installedPlugins[$extGroup][$extName]))
				{
					$extensionId = $this->installedPlugins[$extGroup][$extName]->extension_id;
				}

				// Install or upgrade plugin
				if (is_dir($extPath))
				{
					$result = $this->getInstaller()->install($extPath);
				}

				// Store the result to show install summary later
				$this->storeStatus(
					'plugins',
					array(
						'name'   => $extName,
						'group'  => $extGroup,
						'result' => $result
					)
				);

				// We'll not enable plugin for update case
				if ($this->type != 'update')
				{
					// For another rest type cases
					// Do not change plugin state if it's installed
					if ($result && !$extensionId)
					{
						// If plugin is installed successfully and it didn't exist before we enable it.
						$this->enablePlugin($extName, $extGroup);
					}
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
	private function enablePlugin($extName, $extGroup, $state = 1)
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
	private function uninstallLibraries($parent)
	{
		// Required objects
		$manifest = $parent->get('manifest');

		if ($nodes = $manifest->libraries->library)
		{
			foreach ($nodes as $node)
			{
				$extName = (string) $node->attributes()->name;
				$result  = 0;

				$db    = JFactory::getDbo();
				$query = $db->getQuery(true)
					->select('extension_id')
					->from($db->quoteName("#__extensions"))
					->where("type='library'")
					->where("element=" . $db->quote($extName));

				$db->setQuery($query);

				if ($extId = $db->loadResult())
				{
					$result = $this->getInstaller()->uninstall('library', $extId);
				}

				// Store the result to show install summary later
				$this->storeStatus('libraries', array('name' => $extName, 'result' => $result));
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
	private function uninstallModules($parent)
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
				$result    = 0;

				$db    = JFactory::getDbo();
				$query = $db->getQuery(true)
					->select('extension_id')
					->from($db->quoteName("#__extensions"))
					->where("type='module'")
					->where("element=" . $db->quote($extName));

				$db->setQuery($query);

				if ($extId = $db->loadResult())
				{
					$result = $this->getInstaller()->uninstall('module', $extId);
				}

				// Store the result to show install summary later
				$this->storeStatus(
					'modules',
					array(
						'name'   => $extName,
						'client' => $extClient,
						'result' => $result
					)
				);
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
	private function uninstallPlugins($parent)
	{
		// Required objects
		$manifest = $parent->get('manifest');
		$src      = $parent->getParent()->getPath('source');

		if ($nodes = $manifest->plugins->plugin)
		{
			foreach ($nodes as $node)
			{
				$extName  = (string) $node->attributes()->name;
				$extGroup = (string) $node->attributes()->group;
				$extPath  = $src . '/plugins/' . $extGroup . '/' . $extName;
				$result   = 0;

				$db    = JFactory::getDbo();
				$query = $db->getQuery(true)
					->select('extension_id')
					->from($db->quoteName("#__extensions"))
					->where("type='plugin'")
					->where("element=" . $db->quote($extName))
					->where("folder=" . $db->quote($extGroup));

				$db->setQuery($query);

				if ($extId = $db->loadResult())
				{
					$result = $this->getInstaller()->uninstall('plugin', $extId);
				}

				// Store the result to show install summary later
				$this->storeStatus(
					'plugins',
					array(
						'name'   => $extName,
						'group'  => $extGroup,
						'result' => $result
					)
				);
			}
		}
	}

	/**
	 * Store the result of trying to install an extension
	 *
	 * @param   string  $type    Type of extension (libraries, modules, plugins)
	 * @param   array   $status  The status info
	 *
	 * @return void
	 */
	private function storeStatus($type, $status)
	{
		// Initialise status object if needed
		if (is_null($this->status))
		{
			$this->status = new stdClass;
		}

		// Initialise current status type if needed
		if (!isset($this->status->{$type}))
		{
			$this->status->{$type} = array();
		}

		// Insert the status
		array_push($this->status->{$type}, $status);
	}
}
