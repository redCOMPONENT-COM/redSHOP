<?php
/**
 * @package     RedShop
 * @subpackage  Workflow
 *
 * @copyright   Copyright (C) 2008 - 2020 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

namespace Redshop\IO;

defined('_JEXEC') or die;

/**
 * Route Helper
 *
 * @since  __DEPLOY_VERION__
 */

class Route
{
    /**
     * @param $url
     * @param null $lang
     * @return string|null
     * @since  __DEPLOY_VERSION__
     */
    public static function _($url, $lang = null) {

        $lang = $lang ?? \Redshop\Language\Helper::getLanguage();
        $url = self::addParamToUrl($url, $lang);

        return \Joomla\CMS\Router\Route::_($url);
    }

    /**
     * @param $url
     * @param $item
     * @since  __DEPLOY_VERSION__
     */
    public static function addParamToUrl($url, $item) {
        if (empty($url)) {
            return $url;
        }

        $params = explode('&', $url);
        $params[] = $item;
        $url = implode('&', $params);

        return $url;
    }
}