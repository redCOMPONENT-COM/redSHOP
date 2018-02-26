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
 * Shipping rate
 *
 * @since  __DEPLOY_VERSION__
 */
class Rate
{
	/**
	 * Get encrypted string from an array
	 *
	 * @param   array  $data  Information of shipping which needs to encrypt
	 *
	 * @return  string         Encrypted string.
	 * @since   __DEPLOY_VERSION__
	 */
	public static function encrypt($data)
	{
		$string = implode('|', $data);

		return str_replace(
			"+",
			" ",
			base64_encode(self::cryptMethod($string))
		);
	}

	/**
	 * Decrypt the passed string
	 *
	 * @param   string  $string  String which needs to decrypt
	 *
	 * @return  array            Decrypted info in array
	 * @since   __DEPLOY_VERSION__
	 */
	public static function decrypt($string)
	{
		$decrypt = self::cryptMethod(base64_decode(str_replace(' ', '+', $string)));

		return explode('|', $decrypt);
	}

	/**
	 * Logic to encrypt and decrypt
	 *
	 * @param   string  $string  String which needs to be crypt
	 *
	 * @return  string           Crypt string
	 * @since   __DEPLOY_VERSION__
	 */
	protected static function cryptMethod($string)
	{
		$length    = strlen($string);
		$encrypted = "";

		for ($position = 0; $position < $length; $position++)
		{
			$keyToUse              = (($length + $position) + 1);
			$keyToUse              = (255 + $keyToUse) % 255;
			$byteToBeEncrypted     = substr($string, $position, 1);
			$asciiNumByteToEncrypt = ord($byteToBeEncrypted);
			$xoredByte             = $asciiNumByteToEncrypt ^ $keyToUse;
			$encryptedByte         = chr($xoredByte);
			$encrypted            .= $encryptedByte;
		}

		return $encrypted;
	}

	/**
	 * Delete shipping rate when shipping method is not available
	 *
	 * @return  void
	 *
	 * @since   __DEPLOY_VERSION__
	 */
	public static function removeShippingRate()
	{
		$db    = \JFactory::getDbo();
		$query = $db->getQuery(true)
			->select('DISTINCT(' . $db->qn('shipping_class') . ')')
			->from($db->qn('#__redshop_shipping_rate'));

		$shippingClasses = $db->setQuery($query)->loadColumn();

		if (empty($shippingClasses))
		{
			return;
		}

		$query->clear()
			->select($db->qn('element'))
			->from($db->qn('#__extensions'))
			->where($db->qn('folder') . ' = ' . $db->quote('redshop_shipping'));

		$shipping = $db->setQuery($query)->loadColumn();

		$differentShipping = array_diff($shippingClasses, $shipping);
		sort($differentShipping);

		if (!empty($differentShipping))
		{
			$query->clear()
				->delete($db->qn('#__redshop_shipping_rate'))
				->where($db->qn('shipping_class') . ' IN (' . implode(',', \RedshopHelperUtility::quote($differentShipping)) . ')');
			$db->setQuery($query)->execute();
		}
	}

	/**
	 * Apply VAT on shipping rate
	 *
	 * @param   object  $shippingRate  Shipping Rate information
	 * @param   array   $data          Shipping Rate user information from cart or checkout selection.
	 *
	 * @return  float                  Shipping Rate
	 *
	 * @since   __DEPLOY_VERSION__
	 *
	 * @throws  \InvalidArgumentException
	 */
	public static function applyVat($shippingRate, $data)
	{
		if (!is_array($data))
		{
			throw new \InvalidArgumentException(
				__FUNCTION__ . ' function only accepts array as 2nd argument. Input was: ' . gettype($data)
			);
		}

		$shippingRateVat = $shippingRate->shipping_rate_value;

		if ($shippingRate->apply_vat != 1)
		{
			return $shippingRateVat;
		}

		$result = \RedshopHelperShipping::getShippingVatRates($shippingRate->shipping_tax_group_id, $data);
		$addVat = \RedshopHelperCart::taxExemptAddToCart($data['user_id']);

		if (!empty($result) && $addVat && $result->tax_rate > 0)
		{
			$shippingRateVat = ($shippingRateVat * $result->tax_rate) + $shippingRateVat;
		}

		return $shippingRateVat;
	}

	/**
	 * Filter Shipping rates based on their priority
	 * Only show Higher priority rates (In [1,2,3,4] take 1 as a high priority)
	 * Rates with same priority will shown as radio button list in checkout
	 *
	 * @param   array  $shippingRates  Array shipping rates
	 *
	 * @return  array
	 *
	 * @since   __DEPLOY_VERSION__
	 */
	public static function filterRatesByPriority($shippingRates)
	{
		if (empty($shippingRates))
		{
			return array();
		}

		$filteredRates = array();
		$priority      = 0;

		foreach ($shippingRates as $i => $shippingRate)
		{
			if ($shippingRates[0]->shipping_rate_priority == $shippingRate->shipping_rate_priority)
			{
				$filteredRates[$priority] = $shippingRate;
				$priority++;
			}
		}

		return $filteredRates;
	}

