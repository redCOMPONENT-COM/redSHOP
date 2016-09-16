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
 * Joomla! System Logging Plugin
 *
 * @package        Joomla
 * @subpackage     System
 */

JLoader::import('redshop.library');

class plgredshop_shippingfedex extends JPlugin
{
	public $payment_code = "fedex";

	public $classname = "fedex";

	public function onShowconfig($ps)
	{
		if ($ps->element == $this->classname)
		{
			?>
			<table class="admintable">
				<tr class="row0">
					<td><strong><?php echo JText::_('PLG_REDSHOP_SHIPPING_FEDEX_DEVELOPMENT_LBL') ?></strong></td>
					<td>
					<?php
						echo JHtml::_('select.booleanlist', 'FEDEX_DEVELOPMENT', '', FEDEX_DEVELOPMENT);
					?>
					</td>
					<td><?php echo JHTML::tooltip(JText::_('PLG_REDSHOP_SHIPPING_FEDEX_DEVELOPMENT_LBL'), JText::_('PLG_REDSHOP_SHIPPING_FEDEX_DEVELOPMENT_LBL'), 'tooltip.png', '', '', false);?></td>
				</tr>
				<tr class="row0">
					<td><strong><?php echo JText::_('PLG_REDSHOP_SHIPPING_FEDEX_ACCOUNT_NUMBER_LBL') ?></strong></td>
					<td><input type="text" name="FEDEX_ACCOUNT_NUMBER" class="inputbox" value="<?php echo FEDEX_ACCOUNT_NUMBER ?>"/></td>
					<td><?php echo JHTML::tooltip(JText::_('PLG_REDSHOP_SHIPPING_FEDEX_ACCOUNT_NUMBER_LBL'), JText::_('PLG_REDSHOP_SHIPPING_FEDEX_ACCOUNT_NUMBER_LBL'), 'tooltip.png', '', '', false);?></td>
				</tr>
				<tr class="row1">
					<td><strong><?php echo JText::_('PLG_REDSHOP_SHIPPING_FEDEX_METER_NUMBER_LBL') ?></strong></td>
					<td><input type="text" name="FEDEX_METER_NUMBER" class="inputbox"
					           value="<?php echo FEDEX_METER_NUMBER ?>"/></td>
					<td><?php echo JHTML::tooltip(JText::_('PLG_REDSHOP_SHIPPING_FEDEX_METER_NUMBER_LBL'), JText::_('PLG_REDSHOP_SHIPPING_FEDEX_METER_NUMBER_LBL'), 'tooltip.png', '', '', false);?></td>
				</tr>
				<tr class="row1">
					<td><strong><?php echo JText::_('PLG_REDSHOP_SHIPPING_FEDEX_KEY_LBL') ?></strong></td>
					<td><input type="text" name="FEDEX_KEY" class="inputbox" value="<?php echo FEDEX_KEY ?>"/></td>
					<td><?php echo JHTML::tooltip(JText::_('PLG_REDSHOP_SHIPPING_FEDEX_KEY_LBL'), JText::_('PLG_REDSHOP_SHIPPING_FEDEX_KEY_LBL'), 'tooltip.png', '', '', false);?></td>
				</tr>
				<tr class="row1">
					<td><strong><?php echo JText::_('PLG_REDSHOP_SHIPPING_FEDEX_PASSWORD_LBL') ?></strong></td>
					<td><input type="text" name="FEDEX_PASSWORD" class="inputbox" value="<?php echo FEDEX_PASSWORD ?>"/>
					</td>
					<td><?php echo JHTML::tooltip(JText::_('PLG_REDSHOP_SHIPPING_FEDEX_PASSWORD_LBL'), JText::_('PLG_REDSHOP_SHIPPING_FEDEX_PASSWORD_LBL'), 'tooltip.png', '', '', false);?></td>
				</tr>

				<tr class="row0">
					<td colspan="3"></td>
				</tr>
				<tr class="row1">
					<td><strong><?php echo JText::_('PLG_REDSHOP_SHIPPING_FEDEX_SHIPPER_ADDRESS_LBL') ?></strong></td>
					<td><input type="text" name="FEDEX_SHIPPER_ADDRESS" class="inputbox"
					           value="<?php echo FEDEX_SHIPPER_ADDRESS ?>"/></td>
					<td><?php echo JHTML::tooltip(JText::_('PLG_REDSHOP_SHIPPING_FEDEX_SHIPPER_ADDRESS_LBL'), JText::_('PLG_REDSHOP_SHIPPING_FEDEX_SHIPPER_ADDRESS_LBL'), 'tooltip.png', '', '', false);?></td>
				</tr>
				<tr class="row0">
					<td><strong><?php echo JText::_('PLG_REDSHOP_SHIPPING_FEDEX_SHIPPER_CITY_LBL') ?></strong></td>
					<td><input type="text" name="FEDEX_SHIPPER_CITY" class="inputbox"
					           value="<?php echo FEDEX_SHIPPER_CITY ?>"/></td>
					<td><?php echo JHTML::tooltip(JText::_('PLG_REDSHOP_SHIPPING_FEDEX_SHIPPER_CITY_LBL'), JText::_('PLG_REDSHOP_SHIPPING_FEDEX_SHIPPER_CITY_LBL'), 'tooltip.png', '', '', false);?></td>
				</tr>
				<tr class="row1">
					<td><strong><?php echo JText::_('PLG_REDSHOP_SHIPPING_FEDEX_SHIPPER_STATE_LBL') ?></strong></td>
					<td><input type="text" name="FEDEX_SHIPPER_STATE" class="inputbox"
					           value="<?php echo FEDEX_SHIPPER_STATE ?>"/></td>
					<td><?php echo JHTML::tooltip(JText::_('PLG_REDSHOP_SHIPPING_FEDEX_SHIPPER_STATE_LBL'), JText::_('PLG_REDSHOP_SHIPPING_FEDEX_SHIPPER_STATE_LBL'), 'tooltip.png', '', '', false);?></td>
				</tr>
				<tr class="row0">
					<td><strong><?php echo JText::_('PLG_REDSHOP_SHIPPING_FEDEX_SHIPPER_POSTAL_CODE_LBL') ?></strong></td>
					<td><input type="text" name="FEDEX_SHIPPER_POSTAL_CODE" class="inputbox"
					           value="<?php echo FEDEX_SHIPPER_POSTAL_CODE ?>"/></td>
					<td><?php echo JHTML::tooltip(JText::_('PLG_REDSHOP_SHIPPING_FEDEX_SHIPPER_POSTAL_CODE_LBL'), JText::_('PLG_REDSHOP_SHIPPING_FEDEX_SHIPPER_POSTAL_CODE_LBL'), 'tooltip.png', '', '', false);?></td>
				</tr>
				<tr class="row1">
					<td><strong><?php echo JText::_('PLG_REDSHOP_SHIPPING_FEDEX_SHIPPER_COUNTRY_LBL') ?></strong></td>
					<td><input type="text" name="FEDEX_SHIPPER_COUNTRY_CODE" class="inputbox"
					           value="<?php echo FEDEX_SHIPPER_COUNTRY_CODE ?>"/></td>
					<td><?php echo JHTML::tooltip(JText::_('PLG_REDSHOP_SHIPPING_FEDEX_SHIPPER_COUNTRY_LBL'), JText::_('PLG_REDSHOP_SHIPPING_FEDEX_SHIPPER_COUNTRY_LBL'), 'tooltip.png', '', '', false);?></td>
				</tr>

				<tr class="row0">
					<td colspan="3"></td>
				</tr>
				<tr class="row1">
					<td colspan="3"></td>
				</tr>

				<tr class="row0">
					<td><strong><?php echo JText::_('PLG_REDSHOP_SHIPPING_FEDEX_DISCOUNT_LBL') ?></strong></td>
					<td>
					<?php
						echo JHtml::_('select.booleanlist', 'FEDEX_DISCOUNT', '', Redshop::getConfig()->get('FEDEX_DISCOUNT'));
					?>
					</td>
					<td><?php echo JHTML::tooltip(JText::_('PLG_REDSHOP_SHIPPING_FEDEX_DISCOUNT_LBL'), JText::_('PLG_REDSHOP_SHIPPING_FEDEX_DISCOUNT_LBL'), 'tooltip.png', '', '', false);?></td>
				</tr>
				<tr class="row1">
					<td><strong><?php echo JText::_('PLG_REDSHOP_SHIPPING_FEDEX_CARRIERCODE_LBL') ?></strong></td>
					<td>
						<?php
							$source = array();
							$source['FDXE'] = JText::_('PLG_REDSHOP_SHIPPING_FDXE_LBL');
							$source['FDXG'] = JText::_('PLG_REDSHOP_SHIPPING_FDXG_LBL');
							echo JHTML::_('select.genericlist', $source, 'FEDEX_CARRIERCODE', '', 'value', 'text', FEDEX_CARRIERCODE);
						?>
					</td>
					<td><?php echo JHTML::tooltip(JText::_('PLG_REDSHOP_SHIPPING_FEDEX_CARRIERCODE_LBL'), JText::_('PLG_REDSHOP_SHIPPING_FEDEX_CARRIERCODE_LBL'), 'tooltip.png', '', '', false);?></td>
				</tr>
				<tr class="row0">
					<td><strong><?php echo JText::_('PLG_REDSHOP_SHIPPING_FEDEX_SERVICE_TYPE_LBL') ?></strong></td>
					<td>
						<?php
							$source                                        = array();
							$source['PRIORITY_OVERNIGHT']                  = JText::_('PLG_REDSHOP_SHIPPING_PRIORITY_OVERNIGHT_LBL');
							$source['STANDARD_OVERNIGHT']                  = JText::_('PLG_REDSHOP_SHIPPING_STANDARD_OVERNIGHT_LBL');
							$source['FIRST_OVERNIGHT']                     = JText::_('PLG_REDSHOP_SHIPPING_FIRST_OVERNIGHT_LBL');
							$source['FEDEX_2_DAY']                         = JText::_('PLG_REDSHOP_SHIPPING_FEDEX_2DAY_LBL');
							$source['FEDEX_EXPRESS_SAVER']                 = JText::_('PLG_REDSHOP_SHIPPING_FEDEX_EXPRESS_SAVER_LBL');
							$source['INTERNATIONAL_PRIORITY']              = JText::_('PLG_REDSHOP_SHIPPING_INTERNATIONAL_PRIORITY_LBL');
							$source['INTERNATIONAL_ECONOMY']               = JText::_('PLG_REDSHOP_SHIPPING_INTERNATIONAL_ECONOMY_LBL');
							$source['INTERNATIONAL_FIRST']                 = JText::_('PLG_REDSHOP_SHIPPING_INTERNATIONAL_FIRST_LBL');
							$source['FEDEX_1_DAY_FREIGHT']                 = JText::_('PLG_REDSHOP_SHIPPING_FEDEX_1_DAY_FREIGHT_LBL');
							$source['FEDEX_2_DAY_FREIGHT']                 = JText::_('PLG_REDSHOP_SHIPPING_FEDEX_2_DAY_FREIGHT_LBL');
							$source['FEDEX_3_DAY_FREIGHT']                 = JText::_('PLG_REDSHOP_SHIPPING_FEDEX_3_DAY_FREIGHT_LBL');
							$source['FEDEX_GROUND']                        = JText::_('PLG_REDSHOP_SHIPPING_FEDEX_GROUND_LBL');
							$source['GROUND_HOME_DELIVERY']                = JText::_('PLG_REDSHOP_SHIPPING_GROUND_HOME_DELIVERY_LBL');
							$source['INTERNATIONAL_PRIORITY_FREIGHT']      = JText::_('PLG_REDSHOP_SHIPPING_INTERNATIONAL_PRIORITY_FREIGHT_LBL');
							$source['INTERNATIONAL_ECONOMY_FREIGHT']       = JText::_('PLG_REDSHOP_SHIPPING_INTERNATIONAL_ECONOMY_FREIGHT_LBL');
							$source['EUROPE_FIRST_INTERNATIONAL_PRIORITY'] = JText::_('PLG_REDSHOP_SHIPPING_EUROPE_FIRST_INTERNATIONAL_PRIORITY_LBL');

							echo JHTML::_('select.genericlist', $source, 'FEDEX_SERVICETYPE[]', 'multiple="multiple" size="10"', 'value', 'text', unserialize(FEDEX_SERVICETYPE));
						?>
					</td>
					<td><?php echo JHTML::tooltip(JText::_('PLG_REDSHOP_SHIPPING_FEDEX_SERVICE_TYPE_LBL'), JText::_('PLG_REDSHOP_SHIPPING_FEDEX_SERVICE_TYPE_LBL'), 'tooltip.png', '', '', false);?></td>
				</tr>
				<tr class="row0">
					<td><strong><?php echo JText::_('PLG_REDSHOP_SHIPPING_FEDEX_DROP_OFF_TYPE_LBL') ?></strong></td>
					<td>
						<?php
							$source                            = array();
							$source['REGULAR_PICKUP']          = JText::_('PLG_REDSHOP_SHIPPING_REGULAR_PICKUP_LBL');
							$source['REQUEST_COURIER']         = JText::_('PLG_REDSHOP_SHIPPING_REQUEST_COURIER_LBL');
							$source['DROP_BOX']                = JText::_('PLG_REDSHOP_SHIPPING_DROP_BOX_LBL');
							$source['BUSINESS_SERVICE_CENTER'] = JText::_('PLG_REDSHOP_SHIPPING_BUSINESS_SERVICE_CENTER_LBL');
							$source['STATION']                 = JText::_('PLG_REDSHOP_SHIPPING_STATION_LBL');

							echo JHTML::_('select.genericlist', $source, 'FEDEX_DROPOFFTYPE', '', 'value', 'text', FEDEX_DROPOFFTYPE);
						?>
					</td>
					<td><?php echo JHTML::tooltip(JText::_('PLG_REDSHOP_SHIPPING_FEDEX_DROP_OFF_TYPE_LBL'), JText::_('PLG_REDSHOP_SHIPPING_FEDEX_DROP_OFF_TYPE_LBL'), 'tooltip.png', '', '', false);?></td>
				</tr>
				<tr class="row0">
					<td><strong><?php echo JText::_('PLG_REDSHOP_SHIPPING_FEDEX_PACKAGING_TYPE_LBL') ?></strong></td>
					<td>
						<?php
							$source                   = array();
							$source['FEDEX_ENVELOPE'] = JText::_('PLG_REDSHOP_SHIPPING_FEDEX_ENVELOPE_LBL');
							$source['FEDEX_PAK']      = JText::_('PLG_REDSHOP_SHIPPING_FEDEX_PAK_LBL');
							$source['FEDEX_BOX']      = JText::_('PLG_REDSHOP_SHIPPING_FEDEX_BOX_LBL');
							$source['FEDEX_TUBE']     = JText::_('PLG_REDSHOP_SHIPPING_FEDEX_TUBE_LBL');
							$source['FEDEX_10KG_BOX'] = JText::_('PLG_REDSHOP_SHIPPING_FEDEX_10KG_BOX_LBL');
							$source['FEDEX_25KG_BOX'] = JText::_('PLG_REDSHOP_SHIPPING_FEDEX_25KG_BOX_LBL');
							$source['YOUR_PACKAGING'] = JText::_('PLG_REDSHOP_SHIPPING_YOUR_PACKAGING_LBL');

							echo JHTML::_('select.genericlist', $source, 'FEDEX_PACKAGINGTYPE', '', 'value', 'text', FEDEX_PACKAGINGTYPE);
						?>
					</td>
					<td><?php echo JHTML::tooltip(JText::_('PLG_REDSHOP_SHIPPING_FEDEX_PACKAGING_TYPE_LBL'), JText::_('PLG_REDSHOP_SHIPPING_FEDEX_PACKAGING_TYPE_LBL'), 'tooltip.png', '', '', false);?></td>
				</tr>
				<tr class="row0">
					<td><strong><?php echo JText::_('PLG_REDSHOP_SHIPPING_FEDEX_WEIGHT_UNITS_LBL') ?></strong></td>
					<td>
						<?php
							$source        = array();
							$source['lbs'] = JText::_('PLG_REDSHOP_SHIPPING_LBS');
							$source['kg']  = JText::_('PLG_REDSHOP_SHIPPING_KGS');

							echo JHTML::_('select.genericlist', $source, 'FEDEX_WEIGHTUNITS', '', 'value', 'text', FEDEX_WEIGHTUNITS);
						?>
					</td>
					<td><?php echo JHTML::tooltip(JText::_('PLG_REDSHOP_SHIPPING_FEDEX_WEIGHT_UNITS_LBL'), JText::_('PLG_REDSHOP_SHIPPING_FEDEX_WEIGHT_UNITS_LBL'), 'tooltip.png', '', '', false);?></td>
				</tr>
			</table>

			<?php
			return true;
		}
	}

