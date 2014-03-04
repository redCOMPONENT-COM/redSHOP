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
if (!defined('_VALID_MOS') && !defined('_JEXEC')) die('Direct Access to ' . basename(__FILE__) . ' is not allowed.');

require_once JPATH_SITE . '/components/com_redshop/helpers/product.php';
require_once JPATH_ADMINISTRATOR . '/components/com_redshop/helpers/configuration.php';
require_once JPATH_ADMINISTRATOR . '/components/com_redshop/helpers/shipping.php';

class plgredshop_shippingshipwire extends JPlugin
{
	var $payment_code = "shipwire";
	var $classname = "shipwire";

	function onShowconfig($ps)
	{
		if ($ps->element == $this->classname)
		{
			?>
			<table class="admintable">
				<tr class="row0">
					<td><strong><?php echo JText::_('COM_REDSHOP_SHIPWIRE_EMAIL_LBL') ?></strong></td>
					<td><input class="inputbox" type="text" name="SHIPWIRE_EMAIL" value="<?php echo SHIPWIRE_EMAIL; ?>">
					</td>
					<td><?php echo JHTML::tooltip(JText::_('COM_REDSHOP_SHIPWIRE_EMAIL_LBL'), JText::_('COM_REDSHOP_SHIPWIRE_EMAIL_LBL'), 'tooltip.png', '', '', false);?></td>
				</tr>
				<tr class="row0">
					<td><strong><?php echo JText::_('COM_REDSHOP_SHIPWIRE_PASSWORD_LBL') ?></strong></td>
					<td><input class="inputbox" type="text" name="SHIPWIRE_PASSWORD"
					           value="<?php echo SHIPWIRE_PASSWORD; ?>">
					</td>
					<td><?php echo JHTML::tooltip(JText::_('COM_REDSHOP_SHIPWIRE_PASSWORD_LBL'), JText::_('COM_REDSHOP_SHIPWIRE_PASSWORD_LBL'), 'tooltip.png', '', '', false);?></td>
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
			$maincfgfile = JPATH_ROOT . "/plugins/redshop_shipping/$this->classname/$this->classname.cfg.php";

			$my_config_array = array(
				"SHIPWIRE_EMAIL"    => $d['SHIPWIRE_EMAIL'],
				"SHIPWIRE_PASSWORD" => $d['SHIPWIRE_PASSWORD']
				// END CUSTOM CODE
			);

			$config = '';
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
		include_once JPATH_ROOT . '/plugins/redshop_shipping/' . $this->classname . '.cfg.php';
		$shippinghelper = new shipping;
		$producthelper = new producthelper;

		$rate = 0;
		$shipping = $shippinghelper->getShippingMethodByClass($this->classname);

		$shippingrate = array();
		$shippinginfo = $shippinghelper->getShippingAddress($d['users_info_id']);

		if (count($shippinginfo) < 1)
		{
			return $shippingrate;
		}

		$billing = $producthelper->getUserInformation($shippinginfo->user_id);

		if (count($billing) < 1)
		{
			return $shippingrate;
		}

		$session = JFactory::getSession();
		$cart = $session->get('cart');
		$idx = $cart['idx'];
		$Item = '';

		for ($c = 0; $c < $idx; $c++)
		{
			$productData = $producthelper->getProductById($cart[$c]['product_id']);
			$pSKU = $productData->product_number;
			$quntity = $cart[$c]['quantity'];
			$Item .= '<Item num="' . $c . '">';
			$Item = $Item . '<Code>' . $pSKU . '</Code>';
			$Item = $Item . '<Quantity>' . $quntity . '</Quantity>';
			$Item .= '</Item>';
		}

		$xmlPost = '<RateRequest>';
		$xmlPost = $xmlPost . '<EmailAddress>' . SHIPWIRE_EMAIL . '</EmailAddress>'; //vipula@redweb.dk
		$xmlPost = $xmlPost . '<Password>' . SHIPWIRE_PASSWORD . '</Password>'; //vipula123
		$xmlPost .= '<Order>';
		$xmlPost = $xmlPost . '<Warehouse>0</Warehouse>';
		$xmlPost = $xmlPost . '<AddressInfo type="ship">';
		$xmlPost = $xmlPost . '<Address1>' . $shippinginfo->zipcode . '</Address1>'; //2301 East Lamar Blvd
		$xmlPost = $xmlPost . '<Address2></Address2>';
		$xmlPost = $xmlPost . '<City>' . $shippinginfo->city . '</City>'; //Arlington
		$xmlPost = $xmlPost . '<Country>' . $shippinginfo->country_code . '</Country>'; //UK
		$xmlPost = $xmlPost . '<State>' . $shippinginfo->state_code . '</State>'; //CA
		$xmlPost = $xmlPost . '<Zip>' . $shippinginfo->zipcode . '</Zip>'; //TX 76006
		$xmlPost = $xmlPost . '</AddressInfo>';
		$xmlPost .= $Item;
		/*'<Item num="0">';
		$xmlPost = $xmlPost . '<Code>VIPULA-1</Code>';//VIPULA-1
		$xmlPost = $xmlPost . '<Quantity>1</Quantity>';//1
		$xmlPost .=    '</Item>';*/

		$xmlPost .= '</Order>';
		$xmlPost .= '</RateRequest>';

		$shipwireURL = "https://api.shipwire.com/exec/RateServices.php";

		if (function_exists('curl_init'))
		{
			$CR = curl_init();
			curl_setopt($CR, CURLOPT_URL, $shipwireURL); //"?API=RateV2&XML=".$xmlPost);
			curl_setopt($CR, CURLOPT_POST, 1);
			curl_setopt($CR, CURLOPT_FAILONERROR, true);
			curl_setopt($CR, CURLOPT_POSTFIELDS, $xmlPost);
			curl_setopt($CR, CURLOPT_RETURNTRANSFER, 1);
			curl_setopt($CR, CURLOPT_SSL_VERIFYPEER, false);
			$xmlResult = curl_exec($CR);
		}

		$parser = xml_parser_create();
		xml_parse_into_struct($parser, $xmlResult, $XMLvals, $XMLindex);
		xml_parser_free($parser);

		$status = $this->getElementValue($XMLvals, 'Status');

		if ($status[0] == 'OK')
		{
			$service = $this->getElementValue($XMLvals, 'service');
			$min = $this->getElementValue($XMLvals, 'MINIMUM');
			$max = $this->getElementValue($XMLvals, 'MAXIMUM');

			$values = $XMLvals;

			for ($i = 0; $i < count($XMLvals); $i++)
			{
				if (isset($XMLvals[$i]['attributes']))
				{
					$parent = $XMLvals[$i]['tag'];

					$keys = array_keys($XMLvals[$i]['attributes']);

					for ($z = 0; $z < count($keys); $z++)
					{
						$content[$parent][$i][$keys[$z]] = $XMLvals[$i]['attributes'][$keys[$z]];

						if (isset($content[$parent][$i]['VALUE'])) $content[$parent][$i]['VALUE'] = $XMLvals[$i]['value'];
					}
				}
			}

			foreach ($content as $key => $XMLvals)
			{
				$content[$key] = array_values($content[$key]);
			}

			$j = 1;
			$s = 0;
			$t = 0;

			if (CURRENCY_CODE != "")
			{
				$currency_main = CURRENCY_CODE;
			}
			else
			{
				$currency_main = "USD";
			}

			$currencyClass = new CurrencyHelper;

			for ($i = 0; $i < count($content['COST']); $i++)
			{
				$currency = $content['COST'][$i]['CURRENCY'];
				$originalcost = $content['COST'][$i]['ORIGINALCOST'];

				if ($currency_main == $currency)
				{
					$originalcost = $currencyClass->convert($originalcost, '', $currency_main);
				}

				$method = $content['QUOTE'][$s]['METHOD'];
				$serviceName = $service[$s];
				$minDays = $min[$s];
				$maxDays = $max[$s];

				if (($j % 4) == 0)
				{
					$s++;
				}

				if (($j % 4) == 1)
				{
					$type = 'Normal';
					$t--;
				}
				else
				{
					$type = $content['SUBTOTAL'][$t]['TYPE'];
				}

				$rateName = $method . ' ' . $serviceName . ' ' . $type;
				$shipping_rate_id = $shippinghelper->encryptShipping(__CLASS__ . "|" . $shipping->name . "|" . $rateName . "|" . number_format($originalcost, 2, '.', '') . "|" . $rateName . "|single|0");
				$shippingrate[$i]->text = $method . ' <b>' . $serviceName . '</b> ' . $type;
				$shippingrate[$i]->value = $shipping_rate_id;
				$shippingrate[$i]->rate = $originalcost;
				$shippingrate[$i]->vat = 0;
				$j++;
				$t++;
			}
		}

		return $shippingrate;
	}

	function getElementValue($XMLvals, $elName)
	{
		$elValue = null;

		foreach ($XMLvals as $arrkey => $arrvalue)
		{
			foreach ($arrvalue as $key => $value)
			{
				if ($value == strtoupper($elName))
				{
					$elValue[] = $arrvalue['value'];
				}
			}
		}

		return $elValue;
	}
}

?>