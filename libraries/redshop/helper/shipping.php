<?php
/**
 * @package     RedSHOP.Library
 * @subpackage  Helper
 *
 * @copyright   Copyright (C) 2008 - 2019 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 *
 * @since       2.0.0.3
 */

defined('_JEXEC') or die;

/**
 * Class Redshop Helper for Shipping
 *
 * @since  2.0.0.3
 */
class RedshopHelperShipping
{
	/**
	 * @var   array
	 *
	 * @since 2.0.0.3
	 */
	protected static $shippingBoxes;

	/**
	 * @var   array
	 *
	 * @since 2.0.0.3
	 */
	protected static $users = array();

	/**
	 * Get Shipping rate for xmlexport
	 *
	 * @param   array $data Shipping data
	 *
	 * @return  array
	 *
	 * @since   2.0.0.3
	 */
	public static function getDefaultShippingXmlExport($data)
	{
		$orderSubtotal = $data['order_subtotal'];
		$user          = JFactory::getUser();
		$userId        = $user->id;
		$db            = JFactory::getDbo();

		$data         = Redshop::product((int) $data['product_id']);
		$weightTotal  = $data->weight;
		$volume       = $data->product_volume;
		$userInfo     = self::getShippingAddress($data['users_info_id']);
		$productId    = $data['product_id'];
		$country      = '';
		$state        = '';
		$isCompany    = '';
		$newCwhere    = '';
		$whereState   = '';
		$whereShopper = '';

		if ($userInfo)
		{
			$country   = $userInfo->country_code;
			$isCompany = $userInfo->is_company;
			$userId    = $userInfo->user_id;
			$state     = $userInfo->state_code;
		}

		$shopperGroup = RedshopHelperUser::getShopperGroupData($userId);

		if (count($shopperGroup) > 0)
		{
			$shopperGroupId = $shopperGroup->shopper_group_id;
			$whereShopper   = ' AND (FIND_IN_SET(' . $db->quote((int) $shopperGroupId) . ', '
				. $db->qn('shipping_rate_on_shopper_group') . ') OR '
				. $db->qn('shipping_rate_on_shopper_group') . ' = "") ';
		}

		if ($country)
		{
			$whereCountry = '(FIND_IN_SET(' . $db->quote($country) . ', '
				. $db->qn('shipping_rate_country') . ') OR '
				. $db->qn('shipping_rate_country') . ' = ' . $db->quote(0) . ' OR '
				. $db->qn('shipping_rate_country') . ' = "")';
		}
		else
		{
			$whereCountry = '(FIND_IN_SET(' . $db->quote(Redshop::getConfig()->get('DEFAULT_SHIPPING_COUNTRY')) . ', '
				. $db->qn('shipping_rate_country') . ') OR '
				. $db->qn('shipping_rate_country') . ' = ' . $db->quote(0) . ' OR '
				. $db->qn('shipping_rate_country') . ' = "")';
		}

		if ($state)
		{
			$whereState = ' AND (FIND_IN_SET(' . $db->quote($state) . ', '
				. $db->qn('shipping_rate_state') . ') OR '
				. $db->qn('shipping_rate_state') . ' = ' . $db->quote(0) . ' OR '
				. $db->qn('shipping_rate_state') . ' = "")';
		}

		if (!$isCompany)
		{
			$isWhere = " AND ( " . $db->qn('company_only') . " = 2 OR " . $db->qn('company_only') . " = 0) ";
		}
		else
		{
			$isWhere = " AND ( " . $db->qn('company_only') . " = 1 OR " . $db->qn('company_only') . " = 0) ";
		}

		$shippingArr = self::getShopperGroupDefaultShipping();

		if (empty($shippingArr))
		{
			$pWhere    = 'AND ( FIND_IN_SET(' . $db->quote((int) $productId) . ', ' . $db->qn('shipping_rate_on_product') . ') )';
			$newPwhere = str_replace("AND (", "OR (", $pWhere);

			$sql = "SELECT * FROM " . $db->qn('#__redshop_shipping_rate') . " AS sr "
				. "LEFT JOIN " . $db->qn('#__extensions') . " AS s ON " . $db->qn('sr.shipping_class') . " = " . $db->qn('s.element')
				. " WHERE " . $db->qn('s.folder') . " = " . $db->quote('redshop_shipping') . " AND "
				. $whereCountry . $isWhere . "
						 AND ((" . $db->qn('shipping_rate_volume_start') . " <= " . $db->quote($volume) . " AND "
				. $db->qn('shipping_rate_volume_end') . " >= "
				. $db->quote($volume) . ") OR (" . $db->qn('shipping_rate_volume_end') . " = 0) )
						 AND ((" . $db->qn('shipping_rate_ordertotal_start') . " <= " . $db->quote($orderSubtotal) . " AND "
				. $db->qn('shipping_rate_ordertotal_end') . " >= "
				. $db->quote($orderSubtotal) . ")  OR (" . $db->qn('shipping_rate_ordertotal_end') . " = 0))
						 AND ((" . $db->qn('shipping_rate_weight_start') . " <= " . $db->quote($weightTotal) . " AND "
				. $db->qn('shipping_rate_weight_end') . " >= "
				. $db->quote($weightTotal) . ")  OR (" . $db->qn('shipping_rate_weight_end') . " = 0))"
				. $pWhere . $whereState . $whereShopper . "
						   ORDER BY " . $db->qn('sr.shipping_rate_priority') . " LIMIT 0,1";

			$shippingRate = $db->setQuery($sql)->loadObject();

			if (!$shippingRate)
			{
				$query = $db->getQuery(true)
					->select($db->qn('category_id'))
					->from($db->qn('#__redshop_product_category_xref'))
					->where($db->qn('product_id') . ' = ' . $db->quote((int) $productId));

				$categoryData = $db->setQuery($query)->loadObjectList();

				if ($categoryData)
				{
					$where = 'AND ( ';

					foreach ($categoryData as $c => $categoryDatum)
					{
						$where .= " FIND_IN_SET(" . $db->quote((int) $categoryDatum->category_id) . ","
							. $db->qn('shipping_rate_on_category') . ") ";

						if ($c != count($categoryData) - 1)
						{
							$where .= " OR ";
						}
					}

					$where    .= ")";
					$newCwhere = str_replace("AND (", "OR (", $where);
					$sql       = "SELECT * FROM " . $db->qn('#__redshop_shipping_rate') . " AS sr
									 LEFT JOIN " . $db->qn('#__extensions') . " AS s
									 ON
									 " . $db->qn('sr.shipping_class') . " = " . $db->qn('s.element') . "
									 WHERE " . $db->qn('s.folder') . " = " . $db->quote('redshop_shipping') . " AND "
						. $whereCountry . $whereShopper . $isWhere . "
									 AND ((" . $db->qn('shipping_rate_volume_start') . " <= " . $db->quote($volume) . " AND "
						. $db->qn('shipping_rate_volume_end') . " >= "
						. $db->quote($volume) . ") OR (" . $db->qn('shipping_rate_volume_end') . " = 0) )
									 AND ((" . $db->qn('shipping_rate_ordertotal_start') . " <= " . $db->quote($orderSubtotal) . " AND "
						. $db->qn('shipping_rate_ordertotal_end') . " >= "
						. $db->quote($orderSubtotal) . ") OR (" . $db->qn('shipping_rate_ordertotal_end') . " = 0))
									 AND ((" . $db->qn('shipping_rate_weight_start') . " <= " . $db->quote($weightTotal) . " AND "
						. $db->qn('shipping_rate_weight_end') . " >= "
						. $db->quote($weightTotal) . "  OR (" . $db->qn('shipping_rate_weight_end') . " = 0))"
						. $where . $whereState . "
									ORDER BY " . $db->qn('sr.shipping_rate_priority') . " LIMIT 0,1";

					$shippingRate = $db->setQuery($sql)->loadObject();
				}
			}

			if (!$shippingRate)
			{
				$sql = "SELECT * FROM " . $db->qn('#__redshop_shipping_rate') . " AS sr
								 LEFT JOIN " . $db->qn('#__extensions') . " AS s
								 ON
								 " . $db->qn('sr.shipping_class') . " = " . $db->qn('s.element') . "
						WHERE " . $db->qn('s.folder') . " = " . $db->quote('redshop_shipping') . " AND "
					. $whereCountry . $whereShopper . $isWhere . $whereState . "
						AND ((" . $db->qn('shipping_rate_volume_start') . " <= " . $db->quote($volume) . " AND "
					. $db->qn('shipping_rate_volume_end') . " >= "
					. $db->quote($volume) . ") OR ("
					. $db->qn('shipping_rate_volume_end') . " = 0) )
						AND ((" . $db->qn('shipping_rate_ordertotal_start') . " <= " . $db->quote($orderSubtotal) . " AND "
					. $db->qn('shipping_rate_ordertotal_end') . " >= "
					. $db->quote($orderSubtotal) . ")  OR (" . $db->qn('shipping_rate_ordertotal_end') . " = 0))
						AND ((" . $db->qn('shipping_rate_weight_start') . " <= " . $db->quote($weightTotal) . " AND "
					. $db->qn('shipping_rate_weight_end') . " >= "
					. $db->quote($weightTotal) . ")  OR (" . $db->qn('shipping_rate_weight_end') . " = 0))
						AND (" . $db->qn('shipping_rate_on_product') . " = '' " . $newPwhere . ") AND ("
					. $db->qn('shipping_rate_on_category') . " = '' " . $newCwhere . ")
						ORDER BY " . $db->qn('sr.shipping_rate_priority') . " LIMIT 0,1";

				$shippingRate = $db->setQuery($sql)->loadObject();
			}

			$total       = 0;
			$shippingVat = 0;

			if ($shippingRate)
			{
				$total = $shippingRate->shipping_rate_value;

				if ($shippingRate->apply_vat == 1)
				{
					$result = self::getShippingVatRates($shippingRate->shipping_tax_group_id, $data);
					$addVat = RedshopHelperCart::taxExemptAddToCart($userId);

					if (!empty($result) && $addVat)
					{
						if ($result->tax_rate > 0)
						{
							$shippingVat = $total * $result->tax_rate;
							$total       = $shippingVat + $total;
						}
					}
				}
			}

			$shipArr['shipping_rate'] = $total;
			$shipArr['shipping_vat']  = $shippingVat;

			return $shipArr;
		}
		else
		{
			return $shippingArr;
		}
	}

	/**
	 * Return only one shipping rate on cart page. This function is called by ajax
	 *
	 * @return  string
	 *
	 * @since   2.0.0.3
	 *
	 * @throws  Exception
	 *
	 * @deprecated 2.1.0 Redshop\Shipping\Rate::calculate()
	 * @see Redshop\Shipping\Rate::calculate
	 */
	public static function getShippingRateCalc()
	{
		return Redshop\Shipping\Rate::calculate();
	}

	/**
	 * Encrypt Shipping
	 *
	 * @param   string $strMessage String to encrypt
	 *
	 * @return  string  Encrypt shipping rate
	 *
	 * @since      2.0.0.3
	 *
	 * @deprecated 2.1.0 Redshop\Shipping\Rate::encrypt
	 * @see Redshop\Shipping\Rate::encrypt
	 */
	public static function encryptShipping($strMessage)
	{
		return Redshop\Shipping\Rate::encrypt(array($strMessage));
	}

	/**
	 * Decrypt Shipping
	 *
	 * @param   string $strMessage String to decrypt
	 *
	 * @return  array  Decrypt shipping rate
	 *
	 * @since   2.0.0.3
	 *
	 * @deprecated 2.1.0 Redshop\Shipping\Rate::decrypt
	 * @see Redshop\Shipping\Rate::decrypt
	 */
	public static function decryptShipping($strMessage)
	{
		return Redshop\Shipping\Rate::decrypt($strMessage);
	}

	/**
	 * Get shipping address
	 *
	 * @param   int $userInfoId User info id
	 *
	 * @return  object
	 *
	 * @since   2.0.0.3
	 */
	public static function getShippingAddress($userInfoId)
	{
		if (!$userInfoId)
		{
			return null;
		}

		if (!array_key_exists($userInfoId, static::$users))
		{
			$db    = JFactory::getDbo();
			$query = $db->getQuery(true)
				->select('*')
				->from($db->qn('#__redshop_users_info'))
				->where($db->qn('users_info_id') . ' = ' . $db->quote((int) $userInfoId));

			static::$users[$userInfoId] = $db->setQuery($query)->loadObject();
		}

		return static::$users[$userInfoId];
	}

	/**
	 * Get shipping method class
	 *
	 * @param   string $shippingClass Shipping class
	 *
	 * @return  object
	 *
	 * @since   2.0.0.3
	 */
	public static function getShippingMethodByClass($shippingClass = '')
	{
		$folder = strtolower('redshop_shipping');
		$db     = JFactory::getDbo();
		$query  = $db->getQuery(true)
			->select('*')
			->from($db->qn('#__extensions'))
			->where('LOWER(' . $db->qn('folder') . ')' . ' = ' . $db->quote($folder))
			->where($db->qn('element') . ' = ' . $db->quote($shippingClass));

		return $db->setQuery($query)->loadObject();
	}

	/**
	 * Get shipping method by id
	 *
	 * @param   integer  $id  Shipping id
	 *
	 * @return  object
	 *
	 * @since   2.0.0.3
	 */
	public static function getShippingMethodById($id = 0)
	{
		$folder = strtolower('redshop_shipping');
		$db     = JFactory::getDbo();
		$query  = $db->getQuery(true)
			->select('*')
			->select($db->qn('extension_id', 'id'))
			->from($db->qn('#__extensions'))
			->where('LOWER(' . $db->qn('folder') . ') = ' . $db->quote($folder))
			->where($db->qn('extension_id') . ' = ' . $db->quote((int) $id));

		return $db->setQuery($query)->loadObject();
	}

	/**
	 * Get shipping rates
	 *
	 * @param   string  $shippingClass  Shipping class
	 *
	 * @return  array
	 *
	 * @since   2.0.0.3
	 */
	public static function getShippingRates($shippingClass)
	{
		$db    = JFactory::getDbo();
		$query = $db->getQuery(true)
			->select('*')
			->from($db->qn('#__redshop_shipping_rate'))
			->where($db->qn('shipping_class') . ' = ' . $db->quote($shippingClass));

		return $db->setQuery($query)->loadObjectList();
	}

	/**
	 * Apply VAT on shipping rate
	 *
	 * @param   object $shippingRate Shipping Rate information
	 * @param   array  $data         Shipping Rate user information from cart or checkout selection.
	 *
	 * @return  float  Shipping Rate
	 *
	 * @since   2.0.0.3
	 *
	 * @deprecated 2.1.0
	 * @see Redshop\Shipping\Rate::applyVat
	 *
	 * @throws  InvalidArgumentException
	 */
	public static function applyVatOnShippingRate($shippingRate, $data)
	{
		return Redshop\Shipping\Rate::applyVat($shippingRate, $data);
	}

	/**
	 * List shipping rates
	 *
	 * @param   object   $shippingClass  Shipping class
	 * @param   integer  $usersInfoId    User info id
	 * @param   array    $data           Shipping data
	 *
	 * @return  array                   Shipping Rates
	 *
	 * @since   2.0.0.3
	 *
	 * @throws  Exception
	 */
	public static function listShippingRates($shippingClass, $usersInfoId, &$data)
	{
		$app            = JFactory::getApplication();
		$isAdmin        = $app->isAdmin();
		$orderSubtotal  = $data['order_subtotal'];
		$totalDimention = self::getCartItemDimension();
		$weightTotal    = $totalDimention['totalweight'];
		$volume         = $totalDimention['totalvolume'];
		$session        = JFactory::getSession();
		$db             = JFactory::getDbo();
		$cart           = $session->get('cart');
		$idx            = (int) ($cart ['idx']);

		// Product volume based shipping
		$volumeShipping      = self::getProductVolumeShipping();
		$whereShippingVolume = "";

		if (count($volumeShipping) > 0)
		{
			$whereShippingVolume .= " AND ( ";

			for ($g = 0, $gn = count($volumeShipping); $g < $gn; $g++)
			{
				$length = $volumeShipping[$g]['length'];
				$width  = $volumeShipping[$g]['width'];
				$height = $volumeShipping[$g]['height'];

				if ($g != 0)
				{
					$whereShippingVolume .= " OR ";
				}

				$whereShippingVolume .= "(
						((" . $db->quote($length) . " BETWEEN " . $db->qn('shipping_rate_length_start')
					. " AND " . $db->qn('shipping_rate_length_end') . ")
							OR (" . $db->qn('shipping_rate_length_start') . " = 0 AND "
					. $db->qn('shipping_rate_length_end') . " = 0))
						AND ((" . $db->quote($width) . " BETWEEN " . $db->qn('shipping_rate_width_start')
					. " AND " . $db->qn('shipping_rate_width_end') . ")
							OR (" . $db->qn('shipping_rate_width_start') . " = 0 AND "
					. $db->qn('shipping_rate_width_end') . " = 0))
						AND ((" . $db->quote($height) . " BETWEEN " . $db->qn('shipping_rate_height_start')
					. " AND " . $db->qn('shipping_rate_height_end') . ")
							OR (" . $db->qn('shipping_rate_height_start') . " = 0 AND "
					. $db->qn('shipping_rate_height_end') . "= 0))
						) ";
			}

			$whereShippingVolume .= " ) ";
		}

		$userInfo     = self::getShippingAddress($usersInfoId);
		$country      = '';
		$state        = isset($data['state_code']) ? $data['state_code'] : '';
		$isCompany    = false;
		$shippingRate = array();
		$zip          = '';
		$whereState   = '';
		$whereShopper = '';

		if ($userInfo)
		{
			$country   = $userInfo->country_code;
			$state     = $userInfo->state_code;
			$zip       = $userInfo->zipcode;
			$isCompany = (bool) $userInfo->is_company;

			$shopperGroup = RedshopHelperUser::getShopperGroupData($userInfo->user_id);

			if (!empty($shopperGroup))
			{
				$shopperGroupId = $shopperGroup->shopper_group_id;
				$whereShopper   = " AND (FIND_IN_SET(" . (int) $shopperGroupId . ", " . $db->qn('shipping_rate_on_shopper_group') . ")
				OR " . $db->qn('shipping_rate_on_shopper_group') . "= '') ";
			}
		}
		elseif (empty($userInfo) && Redshop::getConfig()->get('ONESTEP_CHECKOUT_ENABLE'))
		{
			if (!empty($data['post']['anonymous']))
			{
				$anonymousUser = $data['post']['anonymous'];
				$isCompany = ($anonymousUser['billing_type'] == 'company');

				$country   = $anonymousUser['BT']['country_code'];
				$state     = $anonymousUser['BT']['state_code'];
				$zip       = $anonymousUser['BT']['zip_code'];

				if ($anonymousUser['bill_is_ship'] == 0)
				{
					$country   = $anonymousUser['ST']['country_code_ST'];
					$state     = $anonymousUser['ST']['state_code_ST'];
					$zip       = $anonymousUser['ST']['zip_code_ST'];
				}

				switch ($anonymousUser['billing_type'])
				{
					case 'private':
						$shopperGroupId = Redshop::getConfig()->get('SHOPPER_GROUP_DEFAULT_PRIVATE');
						break;
					case 'company':
						$shopperGroupId = Redshop::getConfig()->get('SHOPPER_GROUP_DEFAULT_COMPANY');
						break;
					default:
						$shopperGroupId = Redshop::getConfig()->get('SHOPPER_GROUP_DEFAULT_UNREGISTERED');
						break;
				}

				$whereShopper   = " AND (FIND_IN_SET(" . (int) $shopperGroupId . ", " . $db->qn('shipping_rate_on_shopper_group') . ")
				OR " . $db->qn('shipping_rate_on_shopper_group') . "= '') ";
			}
		}

		if ($isCompany === false)
		{
			$where = " AND ( " . $db->qn('company_only') . " = 2 OR " . $db->qn('company_only') . " = 0) ";
		}
		else
		{
			$where = " AND ( " . $db->qn('company_only') . " = 1 OR " . $db->qn('company_only') . " = 0) ";
		}

		if ($country)
		{
			$whereCountry = "AND (FIND_IN_SET(" . $db->quote($country) . ", " . $db->qn('shipping_rate_country') . ")"
				. " OR " . $db->qn('shipping_rate_country') . " = " . $db->quote(0)
				. " OR " . $db->qn('shipping_rate_country') . " = " . $db->quote('')
				. " )";
		}
		else
		{
			$whereCountry = "AND (FIND_IN_SET(" . $db->quote(Redshop::getConfig()->get('DEFAULT_SHIPPING_COUNTRY')) . ", " . $db->qn('shipping_rate_country') . ")"
				. " OR " . $db->qn('shipping_rate_country') . " = " . $db->quote(0)
				. " OR " . $db->qn('shipping_rate_country') . " = " . $db->quote('')
				. " )";
		}

		if ($state)
		{
			$whereState = " AND (FIND_IN_SET(" . $db->quote($state) . ", " . $db->qn('shipping_rate_state') . ") OR "
				. $db->qn('shipping_rate_state') . " = " . $db->quote(0) . " OR " . $db->qn('shipping_rate_state') . " = '')";
		}

		$pWhere = "";
		$cWhere = "";

		if ($idx)
		{
			$pWhere = 'OR ( ';

			for ($i = 0; $i < $idx; $i++)
			{
				$productId = $cart[$i]['product_id'];
				$pWhere    .= "FIND_IN_SET(" . $db->quote((int) $productId) . ", " . $db->qn('shipping_rate_on_product') . ")";

				if ($i != $idx - 1)
				{
					$pWhere .= " OR ";
				}
			}

			$pWhere .= ")";
		}

		if (!$shippingRate)
		{
			for ($i = 0; $i < $idx; $i++)
			{
				$productId = $cart[$i]['product_id'];
				$query     = $db->getQuery(true)
					->select($db->qn('category_id'))
					->from($db->qn('#__redshop_product_category_xref'))
					->where($db->qn('product_id') . ' = ' . $db->quote((int) $productId));

				$categoryData = $db->setQuery($query)->loadColumn();

				if (!empty($categoryData))
				{
					foreach ($categoryData as $categoryDatum)
					{
						$acWhere[] = " FIND_IN_SET(" . $db->quote((int) $categoryDatum) . "," . $db->qn('shipping_rate_on_category') . ") ";
					}
				}
			}

			if (isset($acWhere) && count($acWhere) > 0)
			{
				$acWhere = implode(' OR ', $acWhere);
				$cWhere  = ' OR (' . $acWhere . ')';
			}
		}

		$numbers = array("1", "2", "3", "4", "5", "6", "7", "8", "9", "0",
			"a", "b", "c", "d", "e", "f", "g", "h", "i", "j", "k", "l", "m", "n", "o", "p", "q", "r", "s", "t", "u", "v", "w", "x", "y", "z",
			"A", "B", "C", "D", "E", "F", "G", "H", "I", "J", "K", "L", "M", "N", "O", "P", "Q", "R", "S", "T", "U", "V", "W", "X", "Y", "Z", " "
		);

		if (!$shippingRate)
		{
			$zipCond = "";
			$zip     = trim($zip);

			if (strlen(str_replace($numbers, '', $zip)) == 0 && $zip != "")
			{
				$zipCond = " AND ( ( " . $db->qn('shipping_rate_zip_start') . " <= " . $db->quote($zip) . " AND "
					. $db->qn('shipping_rate_zip_end') . " >= " . $db->quote($zip) . " )
				OR (" . $db->qn('shipping_rate_zip_start') . " = " . $db->quote(0) . " AND " . $db->qn('shipping_rate_zip_end') . " = " . $db->quote(0) . ")
				OR (" . $db->qn('shipping_rate_zip_start') . " = '' AND " . $db->qn('shipping_rate_zip_end') . " = '') ) ";
			}

			$sql = "SELECT * FROM " . $db->qn('#__redshop_shipping_rate') . " WHERE " . $db->qn('shipping_class') . " = "
				. $db->quote($shippingClass)
				. $whereCountry
				. $whereState
				. $whereShopper
				. $zipCond . "
				AND (( " . $db->quote($volume) . " BETWEEN " . $db->qn('shipping_rate_volume_start')
				. " AND " . $db->qn('shipping_rate_volume_end') . ") OR ( " . $db->qn('shipping_rate_volume_end') . " = 0) )
				AND (( " . $db->quote($orderSubtotal) . " BETWEEN " . $db->qn('shipping_rate_ordertotal_start')
				. " AND " . $db->qn('shipping_rate_ordertotal_end') . ") OR (" . $db->qn('shipping_rate_ordertotal_end') . " = 0))
				AND (( " . $db->quote($weightTotal) . " BETWEEN " . $db->qn('shipping_rate_weight_start')
				. " AND " . $db->qn('shipping_rate_weight_end') . ") OR ("
				. $db->qn('shipping_rate_weight_end') . " = 0)) " . $whereShippingVolume . "
				AND (" . $db->qn('shipping_rate_on_product') . " = '' " . $pWhere . ") AND ("
				. $db->qn('shipping_rate_on_category') . " = '' " . $cWhere . ")" . $where . "
				ORDER BY " . $db->qn('shipping_rate_priority');

			$shippingRate = $db->setQuery($sql)->loadObjectList();
		}

		/*
		 * Rearrange shipping rates array
		 * after filtering zipcode
		 * check character condition for zip code..
		 */
		$shipping = array();

		if (strlen(str_replace($numbers, '', $zip)) != 0 && $zip != "")
		{
			$k          = 0;
			$userZipLen = (self::strposa($zip, $numbers) !== false) ? (self::strposa($zip, $numbers)) : strlen($zip);

			for ($i = 0, $countShippingRate = count($shippingRate); $i < $countShippingRate; $i++)
			{
				$flag            = false;
				$tmpShippingRate = $shippingRate[$i];
				$start           = $tmpShippingRate->shipping_rate_zip_start;
				$end             = $tmpShippingRate->shipping_rate_zip_end;

				if (trim($start) == "" && trim($end) == "")
				{
					$shipping[$k++] = $tmpShippingRate;
				}

				else
				{
					$startZipLen = (self::strposa($start, $numbers) !== false) ? (self::strposa($start, $numbers)) : strlen($start);
					$endZipLen   = (self::strposa($end, $numbers) !== false) ? (self::strposa($end, $numbers)) : strlen($end);

					if ($startZipLen != $endZipLen || $userZipLen != $endZipLen)
					{
						continue;
					}

					$len = $userZipLen;

					for ($j = 0; $j < $len; $j++)
					{
						if (ord(strtoupper($zip[$j])) >= ord(strtoupper($start[$j]))
							&& ord(strtoupper($zip[$j])) <= ord(strtoupper($end[$j]))
						)
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
						$shipping[$k++] = $tmpShippingRate;
					}
				}
			}

			if ($isAdmin == false)
			{
				$shipping = Redshop\Shipping\Rate::filterRatesByPriority($shipping);
			}

			return $shipping;
		}
		else
		{
			if ($isAdmin == false)
			{
				$shippingRate = Redshop\Shipping\Rate::filterRatesByPriority($shippingRate);
			}

			return $shippingRate;
		}
	}

	/**
	 * Get shipping vat rates based on either billing or shipping user
	 *
	 * @param   int   $shippingTaxGroupId Shipping Default Tax Gorup ID
	 * @param   array $data               Shipping User Information array
	 *
	 * @return  object Shipping VAT rates
	 *
	 * @since   2.0.0.3
	 */
	public static function getShippingVatRates($shippingTaxGroupId, $data = array())
	{
		$db    = JFactory::getDbo();
		$query = $db->getQuery(true);

		if (!empty($data) && ((!empty($data['user_id']) && $data['user_id'] > 0) || $data['users_info_id'] > 0))
		{
			if ('BT' == Redshop::getConfig()->get('CALCULATE_VAT_ON'))
			{
				$userData = RedshopHelperUser::getUserInformation($data['user_id'], 'BT', 0, true, true);
			}
			else
			{
				$userData = RedshopHelperUser::getUserInformation(0, '', $data['users_info_id'], false);
			}

			if (count($userData) > 0)
			{
				if (!$userData->country_code)
				{
					$userData->country_code = Redshop::getConfig()->get('DEFAULT_VAT_COUNTRY');
				}

				if (!$userData->state_code)
				{
					$userData->state_code = Redshop::getConfig()->get('DEFAULT_VAT_STATE');
				}

				/*
				 *  VAT_BASED_ON = 0 // webshop mode
				 *  VAT_BASED_ON = 1 // customer mode
				 *  VAT_BASED_ON = 2 // EU mode
				 */
				if (0 == Redshop::getConfig()->getInt('VAT_BASED_ON'))
				{
					$userData->country_code = Redshop::getConfig()->get('DEFAULT_VAT_COUNTRY');
					$userData->state_code   = Redshop::getConfig()->get('DEFAULT_VAT_STATE');
				}
			}

			if (Redshop::getConfig()->get('VAT_BASED_ON') == 2)
			{
				$query->where($db->qn('tr.is_eu_country') . " = 1");
			}
		}
		else
		{
			$session                = JFactory::getSession();
			$auth                   = $session->get('auth');
			$usersInfoId            = $auth['users_info_id'];
			$userData               = new stdClass;
			$userData->country_code = Redshop::getConfig()->get('DEFAULT_VAT_COUNTRY');
			$userData->state_code   = Redshop::getConfig()->get('DEFAULT_VAT_STATE');

			if ($usersInfoId && (Redshop::getConfig()->get('REGISTER_METHOD') == 1 || Redshop::getConfig()->get('REGISTER_METHOD') == 2)
				&& (Redshop::getConfig()->get('VAT_BASED_ON') == 2 || Redshop::getConfig()->get('VAT_BASED_ON') == 1)
			)
			{
				$userQuery = $db->getQuery(true)
					->select($db->qn('country_code'))
					->select($db->qn('state_code'))
					->from($db->qn('#__redshop_users_info', 'u'))
					->leftJoin(
						$db->qn('#__redshop_shopper_group', 'sh')
						. ' ON ' . $db->qn('sh.shopper_group_id') . ' = ' . $db->qn('u.shopper_group_id')
					)
					->where($db->qn('u.users_info_id') . ' = ' . $db->quote((int) $usersInfoId))
					->order($db->qn('u.users_info_id') . ' ASC');
				$userData  = $db->setQuery($userQuery)->loadObject();
			}
		}

		if ($shippingTaxGroupId == 0)
		{
			$query->where($db->qn('tr.tax_group_id') . ' = ' . $db->quote((int) Redshop::getConfig()->get('DEFAULT_VAT_GROUP')));
		}
		elseif ($shippingTaxGroupId > 0)
		{
			$query->leftJoin(
				$db->qn('#__redshop_shipping_rate', 's')
				. ' ON ' . $db->qn('tr.tax_group_id') . ' = ' . $db->qn('s.shipping_tax_group_id')
			)
				->where($db->qn('s.shipping_tax_group_id') . ' = ' . $db->quote((int) $shippingTaxGroupId));
		}
		else
		{
			$query->where($db->qn('tr.tax_group_id') . ' = ' . $db->quote((int) Redshop::getConfig()->get('DEFAULT_VAT_GROUP')));
		}

		$query->select('tr.*')
			->from($db->qn('#__redshop_tax_rate', 'tr'))
			->where($db->qn('tr.tax_country') . ' = ' . $db->quote($userData->country_code) . ' OR' . $db->qn('tr.tax_country') . ' = ""')
			->where($db->qn('tr.tax_rate') . ' = ' . $db->quote($userData->state_code) . ' OR ' . $db->qn('tr.tax_rate') . ' = ""')
			->order($db->qn('tr.tax_rate') . 'DESC');

		return $db->setQuery($query)->loadObject();
	}

	/**
	 * Get shopper group default shipping
	 *
	 * @param   int $userId User id
	 *
	 * @return  array
	 *
	 * @since   2.0.0.3
	 */
	public static function getShopperGroupDefaultShipping($userId = 0)
	{
		$productHelper = productHelper::getInstance();
		$shippingArr   = array();
		$user          = JFactory::getUser();

		// FOR OFFLINE ORDER
		if ($userId == 0)
		{
			$userId = $user->id;
		}

		if (!$userId)
		{
			return array();
		}

		$result = $productHelper->getUserInformation($userId);

		if (!empty($result) && $result->default_shipping == 1)
		{
			$shippingArr['shipping_rate'] = 0;
			$shippingArr['shipping_vat']  = 0;

			$row = self::getShippingVatRates(0);

			if (!empty($row))
			{
				$total       = 0;
				$shippingVat = 0;

				if ($row->tax_rate > 0)
				{
					$shippingVat  = $result->default_shipping_rate * $row->tax_rate;
					$total       += $shippingVat + $result->default_shipping_rate;
					$shippingVat .= '<br />';
				}

				$shippingArr['shipping_vat']  = $shippingVat;
				$shippingArr['shipping_rate'] = $total;

				return $shippingArr;
			}

			$shippingArr['shipping_rate'] = $result->default_shipping_rate;

			return $shippingArr;
		}

		return $shippingArr;
	}

	/**
	 * Find first number position
	 *
	 * @param   string $haystack string to find
	 * @param   array  $needles  array to find
	 * @param   int    $offset   position
	 *
	 * @return  mixed
	 *
	 * @since   2.0.0.3
	 */
	public static function strposa($haystack, $needles = array(), $offset = 0)
	{
		$chr = array();

		foreach ($needles as $needle)
		{
			if (strpos($haystack, $needle, $offset) !== false)
			{
				$chr[] = strpos($haystack, $needle, $offset);
			}
		}

		if (empty($chr))
		{
			return false;
		}

		return min($chr);
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
	 * @deprecated 2.1.0
	 * @see Redshop\Shipping\Rate::filterRatesByPriority
	 */
	public static function filterRatesByPriority($shippingRates)
	{
		return Redshop\Shipping\Rate::filterRatesByPriority($shippingRates);
	}

	/**
	 * Function to get product volume shipping
	 *
	 * @return array $cases , 3cases of shipping
	 *
	 * @since   2.0.0.3
	 */
	public static function getProductVolumeShipping()
	{
		$session = JFactory::getSession();
		$cart    = $session->get('cart');
		$idx     = (int) ($cart['idx']);

		$length  = array();
		$width   = array();
		$height  = array();
		$lengthQ = array();
		$widthQ  = array();
		$heightQ = array();
		$lMax    = 0;
		$lTotal  = 0;
		$wMax    = 0;
		$wTotal  = 0;
		$hMax    = 0;
		$hTotal  = 0;

		// Cart loop
		for ($i = 0; $i < $idx; $i++)
		{
			if (isset($cart[$i]['giftcard_id']) && $cart[$i]['giftcard_id'])
			{
				continue;
			}

			$data       = Redshop::product((int) $cart[$i]['product_id']);
			$length[$i] = $data->product_length;
			$width[$i]  = $data->product_width;
			$height[$i] = $data->product_height;
			$tmpArr     = array($length[$i], $width[$i], $height[$i]);
			$switch     = array_search(min($tmpArr), $tmpArr);

			switch ($switch)
			{
				case 0:
					$lengthQ[$i] = $data->product_length * $cart[$i]['quantity'];
					$widthQ[$i]  = $data->product_width;
					$heightQ[$i] = $data->product_height;
					break;
				case 1:
					$lengthQ[$i] = $data->product_length;
					$widthQ[$i]  = $data->product_width * $cart[$i]['quantity'];
					$heightQ[$i] = $data->product_height;
					break;
				case 2:
					$lengthQ[$i] = $data->product_length;
					$widthQ[$i]  = $data->product_width;
					$heightQ[$i] = $data->product_height * $cart[$i]['quantity'];
					break;
			}
		}

		// Get maximum length
		if (count($length) > 0)
		{
			$lMax = max($length);
		}

		// Get total length
		if (count($lengthQ) > 0)
		{
			$lTotal = array_sum($lengthQ);
		}

		// Get maximum width
		if (count($width) > 0)
		{
			$wMax = max($width);
		}

		// Get total width
		if (count($widthQ) > 0)
		{
			$wTotal = array_sum($widthQ);
		}

		// Get maximum height
		if (count($height) > 0)
		{
			$hMax = max($height);
		}

		// Get total height
		if (count($heightQ) > 0)
		{
			$hTotal = array_sum($heightQ);
		}

		// 3 cases are available for shipping boxes
		$cases              = array();
		$cases[0]['length'] = $lMax;
		$cases[0]['width']  = $wMax;
		$cases[0]['height'] = $hTotal;

		$cases[1]['length'] = $lMax;
		$cases[1]['width']  = $wTotal;
		$cases[1]['height'] = $hMax;

		$cases[2]['length'] = $lTotal;
		$cases[2]['width']  = $wMax;
		$cases[2]['height'] = $hMax;

		return $cases;
	}

	/**
	 * Function to get cart item dimension
	 *
	 * @return array
	 *
	 * @since   2.0.0.3
	 */
	public static function getCartItemDimension()
	{
		$session       = JFactory::getSession();
		$cart          = $session->get('cart');
		$idx           = (int) ($cart ['idx']);

		$totalQnt    = 0;
		$totalWeight = 0;
		$totalVolume = 0;
		$totalLength = 0;
		$totalHeight = 0;
		$totalWidth  = 0;

		for ($i = 0; $i < $idx; $i++)
		{
			if (isset($cart[$i]['giftcard_id']) && $cart[$i]['giftcard_id'])
			{
				continue;
			}

			$data      = Redshop::product((int) $cart[$i]['product_id']);
			$accWeight = 0;

			if (isset($cart[$i]['cart_accessory']) && count($cart[$i]['cart_accessory']) > 0)
			{
				foreach ($cart[$i]['cart_accessory'] as $index => $cartAccessory)
				{
					$accId  = $cartAccessory['accessory_id'];
					$accQty = 1;

					if (isset($cartAccessory['accessory_quantity']))
					{
						$accQty = $cartAccessory['accessory_quantity'];
					}

					if ($accData = RedshopHelperProduct::getProductById($accId))
					{
						$accWeight += ($accData->weight * $accQty);
					}
				}
			}

			$totalQnt    += $cart[$i]['quantity'];
			$totalWeight += (($data->weight * $cart[$i]['quantity']) + $accWeight);
			$totalVolume += ($data->product_volume * $cart[$i]['quantity']);
			$totalLength += ($data->product_length * $cart[$i]['quantity']);
			$totalHeight += ($data->product_height * $cart[$i]['quantity']);
			$totalWidth  += ($data->product_width * $cart[$i]['quantity']);
		}

		$ret = array(
			"totalquantity" => $totalQnt,
			"totalweight"   => $totalWeight,
			"totalvolume"   => $totalVolume,
			"totallength"   => $totalLength,
			"totalheight"   => $totalHeight,
			"totalwidth"    => $totalWidth
		);

		return $ret;
	}

	/**
	 * Get available shipping boxes according to cart items
	 *
	 * @return  array
	 *
	 * @since   2.0.0.3
	 */
	public static function getShippingBox()
	{
		if (is_null(static::$shippingBoxes))
		{
			$volumesShipping     = self::getProductVolumeShipping();
			$db                  = JFactory::getDbo();
			$whereShippingVolume = "";

			$query = $db->getQuery(true)
				->select('*')
				->from($db->qn('#__redshop_shipping_boxes'))
				->where($db->qn('published') . ' = 1')
				->order($db->qn('shipping_box_priority') . ' ASC');

			if (!empty($volumesShipping))
			{
				$whereShippingVolume .= '( ';
				$index                = 0;

				foreach ($volumesShipping as $volumeShipping)
				{
					$length = $volumeShipping['length'];
					$width  = $volumeShipping['width'];
					$height = $volumeShipping['height'];

					if ($index != 0)
					{
						$whereShippingVolume .= " OR ";
					}

					$whereShippingVolume .= " ( " . $db->qn('shipping_box_length') . " >= " . $length . " AND "
						. $db->qn('shipping_box_width') . " >= " . $width . " AND " . $db->qn('shipping_box_height') . " >= " . $height . ") ";

					$index++;
				}

				$whereShippingVolume .= " ) ";

				$query->where($whereShippingVolume);
			}

			static::$shippingBoxes = $db->setQuery($query)->loadObjectList();
		}

		return static::$shippingBoxes;
	}

	/**
	 * Get selected shipping BOX dimensions
	 *
	 * @param   int $boxId Shipping Box id
	 *
	 * @return  array  box dimensions
	 *
	 * @since   2.0.0.3
	 */
	public static function getBoxDimensions($boxId = 0)
	{
		$db                 = JFactory::getDbo();
		$whereShippingBoxes = array();

		if ($boxId)
		{
			$query = $db->getQuery(true)
				->select('*')
				->from($db->qn('#__redshop_shipping_boxes'))
				->where($db->qn('published') . ' = 1')
				->where($db->qn('shipping_box_id') . ' = ' . $db->quote((int) $boxId));

			$boxDetail = $db->setQuery($query)->loadObject();

			if (count($boxDetail) > 0)
			{
				$whereShippingBoxes['box_length'] = $boxDetail->shipping_box_length;
				$whereShippingBoxes['box_width']  = $boxDetail->shipping_box_width;
				$whereShippingBoxes['box_height'] = $boxDetail->shipping_box_height;
			}
		}

		return $whereShippingBoxes;
	}

	/**
	 * Get Shipping rate error
	 *
	 * @param   array  $data  Shipping rate data
	 *
	 * @return  string        Error text
	 *
	 * @since   2.0.0.3
	 */
	public static function getShippingRateError(&$data)
	{
		$bool = self::isCartDimensionMatch($data);

		if ($bool)
		{
			$bool = self::isUserInfoMatch($data);

			if ($bool)
			{
				$bool = self::isProductDetailMatch();

				if ($bool)
				{
					return '';
				}

				return JText::_("COM_REDSHOP_PRODUCT_DETAIL_NOT_MATCH");
			}

			return JText::_("COM_REDSHOP_USER_INFORMATION_NOT_MATCH");
		}

		return JText::_("COM_REDSHOP_CART_DIMENTION_NOT_MATCH");
	}

	/**
	 * Check cart dimension is matched
	 *
	 * @param   array  $data  Cart data
	 *
	 * @return  boolean
	 *
	 * @since   2.0.0.3
	 */
	public static function isCartDimensionMatch(&$data)
	{
		$orderSubtotal  = $data['order_subtotal'];
		$db             = JFactory::getDbo();
		$totalDimention = self::getCartItemDimension();
		$weightTotal    = $totalDimention['totalweight'];
		$volume         = $totalDimention['totalvolume'];

		// Product volume based shipping
		$volumeShipping      = self::getProductVolumeShipping();
		$whereShippingVolume = "";

		if (count($volumeShipping) > 0)
		{
			$whereShippingVolume .= " AND ( ";

			for ($g = 0, $gn = count($volumeShipping); $g < $gn; $g++)
			{
				$length = $volumeShipping[$g]['length'];
				$width  = $volumeShipping[$g]['width'];
				$height = $volumeShipping[$g]['height'];

				if ($g != 0)
				{
					$whereShippingVolume .= " OR ";
				}

				$whereShippingVolume .= "(
						(	(" . $db->quote($length) . " BETWEEN " . $db->qn('shipping_rate_length_start')
					. " AND " . $db->qn('shipping_rate_length_end') . ")
							OR (" . $db->qn('shipping_rate_length_start') . " = 0 AND "
					. $db->qn('shipping_rate_length_end') . " = 0))
						AND ((" . $db->quote($width) . " BETWEEN " . $db->qn('shipping_rate_width_start')
					. " AND " . $db->qn('shipping_rate_width_end') . ")
							OR (" . $db->qn('shipping_rate_width_start') . " = 0 AND "
					. $db->qn('shipping_rate_width_end') . " = 0))
						AND ((" . $db->quote($height) . " BETWEEN " . $db->qn('shipping_rate_height_start')
					. " AND " . $db->qn('shipping_rate_height_end') . ")
							OR (" . $db->qn('shipping_rate_height_start') . " = 0 AND "
					. $db->qn('shipping_rate_height_end') . " = 0))
						) ";
			}

			$whereShippingVolume .= " ) ";
		}

		$query = "SELECT * FROM " . $db->qn('#__redshop_shipping_rate')
			. "WHERE (" . $db->qn('shipping_class') . " = " . $db->quote('default_shipping') . " OR "
			. $db->qn('shipping_class') . " = " . $db->quote('shipper') . ") "
			. "AND ((" . $db->quote($volume) . " BETWEEN " . $db->qn('shipping_rate_volume_start')
			. " AND " . $db->qn('shipping_rate_volume_end') . ") OR (" . $db->qn('shipping_rate_volume_end') . " = 0) ) "
			. "AND ((" . $db->quote($orderSubtotal) . " BETWEEN " . $db->qn('shipping_rate_ordertotal_start')
			. " AND " . $db->qn('shipping_rate_ordertotal_end') . ") OR (" . $db->qn('shipping_rate_ordertotal_end') . " = 0)) "
			. "AND ((" . $db->quote($weightTotal) . " BETWEEN " . $db->qn('shipping_rate_weight_start')
			. " AND " . $db->qn('shipping_rate_weight_end') . ") OR (" . $db->qn('shipping_rate_weight_end') . " = 0)) "
			. $whereShippingVolume
			. " ORDER BY " . $db->qn('shipping_rate_priority');

		$shippingRate = $db->setQuery($query)->loadObjectList();

		if (count($shippingRate) > 0)
		{
			return true;
		}

		return false;
	}

	/**
	 * Check user info is matched
	 *
	 * @param   array &$data Cart data
	 *
	 * @return  boolean
	 *
	 * @since   2.0.0.3
	 */
	public static function isUserInfoMatch(&$data)
	{
		if (!isset($data['users_info_id']) || $data['users_info_id'] == 0)
		{
			return false;
		}

		$db           = JFactory::getDbo();
		$userInfo     = self::getShippingAddress($data['users_info_id']);
		$country      = $userInfo->country_code;
		$state        = $userInfo->state_code;
		$zip          = $userInfo->zipcode;
		$isCompany    = $userInfo->is_company;
		$whereShopper = '';
		$whereState   = '';

		if ($isCompany)
		{
			$where = "AND ( " . $db->qn('company_only') . " = 1 OR " . $db->qn('company_only') . " = 0) ";
		}
		else
		{
			$where = "AND ( " . $db->qn('company_only') . " = 2 OR " . $db->qn('company_only') . " = 0) ";
		}

		if ($country)
		{
			$whereCountry = "AND (FIND_IN_SET(" . (string) $db->quote($country) . ", " . $db->qn('shipping_rate_country') . " ) OR "
				. $db->qn('shipping_rate_country') . " = " . $db->quote(0) . " OR "
				. $db->qn('shipping_rate_country') . " = '') ";
		}
		else
		{
			$whereCountry = "AND (FIND_IN_SET(" . $db->quote(Redshop::getConfig()->get('DEFAULT_SHIPPING_COUNTRY')) . ", " . $db->qn('shipping_rate_country') . ")"
				. " OR " . $db->qn('shipping_rate_country') . " = " . $db->quote(0)
				. " OR " . $db->qn('shipping_rate_country') . " = " . $db->quote('')
				. " )";
		}

		$shopperGroup = RedshopHelperUser::getShopperGroupData($userInfo->user_id);

		if (count($shopperGroup) > 0)
		{
			$shopperGroupId = $shopperGroup->shopper_group_id;
			$whereShopper   = " AND (FIND_IN_SET(" . $db->quote((int) $shopperGroupId) . ", "
				. $db->qn('shipping_rate_on_shopper_group') . " ) OR "
				. $db->qn('shipping_rate_on_shopper_group') . " = '') ";
		}

		if ($state)
		{
			$whereState = "AND (FIND_IN_SET(" . (string) $db->quote($state) . ", " . $db->qn('shipping_rate_state') . " ) OR "
				. $db->qn('shipping_rate_state') . " = " . $db->quote(0) . " OR "
				. $db->qn('shipping_rate_state') . " = '') ";
		}

		$numbers = array("1", "2", "3", "4", "5", "6", "7", "8", "9", "0", " ");
		$zipCond = "";
		$zip     = trim($zip);

		if (strlen(str_replace($numbers, '', $zip)) == 0 && $zip != "")
		{
			$zipCond = "AND ( ( " . $db->qn('shipping_rate_zip_start') . " <= " . $db->quote($zip) . " AND "
				. $db->qn('shipping_rate_zip_end') . " >= " . $db->quote($zip) . " ) "
				. "OR (" . $db->qn('shipping_rate_zip_start') . " = " . $db->quote(0) . " AND " . $db->qn('shipping_rate_zip_end') . " = " . $db->quote(0) . ") "
				. "OR (" . $db->qn('shipping_rate_zip_start') . " = '' AND " . $db->qn('shipping_rate_zip_end') . " = '') ) ";
		}

		$query = "SELECT * FROM " . $db->qn('#__redshop_shipping_rate')
			. "WHERE (" . $db->qn('shipping_class') . " = " . $db->quote('default_shipping')
			. " OR " . $db->qn('shipping_class') . " = " . $db->quote('shipper') . ") "
			. $whereCountry
			. $whereState
			. $whereShopper
			. $zipCond
			. $where
			. " ORDER BY " . $db->qn('shipping_rate_priority');

		$shippingRate = $db->setQuery($query)->loadObjectList();

		if (count($shippingRate) > 0)
		{
			return true;
		}

		return false;
	}

	/**
	 * Check product detail is matched
	 *
	 * @return  boolean
	 *
	 * @since   2.0.0.3
	 */
	public static function isProductDetailMatch()
	{
		$db      = JFactory::getDbo();
		$session = JFactory::getSession();
		$cart    = $session->get('cart');
		$idx     = (int) ($cart['idx']);
		$pWhere  = "";
		$cWhere  = "";

		if ($idx)
		{
			$pWhere = 'OR ( ';

			for ($i = 0; $i < $idx; $i++)
			{
				$productId = $cart[$i]['product_id'];
				$pWhere    .= "FIND_IN_SET(" . $db->quote((int) $productId) . ", " . $db->qn('shipping_rate_on_product') . ")";

				if ($i != $idx - 1)
				{
					$pWhere .= " OR ";
				}
			}

			$pWhere .= ")";
		}

		$acWhere = array();

		for ($i = 0; $i < $idx; $i++)
		{
			$productId = $cart[$i]['product_id'];
			$query     = $db->getQuery(true)
				->select($db->qn('category_id'))
				->from($db->qn('#__redshop_product_category_xref'))
				->where($db->qn('product_id') . ' = ' . $db->quote((int) $productId));

			$categoryData = $db->setQuery($query)->loadObjectList();

			for ($c = 0, $cn = count($categoryData); $c < $cn; $c++)
			{
				$acWhere[] = " FIND_IN_SET(" . $db->quote((int) $categoryData [$c]->category_id) . ", " . $db->qn('shipping_rate_on_category') . ") ";
			}
		}

		if (isset($acWhere) && count($acWhere) > 0)
		{
			$acWhere = implode(' OR ', $acWhere);
			$cWhere  = ' OR (' . $acWhere . ')';
		}

		$query = "SELECT * FROM " . $db->qn('#__redshop_shipping_rate')
			. "WHERE (" . $db->qn('shipping_class') . " = " . $db->quote('default_shipping')
			. " OR " . $db->qn('shipping_class') . " = " . $db->quote('shipper') . " )"
			. "AND (" . $db->qn('shipping_rate_on_product') . " = '' " . $pWhere . ") AND ("
			. $db->qn('shipping_rate_on_category') . " = '' " . $cWhere . ") "
			. "ORDER BY " . $db->qn('shipping_rate_priority');

		$shippingRate = $db->setQuery($query)->loadObjectList();

		if (count($shippingRate) > 0)
		{
			return true;
		}

		return false;
	}

	/**
	 * Get free shipping rate
	 *
	 * @param   int $shippingRateId Shipping rate ID
	 *
	 * @return  string
	 *
	 * @since   2.0.0.3
	 * @throws  Exception
	 *
	 * @deprecated 2.1.0
	 * @see Redshop\Shipping\Rate::getFreeShippingRate
	 */
	public static function getFreeShippingRate($shippingRateId = 0)
	{
		return Redshop\Shipping\Rate::getFreeShippingRate($shippingRateId);
	}

	/**
	 * Load payment languages
	 *
	 * @param   boolean $all True for all (discover, enabled, disabled). False for just enabled only.
	 *
	 * @return   void
	 *
	 * @since   2.0.3
	 */
	public static function loadLanguages($all = false)
	{
		// Load shipping plugin language file
		if ($all)
		{
			$paymentsLangList = RedshopHelperUtility::getPlugins("redshop_shipping");
		}
		else
		{
			$paymentsLangList = RedshopHelperUtility::getPlugins("redshop_shipping", 1);
		}

		$language = JFactory::getLanguage();

		for ($index = 0, $ln = count($paymentsLangList); $index < $ln; $index++)
		{
			$extension = 'plg_redshop_shipping_' . $paymentsLangList[$index]->element;
			$language->load($extension, JPATH_ADMINISTRATOR, $language->getTag(), true);
			$language->load(
				$extension, JPATH_PLUGINS . '/' . $paymentsLangList[$index]->folder . '/' . $paymentsLangList[$index]->element,
				$language->getTag(),
				true
			);
		}
	}

	/**
	 * Method for get shipping table
	 *
	 * @param   array   $post      Available data.
	 * @param   integer $isCompany Is company?
	 * @param   array   $lists     List of data.
	 *
	 * @return  string
	 *
	 * @since  2.0.7
	 *
	 * @throws  Exception
	 */
	public static function getShippingTable($post = array(), $isCompany = 0, $lists = array())
	{
		$shippingTemplate = RedshopHelperTemplate::getTemplate("shipping_template");

		if (count($shippingTemplate) > 0 && $shippingTemplate[0]->template_desc != "")
		{
			$templateHtml = $shippingTemplate[0]->template_desc;
		}
		else
		{
			$templateHtml = '<table class="admintable" border="0"><tbody><tr><td width="100" align="right">{firstname_st_lbl}</td>'
				. '<td>{firstname_st}</td><td><span class="required">*</span></td></tr><tr><td width="100" align="right">{lastname_st_lbl}</td>'
				. '<td>{lastname_st}</td><td><span class="required">*</span></td></tr><tr><td width="100" align="right">{address_st_lbl}</td>'
				. '<td>{address_st}</td><td><span class="required">*</span></td></tr><tr><td width="100" align="right">{zipcode_st_lbl}</td>'
				. '<td>{zipcode_st}</td><td><span class="required">*</span></td></tr><tr><td width="100" align="right">{city_st_lbl}</td>'
				. '<td>{city_st}</td><td><span class="required">*</span></td></tr><tr id="{country_st_txtid}" style="{country_st_style}">'
				. '<td width="100" align="right">{country_st_lbl}</td><td>{country_st}</td><td><span class="required">*</span></td>'
				. '</tr><tr id="{state_st_txtid}" style="{state_st_style}"><td width="100" align="right">{state_st_lbl}</td><td>{state_st}</td>'
				. '<td><span class="required">*</span></td></tr><tr><td width="100" align="right">{phone_st_lbl}</td><td>{phone_st}</td><td>'
				. '<span class="required">*</span></td></tr><tr><td colspan="3">{extra_field_st_start} <table border="0"><tbody><tr>'
				. '<td>{extra_field_st}</td></tr></tbody></table>{extra_field_st_end}</td></tr></tbody></table>';
		}

		if (!isset($post["phone_ST"]) || $post["phone_ST"] == 0)
		{
			$post["phone_ST"] = '';
		}

		$allowCustomer = $isCompany == 1 ? 'style="display:none;"' : '';
		$allowCompany  = $isCompany != 1 ? 'style="display:none;"' : '';

		$readOnly  = "";
		$countries = RedshopHelperWorld::getCountryList($post, 'country_code_ST', 'ST', 'inputbox form-control billingRequired valid', 'state_code_ST');

		$post['country_code_ST']  = $countries['country_code_ST'];
		$lists['country_code_ST'] = $countries['country_dropdown'];

		$states = RedshopHelperWorld::getStateList($post, 'state_code_ST', 'ST');

		$lists['state_code_ST'] = $states['state_dropdown'];

		$countryStyle = count($countries['countrylist']) == 1 && count($states['statelist']) == 0 ? 'display:none;' : '';
		$stateStyle   = $states['is_states'] <= 0 ? 'display:none;' : '';

		$templateHtml = str_replace("{firstname_st_lbl}", JText::_('COM_REDSHOP_FIRSTNAME'), $templateHtml);
		$value        = !empty($post["firstname_ST"]) ? $post["firstname_ST"] : '';
		$templateHtml = str_replace(
			"{firstname_st}",
			'<input class="inputbox form-control billingRequired valid" type="text" name="firstname_ST" id="firstname_ST" size="32" maxlength="250" '
			. 'value="' . $value . '" data-msg="' . JText::_('COM_REDSHOP_THIS_FIELD_IS_REQUIRED') . '"/>',
			$templateHtml
		);

		$templateHtml = str_replace("{lastname_st_lbl}", JText::_('COM_REDSHOP_LASTNAME'), $templateHtml);
		$value        = (!empty($post["lastname_ST"])) ? $post["lastname_ST"] : '';
		$templateHtml = str_replace(
			"{lastname_st}",
			'<input class="inputbox form-control billingRequired valid" type="text" name="lastname_ST" id="lastname_ST" size="32" maxlength="250" '
			. 'value="' . $value . '" data-msg="' . JText::_('COM_REDSHOP_THIS_FIELD_IS_REQUIRED') . '"/>',
			$templateHtml
		);

		$templateHtml = str_replace("{address_st_lbl}", JText::_('COM_REDSHOP_ADDRESS'), $templateHtml);
		$value        = (!empty($post["address_ST"])) ? $post["address_ST"] : '';
		$templateHtml = str_replace(
			"{address_st}",
			'<input class="inputbox form-control billingRequired valid" type="text" name="address_ST" id="address_ST" size="32" maxlength="250" '
			. 'value="' . $value . '" data-msg="' . JText::_('COM_REDSHOP_THIS_FIELD_IS_REQUIRED') . '"/>',
			$templateHtml
		);

		$templateHtml = str_replace("{zipcode_st_lbl}", JText::_('COM_REDSHOP_ZIP'), $templateHtml);
		$value        = (!empty($post["zipcode_ST"])) ? $post["zipcode_ST"] : '';
		$templateHtml = str_replace(
			"{zipcode_st}",
			'<input class="inputbox form-control billingRequired valid zipcode" type="text" name="zipcode_ST" id="zipcode_ST" size="32" maxlength="10" '
			. 'value="' . $value . '" onblur="return autoFillCity(this.value,\'ST\');" '
			. 'data-msg="' . JText::_('COM_REDSHOP_YOUR_MUST_PROVIDE_A_ZIP') . '" />',
			$templateHtml
		);

		$templateHtml = str_replace("{city_st_lbl}", JText::_('COM_REDSHOP_CITY'), $templateHtml);
		$value        = (!empty($post["city_ST"])) ? $post["city_ST"] : '';
		$templateHtml = str_replace(
			"{city_st}",
			'<input class="inputbox form-control billingRequired valid" type="text" name="city_ST" ' . $readOnly . ' id="city_ST" '
			. 'value="' . $value . '" size="32" maxlength="250" data-msg="' . JText::_('COM_REDSHOP_THIS_FIELD_IS_REQUIRED') . '"/>',
			$templateHtml
		);

		$templateHtml = str_replace("{phone_st_lbl}", JText::_('COM_REDSHOP_PHONE'), $templateHtml);
		$value        = (!empty($post["phone_ST"])) ? $post["phone_ST"] : '';
		$templateHtml = str_replace(
			"{phone_st}",
			'<input class="inputbox form-control billingRequired valid phone" type="text" name="phone_ST" id="phone_ST" size="32" maxlength="250" '
			. 'value="' . $value . '" onblur="return searchByPhone(this.value,\'ST\');" '
			. 'data-msg="' . JText::_('COM_REDSHOP_YOUR_MUST_PROVIDE_A_VALID_PHONE') . '"/>',
			$templateHtml
		);

		$templateHtml = str_replace("{country_st_txtid}", "div_country_st_txt", $templateHtml);
		$templateHtml = str_replace("{country_st_style}", $countryStyle, $templateHtml);
		$templateHtml = str_replace("{state_st_txtid}", "div_state_st_txt", $templateHtml);
		$templateHtml = str_replace("{state_st_style}", $stateStyle, $templateHtml);
		$templateHtml = str_replace("{country_st_lbl}", JText::_('COM_REDSHOP_COUNTRY'), $templateHtml);
		$templateHtml = str_replace("{country_st}", $lists['country_code_ST'], $templateHtml);
		$templateHtml = str_replace("{state_st_lbl}", JText::_('COM_REDSHOP_STATE'), $templateHtml);
		$templateHtml = str_replace("{state_st}", $lists ['state_code_ST'], $templateHtml);

		if (strpos($templateHtml, "{extra_field_st_start}") !== false && strpos($templateHtml, "{extra_field_st_end}") !== false)
		{
			$htmlStart  = explode('{extra_field_st_start}', $templateHtml);
			$htmlEnd    = explode('{extra_field_st_end}', $htmlStart[1]);
			$htmlMiddle = $htmlEnd[0];

			$companyExtraField = (Redshop::getConfig()->get('ALLOW_CUSTOMER_REGISTER_TYPE') != 1 && $lists['shipping_company_field'] != "") ?
				$lists['shipping_company_field'] : "";
			$userExtraField    = (Redshop::getConfig()->get('ALLOW_CUSTOMER_REGISTER_TYPE') != 2 && $lists['shipping_customer_field'] != "") ?
				$lists['shipping_customer_field'] : "";

			$htmlCompany = str_replace("{extra_field_st}", $companyExtraField, $htmlMiddle);
			$htmlUser    = str_replace("{extra_field_st}", $userExtraField, $htmlMiddle);

			$htmlCompany = '<div id="exCompanyFieldST" ' . $allowCompany . '>' . $htmlCompany . '</div>';
			$htmlUser    = '<div id="exCustomerFieldST" ' . $allowCustomer . '>' . $htmlUser . '</div>';

			$templateHtml = $htmlStart[0] . $htmlCompany . $htmlUser . $htmlEnd[1];
		}

		JPluginHelper::importPlugin('redshop_checkout');
		RedshopHelperUtility::getDispatcher()->trigger('onRenderShippingCheckout', array(&$templateHtml));

		return $templateHtml;
	}
}
