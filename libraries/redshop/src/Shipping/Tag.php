<?php
/**
 * @package     RedShop
 * @subpackage  Helper
 *
 * @copyright   Copyright (C) 2008 - 2017 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

namespace Redshop\Shipping;

defined('_JEXEC') or die;

/**
 * Shipping tag
 *
 * @since  __DEPLOY_VERSION__
 */
class Tag
{
	/**
	 * Replace shipping method
	 *
	 * @param   object $shipping Shipping data
	 * @param   string $content  Template content
	 *
	 * @return  string
	 *
	 * @since   __DEPLOY_VERSION__
	 */
	public static function replaceShippingMethod($shipping, $content = "")
	{
		$search = array(
			'{shipping_method}',
			'{order_shipping}',
			'{shipping_excl_vat}',
			'{shipping_rate_name}',
			'{shipping}',
			'{vat_shipping}',
			'{order_shipping_shop_location}'
		);

		if (!\Redshop::getConfig()->getBool('SHIPPING_METHOD_ENABLE') || empty((array) $shipping))
		{
			return str_replace($search, array("", "", "", "", '', '', '', ''), $content);
		}

		$shippingDetail = \RedshopShippingRate::decrypt($shipping->ship_method_id);

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
			\JFactory::getLanguage()->load('plg_redshop_shipping_' . $element, JPATH_ADMINISTRATOR);

			// Load language file from plugin folder.
			\JFactory::getLanguage()->load('plg_redshop_shipping_' . $element, JPATH_ROOT . '/plugins/redshop_shipping/' . $element);

			if (array_key_exists(1, $shippingDetail))
			{
				$shippingMethod = $shippingDetail[1];
			}

			if (array_key_exists(2, $shippingDetail))
			{
				$shippingRateName = $shippingDetail[2];
			}
		}

		$replace = array(
			\JText::_($shippingMethod),
			\RedshopHelperProductPrice::formattedPrice($shipping->order_shipping),
			\RedshopHelperProductPrice::formattedPrice($shipping->order_shipping - $shipping->order_shipping_tax),
			\JText::_($shippingRateName),
			\RedshopHelperProductPrice::formattedPrice($shipping->order_shipping),
			\RedshopHelperProductPrice::formattedPrice($shipping->order_shipping_tax),
			self::getShopLocation($shipping, $shippingDetail)
		);