	/**
	 * Get free shipping rate
	 *
	 * @param   integer  $shippingRateId  Shipping rate ID
	 *
	 * @return  string
	 *
	 * @since   __DEPLOY_VERSION__
	 *
	 * @throws  \Exception
	 */
	public static function getFreeShippingRate($shippingRateId = 0)
	{
		$input         = \JFactory::getApplication()->input;
		$usersInfoId   = $input->getInt('users_info_id', 0);
		$productHelper = \productHelper::getInstance();
		$session       = \JFactory::getSession();
		$cart          = $session->get('cart', null);
		$db            = \JFactory::getDbo();

		$idx = 0;

		if (isset($cart ['idx']) === true)
		{
			$idx = (int) ($cart['idx']);
		}

		$orderSubtotal = isset($cart['product_subtotal']) ? $cart['product_subtotal'] : null;
		$user          = \JFactory::getUser();
		$userId        = $user->id;

		if (!empty($idx))
		{
			$text = \JText::_('COM_REDSHOP_NO_SHIPPING_RATE_AVAILABLE');
		}
		else
		{
			return \JText::_('COM_REDSHOP_NO_SHIPPING_RATE_AVAILABLE_WHEN_NOPRODUCT_IN_CART');
		}

		// Try to load user information
		$userInfo     = null;
		$country      = null;
		$state        = null;
		$isCompany    = null;
		$shopperGroup = null;
		$zip          = null;

		if ($userId)
		{
			if ($usersInfoId)
			{
				$userInfo = \RedshopHelperShipping::getShippingAddress($usersInfoId);
			}
			elseif ($userInfo = \RedshopHelperOrder::getShippingAddress($userId))
			{
				$userInfo = $userInfo[0];
			}
		}

		if ($userInfo)
		{
			$country      = $userInfo->country_code;
			$state        = $userInfo->state_code;
			$isCompany    = $userInfo->is_company;
			$shopperGroup = \RedshopHelperUser::getShopperGroupData($userInfo->user_id);
			$zip          = $userInfo->zipcode;
		}

		$whereState   = '';
		$whereShopper = '';

		if (!$isCompany)
		{
			$where = " AND ( " . $db->qn('company_only') . " = 2 OR " . $db->qn('company_only') . " = 0) ";
		}
		else
		{
			$where = " AND ( " . $db->qn('company_only') . " = 1 OR " . $db->qn('company_only') . " = 0) ";
		}

		if (count($shopperGroup) > 0)
		{
			$shopperGroupId = $shopperGroup->shopper_group_id;
			$whereShopper   = " AND (FIND_IN_SET(" . $db->quote((int) $shopperGroupId) . ", "
				. $db->qn('shipping_rate_on_shopper_group') . " ) OR "
				. $db->qn('shipping_rate_on_shopper_group') . " = '') ";
		}

		if ($country)
		{
			$whereCountry = "AND (FIND_IN_SET(" . $db->quote($country) . ", "
				. $db->qn('shipping_rate_country') . " ) OR " . $db->qn('shipping_rate_country') . " = " . $db->quote(0) . " OR "
				. $db->qn('shipping_rate_country') . " = '' )";
		}
		else
		{
			$whereCountry = "AND (FIND_IN_SET(" . $db->quote(\Redshop::getConfig()->get('DEFAULT_SHIPPING_COUNTRY')) . ", "
				. $db->qn('shipping_rate_country') . " ) OR " . $db->qn('shipping_rate_country') . " = " . $db->quote(0) . " OR "
				. $db->qn('shipping_rate_country') . " = '')";
		}

		if ($state)
		{
			$whereState = " AND (FIND_IN_SET(" . $db->quote($state) . ", "
				. $db->qn('shipping_rate_state') . " ) OR " . $db->qn('shipping_rate_state') . " = " . $db->quote(0) . " OR "
				. $db->qn('shipping_rate_state') . " = '')";
		}

		$zipCond = "";
		$zip     = trim($zip);

		if (preg_match('/^[0-9 ]+$/', $zip) && !empty($zip))
		{
			$zipCond = " AND ( ( " . $db->qn('shipping_rate_zip_start') . " <= " . $db->quote($zip) . " AND "
				. $db->qn('shipping_rate_zip_end') . " >= " . $db->quote($zip) . " )
				OR (" . $db->qn('shipping_rate_zip_start') . " = " . $db->quote(0) . " AND " . $db->qn('shipping_rate_zip_end') . " = " . $db->quote(0) . ")
				OR (" . $db->qn('shipping_rate_zip_start') . " = '' AND " . $db->qn('shipping_rate_zip_end') . " = '') ) ";
		}

		if ($shippingRateId)
		{
			$where .= ' AND sr.shipping_rate_id = ' . (int) $shippingRateId . ' ';
		}

		$sql = "SELECT * FROM " . $db->qn('#__redshop_shipping_rate') . " AS sr
								LEFT JOIN " . $db->qn('#__extensions') . " AS s
								ON" . $db->qn('sr.shipping_class') . " = " . $db->qn('s.element')
			. "WHERE ( " . $db->qn('shipping_rate_value') . " = 0 OR "
			. $db->qn('shipping_rate_value') . " = 0) "
			. $whereCountry . $whereState . $whereShopper . $zipCond . $where
			. "ORDER BY " . $db->qn('s.ordering') . ", " . $db->qn('sr.shipping_rate_priority') . " LIMIT 0,1";

		$shippingRate = $db->setQuery($sql)->loadObject();

		if ($shippingRate)
		{
			if ($shippingRate->shipping_rate_ordertotal_start > $orderSubtotal)
			{
				$diff = $shippingRate->shipping_rate_ordertotal_start - $orderSubtotal;
				$text = sprintf(\JText::_('COM_REDSHOP_SHIPPING_TEXT_LBL'), $productHelper->getProductFormattedPrice($diff));
			}

			elseif ($shippingRate->shipping_rate_ordertotal_start <= $orderSubtotal
				&& ($shippingRate->shipping_rate_ordertotal_end == 0
					|| $shippingRate->shipping_rate_ordertotal_end >= $orderSubtotal)
			)
			{
				$text = \JText::_('COM_REDSHOP_FREE_SHIPPING_RATE_IS_IN_USED');
			}

			else
			{
				$text = \JText::_('COM_REDSHOP_NO_SHIPPING_RATE_AVAILABLE');
			}
		}

		return $text;
	}
}
