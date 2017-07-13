<?php
/**
 * @package     RedShop
 * @subpackage  Plugin
 *
 * @copyright   Copyright (C) 2008 - 2017 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

defined('_JEXEC') or die;

// Import library dependencies
jimport('joomla.plugin.plugin');

/**
 * Plugins redSHOP One step checkout
 *
 * @since  1.0
 */
class PlgRedshop_CheckoutGiaohangnhanh extends JPlugin
{
	/**
	 * Constructor - note in Joomla 2.5 PHP4.x is no longer supported so we can use this.
	 *
	 * @param   object  $subject  The object to observe
	 * @param   array   $config   An array that holds the plugin configuration
	 */
	public function __construct(&$subject, $config)
	{
		parent::__construct($subject, $config);
		$this->loadLanguage();
	}

	/**
	 * redSHOP render custom field
	 *
	 * @param   int  $infoId  User shipping id
	 *
	 * @return mixed
	 */
	public function onRenderCustomField($infoId)
	{
		echo RedshopLayoutHelper::render(
			'template',
			array('id' => $infoId),
			JPATH_PLUGINS . '/redshop_checkout/giaohangnhanh/layouts'
		);
	}

	/**
	 * get GiaoHangNhanh City List
	 *
	 * @return mixed
	 */
	public function onAjaxGetGHNCity()
	{
		$app          = JFactory::getApplication();
		$input        = $app->input;
		$id           = $input->post->getInt('id', 0);
		$selectedCity = RedshopHelperExtrafields::getDataByName('rs_city', 14, $id);

		$data   = $this->getDistrictProvinceData();
		$cities = array();

		if (empty($data['Data']))
		{
			echo '';
			JFactory::getApplication()->close();
		}

		$html = '';

		foreach ($data['Data'] as $key => $city)
		{
			$cities[$city['ProvinceCode']] = $city['ProvinceName'];
		}

		foreach ($cities as $code => $name)
		{
			$selected = '';

			if (!empty($selectedCity) && $code == $selectedCity->data_txt)
			{
				$selected = 'selected="selected"';
			}

			$html .= '<option ' . $selected . ' value="' . $code . '">' . $name . '</option>';
		}

		echo $html;
		JFactory::getApplication()->close();
	}

	/**
	 * get GiaoHangNhanh District List
	 *
	 * @return mixed
	 */
	public function onAjaxGetGHNDistrict()
	{
		$app              = JFactory::getApplication();
		$input            = $app->input;
		$city             = $input->post->getInt('city', 0);
		$id               = $input->post->getInt('id', 0);
		$selectedDistrict = RedshopHelperExtrafields::getDataByName('rs_district', 14, $id);

		$data      = $this->getDistrictProvinceData();
		$districts = array();

		if (empty($data['Data']))
		{
			echo '';
			$app->close();
		}

		$html = '';

		foreach ($data['Data'] as $key => $district)
		{
			if ($city != $district['ProvinceCode'])
			{
				continue;
			}

			$districts[$district['ProvinceCode']][$district['DistrictCode']] = $district['DistrictName'];
		}

		foreach ($districts[$city] as $code => $name)
		{
			$selected = '';

			if (!empty($selectedDistrict) && $code == $selectedDistrict->data_txt)
			{
				$selected = 'selected="selected"';
			}

			$html .= '<option ' . $selected . ' value="' . $code . '">' . $name . '</option>';
		}

		echo $html;
		$app->close();
	}

	/**
	 * trigger before user shipping stored
	 *
	 * @param   object  $data  User shipping data
	 *
	 * @return void
	 */
	public function onBeforeUserShippingStore(&$data)
	{
		$cityField = RedshopHelperExtrafields::getDataByName('rs_city', 14, $data->users_info_id);
		$districtField = RedshopHelperExtrafields::getDataByName('rs_district', 14, $data->users_info_id);

		if (empty($cityField) && empty($districtField))
		{
			return;
		}

		$result = $this->getDistrictProvinceData();

		if (empty($result))
		{
			return;
		}

		$userCity     = "";
		$userDistrict = "";
		$cities       = array();
		$districts    = array();

		foreach ($result['Data'] as $key => $city)
		{
			$cities[$city['ProvinceCode']] = $city['ProvinceName'];
		}

		foreach ($result['Data'] as $key => $district)
		{
			if ($cityField->data_txt != $district['ProvinceCode'])
			{
				continue;
			}

			$districts[$district['ProvinceCode']][$district['DistrictCode']] = $district['DistrictName'];
		}

		$userCity = $cities[$cityField->data_txt];
		$userDistrict = $districts[$cityField->data_txt][$districtField->data_txt];

		$data->address .= ' ' . $userDistrict . ' ' . $userCity;
		$data->city = $userCity;

		$serviceList = $this->getServiceList($districtField->data_txt);
		$serviceId = $serviceList['Services'][0]['ShippingServiceID'];

		if (empty($serviceId))
		{
			return;
		}

		$ghnOrder = $this->createShippingOrder($data->order_id, $serviceId, $districtField->data_txt, $data);
		$this->updateOrder($data->order_id, $ghnOrder);

		return;
	}

