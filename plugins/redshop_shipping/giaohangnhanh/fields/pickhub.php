<?php
/**
 * @package     RedSHOP.Backend
 * @subpackage  Element
 *
 * @copyright   Copyright (C) 2005 - 2017 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

defined('_JEXEC') or die;

JFormHelper::loadFieldClass('list');

/**
 * Renders Giaohangnhanh pickhub list
 *
 * @since  1.1
 */
class JFormFieldPickhub extends JFormFieldList
{
	/**
	 * A flexible category list that respects access controls
	 *
	 * @var    string
	 * @since  1.6
	 */
	public $type = 'pickhub';

	/**
	 * Method to get the field options.
	 *
	 * @return  array  The field option objects.
	 *
	 * @since   3.7.0
	 */
	protected function getOptions()
	{
		$list = array();
		$data = $this->getPickHubsData();
		$i = 0;

		foreach ($data['HubInfo'] as $key => $item)
		{
			$list[$i]['value'] = $item['PickHubID'];
			$list[$i]['text'] = $item['Address'];
			$i++;
		}

		return array_merge(parent::getOptions(), $list);
	}

	/**
	 * get GiaoHangNhanh Data List
	 *
	 * @return array
	 */
	public function getPickHubsData()
	{
		$plugin = JPluginHelper::getPlugin('redshop_shipping', 'giaohangnhanh');
		$params = new JRegistry($plugin->params);

		$post = array(
			'ApiKey'       => $params->get('api_key'),
			'ApiSecretKey' => $params->get('api_secret'),
			'ClientID'     => $params->get('client_id'),
			'Password'     => $params->get('password')
		);
		$headers = array(
			"Content-Type: application/x-www-form-urlencoded",
			"Cache-control: no-cache"
		);

		$curl = curl_init($params->get('url_service') . 'GetPickHubs');
		curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($curl, CURLOPT_POSTFIELDS, http_build_query($post));
		curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);
		$json = curl_exec($curl);
		curl_close($curl);

		return json_decode($json, true);
	}
}
