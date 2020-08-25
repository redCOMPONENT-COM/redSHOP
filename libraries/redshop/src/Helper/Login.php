<?php
/**
 * @package     RedShop
 * @subpackage  Libraries
 *
 * @copyright   Copyright (C) 2008 - 2019 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

namespace Redshop\Helper;

defined('_JEXEC') or die;

/**
 * Login helper class
 *
 * @since  __DEPLOY_VERSION__
 */
class Login
{
    /**
     * @param $data
     *
     * @throws \Exception
     */
    public static function loginJoomlaRedShop($data) {
        $app = \JFactory::getApplication();
        $jUser = \JUserHelper::getUserId($data['email']);

        if ($jUser > 0) {
            $jUser = \JFactory::getUser($jUser);

            \JPluginHelper::importPlugin('user');

            $options           = array();
            $options['action'] = 'core.login.site';

            $result = $app->triggerEvent('onUserLogin', array($data, $options));
        } else {
            $jUser = \RedshopHelperJoomla::createJoomlaUser($data, 1);
        }

        $check = \RedshopHelperUser::getUserInformation($jUser->id);

        if (empty($check)) {
            $redUser = \RedshopHelperUser::storeRedshopUser($data, $jUser->id);
        }

        $app->redirect(\JUri::root());
    }

    /**
     * @return array
     */
    public static function getThirdPartyLogin() {
        \JPluginHelper::importPlugin('redshop_login');
       return \RedshopHelperUtility::getDispatcher()->trigger('onThirdPartyLogin');
    }
}