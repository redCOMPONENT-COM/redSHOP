<?php
/**
 * @package     RedSHOP
 * @subpackage  Plugin
 *
 * @copyright   Copyright (C) 2008 - 2015 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

defined('_JEXEC') or die;

/**
 * Telephone Directory search plugin for redSHOP Registration
 *
 * @package     RedSHOP.Telesearch
 * @subpackage  Plugin
 *
 * @since       1.1
 */
class PlgTeleSearchRs_Telesearch extends JPlugin
{
	/**
	 * Find data using telephone number
	 *
	 * @param   array  $data  Phone number related data
	 *
	 * @return  array  Data found by telesearch operation
	 */
	public function findByTelephoneNumber($data)
	{
		jimport('joomla.http');

		$queryData = array(
			'newapi'    => 1,
			'phone'     => $data['phone'],
			'remoteadd' => $_SERVER['SERVER_ADDR']
		);

		$url = $this->params->get('apiUrl', 'http://opslag.redhost.dk/lookup.php')
				. '?' . http_build_query($queryData);

		$http     = new JHttp(new JRegistry);
		$response = $http->get($url);

		if (200 !== (int) $response->code)
		{
			return array();
		}

		return json_decode($response->body, true);
	}
}
