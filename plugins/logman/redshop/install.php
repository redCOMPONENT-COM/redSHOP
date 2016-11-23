<?php
/**
 * @package     LOGman
 * @copyright   Copyright (C) 2011 - 2016 Timble CVBA. (http://www.timble.net)
 * @license     GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link        http://www.joomlatools.com
 */

/**
 * LOGman - AdvancedModules installer
 *
 * @author  Arunas Mazeika <https://github.com/amazeika>
 * @package Joomlatools\Component\LOGman
 */
class plgLogmanAdvancedmodulesInstallerScript
{
    /**
     * @var string The current installed LOGman version.
     */
    protected $_logman_ver = null;

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
        if (!$this->_logman_ver) {
            $this->_logman_ver = $this->_getExtensionVersion('com_logman');
        }

        return $this->_logman_ver;
    }

    /**
     * Extension version getter.
     *
     * @param string $element The element name, e.g. com_extman, com_logman, etc.
     * @return mixed|null|string The extension version, null if couldn't be determined.
     */
    protected function _getExtensionVersion($element)
    {
        $version = null;

        $query    = "SELECT manifest_cache FROM #__extensions WHERE element = '{$element}'";
        if ($result = JFactory::getDBO()->setQuery($query)->loadResult()) {
            $manifest = new JRegistry($result);
            $version  = $manifest->get('version', null);
        }

        return $version;
    }
}
