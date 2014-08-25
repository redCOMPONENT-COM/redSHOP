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
//defined('_VALID_MOS') or die('Direct Access to this location is not allowed.');

if (!defined('_VALID_MOS') && !defined('_JEXEC')) die('Direct Access to ' . basename(__FILE__) . ' is not allowed.');
JHTML::_('behavior.tooltip');

JLoader::import('LoadHelpers', JPATH_SITE . '/components/com_redshop');
JLoader::load('RedshopHelperProduct');
JLoader::load('RedshopHelperCurrency');
JLoader::load('RedshopHelperAdminShipping');
JLoader::load('RedshopHelperAdminConfiguration');

class plgredshop_shippingups extends JPlugin
{
	var $payment_code = "ups";
	var $classname = "ups";

	function onShowconfig($ps)
	{
		if ($ps->element == $this->classname)
		{
			?>
			<table class="adminform">
			<tr class="row0">
				<td><strong><?php echo JText::_('COM_REDSHOP_SHIPPING_METHOD_UPS_ACCESS_CODE') ?></strong></td>
				<td><input type="text" name="UPS_ACCESS_CODE" class="inputbox" value="<?php echo UPS_ACCESS_CODE ?>"/>
				</td>
				<td><?php echo JHTML::tooltip(JText::_('COM_REDSHOP_SHIPPING_METHOD_UPS_ACCESS_CODE'), JText::_('COM_REDSHOP_SHIPPING_METHOD_UPS_ACCESS_CODE'), 'tooltip.png', '', '', false);?></td>
			</tr>
			<tr class="row1">
				<td><strong><?php echo JText::_('COM_REDSHOP_SHIPPING_METHOD_UPS_USER_ID') ?></strong></td>
				<td><input type="text" name="UPS_USER_ID" class="inputbox" value="<?php echo UPS_USER_ID ?>"/></td>
				<td><?php echo JHTML::tooltip(JText::_('COM_REDSHOP_SHIPPING_METHOD_UPS_USER_ID'), JText::_('COM_REDSHOP_SHIPPING_METHOD_UPS_USER_ID'), 'tooltip.png', '', '', false);?></td>
			</tr>
			<tr class="row0">
				<td><strong><?php echo JText::_('COM_REDSHOP_SHIPPING_METHOD_UPS_PASSWORD') ?></strong></td>
				<td><input type="text" name="UPS_PASSWORD" class="inputbox" value="<?php echo UPS_PASSWORD ?>"/></td>
				<td><?php echo JHTML::tooltip(JText::_('COM_REDSHOP_SHIPPING_METHOD_UPS_PASSWORD'), JText::_('COM_REDSHOP_SHIPPING_METHOD_UPS_PASSWORD'), 'tooltip.png', '', '', false);?></td>
			</tr>
			<tr class="row1">
				<td><strong><?php echo JText::_('COM_REDSHOP_SHIPPING_METHOD_UPS_PICKUP_METHOD') ?></strong></td>
				<td><select class="inputbox" name="pickup_type">
						<option <?php if (UPS_PICKUP_TYPE == "01") echo "selected=\"selected\"" ?> value="01">Daily
							Pickup
						</option>
						<option <?php if (UPS_PICKUP_TYPE == "03") echo "selected=\"selected\"" ?> value="03">Customer
							Counter
						</option>
						<option <?php if (UPS_PICKUP_TYPE == "06") echo "selected=\"selected\"" ?> value="06">One Time
							Pickup
						</option>
						<option <?php if (UPS_PICKUP_TYPE == "07") echo "selected=\"selected\"" ?> value="07">On Call
							Air Pickup
						</option>
						<option <?php if (UPS_PICKUP_TYPE == "19") echo "selected=\"selected\"" ?> value="19">Letter
							Center
						</option>
						<option <?php if (UPS_PICKUP_TYPE == "20") echo "selected=\"selected\"" ?> value="20">Air
							Service Center
						</option>
					</select></td>
				<td><?php echo JHTML::tooltip(JText::_('COM_REDSHOP_SHIPPING_METHOD_UPS_PICKUP_METHOD'), JText::_('COM_REDSHOP_SHIPPING_METHOD_UPS_PICKUP_METHOD'), 'tooltip.png', '', '', false);?></td>
			</tr>
			<tr class="row0">
				<td><strong><?php echo JText::_('COM_REDSHOP_SHIPPING_METHOD_UPS_PACKAGE_TYPE') ?></strong></td>
				<td><select class="inputbox" name="package_type">
						<option <?php if (UPS_PACKAGE_TYPE == "00") echo "selected=\"selected\"" ?> value="00">Unknown
						<option <?php if (UPS_PACKAGE_TYPE == "01") echo "selected=\"selected\"" ?> value="01">UPS
							letter
						</option>
						<option <?php if (UPS_PACKAGE_TYPE == "02") echo "selected=\"selected\"" ?> value="02">Package
						</option>
						<option <?php if (UPS_PACKAGE_TYPE == "03") echo "selected=\"selected\"" ?> value="03">UPS
							Tube
						</option>
						<option <?php if (UPS_PACKAGE_TYPE == "04") echo "selected=\"selected\"" ?> value="04">UPS Pak
						</option>
						<option <?php if (UPS_PACKAGE_TYPE == "21") echo "selected=\"selected\"" ?> value="21">UPS
							Express Box
						</option>
						<option <?php if (UPS_PACKAGE_TYPE == "24") echo "selected=\"selected\"" ?> value="24">UPS 25Kg
							Box
						</option>
						<option <?php if (UPS_PACKAGE_TYPE == "25") echo "selected=\"selected\"" ?> value="25">UPS 10Kg
							Box
						</option>
					</select></td>
				<td><?php echo JHTML::tooltip(JText::_('COM_REDSHOP_SHIPPING_METHOD_UPS_PACKAGE_TYPE'), JText::_('COM_REDSHOP_SHIPPING_METHOD_UPS_PACKAGE_TYPE'), 'tooltip.png', '', '', false);?></td>
			</tr>
			<tr class="row1">
				<td><strong><?php echo JText::_('COM_REDSHOP_SHIPPING_METHOD_UPS_TYPE_RESIDENTIAL') ?></strong></td>
				<td><select class="inputbox" name="residential">
						<option <?php if (UPS_RESIDENTIAL == "yes") echo "selected=\"selected\"" ?>
							value="yes"><?php echo JText::_('COM_REDSHOP_UPS_RESIDENTIAL') ?></option>
						<option <?php if (UPS_RESIDENTIAL == "no") echo "selected=\"selected\"" ?>
							value="no"><?php echo JText::_('COM_REDSHOP_UPS_COMMERCIAL') ?></option>
					</select></td>
				<td><?php echo JHTML::tooltip(JText::_('COM_REDSHOP_SHIPPING_METHOD_UPS_TYPE_RESIDENTIAL'), JText::_('COM_REDSHOP_SHIPPING_METHOD_UPS_TYPE_RESIDENTIAL'), 'tooltip.png', '', '', false);?></td>
			</tr>
			<tr class="row1">
				<td><strong><?php echo JText::_('COM_REDSHOP_SHIPPING_METHOD_UPS_HANDLING_FEE') ?></strong></td>
				<td><input class="inputbox" type="text" name="handling_fee" value="<?php echo UPS_HANDLING_FEE ?>"/>
				</td>
				<td><?php echo JHTML::tooltip(JText::_('COM_REDSHOP_SHIPPING_METHOD_UPS_HANDLING_FEE'), JText::_('COM_REDSHOP_SHIPPING_METHOD_UPS_HANDLING_FEE'), 'tooltip.png', '', '', false);?></td>
			</tr>
			<?php // BEGIN CUSTOM CODE ?>
			<tr class="row1">
				<td><strong><?php echo JText::_('COM_REDSHOP_SHIP_FROM_ZIPCODE');?></strong></td>
				<td><input class="inputbox" type="text" name="Override_Source_Zip"
				           value="<?php echo Override_Source_Zip ?>"/></td>
				<td><?php echo JHTML::tooltip(JText::_('COM_REDSHOP_SHIP_FROM_ZIPCODE'), JText::_('COM_REDSHOP_SHIP_FROM_ZIPCODE'), 'tooltip.png', '', '', false);?></td>
			</tr>
			<tr class="row0">
				<td><strong><?php echo JText::_('COM_REDSHOP_SHOW_DELIVERY_DAY_QUOTE');?></strong></td>
				<td><input class="inputbox" type="checkbox"
				           name="Show_Delivery_Days_Quote" <?php if (Show_Delivery_Days_Quote == 1) echo "checked=\"checked\""; ?>
				           value="1"/></td>
				<td><?php echo JHTML::tooltip(JText::_('COM_REDSHOP_SHOW_DELIVERY_DAY_QUOTE'), JText::_('COM_REDSHOP_SHOW_DELIVERY_DAY_QUOTE'), 'tooltip.png', '', '', false);?></td>
			</tr>
			<tr class="row1">
				<td><strong><?php echo JText::_('COM_REDSHOP_SHOW_DELIVERY_ETA');?></strong></td>
				<td><input class="inputbox" type="checkbox"
				           name="Show_Delivery_ETA_Quote" <?php if (Show_Delivery_ETA_Quote == 1) echo "checked=\"checked\""; ?>
				           value="1"/></td>
				<td><?php echo JHTML::tooltip(JText::_('COM_REDSHOP_SHOW_DELIVERY_ETA'), JText::_('COM_REDSHOP_SHOW_DELIVERY_ETA'), 'tooltip.png', '', '', false);?></td>
			</tr>
			<tr class="row0">
				<td><strong><?php echo JText::_('COM_REDSHOP_SHOW_DELIVERY_WARNING');?></strong></td>
				<td><input class="inputbox" type="checkbox"
				           name="Show_Delivery_Warning" <?php if (Show_Delivery_Warning == 1) echo "checked=\"checked\""; ?>
				           value="1"/></td>
				<td><?php echo JHTML::tooltip(JText::_('COM_REDSHOP_SHOW_DELIVERY_WARNING'), JText::_('COM_REDSHOP_SHOW_DELIVERY_WARNING'), 'tooltip.png', '', '', false);?></td>
			</tr>
			<tr class="row1">
			<td colspan="3">
			<table>
				<tr class="row0">
					<td colspan="2"><strong><?php echo JText::_('COM_REDSHOP_AUTHORIZED_SHIPPING_METHOD');?></strong>
					</td>
					<td><?php echo JHTML::tooltip(JText::_('COM_REDSHOP_AUTHORIZED_SHIPPING_METHOD'), JText::_('COM_REDSHOP_AUTHORIZED_SHIPPING_METHOD'), 'tooltip.png', '', '', false);?></td>
				</tr>
				<tr class="row1">
					<td>
						<div align="left"><strong><?php echo JText::_('COM_REDSHOP_SHIPPING_METHOD_LBL');?></strong>
						</div>
					</td>
					<td>
						<div align="left"><strong>Enable?</strong></div>
					</td>
					<td>
						<div align="left"><strong>Fuel SurCharge
								Rate(%)</strong><?php echo JHTML::tooltip(JText::_('COM_REDSHOP_SHIPPING_METHOD_LBL'), JText::_('COM_REDSHOP_SHIPPING_METHOD_LBL_TOOLTIP'), 'tooltip.png', '', '', false);?>
						</div>
					</td>
				</tr>
				<tr class="row0">
					<td>UPS Next Day Air</td>
					<td>
						<div align="center"><input type="checkbox" name="UPS_Next_Day_Air"
						                           class="inputbox" <?php if (UPS_Next_Day_Air == 01) echo "checked=\"checked\""; ?>
						                           value="01"/></div>
					</td>
					<td><input class="inputbox" type="text" name="UPS_Next_Day_Air_FSC"
					           value="<?php echo UPS_Next_Day_Air_FSC; ?>"/></td>
				</tr>
				<tr class="row1">
					<td>UPS 2nd Day Air</td>
					<td>
						<div align="center"><input type="checkbox" name="UPS_2nd_Day_Air"
						                           class="inputbox" <?php if (UPS_2nd_Day_Air == 02) echo "checked=\"checked\""; ?>
						                           value="02"/></div>
					</td>
					<td><input class="inputbox" type="text" name="UPS_2nd_Day_Air_FSC"
					           value="<?php echo UPS_2nd_Day_Air_FSC; ?>"/></td>
				</tr>
				<tr class="row0">
					<td>UPS Ground</td>
					<td>
						<div align="center"><input type="checkbox" name="UPS_Ground"
						                           class="inputbox" <?php if (UPS_Ground == 03) echo "checked=\"checked\""; ?>
						                           value="03"/></div>
					</td>
					<td><input class="inputbox" type="text" name="UPS_Ground_FSC"
					           value="<?php echo UPS_Ground_FSC; ?>"/></td>
				</tr>
				<tr class="row1">
					<td>UPS Worldwide Express SM</td>
					<td>
						<div align="center"><input type="checkbox" name="UPS_Worldwide_Express_SM"
						                           class="inputbox" <?php if (UPS_Worldwide_Express_SM == 07) echo "checked=\"checked\""; ?>
						                           value="07"/></div>
					</td>
					<td><input class="inputbox" type="text" name="UPS_Worldwide_Express_SM_FSC"
					           value="<?php echo UPS_Worldwide_Express_SM_FSC; ?>"/></td>
				</tr>
				<tr class="row0">
					<td>UPS Worldwide Expedited SM</td>
					<td>
						<div align="center"><input type="checkbox" name="UPS_Worldwide_Expedited_SM"
						                           class="inputbox" <?php if (UPS_Worldwide_Expedited_SM == '08') echo "checked=\"checked\""; ?>
						                           value="08"/></div>
					</td>
					<td><input class="inputbox" type="text" name="UPS_Worldwide_Expedited_SM_FSC"
					           value="<?php echo UPS_Worldwide_Expedited_SM_FSC; ?>"/></td>
				</tr>
				<tr class="row1">
					<td>UPS Standard</td>
					<td>
						<div align="center">
							<input type="checkbox" name="UPS_Standard"
							       class="inputbox" <?php if (UPS_Standard == 11) echo "checked=\"checked\""; ?>
							       value="11"/>
						</div>
					</td>
					<td><input class="inputbox" type="text" name="UPS_Standard_FSC"
					           value="<?php echo UPS_Standard_FSC; ?>"/>
					</td>
				</tr>
				<tr class="row0">
					<td>UPS 3 Day Select</td>
					<td>
						<div align="center">
							<input type="checkbox" name="UPS_3_Day_Select"
							       class="inputbox" <?php if (UPS_3_Day_Select == 12) echo "checked=\"checked\""; ?>
							       value="12"/>
						</div>
					</td>
					<td><input class="inputbox" type="text" name="UPS_3_Day_Select_FSC"
					           value="<?php echo UPS_3_Day_Select_FSC; ?>"/>
					</td>
				</tr>
				<tr class="row1">
					<td>UPS Next Day Air Saver</td>
					<td>
						<div align="center">
							<input type="checkbox" name="UPS_Next_Day_Air_Saver"
							       class="inputbox" <?php if (UPS_Next_Day_Air_Saver == 13) echo "checked=\"checked\""; ?>
							       value="13"/>
						</div>
					</td>
					<td><input class="inputbox" type="text" name="UPS_Next_Day_Air_Saver_FSC"
					           value="<?php echo UPS_Next_Day_Air_Saver_FSC; ?>"/>
					</td>
				</tr>
				<tr class="row0">
					<td>UPS Next Day Air Early A.M.</td>
					<td>
						<div align="center">
							<input type="checkbox" name="UPS_Next_Day_Air_Early_AM"
							       class="inputbox" <?php if (UPS_Next_Day_Air_Early_AM == 14) echo "checked=\"checked\""; ?>
							       value="14"/>
						</div>
					</td>
					<td><input class="inputbox" type="text" name="UPS_Next_Day_Air_Early_AM_FSC"
					           value="<?php echo UPS_Next_Day_Air_Early_AM_FSC; ?>"/>
					</td>
				</tr>
				<tr class="row1">
					<td>UPS Worldwide Express Plus SM</td>
					<td>
						<div align="center">
							<input type="checkbox" name="UPS_Worldwide_Express_Plus_SM"
							       class="inputbox" <?php if (UPS_Worldwide_Express_Plus_SM == 54) echo "checked=\"checked\""; ?>
							       value="54"/>
						</div>
					</td>
					<td><input class="inputbox" type="text" name="UPS_Worldwide_Express_Plus_SM_FSC"
					           value="<?php echo UPS_Worldwide_Express_Plus_SM_FSC; ?>"/>
					</td>
				</tr>
				<tr class="row0">
					<td>UPS 2nd Day Air A.M</td>
					<td>
						<div align="center">
							<input type="checkbox" name="UPS_2nd_Day_Air_AM"
							       class="inputbox" <?php if (UPS_2nd_Day_Air_AM == 59) echo "checked=\"checked\""; ?>
							       value="59"/>
						</div>
					</td>
					<td><input class="inputbox" type="text" name="UPS_2nd_Day_Air_AM_FSC"
					           value="<?php echo UPS_2nd_Day_Air_AM_FSC; ?>"/>
					</td>
				</tr>
				<tr class="row1">
					<td>UPS Express Saver</td>
					<td>
						<div align="center">
							<input type="checkbox" name="UPS_Saver"
							       class="inputbox" <?php if (UPS_Saver == 65) echo "checked=\"checked\""; ?>
							       value="65"/>
						</div>
					</td>
					<td><input class="inputbox" type="text" name="UPS_Saver_FSC" value="<?php echo UPS_Saver_FSC; ?>"/>
					</td>
				</tr>
				<tr class="row0">
					<td>n/a</td>
					<td>
						<div align="center">
							<input type="checkbox" name="na"
							       class="inputbox" <?php if (na == 64) echo "checked=\"checked\""; ?> value="64"/>
						</div>
					</td>
					<td>&nbsp;
					</td>
				</tr>
			</table>
			<?php
			return true;
		}
	}

