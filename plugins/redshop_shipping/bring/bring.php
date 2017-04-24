<?php
/**
 * @package     RedSHOP
 * @subpackage  Plugin
 *
 * @copyright   Copyright (C) 2008 - 2015 redCOMPONENT.com. All rights reserved.
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

JLoader::import('redshop.library');

/**
 * Class Plgredshop_Shippingbring
 *
 * @since  1.5
 */
class Plgredshop_Shippingbring extends JPlugin
{
	public $payment_code = "bring";

	public $classname = "bring";

	/**
	 * Constructor
	 *
	 * @param   object &$subject   The object to observe
	 * @param   array  $config     An optional associative array of configuration settings.
	 *                             Recognized key values include 'name', 'group', 'params', 'language'
	 *                             (this list is not meant to be comprehensive).
	 */
	public function __construct(&$subject, $config = array())
	{
		$lang = JFactory::getLanguage();
		$lang->load('plg_redshop_shipping_bring', JPATH_ADMINISTRATOR);

		parent::__construct($subject, $config);
	}

	/**
	 * onShowconfig
	 *
	 * @param   object $ps Values
	 *
	 * @return bool
	 */
	public function onShowconfig($ps)
	{
		if ($ps->element == $this->classname)
		{
			$ArrExlode    = explode(',', BRING_SERVICE);
			$bringService = array(
				'SERVICEPAKKE'   => 'SERVICEPAKKE',
				'A-POST'         => 'A-POST',
				'B-POST'         => 'B-POST',
				'PA_DOREN'       => 'PA_DOREN',
				'BPAKKE_DOR-DOR' => 'BPAKKE_DOR-DOR',
				'EKSPRESS09'     => 'EKSPRESS09'
			);
			?>
            <table class="admintable table table-striped">
                <tr class="row0">
                    <td><strong><?php echo JText::_('PLG_REDSHOP_SHIPPING_BRING_USERCODE_LBL') ?></strong></td>
                    <td><input type="text" name="BRING_USERCODE" class="inputbox" value="<?php echo BRING_USERCODE ?>"/>
                    </td>
                </tr>
                <tr class="row1">
                    <td><strong><?php echo JText::_('PLG_REDSHOP_SHIPPING_BRING_SERVER_LBL') ?></strong></td>
                    <td><input type="text" name="BRING_SERVER" class="inputbox" value="<?php echo BRING_SERVER ?>"/>
                    </td>
                </tr>
                <tr class="row1">
                    <td><strong><?php echo JText::_('PLG_REDSHOP_SHIPPING_BRING_PATH_LBL') ?></strong></td>
                    <td><input type="text" name="BRING_PATH" class="inputbox" value="<?php echo BRING_PATH ?>"/></td>
                </tr>
                <tr class="row1">
                    <td><strong><?php echo JText::_('PLG_REDSHOP_SHIPPING_BRING_ZIPCODE_FROM_LBL') ?></strong></td>
                    <td><input type="text" name="BRING_ZIPCODE_FROM" class="inputbox"
                               value="<?php echo BRING_ZIPCODE_FROM ?>"/></td>
                </tr>
                <tr class="row1">
                    <td><strong><?php echo JText::_('PLG_REDSHOP_SHIPPING_BRING_PRICE_SHOW_WITHVAT_LBL') ?></strong></td>
                    <td>
						<?php echo JHtml::_('redshopselect.booleanlist', 'BRING_PRICE_SHOW_WITHVAT', '', BRING_PRICE_SHOW_WITHVAT); ?>
                    </td>
                </tr>
                <tr class="row1">
                    <td><strong><?php echo JText::_('PLG_REDSHOP_SHIPPING_BRING_PRICE_SHOW_SHORT_DESC_LBL') ?></strong></td>
                    <td>
						<?php echo JHtml::_('redshopselect.booleanlist', 'BRING_PRICE_SHOW_SHORT_DESC', '', BRING_PRICE_SHOW_SHORT_DESC); ?>
                    </td>
                </tr>
                <tr class="row1">
                    <td><strong><?php echo JText::_('PLG_REDSHOP_SHIPPING_BRING_PRICE_SHOW_DESC_LBL') ?></strong></td>
                    <td>
						<?php echo JHtml::_('redshopselect.booleanlist', 'BRING_PRICE_SHOW_DESC', '', BRING_PRICE_SHOW_DESC); ?>
                    </td>
                </tr>
                <tr class="row1">
                    <td>
                        <strong><?php echo JText::_('PLG_REDSHOP_SHIPPING_BRING_USE_SHIPPING_BOX_LBL') ?></strong></td>
                    <td>
						<?php echo JHtml::_('redshopselect.booleanlist', 'BRING_USE_SHIPPING_BOX', '', BRING_USE_SHIPPING_BOX); ?>
                    </td>
                </tr>
                <tr class="row1">
                    <td>
                        <strong><?php echo JText::_('PLG_REDSHOP_SHIPPING_BRING_SERVICE_LBL') ?></strong></td>
                    <td>
						<?php echo JHtml::_('select.genericlist', $bringService, 'BRING_SERVICE[]', 'multiple="multiple"', 'value', 'text', $ArrExlode); ?>
                    </td>
                </tr>
            </table>
			<?php
			return true;
		}
	}

