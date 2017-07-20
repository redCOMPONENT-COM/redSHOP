<?php
/**
 * @package     Redshop.Plugin
 * @subpackage  Bring
 *
 * @copyright   Copyright (C) 2012 - 2017 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later, see LICENSE.
 */

use Joomla\Registry\Registry;

defined('_JEXEC') or die();

/**
 * PlgRedshop_ShippingFedex installer class.
 *
 * @package  Redshopb.Plugin
 * @since    2.0.0
 */
class PlgRedshop_ShippingFedexInstallerScript
{
	/**
	 * Method to run before an install/update/uninstall method
	 *
	 * @param   string  $type  The type of change (install, update or discover_install)
	 *
	 * @return  void
	 */
	public function preflight($type)
	{
		if ($type == 'update' || $type == 'discover_install')
		{
			// Reads current (old) version from manifest
			$db = JFactory::getDbo();
			$version = $db->setQuery(
				$db->getQuery(true)
					->select($db->qn('manifest_cache'))
					->from($db->qn('#__extensions'))
					->where($db->qn('type') . ' = ' . $db->quote('plugin'))
					->where($db->qn('folder') . ' = ' . $db->quote('redshop_shipping'))
					->where($db->qn('element') . ' = ' . $db->quote('fedex'))
			)
				->loadResult();

			if (!empty($version))
			{
				$version = new Registry($version);
				$version = $version->get('version');

				if (version_compare($version, '2.0.0', '<'))
				{
					$this->deleteOldLanguages();
				}
			}
		}

		if ($type != 'uninstall')
		{
			$this->checkConfig();
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

		// Remove old languages structure.
		$languageFolder = __DIR__ . '/language';
		$joomlaLanguageFolder = JPATH_ADMINISTRATOR . '/language';
		$codes = JFolder::folders($languageFolder, '.', true);

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
	 * Method for check config file.
	 *
	 * @return  void
	 *
	 * @since   2.0.0
	 */
	protected function checkConfig()
	{
		$configFile = JPATH_ROOT . '/plugins/redshop_shipping/fedex/config/fedex.cfg.php';

		if (JFile::exists($configFile))
		{
			return;
		}

		// Move old config file to new folder structure.
		$oldConfigFile = JPATH_ROOT . '/plugins/redshop_shipping/fedex/fedex.cfg.php';

		if (JFile::exists($oldConfigFile))
		{
			JFile::move($oldConfigFile, $configFile);

			return;
		}

		// Copy default to config file if not exist.
		$defaultFile = JPATH_ROOT . '/plugins/redshop_shipping/fedex/config/default.cfg.php';

		JFile::copy($defaultFile, $configFile);
	}
}