		return str_replace($search, $replace, $content);
	}

	/**
	 * Method for prepare shop location
	 *
	 * @param   object $shipping       Shipping
	 * @param   array  $shippingDetail Decrypted shipping detail
	 *
	 * @return  string
	 * @since   __DEPLOY_VERSION__
	 */
	protected static function getShopLocation($shipping, $shippingDetail)
	{
		$shopLocation = $shipping->shop_id;

		// @TODO: Shipping GLS
		if (!empty($shippingDetail) && $shippingDetail[0] != 'plgredshop_shippingdefault_shipping_gls')
		{
			$shopLocation = '';
		}

		$mobiles = array();

		if (!empty($shopLocation))
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
				$shopLocation  .= '<br>';

				foreach ($locationsTime as $locationTime)
				{
					$shopLocation .= $locationTime . '<br>';
				}
			}
		}

		if (!!empty($mobiles) && isset($mobiles[1]))
		{
			$shopLocation .= ' ' . $mobiles[1];
		}

		return $shopLocation;
	}

	/**
	 * Replace Shipping Address
	 *
	 * @param   string  $templateHtml    Template content
	 * @param   object  $shippingAddress Shipping address
	 * @param   boolean $sendMail        Is in send mail
	 *
	 * @return  string
	 * @throws  \Exception
	 *
	 * @since   __DEPLOY_VERSION__
	 */
	public static function replaceShippingAddress($templateHtml, $shippingAddress, $sendMail = false)
	{
		$shippingEnable = \Redshop::getConfig()->getBool('SHIPPING_METHOD_ENABLE');

		if (strpos($templateHtml, '{shipping_address_start}') !== false
			&& strpos($templateHtml, '{shipping_address_end}') !== false)
		{
			self::replaceShippingAddressStartEnd($templateHtml, $shippingAddress, $sendMail, $shippingEnable);
		}
		elseif (strpos($templateHtml, '{shipping_address}') !== false)
		{
			self::replaceShippingAddressBlock($templateHtml, $shippingAddress, $sendMail, $shippingEnable);
		}

		$shippingText = $shippingEnable == true ? \JText::_('COM_REDSHOP_SHIPPING_ADDRESS_INFO_LBL') : '';
		$templateHtml = str_replace("{shipping_address_information_lbl}", $shippingText, $templateHtml);

		return $templateHtml;
	}

	/**
	 * Replace Shipping Address block {shipping_address}
	 *
	 * @param   string  $templateHtml    Template content
	 * @param   object  $shippingAddress Shipping address
	 * @param   boolean $sendMail        Is in send mail
	 * @param   boolean $shippingEnable  Enable shipping or not
	 *
	 * @return  void
	 * @throws  \Exception
	 *
	 * @since   __DEPLOY_VERSION__
	 */
	protected static function replaceShippingAddressBlock(&$templateHtml, $shippingAddress, $sendMail, $shippingEnable)
	{
		if (null === $shippingAddress || $shippingAddress === new \stdClass || $shippingEnable === false)
		{
			$templateHtml = str_replace("{shipping_address}", '', $templateHtml);

			return;
		}

		$shippingLayout = $sendMail === true ? 'mail.shipping' : 'cart.shipping';

		\JPluginHelper::importPlugin('redshop_shipping');
		\RedshopHelperUtility::getDispatcher()->trigger('onBeforeRenderShippingAddress', array(&$shippingAddress));

		$html = \RedshopLayoutHelper::render(
			$shippingLayout,
			array('shippingaddresses' => $shippingAddress),
			null,
			array('client' => 0)
		);

		$section = $shippingAddress->is_company == 1 ? \RedshopHelperExtrafields::SECTION_COMPANY_SHIPPING_ADDRESS :
			\RedshopHelperExtrafields::SECTION_PRIVATE_SHIPPING_ADDRESS;

		// Additional functionality - more flexible way
		$templateHtml = \Redshop\Helper\ExtraFields::displayExtraFields(
			$section,
			$shippingAddress->users_info_id,
			"",
			$templateHtml
		);

		$templateHtml = str_replace("{shipping_address}", $html, $templateHtml);
	}

	/**
	 * Replace Shipping Address block {shipping_address}
	 *
	 * @param   string  $templateHtml    Template content
	 * @param   object  $shippingAddress Shipping address
	 * @param   boolean $sendMail        Is in send mail
	 * @param   boolean $shippingEnable  Enable shipping or not
	 *
	 * @return  void
	 * @throws  \Exception
	 *
	 * @since   __DEPLOY_VERSION__
	 */
	protected static function replaceShippingAddressStartEnd(&$templateHtml, $shippingAddress, $sendMail, $shippingEnable)
	{
		// Remove {shipping_address} tag
		$templateHtml = str_replace("{shipping_address}", "", $templateHtml);

		$templateStart = explode('{shipping_address_start}', $templateHtml);
		$templateEnd   = explode('{shipping_address_end}', $templateStart[1]);
		$shippingData  = $shippingEnable ? $templateEnd[0] : '';

		$search = array(
			'{companyname}',
			'{companyname_lbl}',
			'{firstname}',
			'{firstname_lbl}',
			'{lastname}',
			'{lastname_lbl}',
			'{address}',
			'{address_lbl}',
			'{zip}',
			'{zip_lbl}',
			'{city}',
			'{city_lbl}',
			'{country}',
			'{country_lbl}',
			'{state}',
			'{state_lbl}',
			'{phone}',
			'{phone_lbl}',
			'{shipping_extrafield}'
		);

		$replaces = array();

		if (null !== $shippingAddress && $shippingAddress !== new \stdClass && $shippingEnable)
		{
			$extraSection = $shippingAddress->is_company == 1 ?
				\RedshopHelperExtrafields::SECTION_COMPANY_SHIPPING_ADDRESS : \RedshopHelperExtrafields::SECTION_PRIVATE_SHIPPING_ADDRESS;

			if ($shippingAddress->is_company == 1 && !empty($shippingAddress->company_name))
			{
				$replaces[] = $shippingAddress->company_name;
				$replaces[] = \JText::_('COM_REDSHOP_COMPANY_NAME');
			}
			else
			{
				$replaces[] = '';
				$replaces[] = '';
			}

			if (!empty($shippingAddress->firstname))
			{
				$replaces[] = $shippingAddress->firstname;
				$replaces[] = \JText::_('COM_REDSHOP_FIRSTNAME');
			}
			else
			{
				$replaces[] = '';
				$replaces[] = '';
			}

			if (!empty($shippingAddress->lastname))
			{
				$replaces[] = $shippingAddress->lastname;
				$replaces[] = \JText::_('COM_REDSHOP_LASTNAME');
			}
			else
			{
				$replaces[] = '';
				$replaces[] = '';
			}

			if (!empty($shippingAddress->address))
			{
				$replaces[] = $shippingAddress->address;
				$replaces[] = \JText::_('COM_REDSHOP_ADDRESS');
			}
			else
			{
				$replaces[] = '';
				$replaces[] = '';
			}

			if (!empty($shippingAddress->zipcode))
			{
				$replaces[] = $shippingAddress->zipcode;
				$replaces[] = \JText::_('COM_REDSHOP_ZIP');
			}
			else
			{
				$replaces[] = '';
				$replaces[] = '';
			}

			if (!empty($shippingAddress->city))
			{
				$replaces[] = $shippingAddress->city;
				$replaces[] = \JText::_('COM_REDSHOP_CITY');
			}
			else
			{
				$replaces[] = '';
				$replaces[] = '';
			}

			$cname = \RedshopHelperOrder::getCountryName($shippingAddress->country_code);

			if (!empty($cname))
			{
				$replaces[] = \JText::_($cname);
				$replaces[] = \JText::_('COM_REDSHOP_COUNTRY');
			}
			else
			{
				$replaces[] = '';
				$replaces[] = '';
			}

			$stateName = \RedshopHelperOrder::getStateName($shippingAddress->state_code, $shippingAddress->country_code);

			if (!empty($stateName))
			{
				$replaces[] = $stateName;
				$replaces[] = \JText::_('COM_REDSHOP_STATE');
			}
			else
			{
				$replaces[] = '';
				$replaces[] = '';
			}

			if (!empty($shippingAddress->phone))
			{
				$replaces[] = $shippingAddress->phone;
				$replaces[] = \JText::_('COM_REDSHOP_PHONE');
			}
			else
			{
				$replaces[] = '';
				$replaces[] = '';
			}

			$replaces[] = \RedshopHelperExtrafields::listAllFieldDisplay($extraSection, $shippingAddress->users_info_id, 1);

			// Additional functionality - more flexible way
			$shippingData = \Redshop\Helper\ExtraFields::displayExtraFields($extraSection, $shippingAddress->users_info_id, "", $shippingData);
		}

		$templateHtml = $templateStart[0] . str_replace($search, $replaces, $shippingData) . $templateEnd[1];
	}
}
