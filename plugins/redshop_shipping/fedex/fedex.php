<?php
/**
 * @package     RedSHOP
 * @subpackage  Plugin
 *
 * @copyright   Copyright (C) 2005 - 2013 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

defined('_JEXEC') or die;

jimport('joomla.plugin.plugin');

/**
 * Joomla! System Logging Plugin
 *
 * @package        Joomla
 * @subpackage     System
 */

JLoader::import('LoadHelpers', JPATH_SITE . '/components/com_redshop');
JLoader::load('RedshopHelperProduct');
JLoader::load('RedshopHelperAdminConfiguration');
JLoader::load('RedshopHelperAdminShipping');

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
					<td><strong><?php echo JText::_('COM_REDSHOP_FEDEX_DEVELOPMENT_LBL') ?></strong></td>
					<td>
					<?php
						echo JHtml::_('select.booleanlist', 'FEDEX_DEVELOPMENT', '', FEDEX_DEVELOPMENT);
					?>
					</td>
					<td><?php echo JHTML::tooltip(JText::_('COM_REDSHOP_FEDEX_DEVELOPMENT_LBL'), JText::_('COM_REDSHOP_FEDEX_DEVELOPMENT_LBL'), 'tooltip.png', '', '', false);?></td>
				</tr>
				<tr class="row0">
					<td><strong><?php echo JText::_('COM_REDSHOP_FEDEX_ACCOUNT_NUMBER_LBL') ?></strong></td>
					<td><input type="text" name="FEDEX_ACCOUNT_NUMBER" class="inputbox" value="<?php echo FEDEX_ACCOUNT_NUMBER ?>"/></td>
					<td><?php echo JHTML::tooltip(JText::_('COM_REDSHOP_FEDEX_ACCOUNT_NUMBER_LBL'), JText::_('COM_REDSHOP_FEDEX_ACCOUNT_NUMBER_LBL'), 'tooltip.png', '', '', false);?></td>
				</tr>
				<tr class="row1">
					<td><strong><?php echo JText::_('COM_REDSHOP_FEDEX_METER_NUMBER_LBL') ?></strong></td>
					<td><input type="text" name="FEDEX_METER_NUMBER" class="inputbox"
					           value="<?php echo FEDEX_METER_NUMBER ?>"/></td>
					<td><?php echo JHTML::tooltip(JText::_('COM_REDSHOP_FEDEX_METER_NUMBER_LBL'), JText::_('COM_REDSHOP_FEDEX_METER_NUMBER_LBL'), 'tooltip.png', '', '', false);?></td>
				</tr>
				<tr class="row1">
					<td><strong><?php echo JText::_('COM_REDSHOP_FEDEX_KEY_LBL') ?></strong></td>
					<td><input type="text" name="FEDEX_KEY" class="inputbox" value="<?php echo FEDEX_KEY ?>"/></td>
					<td><?php echo JHTML::tooltip(JText::_('COM_REDSHOP_FEDEX_KEY_LBL'), JText::_('COM_REDSHOP_FEDEX_KEY_LBL'), 'tooltip.png', '', '', false);?></td>
				</tr>
				<tr class="row1">
					<td><strong><?php echo JText::_('COM_REDSHOP_FEDEX_PASSWORD_LBL') ?></strong></td>
					<td><input type="text" name="FEDEX_PASSWORD" class="inputbox" value="<?php echo FEDEX_PASSWORD ?>"/>
					</td>
					<td><?php echo JHTML::tooltip(JText::_('COM_REDSHOP_FEDEX_PASSWORD_LBL'), JText::_('COM_REDSHOP_FEDEX_PASSWORD_LBL'), 'tooltip.png', '', '', false);?></td>
				</tr>

				<tr class="row0">
					<td colspan="3"></td>
				</tr>
				<tr class="row1">
					<td><strong><?php echo JText::_('COM_REDSHOP_FEDEX_SHIPPER_ADDRESS_LBL') ?></strong></td>
					<td><input type="text" name="FEDEX_SHIPPER_ADDRESS" class="inputbox"
					           value="<?php echo FEDEX_SHIPPER_ADDRESS ?>"/></td>
					<td><?php echo JHTML::tooltip(JText::_('COM_REDSHOP_FEDEX_SHIPPER_ADDRESS_LBL'), JText::_('COM_REDSHOP_FEDEX_SHIPPER_ADDRESS_LBL'), 'tooltip.png', '', '', false);?></td>
				</tr>
				<tr class="row0">
					<td><strong><?php echo JText::_('COM_REDSHOP_FEDEX_SHIPPER_CITY_LBL') ?></strong></td>
					<td><input type="text" name="FEDEX_SHIPPER_CITY" class="inputbox"
					           value="<?php echo FEDEX_SHIPPER_CITY ?>"/></td>
					<td><?php echo JHTML::tooltip(JText::_('COM_REDSHOP_FEDEX_SHIPPER_CITY_LBL'), JText::_('COM_REDSHOP_FEDEX_SHIPPER_CITY_LBL'), 'tooltip.png', '', '', false);?></td>
				</tr>
				<tr class="row1">
					<td><strong><?php echo JText::_('COM_REDSHOP_FEDEX_SHIPPER_STATE_LBL') ?></strong></td>
					<td><input type="text" name="FEDEX_SHIPPER_STATE" class="inputbox"
					           value="<?php echo FEDEX_SHIPPER_STATE ?>"/></td>
					<td><?php echo JHTML::tooltip(JText::_('COM_REDSHOP_FEDEX_SHIPPER_STATE_LBL'), JText::_('COM_REDSHOP_FEDEX_SHIPPER_STATE_LBL'), 'tooltip.png', '', '', false);?></td>
				</tr>
				<tr class="row0">
					<td><strong><?php echo JText::_('COM_REDSHOP_FEDEX_SHIPPER_POSTAL_CODE_LBL') ?></strong></td>
					<td><input type="text" name="FEDEX_SHIPPER_POSTAL_CODE" class="inputbox"
					           value="<?php echo FEDEX_SHIPPER_POSTAL_CODE ?>"/></td>
					<td><?php echo JHTML::tooltip(JText::_('COM_REDSHOP_FEDEX_SHIPPER_POSTAL_CODE_LBL'), JText::_('COM_REDSHOP_FEDEX_SHIPPER_POSTAL_CODE_LBL'), 'tooltip.png', '', '', false);?></td>
				</tr>

				<tr class="row1">
					<td colspan="3"></td>
				</tr>
				<tr class="row0">
					<td colspan="3"></td>
				</tr>

				<tr class="row1">
					<td><strong><?php echo JText::_('COM_REDSHOP_FEDEX_DISCOUNT_LBL') ?></strong></td>
					<td>
					<?php
						echo JHtml::_('select.booleanlist', 'FEDEX_DISCOUNT', '', FEDEX_DISCOUNT);
					?>
					</td>
					<td><?php echo JHTML::tooltip(JText::_('COM_REDSHOP_FEDEX_DISCOUNT_LBL'), JText::_('COM_REDSHOP_FEDEX_DISCOUNT_LBL'), 'tooltip.png', '', '', false);?></td>
				</tr>
				<tr class="row0">
					<td><strong><?php echo JText::_('COM_REDSHOP_FEDEX_CARRIERCODE_LBL') ?></strong></td>
					<td>
						<?php
							$source = array();
							$source['FDXE'] = JText::_('COM_REDSHOP_FDXE_LBL');
							$source['FDXG'] = JText::_('COM_REDSHOP_FDXG_LBL');
							echo JHTML::_('select.genericlist', $source, 'FEDEX_CARRIERCODE', '', 'value', 'text', FEDEX_CARRIERCODE);
						?>
					</td>
					<td><?php echo JHTML::tooltip(JText::_('COM_REDSHOP_FEDEX_CARRIERCODE_LBL'), JText::_('COM_REDSHOP_FEDEX_CARRIERCODE_LBL'), 'tooltip.png', '', '', false);?></td>
				</tr>
				<tr class="row0">
					<td><strong><?php echo JText::_('COM_REDSHOP_FEDEX_SERVICETYPE_LBL') ?></strong></td>
					<td>
						<?php
							$source                                        = array();
							$source['PRIORITY_OVERNIGHT']                  = JText::_('COM_REDSHOP_PRIORITYOVERNIGHT_LBL');
							$source['STANDARD_OVERNIGHT']                  = JText::_('COM_REDSHOP_STANDARDOVERNIGHT_LBL');
							$source['FIRST_OVERNIGHT']                     = JText::_('COM_REDSHOP_FIRSTOVERNIGHT_LBL');
							$source['FEDEX_2_DAY']                         = JText::_('COM_REDSHOP_FEDEX2DAY_LBL');
							$source['FEDEX_EXPRESS_SAVER']                 = JText::_('COM_REDSHOP_FEDEXEXPRESSSAVER_LBL');
							$source['INTERNATIONAL_PRIORITY']              = JText::_('COM_REDSHOP_INTERNATIONALPRIORITY_LBL');
							$source['INTERNATIONAL_ECONOMY']               = JText::_('COM_REDSHOP_INTERNATIONALECONOMY_LBL');
							$source['INTERNATIONAL_FIRST']                 = JText::_('COM_REDSHOP_INTERNATIONALFIRST_LBL');
							$source['FEDEX_1_DAY_FREIGHT']                 = JText::_('COM_REDSHOP_FEDEX1DAYFREIGHT_LBL');
							$source['FEDEX_2_DAY_FREIGHT']                 = JText::_('COM_REDSHOP_FEDEX2DAYFREIGHT_LBL');
							$source['FEDEX_3_DAY_FREIGHT']                 = JText::_('COM_REDSHOP_FEDEX3DAYFREIGHT_LBL');
							$source['FEDEX_GROUND']                        = JText::_('COM_REDSHOP_FEDEXGROUND_LBL');
							$source['GROUND_HOME_DELIVERY']                = JText::_('COM_REDSHOP_GROUNDHOMEDELIVERY_LBL');
							$source['INTERNATIONAL_PRIORITY_FREIGHT']      = JText::_('COM_REDSHOP_INTERNATIONALPRIORITY_FREIGHT_LBL');
							$source['INTERNATIONAL_ECONOMY_FREIGHT']       = JText::_('COM_REDSHOP_INTERNATIONALECONOMY_FREIGHT_LBL');
							$source['EUROPE_FIRST_INTERNATIONAL_PRIORITY'] = JText::_('COM_REDSHOP_EUROPEFIRSTINTERNATIONALPRIORITY_LBL');

							echo JHTML::_('select.genericlist', $source, 'FEDEX_SERVICETYPE[]', 'multiple="multiple" size="10"', 'value', 'text', unserialize(FEDEX_SERVICETYPE));
						?>
					</td>
					<td><?php echo JHTML::tooltip(JText::_('COM_REDSHOP_FEDEX_SERVICETYPE_LBL'), JText::_('COM_REDSHOP_FEDEX_SERVICETYPE_LBL'), 'tooltip.png', '', '', false);?></td>
				</tr>
				<tr class="row0">
					<td><strong><?php echo JText::_('COM_REDSHOP_FEDEX_DROPOFFTYPE_LBL') ?></strong></td>
					<td>
						<?php
							$source                            = array();
							$source['REGULAR_PICKUP']          = JText::_('COM_REDSHOP_REGULARPICKUP_LBL');
							$source['REQUEST_COURIER']         = JText::_('COM_REDSHOP_REQUESTCOURIER_LBL');
							$source['DROP_BOX']                = JText::_('COM_REDSHOP_DROPBOX_LBL');
							$source['BUSINESS_SERVICE_CENTER'] = JText::_('COM_REDSHOP_BUSINESSSERVICE_CENTER_LBL');
							$source['STATION']                 = JText::_('COM_REDSHOP_STATION_LBL');

							echo JHTML::_('select.genericlist', $source, 'FEDEX_DROPOFFTYPE', '', 'value', 'text', FEDEX_DROPOFFTYPE);
						?>
					</td>
					<td><?php echo JHTML::tooltip(JText::_('COM_REDSHOP_FEDEX_DROPOFFTYPE_LBL'), JText::_('COM_REDSHOP_FEDEX_DROPOFFTYPE_LBL'), 'tooltip.png', '', '', false);?></td>
				</tr>
				<tr class="row0">
					<td><strong><?php echo JText::_('COM_REDSHOP_FEDEX_PACKAGINGTYPE_LBL') ?></strong></td>
					<td>
						<?php
							$source                   = array();
							$source['FEDEX_ENVELOPE'] = JText::_('COM_REDSHOP_FEDEXENVELOPE_LBL');
							$source['FEDEX_PAK']      = JText::_('COM_REDSHOP_FEDEXPAK_LBL');
							$source['FEDEX_BOX']      = JText::_('COM_REDSHOP_FEDEXBOX_LBL');
							$source['FEDEX_TUBE']     = JText::_('COM_REDSHOP_FEDEXTUBE_LBL');
							$source['FEDEX_10KG_BOX'] = JText::_('COM_REDSHOP_FEDEX10KGBOX_LBL');
							$source['FEDEX_25KG_BOX'] = JText::_('COM_REDSHOP_FEDEX25KGBOX_LBL');
							$source['YOUR_PACKAGING'] = JText::_('COM_REDSHOP_YOURPACKAGING_LBL');

							echo JHTML::_('select.genericlist', $source, 'FEDEX_PACKAGINGTYPE', '', 'value', 'text', FEDEX_PACKAGINGTYPE);
						?>
					</td>
					<td><?php echo JHTML::tooltip(JText::_('COM_REDSHOP_FEDEX_PACKAGINGTYPE_LBL'), JText::_('COM_REDSHOP_FEDEX_PACKAGINGTYPE_LBL'), 'tooltip.png', '', '', false);?></td>
				</tr>
				<tr class="row0">
					<td><strong><?php echo JText::_('COM_REDSHOP_FEDEX_WEIGHTUNITS_LBL') ?></strong></td>
					<td>
						<?php
							$source        = array();
							$source['lbs'] = JText::_('COM_REDSHOP_LBS');
							$source['kg']  = JText::_('COM_REDSHOP_KGS');

							echo JHTML::_('select.genericlist', $source, 'FEDEX_WEIGHTUNITS', '', 'value', 'text', FEDEX_WEIGHTUNITS);
						?>
					</td>
					<td><?php echo JHTML::tooltip(JText::_('COM_REDSHOP_FEDEX_WEIGHTUNITS_LBL'), JText::_('COM_REDSHOP_FEDEX_WEIGHTUNITS_LBL'), 'tooltip.png', '', '', false);?></td>
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
		$shippinghelper = new shipping;
		$producthelper = new producthelper;
		$redconfig = new Redconfiguration;
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

		$unitRatio = $producthelper->getUnitConversation($fedex_weightunits, strtolower(DEFAULT_WEIGHT_UNIT));

		if ($fedex_weightunits == 'lbs')
		{
			$fedex_weightunits = 'LB';
		}
		else
		{
			$fedex_weightunits = 'KG';
		}

		$unitRatioVolume = $producthelper->getUnitConversation('inch', DEFAULT_VOLUME_UNIT);
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

		$country_code = SHOP_COUNTRY;

		if ($country_code != '')
		{
			$billing->country_2_code = $redconfig->getCountryCode2($country_code);
		}

		// The XML that will be posted to UPS
		if (FEDEX_DEVELOPMENT == 1)
		{
			$path_to_wsdl = JURI::root() . 'plugins/redshop_shipping/' . $this->classname . '/RateService_v9_test.wsdl';
		}
		else
		{
			$path_to_wsdl = JURI::root() . 'plugins/redshop_shipping/' . $this->classname . '/RateService_v9.wsdl';
		}

		$shippingarray = array(
							"StreetLines"         => array($shippinginfo->address),
							"City"                => $shippinginfo->city,
							"StateOrProvinceCode" => $shippinginfo->state_2_code,
							"PostalCode"          => $shippinginfo->zipcode,
							"CountryCode"         => $shippinginfo->country_2_code
						);
		$billingarray = array(
							"StreetLines"         => array(FEDEX_SHIPPER_ADDRESS),
							"City"                => FEDEX_SHIPPER_CITY,
							"StateOrProvinceCode" => FEDEX_SHIPPER_STATE,
							"PostalCode"          => FEDEX_SHIPPER_POSTAL_CODE,
							"CountryCode"         => $billing->country_2_code
						);

		if (in_array("GROUND_HOME_DELIVERY", $fedex_servicetype))
		{
			$residential = array("Residential" => 1);
			$shippingarray = array_merge($shippingarray, $residential);
			$billingarray = array_merge($billingarray, $residential);
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

		$request['RequestedShipment']['Shipper'] = array('Address' => $billingarray);
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

			if ($this->setEndpoint('changeEndpoint'))
			{
				$newLocation = $client->__setLocation($this->setEndpoint('endpoint'));
			}

			if ($response->HighestSeverity != 'FAILURE' && $response->HighestSeverity != 'ERROR' && $response->HighestSeverity != 'WARNING')
			{
				if (count($response->Notifications) > 1)
				{
					foreach ($response->Notifications as $notification)
					{
						$str .= "<b>Fed ex " . $notification->Severity . " : " . $notification->Code . "</b> : " . $notification->Message . "<br>";
					}
				}
				else
				{
					$str = "<b>Fed ex " . $response->Notifications->Severity . "</b> : " . $response->Notifications->Message;
				}

				echo $str;
			}

			if ($response->HighestSeverity != 'FAILURE' && $response->HighestSeverity != 'ERROR')
			{
				$error = 0;
				$i = 0;

				foreach ($response->RateReplyDetails as $rateReply)
				{
					if (in_array($rateReply->ServiceType, $fedex_servicetype))
					{
						$Amount = $rateReply->RatedShipmentDetails[0]->ShipmentRateDetail->TotalNetCharge->Amount;

						if (FEDEX_DISCOUNT == 0)
							$Amount += $rateReply->RatedShipmentDetails[0]->EffectiveNetDiscount->Amount;

						$shipping_rate_id = $shippinghelper->encryptShipping(__CLASS__ . "|" . $shipping->name . "|" . $rateReply->ServiceType . "|" . number_format($Amount, 2, '.', '') . "|" . $shipping->name . "|single");
						$shippingrate[$i]->text = $rateReply->ServiceType;
						$shippingrate[$i]->value = $shipping_rate_id;
						$shippingrate[$i]->rate = $Amount;
						$shippingrate[$i]->vat = 0;
						$i++;
					}
				}
			}
			else
			{
				$error = "1";
			}
		}
		catch (SoapFault $exception)
		{
			$error = 1;
		}

		return $shippingrate;
	}

	public function setEndpoint($var)
	{
		if ($var == 'changeEndpoint')
		{
			return false;
		}

		if ($var == 'endpoint')
		{
			return '';
		}
	}
}
