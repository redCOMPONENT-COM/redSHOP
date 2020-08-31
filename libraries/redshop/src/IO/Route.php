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
     * @param string $url
     * @param bool $xhtml
     * @param int $tls
     * @param false $absolute
     * @param string $lang
     * @return string|null
     * @throws \Exception
     * @since  __DEPLOY_VERSION__
     */
    public static function _($url, $xhtml = true,
                             $tls = \Joomla\CMS\Router\Route::TLS_IGNORE,
                             $absolute = false,
                             $lang = null) {

        if (empty($lang)) {
            $lang = substr(\Redshop\Language\Helper::getLanguage()->getTag(), 0, 2);
        }

        $item = 'lang=' . $lang;
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