<?php
/**
 * @package     RedSHOP
 * @subpackage  Plugin
 *
 * @copyright   Copyright (C) 2005 - 2015 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

defined('_JEXEC') or die;

jimport('joomla.plugin.plugin');
jimport('joomla.filesystem.folder');

require_once 'helper/override.php';
require_once 'helper/codepool.php';

/**
 * PlgSystemMVCOverride class.
 *
 * @extends JPlugin
 * @since  2.5
 */
class PlgSystemMVCOverride extends JPlugin
{
	/**
	 * Load the language file on instantiation.
	 *
	 * @var    boolean
	 */
	protected $autoloadLanguage = true;

	protected static $option;

	protected $files = array();

	/**
	 * Constructor
	 *
	 * @param   object  &$subject  The object to observe
	 * @param   array   $config    An optional associative array of configuration settings.
	 *                             Recognized key values include 'name', 'group', 'params', 'language'
	 *                             (this list is not meant to be comprehensive).
	 */
	public function __construct(&$subject, $config = array())
	{
		JPlugin::loadLanguage('plg_system_mvcoverride');
		parent::__construct($subject, $config);
	}

	/**
	 * onAfterRoute function.
	 *
	 * @access public
	 * @return void
	 */
	public function onAfterRoute()
	{
		MVCOverrideHelperCodepool::initialize();
		$option = $this->getOption();

		if ($option === false)
		{
			return;
		}

		// Get files that can be overrided
		$componentOverrideFiles = $this->loadComponentFiles($option);

		// Application name
		$applicationName = JFactory::getApplication()->getName();

		// Template name
		$template = JFactory::getApplication()->getTemplate();

		// Code paths
		$includePath = array();

		// Base extensions path
		$includePath[] = JPATH_BASE . '/code';

		// Template code path
		$includePath[] = JPATH_THEMES . '/' . $template . '/code';

		MVCOverrideHelperCodepool::addCodePath($includePath);

		foreach (MVCOverrideHelperCodepool::addCodePath() as $codePool)
		{
			if (version_compare(JVERSION, '3.0', '>='))
			{
				JModelLegacy::addIncludePath($codePool . '/' . $option . '/models');
				JViewLegacy::addViewHelperPath($codePool . '/' . $option);
				JViewLegacy::addViewTemplatePath($codePool . '/' . $option);
			}
			else
			{
				JModel::addIncludePath($codePool . '/' . $option . '/models');
				JView::addViewHelperPath($codePool . '/' . $option);
				JView::addViewTemplatePath($codePool . '/' . $option);
			}

			JModuleHelper::addIncludePath($codePool . '/modules');
			JTable::addIncludePath($codePool . '/' . $option . '/tables');

			JModelForm::addComponentFormPath($codePool . '/' . $option . '/models/forms');
			JModelForm::addComponentFieldPath($codePool . '/' . $option . '/models/fields');
		}

		// Constants to replace JPATH_COMPONENT, JPATH_COMPONENT_SITE and JPATH_COMPONENT_ADMINISTRATOR
		define('JPATH_SOURCE_COMPONENT', JPATH_BASE . '/components/' . $option);
		define('JPATH_SOURCE_COMPONENT_SITE', JPATH_SITE . '/components/' . $option);
		define('JPATH_SOURCE_COMPONENT_ADMINISTRATOR', JPATH_ADMINISTRATOR . '/components/' . $option);

		// Loading override files
		if (!empty($componentOverrideFiles))
		{
			foreach ($componentOverrideFiles as $key => $componentFile)
			{
				if ($filePath = JPath::find(MVCOverrideHelperCodepool::addCodePath(null, true), $componentFile->newPath))
				{
					// Include the original code and replace class name add a Default on
					if ($this->params->get('extendDefault', 0))
					{
						$bufferOverrideFile = JFile::read($filePath);

						// Detect if override file use some constants
						preg_match_all('/JPATH_COMPONENT(_SITE|_ADMINISTRATOR)|JPATH_COMPONENT/i', $bufferOverrideFile, $definesSourceOverride);

						if (count($definesSourceOverride[0]))
						{
							JError::raiseError(
								JText::_('PLG_SYSTEM_MVC_OVERRIDE_PLUGIN'),
								JText::_('PLG_SYSTEM_MVC_OVERRIDE_ERROR')
							);
						}
						else
						{
							$bufferContent = MVCOverrideHelperOverride::createDefaultClass($componentFile->root . $componentFile->path);
							$bufferContent = MVCOverrideHelperOverride::fixDefines($bufferContent);

							// Change private methods to protected methods
							if ($this->params->get('changePrivate', 0))
							{
								$bufferContent = preg_replace(
									'/private *function/i',
									'protected function',
									$bufferContent
								);
							}

							// Finally we can load the base class
							MVCOverrideHelperOverride::load($bufferContent);

							// Load helpers
							if (!is_int($key))
							{
								JLoader::register($key, $filePath);
							}

							require_once $filePath;
						}
					}
					else
					{
						require_once $filePath;
					}
				}
			}
		}
	}

