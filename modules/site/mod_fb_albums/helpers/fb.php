<?php
/**
 * @package     RedSHOP.Module
 * @subpackage  mod_fb_albums
 *
 * @copyright   Copyright (C) 2008 - 2017 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

defined('_JEXEC') or die;

/**
 * Helper for mod_fb_albums
 *
 * @since  2.0.4
 */
abstract class ModFbAlbumsHelper
{
    /**
     * Retrieve a list of FB albums
     *
     * @param   JRegistry  &$params  module parameters
     *
     * @return  mixed
     *
     * @since   2.0.4
     */
    public static function getList(&$params)
    {
        $accessToken = $params->get('access_token', '');
        $page = $params->get('fb_page', '');
        $url = 'https://graph.facebook.com/v2.8/'
            . $page
            . '?fields=albums{name,%20photos{name,%20picture,%20tags}}'
            . '&access_token=' . $accessToken;

        $output = self::doCurl($url);

        return json_decode($output);
    }

    /**
     * doCurl function
     *
     * @param   string  $url  URL that curl will call
     *
     * @return  mixed from curl
     */
    public static function doCurl($url)
    {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        $output = curl_exec($ch);
        curl_close($ch);

        return $output;
    }
}
