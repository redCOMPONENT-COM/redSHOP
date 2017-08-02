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
	 * @param   JRegistry  $params  Module parameters
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

		$page  = $params->get('fb_page', '');
		$limit = $params->get('limit', 3);

		$url = 'https://graph.facebook.com/v2.8/'
			. $page
			. '?fields=albums{name,%20photos{name,%20picture,%20tags}},posts.limit(' . (int) $limit . '){message,%20picture,story}'
			. '&access_token=' . $accessToken;

		$output = self::doCurl($url);

		return json_decode($output);
	}

	/**
	 * doCurl function
	 *
	 * @param   string $url URL that curl will call
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

	/**
	 * getToken  get FB access token
	 *
	 * @param   mixed $params params of module
	 *
	 * @return  mixed
	 */
	public static function getToken(&$params)
	{
		$id     = trim($params->get('client_id', ''));
		$secret = trim($params->get('client_secret', ''));

		$url = 'https://graph.facebook.com/v2.8/oauth/access_token?client_id=' . $id . '&client_secret=' . $secret .
			'&grant_type=client_credentials';

		$output = self::doCurl($url);

		return json_decode($output);
	}
}
