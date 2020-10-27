<?php

/**
 * @package    Redshop.Modules
 * @subpackage plg_system_redgoogleanalytics
 *
 * @copyright  Copyright (C) 2008 - 2019 redCOMPONENT.com. All rights reserved.
 * @license    GNU General Public License version 2 or later, see LICENSE.
 */
defined('_JEXEC') or die();

use Joomla\Registry\Registry;

JLoader::import('redshop.library');

/**
 * PlgSystemRedGoogleAnalyticsInstallerScript installer class.
 *
 * @package Redshopb.Plugin
 * @since   2.0
 */
class PlgSystemRedGoogleAnalyticsInstallerScript
{
    /**
     * Method to run before an install/update/uninstall method
     *
     * @param   string  $type  The type of change (install, update or discover_install)
     *
     * @return void
     */
    public function preflight($type)
    {
        if ($type == 'update' || $type == 'discover_install') {
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

            if (!empty($version)) {
                $version = new Registry($version);
                $version = $version->get('version');

                if (version_compare($version, '2.0.0', '<')) {
                    $this->getTrackerKeyFromOldRedshop();
                }
            }
        }
    }

    /**
     * Method for get Google Analytics API key from redSHOP config.
     *
     * @return void
     *
     * @since 2.0
     */
    protected function getTrackerKeyFromOldRedshop()
    {
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

        // Set the reset_status parameter to 0 and save the updated parameters
        $pluginParams              = json_decode($pluginParams);
        $pluginParams->tracker_key = \Redshop::getConfig()->get('GOOGLE_ANA_TRACKER_KEY', '');
        $pluginParams              = json_encode($pluginParams);
        $row['params']             = $pluginParams;

        $extensionTable->save($row);
    }
}
