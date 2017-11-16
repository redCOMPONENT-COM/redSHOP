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
	/**
	 * Auto load language
	 *
	 * @var boolean
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

		include_once JPATH_ROOT . '/plugins/' . $this->_type . '/' . $this->_name . '/config/' . $this->_name . '.cfg.php';

		echo RedshopLayoutHelper::render('config', array(), __DIR__ . '/layouts');

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

		$configFile = JPATH_ROOT . '/plugins/' . $this->_type . '/' . $this->_name . '/config/' . $this->_name . '.cfg.php';

		$configs = array(
			"USPS_USERNAME"                 => $data['USPS_USERNAME'],
			"USPS_PASSWORD"                 => $data['USPS_PASSWORD'],
			"USPS_SERVER"                   => $data['USPS_SERVER'],
			"USPS_PATH"                     => $data['USPS_PATH'],
			"USPS_PROXYSERVER"              => $data['USPS_PROXYSERVER'],
			"USPS_TAX_CLASS"                => $data['USPS_TAX_CLASS'],
			"USPS_HANDLINGFEE"              => $data['USPS_HANDLINGFEE'],
			"USPS_PADDING"                  => $data['USPS_PADDING'],
			"OVERRIDE_SOURCE_ZIP"           => $data['OVERRIDE_SOURCE_ZIP'],
			"USPS_INTLHANDLINGFEE"          => $data['USPS_INTLHANDLINGFEE'],
			"USPS_MACHINABLE"               => $data['USPS_MACHINABLE'],
			"USPS_SHOW_DELIVERY_QUOTE"      => $data['USPS_SHOW_DELIVERY_QUOTE'],
			"USPS_REPORTERRORS"             => $data['USPS_REPORTERRORS'],
			"USPS_STANDARDSHIPPING"         => $data['USPS_STANDARDSHIPPING'],
			"USPS_PREFIX"                   => $data['USPS_PREFIX'],
			"USPS_DOMESTIC_SHIPPING_METHOD" => implode(',', $data['USPS_DOMESTIC_SHIPPING_METHOD']),
			"USPS_SHIP0"                    => $data['USPS_SHIP0'],
			"USPS_SHIP1"                    => $data['USPS_SHIP1'],
			"USPS_SHIP2"                    => $data['USPS_SHIP2'],
			"USPS_SHIP3"                    => $data['USPS_SHIP3'],
			"USPS_SHIP4"                    => $data['USPS_SHIP4'],
			"USPS_SHIP5"                    => $data['USPS_SHIP5'],
			"USPS_SHIP6"                    => $data['USPS_SHIP6'],
			"USPS_SHIP7"                    => $data['USPS_SHIP7'],
			"USPS_SHIP8"                    => $data['USPS_SHIP8'],
			"USPS_SHIP9"                    => $data['USPS_SHIP9'],
			"USPS_SHIP10"                   => $data['USPS_SHIP10'],
			"USPS_SHIP11"                   => $data['USPS_SHIP11'],
			"USPS_SHIP12"                   => $data['USPS_SHIP12'],
			"USPS_SHIP13"                   => $data['USPS_SHIP13'],
			"USPS_SHIP14"                   => $data['USPS_SHIP14'],
			"USPS_SHIP15"                   => $data['USPS_SHIP15'],
			"USPS_SHIP16"                   => $data['USPS_SHIP16'],
			"USPS_SHIP17"                   => $data['USPS_SHIP17'],
			"USPS_SHIP18"                   => $data['USPS_SHIP18'],
			"USPS_SHIP19"                   => $data['USPS_SHIP19'],
			"USPS_SHIP20"                   => $data['USPS_SHIP20'],
			"USPS_SHIP21"                   => $data['USPS_SHIP21'],
			"USPS_SHIP22"                   => $data['USPS_SHIP22'],
			"USPS_SHIP23"                   => $data['USPS_SHIP23'],
			"USPS_SHIP24"                   => $data['USPS_SHIP24'],
			"USPS_SHIP25"                   => $data['USPS_SHIP25'],
			"USPS_SHIP26"                   => $data['USPS_SHIP26'],
			"USPS_SHIP27"                   => $data['USPS_SHIP27'],
			"USPS_SHIP28"                   => $data['USPS_SHIP28'],
			"USPS_SHIP29"                   => $data['USPS_SHIP29'],
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
			"USPS_INTL0"                    => $data['USPS_INTL0'],
			"USPS_INTL1"                    => $data['USPS_INTL1'],
			"USPS_INTL2"                    => $data['USPS_INTL2'],
			"USPS_INTL3"                    => $data['USPS_INTL3'],
			"USPS_INTL4"                    => $data['USPS_INTL4'],
			"USPS_INTL5"                    => $data['USPS_INTL5'],
			"USPS_INTL6"                    => $data['USPS_INTL6'],
			"USPS_INTL7"                    => $data['USPS_INTL7'],
			"USPS_INTL8"                    => $data['USPS_INTL8'],
			"USPS_INTL9"                    => $data['USPS_INTL9'],
			"USPS_INTL10"                   => $data['USPS_INTL10'],
			"USPS_INTL11"                   => $data['USPS_INTL11'],
			"USPS_INTL12"                   => $data['USPS_INTL12'],
			"USPS_INTL13"                   => $data['USPS_INTL13'],
			"USPS_INTL14"                   => $data['USPS_INTL14'],
			"USPS_INTL15"                   => $data['USPS_INTL15'],
			"USPS_INTL16"                   => $data['USPS_INTL16'],
			"USPS_INTL17"                   => $data['USPS_INTL17'],
			"USPS_INTL18"                   => $data['USPS_INTL18'],
			"USPS_INTL19"                   => $data['USPS_INTL19'],
			"USPS_INTL20"                   => $data['USPS_INTL20'],
			"USPS_INTL21"                   => $data['USPS_INTL21'],
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

		foreach ($configs as $key => $value)
		{
			$config .= "define('$key', '$value');\n";
		}

		return JFile::write($configFile, $config);
	}

	/**
	 * Method will trigger on listing shipping rate in checkout.
	 *
	 * @param   array  $data   Shipping Cart information
	 *
	 * @return  array          Shipping Rate array
	 */
	public function onListRates(&$data)
	{
		$shippinghelper = shipping::getInstance();
		$productHelper  = productHelper::getInstance();
		$redconfig      = Redconfiguration::getInstance();
		$shipping       = RedshopHelperShipping::getShippingMethodByClass($this->_name);

		$shippingRates = array();
		$rate         = 0;

		include_once JPATH_ROOT . '/plugins/' . $this->_type . '/' . $this->_name . '/config/' . $this->_name . '.cfg.php';

		// Conversation of weight
		$unitRatio       = $productHelper->getUnitConversation('pounds', Redshop::getConfig()->get('DEFAULT_WEIGHT_UNIT'));
		$unitRatioVolume = $productHelper->getUnitConversation('inch', Redshop::getConfig()->get('DEFAULT_VOLUME_UNIT'));
		$totalDimension  = RedshopHelperShipping::getCartItemDimension();
		$orderWeight    = $totalDimension['totalweight'];

		if ($unitRatio != 0)
		{
			// Converting weight in pounds
			$orderWeight = $orderWeight * $unitRatio;
		}

		$shippingAddress = RedshopHelperShipping::getShippingAddress($data['users_info_id']);

		if (count($shippingAddress) < 1)
		{
			return $shippingRates;
		}

		if (isset($data['shipping_box_id']) && $data['shipping_box_id'])
		{
			$whereShippingBoxes = RedshopHelperShipping::getBoxDimensions($data['shipping_box_id']);
		}
		else
		{
			$productData = RedshopHelperShipping::getProductVolumeShipping();

			$whereShippingBoxes               = array();
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

		$girth = 2 * ceil($shippingWidth) + 2 * ceil($shippingHeight);
		$size  = (ceil($shippingLength) + $girth) / $unitRatioVolume;

		if ($size <= (84 * $unitRatioVolume))
		{
			$sizeType = 'REGULAR';
		}
		elseif ($size <= (108 * $unitRatioVolume))
		{
			$sizeType = 'LARGE';
		}
		elseif ($size <= (130 * $unitRatioVolume))
		{
			$sizeType = 'OVERSIZE';
		}
		else
		{
			$sizeType = 'GI-HUGE-IC';
		}

		if ($orderWeight > 0)
		{
			$uspsUsername    = USPS_USERNAME;
			$uspsPassword    = USPS_PASSWORD;
			$uspsProxyServer = USPS_PROXYSERVER;
			$uspsPackageId   = 0;

			/**
			 * This does not appear to be used in module
			 * $usps_intllbrate = USPS_INTLLBRATE;
			 * USPS International Per Pound Rate
			 * USPS International handling fee
			 */
			$uspsIntlHandlingFee = USPS_INTLHANDLINGFEE;

			if (USPS_REPORTERRORS == '1')
			{
				$uspsReportErrors = 1;
			}
			else
			{
				$uspsReportErrors = 0;
			}

			// Flag used to determine if standard shipping should be displayed if encounter error or no options available
			if (USPS_STANDARDSHIPPING == '1')
			{
				$uspsStandardShipping = 1;
			}
			else
			{
				$uspsStandardShipping = 0;
			}

			$uspsPrefix = USPS_PREFIX;

			// Pad the shipping weight to allow weight for shipping materials
			$uspsPadding = USPS_PADDING * 0.01;
			$orderWeight = ($orderWeight * $uspsPadding) + $orderWeight;

			// USPS Machinable for Parcel Post
			$uspsMachinable = USPS_MACHINABLE;

			if ($uspsMachinable == '1')
			{
				$uspsMachinable = 'TRUE';
			}
			else
			{
				$uspsMachinable = 'FALSE';
			}

			// The zip that you are shipping from
			// substr($dbv->f("vendor_zip"),0,5);
			$sourceZip = OVERRIDE_SOURCE_ZIP;
			$shpService = 'All';

			if (isset($shippingAddress->country_code))
			{
				$shippingAddress->country_2_code = RedshopHelperWorld::getCountryCode2($shippingAddress->country_code);
			}

			// Send integer rounded down
			$shippingPounds = floor($orderWeight);

			// Send integer rounded up
			$shippingOunces = ceil(16 * ($orderWeight - floor($orderWeight)));

			if ($orderWeight > 70.00)
			{
				echo JText::_('PLG_REDSHOP_SHIPPING_USPSV4_USPS_OVER_WEIGHT');

				return $shippingRates;
			}

			$uspsShippingActive = array();
			$uspsIntlActive = array();

			// Default to International
			$domestic = 0;

			if ($shippingAddress->country_2_code == "US" || $shippingAddress->country_2_code == "PR" || $shippingAddress->country_2_code == "VI")
			{
				// Domestic if US, PR or VI
				$domestic = 1;
			}

			// Build XML string based on service request
			if ($domestic)
			{
				// The xml that will be posted to usps for domestic rates
				$xmlPost = 'API=RateV4&XML=<RateV4Request USERID="' . $uspsUsername . '" PASSWORD="' . $uspsPassword . '">';

				$xmlPost .= '<Package ID="' . $uspsPackageId . '">';
				$xmlPost .= "<Service>ALL</Service>";
				$xmlPost .= "<ZipOrigination>" . $sourceZip . "</ZipOrigination>";
				$xmlPost .= "<ZipDestination>" . substr($shippingAddress->zipcode, 0, 5) . "</ZipDestination>";
				$xmlPost .= "<Pounds>" . $shippingPounds . "</Pounds>";
				$xmlPost .= "<Ounces>" . $shippingOunces . "</Ounces>";
				$xmlPost .= "<Container></Container>";
				$xmlPost .= "<Size>" . $sizeType . "</Size>";
				$xmlPost .= "<Width>" . $shippingWidth . "</Width>";
				$xmlPost .= "<Length>" . $shippingLength . "</Length>";
				$xmlPost .= "<Height>" . $shippingHeight . "</Height>";
				$xmlPost .= "<Girth>" . ceil($girth) . "</Girth>";
				$xmlPost .= "<Machinable>" . $uspsMachinable . "</Machinable>";
				$xmlPost .= "</Package>";

				$xmlPost .= "</RateV4Request>";

				$i = 0;

				while (defined("USPS_SHIP" . $i))
				{
					$shippingOptionAvailable = (boolean) constant("USPS_SHIP" . $i);
					$shippingOptionText  = constant("USPS_SHIP" . $i . "_TEXT");

					if ($shippingOptionAvailable == '1')
					{
						if ($shippingOptionText !== "")
						{
							$uspsShippingActive[] = $shippingOptionText;
						}
					}

					$i++;
				}
			}
			else
			{
				// The xml that will be posted to usps for international rates
				$xmlPost = 'API=IntlRateV2&XML=<IntlRateV2Request USERID="' . $uspsUsername . '" PASSWORD="' . $uspsPassword . '">';
				$xmlPost .= '<Package ID="1ST">';
				$xmlPost .= '<Pounds>' . $shippingPounds . '</Pounds>';
				$xmlPost .= '<Ounces>' . $shippingOunces . '</Ounces>';
				$xmlPost .= '<Machinable>True</Machinable>';
				$xmlPost .= '<MailType>Package</MailType>';
				$xmlPost .= '<ValueOfContents>0.0</ValueOfContents>';
				$xmlPost .= '<Country>' . self::getCountryName($shippingAddress->country_code) . '</Country>';
				$xmlPost .= '<Container>RECTANGULAR</Container>';
				$xmlPost .= "<Size>" . $sizeType . "</Size>";
				$xmlPost .= "<Width>" . $shippingWidth . "</Width>";
				$xmlPost .= "<Length>" . $shippingLength . "</Length>";
				$xmlPost .= "<Height>" . $shippingHeight . "</Height>";
				$xmlPost .= "<Girth>" . ceil($girth) . "</Girth>";
				$xmlPost .= "<CommercialFlag>N</CommercialFlag>";
				$xmlPost .= '</Package></IntlRateV2Request>';

				$i = 0;

				while (defined("USPS_INTL" . $i))
				{
					$shippingOptionAvailable = constant("USPS_INTL" . $i);
					$shippingOptionText  = constant("USPS_INTL" . $i . "_TEXT");

					if ($shippingOptionAvailable == '1')
					{
						if ($shippingOptionText !== "")
						{
							$uspsIntlActive[] = $shippingOptionText;
						}
					}

					$i++;
				}
			}

			$xmlResult = '';

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

				if (!empty($uspsProxyServer))
				{
					curl_setopt($CR, CURLOPT_HTTPPROXYTUNNEL, true);
					curl_setopt($CR, CURLOPT_PROXYTYPE, CURLPROXY_HTTP);
					curl_setopt($CR, CURLOPT_PROXY, $uspsProxyServer);
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
				$fp = fsockopen(USPS_SERVER, 80, $errorNumber, $errorMessage, $timeout = 60);

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
				echo "Cart Contents: " . $orderWeight . "<br><br>\n";
			}

			if ($error)
			{
				echo JText::_('PLG_REDSHOP_SHIPPING_USPSV4_USPS_NO_PACKAGES_FOUND');

				return $shippingRates;
			}

			// Get shipping options that are selected as available in VM from XML response
			$count = 0;

			$shippingServices = array();
			$shippingPostage = array();
			$shippingCommits = array();
			$shippingWeights = array();

			if ($domestic)
			{
				$matchedChild = $xmlDoc->Package;

				foreach ($matchedChild->Postage as $postage)
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

					if ($this->containsString($serviceName, $uspsShippingActive))
					{
						$shippingServices[$count] = (string) $postage->MailService;
						$shippingPostage[$count] = (string) $postage->Rate;

						if (preg_match('/%$/', USPS_HANDLINGFEE))
						{
							$shippingPostage[$count] = $shippingPostage[$count] * (1 + substr(USPS_HANDLINGFEE, 0, -1) / 100);
						}
						else
						{
							$shippingPostage[$count] = $shippingPostage[$count] + USPS_HANDLINGFEE;
						}

						$count++;
					}
				}
			}
			else
			{
				// International response
				$totalMatchedChild = $xmlDoc->Package;

				if ($totalMatchedChild)
				{
					foreach ($totalMatchedChild->Service as $service)
					{
						$serviceName = str_replace("&lt;sup&gt;&amp;reg;&lt;/sup&gt;", "", (string) $service->SvcDescription);
						$serviceName = str_replace("&lt;sup&gt;&amp;trade;&lt;/sup&gt;", "", $serviceName);
						$serviceName = str_replace("&lt;sup&gt;&#174;&lt;/sup&gt;", "", $serviceName);
						$serviceName = str_replace("&lt;sup&gt;&#8482;&lt;/sup&gt;", "", $serviceName);

						if ($this->containsString($serviceName, $uspsIntlActive))
						{
							$shippingServices[$count] = (string) $service->SvcDescription;
							$shippingPostage[$count] = (string) $service->Postage;
							$shippingCommits[$count]  = (string) $service->SvcCommitments;
							$shippingWeights[$count]  = (string) $service->MaxWeight;

							if (preg_match('/%$/', USPS_INTLHANDLINGFEE))
							{
								$shippingPostage[$count] = $shippingPostage[$count] * (1 + substr(USPS_INTLHANDLINGFEE, 0, -1) / 100);
							}
							else
							{
								$shippingPostage[$count] = $shippingPostage[$count] + USPS_INTLHANDLINGFEE;
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
				$charge[$i] = $shippingPostage[$i];

				if (!empty($uspsPrefix))
				{
					$shippingServices[$i] = $uspsPrefix . " " . $shippingServices[$i];
				}

				$delivery = "";

				if (USPS_SHOW_DELIVERY_QUOTE == 1 && !empty($shippingCommits[$i]))
				{
					$delivery = $shippingCommits[$i];
				}

				$shippingRateId = RedshopShippingRate::encrypt(
					array(
						__CLASS__,
						$shipping->name,
						$shippingServices[$i],
						number_format($charge[$i], 2, '.', ''),
						$shippingServices[$i],
						'single',
						'0'
					)
				);

				$shippingRates[$rate]        = new stdClass;
				$shippingRates[$rate]->text  = $shippingServices[$i];
				$shippingRates[$rate]->value = $shippingRateId;
				$shippingRates[$rate]->rate  = $charge[$i];
				$shippingRates[$rate]->vat   = 0;

				$rate++;
				$i++;
			}
		}

		return $shippingRates;
	}

	/**
	 * Find substring from an array values - similar to in_array but finds as substrings.
	 *
	 * @param   string $needle   String which needs to match
	 * @param   array  $haystack Array from it needs to find.
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
	 * @return  string                  Country Name
	 */
	public static function getCountryName($country3Code)
	{
		// Initialiase variables.
		$db    = JFactory::getDbo();
		$query = $db->getQuery(true)
			->select($db->qn('country_name'))
			->from($db->qn('#__redshop_country'))
			->where($db->qn('country_3_code') . ' = ' . $db->quote($country3Code));

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