	public function onWriteconfig($d)
	{
		if ($d['element'] == $this->classname)
		{
			$maincfgfile = JPATH_ROOT . "/plugins/redshop_shipping/$this->classname/$this->classname.cfg.php";

			$my_config_array = array(
				"FEDEX_ACCOUNT_NUMBER"      => $d['FEDEX_ACCOUNT_NUMBER'],
				"FEDEX_METER_NUMBER"        => $d['FEDEX_METER_NUMBER'],
				"FEDEX_CARRIERCODE"         => $d['FEDEX_CARRIERCODE'],
				"FEDEX_SERVICETYPE"         => serialize($d['FEDEX_SERVICETYPE']),
				"FEDEX_DROPOFFTYPE"         => $d['FEDEX_DROPOFFTYPE'],
				"FEDEX_PACKAGINGTYPE"       => $d['FEDEX_PACKAGINGTYPE'],
				"FEDEX_WEIGHTUNITS"         => $d['FEDEX_WEIGHTUNITS'],
				"FEDEX_DEVELOPMENT"         => $d['FEDEX_DEVELOPMENT'],
				"FEDEX_KEY"                 => $d['FEDEX_KEY'],
				"FEDEX_PASSWORD"            => $d['FEDEX_PASSWORD'],
				"FEDEX_SHIPPER_ADDRESS"     => $d['FEDEX_SHIPPER_ADDRESS'],
				"FEDEX_SHIPPER_CITY"        => $d['FEDEX_SHIPPER_CITY'],
				"FEDEX_SHIPPER_STATE"       => $d['FEDEX_SHIPPER_STATE'],
				"FEDEX_SHIPPER_POSTAL_CODE" => $d['FEDEX_SHIPPER_POSTAL_CODE'],
				"FEDEX_SHIPPER_COUNTRY_CODE" => $d['FEDEX_SHIPPER_COUNTRY_CODE'],
				"FEDEX_DISCOUNT"            => $d['FEDEX_DISCOUNT']
				// END CUSTOM CODE
			);

			$config = "<?php ";

			foreach ($my_config_array as $key => $value)
			{
				$config .= "define ('$key', '$value');\n";
			}

			$config .= "?>";

			if ($fp = fopen($maincfgfile, "w"))
			{
				fputs($fp, $config, strlen($config));
				fclose($fp);

				return true;
			}
			else
			{
				return false;
			}
		}
	}

