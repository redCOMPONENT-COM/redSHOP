<?php
/**
 * @package     RedSHOP
 * @subpackage  Plugin
 *
 * @copyright   Copyright (C) 2008 - 2017 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

use Joomla\Registry\Registry;

defined('_JEXEC') or die;

JLoader::import('redshop.library');

/**
 * Plugins redSHOP Shipping UPS
 *
 * @since  2.0
 */
class PlgRedshop_ShippingUps extends JPlugin
{
	/**
	 * @var string
	 */
	public $paymentCode = "ups";

	/**
	 * @var string
	 */
	public $className = "ups";

	/**
	 * Event on show configuration
	 *
	 * @param   object $shipping Shipping detail
	 *
	 * @return  boolean
	 *
	 * @since   1.0.0
	 */
	public function onShowConfig($shipping)
	{
		if ($shipping->element != $this->className)
		{
			return false;
		}

		echo RedshopLayoutHelper::render('config', array(), __DIR__ . '/layouts');
	}

	/**
	 * Event on write configuration
	 *
	 * @param   array $data Configuration data
	 *
	 * @return  boolean
	 *
	 * @since   1.0.0
	 */
	public function onWriteConfig($data)
	{
		if ($data['element'] != $this->className)
		{
			return true;
		}

		$configFile = JPATH_ROOT . '/plugins/' . $data['plugin'] . '/' . $this->className . '/' . $this->className . '.cfg.php';

		$configs = array(
			"UPS_ACCESS_CODE"                   => $data['UPS_ACCESS_CODE'],
			"UPS_USER_ID"                       => $data['UPS_USER_ID'],
			"UPS_PASSWORD"                      => $data['UPS_PASSWORD'],
			"UPS_PICKUP_TYPE"                   => $data['pickup_type'],
			"UPS_PACKAGE_TYPE"                  => $data['package_type'],
			"UPS_RESIDENTIAL"                   => $data['residential'],
			"UPS_HANDLING_FEE"                  => $data['handling_fee'],
			"UPS_TAX_CLASS"                     => $data['tax_class'],
			// BEGIN CUSTOM CODE
			"Override_Source_Zip"               => $data['Override_Source_Zip'],
			"Show_Delivery_Days_Quote"          => $data['Show_Delivery_Days_Quote'],
			"Show_Delivery_ETA_Quote"           => $data['Show_Delivery_ETA_Quote'],
			"Show_Delivery_Warning"             => $data['Show_Delivery_Warning'],
			"UPS_Next_Day_Air"                  => $data['UPS_Next_Day_Air'],
			"UPS_Next_Day_Air_FSC"              => $data['UPS_Next_Day_Air_FSC'],
			"UPS_2nd_Day_Air"                   => $data['UPS_2nd_Day_Air'],
			"UPS_2nd_Day_Air_FSC"               => $data['UPS_2nd_Day_Air_FSC'],
			"UPS_Ground"                        => $data['UPS_Ground'],
			"UPS_Ground_FSC"                    => $data['UPS_Ground_FSC'],
			"UPS_Worldwide_Express_SM"          => $data['UPS_Worldwide_Express_SM'],
			"UPS_Worldwide_Express_SM_FSC"      => $data['UPS_Worldwide_Express_SM_FSC'],
			"UPS_Worldwide_Expedited_SM"        => $data['UPS_Worldwide_Expedited_SM'],
			"UPS_Worldwide_Expedited_SM_FSC"    => $data['UPS_Worldwide_Expedited_SM_FSC'],
			"UPS_Standard"                      => $data['UPS_Standard'],
			"UPS_Standard_FSC"                  => $data['UPS_Standard_FSC'],
			"UPS_3_Day_Select"                  => $data['UPS_3_Day_Select'],
			"UPS_3_Day_Select_FSC"              => $data['UPS_3_Day_Select_FSC'],
			"UPS_Next_Day_Air_Saver"            => $data['UPS_Next_Day_Air_Saver'],
			"UPS_Next_Day_Air_Saver_FSC"        => $data['UPS_Next_Day_Air_Saver_FSC'],
			"UPS_Next_Day_Air_Early_AM"         => $data['UPS_Next_Day_Air_Early_AM'],
			"UPS_Next_Day_Air_Early_AM_FSC"     => $data['UPS_Next_Day_Air_Early_AM_FSC'],
			"UPS_Worldwide_Express_Plus_SM"     => $data['UPS_Worldwide_Express_Plus_SM'],
			"UPS_Worldwide_Express_Plus_SM_FSC" => $data['UPS_Worldwide_Express_Plus_SM_FSC'],
			"UPS_2nd_Day_Air_AM"                => $data['UPS_2nd_Day_Air_AM'],
			"UPS_2nd_Day_Air_AM_FSC"            => $data['UPS_2nd_Day_Air_AM_FSC'],
			"UPS_Saver"                         => $data['UPS_Saver'],
			"UPS_Saver_FSC"                     => $data['UPS_Saver_FSC'],
			"na"                                => $data['na']
			// END CUSTOM CODE
		);

		$config = "<?php\n";
		$config .= "defined('_JEXEC') or die;\n";

		foreach ($configs as $key => $value)
		{
			$config .= "define('$key', '$value');\n";
		}

		return JFile::write($configFile, $config);
	}

