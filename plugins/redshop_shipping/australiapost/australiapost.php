<?php
/**
 * @copyright Copyright (C) 2010 redCOMPONENT.com. All rights reserved.
 * @license   GNU/GPL, see license.txt or http://www.gnu.org/copyleft/gpl.html
 *            Developed by email@recomponent.com - redCOMPONENT.com
 *
 * redSHOP can be downloaded from www.redcomponent.com
 * redSHOP is free software; you can redistribute it and/or
 * modify it under the terms of the GNU General Public License 2
 * as published by the Free Software Foundation.
 *
 * You should have received a copy of the GNU General Public License
 * along with redSHOP; if not, write to the Free Software
 * Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston, MA  02110-1301, USA.
 */
// No direct access
defined('_JEXEC') or die;

jimport('joomla.plugin.plugin');

/**
 * Joomla! System Logging Plugin
 *
 * @package        Joomla
 * @subpackage     System
 */
if (!defined('_VALID_MOS') && !defined('_JEXEC')) die('Direct Access to ' . basename(__FILE__) . ' is not allowed.');

require_once JPATH_SITE . DS . 'components' . DS . 'com_redshop' . DS . 'helpers' . DS . 'product.php';
require_once JPATH_ADMINISTRATOR . DS . 'components' . DS . 'com_redshop' . DS . 'helpers' . DS . 'configuration.php';
require_once JPATH_ADMINISTRATOR . DS . 'components' . DS . 'com_redshop' . DS . 'helpers' . DS . 'shipping.php';

class plgredshop_shippingaustraliapost extends JPlugin
{
	var $payment_code = "australiapost";
	var $classname = "australiapost";

