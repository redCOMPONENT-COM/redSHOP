<?php
/**
 * @package     RedSHOP.Library
 * @subpackage  Helper
 *
 * @copyright   Copyright (C) 2008 - 2017 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

defined('_JEXEC') or die;

/**
 * Class Redshop Helper Shipping Tag
 *
 * @since  2.0.7
 */
class RedshopHelperShippingTag
{
	/**
	 * Replace shipping method
	 *
	 * @param   stdClass  $shipping  Shipping data
	 * @param   string    $content   Template content
	 *
	 * @return  string
	 *
	 * @since   2.0.7
	 */
	public static function replaceShippingMethod($shipping, $content = "")
	{
		$search = array('{shipping_method}', '{order_shipping}', '{shipping_excl_vat}', '{shipping_rate_name}', '{shipping}',
			'{vat_shipping}', '{order_shipping_shop_location}');

		if (!Redshop::getConfig()->getBool('SHIPPING_METHOD_ENABLE') || empty((array) $shipping))
		{
			return str_replace($search, array("", "", "", ""), $content);
		}

		$shippingDetail = RedshopShippingRate::decrypt($shipping->ship_method_id);

		if (count($shippingDetail) <= 1)
		{
			$shippingDetail = explode("|", $shipping->ship_method_id);
		}

		$shippingMethod   = '';
		$shippingRateName = '';

		if (count($shippingDetail) > 0)
		{
			$element = strtolower(str_replace('plgredshop_shipping', '', $shippingDetail[0]));

			// Load language file of the shipping plugin
			JFactory::getLanguage()->load('plg_redshop_shipping_' . $element, JPATH_ADMINISTRATOR);

			// Load language file from plugin folder.
			JFactory::getLanguage()->load('plg_redshop_shipping_' . $element, JPATH_ROOT . '/plugins/redshop_shipping/' . $element);

			if (array_key_exists(1, $shippingDetail))
			{
				$shippingMethod = $shippingDetail[1];
			}

			if (array_key_exists(2, $shippingDetail))
			{
				$shippingRateName = $shippingDetail[2];
			}
		}

		$shopLocation = $shipping->shop_id;

		$replace = array(
			JText::_($shippingMethod),
			RedshopHelperProductPrice::formattedPrice($shipping->order_shipping),
			RedshopHelperProductPrice::formattedPrice($shipping->order_shipping - $shipping->order_shipping_tax),
			JText::_($shippingRateName),
			RedshopHelperProductPrice::formattedPrice($shipping->order_shipping),
			RedshopHelperProductPrice::formattedPrice($shipping->order_shipping_tax)
		);

		// @TODO: Shipping GLS
		if ($shippingDetail[0] != 'plgredshop_shippingdefault_shipping_gls')
		{
			$shopLocation = '';
		}

		$mobiles = array();

		if ($shopLocation)
		{
			$mobiles            = explode('###', $shopLocation);
			$arrLocationDetails = explode('|', $shopLocation);
			$countLocDet        = count($arrLocationDetails);
			$shopLocation       = '';

			if ($countLocDet > 1)
			{
				$shopLocation .= '<b>' . $arrLocationDetails[0] . ' ' . $arrLocationDetails[1] . '</b>';
			}

			if ($countLocDet > 2)
			{
				$shopLocation .= '<br>' . $arrLocationDetails[2];
			}

			if ($countLocDet > 3)
			{
				$shopLocation .= '<br>' . $arrLocationDetails[3];
			}

			if ($countLocDet > 4)
			{
				$shopLocation .= ' ' . $arrLocationDetails[4];
			}

			if ($countLocDet > 5)
			{
				$shopLocation .= '<br>' . $arrLocationDetails[5];
			}

			if ($countLocDet > 6)
			{
				$arrLocationTime = explode('  ', $arrLocationDetails[6]);
				$shopLocation    .= '<br>';

				for ($t = 0, $tn = count($arrLocationTime); $t < $tn; $t++)
				{
					$shopLocation .= $arrLocationTime[$t] . '<br>';
				}
			}
		}

		if (isset($mobiles[1]))
		{
			$shopLocation .= ' ' . $mobiles[1];
		}

		$replace[] = $shopLocation;

		return str_replace($search, $replace, $content);
	}
}