	public function onListRates(&$d)
	{
		$shippinghelper = shipping::getInstance();
		$producthelper = productHelper::getInstance();
		$redconfig = Redconfiguration::getInstance();
		include_once JPATH_ROOT . "/plugins/redshop_shipping/$this->classname/$this->classname.cfg.php";
		$shipping = $shippinghelper->getShippingMethodByClass($this->classname);
		$itemparams = new JRegistry($shipping->params);

		$fedex_accountnumber = FEDEX_ACCOUNT_NUMBER;
		$fedex_meternumber = FEDEX_METER_NUMBER;
		$fedex_carriercode = FEDEX_CARRIERCODE;
		$fedex_servicetype = unserialize(FEDEX_SERVICETYPE);
		$fedex_dropofftype = FEDEX_DROPOFFTYPE;
		$fedex_packagingtype = FEDEX_PACKAGINGTYPE;
		$fedex_weightunits = FEDEX_WEIGHTUNITS;
		$fedex_key = FEDEX_KEY;
		$fedex_pass = FEDEX_PASSWORD;
		$shippingrate = array();
		$rate = 0;

		$unitRatio = $producthelper->getUnitConversation($fedex_weightunits, strtolower(Redshop::getConfig()->get('DEFAULT_WEIGHT_UNIT')));

		if ($fedex_weightunits == 'lbs')
		{
			$fedex_weightunits = 'LB';
		}
		else
		{
			$fedex_weightunits = 'KG';
		}

		$unitRatioVolume = $producthelper->getUnitConversation('inch', Redshop::getConfig()->get('DEFAULT_VOLUME_UNIT'));
		$totaldimention = $shippinghelper->getCartItemDimention();
		$carttotalQnt = $totaldimention['totalquantity'];
		$carttotalWeight = $totaldimention['totalweight'];

		// Check for not zero
		if ($unitRatio != 0)
		{
			// Converting weight in kg
			$carttotalWeight = $carttotalWeight * $unitRatio;
		}

		$shippinginfo = $shippinghelper->getShippingAddress($d['users_info_id']);

		if (count($shippinginfo) < 1)
		{
			return $shippingrate;
		}

		if (isset($d['shipping_box_id']) && $d['shipping_box_id'])
		{
			$whereShippingBoxes = $shippinghelper->getBoxDimensions($d['shipping_box_id']);
		}
		else
		{
			$whereShippingBoxes = array();
			$productData = $shippinghelper->getProductVolumeShipping();
			$whereShippingBoxes['box_length'] = $productData[2]['length'];
			$whereShippingBoxes['box_width'] = $productData[1]['width'];
			$whereShippingBoxes['box_height'] = $productData[0]['height'];
		}

		if (is_array($whereShippingBoxes) && count($whereShippingBoxes) > 0 && $unitRatioVolume > 0)
		{
			$shipping_length = (int) ($whereShippingBoxes['box_length'] * $unitRatioVolume);
			$shipping_width = (int) ($whereShippingBoxes['box_width'] * $unitRatioVolume);
			$shipping_height = (int) ($whereShippingBoxes['box_height'] * $unitRatioVolume);
		}
		else
		{
			return $shippingrate;
		}

		$billing = $producthelper->getUserInformation($shippinginfo->user_id);

		if (count($billing) < 1)
		{
			return $shippingrate;
		}

		if (isset($shippinginfo->country_code))
		{
			$shippinginfo->country_2_code = $redconfig->getCountryCode2($shippinginfo->country_code);
		}

		if (isset($billing->country_code))
		{
			$billing->country_2_code = $redconfig->getCountryCode2($billing->country_code);
		}

		if (isset($shippinginfo->state_code))
		{
			$shippinginfo->state_2_code = $shippinginfo->state_code;

			if (strlen($billing->state_code) > 2)
			{
				$shippinginfo->state_2_code = $redconfig->getStateCode2($shippinginfo->state_code);
			}
		}

		if (isset($billing->state_code))
		{
			$billing->state_2_code = $billing->state_code;

			if (strlen($billing->state_code) > 2)
			{
				$billing->state_2_code = $redconfig->getStateCode2($billing->state_code);
			}
		}

		$country_code = Redshop::getConfig()->get('SHOP_COUNTRY');

		if ($country_code != '')
		{
			$billing->country_2_code = $redconfig->getCountryCode2($country_code);
		}

		// The XML that will be posted to UPS
		if (FEDEX_DEVELOPMENT == 1)
		{
			$path_to_wsdl = JURI::root() . 'plugins/redshop_shipping/' . $this->classname . '/wsdl/RateService_v9_test.wsdl';
		}
		else
		{
			$path_to_wsdl = JURI::root() . 'plugins/redshop_shipping/' . $this->classname . '/wsdl/RateService_v9.wsdl';
		}

		$shippingarray = array(
							"StreetLines"         => array($shippinginfo->address),
							"City"                => $shippinginfo->city,
							"StateOrProvinceCode" => $shippinginfo->state_2_code,
							"PostalCode"          => $shippinginfo->zipcode,
							"CountryCode"         => $shippinginfo->country_2_code
						);
		$shipperarray = array(
							"StreetLines"         => array(FEDEX_SHIPPER_ADDRESS),
							"City"                => FEDEX_SHIPPER_CITY,
							"StateOrProvinceCode" => FEDEX_SHIPPER_STATE,
							"PostalCode"          => FEDEX_SHIPPER_POSTAL_CODE,
							"CountryCode"         => FEDEX_SHIPPER_COUNTRY_CODE
						);

		if (in_array("GROUND_HOME_DELIVERY", $fedex_servicetype))
		{
			$residential = array("Residential" => 1);
			$shippingarray = array_merge($shippingarray, $residential);
			$shipperarray = array_merge($shipperarray, $residential);
			$residentialflag = 1;
		}

		ob_flush();
		ini_set("soap.wsdl_cache_enabled", "0");

		$client = new SoapClient($path_to_wsdl, array("trace" => 1));

		$request['WebAuthenticationDetail'] = array('UserCredential' => array('Key' => FEDEX_KEY, 'Password' => FEDEX_PASSWORD));
		$request['ClientDetail'] = array('AccountNumber' => FEDEX_ACCOUNT_NUMBER, 'MeterNumber' => FEDEX_METER_NUMBER);
		$request['TransactionDetail'] = array('CustomerTransactionId' => ' *** Rate Available Services Request v10 using PHP ***');
		$request['Version'] = array('ServiceId' => 'crs', 'Major' => '9', 'Intermediate' => '0', 'Minor' => '0');
		$request['ReturnTransitAndCommit'] = true;

		// Valid values REGULAR_PICKUP, REQUEST_COURIER, ...
		$request['RequestedShipment']['DropoffType'] = $fedex_dropofftype;
		$request['RequestedShipment']['ShipTimestamp'] = date('c');

		// Service Type and Packaging Type are not passed in the request

		$request['RequestedShipment']['Shipper'] = array('Address' => $shipperarray);
		$request['RequestedShipment']['Recipient'] = array('Address' => $shippingarray);
		$request['RequestedShipment']['ShippingChargesPayment'] = array(
																		'PaymentType'   => 'SENDER',
																		'Payor'         => array(
																							'AccountNumber' => FEDEX_ACCOUNT_NUMBER, // Replace 'XXX' with payor's account number
																							'CountryCode'   => 'US'
																							)
																	);
		$request['RequestedShipment']['RateRequestTypes'] = 'ACCOUNT';
		$request['RequestedShipment']['RateRequestTypes'] = 'LIST';
		$request['RequestedShipment']['PackageCount'] = '1';

		$request['RequestedShipment']['RequestedPackageLineItems'] = array(
																		'0' => array(
																					'SequenceNumber'    => 1,
																					'GroupPackageCount' => $carttotalQnt,
																					'Weight'			=> array(
																											'Value'  => round($carttotalWeight, 2),
																											'Units'  => strtoupper($fedex_weightunits)
																											),
																					'Dimensions' 		=> array(
																											'Length'     => $shipping_length,
																											'Width'      => $shipping_width,
																											'Height'     => $shipping_height,
																											'Units'      => 'IN'
																											)
																					)
																		);

		try
		{
			$response = $client->getRates($request);

			if ($response->HighestSeverity == 'FAILURE' || $response->HighestSeverity == 'ERROR' || $response->HighestSeverity == 'WARNING')
			{
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
				$error = 0;
				$i = 0;

				foreach ($response->RateReplyDetails as $rateReply)
				{
					if (in_array($rateReply->ServiceType, $fedex_servicetype))
					{
						$Amount = $rateReply->RatedShipmentDetails[0]->ShipmentRateDetail->TotalNetCharge->Amount;

						if (Redshop::getConfig()->get('FEDEX_DISCOUNT') == 0)
						{
							$Amount += $rateReply->RatedShipmentDetails[0]->EffectiveNetDiscount->Amount;
						}

						$shipping_rate_id = RedshopShippingRate::encrypt(
											array(
												__CLASS__,
												$shipping->name,
												$rateReply->ServiceType,
												number_format($Amount, 2, '.', ''),
												$shipping->name,
												'single'
											)
										);

						$shippingrate[$i] = new stdClass;
						$shippingrate[$i]->text  = JText::_('PLG_REDSHOP_SHIPPING_' . $rateReply->ServiceType . '_LBL');
						$shippingrate[$i]->value = $shipping_rate_id;
						$shippingrate[$i]->rate  = $Amount;
						$shippingrate[$i]->vat   = 0;

						$i++;
					}
				}
			}
		}
		catch (SoapFault $exception)
		{
			// Get Exception
		}

		return $shippingrate;
	}
}
