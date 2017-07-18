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
 * Plugins redSHOP Kerry express shipping
 *
 * @since  1.0
 */
class PlgRedshop_CheckoutKerry_Express extends JPlugin
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
	public function onRenderCustomField($infoId = 0)
	{
		echo RedshopLayoutHelper::render(
			'template',
			array('id' => $infoId),
			JPATH_PLUGINS . '/redshop_checkout/kerry_express/layouts'
		);
	}

	/**
	 * get Kerry City List
	 *
	 * @return mixed
	 */
	public function onAjaxGetKerryCity()
	{
		$app          = JFactory::getApplication();
		$input        = $app->input;
		$id           = $input->post->getInt('id', 0);
		$selectedCity = RedshopHelperExtrafields::getDataByName('rs_kerry_city', 14, $id);

		if (empty($selectedCity))
		{
			$selectedCity = RedshopHelperExtrafields::getDataByName('rs_kerry_billing_city', 7, $id);
		}

		$handle = $this->getDistrictProvinceData();
		$data   = array();

		while ($result = fgetcsv($handle, null, ',', '"'))
		{
			if (!is_numeric($result[1]))
			{
				continue;
			}

			$data[$result[1]] = $result[0];
		}

		if (empty($data))
		{
			echo '';
			JFactory::getApplication()->close();
		}

		$html = '';

		foreach ($data as $code => $name)
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
	 * get Kerry District List
	 *
	 * @return mixed
	 */
	public function onAjaxGetKerryDistrict()
	{
		$app              = JFactory::getApplication();
		$input            = $app->input;
		$city             = $input->post->getString('city', '');
		$id               = $input->post->getInt('id', 0);
		$selectedDistrict = RedshopHelperExtrafields::getDataByName('rs_kerry_district', 14, $id);

		if (empty($selectedDistrict))
		{
			$selectedDistrict = RedshopHelperExtrafields::getDataByName('rs_kerry_billing_district', 7, $id);
		}

		$handle = $this->getDistrictProvinceData();
		$data   = array();

		while ($result = fgetcsv($handle, null, ',', '"'))
		{
			if (!is_numeric($result[1]))
			{
				continue;
			}

			$data[$result[1]][$result[3]] = $result[2];
		}

		if (empty($data))
		{
			echo '';
			$app->close();
		}

		$html = '';

		foreach ($data[$city] as $code => $name)
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
	 * get Kerry District List
	 *
	 * @return mixed
	 */
	public function onAjaxGetKerryWard()
	{
		$app          = JFactory::getApplication();
		$input        = $app->input;
		$district     = $input->post->getString('district', '');
		$id           = $input->post->getInt('id', 0);
		$selectedWard = RedshopHelperExtrafields::getDataByName('rs_kerry_ward', 14, $id);

		if (empty($selectedWard))
		{
			$selectedWard = RedshopHelperExtrafields::getDataByName('rs_kerry_billing_ward', 7, $id);
		}

		$handle = $this->getDistrictProvinceData();
		$data   = array();

		while ($result = fgetcsv($handle, null, ',', '"'))
		{
			if (!is_numeric($result[1]))
			{
				continue;
			}

			$data[$result[3]][$result[5]] = $result[4];
		}

		if (empty($data))
		{
			echo '';
			$app->close();
		}

		$html = '';

		foreach ($data[$district] as $code => $name)
		{
			$selected = '';

			if (!empty($selectedWard) && $code == $selectedWard->data_txt)
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
		$cityField     = RedshopHelperExtrafields::getDataByName('rs_kerry_billing_city', 7, $data->users_info_id);
		$districtField = RedshopHelperExtrafields::getDataByName('rs_kerry_billing_district', 7, $data->users_info_id);
		$wardField     = RedshopHelperExtrafields::getDataByName('rs_kerry_billing_ward', 7, $data->users_info_id);

		$userCity     = "";
		$userDistrict = "";
		$userWard     = "";
		$cities       = array();
		$districts    = array();
		$wards        = array();

		$handle = $this->getDistrictProvinceData();

		while ($result = fgetcsv($handle, null, ',', '"'))
		{
			if (!is_numeric($result[1]))
			{
				continue;
			}

			$cities[$result[1]]                = $result[0];
			$districts[$result[1]][$result[3]] = $result[2];
			$wards[$result[3]][$result[5]]     = $result[4];
		}

		$userCity     = $cities[$cityField->data_txt];
		$userDistrict = $districts[$cityField->data_txt][$districtField->data_txt];
		$userWard     = $wards[$districtField->data_txt][$wardField->data_txt];

		$data->address .= ' ' . $userWard . ' ' . $userDistrict . ' ' . $userCity;
		$data->city = $userCity;

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
		$cityField     = RedshopHelperExtrafields::getDataByName('rs_kerry_city', 14, $data->users_info_id);
		$districtField = RedshopHelperExtrafields::getDataByName('rs_kerry_district', 14, $data->users_info_id);
		$wardField     = RedshopHelperExtrafields::getDataByName('rs_kerry_ward', 14, $data->users_info_id);

		if (empty($cityField) && empty($districtField) && empty($wardField))
		{
			$cityField     = RedshopHelperExtrafields::getDataByName('rs_kerry_billing_city', 7, $data->users_info_id);
			$districtField = RedshopHelperExtrafields::getDataByName('rs_kerry_billing_district', 7, $data->users_info_id);
			$wardField     = RedshopHelperExtrafields::getDataByName('rs_kerry_billing_ward', 7, $data->users_info_id);
		}

		$userCity     = "";
		$userDistrict = "";
		$userWard     = "";
		$cities       = array();
		$districts    = array();
		$wards        = array();

		$handle = $this->getDistrictProvinceData();

		while ($result = fgetcsv($handle, null, ',', '"'))
		{
			if (!is_numeric($result[1]))
			{
				continue;
			}

			$cities[$result[1]]                = $result[0];
			$districts[$result[1]][$result[3]] = $result[2];
			$wards[$result[3]][$result[5]]     = $result[4];
		}

		$userCity     = $cities[$cityField->data_txt];
		$userDistrict = $districts[$cityField->data_txt][$districtField->data_txt];
		$userWard     = $wards[$districtField->data_txt][$wardField->data_txt];

		$data->address .= ' ' . $userWard . ' ' . $userDistrict . ' ' . $userCity;
		$data->city = $userCity;

		$this->createShippingOrder($data);

		return;
	}

	/**
	 * get Kerry Data List
	 *
	 * @return array
	 */
	public function getDistrictProvinceData()
	{
		$path = JPATH_PLUGINS . '/redshop_checkout/kerry_express/data/data.csv';
		$handle = fopen($path, 'r');

		return $handle;
	}

	/**
	 * Create Kerry express Shipping Order
	 *
	 * @param   object  $data  Order shipping data
	 *
	 * @return array
	 */
	public function createShippingOrder($data)
	{
		$orderId       = $data->order_id;
		$cityField     = RedshopHelperExtrafields::getDataByName('rs_kerry_city', 14, $data->users_info_id);
		$districtField = RedshopHelperExtrafields::getDataByName('rs_kerry_district', 14, $data->users_info_id);
		$wardField     = RedshopHelperExtrafields::getDataByName('rs_kerry_ward', 14, $data->users_info_id);
		$items         = RedshopHelperOrder::getItems($orderId);
		$weight        = 0;
		$itemList      = array();
		$i             = 0;

		foreach ($items as $item)
		{
			$productData = RedshopHelperProduct::getProductById($item->product_id);
			$weight += $productData->weight;
			$itemList[$i]['product_name']      = $productData->product_name;
			$itemList[$i]['package_weight']    = $productData->weight;
			$itemList[$i]['package_dimension'] = '0x0x0';
			$i++;
		}

		$post = array(
			'token_key'        => $this->params->get('token_key'),
			'order_number'     => $orderId,
			'waybill_number'   => $orderId,
			'no_packs'         => count($items),
			'package_weight'   => $weight,
			'cod'              => 0,
			'service_type'      => '0201',
			'order_note'       => $data->firstname . ' ' . $data->lastname,
			'receiver_address' => array(
					'full_address'       => $data->address,
					'province_area_code' => $cityField->data_txt,
					'district_area_code' => $districtField->data_txt,
					'ward_area_code'     => $wardField->data_txt,
					'contact_phone'      => $data->phone,
					'contact_name'       => $data->firstname . ' ' . $data->lastname
				),
			'sender_address' => array(
					'full_address'       => $this->params->get('address'),
					'province_area_code' => $this->params->get('city'),
					'district_area_code' => $this->params->get('district_code'),
					'ward_area_code'     => $this->params->get('ward_code'),
					'contact_phone'      => $this->params->get('contact_phone'),
					'contact_name'       => $this->params->get('contact_name')
				),
			'orderItem' => $itemList
		);
		$headers = array(
			"Content-Type: application/json",
			"Cache-control: no-cache"
		);

		$curl = curl_init('http://gw.kerryexpress.com.vn/api/WS001PostNewOrderInfor');
		curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($curl, CURLOPT_POSTFIELDS, json_encode($post));
		curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);
		$json = curl_exec($curl);
		curl_close($curl);

		return json_decode($json, true);
	}
}
