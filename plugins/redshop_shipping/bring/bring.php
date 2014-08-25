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

define('BRING_RESPONSE_ERROR', 'test');

JLoader::import('LoadHelpers', JPATH_SITE . '/components/com_redshop');
JLoader::load('RedshopHelperProduct');
JLoader::load('RedshopHelperCurrency');
JLoader::load('RedshopHelperAdminShipping');
JLoader::load('RedshopHelperAdminConfiguration');

class plgredshop_shippingbring extends JPlugin
{
	var $payment_code = "bring";
	var $classname = "bring";

	function onShowconfig($ps)
	{
		if ($ps->element == $this->classname)
		{
			?>
			<table class="admintable">
				<tr class="row0">
					<td><strong><?php echo JText::_('COM_REDSHOP_BRING_USERCODE_LBL') ?></strong></td>
					<td><input type="text" name="BRING_USERCODE" class="inputbox" value="<?php echo BRING_USERCODE ?>"/>
					</td>
					<td><?php echo JHTML::tooltip(JText::_('COM_REDSHOP_BRING_USERCODE_LBL'), JText::_('COM_REDSHOP_BRING_USERCODE_LBL'), 'tooltip.png', '', '', false);?></td>
				</tr>
				<tr class="row1">
					<td><strong><?php echo JText::_('COM_REDSHOP_BRING_SERVER_LBL') ?></strong></td>
					<td><input type="text" name="BRING_SERVER" class="inputbox" value="<?php echo BRING_SERVER ?>"/>
					</td>
					<td><?php echo JHTML::tooltip(JText::_('COM_REDSHOP_BRING_SERVER_LBL'), JText::_('COM_REDSHOP_BRING_SERVER_LBL'), 'tooltip.png', '', '', false);?></td>
				</tr>
				<tr class="row1">
					<td><strong><?php echo JText::_('COM_REDSHOP_BRING_PATH_LBL') ?></strong></td>
					<td><input type="text" name="BRING_PATH" class="inputbox" value="<?php echo BRING_PATH ?>"/></td>
					<td><?php echo JHTML::tooltip(JText::_('COM_REDSHOP_BRING_PATH_LBL'), JText::_('COM_REDSHOP_BRING_PATH_LBL'), 'tooltip.png', '', '', false);?></td>
				</tr>
				<tr class="row1">
					<td><strong><?php echo JText::_('COM_REDSHOP_BRING_ZIPCODE_FROM_LBL') ?></strong></td>
					<td><input type="text" name="BRING_ZIPCODE_FROM" class="inputbox"
					           value="<?php echo BRING_ZIPCODE_FROM ?>"/></td>
					<td><?php echo JHTML::tooltip(JText::_('COM_REDSHOP_BRING_ZIPCODE_FROM_LBL'), JText::_('COM_REDSHOP_BRING_ZIPCODE_FROM_LBL'), 'tooltip.png', '', '', false);?></td>
				</tr>
				<tr class="row1">
					<td><strong><?php echo JText::_('COM_REDSHOP_BRING_PRICE_SHOW_WITHVAT_LBL') ?></strong></td>
					<td>
						<label>
							<input name="BRING_PRICE_SHOW_WITHVAT"
							       type="radio" <?php if (BRING_PRICE_SHOW_WITHVAT == 1) echo "checked=\"checked\""; ?>
							       value="1"/>

							<?php echo JText::_('YES') ?>
						</label>
						<label>
							<input name="BRING_PRICE_SHOW_WITHVAT"
							       type="radio" <?php if (BRING_PRICE_SHOW_WITHVAT == 0) echo "checked=\"checked\""; ?>
							       value="0"/>
							<?php echo JText::_('NO') ?>
						</label>
					</td>

				</tr>
				<tr class="row1">
					<td><strong><?php echo JText::_('COM_REDSHOP_BRING_PRICE_SHOW_SHORT_DESC_LBL') ?></strong></td>
					<td>
						<label>
							<input name="BRING_PRICE_SHOW_SHORT_DESC"
							       type="radio" <?php if (BRING_PRICE_SHOW_SHORT_DESC == 1) echo "checked=\"checked\""; ?>
							       value="1"/>

							<?php echo JText::_('YES') ?>
						</label>
						<label>
							<input name="BRING_PRICE_SHOW_SHORT_DESC"
							       type="radio" <?php if (BRING_PRICE_SHOW_SHORT_DESC == 0) echo "checked=\"checked\""; ?>
							       value="0"/>
							<?php echo JText::_('NO') ?>
						</label>
					</td>

				</tr>
				<tr class="row1">
					<td><strong><?php echo JText::_('COM_REDSHOP_BRING_PRICE_SHOW_DESC_LBL') ?></strong></td>
					<td>
						<label>
							<input name="BRING_PRICE_SHOW_DESC"
							       type="radio" <?php if (BRING_PRICE_SHOW_DESC == 1) echo "checked=\"checked\""; ?>
							       value="1"/>

							<?php echo JText::_('YES') ?>
						</label>
						<label>
							<input name="BRING_PRICE_SHOW_DESC"
							       type="radio" <?php if (BRING_PRICE_SHOW_DESC == 0) echo "checked=\"checked\""; ?>
							       value="0"/>
							<?php echo JText::_('NO') ?>
						</label>
					</td>

				</tr>
				<tr class="row1">
					<td>
						<strong><?php echo JText::_('COM_REDSHOP_BRING_USE_SHIPPING_BOX_LBL') ?></strong></td>
					<td>
						<label>
							<input name="BRING_USE_SHIPPING_BOX"
							       type="radio" <?php if (BRING_USE_SHIPPING_BOX == 1) echo "checked=\"checked\""; ?>
							       value="1"/>
							<?php echo JText::_('COM_REDSHOP_YES') ?>
						</label>
						<label>
							<input name="BRING_USE_SHIPPING_BOX"
							       type="radio" <?php if (BRING_USE_SHIPPING_BOX == 0) echo "checked=\"checked\""; ?>
							       value="0"/>
							<?php echo JText::_('COM_REDSHOP_NO') ?>
						</label>
					</td>
					<td>
					</td>
				</tr>
				<tr class="row1">
					<td>
						<strong><?php echo JTEXT::_('BRING_SERVICE_LBL') ?></strong></td>
					<td>
						<?php $ArrExlode = explode(',', BRING_SERVICE);?>
						<select id="BRING_SERVICE" name="BRING_SERVICE[]" multiple="multiple">
							<option
								value='SERVICEPAKKE' <?php if (in_array('SERVICEPAKKE', $ArrExlode)) echo 'selected="selected"'; ?>>
								SERVICEPAKKE
							</option>
							<option
								value='A-POST'    <?php if (in_array('A-POST', $ArrExlode)) echo 'selected="selected"'; ?>>
								A-POST
							</option>
							<option
								value='B-POST'        <?php if (in_array('B-POST', $ArrExlode)) echo 'selected="selected"'; ?>>
								B-POST
							</option>
							<option
								value='PA_DOREN'    <?php if (in_array('PA_DOREN', $ArrExlode)) echo 'selected="selected"'; ?>>
								PA_DOREN
							</option>
							<option
								value='BPAKKE_DOR-DOR'    <?php if (in_array('BPAKKE_DOR-DOR', $ArrExlode)) echo 'selected="selected"'; ?>>
								BPAKKE_DOR-DOR
							</option>
							<option
								value='EKSPRESS09' <?php if (in_array('EKSPRESS09', $ArrExlode)) echo 'selected="selected"'; ?>>
								EKSPRESS09
							</option>
						</select>

					</td>
					<td>
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
			$maincfgfile = JPATH_ROOT . '/plugins/' . $d['plugin'] . '/' . $this->classname . '.cfg.php';

			$my_config_array = array(
				"BRING_USERCODE"              => $d['BRING_USERCODE'],
				"BRING_SERVER"                => $d['BRING_SERVER'],
				"BRING_PATH"                  => $d['BRING_PATH'],
				"BRING_ZIPCODE_FROM"          => $d['BRING_ZIPCODE_FROM'],
				"BRING_PRICE_SHOW_WITHVAT"    => $d['BRING_PRICE_SHOW_WITHVAT'],
				"BRING_PRICE_SHOW_SHORT_DESC" => $d['BRING_PRICE_SHOW_SHORT_DESC'],
				"BRING_PRICE_SHOW_DESC"       => $d['BRING_PRICE_SHOW_DESC'],
				"BRING_USE_SHIPPING_BOX"      => $d['BRING_USE_SHIPPING_BOX'],
				"BRING_SERVICE"               => implode(',', $d['BRING_SERVICE'])

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
		$currency = new CurrencyHelper;
		$redconfig = new Redconfiguration;

		$shipping = $shippinghelper->getShippingMethodByClass($this->classname);
		$shippingcfg = JPATH_ROOT . '/plugins/' . $shipping->folder . '/' . $shipping->element . '.cfg.php';
		include_once $shippingcfg;

		$shippingrate = array();
		$rate = 0;

		// conversation of weight ( ration )
		$unitRatio = $producthelper->getUnitConversation('gram', DEFAULT_WEIGHT_UNIT);
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

		if (BRING_USE_SHIPPING_BOX == '1')
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

		$shipping_length = $totaldimention['totallength'];
		$shipping_height = $totaldimention['totalheight'];
		$shipping_width = $totaldimention['totalwidth'];
		$shipping_volume = $totaldimention['totalvolume'];

		if (is_array($whereShippingBoxes) && count($whereShippingBoxes) > 0 && $unitRatioVolume > 0)
		{
			$shipping_length = ( int ) ($whereShippingBoxes['box_length'] * $unitRatioVolume);
			$shipping_width = ( int ) ($whereShippingBoxes['box_width'] * $unitRatioVolume);
			$shipping_height = ( int ) ($whereShippingBoxes['box_height'] * $unitRatioVolume);
		}

		if (isset($shippinginfo->country_code))
		{
			$shippinginfo->country_2_code = $redconfig->getCountryCode2($shippinginfo->country_code);
		}

		$dest_zip = substr($shippinginfo->zipcode, 0, 5);

		//Determine weight in pounds and ounces
		$shipping_gram = floor($order_weight); //send integer rounded down		//end cw733 fix

		// weightInGrams=1500&from=7600&to=1407&length=30&width=40&height=40&volume=33&date=2009-2-3
		$query = '';
		$query .= 'from=' . BRING_ZIPCODE_FROM;
		$query .= '&to=' . $dest_zip;

		if (!$shipping_length)
		{
			$shipping_length = 1;
		}

		if (!$shipping_width)
		{
			$shipping_width = 1;
		}

		if (!$shipping_height)
		{
			$shipping_height = 1;
		}

		if (!$shipping_volume)
		{
			$shipping_volume = 1;
		}

		if ($shipping_gram)
		{
			$query .= '&weightInGrams=' . $shipping_gram;
		}

		$query .= '&length=' . $shipping_length;
		$query .= '&width=' . $shipping_width;
		$query .= '&height=' . $shipping_height;
		$query .= '&volume=' . $shipping_volume;

		// Using cURL is Up-To-Date and easier!!
		if (function_exists("curl_init"))
		{
			$myfile = "http://" . BRING_SERVER . BRING_PATH . "?" . $query;

			$CR = curl_init($myfile);
			curl_setopt($CR, CURLOPT_HEADER, 0);
			curl_setopt($CR, CURLOPT_HTTPGET, 1);
			curl_setopt($CR, CURLOPT_RETURNTRANSFER, 1);

			$xmlResult = curl_exec($CR);
			$error = curl_error($CR);

			if (!empty($error))
			{
				$html = BRING_RESPONSE_ERROR;

				return $shippingrate;
			}
			else
			{
				$xmlDoc = JFactory::getXMLParser('Simple');
				$xmlDoc->loadString($xmlResult);
			}
			curl_close($CR);
		}
		//Get shipping options that are selected as available in VM from XML response
		$bring_products = array();
		$document = $xmlDoc->document;

		if (!$document)
			return $shippingrate;
		$product = $document->children();
		$shippingError = false;

		for ($i = 0; $i < count($product); $i++)
		{
			$bring_products[$i] = new stdClass;

			if (strtolower($product[$i]->name()) == 'head')
			{
//				$headchilds = $product[$i]->children();
				$shippingError = true;
			}

			if (strtolower($product[$i]->name()) == 'body')
			{
//				$bodychilds = $product[$i]->children();
//				$html .= "<br />".JText::_('COM_REDSHOP_NORWEGIAN_ERROR_MESSAGE')."<br />";
			}

			if (strtolower($product[$i]->name()) == 'product')
			{
				$productchilds = $product[$i]->children();

				for ($j = 0; $j < count($productchilds); $j++)
				{
					if (strtolower($productchilds[$j]->name()) == "productid" || strtolower($productchilds[$j]->name()) == "guiinformation")
					{
						if (strtolower($productchilds[$j]->name()) == "productid")
						{
							$product_name = $productchilds[$j]->data();
							$bring_products[$i]->product->product_id = $product_name;
						}

						$productDisplayName = "";

						if (isset($productchilds[$j]->ProductName[0]))
						{
							$productDisplayName = $productchilds[$j]->ProductName[0]->data();
							//$productdescriptiontext = $productchilds[$j]->ProductName[0]->descriptiontext
						}

						if ($productDisplayName)
						{
							$bring_products[$i]->product->product_name = $productDisplayName; //." ( ".$product_name." )";
						}
						else
						{
							$bring_products[$i]->product->product_name = $product_name;
						}

						if (isset($productchilds[$j]->DescriptionText[0]))
						{
							$bring_products[$i]->product->product_desc = $productchilds[$j]->DescriptionText[0]->data();
						}

						if (isset($productchilds[$j]->HelpText[0]))
						{
							$bring_products[$i]->product->product_desc1 = $productchilds[$j]->HelpText[0]->data();
						}
					}

					if (strtolower($productchilds[$j]->name()) == 'price')
					{
						$price = $productchilds[$j];
						$priceAttribute = $productchilds[$j]->attributes();
						$currencyidentificationcode = $priceAttribute['currencyidentificationcode'];
						$bring_products[$i]->product->currencyidentificationcode = $currencyidentificationcode;

						$priceChilds = $price->children();
						$PackagePriceWithoutAdditionalServices = $priceChilds[0]->children();

						$AmountWithoutVAT = $PackagePriceWithoutAdditionalServices[0]->data();
						$bring_products[$i]->product->AmountWithoutVAT = $AmountWithoutVAT;

						$VAT = $PackagePriceWithoutAdditionalServices[1]->data();
						$bring_products[$i]->product->VAT = $VAT;

						$AmountWithVAT = $PackagePriceWithoutAdditionalServices[2]->data();
						$bring_products[$i]->product->AmountWithVAT = $AmountWithVAT;
					}

					if (strtolower($productchilds[$j]->name()) == 'expecteddelivery')
					{
						//WorkingDays
						$WorkingDays = $productchilds[$j]->WorkingDays[0]->data();
						$bring_products[$i]->product->delivery = $WorkingDays;
					}
				}
			}

			if (strtolower($product[$i]->name()) == 'tracemessages')
			{
				$TraceMessageschilds = $product[$i]->children();
				$bring_products[$i]->TraceMessages->Message = array();

				for ($j = 0; $j < count($TraceMessageschilds); $j++)
				{
					$Message = $TraceMessageschilds[$j]->data();
					$bring_products[$i]->TraceMessages->Message[$j] = $Message;
				}
			}
		}

		//Finally, write out the shipping options
		$i = 0;
		//If no shipping options available send standard shipping options if turned on in config
		if (!$shippingError)
		{
			$ArrExlode = explode(',', BRING_SERVICE);

			for ($i = 0; $i < count($bring_products); $i++)
			{
				$product_id = $bring_products[$i]->product->product_id;

				if (($i != count($bring_products) - 1) && in_array($product_id, $ArrExlode))
				{
					$AmountWithoutVAT = $bring_products[$i]->product->AmountWithoutVAT;
					$AmountVAT = $bring_products[$i]->product->VAT;
					$product_name = $bring_products[$i]->product->product_name;
					$currencyidentificationcode = $bring_products[$i]->product->currencyidentificationcode;
					$delivery = $bring_products[$i]->product->delivery;
					// convert NOK currency to site currency
					$Displaycost = $currency->convert($AmountWithoutVAT, '', $currencyidentificationcode);
					$cost = $currency->convert($AmountWithoutVAT, '', $currencyidentificationcode);
					$vat = $currency->convert($AmountVAT, '', $currencyidentificationcode);

					if (BRING_PRICE_SHOW_WITHVAT)
					{
						$Displaycost = $currency->convert($Amountwithvat, '', $currencyidentificationcode);
					}

					$shipping_rate_id = $shippinghelper->encryptShipping(__CLASS__ . "|" . $shipping->name . "|" . $product_name . "|" . number_format($cost + $vat, 2, '.', '') . "|" . $product_name . "|single|0");

					$shippingrate[$rate]->text = $product_name; //." ".JText::_('COM_REDSHOP_DELIVERY')." ".$delivery;
					$shippingrate[$rate]->value = $shipping_rate_id;
					$shippingrate[$rate]->rate = $Displaycost;
					$shippingrate[$rate]->vat = $vat;
					$shippingrate[$rate]->shortdesc = (BRING_PRICE_SHOW_SHORT_DESC) ? $bring_products[$i]->product->product_desc : '';
					$shippingrate[$rate]->desc = (BRING_PRICE_SHOW_DESC) ? $bring_products[$i]->product->product_desc1 : '';
					$rate++;
				}
//				else
//				{
//					$Message	=	$bring_products[$i]->TraceMessages->Message;
//					for ($k=0;$k<count($Message);$k++)
//					{
//						$html .= "<strong>(".$Message[$k].")</strong>";
//						$html .= "<br />";
//					}
//				}
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

?>