<?php
/**
 * @package     Joomla.Site
 * @subpackage  mod_login
 *
 * @copyright   Copyright (C) 2005 - 2018 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die;

use Joomla\CMS\Factory;
use Joomla\CMS\Language\Multilanguage;

/**
 * Helper for mod_login
 *
 * @since  1.5
 */
class ModRedshopLoginHelper
{
    /**
     * @param $params
     * @param $type
     *
     * @return string
     * @throws Exception
     * @since  1.0
     */
    public static function getReturnUrl($params, $type)
    {
        $app  = JFactory::getApplication();
        $item = $app->getMenu()->getItem($params->get($type));

        // Stay on the same page
        $url = JUri::getInstance()->toString();

        if ($item) {
            $lang = '';

            if ($item->language !== '*' && Multilanguage::isEnabled()) {
                $lang = '&lang=' . $item->language;
            }

            $url = 'index.php?Itemid=' . $item->id . $lang;
        }

        return base64_encode($url);
    }

    /**
     * Returns the current users type
     *
     * @return string
     * @since  1.0
     */
    public static function getType()
    {
        $user = Factory::getApplication()->getIdentity();

        return (!$user->get('guest')) ? 'logout' : 'login';
    }

    /**
     * Get list of available two factor methods
     *
     * @return array
     *
     * @deprecated  4.0  Use JAuthenticationHelper::getTwoFactorMethods() instead.
     * @since       1.0
     */
    public static function getTwoFactorMethods()
    {
        JLog::add(
            __METHOD__ . ' is deprecated, use JAuthenticationHelper::getTwoFactorMethods() instead.',
            JLog::WARNING,
            'deprecated'
        );

        return JAuthenticationHelper::getTwoFactorMethods();
    }
}
