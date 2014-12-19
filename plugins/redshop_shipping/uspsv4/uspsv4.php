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

JLoader::import('redshop.library');
JLoader::load('RedshopHelperProduct');
JLoader::load('RedshopHelperAdminConfiguration');
JLoader::load('RedshopHelperAdminShipping');

class plgredshop_shippinguspsv4 extends JPlugin
{
	var $payment_code = "uspsv4";
	var $classname = "uspsv4";

	function onShowconfig($ps)
	{
		if ($ps->element == $this->classname)
		{
			?>
			<table>
			<tr>
				<td><strong><?php echo _USPS_USERNAME ?></strong></td>
				<td>
					<input type="text" name="USPS_USERNAME" class="inputbox" value="<?php echo USPS_USERNAME ?>"/>
				</td>
				<td>
					<?php echo JTEXT::_(_USPS_USERNAME_TOOLTIP) ?>
				</td>
			</tr>
			<tr>
				<td><strong><?php echo _USPS_PASSWORD ?></strong>
				</td>
				<td>
					<input type="text" name="USPS_PASSWORD" class="inputbox" value="<?php echo USPS_PASSWORD ?>"/>
				</td>
				<td>
					<?php echo JTEXT::_(_USPS_PASSWORD_TOOLTIP) ?>
				</td>
			</tr>
			<tr>
				<td><strong><?php echo _USPS_SERVER ?></strong>
				</td>
				<td>
					<input type="text" name="USPS_SERVER" class="inputbox" value="<?php echo USPS_SERVER ?>"/>
				</td>
				<td>
					<?php echo JTEXT::_(_USPS_SERVER_TOOLTIP) ?>
				</td>
			</tr>
			<tr>
				<td><strong><?php echo _USPS_PATH ?></strong>
				</td>
				<td>
					<input type="text" name="USPS_PATH" class="inputbox" value="<?php echo USPS_PATH ?>"/>
				</td>
				<td>
					<?php echo JTEXT::_(_USPS_PATH_TOOLTIP) ?>
				</td>
			</tr>
			</tr>
			<tr class="row1">
				<td><strong><?php echo JText::_('COM_REDSHOP_SHIP_FROM_ZIPCODE');?></strong></td>
				<td><input class="inputbox" type="text" name="OVERRIDE_SOURCE_ZIP"
				           value="<?php echo OVERRIDE_SOURCE_ZIP ?>"/></td>
				<td><?php echo JHTML::tooltip(JText::_('COM_REDSHOP_SHIP_FROM_ZIPCODE_TOOLTIP'), JText::_('COM_REDSHOP_SHIP_FROM_ZIPCODE'), 'tooltip.png', '', '', false);?></td>
			</tr>
			<tr>
				<td><strong><?php echo _USPS_PROXYSERVER ?></strong>
				</td>
				<td>
					<input type="text" name="USPS_PROXYSERVER" class="inputbox" value="<?php echo USPS_PROXYSERVER ?>"/>
				</td>
				<td>
					<?php echo JTEXT::_(_USPS_PROXYSERVER_TOOLTIP) ?>
				</td>
			</tr>
			<tr>
				<TD colspan="3">
					<HR/>
				</td>
			</tr>
			<tr>
				<td><strong><?php echo _USPS_PADDING ?></strong></td>
				<td><input class="inputbox" TYPE="text" name="USPS_PADDING" value="<?php echo USPS_PADDING ?>"/></td>
				<td><?php echo JTEXT::_(_USPS_PADDING_TOOLTIP) ?></td>
			</tr>
			<tr>
				<td><strong><?php echo _USPS_HANDLING_FEE ?></strong></td>
				<td><input class="inputbox" TYPE="text" name="USPS_HANDLINGFEE" value="<?php echo USPS_HANDLINGFEE ?>"/>
				</td>
				<td><?php echo JTEXT::_(_USPS_HANDLING_FEE_TOOLTIP) ?></td>
			</tr>
			<tr>
				<td><strong><?php echo _USPS_INTLHANDLINGFEE ?></strong>
				</td>
				<td>
					<input type="text" name="USPS_INTLHANDLINGFEE" class="inputbox"
					       value="<?php echo USPS_INTLHANDLINGFEE ?>"/>
				</td>
				<td>
					<?php echo JTEXT::_(_USPS_INTLHANDLINGFEE_TOOLTIP) ?>
				</td>
			</tr>
			<tr>
				<td><strong><?php echo _USPS_MACHINABLE ?></strong></td>
				<td>
					<label>
						<input name="USPS_MACHINABLE"
						       type="radio" <?php if (USPS_MACHINABLE == 1) echo "checked=\"checked\""; ?> value="1"/>
						<?php echo JText::_('JYES'); ?></label>
					<label>
						<input name="USPS_MACHINABLE"
						       type="radio" <?php if (USPS_MACHINABLE == 0) echo "checked=\"checked\""; ?> value="0"/>
						<?php echo JText::_('JNO'); ?></label>
				</td>
				<td><?php echo JTEXT::_(_USPS_MACHINABLE_TOOLTIP) ?></td>
			</tr>
			<tr>
				<td><strong><?php echo _USPS_QUOTE ?></strong></td>
				<td>
					<label>
						<input name="USPS_SHOW_DELIVERY_QUOTE"
						       type="radio" <?php if (USPS_SHOW_DELIVERY_QUOTE == 1) echo "checked=\"checked\""; ?>
						       value="1"/>
						<?php echo JText::_('JYES'); ?></label>
					<label>
						<input name="USPS_SHOW_DELIVERY_QUOTE"
						       type="radio" <?php if (USPS_SHOW_DELIVERY_QUOTE == 0) echo "checked=\"checked\""; ?>
						       value="0"/>
						<?php echo JText::_('JNO'); ?></label>
				</td>
				<td><?php echo JTEXT::_(_USPS_QUOTE_TOOLTIP) ?></td>
			</tr>
			<tr>
				<td><strong><?php echo _USPS_REPORTERRORS ?></strong></td>
				<td>
					<label>
						<input name="USPS_REPORTERRORS"
						       type="radio" <?php if (USPS_REPORTERRORS == 1) echo "checked=\"checked\""; ?> value="1"/>
						<?php echo JText::_('JYES'); ?></label>
					<label>
						<input name="USPS_REPORTERRORS"
						       type="radio" <?php if (USPS_REPORTERRORS == 0) echo "checked=\"checked\""; ?> value="0"/>
						<?php echo JText::_('JNO'); ?></label>
				</td>
				<td><?php echo JTEXT::_(_USPS_REPORTERRORS_TOOLTIP) ?></td>
			</tr>
			<tr>
				<td><strong><?php echo _USPS_STANDARDSHIPPING ?></strong></td>
				<td>
					<label>
						<input name="USPS_STANDARDSHIPPING"
						       type="radio" <?php if (USPS_STANDARDSHIPPING == 1) echo "checked=\"checked\""; ?>
						       value="1"/>
						<?php echo JText::_('JYES'); ?></label>
					<label>
						<input name="USPS_STANDARDSHIPPING"
						       type="radio" <?php if (USPS_STANDARDSHIPPING == 0) echo "checked=\"checked\""; ?>
						       value="0"/>
						<?php echo JText::_('JNO'); ?></label>
				</td>
				<td><?php echo JTEXT::_(_USPS_STANDARDSHIPPING_TOOLTIP) ?></td>
			</tr>

			<tr>
				<td><strong><?php echo _USPS_PREFIX ?></strong>
				</td>
				<td>
					<input type="text" name="USPS_PREFIX" class="inputbox" value="<?php echo USPS_PREFIX ?>"/>
				</td>
				<td>
					<?php echo JTEXT::_(_USPS_PREFIX_TOOLTIP) ?>
				</td>
			</tr>
			<tr>
				<td colspan="3">
					<hr>
					<strong><?php echo _USPS_SHIP; ?></strong>
					<hr>
				</td>
			</tr>
			<tr>
				<td colspan="3">
					<table class="adminform" width="100%">
						<tr>
							<th><strong>Shipping Method</strong></th>
							<th><strong>Available</strong></th>
						</tr>
						<?php
						$i = 0;
						while (defined("USPS_SHIP" . $i)):
							$dom_ship_option_avail = constant("USPS_SHIP" . $i);
							$dom_ship_option_text = constant("USPS_SHIP" . $i . "_TEXT");
							?>
							<tr class="row<?php echo($i & 1); ?>">
								<td><strong><?php echo $dom_ship_option_text; ?></strong></td>
								<td>
									<label>
										<input name="USPS_SHIP<?php echo $i; ?>"
										       type="radio" <?php if ($dom_ship_option_avail == 1) echo "checked=\"checked\""; ?>
										       value="1"/>
										<?php echo JText::_('JYES'); ?></label>
									<label>
										<input name="USPS_SHIP<?php echo $i; ?>"
										       type="radio" <?php if ($dom_ship_option_avail == 0) echo "checked=\"checked\""; ?>
										       value="0"/>
										<?php echo JText::_('JNO'); ?></label>
								</td>
							</tr>
							<?php
							$i++;
						endwhile; ?>
					</table>
				</td>
			</tr>
			<tr>
				<td colspan="3">
					<hr>
					<strong><?php echo _USPS_INTL; ?></strong>
					<hr>
				</td>
			</tr>

			<?php
			//Display international shipping options
			?>
			<tr>
				<td colspan="3">
					<table class="adminform" width="100%">
						<tr>
							<th><strong>Shipping Method</strong></th>
							<th><strong>Available</strong></th>
						</tr>
						<?php
						$i = 0;
						while (defined("USPS_INTL" . $i)):
							$ship_option_avail = constant("USPS_INTL" . $i);
							$ship_option_text = constant("USPS_INTL" . $i . "_TEXT");
							?>
							<tr class="row<?php echo($i & 1); ?>">
								<td><strong><?php echo $ship_option_text; ?></strong></td>
								<td>
									<label>
										<input name="USPS_INTL<?php echo $i; ?>"
										       type="radio" <?php if ($ship_option_avail == 1) echo "checked=\"checked\""; ?>
										       value="1"/>
										<?php echo JText::_('JYES'); ?></label>
									<label>
										<input name="USPS_INTL<?php echo $i; ?>"
										       type="radio" <?php if ($ship_option_avail == 0) echo "checked=\"checked\""; ?>
										       value="0"/>
										<?php echo JText::_('JNO'); ?></label>
								</td>
							</tr>
							<?php
							$i++;
						endwhile; ?>
					</table>
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
				"USPS_USERNAME"                 => $d['USPS_USERNAME'],
				"USPS_PASSWORD"                 => $d['USPS_PASSWORD'],
				"USPS_SERVER"                   => $d['USPS_SERVER'],
				"USPS_PATH"                     => $d['USPS_PATH'],
				"USPS_PROXYSERVER"              => $d['USPS_PROXYSERVER'],
				"USPS_TAX_CLASS"                => $d['USPS_TAX_CLASS'],
				"USPS_HANDLINGFEE"              => $d['USPS_HANDLINGFEE'],
				"USPS_PADDING"                  => $d['USPS_PADDING'],
				"OVERRIDE_SOURCE_ZIP"           => $d['OVERRIDE_SOURCE_ZIP'],
				"USPS_INTLHANDLINGFEE"          => $d['USPS_INTLHANDLINGFEE'],
				"USPS_MACHINABLE"               => $d['USPS_MACHINABLE'],
				"USPS_SHOW_DELIVERY_QUOTE"      => $d['USPS_SHOW_DELIVERY_QUOTE'],
				"USPS_REPORTERRORS"             => $d['USPS_REPORTERRORS'],
				"USPS_STANDARDSHIPPING"         => $d['USPS_STANDARDSHIPPING'],
				"USPS_PREFIX"                   => $d['USPS_PREFIX'],
				"USPS_DOMESTIC_SHIPPING_METHOD" => implode(',', $d['USPS_DOMESTIC_SHIPPING_METHOD']),
				"USPS_SHIP0"                    => $d['USPS_SHIP0'],
				"USPS_SHIP1"                    => $d['USPS_SHIP1'],
				"USPS_SHIP2"                    => $d['USPS_SHIP2'],
				"USPS_SHIP3"                    => $d['USPS_SHIP3'],
				"USPS_SHIP4"                    => $d['USPS_SHIP4'],
				"USPS_SHIP5"                    => $d['USPS_SHIP5'],
				"USPS_SHIP6"                    => $d['USPS_SHIP6'],
				"USPS_SHIP7"                    => $d['USPS_SHIP7'],
				"USPS_SHIP8"                    => $d['USPS_SHIP8'],
				"USPS_SHIP9"                    => $d['USPS_SHIP9'],
				"USPS_SHIP10"                   => $d['USPS_SHIP10'],
				"USPS_SHIP11"                   => $d['USPS_SHIP11'],
				"USPS_SHIP12"                   => $d['USPS_SHIP12'],
				"USPS_SHIP13"                   => $d['USPS_SHIP13'],
				"USPS_SHIP14"                   => $d['USPS_SHIP14'],
				"USPS_SHIP15"                   => $d['USPS_SHIP15'],
				"USPS_SHIP16"                   => $d['USPS_SHIP16'],
				"USPS_SHIP17"                   => $d['USPS_SHIP17'],
				"USPS_SHIP18"                   => $d['USPS_SHIP18'],
				"USPS_SHIP19"                   => $d['USPS_SHIP19'],
				"USPS_SHIP20"                   => $d['USPS_SHIP20'],
				"USPS_SHIP21"                   => $d['USPS_SHIP21'],
				"USPS_SHIP22"                   => $d['USPS_SHIP22'],
				"USPS_SHIP23"                   => $d['USPS_SHIP23'],
				"USPS_SHIP24"                   => $d['USPS_SHIP24'],
				"USPS_SHIP25"                   => $d['USPS_SHIP25'],
				"USPS_SHIP26"                   => $d['USPS_SHIP26'],
				"USPS_SHIP27"                   => $d['USPS_SHIP27'],
				"USPS_SHIP28"                   => $d['USPS_SHIP28'],
				"USPS_SHIP29"                   => $d['USPS_SHIP29'],
				"USPS_SHIP0_COMMIT"             => "Overnight, most locations",
				"USPS_SHIP1_COMMIT"             => "Overnight, most locations",
				"USPS_SHIP2_COMMIT"             => "Overnight, most locations",
				"USPS_SHIP3_COMMIT"             => "Overnight, most locations",
				"USPS_SHIP4_COMMIT"             => "Overnight, most locations",
				"USPS_SHIP5_COMMIT"             => "Overnight, most locations",
				"USPS_SHIP6_COMMIT"             => "Overnight, most locations",
				"USPS_SHIP7_COMMIT"             => "Overnight, most locations",
				"USPS_SHIP8_COMMIT"             => "Overnight, most locations",
				"USPS_SHIP9_COMMIT"             => "Overnight, most locations",
				"USPS_SHIP10_COMMIT"            => "Overnight, most locations",
				"USPS_SHIP11_COMMIT"            => "Overnight, most locations",
				"USPS_SHIP12_COMMIT"            => "Delivery within 2 days in most cases",
				"USPS_SHIP13_COMMIT"            => "Delivery within 2 days in most cases",
				"USPS_SHIP14_COMMIT"            => "Delivery within 2 days in most cases",
				"USPS_SHIP15_COMMIT"            => "Delivery within 2 days in most cases",
				"USPS_SHIP16_COMMIT"            => "Delivery within 2 days in most cases",
				"USPS_SHIP17_COMMIT"            => "Delivery within 2 days in most cases",
				"USPS_SHIP18_COMMIT"            => "Delivery within 2 days in most cases",
				"USPS_SHIP19_COMMIT"            => "Delivery within 2 days in most cases",
				"USPS_SHIP20_COMMIT"            => "Delivery within 2 days in most cases",
				"USPS_SHIP21_COMMIT"            => "Delivery within 2 days in most cases",
				"USPS_SHIP22_COMMIT"            => "Delivery within 2 days in most cases",
				"USPS_SHIP23_COMMIT"            => "Delivery within 2 days in most cases",
				"USPS_SHIP24_COMMIT"            => "Delivery within 2 days in most cases",
				"USPS_SHIP25_COMMIT"            => "Delivery within 2 days in most cases",
				"USPS_SHIP26_COMMIT"            => "Delivery within 2 days in most cases",
				"USPS_SHIP27_COMMIT"            => "Delivery in 2 to 8 days",
				"USPS_SHIP28_COMMIT"            => "Delivery in 2 to 8 days",
				"USPS_SHIP29_COMMIT"            => "Delivery in 2 to 8 days",
				"USPS_INTL0"                    => $d['USPS_INTL0'],
				"USPS_INTL1"                    => $d['USPS_INTL1'],
				"USPS_INTL2"                    => $d['USPS_INTL2'],
				"USPS_INTL3"                    => $d['USPS_INTL3'],
				"USPS_INTL4"                    => $d['USPS_INTL4'],
				"USPS_INTL5"                    => $d['USPS_INTL5'],
				"USPS_INTL6"                    => $d['USPS_INTL6'],
				"USPS_INTL7"                    => $d['USPS_INTL7'],
				"USPS_INTL8"                    => $d['USPS_INTL8'],
				"USPS_INTL9"                    => $d['USPS_INTL9'],
				"USPS_INTL10"                   => $d['USPS_INTL10'],
				"USPS_INTL11"                   => $d['USPS_INTL11'],
				"USPS_INTL12"                   => $d['USPS_INTL12'],
				"USPS_INTL13"                   => $d['USPS_INTL13'],
				"USPS_INTL14"                   => $d['USPS_INTL14'],
				"USPS_INTL15"                   => $d['USPS_INTL15'],
				"USPS_INTL16"                   => $d['USPS_INTL16'],
				"USPS_INTL17"                   => $d['USPS_INTL17'],
				"USPS_INTL18"                   => $d['USPS_INTL18'],
				"USPS_INTL19"                   => $d['USPS_INTL19'],
				"USPS_INTL20"                   => $d['USPS_INTL20'],
				"USPS_INTL21"                   => $d['USPS_INTL21'],
				"USPS_SHIP0_TEXT"               => "Priority Mail Express Hold For Pickup",
				"USPS_SHIP1_TEXT"               => "Priority Mail Express",
				"USPS_SHIP2_TEXT"               => "Priority Mail Express Sunday/Holiday Delivery",
				"USPS_SHIP3_TEXT"               => "Priority Mail Express Flat Rate Boxes",
				"USPS_SHIP4_TEXT"               => "Priority Mail Express Flat Rate Boxes Hold For Pickup",
				"USPS_SHIP5_TEXT"               => "Priority Mail Express Sunday/Holiday Delivery Flat Rate Boxes",
				"USPS_SHIP6_TEXT"               => "Priority Mail Express Flat Rate Envelope",
				"USPS_SHIP7_TEXT"               => "Priority Mail Express Flat Rate Envelope Hold For Pickup",
				"USPS_SHIP8_TEXT"               => "Priority Mail Express Sunday/Holiday Delivery Flat Rate Envelope",
				"USPS_SHIP9_TEXT"               => "Priority Mail Express Legal Flat Rate Envelope",
				"USPS_SHIP10_TEXT"              => "Priority Mail Express Legal Flat Rate Envelope Hold For Pickup",
				"USPS_SHIP11_TEXT"              => "Priority Mail Express Sunday/Holiday Delivery Legal Flat Rate Envelope",
				"USPS_SHIP12_TEXT"              => "Priority Mail",
				"USPS_SHIP13_TEXT"              => "Priority Mail Large Flat Rate Box",
				"USPS_SHIP14_TEXT"              => "Priority Mail Medium Flat Rate Box",
				"USPS_SHIP15_TEXT"              => "Priority Mail Small Flat Rate Box",
				"USPS_SHIP16_TEXT"              => "Priority Mail Flat Rate Envelope",
				"USPS_SHIP17_TEXT"              => "Priority Mail Legal Flat Rate Envelope",
				"USPS_SHIP18_TEXT"              => "Priority Mail Padded Flat Rate Envelope",
				"USPS_SHIP19_TEXT"              => "Priority Mail Gift Card Flat Rate Envelope",
				"USPS_SHIP20_TEXT"              => "Priority Mail Small Flat Rate Envelope",
				"USPS_SHIP21_TEXT"              => "Priority Mail Window Flat Rate Envelope",
				"USPS_SHIP22_TEXT"              => "First-Class Mail Parcel",
				"USPS_SHIP23_TEXT"              => "First-Class Mail Letter",
				"USPS_SHIP24_TEXT"              => "First-Class Mail Postcards",
				"USPS_SHIP25_TEXT"              => "First-Class Mail Large Postcards",
				"USPS_SHIP26_TEXT"              => "First-Class Mail Large Envelope",
				"USPS_SHIP27_TEXT"              => "Standard Post",
				"USPS_SHIP28_TEXT"              => "Media Mail",
				"USPS_SHIP29_TEXT"              => "Library Mail",
				"USPS_INTL0_TEXT"               => "Global Express Guaranteed (GXG)**",
				"USPS_INTL1_TEXT"               => "Global Express Guaranteed Non-Document Rectangular",
				"USPS_INTL2_TEXT"               => "Global Express Guaranteed Non-Document Non-Rectangular",
				"USPS_INTL3_TEXT"               => "USPS GXG Envelopes**",
				"USPS_INTL4_TEXT"               => "Priority Mail Express International",
				"USPS_INTL5_TEXT"               => "Priority Mail Express International Flat Rate Boxes",
				"USPS_INTL6_TEXT"               => "Priority Mail Express International Flat Rate Envelope",
				"USPS_INTL7_TEXT"               => "Priority Mail Express International Legal Flat Rate Envelope",
				"USPS_INTL8_TEXT"               => "Priority Mail International",
				"USPS_INTL9_TEXT"               => "Priority Mail International Large Flat Rate Box",
				"USPS_INTL10_TEXT"              => "Priority Mail International Medium Flat Rate Box",
				"USPS_INTL11_TEXT"              => "Priority Mail International Small Flat Rate Box**",
				"USPS_INTL12_TEXT"              => "Priority Mail International DVD Flat Rate priced box**",
				"USPS_INTL13_TEXT"              => "Priority Mail International Large Video Flat Rate priced box**",
				"USPS_INTL14_TEXT"              => "Priority Mail International Flat Rate Envelope**",
				"USPS_INTL15_TEXT"              => "Priority Mail International Legal Flat Rate Envelope**",
				"USPS_INTL16_TEXT"              => "Priority Mail International Padded Flat Rate Envelope**",
				"USPS_INTL17_TEXT"              => "Priority Mail International Gift Card Flat Rate Envelope**",
				"USPS_INTL18_TEXT"              => "Priority Mail International Small Flat Rate Envelope**",
				"USPS_INTL19_TEXT"              => "Priority Mail International Window Flat Rate Envelope**",
				"USPS_INTL20_TEXT"              => "First-Class Package International Service**",
				"USPS_INTL21_TEXT"              => "First-Class Mail International Large Envelope**"

			);

			$config = "<?php\n";
			$config .= "defined('_JEXEC') or die;\n";

			foreach ($my_config_array as $key => $value)
			{
				$config .= "define('$key', '$value');\n";
			}

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
		$shipping = $shippinghelper->getShippingMethodByClass($this->classname);
		$db = JFactory::getDbo();
		//require_once  JPATH_SITE. '/includes/domit/xml_domit_lite_include.php' ;

		$itemparams = new JRegistry($shipping->params);

		$shippingrate = array();
		$rate = 0;

		$shippingcfg = JPATH_ROOT . '/plugins/' . $shipping->folder . '/' . $shipping->element . '/' . $shipping->element . '.cfg.php';
		include_once $shippingcfg;

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

		$girth = 2 * ceil($shipping_width) + 2 * ceil($shipping_height);
		$size = (ceil($shipping_length) + $girth) / $unitRatioVolume;

		if ($size <= (84 * $unitRatioVolume))
		{
			$sizetype = 'REGULAR';
		}
		else if ($size <= (108 * $unitRatioVolume))
		{
			$sizetype = 'LARGE';
		}
		else if ($size <= (130 * $unitRatioVolume))
		{
			$sizetype = 'OVERSIZE';
		}
		else
		{
			$sizetype = 'GI-HUGE-IC';
		}

		if ($order_weight > 0)
		{
			$usps_username = USPS_USERNAME;
			$usps_password = USPS_PASSWORD;
			$usps_proxyserver = USPS_PROXYSERVER; //"http://proxy.shr.secureserver.net:3128";
			$usps_packageid = 0;
			//this does not appear to be used in module
			//$usps_intllbrate = USPS_INTLLBRATE; //USPS International Per Pound Rate
			$usps_intlhandlingfee = USPS_INTLHANDLINGFEE; //USPS International handling fee
			if (USPS_REPORTERRORS == '1') $usps_reporterrors = 1;
			else $usps_reporterrors = 0;
			//flag used to determine if standard shipping should be displayed if encounter error or no options available
			if (USPS_STANDARDSHIPPING == '1') $usps_standardshipping = 1;
			else $usps_standardshipping = 0;
			$usps_prefix = USPS_PREFIX;

			//Pad the shipping weight to allow weight for shipping materials
			$usps_padding = USPS_PADDING * 0.01;
			$order_weight = ($order_weight * $usps_padding) + $order_weight;

			//USPS Machinable for Parcel Post
			$usps_machinable = USPS_MACHINABLE;

			if ($usps_machinable == '1') $usps_machinable = 'TRUE';
			else $usps_machinable = 'FALSE';

			//The zip that you are shipping from
			$source_zip = OVERRIDE_SOURCE_ZIP; //substr($dbv->f("vendor_zip"),0,5);
			$shpService = 'All';

			if (isset($shippinginfo->country_code))
			{
				$shippinginfo->country_2_code = $redconfig->getCountryCode2($shippinginfo->country_code);
			}

			$sql = "SELECT * FROM #__redshop_country WHERE country_3_code = '$shippinginfo->country_code'";
			$db->setQuery($sql);
			$country = $db->loadObject();
			$dest_country_name = $country->country_name;
			$dest_zip = substr($shippinginfo->zipcode, 0, 5);

			$shipping_pounds = floor($order_weight); //send integer rounded down
			$shipping_ounces = ceil(16 * ($order_weight - floor($order_weight))); //send integer rounded up

			if ($order_weight > 70.00)
			{
				echo  "We are unable to ship USPSv4 as the package weight exceeds the 70 pound limit, please select another shipping method.";

				return $shippingrate;
			}
			else
			{
				$domestic = 0; //default to international
				if (($shippinginfo->country_2_code == "US") || ($shippinginfo->country_2_code == "PR") || ($shippinginfo->country_2_code == "VI"))
				{ //domestic if US, PR or VI
					$domestic = 1;
				}
				//Build XML string based on service request
				if ($domestic)
				{
					//the xml that will be posted to usps for domestic rates
					$xmlPost = 'API=RateV4&XML=<RateV4Request USERID="' . $usps_username . '" PASSWORD="' . $usps_password . '">';

					$xmlPost .= '<Package ID="' . $usps_packageid . '">';
					$xmlPost .= "<Service>ALL</Service>";
					$xmlPost .= "<ZipOrigination>" . $source_zip . "</ZipOrigination>";
					$xmlPost .= "<ZipDestination>" . $dest_zip . "</ZipDestination>";
					$xmlPost .= "<Pounds>" . $shipping_pounds . "</Pounds>";
					$xmlPost .= "<Ounces>" . $shipping_ounces . "</Ounces>";
					$xmlPost .= "<Container></Container>";
					$xmlPost .= "<Size>" . $sizetype . "</Size>";
					$xmlPost .= "<Width>" . $shipping_width . "</Width>";
					$xmlPost .= "<Length>" . $shipping_length . "</Length>";
					$xmlPost .= "<Height>" . $shipping_height . "</Height>";
					$xmlPost .= "<Girth>" . ceil($girth) . "</Girth>";
					$xmlPost .= "<Machinable>" . $usps_machinable . "</Machinable>";
					$xmlPost .= "</Package>";

					$xmlPost .= "</RateV4Request>";

					$i = 0;

					while (defined("USPS_SHIP" . $i))
					{
						$ship_option_avail = constant("USPS_SHIP" . $i);
						$ship_option_text = constant("USPS_SHIP" . $i . "_TEXT");

						if ($ship_option_avail == '1')
						{
							if ($ship_option_text !== "")
							{
								$usps_ship_active[] = $ship_option_text;
							}
						}

						$i++;
					}
				}
				else
				{
					//the xml that will be posted to usps for international rates
					$xmlPost = 'API=IntlRateV2&XML=<IntlRateV2Request USERID="' . $usps_username . '" PASSWORD="' . $usps_password . '">';
					$xmlPost .= '<Package ID="1ST">';
					$xmlPost .= '<Pounds>' . $shipping_pounds . '</Pounds>';
					$xmlPost .= '<Ounces>' . $shipping_ounces . '</Ounces>';
					$xmlPost .= '<Machinable>True</Machinable>';
					$xmlPost .= '<MailType>Package</MailType>';
					$xmlPost .= '<ValueOfContents>0.0</ValueOfContents>';
					$xmlPost .= '<Country>' . $dest_country_name . '</Country>';
					$xmlPost .= '<Container>RECTANGULAR</Container>';
					$xmlPost .= "<Size>" . $sizetype . "</Size>";
					$xmlPost .= "<Width>" . $shipping_width . "</Width>";
					$xmlPost .= "<Length>" . $shipping_length . "</Length>";
					$xmlPost .= "<Height>" . $shipping_height . "</Height>";
					$xmlPost .= "<Girth>" . ceil($girth) . "</Girth>";
					$xmlPost .= "<CommercialFlag>N</CommercialFlag>";
					$xmlPost .= '</Package></IntlRateV2Request>';

					$i = 0;

					while (defined("USPS_INTL" . $i))
					{
						$ship_option_avail = constant("USPS_INTL" . $i);
						$ship_option_text = constant("USPS_INTL" . $i . "_TEXT");

						if ($ship_option_avail == '1')
						{
							if ($ship_option_text !== "")
							{
								$usps_intl_active[] = $ship_option_text;
							}
						}

						$i++;
					}
				}

				$html = "";

				if (function_exists("curl_init"))
				{
					$CR = curl_init();
					curl_setopt($CR, CURLOPT_URL, "http://" . USPS_SERVER . USPS_PATH);
					curl_setopt($CR, CURLOPT_POST, 1);
					curl_setopt($CR, CURLOPT_FAILONERROR, true);
					curl_setopt($CR, CURLOPT_POSTFIELDS, $xmlPost);
					curl_setopt($CR, CURLOPT_RETURNTRANSFER, 1);
					curl_setopt($CR, CURLOPT_SSL_VERIFYPEER, false);
					curl_setopt($CR, CURLOPT_CONNECTTIMEOUT, 20);
					curl_setopt($CR, CURLOPT_TIMEOUT, 30);

					if (!empty($usps_proxyserver))
					{
						curl_setopt($CR, CURLOPT_HTTPPROXYTUNNEL, true);
						curl_setopt($CR, CURLOPT_PROXYTYPE, CURLPROXY_HTTP);
						curl_setopt($CR, CURLOPT_PROXY, $usps_proxyserver);
					}

					$xmlResult = curl_exec($CR);
					$error = curl_error($CR);
					$xmlDoc = JFactory::getXML($xmlResult, false);

					if (!empty($error))
					{
						$html = _USPS_RESPONSE_ERROR;
						$error = true;
					}
					else
					{ //Check for error from response from USPS
						if (strstr($xmlResult, "Error"))
						{
							$error = true;
							$html = "<span class=\"message\">" . _USPS_RESPONSE_ERROR . "</span><br/>";
							$html .= "Error Description: " . $xmlResult . "<br/>";
						}
					}
					curl_close($CR);
				}
				else
				{
					//5/17/08 - changes to call fsockopen correctly and parse the response
					$fp = fsockopen(USPS_SERVER, 80, $errno, $errstr, $timeout = 60);

					if (!$fp)
					{
						$error = true;
						$html = _USPS_RESPONSE_ERROR . ": $errstr ($errno)";
					}
					else
					{
						//send the server request
						fputs($fp, "POST " . USPS_PATH . " HTTP/1.1\r\n");
						fputs($fp, "Host: " . USPS_SERVER . "\r\n");
						fputs($fp, "Content-type: application/x-www-form-urlencoded\r\n");
						fputs($fp, "Content-length: " . strlen($xmlPost) . "\r\n");
						fputs($fp, "Connection: close\r\n\r\n");
						fputs($fp, $xmlPost . "\r\n\r\n");

						$xmlResult = '';
						$header = '';
						//get the response
						$lineNum = 0;
						do // loop until the end of the header
						{
							$header .= fgets($fp, 128);
						} while (strpos($header, "\r\n\r\n") === false);

						while (!feof($fp))
						{
							$xmlResult .= fgets($fp, 128);
						}
						fclose($fp);

						/* XML Parsing */

						if ($xmlDoc = JFactory::getXML($xmlResult, false))
							$error = false;
						else
						{
							$error = true;
							$html = "Error parsing the XML Response from USPS.com";
						}
					}
				}

				if ($itemparams->get("uspsv4_debug"))
				{
					echo "XML Post: <br>";
					echo "<textarea cols='80' rows='5'>http://" . USPS_SERVER . USPS_PATH . "?" . $xmlPost . "</textarea>";
					echo "<br>";
					echo "XML Result: <br>";
					echo "<textarea cols='80' rows='10'>" . $xmlResult . "</textarea>";
					echo "<br>";
					echo "Cart Contents: " . $order_weight . "<br><br>\n";
				}

				if ($error)
				{
					echo "We are unable to obtain any USPS shipping methods at this time, please choose from one of the following shipping methods.";

					return $shippingrate;
				}
				//Get shipping options that are selected as available in VM from XML response
				$count = 0;

				if ($domestic)
				{
					$matchedchild = $xmlDoc->Package;

					foreach ($matchedchild->Postage as $postage)
					{
						$serviceName = str_replace("&lt;sup&gt;&amp;reg;&lt;/sup&gt;", "", (string) $postage->MailService);
						$serviceName = str_replace("&lt;sup&gt;&amp;trade;&lt;/sup&gt;", "", $serviceName);
						$serviceName = str_replace("&lt;sup&gt;&#174;&lt;/sup&gt;", "", $serviceName);
						$serviceName = str_replace("&lt;sup&gt;&#8482;&lt;/sup&gt;", "", $serviceName);
						$serviceName = str_replace(" 1-Day", "", $serviceName);
						$serviceName = str_replace(" 2-Day", "", $serviceName);
						$serviceName = str_replace(" 3-Day", "", $serviceName);
						$serviceName = str_replace(" Military", "", $serviceName);
						$serviceName = str_replace(" DPO", "", $serviceName);

						if (in_array($serviceName, $usps_ship_active))
						{
							$ship_service[$count] = $serviceName;
							$ship_postage[$count] = (string) $postage->Rate;

							if (preg_match('/%$/', USPS_HANDLINGFEE))
							{
								$ship_postage[$count] = $ship_postage[$count] * (1 + substr(USPS_HANDLINGFEE, 0, -1) / 100);
							}
							else
							{
								$ship_postage[$count] = $ship_postage[$count] + USPS_HANDLINGFEE;
							}

							$count++;
						}
					}
				}
				else
				{
					// International response
					$totalmatchedchild = $xmlDoc->Package;

					if ($totalmatchedchild)
					{
						foreach ($totalmatchedchild->Service as $service)
						{
							$serviceName = str_replace("&lt;sup&gt;&amp;reg;&lt;/sup&gt;", "", (string) $service->SvcDescription);
							$serviceName = str_replace("&lt;sup&gt;&amp;trade;&lt;/sup&gt;", "", $serviceName);
							$serviceName = str_replace("&lt;sup&gt;&#174;&lt;/sup&gt;", "", $serviceName);
							$serviceName = str_replace("&lt;sup&gt;&#8482;&lt;/sup&gt;", "", $serviceName);

							if (in_array($serviceName, $usps_intl_active))
							{
								$ship_service[$count] = $serviceName;
								$ship_postage[$count] = (string) $service->Postage;
								$ship_commit[$count] = (string) $service->SvcCommitments;
								$ship_weight[$count] = (string) $service->MaxWeight;

								if (preg_match('/%$/', USPS_INTLHANDLINGFEE))
								{
									$ship_postage[$count] = $ship_postage[$count] * (1 + substr(USPS_INTLHANDLINGFEE, 0, -1) / 100);
								}
								else
								{
									$ship_postage[$count] = $ship_postage[$count] + USPS_INTLHANDLINGFEE;
								}

								$count++;
							}
						}
					}
				}

				//Finally, write out the shipping options
				$i = 0;

				while ($i < $count)
				{
					// USPS returns Charges in USD.
					$charge[$i] = $ship_postage[$i];

					// Add prefix to shipping options if exists
					/*if((!strstr($ship_service[$i],'First-Class') && USPS_INTL10_TEXT) && !$domestic)
					{
						if(!strstr($ship_service[$i],'Large') && USPS_INTL11_TEXT && !$domestic)
						{*/
					if (!empty($usps_prefix))
					{
						$ship_service[$i] = $usps_prefix . " " . $ship_service[$i];
					}

					$delivary = "";

					if (USPS_SHOW_DELIVERY_QUOTE == 1 && !empty($ship_commit[$i]))
					{
						$delivary = $ship_commit[$i];
					}

					$shipping_rate_id = $shippinghelper->encryptShipping(__CLASS__ . "|" . $shipping->name . "|" . $ship_service[$i] . "|" . number_format($charge[$i], 2, '.', '') . "|" . $ship_service[$i] . "|single|0");
					$shippingrate[$rate] = new stdClass;
					$shippingrate[$rate]->text = $ship_service[$i];
					$shippingrate[$rate]->value = $shipping_rate_id;
					$shippingrate[$rate]->rate = $charge[$i];
					$shippingrate[$rate]->vat = 0;
					$rate++;
					$i++;
					/*}
				}*/
				}
			}
		}

		return $shippingrate;
	}

