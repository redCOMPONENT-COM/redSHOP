<?php
/**
 * @package     RedSHOP
 * @subpackage  Plugin
 *
 * @copyright   Copyright (C) 2008 - 2017 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

defined('_JEXEC') or die;

use Joomla\Registry\Registry;

JLoader::import('redshop.library');

/**
 * Class redSHOP Shipping - Fedex
 *
 * @since  1.5
 */
class PlgRedshop_ShippingFedex extends JPlugin
{
	/**
	 * Load the language file on instantiation.
	 *
	 * @var    boolean
	 */
	protected $autoloadLanguage = true;

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
		if ($shipping->element != $this->_name)
		{
			return false;
		}

		// Load config
		include_once JPATH_ROOT . '/plugins/redshop_shipping/' . $this->_name . '/config/' . $this->_name . '.cfg.php';

		echo RedshopLayoutHelper::render(
			'config',
			array(),
			__DIR__ . '/layouts'
		);

		return true;
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
		if ($data['element'] != $this->_name)
		{
			return true;
		}

		$configFile = JPATH_ROOT . '/plugins/redshop_shipping/' . $this->_name . '/config/' . $this->_name . '.cfg.php';

		$configs = array(
			"FEDEX_ACCOUNT_NUMBER"       => $data['FEDEX_ACCOUNT_NUMBER'],
			"FEDEX_METER_NUMBER"         => $data['FEDEX_METER_NUMBER'],
			"FEDEX_CARRIERCODE"          => $data['FEDEX_CARRIERCODE'],
			"FEDEX_SERVICETYPE"          => serialize($data['FEDEX_SERVICETYPE']),
			"FEDEX_DROPOFFTYPE"          => $data['FEDEX_DROPOFFTYPE'],
			"FEDEX_PACKAGINGTYPE"        => $data['FEDEX_PACKAGINGTYPE'],
			"FEDEX_WEIGHTUNITS"          => $data['FEDEX_WEIGHTUNITS'],
			"FEDEX_DEVELOPMENT"          => $data['FEDEX_DEVELOPMENT'],
			"FEDEX_KEY"                  => $data['FEDEX_KEY'],
			"FEDEX_PASSWORD"             => $data['FEDEX_PASSWORD'],
			"FEDEX_SHIPPER_ADDRESS"      => $data['FEDEX_SHIPPER_ADDRESS'],
			"FEDEX_SHIPPER_CITY"         => $data['FEDEX_SHIPPER_CITY'],
			"FEDEX_SHIPPER_STATE"        => $data['FEDEX_SHIPPER_STATE'],
			"FEDEX_SHIPPER_POSTAL_CODE"  => $data['FEDEX_SHIPPER_POSTAL_CODE'],
			"FEDEX_SHIPPER_COUNTRY_CODE" => $data['FEDEX_SHIPPER_COUNTRY_CODE'],
			"FEDEX_DISCOUNT"             => $data['FEDEX_DISCOUNT']
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
		include_once JPATH_ROOT . '/plugins/redshop_shipping/fedex/config/fedex.cfg.php';

		$productHelper = productHelper::getInstance();
		$shipping      = RedshopHelperShipping::getShippingMethodByClass($this->_name);

		$fedexAccountNumber = FEDEX_ACCOUNT_NUMBER;
		$fedexMeterNumber   = FEDEX_METER_NUMBER;
		$fedexCarrierCode   = FEDEX_CARRIERCODE;
		$fedexServiceType   = unserialize(FEDEX_SERVICETYPE);
		$fedexDropOffType   = FEDEX_DROPOFFTYPE;
		$fedexPackagingType = FEDEX_PACKAGINGTYPE;
		$fedexWeightUnits   = FEDEX_WEIGHTUNITS;
		$fedexKey           = FEDEX_KEY;
		$fedexPass          = FEDEX_PASSWORD;
		$shippingRates      = array();

		$unitRatio        = $productHelper->getUnitConversation($fedexWeightUnits, strtolower(Redshop::getConfig()->get('DEFAULT_WEIGHT_UNIT')));
		$fedexWeightUnits = $fedexWeightUnits == 'lbs' ? 'LB' : 'KG';

		$unitRatioVolume   = $productHelper->getUnitConversation('inch', Redshop::getConfig()->get('DEFAULT_VOLUME_UNIT'));
		$totalDimension    = RedshopHelperShipping::getCartItemDimension();
		$cartTotalQuantity = $totalDimension['totalquantity'];
		$cartTotalWeight   = $totalDimension['totalweight'];

		// Check for not zero
		if ($unitRatio != 0)
		{
			// Converting weight in kg
			$cartTotalWeight = $cartTotalWeight * $unitRatio;
		}

		$shippingInfor = RedshopHelperShipping::getShippingAddress($data['users_info_id']);

		if (is_null($shippingInfor))
		{
			return $shippingRates;
		}

		if (!empty($data['shipping_box_id']))
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

		$billing = RedshopHelperUser::getUserInformation($shippingInfor->user_id);

		if (!empty((array) $billing))
		{
			return $shippingRates;
		}

		if (!empty($shippingInfor->country_code))
		{
			$shippingInfor->country_2_code = RedshopHelperWorld::getCountryCode2($shippingInfor->country_code);
		}

		if (!empty($billing->country_code))
		{
			$billing->country_2_code = RedshopHelperWorld::getCountryCode2($billing->country_code);
		}

		if (!empty($shippingInfor->state_code))
		{
			$shippingInfor->state_2_code = $shippingInfor->state_code;

			if (strlen($billing->state_code) > 2)
			{
				$shippingInfor->state_2_code = RedshopHelperWorld::getStateCode2($shippingInfor->state_code);
			}
		}

		if (!empty($billing->state_code))
		{
			$billing->state_2_code = $billing->state_code;

			if (strlen($billing->state_code) > 2)
			{
				$billing->state_2_code = RedshopHelperWorld::getStateCode2($billing->state_code);
			}
		}

		$countryCode = Redshop::getConfig()->get('SHOP_COUNTRY');

		if ($countryCode != '')
		{
			$billing->country_2_code = RedshopHelperWorld::getCountryCode2($countryCode);
		}

		// The XML that will be posted to UPS
		if (FEDEX_DEVELOPMENT == 1)
		{
			$wsdlPath = JURI::root() . 'plugins/redshop_shipping/' . $this->_name . '/wsdl/RateService_v9_test.wsdl';
		}
		else
		{
			$wsdlPath = JURI::root() . 'plugins/redshop_shipping/' . $this->_name . '/wsdl/RateService_v9.wsdl';
		}

		$shippingData = array(
			"StreetLines"         => array($shippingInfor->address),
			"City"                => $shippingInfor->city,
			"StateOrProvinceCode" => $shippingInfor->state_2_code,
			"PostalCode"          => $shippingInfor->zipcode,
			"CountryCode"         => $shippingInfor->country_2_code
		);

		$shippers = array(
			"StreetLines"         => array(FEDEX_SHIPPER_ADDRESS),
			"City"                => FEDEX_SHIPPER_CITY,
			"StateOrProvinceCode" => FEDEX_SHIPPER_STATE,
			"PostalCode"          => FEDEX_SHIPPER_POSTAL_CODE,
			"CountryCode"         => FEDEX_SHIPPER_COUNTRY_CODE
		);

		if (in_array("GROUND_HOME_DELIVERY", $fedexServiceType))
		{
			$residential     = array("Residential" => 1);
			$shippingData    = array_merge($shippingData, $residential);
			$shippers        = array_merge($shippers, $residential);
			$residentialFlag = 1;
		}

		ob_flush();
		ini_set("soap.wsdl_cache_enabled", "0");

		$client = new SoapClient($wsdlPath, array("trace" => 1));

		$request['WebAuthenticationDetail'] = array('UserCredential' => array('Key' => FEDEX_KEY, 'Password' => FEDEX_PASSWORD));
		$request['ClientDetail']            = array('AccountNumber' => FEDEX_ACCOUNT_NUMBER, 'MeterNumber' => FEDEX_METER_NUMBER);
		$request['TransactionDetail']       = array('CustomerTransactionId' => ' *** Rate Available Services Request v10 using PHP ***');
		$request['Version']                 = array('ServiceId' => 'crs', 'Major' => '9', 'Intermediate' => '0', 'Minor' => '0');
		$request['ReturnTransitAndCommit']  = true;

		// Valid values REGULAR_PICKUP, REQUEST_COURIER, ...
		$request['RequestedShipment']['DropoffType']   = $fedexDropOffType;
		$request['RequestedShipment']['ShipTimestamp'] = date('c');

		// Service Type and Packaging Type are not passed in the request

		$request['RequestedShipment']['Shipper']                = array('Address' => $shippers);
		$request['RequestedShipment']['Recipient']              = array('Address' => $shippingData);
		$request['RequestedShipment']['ShippingChargesPayment'] = array(
			'PaymentType' => 'SENDER',
			'Payor'       => array(
				'AccountNumber' => FEDEX_ACCOUNT_NUMBER, // Replace 'XXX' with payor's account number
				'CountryCode'   => 'US'
			)
		);
		$request['RequestedShipment']['RateRequestTypes']       = 'ACCOUNT';
		$request['RequestedShipment']['RateRequestTypes']       = 'LIST';
		$request['RequestedShipment']['PackageCount']           = '1';

		$request['RequestedShipment']['RequestedPackageLineItems'] = array(
			'0' => array(
				'SequenceNumber'    => 1,
				'GroupPackageCount' => $cartTotalQuantity,
				'Weight'            => array(
					'Value' => round($cartTotalWeight, 2),
					'Units' => strtoupper($fedexWeightUnits)
				),
				'Dimensions'        => array(
					'Length' => $shippingLength,
					'Width'  => $shippingWidth,
					'Height' => $shippingHeight,
					'Units'  => 'IN'
				)
			)
		);

		try
		{
			$response = $client->getRates($request);

			if ($response->HighestSeverity == 'FAILURE' || $response->HighestSeverity == 'ERROR' || $response->HighestSeverity == 'WARNING')
			{
				$str = '';

				if (count($response->Notifications) > 1)
				{
					foreach ($response->Notifications as $notification)
					{
						$str .= "<b>Fedex " . $notification->Severity . " : " . $notification->Code . "</b> : " . $notification->Message . "<br>";
					}
				}
				else
				{
					$str = "<b>Fedex " . $response->Notifications->Severity . "</b> : " . $response->Notifications->Message;
				}

				echo $str;
			}
			else
			{
				$i = 0;

				foreach ($response->RateReplyDetails as $rateReply)
				{
					if (in_array($rateReply->ServiceType, $fedexServiceType))
					{
						$amount = $rateReply->RatedShipmentDetails[0]->ShipmentRateDetail->TotalNetCharge->Amount;

						if (Redshop::getConfig()->get('FEDEX_DISCOUNT') == 0)
						{
							$amount += $rateReply->RatedShipmentDetails[0]->EffectiveNetDiscount->Amount;
						}

						$shipping_rate_id = RedshopShippingRate::encrypt(
							array(
								__CLASS__,
								$shipping->name,
								$rateReply->ServiceType,
								number_format($amount, 2, '.', ''),
								$shipping->name,
								'single'
							)
						);

						$shippingRates[$i]        = new stdClass;
						$shippingRates[$i]->text  = JText::_('PLG_REDSHOP_SHIPPING_' . $rateReply->ServiceType . '_LBL');
						$shippingRates[$i]->value = $shipping_rate_id;
						$shippingRates[$i]->rate  = $amount;
						$shippingRates[$i]->vat   = 0;

						$i++;
					}
				}
			}
		}
		catch (SoapFault $exception)
		{
			// Get Exception
		}

		return $shippingRates;
	}
}
