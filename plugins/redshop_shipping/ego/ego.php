<?php
/**
 * Written by Andrew Meaden and Noven Purnell-Webb of MageData
 * Based on RedComponents Aus Post code - thanks guys :)
 * @license GNU/GPL, see license.txt or http://www.gnu.org/copyleft/gpl.html
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

class plgredshop_shippingego extends JPlugin
{
	var $payment_code = "ego";
	var $classname = "ego";

	function onShowconfig($ps)
	{
		if ($ps->element == $this->classname)
		{
			?>
			<table class="admintable">
				<tr class="row0">
					<td><strong><?php echo JText::_('COM_REDSHOP_EGO_PICKUPZIPCODE_LBL') ?></strong></td>
					<td><input class="inputbox" type="text" name="EGO_PICKUPZIPCODE"
					           value="<?php echo EGO_PICKUPZIPCODE; ?>">
					</td>
					<td><?php echo JHTML::tooltip(JText::_('COM_REDSHOP_EGO_PICKUPZIPCODE_LBL'), JText::_('COM_REDSHOP_EGO_PICKUPZIPCODE_LBL'), 'tooltip.png', '', '', false);?></td>
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
				"EGO_PICKUPZIPCODE" => $d['EGO_PICKUPZIPCODE']
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

		// Grab out the product dimensions from Redshop
		$totaldimention = $shippinghelper->getCartItemDimention();

		$unitRatio = $producthelper->getUnitConversation('pounds', DEFAULT_WEIGHT_UNIT);
		$unitRatioVolume = $producthelper->getUnitConversation('inch', DEFAULT_VOLUME_UNIT);

		$carttotalQnt = $totaldimention['totalquantity'];

		if ($unitRatio != 0)
		{
			$carttotalWeight = $carttotalWeight * $unitRatio; // Converting weight in pounds
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
			$carttotalLength = ( int ) ($whereShippingBoxes['box_length'] * $unitRatioVolume);
			$carttotalWidth = ( int ) ($whereShippingBoxes['box_width'] * $unitRatioVolume);
			$carttotalHeight = ( int ) ($whereShippingBoxes['box_height'] * $unitRatioVolume);
		}
		else
		{
			return $shippingrate;
		}

		if ($carttotalWeight > 0)
		{
			$shippinginfo = $shippinghelper->getShippingAddress($d['users_info_id']);

			if (count($shippinginfo) < 1)
			{
				return $shippingrate;
			}

			$query = "";
			$query .= 'pickup=' . EGO_PICKUPZIPCODE;
			$query .= '&delivery=' . $shippinginfo->zipcode;
			$query .= '&weight=' . $carttotalWeight;
			$query .= '&depth=' . $carttotalLength;
			$query .= '&width=' . $carttotalWidth;
			$query .= '&height=' . $carttotalHeight;
			$query .= '&type=Carton';
			$query .= '&items=' . $carttotalQnt;

			$myfile = 'http://www.e-go.com.au/calculatorAPI?' . $query;

			// Wrapped in a big if (false) block

			$ego_quote = file($myfile);

			foreach ($ego_quote as $num => $quote)
			{
				$quote = trim($quote);
				$quote_field = explode("=", $quote);

				if ($quote_field[0] == "error")
				{
					$error = $quote_field[1];
				}
				else if ($quote_field[0] == "eta")
				{
					$days[] = $quote_field[1];
				}
				else if ($quote_field[0] == "price")
				{
					$charge[] = $quote_field[1];
				}
			}

			if ($error != "OK")
			{
				echo JText::_('COM_REDSHOP_NOT_GET_SHIPPING_RATES');
				error_log("Unable to determine shipping rate!");

				return $shippingrate;
			}

			for ($i = 0; $i < count($charge); $i++)
			{
				$rs = $charge[$i];
				$shipping_rate_id = $shippinghelper->encryptShipping(__CLASS__ . "|" . $shipping->name . "|" . $shipping->name . "|" . number_format($charge[$i], 2, '.', '') . "|" . $shipping->name . "|single|0");

				$shippingrate[$rate]->text = $days[$i] . JText::_('COM_REDSHOP_DAYS');
				$shippingrate[$rate]->value = $shipping_rate_id;
				$shippingrate[$rate]->rate = $charge[$i];
				$shippingrate[$rate]->vat = 00;
				$rate++;
			}
		}

		return $shippingrate;
	}
}
