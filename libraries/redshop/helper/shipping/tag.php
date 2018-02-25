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
	 * @param   stdClass $shipping Shipping data
	 * @param   string   $content  Template content
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
				$locationsTime = explode('  ', $arrLocationDetails[6]);
				$shopLocation .= '<br>';

				foreach ($locationsTime as $locationTime)
				{
					$shopLocation .= $locationTime . '<br>';
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

	/**
	 * Replace Shipping Address
	 *
	 * @param   string  $templateHtml    Template content
	 * @param   object  $shippingAddress Shipping address
	 * @param   boolean $sendMail        Is in send mail
	 *
	 * @return  string
	 * @throws  Exception
	 *
	 * @since   __DEPLOY_VERSION__
	 */
	public static function replaceShippingAddress($templateHtml, $shippingAddress, $sendMail = false)
	{
		if (strpos($templateHtml, '{shipping_address_start}') !== false
			&& strpos($templateHtml, '{shipping_address_end}') !== false)
		{
			$templateStart = explode('{shipping_address_start}', $templateHtml);
			$templateEnd   = explode('{shipping_address_end}', $templateStart[1]);
			$shippingData  = (Redshop::getConfig()->get('SHIPPING_METHOD_ENABLE')) ? $templateEnd[0] : '';

			$shippingExtraField = '';

			if (isset($shippingAddress) && Redshop::getConfig()->get('SHIPPING_METHOD_ENABLE'))
			{
				$extraSection = $shippingAddress->is_company == 1 ?
					RedshopHelperExtrafields::SECTION_COMPANY_SHIPPING_ADDRESS : RedshopHelperExtrafields::SECTION_PRIVATE_SHIPPING_ADDRESS;

				if ($shippingAddress->is_company == 1 && $shippingAddress->company_name != "")
				{
					$shippingData = str_replace("{companyname}", $shippingAddress->company_name, $shippingData);
					$shippingData = str_replace("{companyname_lbl}", JText::_('COM_REDSHOP_COMPANY_NAME'), $shippingData);
				}

				if ($shippingAddress->firstname != "")
				{
					$shippingData = str_replace("{firstname}", $shippingAddress->firstname, $shippingData);
					$shippingData = str_replace("{firstname_lbl}", JText::_('COM_REDSHOP_FIRSTNAME'), $shippingData);
				}

				if ($shippingAddress->lastname != "")
				{
					$shippingData = str_replace("{lastname}", $shippingAddress->lastname, $shippingData);
					$shippingData = str_replace("{lastname_lbl}", JText::_('COM_REDSHOP_LASTNAME'), $shippingData);
				}

				if ($shippingAddress->address != "")
				{
					$shippingData = str_replace("{address}", $shippingAddress->address, $shippingData);
					$shippingData = str_replace("{address_lbl}", JText::_('COM_REDSHOP_ADDRESS'), $shippingData);
				}

				if ($shippingAddress->zipcode != "")
				{
					$shippingData = str_replace("{zip}", $shippingAddress->zipcode, $shippingData);
					$shippingData = str_replace("{zip_lbl}", JText::_('COM_REDSHOP_ZIP'), $shippingData);
				}

				if ($shippingAddress->city != "")
				{
					$shippingData = str_replace("{city}", $shippingAddress->city, $shippingData);
					$shippingData = str_replace("{city_lbl}", JText::_('COM_REDSHOP_CITY'), $shippingData);
				}

				$cname = RedshopHelperOrder::getCountryName($shippingAddress->country_code);

				if ($cname != "")
				{
					$shippingData = str_replace("{country}", JText::_($cname), $shippingData);
					$shippingData = str_replace("{country_lbl}", JText::_('COM_REDSHOP_COUNTRY'), $shippingData);
				}

				$stateName = RedshopHelperOrder::getStateName($shippingAddress->state_code, $shippingAddress->country_code);

				if ($stateName != "")
				{
					$shippingData = str_replace("{state}", $stateName, $shippingData);
					$shippingData = str_replace("{state_lbl}", JText::_('COM_REDSHOP_STATE'), $shippingData);
				}

				if ($shippingAddress->phone != "")
				{
					$shippingData = str_replace("{phone}", $shippingAddress->phone, $shippingData);
					$shippingData = str_replace("{phone_lbl}", JText::_('COM_REDSHOP_PHONE'), $shippingData);
				}

				// Additional functionality - more flexible way
				$shippingData = Redshop\Helper\ExtraFields::displayExtraFields($extraSection, $shippingAddress->users_info_id, "", $shippingData);

				$shippingExtraField = RedshopHelperExtrafields::listAllFieldDisplay($extraSection, $shippingAddress->users_info_id, 1);
			}

			$shippingData = str_replace("{companyname}", "", $shippingData);
			$shippingData = str_replace("{companyname_lbl}", "", $shippingData);
			$shippingData = str_replace("{firstname}", "", $shippingData);
			$shippingData = str_replace("{firstname_lbl}", "", $shippingData);
			$shippingData = str_replace("{lastname}", "", $shippingData);
			$shippingData = str_replace("{lastname_lbl}", "", $shippingData);
			$shippingData = str_replace("{address}", "", $shippingData);
			$shippingData = str_replace("{address_lbl}", "", $shippingData);
			$shippingData = str_replace("{zip}", "", $shippingData);
			$shippingData = str_replace("{zip_lbl}", "", $shippingData);
			$shippingData = str_replace("{city}", "", $shippingData);
			$shippingData = str_replace("{city_lbl}", "", $shippingData);
			$shippingData = str_replace("{country}", "", $shippingData);
			$shippingData = str_replace("{country_lbl}", "", $shippingData);
			$shippingData = str_replace("{state}", "", $shippingData);
			$shippingData = str_replace("{state_lbl}", "", $shippingData);
			$shippingData = str_replace("{phone}", "", $shippingData);
			$shippingData = str_replace("{phone_lbl}", "", $shippingData);
			$shippingData = str_replace("{shipping_extrafield}", $shippingExtraField, $shippingData);

			$templateHtml = $templateStart[0] . $shippingData . $templateEnd[1];
		}
		elseif (strpos($templateHtml, '{shipping_address}') !== false)
		{
			$shipAdd = '';

			if (isset($shippingAddress) && Redshop::getConfig()->getBool('SHIPPING_METHOD_ENABLE'))
			{
				$shippingLayout = 'cart.shipping';

				if ($sendMail)
				{
					$shippingLayout = 'mail.shipping';
				}

				JPluginHelper::importPlugin('redshop_shipping');
				JFactory::getApplication()->triggerEvent('onBeforeRenderShippingAddress', array(&$shippingAddress));

				$shipAdd = RedshopLayoutHelper::render(
					$shippingLayout,
					array('shippingaddresses' => $shippingAddress),
					null,
					array('client' => 0)
				);

				$section = $shippingAddress->is_company == 1 ? RedshopHelperExtrafields::SECTION_COMPANY_SHIPPING_ADDRESS :
					RedshopHelperExtrafields::SECTION_PRIVATE_SHIPPING_ADDRESS;

				// Additional functionality - more flexible way
				$templateHtml = Redshop\Helper\ExtraFields::displayExtraFields(
					$section,
					$shippingAddress->users_info_id,
					"",
					$templateHtml
				);
			}

			$templateHtml = str_replace("{shipping_address}", $shipAdd, $templateHtml);
		}

		$shippingText = Redshop::getConfig()->getBool('SHIPPING_METHOD_ENABLE') == true ? JText::_('COM_REDSHOP_SHIPPING_ADDRESS_INFO_LBL') : '';
		$templateHtml = str_replace("{shipping_address}", "", $templateHtml);
		$templateHtml = str_replace("{shipping_address_information_lbl}", $shippingText, $templateHtml);

		return $templateHtml;
	}
}