	/**
	 * Show all configuration parameters for this Shipping method
	 * @returns boolean False when the Shipping method has no configration
	 */
	public function show_configuration()
	{
		?>

		<?php    return true;
	} //end function show_configuration

}
define('_USPS_USERNAME', 'USPS shipping username');
define('_USPS_USERNAME_TOOLTIP', 'Username that you received from registering at USPS.com.');
define('_USPS_PASSWORD', 'USPS shipping password');
define('_USPS_PASSWORD_TOOLTIP', 'Password that you received from registering at USPS.com.');
define('_USPS_SERVER', 'USPS shipping server');
define('_USPS_SERVER_TOOLTIP', 'USPS shipping server, currently only works on live server!  Should be production.shippingapis.com');
define('_USPS_PATH', 'USPS shipping path');
define('_USPS_PATH_TOOLTIP', 'USPS shipping path, should be /ShippingAPI.dll');
define('_USPS_TAX_CLASS', 'Tax Class');
define('_USPS_TAX_CLASS_TOOLTIP', 'Use the following tax class on the shipping fee.');
define('_USPS_PROXYSERVER', 'Proxy server (include http://)');
define('_USPS_PROXYSERVER_TOOLTIP', 'If your hosting provider requires a proxy server to make the CURL request to USPS (ie. Godaddy requires http://proxy.shr.secureserver.net:3128)');
define('_USPS_PADDING', 'Percent to pad weight for shipping package. (Include %)');
define('_USPS_PADDING_TOOLTIP', 'Pad the shipping weight to allow additional weight for shipping box and packing. Using this allows you to put actual weight in your items weight settings.  Requires you to have the % sign included.  Example 15%');
define('_USPS_HANDLING_FEE', 'Your handling fee for USPS domestic shipments.');
define('_USPS_HANDLING_FEE_TOOLTIP', 'Do you want to charge a handling fee to ship domestic with USPS?  Format can be a dollar amount (example 2.00) or a percentage (example 5%).');
define('_USPS_INTLHANDLINGFEE', 'Your handling fee for USPS International shipments.');
define('_USPS_INTLHANDLINGFEE_TOOLTIP', 'Do you want to charge a handling fee to ship Internationally with USPS? Format can be a dollar amount (example 2.00) or a percentage (example 5%).');
define('_USPS_MACHINABLE', 'Machinable packages?');
define('_USPS_MACHINABLE_TOOLTIP', 'Is this package able to be handled by a machine.  Default should be No.');
define('_USPS_QUOTE', 'Show delivery days quote?');
define('_USPS_QUOTE_TOOLTIP', 'Show USPS commited deliver days next to the shipping service and cost in the front-end. USPS will send back the delivery days for each International service, but the domestic values must be set below.');
define('_USPS_REPORTERRORS', 'Report errors on frontend?');
define('_USPS_REPORTERRORS_TOOLTIP', 'Enabling the errors generated from USPS module is useful when troubleshooting or to provide additional information to the shopper.');
define('_USPS_STANDARDSHIPPING', 'Show standard shipping options on error or no USPS results?');
define('_USPS_STANDARDSHIPPING_TOOLTIP', 'If turned on this option will display the standard shipping options in the frontend if there is an error encountered in the USPS module or there are no active services returned from USPS.');
define('_USPS_PREFIX', 'Prefix to append to shipping options');
define('_USPS_PREFIX_TOOLTIP', 'This allows you to append a prefix to the shipping options returned from USPS and displayed on the shipping step of the checkout process. If left blank the shipping descriptions will appear as they do from USPS.');
define('_USPS_SHIP', 'Domestic Shipping Options');
define('_USPS_INTL', 'International Shipping Options');
define('_USPS_COMMIT_TOOLTIP', 'USPS domestic services do not include the commitment in the response. Therefore text in this column will be displayed as the commitment days for each service if the option to display the delivery days is on.');
define('_USPS_RESPONSE_ERROR', 'USPS was not able to process the Shipping Rate Request.');
define('_USPS_DOMESTIC_SHIPPING_METHOD', 'Select Service for domestice type user.');
?>
