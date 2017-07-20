<?php
/**
 * @package     RedSHOP
 * @subpackage  Plugin
 *
 * @copyright   Copyright (C) 2008 - 2015 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

defined('_JEXEC') or die;

JLoader::import('redshop.library');

/**
 * USPS Shipping plugins
 *
 * @package     Redshop.Shipping
 * @subpackage  System
 * @since       1.5
 */
class PlgRedshop_ShippingUspsv4 extends JPlugin
{
	const SHIPPING_NAME = 'uspsv4';

	/**
	 * Method will be trigger on showing configuration
	 *
	 * @param   object  $ps  Plugin information
	 *
	 * @deprecated  Will be deprecated in 1.6 and will be moved to joomla plugin params.
	 * @return  void
	 */
	public function onShowconfig($ps)
	{
		if ($ps->element == self::SHIPPING_NAME)
		{
			?>
			<table>
			<tr>
				<td>
					<?php echo JText::_('PLG_REDSHOP_SHIPPING_USPSV4_USPS_USERNAME'); ?>
				</td>
				<td>
					<input
						type="text"
						name="USPS_USERNAME"
						class="inputbox"
						value="<?php echo USPS_USERNAME ?>"
					/>
				</td>
				<td>
					<?php echo JText::_('PLG_REDSHOP_SHIPPING_USPSV4_USPS_USERNAME_TOOLTIP'); ?>
				</td>
			</tr>
			<tr>
				<td>
					<?php echo JText::_('PLG_REDSHOP_SHIPPING_USPSV4_USPS_PASSWORD'); ?>
				</td>
				<td>
					<input
						type="text"
						name="USPS_PASSWORD"
						class="inputbox"
						value="<?php echo USPS_PASSWORD ?>"
					/>
				</td>
				<td>
					<?php echo JText::_('PLG_REDSHOP_SHIPPING_USPSV4_USPS_PASSWORD_TOOLTIP'); ?>
				</td>
			</tr>
			<tr>
				<td>
					<?php echo JText::_('PLG_REDSHOP_SHIPPING_USPSV4_USPS_SERVER'); ?>
				</td>
				<td>
					<input
						type="text"
						name="USPS_SERVER"
						class="inputbox"
						value="<?php echo USPS_SERVER ?>"
					/>
				</td>
				<td>
					<?php echo JText::_('PLG_REDSHOP_SHIPPING_USPSV4_USPS_SERVER_TOOLTIP'); ?>
				</td>
			</tr>
			<tr>
				<td>
					<?php echo JText::_('PLG_REDSHOP_SHIPPING_USPSV4_USPS_PATH'); ?>

				</td>
				<td>
					<input
						type="text"
						name="USPS_PATH"
						class="inputbox"
						value="<?php echo USPS_PATH ?>"
					/>
				</td>
				<td>
					<?php echo JText::_('PLG_REDSHOP_SHIPPING_USPSV4_USPS_PATH_TOOLTIP'); ?>
				</td>
			</tr>
			</tr>
			<tr class="row1">
				<td>
					<?php echo JText::_('COM_REDSHOP_SHIP_FROM_ZIPCODE');?>
				</td>
				<td>
					<input
						class="inputbox"
						type="text"
						name="OVERRIDE_SOURCE_ZIP"
				        value="<?php echo OVERRIDE_SOURCE_ZIP ?>"
				    />
				</td>
				<td>
					<?php echo JText::_('COM_REDSHOP_SHIP_FROM_ZIPCODE_TOOLTIP'); ?>
				</td>
			</tr>
			<tr>
				<td>
					<?php echo JText::_('PLG_REDSHOP_SHIPPING_USPSV4_USPS_PROXYSERVER'); ?>
				</td>
				<td>
					<input
						type="text"
						name="USPS_PROXYSERVER"
						class="inputbox"
						value="<?php echo USPS_PROXYSERVER ?>"
					/>
				</td>
				<td>
					<?php echo JText::_('PLG_REDSHOP_SHIPPING_USPSV4_USPS_PROXYSERVER_TOOLTIP'); ?>
				</td>
			</tr>
			<tr><td colspan="3"><hr/></td></tr>
			<tr>
				<td>
					<?php echo JText::_('PLG_REDSHOP_SHIPPING_USPSV4_USPS_PADDING'); ?>
				</td>
				<td>
					<input
						class="inputbox"
						type="text"
						name="USPS_PADDING"
						value="<?php echo USPS_PADDING ?>"
					/>
				</td>
				<td><?php echo JText::_('PLG_REDSHOP_SHIPPING_USPSV4_USPS_PADDING_TOOLTIP'); ?></td>
			</tr>
			<tr>
				<td>
					<?php echo JText::_('PLG_REDSHOP_SHIPPING_USPSV4_USPS_HANDLING_FEE'); ?>
				</td>
				<td>
					<input
						class="inputbox"
						type="text"
						name="USPS_HANDLINGFEE"
						value="<?php echo USPS_HANDLINGFEE ?>"
					/>
				</td>
				<td><?php echo JText::_('PLG_REDSHOP_SHIPPING_USPSV4_USPS_HANDLING_FEE_TOOLTIP'); ?></td>
			</tr>
			<tr>
				<td>
					<?php echo JText::_('PLG_REDSHOP_SHIPPING_USPSV4_USPS_INTLHANDLINGFEE'); ?>

				</td>
				<td>
					<input
						type="text"
						name="USPS_INTLHANDLINGFEE"
						class="inputbox"
					    value="<?php echo USPS_INTLHANDLINGFEE ?>"
					/>
				</td>
				<td>
					<?php echo JText::_('PLG_REDSHOP_SHIPPING_USPSV4_USPS_INTLHANDLINGFEE_TOOLTIP'); ?>
				</td>
			</tr>
			<tr>
				<td>
					<?php echo JText::_('PLG_REDSHOP_SHIPPING_USPSV4_USPS_MACHINABLE'); ?>
				</td>
				<td>
					<?php
						echo JHTML::_('redshopselect.booleanlist', 'USPS_MACHINABLE', array(), USPS_MACHINABLE);
					?>
				</td>
				<td>
					<?php echo JText::_('PLG_REDSHOP_SHIPPING_USPSV4_USPS_MACHINABLE_TOOLTIP'); ?>
				</td>
			</tr>
			<tr>
				<td>
					<?php echo JText::_('PLG_REDSHOP_SHIPPING_USPSV4_USPS_QUOTE'); ?>
				</td>
				<td>
					<?php
						echo JHTML::_('redshopselect.booleanlist', 'USPS_SHOW_DELIVERY_QUOTE', array(), USPS_SHOW_DELIVERY_QUOTE);
					?>
				</td>
				<td>
					<?php echo JText::_('PLG_REDSHOP_SHIPPING_USPSV4_USPS_QUOTE_TOOLTIP'); ?>
				</td>
			</tr>
			<tr>
				<td>
					<?php echo JText::_('PLG_REDSHOP_SHIPPING_USPSV4_USPS_REPORTERRORS'); ?>
				</td>
				<td>
					<?php
						echo JHTML::_('redshopselect.booleanlist', 'USPS_REPORTERRORS', array(), USPS_REPORTERRORS);
					?>
				</td>
				<td>
					<?php echo JText::_('PLG_REDSHOP_SHIPPING_USPSV4_USPS_REPORTERRORS_TOOLTIP'); ?>
				</td>
			</tr>
			<tr>
				<td>
					<?php echo JText::_('PLG_REDSHOP_SHIPPING_USPSV4_USPS_STANDARDSHIPPING'); ?>
				</td>
				<td>
					<?php
						echo JHTML::_('redshopselect.booleanlist', 'USPS_STANDARDSHIPPING', array(), USPS_STANDARDSHIPPING);
					?>
				</td>
				<td>
					<?php echo JText::_('PLG_REDSHOP_SHIPPING_USPSV4_USPS_STANDARDSHIPPING_TOOLTIP'); ?>
				</td>
			</tr>

			<tr>
				<td>
					<?php echo JText::_('PLG_REDSHOP_SHIPPING_USPSV4_USPS_PREFIX'); ?>

				</td>
				<td>
					<input type="text" name="USPS_PREFIX" class="inputbox" value="<?php echo USPS_PREFIX ?>"/>
				</td>
				<td>
					<?php echo JText::_('PLG_REDSHOP_SHIPPING_USPSV4_USPS_PREFIX_TOOLTIP'); ?>
				</td>
			</tr>
			</table>
			<table cellpadding="30">
				<tr valign="top">
					<td>
						<table>
							<tr>
								<td colspan="3">
								<hr>
									<strong><?php echo JText::_('PLG_REDSHOP_SHIPPING_USPSV4_USPS_SHIP'); ?></strong>
								<hr>
								</td>
							</tr>
							<?php
							$i = 0;
							?>
							<?php while (defined("USPS_SHIP" . $i)) : ?>

							<?php
								$shipName = 'USPS_SHIP' . $i;
							?>
							<tr class="row<?php echo($i & 1); ?>">
								<td>
									<?php echo constant($shipName . '_TEXT'); ?>
								</td>
								<td>
									<?php
										echo JHtml::_(
											'redshopselect.booleanlist',
											$shipName,
											array(),
											constant($shipName)
										);
									?>
								</td>
							</tr>
							<?php $i++; ?>
							<?php endwhile; ?>
						</table>
					</td>
					<td>
						<table>
							<tr>
								<td colspan="3">
									<hr>
										<strong><?php echo JText::_('PLG_REDSHOP_SHIPPING_USPSV4_USPS_INTL'); ?></strong>
									<hr>
								</td>
							</tr>
							<?php $i = 0; ?>

							<?php while (defined("USPS_INTL" . $i)) : ?>
							<?php
								$shipName = 'USPS_INTL' . $i;
							?>
							<tr class="row<?php echo($i & 1); ?>">
								<td>
									<?php echo constant($shipName . '_TEXT'); ?>
								</td>
								<td>
									<?php
										echo JHtml::_(
											'redshopselect.booleanlist',
											$shipName,
											array(),
											constant($shipName)
										);
									?>
								</td>
							</tr>
							<?php $i++; ?>
							<?php endwhile; ?>
						</table>
					</td>
				</tr>
			</table>
			<?php
			return true;
		}
	}

