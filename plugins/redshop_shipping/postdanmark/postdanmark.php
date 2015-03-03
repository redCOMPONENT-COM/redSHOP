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
 * Handle Post Denmark Shipping Locations
 *
 * @since  1.4
 */
class Plgredshop_ShippingPostdanmark extends JPlugin
{
	/**
	 * Shipping Method unique name
	 *
	 * @var  string
	 */
	public $classname    = "postdanmark";

	/**
	 * Method will trigger on shipping rate listing.
	 *
	 * @param   array  &$d  Users information
	 *
	 * @return  array      Shipping Rates
	 */
	public function onListRates(&$d)
	{
		$shippinghelper = new shipping;
		$shippingrate   = array();
		$rate           = 0;
		$shipping       = $shippinghelper->getShippingMethodByClass($this->classname);
		$shippingArr    = $shippinghelper->getShopperGroupDefaultShipping();

		if (!empty($shippingArr))
		{
			$shopper_shipping           = $shippingArr['shipping_rate'];
			$shippingVatRate            = $shippingArr['shipping_vat'];
			$default_shipping           = JText::_('COM_REDSHOP_DEFAULT_SHOPPER_GROUP_SHIPPING');
			$shopper_shipping_id        = $shippinghelper->encryptShipping(__CLASS__ . "|" . JText::_($shipping->name) . "|" . $default_shipping . "|" . number_format($shopper_shipping, 2, '.', '') . "|" . $default_shipping . "|single|" . $shippingVatRate . "|0|1");
			$shippingrate[$rate]->text  = $default_shipping;
			$shippingrate[$rate]->value = $shopper_shipping_id;
			$shippingrate[$rate]->rate  = $shopper_shipping;

			$rate++;
		}

		$ratelist = $shippinghelper->listshippingrates($shipping->element, $d['users_info_id'], $d);

		for ($i = 0; $i < count($ratelist); $i++)
		{
			$rs                         = $ratelist[$i];
			$shippingRate               = $rs->shipping_rate_value;
			$rs->shipping_rate_value    = $shippinghelper->applyVatOnShippingRate($rs, $d['user_id']);
			$shippingVatRate            = $rs->shipping_rate_value - $shippingRate;
			$economic_displaynumber     = $rs->economic_displaynumber;
			$shipping_rate_id           = $shippinghelper->encryptShipping(__CLASS__ . "|" . JText::_($shipping->name) . "|" . $rs->shipping_rate_name . "|" . number_format($rs->shipping_rate_value, 2, '.', '') . "|" . $rs->shipping_rate_id . "|single|" . $shippingVatRate . '|' . $economic_displaynumber . '|' . $rs->deliver_type);

			$shippingrate[$rate]        = new stdClass;
			$shippingrate[$rate]->text  = $rs->shipping_rate_name;
			$shippingrate[$rate]->value = $shipping_rate_id;
			$shippingrate[$rate]->rate  = $rs->shipping_rate_value;
			$shippingrate[$rate]->vat   = $shippingVatRate;

			$rate++;
		}

		JHtml::_('redshopjquery.framework');
		$document = JFactory::getDocument();
		$document->addScript('//maps.googleapis.com/maps/api/js?v=3.exp&sensor=false&libraries=places');
		$document->addScript('plugins/redshop_shipping/postdanmark/includes/js/functions.js');
		$document->addScript('plugins/redshop_shipping/postdanmark/includes/js/map_functions.js');

		return $shippingrate;
	}

