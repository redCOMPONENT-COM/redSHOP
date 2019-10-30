<?php
/**
 * @package     RedShop
 * @subpackage  Helper
 *
 * @copyright   Copyright (C) 2008 - 2019 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

namespace Redshop\Shipping;

defined('_JEXEC') or die;

/**
 * Shipping rate
 *
 * @since  2.1.0
 */
class Rate
{
	/**
	 * Get encrypted string from an array
	 *
	 * @param   array $data Information of shipping which needs to encrypt
	 *
	 * @return  string         Encrypted string.
	 * @since   2.1.0
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
	 * @param   string $string String which needs to decrypt
	 *
	 * @return  array            Decrypted info in array
	 * @since   2.1.0
	 */
	public static function decrypt($string)
	{
		$decrypt = self::cryptMethod(base64_decode(str_replace(' ', '+', $string)));

		return explode('|', $decrypt);
	}

	/**
	 * Logic to encrypt and decrypt
	 *
	 * @param   string $string String which needs to be crypt
	 *
	 * @return  string           Crypt string
	 * @since   2.1.0
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
	 * @since   2.1.0
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
	 * @param   object $shippingRate Shipping Rate information
	 * @param   array  $data         Shipping Rate user information from cart or checkout selection.
	 *
	 * @return  float                  Shipping Rate
	 *
	 * @since   2.1.0
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
	 * @param   array $shippingRates Array shipping rates
	 *
	 * @return  array
	 *
	 * @since   2.1.0
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
	 * @param   integer $shippingRateId Shipping rate ID
	 *
	 * @return  string
	 *
	 * @since   2.1.0
	 *
	 * @throws  \Exception
	 */
	public static function getFreeShippingRate($shippingRateId = 0)
	{
		$cart = \RedshopHelperCartSession::getCart();
		$idx  = 0;

		if (isset($cart['idx']))
		{
			$idx = (int) ($cart['idx']);
		}

		$orderSubtotal = isset($cart['product_subtotal']) ? $cart['product_subtotal'] : null;
		$userId        = \JFactory::getUser()->id;

		if (!empty($idx))
		{
			$text = \JText::_('COM_REDSHOP_NO_SHIPPING_RATE_AVAILABLE');
		}
		else
		{
			return \JText::_('COM_REDSHOP_NO_SHIPPING_RATE_AVAILABLE_WHEN_NOPRODUCT_IN_CART');
		}

		$input       = \JFactory::getApplication()->input;
		$usersInfoId = $input->getInt('users_info_id', 0);
		$db          = \JFactory::getDbo();

		$query = $db->getQuery(true)
			->select('*')
			->from($db->qn('#__redshop_shipping_rate', 'sr'))
			->leftJoin($db->qn('#__extensions', 's') . ' ON ' . $db->qn('sr.shipping_class') . ' = ' . $db->qn('s.element'))
			->where($db->qn('shipping_rate_value') . ' = 0')
			->order($db->qn('s.ordering') . ',' . $db->qn('sr.shipping_rate_priority'));

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
			else
			{
				$userInfo = \RedshopHelperOrder::getShippingAddress($userId);
				$userInfo = null !== $userInfo ? $userInfo[0] : null;
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

		if (!$isCompany)
		{
			$query->where('(' . $db->qn('company_only') . ' = 2 OR ' . $db->qn('company_only') . ' = 0)');
		}
		else
		{
			$query->where('(' . $db->qn('company_only') . ' = 1 OR ' . $db->qn('company_only') . ' = 0)');
		}

		if (count($shopperGroup) > 0)
		{
			$shopperGroupId = $shopperGroup->shopper_group_id;

			$query->where(
				'('
				. 'FIND_IN_SET(' . $db->quote((int) $shopperGroupId) . ',' . $db->qn('shipping_rate_on_shopper_group') . ')'
				. ' OR ' . $db->qn('shipping_rate_on_shopper_group') . ' = ' . $db->quote('')
				. ')'
			);
		}

		if ($country)
		{
			$query->where(
				'('
				. 'FIND_IN_SET(' . $db->quote($country) . ',' . $db->qn('shipping_rate_country') . ')'
				. ' OR ' . $db->qn('shipping_rate_country') . ' = ' . $db->quote(0)
				. ' OR ' . $db->qn('shipping_rate_country') . ' = ' . $db->quote('')
				. ')'
			);
		}
		else
		{
			$query->where(
				'('
				. 'FIND_IN_SET(' . $db->quote(\Redshop::getConfig()->get('DEFAULT_SHIPPING_COUNTRY')) . ',' . $db->qn('shipping_rate_country') . ')'
				. ' OR ' . $db->qn('shipping_rate_country') . ' = ' . $db->quote(0)
				. ' OR ' . $db->qn('shipping_rate_country') . ' = ' . $db->quote('')
				. ')'
			);
		}

		if ($state)
		{
			$query->where(
				'('
				. 'FIND_IN_SET(' . $db->quote($state) . ',' . $db->qn('shipping_rate_state') . ')'
				. ' OR ' . $db->qn('shipping_rate_state') . ' = ' . $db->quote(0)
				. ' OR ' . $db->qn('shipping_rate_state') . ' = ' . $db->quote('')
				. ')'
			);
		}

		$zip = trim($zip);

		if (preg_match('/^[0-9 ]+$/', $zip) && !empty($zip))
		{
			$query->where(
				'('
				. '(' . $db->qn('shipping_rate_zip_start') . ' <= ' . $db->quote($zip)
				. ' AND ' . $db->qn('shipping_rate_zip_end') . ' >= ' . $db->quote($zip) . ')'
				. ' OR '
				. '(' . $db->qn('shipping_rate_zip_start') . ' = ' . $db->quote(0)
				. ' AND ' . $db->qn('shipping_rate_zip_end') . ' = ' . $db->quote(0) . ')'
				. ' OR '
				. '(' . $db->qn('shipping_rate_zip_start') . ' = ' . $db->quote('')
				. ' AND ' . $db->qn('shipping_rate_zip_end') . ' = ' . $db->quote('') . ')'
				. ')'
			);
		}

		if ($shippingRateId)
		{
			$query->where($db->qn('sr.shipping_rate_id') . ' = ' . (int) $shippingRateId);
		}

		$shippingRate = $db->setQuery($query)->loadObject();

		if ($shippingRate)
		{
			if ($shippingRate->shipping_rate_ordertotal_start > $orderSubtotal)
			{
				$diff = $shippingRate->shipping_rate_ordertotal_start - $orderSubtotal;
				$text = sprintf(\JText::_('COM_REDSHOP_SHIPPING_TEXT_LBL'), \RedshopHelperProductPrice::formattedPrice($diff));
			}
			elseif ($shippingRate->shipping_rate_ordertotal_start <= $orderSubtotal
				&& ($shippingRate->shipping_rate_ordertotal_end == 0 || $shippingRate->shipping_rate_ordertotal_end >= $orderSubtotal)
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

	/**
	 * Return only one shipping rate on cart page. This function is called by ajax
	 *
	 * @return  string
	 *
	 * @since   2.1.0
	 *
	 * @throws  \Exception
	 */
	public static function calculate()
	{
		$input      = \JFactory::getApplication()->input;
		$db         = \JFactory::getDbo();
		$country    = $input->getString('country_code');
		$state      = $input->getString('state_code');
		$zip        = $input->getString('zip_code');
		$cart       = \RedshopHelperCartSession::getCart();
		$idx        = (int) ($cart['idx']);
		$orderTotal = 0;
		$rate       = 0;
		$pWhere     = "";
		$cWhere     = "";

		for ($i = 0; $i < $idx; $i++)
		{
			$orderTotal += ($cart[$i]['product_price'] * $cart[$i]['quantity']);

			$productId = $cart[$i]['product_id'];
			$pWhere   .= 'FIND_IN_SET(' . $db->quote((int) $productId) . ', ' . $db->qn('shipping_rate_on_product') . ')';

			if ($i != $idx - 1)
			{
				$pWhere .= " OR ";
			}

			$query = $db->getQuery(true)
				->select($db->qn('category_id'))
				->from($db->qn('#__redshop_product_category_xref'))
				->where($db->qn('product_id') . ' = ' . $db->quote((int) $productId));

			$categoryData = $db->setQuery($query)->loadObjectList();

			if ($categoryData)
			{
				$cWhere = ' ( ';

				foreach ($categoryData as $c => $category)
				{
					$cWhere .= " FIND_IN_SET(" . $db->quote((int) $category->category_id) . ", "
						. $db->qn('shipping_rate_on_category') . ") ";

					if ($c != count($categoryData) - 1)
					{
						$cWhere .= " OR ";
					}
				}

				$cWhere .= ")";
			}
		}

		if ($pWhere != "")
		{
			$pWhere = " OR (" . $pWhere . ")";
		}

		if ($cWhere != "")
		{
			$cWhere = " OR (" . $cWhere . ")";
		}

		$totalDimention = \RedshopHelperShipping::getCartItemDimension();
		$weightTotal    = $totalDimention['totalweight'];
		$volume         = $totalDimention['totalvolume'];

		// Product volume based shipping
		$volumes = \RedshopHelperShipping::getProductVolumeShipping();

		$whereShippingVolume = "";

		foreach ($volumes as $index => $volume)
		{
			$length = $volume['length'];
			$width  = $volume['width'];

			if ($index == 0)
			{
				$whereShippingVolume .= "AND (";
			}

			$whereShippingVolume .= "((" . $db->qn('shipping_rate_length_start') . " <= " . $db->quote($length) . " AND "
				. $db->qn('shipping_rate_length_end') . " >= "
				. $db->quote($length) . " AND (" . $db->qn('shipping_rate_width_start') . " <= " . $db->quote($width) . " AND "
				. $db->qn('shipping_rate_width_end') . " >= "
				. $db->quote($width) . ") AND (" . $db->qn('shipping_rate_height_start') . " <= " . $db->quote($length) . " AND "
				. $db->qn('shipping_rate_height_end') . " >= "
				. $db->quote($length) . ")) ";

			if ($index != count($volumes) - 1)
			{
				$whereShippingVolume .= " OR ";
			}

			if ($index == count($volumes) - 1)
			{
				$whereShippingVolume .= ")";
			}
		}

		$numbers = array("1", "2", "3", "4", "5", "6", "7", "8", "9", "0", " ");

		$zipCond = "";
		$zip     = trim($zip);

		if (strlen(str_replace($numbers, '', $zip)) == 0 && $zip != "")
		{
			$zipCond = ' AND ( ( ' . $db->qn('shipping_rate_zip_start') . ' <= ' . $db->quote($zip) . ' AND '
				. $db->qn('shipping_rate_zip_end') . ' >= ' . $db->quote($zip) . ' )
			OR (' . $db->qn('shipping_rate_zip_start') . ' = ' . $db->quote(0)
				. ' AND ' . $db->qn('shipping_rate_zip_end') . ' = ' . $db->quote(0) . ')
			OR (' . $db->qn('shipping_rate_zip_start') . ' = "" AND ' . $db->qn('shipping_rate_zip_end') . ' = "") ) ';
		}

		$whereCountry = "";
		$whereState   = '';

		if ($country)
		{
			$whereCountry = ' AND (FIND_IN_SET(' . $db->quote($country) . ', '
				. $db->qn('shipping_rate_country') . ') OR ('
				. $db->qn('shipping_rate_country') . ' = ' . $db->quote(0) . ' OR '
				. $db->qn('shipping_rate_country') . ' = "") )';
		}

		if ($state)
		{
			$whereState = ' AND (FIND_IN_SET(' . $db->quote($state) . ', '
				. $db->qn('shipping_rate_state') . ') OR '
				. $db->qn('shipping_rate_state') . ' = ' . $db->quote(0) . ' OR '
				. $db->qn('shipping_rate_state') . ' = "")';
		}

		$sql = "SELECT "
			. $db->qn('shipping_rate_value') . ", "
			. $db->qn('shipping_rate_zip_start') . ", "
			. $db->qn('shipping_rate_zip_end')
			. " FROM "
			. $db->qn('#__redshop_shipping_rate') . " AS sr
				LEFT JOIN " . $db->qn('#__extensions') . " AS s
				ON
				" . $db->qn('sr.shipping_class') . " = " . $db->qn('s.element')
			. " WHERE 1=1 AND " . $db->qn('s.folder') . " = " . $db->quote('redshop_shipping')
			. " AND " . $whereCountry . $whereState . $zipCond . "
				AND ((" . $db->qn('shipping_rate_volume_start') . " <= " . $db->quote($volume) . " AND "
			. $db->qn('shipping_rate_volume_end') . " >= "
			. $db->quote($volume) . ") OR (" . $db->qn('shipping_rate_volume_end') . " = 0) )
				AND ((" . $db->qn('shipping_rate_ordertotal_start') . " <= " . $db->quote($orderTotal) . " AND "
			. $db->qn('shipping_rate_ordertotal_end') . " >= "
			. $db->quote($orderTotal) . " OR (" . $db->qn('shipping_rate_ordertotal_end') . " = 0))
				AND ((" . $db->qn('shipping_rate_weight_start') . " <= " . $db->quote($weightTotal) . " AND "
			. $db->qn('shipping_rate_weight_end') . " >= "
			. $db->quote($weightTotal) . ") OR (" . $db->qn('shipping_rate_weight_end') . " = 0))" . $whereShippingVolume . "
				AND (" . $db->qn('shipping_rate_on_product') . " = '' " . $pWhere . ") AND ("
			. $db->qn('shipping_rate_on_category') . " = '' " . $cWhere . " )
				ORDER BY " . $db->qn('shipping_rate_priority') . ", " . $db->qn('shipping_rate_value') . ", " . $db->qn('sr.shipping_rate_id');

		$shippingRates = $db->setQuery($sql)->loadObjectList();

		/**
		 * rearrange shipping rates array
		 * after filtering zipcode
		 * check character condition for zip code..
		 */
		$shipping = array();

		if (strlen(str_replace($numbers, '', $zip)) != 0 && $zip != "")
		{
			$k = 0;

			$userZipLen = \RedshopHelperShipping::strposa($zip, $numbers) !== false ?
				\RedshopHelperShipping::strposa($zip, $numbers) : strlen($zip);

			foreach ($shippingRates as $shippingRate)
			{
				$flag  = false;
				$start = $shippingRate->shipping_rate_zip_start;
				$end   = $shippingRate->shipping_rate_zip_end;

				$startZipLen = \RedshopHelperShipping::strposa($start, $numbers) !== false ?
					\RedshopHelperShipping::strposa($start, $numbers) : strlen($start);
				$endZipLen   = \RedshopHelperShipping::strposa($end, $numbers) !== false ?
					\RedshopHelperShipping::strposa($end, $numbers) : strlen($end);

				if ($startZipLen != $endZipLen || $userZipLen != $endZipLen)
				{
					continue;
				}

				$len = $userZipLen;

				for ($j = 0; $j < $len; $j++)
				{
					if (ord(strtoupper($zip[$j])) >= ord(strtoupper($start[$j])) && ord(strtoupper($zip[$j])) <= ord(strtoupper($end[$j])))
					{
						$flag = true;
					}

					else
					{
						$flag = false;
						break;
					}
				}

				if ($flag)
				{
					$shipping[$k++] = $shippingRate;
				}
			}

			if (count($shipping) > 0)
			{
				$rate = $shipping[0]->shipping_rate_value;
			}

			else
			{
				if (count($shippingRates) > 0)
				{
					$rate = $shippingRates[0]->shipping_rate_value;
				}
			}
		}
		else
		{
			if (count($shippingRates) > 0)
			{
				$rate = $shippingRates[0]->shipping_rate_value;
			}
		}

		$total = $cart['total'] - $cart['shipping'] + $rate;
		$rate  = \RedshopHelperProductPrice::formattedPrice($rate, true);
		$total = \RedshopHelperProductPrice::formattedPrice($total, true);

		return $rate . "`" . $total;
	}
}
