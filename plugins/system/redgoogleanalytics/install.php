<?php
/**
 * @package     Redshop.Modules
 * @subpackage  plg_system_redgoogleanalytics
 *
 * @copyright   Copyright (C) 2012 - 2017 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later, see LICENSE.
 */
defined('_JEXEC') or die();

use Joomla\Registry\Registry;

/**
 * PlgSystemRedGoogleAnalyticsInstallerScript installer class.
 *
 * @package  Redshopb.Plugin
 * @since    2.0
 */
class PlgSystemRedGoogleAnalyticsInstallerScript
{
	/**
	 * Method to run before an install/update/uninstall method
	 *
	 * @param   string  $type   The type of change (install, update or discover_install)
	 *
	 * @return  void
	 */
	public function preflight($type)
	{
		if ($type == 'update' || $type == 'discover_install')
		{
			// Reads current (old) version from manifest
			$db      = JFactory::getDbo();
			$version = $db->setQuery(
				$db->getQuery(true)
					->select($db->qn('manifest_cache'))
					->from($db->qn('#__extensions'))
					->where($db->qn('type') . ' = ' . $db->q('plugin'))
					->where($db->qn('element') . ' = ' . $db->q('redgoogleanalytics'))
					->where($db->qn('folder')) . ' = ' . $db->q('system')
			)->loadResult();

			if (!empty($version))
			{
				$version = new Registry($version);
				$version = $version->get('version');

				if (version_compare($version, '2.0.0', '<'))
				{
					$this->deleteOldLanguages();
					$this->getTrackerKeyFromOldRedshop();
				}
			}
		}
	}

	/**
	 * Method for delete old languages files in core language folder of Joomla
	 *
	 * @return  void
	 */
	protected function deleteOldLanguages()
	{
		// Delete old languages files if necessary
		JLoader::import('joomla.filesystem.file');
		$languageFolder       = __DIR__ . '/language';
		$joomlaLanguageFolder = JPATH_SITE . '/language';
		$codes                = JFolder::folders($languageFolder, '.', true);

		if (empty($codes))
		{
			return;
		}

		foreach ($codes as $code)
		{
			$files = JFolder::files($languageFolder . '/' . $code, '.ini');

			if (empty($files))
			{
				continue;
			}

			foreach ($files as $file)
			{
				if (!JFile::exists($joomlaLanguageFolder . '/' . $code . '/' . $file))
				{
					continue;
				}

				JFile::delete($joomlaLanguageFolder . '/' . $code . '/' . $file);
			}
		}
	}

	/**
	 * Method for get Google Analytics API key from redSHOP config.
	 *
	 * @return  void
	 *
	 * @since   2.0
	 */
	protected function getTrackerKeyFromOldRedshop()
	{
		// Get and load the plugin from the extension table

		/** @var JTableExtension $extensionTable */
		$extensionTable = JTable::getInstance('Extension');

		$pluginId = $extensionTable->find(
			array(
				'element' => 'redgoogleanalytics',
				'type'    => 'plugin',
				'folder'  => 'system',
				'enabled' => 1
			)
		);

		$extensionTable->load($pluginId);
		$pluginParams = $extensionTable->get('params');

		jimport('redshop.library');

		// Set the reset_status parameter to 0 and save the updated parameters
		$pluginParams              = json_decode($pluginParams);
		$pluginParams->tracker_key = Redshop::getConfig()->get('GOOGLE_ANA_TRACKER_KEY', '');
		$pluginParams              = json_encode($pluginParams);
		$row['params']             = $pluginParams;

		$extensionTable->save($row);
	}
}
