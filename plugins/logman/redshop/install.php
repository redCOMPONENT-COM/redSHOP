<?php
/**
 * @package    LOGman
 * @copyright  Copyright (C) 2008 - 2015 redCOMPONENT.com. All rights reserved.
 * @license    GNU General Public License version 2 or later; see LICENSE
 */
defined('_JEXEC') or die();
use Joomla\Registry\Registry;
/**
 *  PlgButtonProduct installer class.
 *
 * @package  Redshopb.Plugin
 * @since    1.7.0
 */
class PlgLogmanRedshopInstallerScript
{
	/**
	 * @var string The current installed LOGman version.
	 */
	protected $logmanVer = null;

	/**
	 * Method to run before an install/update/uninstall method
	 *
	 * @param   string  $type       The type of change (install, update or discover_install)
	 * @param   object  $installer  Class of calling method
	 *
	 * @return  void
	 */
	public function preflight($type, $installer)
	{
		$return = true;
		$errors = array();

		if (version_compare($this->getLogmanVersion(), '3.0.0', '<'))
		{
			$errors[] = JText::_('This plugin requires a newer LOGman version. Please download the latest version from <a href=http://joomlatools.com target=_blank>joomlatools.com</a> and upgrade.');
			$return   = false;
		}

		if ($return == false && $errors)
		{
			$error = implode('<br />', $errors);
			$installer->getParent()->abort($error);
		}

		if ($type == 'update' || $type == 'discover_install')
		{
			// Reads current (old) version from manifest
			$db = JFactory::getDbo();
			$version = $db->setQuery(
				$db->getQuery(true)
					->select($db->qn('manifest_cache'))
					->from($db->qn('#__extensions'))
					->where($db->qn('type') . ' = ' . $db->q('plugin'))
					->where($db->qn('element') . ' = ' . $db->q('redshop'))
					->where($db->qn('folder')) . ' = ' . $db->q('logman')
			)->loadResult();

			if (!empty($version))
			{
				$version = new Registry($version);
				$version = $version->get('version');

				if (version_compare($version, '3.0.1', '<'))
				{
					$this->deleteOldLanguages();
				}
			}
		}

		return $return;
	}

	/**
	 * Returns the current version (if any) of LOGman.
	 *
	 * @return string|null The LOGman version if present, null otherwise.
	 */
	public function getLogmanVersion()
	{
		if (!$this->logmanVer)
		{
			$this->logmanVer = $this->getExtensionVersion('com_logman');
		}

		return $this->logmanVer;
	}

	/**
	 * Extension version getter.
	 *
	 * @param   string  $element  The element name, e.g. com_extman, com_logman, etc.
	 *
	 * @return mixed|null|string The extension version, null if couldn't be determined.
	 */
	protected function getExtensionVersion($element)
	{
		$version = null;

		$db = JFactory::getDbo();
		$query = $db->getQuery(true);

		$query->select($db->qn('manifest_cache'))
			->from($db->qn('#__extensions'))
			->where($db->qn('type') . ' = ' . $db->q('plugin'))
			->where($db->qn('element') . ' = ' . $db->q('redshop'))
			->where($db->qn('folder')) . ' = ' . $db->q('logman');

		$db->setQuery($query);

		if ($result = $db->loadResult())
		{
			$manifest = new JRegistry($result);
			$version  = $manifest->get('version', null);
		}

		return $version;
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
		$languageFolder = __DIR__ . '/language';
		$joomlaLanguageFolder = JPATH_SITE . '/language';
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
}
