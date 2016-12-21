<?php
/**
 * @package     RedSHOP.Backend
 * @subpackage  Model
 *
 * @copyright   Copyright (C) 2008 - 2016 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

defined('_JEXEC') or die;

/**
 * Redshop troubleshoots model
 *
 * @package     Redshop.library
 * @subpackage  Model
 * @since       2.1
 */
class RedshopModelTroubleshoots extends RedshopModel
{

	/**
	 * @var    object
	 * @since  2.1
	 */
	private $plugin = null;

	/**
	 * Constructor.
	 *
	 * @param   array  $config  An optional associative array of configuration settings.
	 *
	 * @see     JModelLegacy
	 */
	public function __construct($config = array())
	{
		parent::__construct($config = array());

		$this->plugin = JPluginHelper::getPlugin('system', 'mvcoverride');

		// Convert plugin prams
		if ($this->plugin)
		{
			$this->plugin->params = new JRegistry($this->plugin->params);
		}
	}

	/**
	 * Get data
	 *
	 * @return bool|mixed
	 *
	 * @since  2.1
	 */
	public function getData()
	{
		$jsonFile = JPATH_ADMINISTRATOR . '/components/com_redshop/assets/checksum.md5.json';

		$list = array ();

		if (JFile::exists($jsonFile))
		{
			$items     = json_decode(file_get_contents($jsonFile));

			if ($items)
			{
				foreach ($items as $index => $item)
				{
					if (isset($item->path))
					{
						// Get overrides
						$item = new RedshopTroubleshootItem($item, $this->getOverridePaths());

						if ($item->isModified() || $item->isOverrided() || $item->isMissing())
						{
							$list[] = $item;
						}
					}
				}

				return $list;
			}
		}

		return false;
	}

	/**
	 * Check if mvcoverride plugin is enabled
	 *
	 * @return bool
	 *
	 * @since  2.1
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
	 * @since  2.1
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
			$overridePaths[] = JPATH_ADMINISTRATOR . '/templates/' . JFactory::getApplication('administrator')->getTemplate() . '/com_redshop';
			$overridePaths[] = JPATH_SITE . '/templates/' . JFactory::getApplication('site')->getTemplate() . '/com_redshop';
		}

		return $overridePaths;
	}
}
