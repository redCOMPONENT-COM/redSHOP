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
        $accessTokenObj = self::getToken($params);


        if (isset($accessTokenObj->error))
        {
            return null;
        }

        $accessToken = $accessTokenObj->access_token;

        $page = $params->get('fb_page', '');

        $type = $params->get('display', 0);

        switch ($type)
        {
            case 1:
                $limit = $params->get('limit', '3');

                $url = 'https://graph.facebook.com/v2.8/'
                    . $page
                    . '?fields=posts.limit(' . (int) $limit . '){message,%20picture,story}'
                    . '&access_token=' . $accessToken;

                break;
            default:
                $url = 'https://graph.facebook.com/v2.8/'
                    . $page
                    . '?fields=albums{name,%20photo{name,%20picture,%20tags}}'
                    . '&access_token=' . $accessToken;
                break;
        }

        echo $url; die;

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

    public static function getToken(&$params)
    {
        $id = trim($params->get('client_id', ''));
        $secret = trim($params->get('client_secret', ''));

        $url = 'https://graph.facebook.com/v2.8/oauth/access_token?client_id=' . $id . '&client_secret=' . $secret .
            '&grant_type=client_credentials';

        $output = self::doCurl($url);

        return json_decode($output);
    }
}
