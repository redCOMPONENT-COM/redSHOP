<?php
/**
 * @package     RedSHOP
 * @subpackage  Plugin
 *
 * @copyright   Copyright (C) 2005 - 2014 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

defined('_JEXEC') or die;

jimport('joomla.plugin.plugin');

require_once 'helper/override.php';
require_once 'helper/codepool.php';

MVCOverrideHelperCodepool::initialize();

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
	 * On After Initialize
	 *
	 * @access public
	 * @return void
	 */
	public function onAfterInitialise()
	{
		// Override JModuleHelper library class
		$moduleHelperContent = JFile::read(JPATH_LIBRARIES . '/joomla/application/module/helper.php');
		$moduleHelperContent = str_replace('JModuleHelper', 'JModuleHelperLibraryDefault', $moduleHelperContent);
		$moduleHelperContent = str_replace('<?php', '', $moduleHelperContent);
		eval($moduleHelperContent);

		jimport('joomla.application.module.helper');
		JLoader::register('jmodulehelper', dirname(__FILE__) . '/core/module/helper.php', true);
	}

	/**
	 * onAfterRoute function.
	 *
	 * @access public
	 * @return void
	 */
	public function onAfterRoute()
	{
		$app = JFactory::getApplication();
		$option = $app->input->getCmd('option', '');

		if (empty($option) && $app->isSite())
		{
			$menuDefault = JFactory::getApplication()->getMenu()->getDefault();

			if ($menuDefault == 0)
			{
				return;
			}

			$componentID = $menuDefault->componentid;
			$db = JFactory::getDBO();
			$query = $db->getQuery(true)
				->select('element')
				->from($db->qn('#__extensions'))
				->where('id = ' . $db->quote($componentID));
			$db->setQuery($query);
			$option = $db->loadResult();
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
			if (JVERSION > 2.5)
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
				if ($filePath = JPath::find(MVCOverrideHelperCodepool::addCodePath(), $componentFile))
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
							$bufferContent = MVCOverrideHelperOverride::createDefaultClass(JPATH_BASE . '/components/' . $componentFile);
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
		$files           = array();
		$componentName = str_replace('com_', '', $option);
		$app = JFactory::getApplication();
		$isAdmin = '';

		if ($app->isAdmin())
		{
			$isAdmin = 'admin';
		}

		// Check if default controller exists
		if (JFile::exists($JPATH_COMPONENT . '/controller.php'))
		{
			$files[] = $JPATH_COMPONENT . '/controller.php';
		}

		// Check if controllers folder exists
		if (JFolder::exists($JPATH_COMPONENT . '/controllers'))
		{
			$listFiles = JFolder::files($JPATH_COMPONENT . '/controllers', '.php', false, true);
			$files = array_merge($files, $listFiles);
		}

		// Check if models folder exists
		if (JFolder::exists($JPATH_COMPONENT . '/models'))
		{
			$listFiles = JFolder::files($JPATH_COMPONENT . '/models', '.php', false, true);
			$files = array_merge($files, $listFiles);
		}

		// Check if models folder exists
		if (JFolder::exists($JPATH_COMPONENT . '/helpers'))
		{
			if ($listFiles = JFolder::files($JPATH_COMPONENT . '/helpers', '.php', false, true))
			{
				$newListFile = array();

				foreach ($listFiles as $file)
				{
					$newListFile[$componentName . 'helper' . $isAdmin . JFile::stripExt(JFile::getName($file))] = $file;
				}

				$files = array_merge($files, $newListFile);
			}
		}

		// Check if views folder exists
		if (JFolder::exists($JPATH_COMPONENT . '/views'))
		{
			// Reading view folders
			$views = JFolder::folders($JPATH_COMPONENT . '/views');

			foreach ($views as $view)
			{
				// Get view formats files
				$listFiles = JFolder::files($JPATH_COMPONENT . '/views/' . $view, '.php', false, true);
				$files = array_merge($files, $listFiles);
			}
		}

		$return = array();

		// Cleaning files
		foreach ($files as $key => $file)
		{
			$file     = JPath::clean($file);
			$file     = substr($file, strlen(JPATH_BASE . '/components/'));
			$return[$key] = $file;
		}

		return $return;
	}
}