	/**
	 * Method will be trigger on writing configuration
	 *
	 * @param   object  $d  Plugin information
	 *
	 * @deprecated  Will be deprecated in 1.6 and will be moved to joomla plugin params.
	 * @return  void
	 */
	public function onWriteconfig($d)
	{
		if ($d['element'] == self::SHIPPING_NAME)
		{
			$maincfgfile = JPATH_ROOT . '/plugins/' . $d['plugin'] . '/' . self::SHIPPING_NAME . '/' . self::SHIPPING_NAME . '.cfg.php';

			$config = array(
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

			$configFile = "<?php\n";
			$configFile .= "defined('_JEXEC') or die;\n";

			foreach ($config as $key => $value)
			{
				$configFile .= "define('$key', '$value');\n";
			}

			if ($fp = fopen($maincfgfile, "w"))
			{
				fputs($fp, $configFile, strlen($configFile));
				fclose($fp);

				return true;
			}
			else
			{
				return false;
			}
		}
	}

	/**
	 * Method will trigger on listing shipping rate in checkout.
	 *
	 * @param   array  &$d  Shipping Cart information
	 *
	 * @return  array  Shipping Rate array
	 */
	public function onListRates(&$d)
	{
		$shippinghelper = shipping::getInstance();
		$producthelper  = productHelper::getInstance();
		$redconfig      = Redconfiguration::getInstance();
		$shipping       = $shippinghelper->getShippingMethodByClass(self::SHIPPING_NAME);

		$db = JFactory::getDbo();

		$shippingrate = array();
		$rate = 0;

		include_once JPATH_ROOT . '/plugins/' . $shipping->folder . '/' . $shipping->element . '/' . $shipping->element . '.cfg.php';

		// Conversation of weight
		$unitRatio       = $producthelper->getUnitConversation('pounds', Redshop::getConfig()->get('DEFAULT_WEIGHT_UNIT'));
		$unitRatioVolume = $producthelper->getUnitConversation('inch', Redshop::getConfig()->get('DEFAULT_VOLUME_UNIT'));
		$totaldimention  = $shippinghelper->getCartItemDimention();
		$order_weight    = $totaldimention['totalweight'];

		if ($unitRatio != 0)
		{
			// Converting weight in pounds
			$order_weight = $order_weight * $unitRatio;
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
			$productData                      = $shippinghelper->getProductVolumeShipping();

			$whereShippingBoxes               = array();
			$whereShippingBoxes['box_length'] = $productData[2]['length'];
			$whereShippingBoxes['box_width']  = $productData[1]['width'];
			$whereShippingBoxes['box_height'] = $productData[0]['height'];
		}

		if (is_array($whereShippingBoxes) && count($whereShippingBoxes) > 0 && $unitRatioVolume > 0)
		{
			$shipping_length = (int) ($whereShippingBoxes['box_length'] * $unitRatioVolume);
			$shipping_width  = (int) ($whereShippingBoxes['box_width'] * $unitRatioVolume);
			$shipping_height = (int) ($whereShippingBoxes['box_height'] * $unitRatioVolume);
		}
		else
		{
			return $shippingrate;
		}

		$girth = 2 * ceil($shipping_width) + 2 * ceil($shipping_height);
		$size  = (ceil($shipping_length) + $girth) / $unitRatioVolume;

		if ($size <= (84 * $unitRatioVolume))
		{
			$sizetype = 'REGULAR';
		}
		elseif ($size <= (108 * $unitRatioVolume))
		{
			$sizetype = 'LARGE';
		}
		elseif ($size <= (130 * $unitRatioVolume))
		{
			$sizetype = 'OVERSIZE';
		}
		else
		{
			$sizetype = 'GI-HUGE-IC';
		}

		if ($order_weight > 0)
		{
			$usps_username    = USPS_USERNAME;
			$usps_password    = USPS_PASSWORD;
			$usps_proxyserver = USPS_PROXYSERVER;
			$usps_packageid   = 0;

			/*This does not appear to be used in module
			$usps_intllbrate = USPS_INTLLBRATE;
			USPS International Per Pound Rate
			USPS International handling fee*/
			$usps_intlhandlingfee = USPS_INTLHANDLINGFEE;

			if (USPS_REPORTERRORS == '1')
			{
				$usps_reporterrors = 1;
			}
			else
			{
				$usps_reporterrors = 0;
			}

			// Flag used to determine if standard shipping should be displayed if encounter error or no options available
			if (USPS_STANDARDSHIPPING == '1')
			{
				$usps_standardshipping = 1;
			}
			else
			{
				$usps_standardshipping = 0;
			}

			$usps_prefix = USPS_PREFIX;

			// Pad the shipping weight to allow weight for shipping materials
			$usps_padding = USPS_PADDING * 0.01;
			$order_weight = ($order_weight * $usps_padding) + $order_weight;

			// USPS Machinable for Parcel Post
			$usps_machinable = USPS_MACHINABLE;

			if ($usps_machinable == '1')
			{
				$usps_machinable = 'TRUE';
			}
			else
			{
				$usps_machinable = 'FALSE';
			}

			// The zip that you are shipping from
			// substr($dbv->f("vendor_zip"),0,5);
			$source_zip = OVERRIDE_SOURCE_ZIP;
			$shpService = 'All';

			if (isset($shippinginfo->country_code))
			{
				$shippinginfo->country_2_code = $redconfig->getCountryCode2($shippinginfo->country_code);
			}

			// Send integer rounded down
			$shippingPounds = floor($order_weight);

			// Send integer rounded up
			$shipping_ounces = ceil(16 * ($order_weight - floor($order_weight)));

			if ($order_weight > 70.00)
			{
				echo JText::_('PLG_REDSHOP_SHIPPING_USPSV4_USPS_OVER_WEIGHT');

				return $shippingrate;
			}
			else
			{
				// Default to International
				$domestic = 0;

				if ($shippinginfo->country_2_code == "US" || $shippinginfo->country_2_code == "PR" || $shippinginfo->country_2_code == "VI")
				{
					// Domestic if US, PR or VI
					$domestic = 1;
				}

				// Build XML string based on service request
				if ($domestic)
				{
					// The xml that will be posted to usps for domestic rates
					$xmlPost = 'API=RateV4&XML=<RateV4Request USERID="' . $usps_username . '" PASSWORD="' . $usps_password . '">';

					$xmlPost .= '<Package ID="' . $usps_packageid . '">';
					$xmlPost .= "<Service>ALL</Service>";
					$xmlPost .= "<ZipOrigination>" . $source_zip . "</ZipOrigination>";
					$xmlPost .= "<ZipDestination>" . substr($shippinginfo->zipcode, 0, 5) . "</ZipDestination>";
					$xmlPost .= "<Pounds>" . $shippingPounds . "</Pounds>";
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
						$ship_option_avail = (boolean) constant("USPS_SHIP" . $i);
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
					// The xml that will be posted to usps for international rates
					$xmlPost = 'API=IntlRateV2&XML=<IntlRateV2Request USERID="' . $usps_username . '" PASSWORD="' . $usps_password . '">';
					$xmlPost .= '<Package ID="1ST">';
					$xmlPost .= '<Pounds>' . $shippingPounds . '</Pounds>';
					$xmlPost .= '<Ounces>' . $shipping_ounces . '</Ounces>';
					$xmlPost .= '<Machinable>True</Machinable>';
					$xmlPost .= '<MailType>Package</MailType>';
					$xmlPost .= '<ValueOfContents>0.0</ValueOfContents>';
					$xmlPost .= '<Country>' . self::getCountryName($shippinginfo->country_code) . '</Country>';
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
					$error     = curl_error($CR);
					$xmlDoc    = JFactory::getXML($xmlResult, false);

					if (!empty($error))
					{
						$error = true;
					}
					else
					{
						// Check for error from response from USPS
						if (strstr($xmlResult, "<Error>"))
						{
							$error = true;
						}
					}

					curl_close($CR);
				}
				else
				{
					// Call fsockopen correctly and parse the response
					$fp = fsockopen(USPS_SERVER, 80, $errno, $errstr, $timeout = 60);

					if (!$fp)
					{
						$error = true;
					}
					else
					{
						// Send the server request
						fputs($fp, "POST " . USPS_PATH . " HTTP/1.1\r\n");
						fputs($fp, "Host: " . USPS_SERVER . "\r\n");
						fputs($fp, "Content-type: application/x-www-form-urlencoded\r\n");
						fputs($fp, "Content-length: " . strlen($xmlPost) . "\r\n");
						fputs($fp, "Connection: close\r\n\r\n");
						fputs($fp, $xmlPost . "\r\n\r\n");

						$xmlResult = '';
						$header    = '';

						// Get the response
						$lineNum = 0;

						// Loop until the end of the header
						do
						{
							$header .= fgets($fp, 128);
						}
						while (strpos($header, "\r\n\r\n") === false);

						while (!feof($fp))
						{
							$xmlResult .= fgets($fp, 128);
						}

						fclose($fp);

						// XML Parsing
						if ($xmlDoc = JFactory::getXML($xmlResult, false))
						{
							$error = false;
						}
						else
						{
							$error = true;
						}
					}
				}

				if ($this->params->get("uspsv4_debug"))
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
					echo JText::_('PLG_REDSHOP_SHIPPING_USPSV4_USPS_NO_PACKAGES_FOUND');

					return $shippingrate;
				}

				// Get shipping options that are selected as available in VM from XML response
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

						if ($this->containsString($serviceName, $usps_ship_active))
						{
							$ship_service[$count] = (string) $postage->MailService;
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

							if ($this->containsString($serviceName, $usps_intl_active))
							{
								$ship_service[$count] = (string) $service->SvcDescription;
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

				// Finally, write out the shipping options
				$i = 0;

				while ($i < $count)
				{
					// USPS returns Charges in USD.
					$charge[$i] = $ship_postage[$i];

					if (!empty($usps_prefix))
					{
						$ship_service[$i] = $usps_prefix . " " . $ship_service[$i];
					}

					$delivary = "";

					if (USPS_SHOW_DELIVERY_QUOTE == 1 && !empty($ship_commit[$i]))
					{
						$delivary = $ship_commit[$i];
					}

					$shipping_rate_id = RedshopShippingRate::encrypt(
											array(
												__CLASS__,
												$shipping->name,
												$ship_service[$i],
												number_format($charge[$i], 2, '.', ''),
												$ship_service[$i],
												'single',
												'0'
											)
										);

					$shippingrate[$rate]        = new stdClass;
					$shippingrate[$rate]->text  = $ship_service[$i];
					$shippingrate[$rate]->value = $shipping_rate_id;
					$shippingrate[$rate]->rate  = $charge[$i];
					$shippingrate[$rate]->vat   = 0;

					$rate++;
					$i++;
				}
			}
		}

		return $shippingrate;
	}

	/**
	 * Find substring from an array values - similar to in_array but finds as substrings.
	 *
	 * @param   string  $needle    String which needs to match
	 * @param   array   $haystack  Array from it needs to find.
	 *
	 * @return  boolean  True if string matches.
	 */
	public function containsString($needle, $haystack)
	{
		if ($needle == null)
		{
			return false;
		}

		foreach ($haystack as $h)
		{
			if (strstr($h, $needle) !== false)
			{
				return true;
			}
		}

		return false;
	}

	/**
	 * Get country name
	 *
	 * @param   integer  $country3Code  3 digit country code
	 *
	 * @return  string  Country Name
	 */
	public static function getCountryName($country3Code)
	{
		// Initialiase variables.
		$db    = JFactory::getDbo();
		$query = $db->getQuery(true)
					->select('country_name')
					->from($db->qn('#__redshop_country'))
					->where($db->qn('country_3_code') . ' = ' . $db->q($country3Code));

		// Set the query and load the result.
		$db->setQuery($query, 0, 1);
		$countryName = $db->loadResult();

		// Check for a database error.
		if ($db->getErrorNum())
		{
			JError::raiseWarning(500, $db->getErrorMsg());

			return null;
		}

		return $countryName;
	}
}
