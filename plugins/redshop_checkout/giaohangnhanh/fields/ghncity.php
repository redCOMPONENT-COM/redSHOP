<?php
/**
 * @package     RedSHOP.Backend
 * @subpackage  Element
 *
 * @copyright   Copyright (C) 2005 - 2015 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

defined('_JEXEC') or die;

JFormHelper::loadFieldClass('list');

/**
 * Renders a Shopper Group MultiSelect List
 *
 * @since  1.1
 */
class JFormFieldGhncity extends JFormFieldList
{
	/**
	 * A flexible category list that respects access controls
	 *
	 * @var    string
	 * @since  1.6
	 */
	public $type = 'ghncity';

	/**
	 * Set select list options
	 *
	 * @return  string  select list options
	 */
	protected function getOptions()
	{
		$cities = array();
		$list = array();
		$data = $this->getDistrictProvinceData();
		$i = 0;

		foreach ($data['Data'] as $key => $item)
		{
			$cities[$item['ProvinceCode']] = $item['ProvinceName'];
		}

		foreach ($cities as $key => $city)
		{
			$list[$i]['value'] = $key;
			$list[$i]['text'] = $city;
			$i++;
		}

		return array_merge(parent::getOptions(), $list);
	}

	/**
	 * get GiaoHangNhanh Data List
	 *
	 * @return array
	 */
	public function getDistrictProvinceData()
	{
		$plugin = JPluginHelper::getPlugin('redshop_checkout', 'giaohangnhanh');
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

		$curl = curl_init($params->get('url_service') . 'GetDistrictProvinceData');
		curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($curl, CURLOPT_POSTFIELDS, http_build_query($post));
		curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);
		$json = curl_exec($curl);
		curl_close($curl);

		return json_decode($json, true);
	}
}
