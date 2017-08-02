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
 * Plugins redSHOP Giaohangnhanh shipping
 *
 * @since  1.0
 */
class PlgRedshop_ShippingGiaohangnhanh extends JPlugin
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
	 * onListRates
	 *
	 * @param   array  $data  Array values
	 *
	 * @return array
	 */
	public function onListRates(&$data)
	{
		$data['state_code'] = $data['post']['ghnCity'];
		$shipping = RedshopHelperShipping::getShippingMethodByClass('giaohangnhanh');
		$rateList = RedshopHelperShipping::listShippingRates($shipping->element, $data['users_info_id'], $data);

		$shippingRate = array();

		if (!empty($rateList))
		{
			foreach ($rateList as $key => $rate)
			{
				$shippingRateValue         = $rate->shipping_rate_value;
				$rate->shipping_rate_value = RedshopHelperShipping::applyVatOnShippingRate($rate, $data);
				$shippingVatRate           = $rate->shipping_rate_value - $shippingRateValue;

				$shippingRateId = RedshopShippingRate::encrypt(
					array(
						__CLASS__ ,
						$shipping->name,
						$rate->shipping_rate_name,
						number_format($rate->shipping_rate_value, 2, '.', ''),
						$rate->shipping_rate_id,
						'single',
						$shippingVatRate,
						$rate->economic_displaynumber,
						$rate->deliver_type
					)
				);

				$shippingRate[$key]        = new stdClass;
				$shippingRate[$key]->text  = $rate->shipping_rate_name;
				$shippingRate[$key]->value = $shippingRateId;
				$shippingRate[$key]->rate  = $rate->shipping_rate_value;
				$shippingRate[$key]->vat   = $shippingVatRate;
			}

			return $shippingRate;
		}

		$cart          = JFactory::getSession()->get('cart');
		$userInfoId    = $data['users_info_id'];
		$districtField = RedshopHelperExtrafields::getDataByName('rs_ghn_district', 14, $userInfoId);

		if (empty($districtField))
		{
			$districtField = RedshopHelperExtrafields::getDataByName('rs_ghn_billing_district', 7, $userInfoId);
		}

		$district = $districtField->data_txt;

		if ($data['users_info_id'] == 0)
		{
			$district = $data['post']['ghnDistrict'];
		}

		$serviceList = $this->getServiceList($district);
		$weight = 0;
		$height = 0;
		$length = 0;
		$width  = 0;

		foreach ($cart as $key => $value)
		{
			if (!is_numeric($key))
			{
				continue;
			}

			$productData = RedshopHelperProduct::getProductById($value['product_id']);

			$weight += $productData->weight;
			$height += $productData->product_height;
			$length += $productData->product_length;
			$width  += $productData->product_width;
		}

		$items                        = array();
		$items[0]['Weight']           = $weight;
		$items[0]['Length']           = $length;
		$items[0]['Width']            = $width;
		$items[0]['Height']           = $height;
		$items[0]['FromDistrictCode'] = $this->params->get('from_district_code');
		$items[0]['ToDistrictCode']   = $district;
		$items[0]['ServiceID']        = $serviceList['Services'][0]['ShippingServiceID'];

		$result = $this->calculateServiceFee($items);

		if (empty($result['Items']) || !empty($result['ErrorMessage']))
		{
			return array();
		}

		$shippingRate = array();

		foreach ($result['Items'] as $key => $rate)
		{
			$shippingRateId = RedshopShippingRate::encrypt(
				array(
					__CLASS__ ,
					$shipping->name,
					$rate['ServiceName'],
					number_format($rate['ServiceFee'], 2, '.', ''),
					0,
					'single',
					0,
					0,
					0
				)
			);

			$shippingRate[$key]        = new stdClass;
			$shippingRate[$key]->text  = $rate['ServiceName'];
			$shippingRate[$key]->value = $shippingRateId;
			$shippingRate[$key]->rate  = $rate['ServiceFee'];
			$shippingRate[$key]->vat   = 0;
		}

		return $shippingRate;
	}

	/**
	 * redSHOP render custom field
	 *
	 * @param   int  $infoId  User shipping id
	 *
	 * @return mixed
	 */
	public function onRenderCustomField($infoId = 0)
	{
		echo RedshopLayoutHelper::render(
			'template',
			array(
				'id'      => $infoId,
				'zipcode' => $this->params->get('zipcode', '70000')
			),
			JPATH_PLUGINS . '/redshop_shipping/giaohangnhanh/layouts'
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
		$selectedCity = RedshopHelperExtrafields::getDataByName('rs_ghn_city', 14, $id);

		if (empty($selectedCity))
		{
			$selectedCity = RedshopHelperExtrafields::getDataByName('rs_ghn_billing_city', 7, $id);
		}

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
		$selectedDistrict = RedshopHelperExtrafields::getDataByName('rs_ghn_district', 14, $id);

		if (empty($selectedDistrict))
		{
			$selectedDistrict = RedshopHelperExtrafields::getDataByName('rs_ghn_billing_district', 7, $id);
		}

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
	 * trigger before user billing stored
	 *
	 * @param   object  $data  User billing data
	 *
	 * @return void
	 */
	public function onBeforeUserBillingStore(&$data)
	{
		$billing = $this->getBillingExtraFields($data->users_info_id);

		$data->address .= ' ' . $billing['district'] . ' ' . $billing['city'];
		$data->city = $billing['city'];
		$data->state_code = $billing['city_code'];

		return;
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
		$shippingExtraFields = $this->getShippingExtraFields($data->users_info_id);
		$billingExtraFields  = $this->getBillingExtraFields($data->users_info_id);

		$userCity = !empty($shippingExtraFields['city']) ? $shippingExtraFields['city'] : $billingExtraFields['city'];
		$userDistrict = !empty($shippingExtraFields['district']) ? $shippingExtraFields['district'] : $billingExtraFields['district'];

		$data->address    .= ' ' . $userDistrict . ' ' . $userCity;
		$data->city       = $userCity;
		$data->state_code = !empty($shippingExtraFields['city_code']) ? $shippingExtraFields['city_code'] : $billingExtraFields['city_code'];
	}

	/**
	 * trigger before after order status changed
	 *
	 * @param   int     $orderId        Order ID
	 * @param   string  $paymentStatus  Order payment status
	 * @param   string  $orderStatus    Order status
	 *
	 * @return void
	 */
	public function sendOrderShipping($orderId, $paymentStatus, $orderStatus)
	{
		$createOrder = $this->params->get('order_status_to_create', '');
		$orderData = RedshopHelperOrder::getOrderDetail($orderId);
		$trackingId = $orderData->track_no;

		if ($orderStatus != $createOrder && $trackingId != "")
		{
			return;
		}

		$paymentData = RedshopHelperOrder::getOrderPaymentDetail($orderId);

		if ($paymentData->payment_method_class != 'rs_payment_banktransfer' && $paymentStatus != 'Paid')
		{
			return;
		}

		$shippingData  = RedshopHelperOrder::getOrderShippingUserInfo($orderId);
		$userInfoId    = $shippingData->users_info_id;
		$districtField = RedshopHelperExtrafields::getDataByName('rs_ghn_district', 14, $userInfoId);

		if (empty($districtField))
		{
			$districtField = RedshopHelperExtrafields::getDataByName('rs_ghn_billing_district', 7, $userInfoId);
		}

		$district = $districtField->data_txt;

		$serviceList = $this->getServiceList($district);
		$serviceId = $serviceList['Services'][0]['ShippingServiceID'];

		if (empty($serviceId))
		{
			return;
		}

		$ghnOrder = $this->createShippingOrder($orderId, $serviceId, $district, $shippingData, $orderData);
		$this->updateOrder($orderId, $ghnOrder);
	}

	/**
	 * trigger before when render Shipping Rate State
	 *
	 * @param   object  $stateList    State List
	 * @param   string  $countryCode  Country Code
	 *
	 * @return void
	 */
	public function onRenderShippingRateState(&$stateList, $countryCode)
	{
		if ($countryCode != "VNM")
		{
			return;
		}

		$result = $this->getDistrictProvinceData();

		if (empty($result))
		{
			return;
		}

		$cities = array();

		foreach ($result['Data'] as $key => $city)
		{
			$cities[$city['ProvinceCode']] = $city['ProvinceName'];
		}

		$data       = array();
		$key        = 0;
		$data[$key] = new stdClass;

		foreach ($cities as $value => $text)
		{
			$data[$key]->value = $value;
			$data[$key]->text  = $text;
			$key++;
		}

		$stateList = array_merge($stateList, $data);
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
	 * Calculate Service Fee
	 *
	 * @param   array  $items  Items to calculate
	 *
	 * @return array
	 */
	public function calculateServiceFee($items)
	{
		$post = array(
			'ApiKey'       => $this->params->get('api_key'),
			'ApiSecretKey' => $this->params->get('api_secret'),
			'ClientID'     => $this->params->get('client_id'),
			'Password'     => $this->params->get('password'),
			'Items'        => $items
		);
		$headers = array(
			"Content-Type: application/x-www-form-urlencoded",
			"Cache-control: no-cache"
		);

		$curl = curl_init($this->params->get('url_service') . 'CalculateServiceFee');
		curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($curl, CURLOPT_POSTFIELDS, http_build_query($post));
		curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);
		$json = curl_exec($curl);
		curl_close($curl);

		return json_decode($json, true);
	}

	/**
	 * get GiaoHangNhanh Service List
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
			'FromDistrictCode' => $this->params->get('from_district_code'),
			'ToDistrictCode'   => $districtCode
		);
		$headers = array(
			"Content-Type: application/x-www-form-urlencoded",
			"Cache-control: no-cache"
		);

		$curl = curl_init($this->params->get('url_service') . 'ServiceInfos');
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
	 * @param   string  $trackingId  Order tracking Id
	 *
	 * @return array
	 */
	public function getGHNOrderInfo($trackingId)
	{
		$post = array(
			'ApiKey'       => $this->params->get('api_key'),
			'ApiSecretKey' => $this->params->get('api_secret'),
			'ClientID'     => $this->params->get('client_id'),
			'Password'     => $this->params->get('password'),
			'OrderCode'    => $trackingId
		);
		$headers = array(
			"Content-Type: application/x-www-form-urlencoded",
			"Cache-control: no-cache"
		);

		$curl = curl_init($this->params->get('url_service') . 'GetOrderInfo');
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
	 * @param   object  $orderData      Order payment data
	 *
	 * @return array
	 */
	public function createShippingOrder($orderId, $serviceId, $districtCode, $orderShipping, $orderData)
	{
		$items     = RedshopHelperOrder::getItems($orderId);
		$weight    = 0;
		$codAmount = $orderData->order_total;

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
			'PickHubID'            => $this->params->get('pick_hub_id'),
			'ClientOrderCode'      => $orderId,
			'RecipientName'        => $orderShipping->firstname . ' ' . $orderShipping->lastname,
			'RecipientPhone'       => $orderShipping->phone,
			'DeliveryAddress'      => $orderShipping->address,
			'DeliveryDistrictCode' => $districtCode,
			'Weight'               => $weight,
			'ServiceID'            => $serviceId,
			'CODAmount'            => $codAmount
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

		return JFactory::getDbo()->updateObject('#__redshop_orders', $order, 'order_id');
	}

	/**
	 * Trigger before store redSHOP user
	 *
	 * @param   array    $data   Order shipping data
	 * @param   boolean  $isNew  User is new
	 *
	 * @return void
	 */
	public function onBeforeCreateRedshopUser(&$data, $isNew)
	{
		$cities = array();
		$result = $this->getDistrictProvinceData();

		foreach ($result['Data'] as $key => $city)
		{
			$cities[$city['ProvinceCode']] = $city['ProvinceName'];
		}

		$data['city']       = $cities[$data['rs_ghn_billing_city']];
		$data['zipcode']    = $this->params->get('zipcode', '70000');
		$data['state_code'] = $data['rs_ghn_billing_city'];
	}

	/**
	 * Trigger before store redSHOP user shipping
	 *
	 * @param   array   $data  Order shipping data
	 *
	 * @return void
	 */
	public function onBeforeStoreRedshopUserShipping(&$data)
	{
		$cities = array();
		$result = $this->getDistrictProvinceData();

		foreach ($result['Data'] as $key => $city)
		{
			$cities[$city['ProvinceCode']] = $city['ProvinceName'];
		}

		$data['city_ST']       = $cities[$data['rs_ghn_city']];
		$data['zipcode_ST']    = $this->params->get('zipcode', '70000');
		$data['state_code_ST'] = $data['rs_ghn_city'];
	}

	/**
	 * Trigger before render Billing address in checkout
	 *
	 * @param   array  $data  User billing data
	 *
	 * @return  void
	 */
	public function onBeforeRenderBillingAddress(&$data)
	{
		$billing = $this->getBillingExtraFields($data->users_info_id);

		$data->extraField = array(
				'rs_ghn_billing_city'     => $billing['city'],
				'rs_ghn_billing_district' => $billing['district']
			);
	}

	/**
	 * Trigger before render Shipping address in checkout
	 *
	 * @param   array  $data  User shipping data
	 *
	 * @return  void
	 */
	public function onBeforeRenderShippingAddress(&$data)
	{
		$shipping = $this->getShippingExtraFields($data->users_info_id);

		$data->extraField = array(
				'rs_ghn_city'     => $shipping['city'],
				'rs_ghn_district' => $shipping['district']
			);
	}

	/**
	 * Function to get User billing extra fields
	 *
	 * @param   int  $userInfoId  Order info id
	 *
	 * @return  array
	 */
	public function getBillingExtraFields($userInfoId)
	{
		$cityField = RedshopHelperExtrafields::getDataByName('rs_ghn_billing_city', 7, $userInfoId);
		$districtField = RedshopHelperExtrafields::getDataByName('rs_ghn_billing_district', 7, $userInfoId);

		if (empty($cityField) && empty($districtField))
		{
			return array();
		}

		$result = $this->getDistrictProvinceData();

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

		return array(
				'city'          => $userCity,
				'district'      => $userDistrict,
				'city_code'     => $cityField->data_txt,
				'district_code' => $districtField->data_txt
			);
	}

	/**
	 * Function to get User shipping extra fields
	 *
	 * @param   int  $userInfoId  Order info id
	 *
	 * @return  array
	 */
	public function getShippingExtraFields($userInfoId)
	{
		$cityField = RedshopHelperExtrafields::getDataByName('rs_ghn_city', 14, $userInfoId);
		$districtField = RedshopHelperExtrafields::getDataByName('rs_ghn_district', 14, $userInfoId);

		if (empty($cityField) && empty($districtField))
		{
			return array();
		}

		$result = $this->getDistrictProvinceData();

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

		return array(
				'city'          => $userCity,
				'district'      => $userDistrict,
				'city_code'     => $cityField->data_txt,
				'district_code' => $districtField->data_txt
			);
	}

	/**
	 * Trigger before Webservice store redSHOP order
	 *
	 * @param   array  $data  Order shipping data
	 *
	 * @return  void
	 */
	public function onBeforeWSOrderStore(&$data)
	{
		$orderInfo        = $this->getGHNOrderInfo($data['trackingId']);
		$data['status']   = $this->params->get(strtolower($orderInfo['CurrentStatus']));
		$data['order_id'] = $this->getOrderbyTrackingId($data['trackingId']);
	}

	/**
	 * Trigger after Webservice store redSHOP order
	 *
	 * @param   array  $data  Order shipping data
	 *
	 * @return  void
	 */
	public function onAfterWSOrderStore($data)
	{
		$orderInfo = $this->getGHNOrderInfo($data['trackingId']);
		$data['status'] = $this->params->get(strtolower($orderInfo['CurrentStatus']));

		$log               = new stdClass;
		$log->order_id     = $this->getOrderbyTrackingId($data['trackingId']);
		$log->order_status = $data['status'];
		$log->date_changed = time();

		JFactory::getDbo()->insertObject('#__redshop_order_status_log', $log);
	}

	/**
	 * Function to get Order by tracking ID
	 *
	 * @param   string  $trackingId  Order tracking id
	 *
	 * @return  boolean
	 */
	public function getOrderbyTrackingId($trackingId)
	{
		$db = JFactory::getDbo();
		$query = $db->getQuery(true)
			->select($db->qn('order_id'))
			->from($db->qn('#__redshop_orders'))
			->where($db->qn('track_no') . ' = ' . $db->q($trackingId));

		return $db->setQuery($query)->loadResult();
	}

	/**
	 * Function to get Order by tracking ID
	 *
	 * @param   int     $orderId      Order Id
	 * @param   string  $trackingUrl  Order Tracking URL
	 *
	 * @return  void
	 */
	public function onReplaceTrackingUrl($orderId, &$trackingUrl)
	{
		$orderData = RedshopHelperOrder::getOrderDetail($orderId);
		$trackingUrl = 'https://5sao.ghn.vn/Tracking/ViewTracking/' . $orderData->track_no;
	}
}
