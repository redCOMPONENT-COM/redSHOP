<?php
/**
 * @package    LOGman
 * @copyright  Copyright (C) 2008 - 2015 redCOMPONENT.com. All rights reserved.
 * @license    GNU General Public License version 2 or later; see LICENSE
 */

/**
 * redSHOP LOGman plugin installer.
 *
 * @package  Joomlatools\Plugin\LOGman
 *
 * @since    1.0.0
 */
class plgLogmanAdvancedmodulesInstallerScript
{
	/**
	 * @var string The current installed LOGman version.
	 */
	protected $logman_ver = null;

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

		return $return;
	}

	/**
	 * Returns the current version (if any) of LOGman.
	 *
	 * @return string|null The LOGman version if present, null otherwise.
	 */
	public function getLogmanVersion()
	{
		if (!$this->logman_ver)
		{
			$this->logman_ver = $this->_getExtensionVersion('com_logman');
		}

		return $this->_logman_ver;
	}

	/**
	 * Extension version getter.
	 *
	 * @param   string  $element  The element name, e.g. com_extman, com_logman, etc.
	 *
	 * @return mixed|null|string The extension version, null if couldn't be determined.
	 */
	protected function _getExtensionVersion($element)
	{
		$version = null;

		$query = "SELECT manifest_cache FROM #__extensions WHERE element = '{$element}'";
		if ($result = JFactory::getDBO()->setQuery($query)->loadResult())
		{
			$manifest = new JRegistry($result);
			$version  = $manifest->get('version', null);
		}

		return $version;
	}
}