	function onWriteconfig($d)
	{
		if ($d['element'] == $this->classname)
		{
			$maincfgfile = JPATH_ROOT . '/plugins/' . $d['plugin'] . '/' . $this->classname . '/' . $this->classname . '.cfg.php';

			$my_config_array = array(
				"UPS_ACCESS_CODE"                   => $d['UPS_ACCESS_CODE'],
				"UPS_USER_ID"                       => $d['UPS_USER_ID'],
				"UPS_PASSWORD"                      => $d['UPS_PASSWORD'],
				"UPS_PICKUP_TYPE"                   => $d['pickup_type'],
				"UPS_PACKAGE_TYPE"                  => $d['package_type'],
				"UPS_RESIDENTIAL"                   => $d['residential'],
				"UPS_HANDLING_FEE"                  => $d['handling_fee'],
				"UPS_TAX_CLASS"                     => $d['tax_class'],

				// BEGIN CUSTOM CODE
				"Override_Source_Zip"               => $d['Override_Source_Zip'],
				"Show_Delivery_Days_Quote"          => $d['Show_Delivery_Days_Quote'],
				"Show_Delivery_ETA_Quote"           => $d['Show_Delivery_ETA_Quote'],
				"Show_Delivery_Warning"             => $d['Show_Delivery_Warning'],
				"UPS_Next_Day_Air"                  => $d['UPS_Next_Day_Air'],
				"UPS_Next_Day_Air_FSC"              => $d['UPS_Next_Day_Air_FSC'],
				"UPS_2nd_Day_Air"                   => $d['UPS_2nd_Day_Air'],
				"UPS_2nd_Day_Air_FSC"               => $d['UPS_2nd_Day_Air_FSC'],
				"UPS_Ground"                        => $d['UPS_Ground'],
				"UPS_Ground_FSC"                    => $d['UPS_Ground_FSC'],
				"UPS_Worldwide_Express_SM"          => $d['UPS_Worldwide_Express_SM'],
				"UPS_Worldwide_Express_SM_FSC"      => $d['UPS_Worldwide_Express_SM_FSC'],
				"UPS_Worldwide_Expedited_SM"        => $d['UPS_Worldwide_Expedited_SM'],
				"UPS_Worldwide_Expedited_SM_FSC"    => $d['UPS_Worldwide_Expedited_SM_FSC'],
				"UPS_Standard"                      => $d['UPS_Standard'],
				"UPS_Standard_FSC"                  => $d['UPS_Standard_FSC'],
				"UPS_3_Day_Select"                  => $d['UPS_3_Day_Select'],
				"UPS_3_Day_Select_FSC"              => $d['UPS_3_Day_Select_FSC'],
				"UPS_Next_Day_Air_Saver"            => $d['UPS_Next_Day_Air_Saver'],
				"UPS_Next_Day_Air_Saver_FSC"        => $d['UPS_Next_Day_Air_Saver_FSC'],
				"UPS_Next_Day_Air_Early_AM"         => $d['UPS_Next_Day_Air_Early_AM'],
				"UPS_Next_Day_Air_Early_AM_FSC"     => $d['UPS_Next_Day_Air_Early_AM_FSC'],
				"UPS_Worldwide_Express_Plus_SM"     => $d['UPS_Worldwide_Express_Plus_SM'],
				"UPS_Worldwide_Express_Plus_SM_FSC" => $d['UPS_Worldwide_Express_Plus_SM_FSC'],
				"UPS_2nd_Day_Air_AM"                => $d['UPS_2nd_Day_Air_AM'],
				"UPS_2nd_Day_Air_AM_FSC"            => $d['UPS_2nd_Day_Air_AM_FSC'],
				"UPS_Saver"                         => $d['UPS_Saver'],
				"UPS_Saver_FSC"                     => $d['UPS_Saver_FSC'],
				"na"                                => $d['na']
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

	function onListRates(&$d)
	{
		$shippinghelper = new shipping;
		$producthelper = new producthelper;
		$redconfig = new Redconfiguration;
		$currency = new CurrencyHelper;
		$shipping = $shippinghelper->getShippingMethodByClass($this->classname);

		$itemparams = new JRegistry($shipping->params);
		$shippingcfg = JPATH_ROOT . '/plugins/' . $shipping->folder . '/' . $shipping->element . '/' . $shipping->element . '.cfg.php';
		include_once $shippingcfg;

		$shippingrate = array();
		$rate = 0;

		// conversation of weight ( ration )
		$unitRatio = $producthelper->getUnitConversation('pounds', DEFAULT_WEIGHT_UNIT);
		$unitRatioVolume = $producthelper->getUnitConversation('inch', DEFAULT_VOLUME_UNIT);

		$totaldimention = $shippinghelper->getCartItemDimention();
		$order_weight = $totaldimention['totalweight'];

		if ($unitRatio != 0)
		{
			$order_weight = $order_weight * $unitRatio; // converting weight in pounds
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
			$shipping_length = ( int ) ($whereShippingBoxes['box_length'] * $unitRatioVolume);
			$shipping_width = ( int ) ($whereShippingBoxes['box_width'] * $unitRatioVolume);
			$shipping_height = ( int ) ($whereShippingBoxes['box_height'] * $unitRatioVolume);
		}
		else
		{
			return $shippingrate;
		}

		if ($order_weight < 1)
		{
			$order_weight = 1;
		}

		if ($order_weight > 150)
		{
			$order_weight = 150.00;
		}

		//The zip that you are shipping to
		$vendor_country_2_code = DEFAULT_SHIPPING_COUNTRY;

		if (DEFAULT_SHIPPING_COUNTRY)
		{
			$vendor_country_2_code = $redconfig->getCountryCode2(DEFAULT_SHIPPING_COUNTRY);
		}

		if (isset($shippinginfo->country_code))
		{
			$shippinginfo->country_2_code = $redconfig->getCountryCode2($shippinginfo->country_code);
		}

		$dest_zip = substr($shippinginfo->zipcode, 0, 5); // Make sure the ZIP is 5 chars long

		//LBS  = Pounds
		//KGS  = Kilograms
		$weight_measure = (DEFAULT_WEIGHT_UNIT == "gram") ? "KGS" : "LBS"; // if change than change conversation base unit also
		$measurecode = ($weight_measure == "KGS") ? "CM" : "IN";

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
		$xmlPost .= "    <CountryCode>$vendor_country_2_code</CountryCode>";
		$xmlPost .= "   </Address>";
		$xmlPost .= "  </Shipper>";
		$xmlPost .= "  <ShipTo>";
		$xmlPost .= "   <Address>";
		$xmlPost .= "    <PostalCode>" . $dest_zip . "</PostalCode>";
		$xmlPost .= "    <CountryCode>" . $shippinginfo->country_2_code . "</CountryCode>";

		if (UPS_RESIDENTIAL == "yes")
		{
			$xmlPost .= "    <ResidentialAddressIndicator/>";
		}

		$xmlPost .= "   </Address>";
		$xmlPost .= "  </ShipTo>";
		$xmlPost .= "  <ShipFrom>";
		$xmlPost .= "   <Address>";
		$xmlPost .= "    <PostalCode>" . Override_Source_Zip . "</PostalCode>";
		$xmlPost .= "    <CountryCode>$vendor_country_2_code</CountryCode>";
		$xmlPost .= "   </Address>";
		$xmlPost .= "  </ShipFrom>";

		// Service is only required, if the Tag "RequestOption" contains the value "rate"
		// We don't want a specific servive, but ALL Rates
		//$xmlPost .= "  <Service>";
		//$xmlPost .= "   <Code>".$shipping_type."</Code>";
		//$xmlPost .= "  </Service>";

		$xmlPost .= "  <Package>";
		$xmlPost .= "   <PackagingType>";
		$xmlPost .= "    <Code>" . UPS_PACKAGE_TYPE . "</Code>";
		$xmlPost .= "   </PackagingType>";
		$xmlPost .= "   <Dimensions>";
		$xmlPost .= "    <UnitOfMeasurement>";
		$xmlPost .= "     <Code>" . $measurecode . "</Code>";
		$xmlPost .= "    </UnitOfMeasurement>";
		$xmlPost .= "    <Length>" . ceil($shipping_length) . "</Length>";
		$xmlPost .= "    <Width>" . ceil($shipping_width) . "</Width>";
		$xmlPost .= "    <Height>" . ceil($shipping_height) . "</Height>";
		$xmlPost .= "   </Dimensions>";
		$xmlPost .= "   <PackageWeight>";
		$xmlPost .= "    <UnitOfMeasurement>";
		$xmlPost .= "     <Code>" . $weight_measure . "</Code>";
		$xmlPost .= "    </UnitOfMeasurement>";
		$xmlPost .= "    <Weight>" . $order_weight . "</Weight>";
		$xmlPost .= "   </PackageWeight>";
		$xmlPost .= "  </Package>";
		$xmlPost .= " </Shipment>";
		$xmlPost .= "</RatingServiceSelectionRequest>";

		// echo htmlentities( $xmlPost );
		$upsURL = "https://www.ups.com:443/ups.app/xml/Rate";
		$error = false;

		$CR = curl_init();
		curl_setopt($CR, CURLOPT_URL, $upsURL); //"?API=RateV2&XML=".$xmlPost);
		curl_setopt($CR, CURLOPT_POST, 1);
		curl_setopt($CR, CURLOPT_FAILONERROR, true);
		curl_setopt($CR, CURLOPT_POSTFIELDS, $xmlPost);
		curl_setopt($CR, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($CR, CURLOPT_SSL_VERIFYPEER, false);
		$xmlResult = curl_exec($CR);

		$xmlDoc = JFactory::getXMLParser('Simple');

		if (!$xmlResult)
		{
			$error = true;
		}
		else
		{
			/* XML Parsing */
			$xmlDoc->loadString($xmlResult, false, true);
			/* Let's check wether the response from UPS is Success or Failure ! */
			if (strstr($xmlResult, "Failure"))
			{
				$error = true;

				return $shippingrate;
			}
		}

		if ($itemparams->get("ups_debug"))
		{
			echo "XML Post: <br>";
			echo "<textarea cols='80' rows='10'>https://www.ups.com:443/ups.app/xml/Rate?" . $xmlPost . "</textarea>";
			echo "<br>";
			echo "XML Result: <br>";
			echo "<textarea cols='80' rows='10'>" . $xmlResult . "</textarea>";
			echo "<br>";
			echo "Cart Contents: " . $order_weight . "<br><br>\n";
		}
		// retrieve the list of all "RatedShipment" Elements

		$matchedchild = $xmlDoc->document->_children;
		$allservicecodes = array(
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
		$myservicecodes = array();

		foreach ($allservicecodes as $servicecode)
		{
			if (constant($servicecode) != '' || constant($servicecode) != 0)
			{
				$myservicecodes[] = constant($servicecode);
			}
		}

		$count = 0;

		for ($t = 0; $t < count($matchedchild); $t++)
		{
			$totalmatchedchild = $matchedchild[$t]->_children;
			$matched_childname = $matchedchild[$t]->name();
			$currNode = $matchedchild[$t];

			if ($matched_childname == "ratedshipment")
			{
				$service = $currNode->getElementByPath("service");
				$servicecode = $service->_children[0]->data();

				if (in_array($servicecode, $myservicecodes))
				{
					$ratedshipmentwarning = $currNode->getElementByPath("ratedshipmentwarning");
					$ship_postage[$count]['Ratedshipmentwarning'] = $ratedshipmentwarning->data();
					$ScheduledDeliveryTime = $currNode->getElementByPath("ScheduledDeliveryTime");
					$ship_postage[$count]['ScheduledDeliveryTime'] = $ScheduledDeliveryTime->data();
					$GuaranteedDaysToDelivery = $currNode->getElementByPath("GuaranteedDaysToDelivery");
					$ship_postage[$count]['GuaranteedDaysToDelivery'] = $GuaranteedDaysToDelivery->data();

					$transportationcharges = $currNode->getElementByPath("transportationcharges");
					$childeArr = $transportationcharges->_children;

					for ($i = 0; $i < count($childeArr); $i++)
					{
						if ($childeArr[$i]->name() == "currencycode")
						{
							$ship_postage[$count]['Currency'] = $childeArr[$i]->data();
						}

						if ($childeArr[$i]->name() == "monetaryvalue")
						{
							$ship_postage[$count]['Rate'] = $childeArr[$i]->data();
						}
					}
					switch ($servicecode)
					{
						case "01":
							$ship_postage[$count]["ServiceName"] = "UPS Next Day Air";
							break;
						case "02":
							$ship_postage[$count]["ServiceName"] = "UPS 2nd Day Air";
							break;
						case "03":
							$ship_postage[$count]["ServiceName"] = "UPS Ground";
							break;
						case "07":
							$ship_postage[$count]["ServiceName"] = "UPS Worldwide Express SM";
							break;
						case "08":
							$ship_postage[$count]["ServiceName"] = "UPS Worldwide Expedited SM";
							break;
						case "11":
							$ship_postage[$count]["ServiceName"] = "UPS Standard";
							break;
						case "12":
							$ship_postage[$count]["ServiceName"] = "UPS 3 Day Select";
							break;
						case "13":
							$ship_postage[$count]["ServiceName"] = "UPS Next Day Air Saver";
							break;
						case "14":
							$ship_postage[$count]["ServiceName"] = "UPS Next Day Air Early A.M.";
							break;
						case "54":
							$ship_postage[$count]["ServiceName"] = "UPS Worldwide Express Plus SM";
							break;
						case "59":
							$ship_postage[$count]["ServiceName"] = "UPS 2nd Day Air A.M.";
							break;
						case "64":
							$ship_postage[$count]["ServiceName"] = "n/a";
							break;
						case "65":
							$ship_postage[$count]["ServiceName"] = "UPS Saver";
							break;
					}

					$count++;
				}
			}
		}

		if (count($ship_postage) <= 0)
		{
			return $shippingrate;
		}

		// UPS returns Charges in USD ONLY.
		// So we have to convert from USD to Vendor Currency if necessary
		if (CURRENCY_CODE != "USD")
		{
			$convert = true;
		}
		else
		{
			$convert = false;
		}

		for ($i = 0; $i < count($ship_postage); $i++)
		{
			$ratevalue = $ship_postage[$i]['Rate'];
			$ServiceName = $ship_postage[$i]['ServiceName'];
			$fsc = $ship_postage[$i]['ServiceName'] . "_FSC";
			$fsc = str_replace(" ", "_", str_replace(".", "", str_replace("/", "", $fsc)));
			$fsc = constant($fsc);
			//if( $fsc == 0 )
			//{
			//	$fsc = 1;
			//	}

			if ($convert)
			{
				$tmp = $currency->convert($ratevalue, "USD", CURRENCY_CODE);

				if (!empty($tmp))
				{
					$charge = $tmp;

					if ($fsc == 0)
					{
						$charge_fee = 0;
					}
					else
					{
						$charge_fee = ($charge * $fsc) / 100;
					}

					$charge += (UPS_HANDLING_FEE + $charge_fee);
					$ratevalue = $producthelper->getProductFormattedPrice($charge, false);
				}
				else
				{
					$charge = $ratevalue;
					$charge_fee = ($charge * $fsc) / 100;

					if ($fsc == 0)
					{
						$charge_fee = 0;
					}
					else
					{
						$charge_fee = ($charge * $fsc) / 100;
					}

					$charge += (UPS_HANDLING_FEE + $charge_fee);
					$ratevalue = $ratevalue . " USD";
				}
			}
			else
			{
				$charge = $ratevalue;

				if ($fsc == 0)
				{
					$charge_fee = 0;
				}
				else
				{
					$charge_fee = ($charge * $fsc) / 100;
				}

				$charge += (UPS_HANDLING_FEE + $charge_fee);
				$ratevalue = $producthelper->getProductFormattedPrice($charge, false);
			}

			$shipping_rate_id = $shippinghelper->encryptShipping(__CLASS__ . "|" . $shipping->name . "|" . $ServiceName . "|" . number_format($charge, 2, '.', '') . "|" . $ServiceName . "|single|0");
			$shippingrate[$rate]->text = $ServiceName . ' (' . $ratevalue . ') '; //." ".JText::_('COM_REDSHOP_DELIVERY')." ".$value['GuaranteedDaysToDelivery'];
			$shippingrate[$rate]->value = $shipping_rate_id;
			$shippingrate[$rate]->rate = $charge;
			$shippingrate[$rate]->vat = 0;
			$rate++;
			// DELIVERY QUOTE
			/*if (Show_Delivery_Days_Quote == 1) {
				if( !empty($value['GuaranteedDaysToDelivery'])) {
					$GuaranteedDaysToDelivery = "&nbsp;&nbsp;-&nbsp;&nbsp;".$value['GuaranteedDaysToDelivery']." ".JText::_('COM_REDSHOP_UPS_SHIPPING_GUARANTEED_DAYS');
				}
			}

			if (Show_Delivery_ETA_Quote == 1) {
				if( !empty($value['ScheduledDeliveryTime'])) {
					$ScheduledDeliveryTime = "&nbsp;(ETA:&nbsp;".$value['ScheduledDeliveryTime'].")";
				}
			}

			if (Show_Delivery_Warning == 1 && !empty($value['RatedShipmentWarning'])) {
				$RatedShipmentWarning = "</label><br/>\n&nbsp;&nbsp;&nbsp;*&nbsp;<em>".$value['RatedShipmentWarning']."</em>\n";
			}*/

		}

		return $shippingrate;
	}

	function show_configuration()
	{
		?>

	<?php

	}

}

?>