	/**
	 * Event run on list rates
	 *
	 * @param   array $data Data
	 *
	 * @return  array
	 *
	 * @since   1.0.0
	 */
	public function onListRates(&$data)
	{
		$productHelper = productHelper::getInstance();
		$shipping      = RedshopHelperShipping::getShippingMethodByClass($this->className);

		$shippingParams = new Registry($shipping->params);
		$shippingConfig = JPATH_ROOT . '/plugins/' . $shipping->folder . '/' . $shipping->element . '/' . $shipping->element . '.cfg.php';

		include_once $shippingConfig;

		$shippingRates = array();
		$rate          = 0;

		// Conversation of weight ( ration )
		$unitRatio       = $productHelper->getUnitConversation('pounds', Redshop::getConfig()->get('DEFAULT_WEIGHT_UNIT'));
		$unitRatioVolume = $productHelper->getUnitConversation('inch', Redshop::getConfig()->get('DEFAULT_VOLUME_UNIT'));

		$cartTotalDimension = RedshopHelperShipping::getCartItemDimension();
		$orderWeight        = $cartTotalDimension['totalweight'];

		if ($unitRatio != 0)
		{
			// Converting weight in pounds
			$orderWeight = $orderWeight * $unitRatio;
		}

		$shippingInformation = RedshopHelperShipping::getShippingAddress($data['users_info_id']);

		if (is_null($shippingInformation))
		{
			return $shippingRates;
		}

		if (isset($data['shipping_box_id']) && $data['shipping_box_id'])
		{
			$whereShippingBoxes = RedshopHelperShipping::getBoxDimensions($data['shipping_box_id']);
		}
		else
		{
			$whereShippingBoxes               = array();
			$productData                      = RedshopHelperShipping::getProductVolumeShipping();
			$whereShippingBoxes['box_length'] = $productData[2]['length'];
			$whereShippingBoxes['box_width']  = $productData[1]['width'];
			$whereShippingBoxes['box_height'] = $productData[0]['height'];
		}

		if (is_array($whereShippingBoxes) && count($whereShippingBoxes) > 0 && $unitRatioVolume > 0)
		{
			$shippingLength = (int) ($whereShippingBoxes['box_length'] * $unitRatioVolume);
			$shippingWidth  = (int) ($whereShippingBoxes['box_width'] * $unitRatioVolume);
			$shippingHeight = (int) ($whereShippingBoxes['box_height'] * $unitRatioVolume);
		}
		else
		{
			return $shippingRates;
		}

		if ($orderWeight < 1)
		{
			$orderWeight = 1;
		}

		if ($orderWeight > 150)
		{
			$orderWeight = 150.00;
		}

		// The zip that you are shipping to
		$vendorCountry2Code = Redshop::getConfig()->get('DEFAULT_SHIPPING_COUNTRY');

		if (Redshop::getConfig()->get('DEFAULT_SHIPPING_COUNTRY'))
		{
			$vendorCountry2Code = RedshopHelperWorld::getCountryCode2(Redshop::getConfig()->get('DEFAULT_SHIPPING_COUNTRY'));
		}

		if (isset($shippingInformation->country_code))
		{
			$shippingInformation->country_2_code = RedshopHelperWorld::getCountryCode2($shippingInformation->country_code);
		}

		// Make sure the ZIP is 5 chars long
		$destinationZipcode = substr($shippingInformation->zipcode, 0, 5);

		/*
		 * LBS  = Pounds
		 * KGS  = Kilograms
		 * If change than change conversation base unit also
		 *
		 */
		$weightMeasure = (Redshop::getConfig()->get('DEFAULT_WEIGHT_UNIT') == "gram") ? "KGS" : "LBS";
		$measureCode   = ($weightMeasure == "KGS") ? "CM" : "IN";

		// The XML that will be posted to UPS
		$xmlPost = "<?xml version=\"1.0\"?>";
		$xmlPost .= "<AccessRequest xml:lang=\"en-US\">";
		$xmlPost .= " <AccessLicenseNumber>" . UPS_ACCESS_CODE . "</AccessLicenseNumber>";
		$xmlPost .= " <UserId>" . UPS_USER_ID . "</UserId>";
		$xmlPost .= " <Password>" . UPS_PASSWORD . "</Password>";
		$xmlPost .= "</AccessRequest>";
		$xmlPost .= "<?xml version=\"1.0\"?>";
		$xmlPost .= "<RatingServiceSelectionRequest xml:lang=\"en-US\">";
		$xmlPost .= " <Request>";
		$xmlPost .= "  <TransactionReference>";
		$xmlPost .= "  <CustomerContext>Shipping Estimate</CustomerContext>";
		$xmlPost .= "  <XpciVersion>1.0001</XpciVersion>";
		$xmlPost .= "  </TransactionReference>";
		$xmlPost .= "  <RequestAction>rate</RequestAction>";
		$xmlPost .= "  <RequestOption>shop</RequestOption>";
		$xmlPost .= " </Request>";
		$xmlPost .= " <PickupType>";
		$xmlPost .= "  <Code>" . UPS_PICKUP_TYPE . "</Code>";
		$xmlPost .= " </PickupType>";
		$xmlPost .= " <Shipment>";
		$xmlPost .= "  <Shipper>";
		$xmlPost .= "   <Address>";
		$xmlPost .= "    <PostalCode>" . Override_Source_Zip . "</PostalCode>";
		$xmlPost .= "    <CountryCode>$vendorCountry2Code</CountryCode>";
		$xmlPost .= "   </Address>";
		$xmlPost .= "  </Shipper>";
		$xmlPost .= "  <ShipTo>";
		$xmlPost .= "   <Address>";
		$xmlPost .= "    <PostalCode>" . $destinationZipcode . "</PostalCode>";
		$xmlPost .= "    <CountryCode>" . $shippingInformation->country_2_code . "</CountryCode>";

		if (UPS_RESIDENTIAL == "yes")
		{
			$xmlPost .= "    <ResidentialAddressIndicator/>";
		}

		$xmlPost .= "   </Address>";
		$xmlPost .= "  </ShipTo>";
		$xmlPost .= "  <ShipFrom>";
		$xmlPost .= "   <Address>";
		$xmlPost .= "    <PostalCode>" . Override_Source_Zip . "</PostalCode>";
		$xmlPost .= "    <CountryCode>$vendorCountry2Code</CountryCode>";
		$xmlPost .= "   </Address>";
		$xmlPost .= "  </ShipFrom>";

		/*
		Service is only required, if the Tag "RequestOption" contains the value "rate"
		We don't want a specific servive, but ALL Rates
		$xmlPost .= "  <Service>";
		$xmlPost .= "   <Code>".$shipping_type."</Code>";
		$xmlPost .= "  </Service>";
		*/

		$xmlPost .= "  <Package>";
		$xmlPost .= "   <PackagingType>";
		$xmlPost .= "    <Code>" . UPS_PACKAGE_TYPE . "</Code>";
		$xmlPost .= "   </PackagingType>";
		$xmlPost .= "   <Dimensions>";
		$xmlPost .= "    <UnitOfMeasurement>";
		$xmlPost .= "     <Code>" . $measureCode . "</Code>";
		$xmlPost .= "    </UnitOfMeasurement>";
		$xmlPost .= "    <Length>" . ceil($shippingLength) . "</Length>";
		$xmlPost .= "    <Width>" . ceil($shippingWidth) . "</Width>";
		$xmlPost .= "    <Height>" . ceil($shippingHeight) . "</Height>";
		$xmlPost .= "   </Dimensions>";
		$xmlPost .= "   <PackageWeight>";
		$xmlPost .= "    <UnitOfMeasurement>";
		$xmlPost .= "     <Code>" . $weightMeasure . "</Code>";
		$xmlPost .= "    </UnitOfMeasurement>";
		$xmlPost .= "    <Weight>" . $orderWeight . "</Weight>";
		$xmlPost .= "   </PackageWeight>";
		$xmlPost .= "  </Package>";
		$xmlPost .= " </Shipment>";
		$xmlPost .= "</RatingServiceSelectionRequest>";

		$upsURL = "https://www.ups.com:443/ups.app/xml/Rate";

		$CR = curl_init();
		curl_setopt($CR, CURLOPT_URL, $upsURL); /* "?API=RateV2&XML=".$xmlPost); */
		curl_setopt($CR, CURLOPT_POST, 1);
		curl_setopt($CR, CURLOPT_FAILONERROR, true);
		curl_setopt($CR, CURLOPT_POSTFIELDS, $xmlPost);
		curl_setopt($CR, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($CR, CURLOPT_SSL_VERIFYPEER, false);
		$xmlResult    = curl_exec($CR);
		$matchesChild = array();

		if (!$xmlResult)
		{
			$error = true;
		}
		else
		{
			// XML Parsing
			$xmlDoc       = JFactory::getXML($xmlResult, false);
			$matchesChild = $xmlDoc->RatedShipment;

			// Let's check wether the response from UPS is Success or Failure !
			if (strstr($xmlResult, "Failure"))
			{
				$error = true;

				return $shippingRates;
			}
		}

		if ($shippingParams->get("ups_debug"))
		{
			echo "XML Post: <br>";
			echo '<textarea cols="80" rows="10">https://www.ups.com:443/ups.app/xml/Rate?' . $xmlPost . "</textarea>";
			echo "<br>";
			echo "XML Result: <br>";
			echo '<textarea cols="80" rows="10">' . $xmlResult . "</textarea>";
			echo "<br>";
			echo "Cart Contents: " . $orderWeight . "<br><br>\n";
		}

		// Retrieve the list of all "RatedShipment" Elements
		$allServiceCodes = array(
			"UPS_Next_Day_Air",
			"UPS_2nd_Day_Air",
			"UPS_Ground",
			"UPS_Worldwide_Express_SM",
			"UPS_Worldwide_Expedited_SM",
			"UPS_Standard",
			"UPS_3_Day_Select",
			"UPS_Next_Day_Air_Saver",
			"UPS_Next_Day_Air_Early_AM",
			"UPS_Worldwide_Express_Plus_SM",
			"UPS_2nd_Day_Air_AM",
			"UPS_Saver",
			"na");

		$myServiceCodes = array();

		foreach ($allServiceCodes as $serviceCode)
		{
			if (constant($serviceCode) != '' || constant($serviceCode) != 0)
			{
				$myServiceCodes[] = constant($serviceCode);
			}
		}

		$count           = 0;
		$shippingPostage = array();

		for ($t = 0, $tn = count($matchesChild); $t < $tn; $t++)
		{
			$currNode = $matchesChild[$t];

			if (strtolower($matchesChild[$t]->name() != 'ratedshipment'))
			{
				continue;
			}

			$serviceCode = (string) $matchesChild[$t]->Service->Code;

			if (!in_array($serviceCode, $myServiceCodes))
			{
				continue;
			}

			if (isset($shippingPostage[$count]['Ratedshipmentwarning']))
			{
				$shippingPostage[$count]['Ratedshipmentwarning'] = array();
			}

			foreach ($currNode->RatedShipmentWarning as $ratedShippingWarning)
			{
				$shippingPostage[$count]['Ratedshipmentwarning'][] = (string) $ratedShippingWarning;
			}

			$shippingPostage[$count]['ScheduledDeliveryTime']    = (string) $currNode->ScheduledDeliveryTime;
			$shippingPostage[$count]['GuaranteedDaysToDelivery'] = (string) $currNode->GuaranteedDaysToDelivery;
			$shippingPostage[$count]['Currency']                 = (string) $currNode->TransportationCharges->CurrencyCode;
			$shippingPostage[$count]['Rate']                     = (string) $currNode->TransportationCharges->MonetaryValue;

			switch ($serviceCode)
			{
				case "01":
					$shippingPostage[$count]["ServiceName"] = "UPS Next Day Air";
					break;

				case "02":
					$shippingPostage[$count]["ServiceName"] = "UPS 2nd Day Air";
					break;

				case "03":
					$shippingPostage[$count]["ServiceName"] = "UPS Ground";
					break;

				case "07":
					$shippingPostage[$count]["ServiceName"] = "UPS Worldwide Express SM";
					break;

				case "08":
					$shippingPostage[$count]["ServiceName"] = "UPS Worldwide Expedited SM";
					break;

				case "11":
					$shippingPostage[$count]["ServiceName"] = "UPS Standard";
					break;

				case "12":
					$shippingPostage[$count]["ServiceName"] = "UPS 3 Day Select";
					break;

				case "13":
					$shippingPostage[$count]["ServiceName"] = "UPS Next Day Air Saver";
					break;

				case "14":
					$shippingPostage[$count]["ServiceName"] = "UPS Next Day Air Early A.M.";
					break;

				case "54":
					$shippingPostage[$count]["ServiceName"] = "UPS Worldwide Express Plus SM";
					break;

				case "59":
					$shippingPostage[$count]["ServiceName"] = "UPS 2nd Day Air A.M.";
					break;

				case "64":
					$shippingPostage[$count]["ServiceName"] = "n/a";
					break;

				case "65":
					$shippingPostage[$count]["ServiceName"] = "UPS Saver";
					break;

				default:
					break;
			}

			$count++;
		}

		if (count($shippingPostage) <= 0)
		{
			return $shippingRates;
		}

		// UPS returns Charges in USD ONLY.
		// So we have to convert from USD to Vendor Currency if necessary
		if (Redshop::getConfig()->get('CURRENCY_CODE') != "USD")
		{
			$convert = true;
		}
		else
		{
			$convert = false;
		}

		for ($i = 0, $in = count($shippingPostage); $i < $in; $i++)
		{
			$rateValue   = $shippingPostage[$i]['Rate'];
			$serviceName = $shippingPostage[$i]['ServiceName'];
			$fsc         = $shippingPostage[$i]['ServiceName'] . "_FSC";
			$fsc         = str_replace(" ", "_", str_replace(".", "", str_replace("/", "", $fsc)));
			$fsc         = constant($fsc);

			/*if ($fsc == 0)
			{
				$fsc = 1;
			}*/

			if ($convert)
			{
				$tmp    = RedshopHelperCurrency::convert($rateValue, "USD", Redshop::getConfig()->get('CURRENCY_CODE'));
				$charge = !empty($tmp) ? $tmp : $rateValue;

			}
			else
			{
				$charge = $rateValue;
			}

			$chargeFee = ($fsc == 0) ? 0 : ($charge * $fsc) / 100;
			$charge    += (int) UPS_HANDLING_FEE + $chargeFee;

			$shippingRateId = RedshopShippingRate::encrypt(
				array(
					__CLASS__,
					$shipping->name,
					$serviceName,
					number_format($charge, 2, '.', ''),
					$serviceName,
					'single',
					'0'
				)
			);

			$shippingRates[$rate]        = new stdClass;
			$shippingRates[$rate]->text  = $serviceName;
			$shippingRates[$rate]->value = $shippingRateId;
			$shippingRates[$rate]->rate  = $charge;
			$shippingRates[$rate]->vat   = 0;
			$rate++;
		}

		return $shippingRates;
	}
}
