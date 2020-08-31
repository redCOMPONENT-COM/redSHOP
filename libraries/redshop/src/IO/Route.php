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
     * @param bool $xhtml
     * @param  $tls
     * @param bool $absolute
     * @param null $lang
     * @return string|null
     * @since  __DEPLOY_VERSION__
     */
    public static function _($url, $xhtml = true,
                             $tls = \Joomla\CMS\Router\Route::TLS_IGNORE,
                             $absolute = false,
                             $lang = null) {

        $lang = $lang ?? \Redshop\Language\Helper::getLanguage();
        $item = 'lang=' . substr($lang->getTag(), 0, 2);
        $condition = !\Joomla\CMS\Factory::getApplication()->isClient('administrator')
            && strpos($url, 'lang') === false;

        if ($condition) {
            $url = self::addParamToUrl($url, $item);
        }

        return \Joomla\CMS\Router\Route::_($url, $xhtml, $tls, $absolute);
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