	/**
	 * Get file info
	 *
	 * @param   string  $path  Path
	 * @param   string  $side  Side execute
	 * @param   string  $type  Type files
	 *
	 * @return stdClass
	 */
	private function getFileInfo($path, $side = 'component', $type = '')
	{
		$object = new stdClass;
		$object->path = JPath::clean($path);
		$object->side = $side;
		$app = JFactory::getApplication();

		// Cleaning files
		switch ($side)
		{
			case 'component':
				$object->path = substr($object->path, strlen(JPATH_BASE . '/components/'));
				$object->root = JPATH_BASE . '/components/';
				break;
			case 'site':
				$object->path = substr($object->path, strlen(JPATH_SITE . '/components/'));
				$object->root = JPATH_SITE . '/components/';
				break;
			case 'admin':
				$object->path = substr($object->path, strlen(JPATH_ADMINISTRATOR . '/components/'));
				$object->root = JPATH_ADMINISTRATOR . '/components/';
				break;
		}

		if ((($app->isAdmin() && $side == 'component') || $side == 'admin') && $type == 'helper')
		{
			$object->newPath = str_replace(JFile::getName($object->path), 'admin' . JFile::getName($object->path), $object->path);
		}
		else
		{
			$object->newPath = $object->path;
		}

		return $object;
	}

	/**
	 * Get option
	 *
	 * @return bool|mixed|string
	 */
	private function getOption()
	{
		if (self::$option)
		{
			return self::$option;
		}

		$app = JFactory::getApplication();
		self::$option = $app->input->getCmd('option', '');

		if (empty(self::$option) && $app->isSite())
		{
			$menuDefault = JFactory::getApplication()->getMenu()->getDefault();

			if ($menuDefault == 0)
			{
				return false;
			}

			$componentID = $menuDefault->componentid;
			$db = JFactory::getDBO();
			$query = $db->getQuery(true)
				->select('element')
				->from($db->qn('#__extensions'))
				->where('id = ' . $db->quote($componentID));
			$db->setQuery($query);
			self::$option = $db->loadResult();
		}

		return self::$option;
	}

	/**
	 * Add new files
	 *
	 * @param   string  $folder  Name folder
	 * @param   string  $type    Type files
	 * @param   string  $side    Side execute
	 *
	 * @return void
	 */
	private function addNewFiles($folder, $type, $side = 'component')
	{
		if (!JFolder::exists($folder))
		{
			return;
		}

		$app = JFactory::getApplication();
		$componentName = str_replace('com_', '', $this->getOption());

		switch ($type)
		{
			case 'helper':
				if ($listFiles = JFolder::files($folder, '.php', false, true))
				{
					foreach ($listFiles as $file)
					{
						if (($app->isAdmin() && $side == 'component') || $side == 'admin')
						{
							$indexName = $componentName . 'helperadmin' . JFile::stripExt(JFile::getName($file));
						}
						else
						{
							$indexName = $componentName . 'helper' . JFile::stripExt(JFile::getName($file));
						}

						$this->files[$indexName] = $this->getFileInfo($file, $side, $type);
					}
				}
				break;
			case 'view':
				// Reading view folders
				if ($views = JFolder::folders($folder))
				{
					foreach ($views as $view)
					{
						// Get view formats files
						if ($listFiles = JFolder::files($folder . '/' . $view, '.php', false, true))
						{
							foreach ($listFiles as $file)
							{
								$this->files[] = $this->getFileInfo($file, $side);
							}
						}
					}
				}
				break;
			default:
				if ($listFiles = JFolder::files($folder, '.php', false, true))
				{
					foreach ($listFiles as $file)
					{
						$this->files[] = $this->getFileInfo($file, $side);
					}
				}
		}
	}

	/**
	 * loadComponentFiles function.
	 *
	 * @param   mixed  $option  Component name
	 *
	 * @access private
	 *
	 * @return array
	 */
	private function loadComponentFiles($option)
	{
		$JPATH_COMPONENT = JPATH_BASE . '/components/' . $option;

		// Check if default controller exists
		if (JFile::exists($JPATH_COMPONENT . '/controller.php'))
		{
			$this->files[] = $this->getFileInfo($JPATH_COMPONENT . '/controller.php');
		}

		$this->addNewFiles($JPATH_COMPONENT . '/controllers', 'controller');
		$this->addNewFiles($JPATH_COMPONENT . '/models', 'model');
		$this->addNewFiles(JPATH_SITE . '/components/' . $option . '/helpers', 'helper', 'site');
		$this->addNewFiles(JPATH_ADMINISTRATOR . '/components/' . $option . '/helpers', 'helper', 'admin');
		$this->addNewFiles($JPATH_COMPONENT . '/views', 'view');

		return $this->files;
	}
}