	/**
	 * onWriteconfig
	 *
	 * @param   array $d Values
	 *
	 * @return bool
	 */
	public function onWriteconfig($d)
	{
		if ($d['element'] == $this->classname)
		{
			$maincfgfile = JPATH_ROOT . '/plugins/' . $d['plugin'] . '/' . $this->classname . '/' . $this->classname . '.cfg.php';

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

	/**
	 * onListRates
	 *
	 * @param   array &$d Values
	 *
	 * @return array
	 */
	public function onListRates(&$d)
	{
		$shippinghelper = shipping::getInstance();
		$producthelper  = productHelper::getInstance();
		$redconfig      = Redconfiguration::getInstance();

		$shipping = $shippinghelper->getShippingMethodByClass($this->classname);
		include_once JPATH_ROOT . '/plugins/redshop_shipping/' . $this->classname . '/' . $this->classname . '.cfg.php';

		$shippingrate = array();
		$rate         = 0;

		// Conversation of weight ( ration )
		$unitRatio       = $producthelper->getUnitConversation('gram', Redshop::getConfig()->get('DEFAULT_WEIGHT_UNIT'));
		$unitRatioVolume = $producthelper->getUnitConversation('inch', Redshop::getConfig()->get('DEFAULT_VOLUME_UNIT'));

		$totaldimention = $shippinghelper->getCartItemDimention();
		$order_weight   = $totaldimention['totalweight'];

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

		if (BRING_USE_SHIPPING_BOX == '1')
		{
			$whereShippingBoxes = $shippinghelper->getBoxDimensions($d['shipping_box_id']);
		}
		else
		{
			$whereShippingBoxes               = array();
			$productData                      = $shippinghelper->getProductVolumeShipping();
			$whereShippingBoxes['box_length'] = $productData[2]['length'];
			$whereShippingBoxes['box_width']  = $productData[1]['width'];
			$whereShippingBoxes['box_height'] = $productData[0]['height'];
		}

		$shipping_length = $totaldimention['totallength'];
		$shipping_height = $totaldimention['totalheight'];
		$shipping_width  = $totaldimention['totalwidth'];
		$shipping_volume = $totaldimention['totalvolume'];

		if (is_array($whereShippingBoxes) && count($whereShippingBoxes) > 0 && $unitRatioVolume > 0)
		{
			$shipping_length = (int) ($whereShippingBoxes['box_length'] * $unitRatioVolume);
			$shipping_width  = (int) ($whereShippingBoxes['box_width'] * $unitRatioVolume);
			$shipping_height = (int) ($whereShippingBoxes['box_height'] * $unitRatioVolume);
		}

		if (isset($shippinginfo->country_code))
		{
			$shippinginfo->country_2_code = $redconfig->getCountryCode2($shippinginfo->country_code);
		}

		$dest_zip = substr($shippinginfo->zipcode, 0, 5);

		/* Determine weight in pounds and ounces
		send integer rounded down
		end cw733 fix*/
		$shipping_gram = floor($order_weight);

		// WeightInGrams=1500&from=7600&to=1407&length=30&width=40&height=40&volume=33&date=2009-2-3
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

		$query  .= '&length=' . $shipping_length;
		$query  .= '&width=' . $shipping_width;
		$query  .= '&height=' . $shipping_height;
		$query  .= '&volume=' . $shipping_volume;
		$xmlDoc = false;

		// Using cURL is Up-To-Date and easier!!
		if (function_exists("curl_init"))
		{
			$myfile = "http://" . BRING_SERVER . BRING_PATH . "?" . $query;

			$CR = curl_init($myfile);
			curl_setopt($CR, CURLOPT_HEADER, 0);
			curl_setopt($CR, CURLOPT_HTTPGET, 1);
			curl_setopt($CR, CURLOPT_RETURNTRANSFER, 1);

			$xmlResult = curl_exec($CR);
			$error     = curl_error($CR);

			if (!empty($error))
			{
				return $shippingrate;
			}
			else
			{
				// Disable libxml errors and allow to fetch error information as needed
				libxml_use_internal_errors(true);
				$xmlDoc = simplexml_load_string($xmlResult, 'SimpleXMLElement');
			}

			curl_close($CR);
		}

		// Get shipping options that are selected as available in VM from XML response
		$bring_products = array();

		if ($xmlDoc === false)
		{
			return $shippingrate;
		}

		foreach ($xmlDoc->Product as $oneProduct)
		{
			$bringProduct             = new stdClass;
			$bringProduct->product_id = (string) $oneProduct->ProductId;

			if ((string) $oneProduct->GuiInformation->ProductName)
			{
				$bringProduct->product_name = (string) $oneProduct->GuiInformation->ProductName;
			}
			else
			{
				$bringProduct->product_name = $bringProduct->product_id;
			}

			if ((string) $oneProduct->GuiInformation->DescriptionText)
			{
				$bringProduct->product_desc = (string) $oneProduct->GuiInformation->DescriptionText;
			}

			if ((string) $oneProduct->GuiInformation->HelpText)
			{
				$bringProduct->product_desc1 = (string) $oneProduct->GuiInformation->HelpText;
			}

			$attributePrice                           = $oneProduct->Price->attributes();
			$bringProduct->currencyidentificationcode = (string) $attributePrice['currencyIdentificationCode'];
			$bringProduct->AmountWithoutVAT           = (string) $oneProduct->Price->PackagePriceWithoutAdditionalServices->AmountWithoutVAT;
			$bringProduct->AmountWithVAT              = (string) $oneProduct->Price->PackagePriceWithoutAdditionalServices->AmountWithVAT;
			$bringProduct->VAT                        = (string) $oneProduct->Price->PackagePriceWithoutAdditionalServices->VAT;
			$bringProduct->delivery                   = (string) $oneProduct->ExpectedDelivery->WorkingDays;

			$bring_products[] = $bringProduct;
		}

		$ArrExlode = explode(',', BRING_SERVICE);

		foreach ($bring_products as $bringProduct)
		{
			$product_id = $bringProduct->product_id;

			if (in_array($product_id, $ArrExlode))
			{
				$AmountWithoutVAT           = $bringProduct->AmountWithoutVAT;
				$AmountVAT                  = $bringProduct->VAT;
				$product_name               = $bringProduct->product_name;
				$currencyidentificationcode = $bringProduct->currencyidentificationcode;

				// Convert NOK currency to site currency
				$Displaycost = RedshopHelperCurrency::convert($AmountWithoutVAT, $currencyidentificationcode);
				$cost        = RedshopHelperCurrency::convert($AmountWithoutVAT, $currencyidentificationcode);
				$vat         = RedshopHelperCurrency::convert($AmountVAT, $currencyidentificationcode);

				if (BRING_PRICE_SHOW_WITHVAT)
				{
					$Displaycost = RedshopHelperCurrency::convert($bringProduct->AmountWithVAT, $currencyidentificationcode);
				}

				$shipping_rate_id = RedshopShippingRate::encrypt(
					array(
						__CLASS__,
						$shipping->name,
						$product_name,
						number_format($cost + $vat, 2, '.', ''),
						$product_name,
						'single',
						'0'
					)
				);

				$shippingrate[$rate]            = new stdClass;
				$shippingrate[$rate]->text      = $product_name;
				$shippingrate[$rate]->value     = $shipping_rate_id;
				$shippingrate[$rate]->rate      = $Displaycost;
				$shippingrate[$rate]->vat       = $vat;
				$shippingrate[$rate]->shortdesc = (BRING_PRICE_SHOW_SHORT_DESC) ? $bringProduct->product_desc : '';
				$shippingrate[$rate]->desc      = (BRING_PRICE_SHOW_DESC) ? $bringProduct->product_desc1 : '';
				$rate++;
			}
		}

		return $shippingrate;
	}

	/**
	 * Show all configuration parameters for this Shipping method
	 *
	 * @return  boolean  False when the Shipping method has no configration
	 */
	public function show_configuration()
	{
		return true;
	}
}
