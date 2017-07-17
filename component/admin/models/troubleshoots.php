<?php
/**
 * @package     RedSHOP.Backend
 * @subpackage  Model
 *
 * @copyright   Copyright (C) 2008 - 2017 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

defined('_JEXEC') or die;

use Joomla\Registry\Registry;

/**
 * Redshop troubleshoots model
 *
 * @package     Redshop.library
 * @subpackage  Model
 * @since       2.0.6
 */
class RedshopModelTroubleshoots extends RedshopModel
{
	/**
	 * @var    object
	 * @since  2.0.6
	 */
	private $plugin = null;

	/**
	 * Constructor.
	 *
	 * @param   array $config An optional associative array of configuration settings.
	 *
	 * @see     JModelLegacy
	 * @since   2.0.6
	 */
	public function __construct($config = array())
	{
		parent::__construct($config = array());

		$this->plugin = JPluginHelper::getPlugin('system', 'mvcoverride');

		// Convert plugin prams
		if ($this->plugin)
		{
			$this->plugin->params = new Registry($this->plugin->params);
		}
	}

	/**
	 * Get data
	 *
	 * @return  mixed  List of files if success. False otherwise.
	 *
	 * @since   2.0.6
	 */
	public function getData()
	{
		$jsonFile = JPATH_ADMINISTRATOR . '/components/com_redshop/assets/checksum.md5.json';

		if (!JFile::exists($jsonFile))
		{
			return false;
		}

		$items = json_decode(file_get_contents($jsonFile));

		if (empty($items))
		{
			return false;
		}

		$list = array();

		foreach ($items as $index => $item)
		{
			if (!isset($item->path))
			{
				continue;
			}

			// Get overrides
			$item = new RedshopTroubleshootItem($item, $this->getOverridePaths());

			if ($item->isModified() || $item->isOverrided() || $item->isMissing())
			{
				$list[] = $item;
			}
		}

		return $list;
	}

	/**
	 * Check if mvcoverride plugin is enabled
	 *
	 * @return  boolean
	 *
	 * @since  2.0.6
	 */
	protected function isMvcPluginEnabled()
	{
		return JPluginHelper::isEnabled('system', 'mvcoverride');
	}

	/**
	 * Get array of override paths
	 *
	 * @return array
	 *
	 * @since  2.0.6
	 */
	private function getOverridePaths()
	{
		static $overridePaths;

		if (empty($overridePaths))
		{
			// Add MVC Overrides checking
			if ($this->isMvcPluginEnabled())
			{
				// Get mvc overrides
				$pluginIncludePaths = explode(',', $this->plugin->params->get('includePath'));

				// MVC Overrides
				if ($pluginIncludePaths)
				{
					foreach ($pluginIncludePaths as $pluginIncludePath)
					{
						if (strpos($pluginIncludePath, '{JPATH_BASE}') !== false)
						{
							$overridePaths[] = str_replace('{JPATH_BASE}', JPATH_ADMINISTRATOR, $pluginIncludePath);
							$overridePaths[] = str_replace('{JPATH_BASE}', JPATH_ROOT, $pluginIncludePath);
						}

						if (strpos($pluginIncludePath, '{JPATH_THEMES}') !== false)
						{
							if (strpos($pluginIncludePath, '{template}') !== false)
							{
								$jpathThemes     = str_replace('{JPATH_THEMES}', JPATH_ADMINISTRATOR . '/templates', $pluginIncludePath);
								$overridePaths[] = str_replace('{template}', JFactory::getApplication('site')->getTemplate(), $jpathThemes);
								$jpathThemes     = str_replace('{JPATH_THEMES}', JPATH_ROOT . '/templates', $pluginIncludePath);
								$overridePaths[] = str_replace('{template}', JFactory::getApplication('administrator')->getTemplate(), $jpathThemes);
							}
							else
							{
								$overridePaths[] = str_replace('{JPATH_THEMES}', JPATH_ADMINISTRATOR . '/templates', $pluginIncludePath);
								$overridePaths[] = str_replace('{JPATH_THEMES}', JPATH_ROOT . '/templates', $pluginIncludePath);
							}
						}
					}
				}
			}

			// Joomla! Overrides
			$db = $this->getDbo();
			$query = $db->getQuery(true)
				->select($db->qn('template'))
				->from($db->qn('#__template_styles'))
				->where($db->qn('client_id') . ' = 0')
				->where($db->qn('home') . ' = 1');
			$templateName = $db->setQuery($query)->loadResult();
			$overridePaths[] = JPATH_SITE . '/templates/' . $templateName . '/com_redshop';

			// $overridePaths[] = JPATH_ADMINISTRATOR . '/templates/' . JFactory::getApplication()->getTemplate() . '/com_redshop';
		}

		return $overridePaths;
	}
}
