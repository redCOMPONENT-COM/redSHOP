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
		$jsonFile = JPATH_ROOT . '/redshop.json';
		$list     = json_decode(JFile::read($jsonFile));

		if ($list)
		{
			foreach ($list as $index => $item)
			{
				$md5 = $item->md5;

				if (isset($item->path))
				{
					// Get overrides
					$item = $this->getOverrides($item->path);
					$item['md5'] = $md5;
					$item['missing'] = false;
					$item['hacking'] = false;

					// Check if original file exists
					if (!JFile::exists($item['original']))
					{
						$item['missing'] = true;
					}
					else
					{
						// Compare md5
						if (md5_file($item['original']) != $item['md5'])
						{
							$item['hacking'] = true;
						}
					}

					$list[$index] = $item;
				}
			}

			return $list;
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
		$overridePaths = array();

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

		return $overridePaths;
	}

	/**
	 * Get overrides
	 *
	 * @param   string  $originalFile  Original file
	 *
	 * @return  array
	 *
	 * @since   2.1
	 */
	protected function getOverrides($originalFile)
	{
		$overridePaths = array();
		$overrideDirs  = $this->getOverridePaths();

		$formattedFiles = $this->formatOriginalFile($originalFile);

		$overridePaths['original'] = $formattedFiles['original'];

		foreach ($overrideDirs as $index => $overrideDir)
		{
			$overridePath = $overrideDir . '/' . $formattedFiles['trim'];

			// Store override files
			if (JFile::exists($overridePath))
			{
				$overridePaths['overrides'][$index] = $overridePath;
			}
		}

		$overridePaths['overrideDirs'] = $overrideDirs;

		return $overridePaths;
	}

	/**
	 * Format original file
	 *
	 * @param   string  $originalFile  Original file from json
	 *
	 * @return  array
	 *
	 * @since   2.1
	 */
	private function formatOriginalFile($originalFile)
	{
		$return = array();

		// Administrator file
		if (strpos($originalFile, 'component/admin/') !== false)
		{
			$return['original'] = str_replace('component/admin/', JPATH_ADMINISTRATOR . '/components/com_redshop/', $originalFile);
		}

		// Frontend file
		if (strpos($originalFile, 'component/site/') !== false)
		{
			$return['original'] = str_replace('component/site/', JPATH_ROOT . '/components/com_redshop/', $originalFile);
		}

		// Another file
		if (strpos($originalFile, 'component/admin') === false && strpos($originalFile, 'component/site') === false)
		{
			$return['original'] = JPATH_ROOT . '/' . $originalFile;
		}

		$originalFile = str_replace('component/admin/', '', $originalFile);
		$originalFile = str_replace('component/site/', '', $originalFile);

		$return['trim'] = '/' . $originalFile;

		return $return;
	}
}