	/**
	 * get GiaoHangNhanh Data List
	 *
	 * @return array
	 */
	public function getDistrictProvinceData()
	{
		$post = array(
			'ApiKey'       => $this->params->get('api_key'),
			'ApiSecretKey' => $this->params->get('api_secret'),
			'ClientID'     => $this->params->get('client_id'),
			'Password'     => $this->params->get('password')
		);
		$headers = array(
			"Content-Type: application/x-www-form-urlencoded",
			"Cache-control: no-cache"
		);

		$curl = curl_init($this->params->get('url_service') . 'GetDistrictProvinceData');
		curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($curl, CURLOPT_POSTFIELDS, http_build_query($post));
		curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);
		$json = curl_exec($curl);
		curl_close($curl);

		return json_decode($json, true);
	}

	/**
	 * get GiaoHangNhanh Data List
	 *
	 * @param   int  $districtCode  GHN District code
	 *
	 * @return array
	 */
	public function getServiceList($districtCode)
	{
		$post = array(
			'ApiKey'           => $this->params->get('api_key'),
			'ApiSecretKey'     => $this->params->get('api_secret'),
			'ClientID'         => $this->params->get('client_id'),
			'Password'         => $this->params->get('password'),
			'FromDistrictCode' => $this->params->get('from_district_code', '0201'),
			'ToDistrictCode'   => $districtCode
		);
		$headers = array(
			"Content-Type: application/x-www-form-urlencoded",
			"Cache-control: no-cache"
		);

		$curl = curl_init($this->params->get('url_service') . 'GetServiceList');
		curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($curl, CURLOPT_POSTFIELDS, http_build_query($post));
		curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);
		$json = curl_exec($curl);
		curl_close($curl);

		return json_decode($json, true);
	}

	/**
	 * Create GHN Shipping Order
	 *
	 * @param   int     $orderId        Order id
	 * @param   int     $serviceId      Service id
	 * @param   int     $districtCode   District code
	 * @param   object  $orderShipping  Order shipping data
	 *
	 * @return array
	 */
	public function createShippingOrder($orderId, $serviceId, $districtCode, $orderShipping)
	{
		$items = RedshopHelperOrder::getItems($orderId);
		$weight = 0;

		foreach ($items as $item)
		{
			$productData = RedshopHelperProduct::getProductById($item->product_id);
			$weight += $productData->weight;
		}

		$post = array(
			'ApiKey'               => $this->params->get('api_key'),
			'ApiSecretKey'         => $this->params->get('api_secret'),
			'ClientID'             => $this->params->get('client_id'),
			'Password'             => $this->params->get('password'),
			'PickHubID'            => $this->params->get('pick_hub_id', '287484'),
			'ClientOrderCode'      => $orderId,
			'RecipientName'        => $orderShipping->firstname . ' ' . $orderShipping->lastname,
			'RecipientPhone'       => $orderShipping->phone,
			'DeliveryAddress'      => $orderShipping->address,
			'DeliveryDistrictCode' => $districtCode,
			'Weight'               => $weight,
			'ServiceID'            => $serviceId
		);
		$headers = array(
			"Content-Type: application/x-www-form-urlencoded",
			"Cache-control: no-cache"
		);

		$curl = curl_init($this->params->get('url_service') . 'CreateShippingOrder');
		curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($curl, CURLOPT_POSTFIELDS, http_build_query($post));
		curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);
		$json = curl_exec($curl);
		curl_close($curl);

		return json_decode($json, true);
	}

	/**
	 * update Order
	 *
	 * @param   int    $orderId  Order ID
	 * @param   array  $data     Update data
	 *
	 * @return array
	 */
	public function updateOrder($orderId, $data)
	{
		$order           = new stdClass;
		$order->order_id = $orderId;
		$order->track_no = $data['OrderCode'];

		return JFactory::getDbo()->updateObject('#__redshop_orders', $order, 'id');
	}
}
