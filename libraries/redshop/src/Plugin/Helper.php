<?php
/**
 * @package     RedShop
 * @subpackage  Helper
 *
 * @copyright   Copyright (C) 2008 - 2019 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

namespace Redshop\Plugin;

defined('_JEXEC') or die;

/**
 * Helper Plugin
 *
 * @since  __DEPLOY_VERSION__
 */
class Helper
{
    /**
     * @param $pluginGroup
     * @param $pluginName
     * @param $function
     * @param array $params
     * @since __DEPLOY_VERSION__
     */
    public static function invoke($pluginGroup, $pluginName, $function, $params = []) {
        if ($pluginGroup == 'redshop_promotion') {
            echo '<pre>';
            var_dump($pluginName);
            var_dump($pluginGroup);
            var_dump($function);
            var_dump($params);
            die;
        }

        if (empty($pluginName)) {
            \JPluginHelper::importPlugin($pluginGroup);
        } else {
            \JPluginHelper::importPlugin($pluginGroup, $pluginName);
        }

        $dispatcher = \RedshopHelperUtility::getDispatcher();
        return $dispatcher->trigger($function, $params);
    }
}