	/**
	 * Fetch data from postdanmark on ajax request
	 *
	 * @return  void
	 */
	public function onPostDanmarkAjaxRequest()
	{
		$app = JFactory::getApplication();

		$zipcode     = $app->input->getInt('zipcode', '');
		$countryCode = $app->input->getCmd('countryCode', '');

		if (strlen((int) $zipcode) == 4)
		{
			$url = "http://api.postnord.com/wsp/rest/BusinessLocationLocator"
				. "/Logistics/ServicePointService_1.0/findNearestByAddress.json?"
				. "consumerId=" . $this->params->get('consumerId')
				. "&countryCode=" . trim($countryCode)
				. "&postalCode=" . trim($zipcode)
				. "&numberOfServicePoints=12";

			$curl = curl_init();
			curl_setopt($curl, CURLOPT_URL, $url);
			curl_setopt($curl, CURLOPT_HEADER, 0);
			curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
			$jsonFile = curl_exec($curl);
			curl_close($curl);

			$data = json_decode($jsonFile);

			if ($data)
			{
				$points      = $data->servicePointInformationResponse->servicePoints;
				$addresses   = array();
				$name        = array();
				$number      = array();
				$generate    = array();
				$opening     = array();
				$close       = array();
				$opening_sat = array();
				$close_sat   = array();
				$lat         = array();
				$lng         = array();
				$key         = 1;

				if (!empty($points))
				{
					// Unique shops location based on their servicePointId
					$uniqueShops = array();

					// Loop through shops to make it unique
					foreach ($points as $shop)
					{
						$uniqueShops[$shop->servicePointId] = $shop;
					}

					// Loop through to prepare for map markers
					foreach ($uniqueShops as $point)
					{
						if ($point->visitingAddress->streetName)
						{
							$point_addr = $point->visitingAddress->streetName;

							if (isset($point->visitingAddress->streetNumber))
							{
								$point_addr .= ' ' . $point->visitingAddress->streetNumber;
							}

							$addresses[] = $point_addr;
							$name[]      = $point->name;

							if (count($point->openingHours) > 0)
							{
								$opening[] = $point->openingHours[0]->from1;
								$close[]   = $point->openingHours[0]->to1;

								if (count($point->openingHours) > 5)
								{
									$opening_sat[] = $point->openingHours[5]->from1;
									$close_sat[]   = $point->openingHours[5]->to1;
								}
							}

							$lat[]            = $point->coordinate->northing;
							$lng[]            = $point->coordinate->easting;
							$number[]         = $point->deliveryAddress->postalCode . ' ' . $point->deliveryAddress->city;
							$servicePointId[] = $point->servicePointId;
						}
					}
				}

				$shopLocations['radio_html']  = $this->getPickupLocationsResult($uniqueShops);
				$shopLocations['addresses']   = $addresses;
				$shopLocations['name']        = $name;
				$shopLocations['number']      = $number;
				$shopLocations['generate']    = $generate;
				$shopLocations['opening']     = $opening;
				$shopLocations['close']       = $close;
				$shopLocations['opening_sat'] = $opening_sat;
				$shopLocations['close_sat']   = $close_sat;
				$shopLocations['lat']         = $lat;
				$shopLocations['lng']         = $lng;

				if (isset($servicePointId))
				{
					$shopLocations['servicePointId'] = $servicePointId;
				}

				echo json_encode($shopLocations);

			}
			else
			{
				$shopLocations['error'] = 'Der er desværre ingen Post Danmark udleveringssted i det ønskede postnummer.';
				echo json_encode($shopLocations);
			}
		}

		$app->close();
	}

	/**
	 * Get shipping location restult based on give data
	 *
	 * @param   array  $shops  Shipping locations of shops
	 *
	 * @return  string
	 */
	protected function getPickupLocationsResult($shops)
	{
		$response = '';

		if (!isset($shops) || sizeof($shops) == 0)
		{
			$response .= '<span class="postdanmark-error" id="postdanmark-error">Postnummeret er ikke korrekt.</span><br/>';
			$response .= '<input type="hidden" name="postdanmark_pickupLocation" id="location" class="postdanmark_location">';
		}
		else
		{
			$response .= '<div class="postdanmark-choose"><strong>Vælg et udleveringssted:</strong></div>';
			$response .= '<table id="mapAddress"><tr>';

			if (sizeof($shops) == 1)
			{
				$response .= $this->createShop($shops[0], 0, 1);
			}
			else
			{
				$cnt     = 0;
				$count   = count($shops);

				foreach ($shops as $shop)
				{
					$response .= $this->createShop($shop, $cnt, $count);
					$cnt++;
				}
			}

			$response .= '</tr></table>';
		}

		return $response;
	}

	/**
	 * Create HTML for shops
	 *
	 * @param   object   $shop   Shop Information
	 * @param   integer  $key    Count key id
	 * @param   integer  $count  Total Count
	 *
	 * @return  string  Shop HTML
	 */
	protected function createShop($shop, $key, $count)
	{
		++$key;
		$response = '<td class="radio_point_container" onclick="selectMarker(' . trim($shop->servicePointId) . ')"><table class="point_table"><tr>';

		if ($count == $key)
		{
			$response .= '<td class="radio_point"><input type="radio" id="' . trim($shop->servicePointId) . '" value="' . trim($shop->servicePointId) . '" name="postdanmark_pickupLocation" class="radio validate-one-required-by-name" />';
		}
		else
		{
			$response .= '<td class="radio_point"><input type="radio" id="' . trim($shop->servicePointId) . '" value="' . trim($shop->servicePointId) . '" name="postdanmark_pickupLocation" class="radio" />';
		}

		$response .= '<strong>' . $key . '. <strong></td>';
		$response .= '<td class="point_info"><strong>' . trim($shop->name) . '</strong><div class="postdanmark_address"><span class="street">' . trim($shop->deliveryAddress->streetName) . '</span><br /><span class="city">' . trim($shop->deliveryAddress->city) . '</span><input type="hidden" class="service_postcode" value="' . trim($shop->deliveryAddress->postalCode) . '" /></div></td>';

		$response .= '</tr></table></td>';

		if ($key % 4 == 0 && $key < $count)
		{
			$response .= '</tr><tr>';
		}

		return $response;
	}
}