	function onShowconfig($ps)
	{
		if ($ps->element == $this->classname)
		{
			?>
			<table class="admintable">
				<tr class="row0">
					<td><strong><?php echo JText::_('COM_REDSHOP_AUSTRALIAPOST_AUSEVICETYPE_LBL') ?></strong></td>
					<td><select class="inputbox" name="AUSTRALIAPOST_AUSEVICETYPE">
							<option <?php if (AUSTRALIAPOST_AUSEVICETYPE == "Standard") echo "selected=\"selected\"" ?>
								value="Standard"><?php echo JText::_('COM_REDSHOP_AUS_STANDARD') ?></option>
							<option <?php if (AUSTRALIAPOST_AUSEVICETYPE == "Express") echo "selected=\"selected\"" ?>
								value="Express"><?php echo JText::_('COM_REDSHOP_AUS_EXPRESS') ?></option>
							<option <?php if (AUSTRALIAPOST_AUSEVICETYPE == "Air") echo "selected=\"selected\"" ?>
								value="Air"><?php echo JText::_('COM_REDSHOP_AUS_AIR') ?></option>
							<option <?php if (AUSTRALIAPOST_AUSEVICETYPE == "Sea") echo "selected=\"selected\"" ?>
								value="Sea"><?php echo JText::_('COM_REDSHOP_AUS_SEA') ?></option>
							<option <?php if (AUSTRALIAPOST_AUSEVICETYPE == "Economy") echo "selected=\"selected\"" ?>
								value="Economy"><?php echo JText::_('COM_REDSHOP_AUS_ECONOMY') ?></option>
						</select>
					</td>
					<td><?php echo JHTML::tooltip(JText::_('COM_REDSHOP_AUSTRALIAPOST_AUSEVICETYPE_LBL'), JText::_('COM_REDSHOP_AUSTRALIAPOST_AUSEVICETYPE_LBL'), 'tooltip.png', '', '', false);?></td>
				</tr>
				<tr class="row0">
					<td><strong><?php echo JText::_('COM_REDSHOP_AUSTRALIAPOST_SEVICETYPE_LBL') ?></strong></td>
					<td><select class="inputbox" name="AUSTRALIAPOST_SEVICETYPE">
							<option <?php if (AUSTRALIAPOST_SEVICETYPE == "Standard") echo "selected=\"selected\"" ?>
								value="Standard"><?php echo JText::_('COM_REDSHOP_AUS_STANDARD') ?></option>
							<option <?php if (AUSTRALIAPOST_SEVICETYPE == "Express") echo "selected=\"selected\"" ?>
								value="Express"><?php echo JText::_('COM_REDSHOP_AUS_EXPRESS') ?></option>
							<option <?php if (AUSTRALIAPOST_SEVICETYPE == "Air") echo "selected=\"selected\"" ?>
								value="Air"><?php echo JText::_('COM_REDSHOP_AUS_AIR') ?></option>
							<option <?php if (AUSTRALIAPOST_SEVICETYPE == "Sea") echo "selected=\"selected\"" ?>
								value="Sea"><?php echo JText::_('COM_REDSHOP_AUS_SEA') ?></option>
							<option <?php if (AUSTRALIAPOST_SEVICETYPE == "Economy") echo "selected=\"selected\"" ?>
								value="Economy"><?php echo JText::_('COM_REDSHOP_AUS_ECONOMY') ?></option>
						</select>
					</td>
					<td><?php echo JHTML::tooltip(JText::_('COM_REDSHOP_AUSTRALIAPOST_SEVICETYPE_LBL'), JText::_('COM_REDSHOP_AUSTRALIAPOST_SEVICETYPE_LBL'), 'tooltip.png', '', '', false);?></td>
				</tr>
				<tr class="row0">
					<td><strong><?php echo JText::_('COM_REDSHOP_AUSTRALIAPOST_PICKUPZIPCODE_LBL') ?></strong></td>
					<td><input class="inputbox" type="text" name="AUSTRALIAPOST_PICKUPZIPCODE"
					           value="<?php echo AUSTRALIAPOST_PICKUPZIPCODE; ?>">
					</td>
					<td><?php echo JHTML::tooltip(JText::_('COM_REDSHOP_AUSTRALIAPOST_PICKUPZIPCODE_LBL'), JText::_('COM_REDSHOP_AUSTRALIAPOST_PICKUPZIPCODE_LBL'), 'tooltip.png', '', '', false);?></td>
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
			$maincfgfile = JPATH_ROOT . DS . 'plugins' . DS . $d['plugin'] . DS . $this->classname . '.cfg.php';

			$my_config_array = array(
				"AUSTRALIAPOST_AUSEVICETYPE"  => $d['AUSTRALIAPOST_AUSEVICETYPE'],
				"AUSTRALIAPOST_SEVICETYPE"    => $d['AUSTRALIAPOST_SEVICETYPE'],
				"AUSTRALIAPOST_PICKUPZIPCODE" => $d['AUSTRALIAPOST_PICKUPZIPCODE']
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
		include_once (JPATH_ROOT . DS . 'plugins' . DS . 'redshop_shipping' . DS . $this->classname . '.cfg.php');
		$shippinghelper = new shipping;
		$producthelper = new producthelper;
		$redconfig = new Redconfiguration;

		$shipping = $shippinghelper->getShippingMethodByClass($this->classname);

		$shippingrate = array();
		$rate = 0;

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

		// Conversation of weight ( ration )
		$volRatio = $producthelper->getUnitConversation('mm', DEFAULT_VOLUME_UNIT);
		$unitRatio = $producthelper->getUnitConversation('gram', DEFAULT_WEIGHT_UNIT);

		$totaldimention = $shippinghelper->getCartItemDimention();
		$carttotalQnt = $totaldimention['totalquantity'];
		$carttotalWeight = $totaldimention['totalweight'];

		if (is_array($whereShippingBoxes) && count($whereShippingBoxes) > 0 && $volRatio > 0)
		{
			$carttotalLength = $whereShippingBoxes['box_length'] * $volRatio;
			$carttotalWidth = $whereShippingBoxes['box_width'] * $volRatio;
			$carttotalHeight = $whereShippingBoxes['box_height'] * $volRatio;
		}
		else
		{
			return $shippingrate;
		}

		// Check for not zero
		if ($unitRatio != 0)
		{
			$carttotalWeight = $carttotalWeight * $unitRatio; // Converting weight in kg
		}

		if ($carttotalWeight > 0)
		{
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

			if (isset($shippinginfo->country_code))
			{
				$shippinginfo->country_2_code = $redconfig->getCountryCode2($shippinginfo->country_code);
			}

			if (isset($billing->country_code))
			{
				$billing->country_2_code = $redconfig->getCountryCode2($billing->country_code);
			}

			$itemparams = new JRegistry($shipping->params);
			$australiapost_servicetype = 'Air';
			$australiapost_auservicetype = 'Standard';

			if ($itemparams->get("australiapost_servicetype"))
			{
				$australiapost_servicetype = $itemparams->get("australiapost_servicetype");
			}

			if ($itemparams->get("australiapost_auservicetype"))
			{
				$australiapost_auservicetype = $itemparams->get("australiapost_auservicetype");
			}

			$query = "";

			if ($shippinginfo->country_2_code == "AU")
			{
				$query .= 'Pickup_Postcode=' . AUSTRALIAPOST_PICKUPZIPCODE; //$billing->zipcode;
				$query .= '&Destination_Postcode=' . $shippinginfo->zipcode;
				$query .= '&Service_Type=' . strtoupper($australiapost_auservicetype); //"Standard"
			}
			else
			{
				$query .= 'Service_Type=' . strtoupper($australiapost_servicetype); // "Express", "Air", "Sea", or "Economy"
			}

			$query .= '&Country=' . $shippinginfo->country_2_code;
			$query .= '&Weight=' . $carttotalWeight;
			$query .= '&Length=' . $carttotalLength;
			$query .= '&Width=' . $carttotalWidth;
			$query .= '&Height=' . $carttotalHeight;
			$query .= '&Quantity=' . $carttotalQnt;

			$myfile = 'http://drc.edeliver.com.au/ratecalc.asp?' . $query;

			/******START OF DOMESTIC/INTERNATIONAL RATE******/
			// Using cURL is Up-To-Date and easier!!
			$error = "";
			$charge = array();
			$days = array();

			if (function_exists("curl_init"))
			{
				$CR = curl_init();
				curl_setopt($CR, CURLOPT_URL, $myfile);
				curl_setopt($CR, CURLOPT_TIMEOUT, 30);
				curl_setopt($CR, CURLOPT_FAILONERROR, true);

				if ($query)
				{
					curl_setopt($CR, CURLOPT_POSTFIELDS, $query);
					curl_setopt($CR, CURLOPT_POST, 1);
				}
				curl_setopt($CR, CURLOPT_RETURNTRANSFER, 1);
				$result = curl_exec($CR);
				$error = curl_error($CR);
				curl_close($CR);

				if (!$result)
				{
//					echo JText::_('COM_REDSHOP_NOT_GET_SHIPPING_RATES');
					return $shippingrate;
				}

				if ($result)
				{
					$res = explode("charge=", $result);

					if (count($res) > 0)
					{
						$res1 = explode("days=", $res[1]);
						$charge[] = $res1[0];
						$res2 = explode("err_msg=", $res1[1]);
						$days[] = $res2[0];
						$error = trim(strtolower($res2[1]));
					}
				}
			}
			else
			{
				$myfile = file('http://drc.edeliver.com.au/ratecalc.asp?' . $query);

				if (count($myfile) > 0)
				{
					$result = explode("charge=", $myfile[0]);

					if (count($result) > 1)
					{
						$charge[] = $result[1];
					}

					$daysarr = explode("days=", $myfile[1]);

					if (count($daysarr) > 1)
					{
						$days[] = $daysarr[1];
					}

					$error = explode("err_msg=", $myfile[2]);

					if (count($error) > 1)
					{
						$error = trim(strtolower($error[1]));
					}
				}
			}

			if ($error != "ok")
			{
//				echo JText::_('COM_REDSHOP_NOT_GET_SHIPPING_RATES');
				return $shippingrate;
			}
			/******END OF DOMESTIC/INTERNATIONAL RATE******/
			for ($i = 0; $i < count($charge); $i++)
			{
				$rs = $charge[$i];
				$shipping_rate_id = $shippinghelper->encryptShipping(__CLASS__ . "|" . $shipping->name . "|" . $shipping->name . "|" . number_format($charge[$i], 2, '.', '') . "|" . $shipping->name . "|single|0");

				$shippingrate[$rate]->text = $days[$i] . JText::_('COM_REDSHOP_DAYS');
				$shippingrate[$rate]->value = $shipping_rate_id;
				$shippingrate[$rate]->rate = $charge[$i];
				$shippingrate[$rate]->vat = 0;
				$rate++;
			}
		}

		return $shippingrate;
	}
}

?>