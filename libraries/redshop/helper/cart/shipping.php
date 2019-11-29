<?php
/**
 * @package     RedSHOP.Library
 * @subpackage  Helper
 *
 * @copyright   Copyright (C) 2008 - 2019 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */
defined('_JEXEC') or die;

/**
 * Class Redshop Helper for Cart - Shipping
 *
 * @since  2.1.0
 */
class RedshopHelperCartShipping
{
	/**
	 * List of default shipping
	 *
	 * @var  array
	 *
	 * @since  2.1.0
	 */
	protected static $defaultShipping = array();

	/**
	 * Get Shipping rate for cart
	 *
	 * @param   array $data Shipping data
	 *
	 * @return  array
	 *
	 * @since   2.1.0
	 */
	public static function getDefault($data)
	{
		$shipping = RedshopHelperShipping::getShopperGroupDefaultShipping();

		if (!empty($shipping))
		{
			return $shipping;
		}

		$userInfo = RedshopHelperOrder::getBillingAddress();
		$key      = md5(serialize($data)) . md5(serialize($userInfo));

		if (array_key_exists($key, self::$defaultShipping))
		{
			return self::$defaultShipping[$key];
		}

		$db             = JFactory::getDbo();
		$orderSubtotal  = $data['order_subtotal'];
		$totalDimension = RedshopHelperShipping::getCartItemDimension();
		$weightTotal    = $totalDimension['totalweight'];
		$volume         = $totalDimension['totalvolume'];
		$country        = Redshop::getConfig()->get('DEFAULT_SHIPPING_COUNTRY');
		$state          = '';
		$isCompany      = 0;
		$whereState     = '';
		$whereShopper   = '';
		$userId         = JFactory::getUser()->id;

		if ($userInfo)
		{
			$country   = $userInfo->country_code;
			$isCompany = (int) $userInfo->is_company;
			$userId    = $userInfo->user_id;
			$state     = $userInfo->state_code;
		}

		$shopperGroup = RedshopHelperUser::getShopperGroupData($userId);

		if (null !== $shopperGroup)
		{
			$whereShopper = ' AND (FIND_IN_SET(' . $db->quote((int) $shopperGroup->shopper_group_id) . ', '
				. $db->qn('shipping_rate_on_shopper_group') . ' ) OR '
				. $db->qn('shipping_rate_on_shopper_group') . ' = "") ';
		}

		$whereCountry = '(FIND_IN_SET(' . (string) $db->quote($country) . ', ' . $db->qn('shipping_rate_country') . ') OR '
			. $db->qn('shipping_rate_country') . ' = ' . $db->quote(0) . ' OR ' . $db->qn('shipping_rate_country') . ' = "")';

		if ($state)
		{
			$whereState = ' AND (FIND_IN_SET(' . (string) $db->quote($state) . ', ' . $db->qn('shipping_rate_state') . ') OR '
				. $db->qn('shipping_rate_state') . ' = ' . $db->quote(0) . ' OR ' . $db->qn('shipping_rate_state') . ' = "")';
		}

		$companyOnly = !$isCompany ? 2 : 1;
		$isWhere     = ' AND (' . $db->qn('company_only') . ' = ' . $companyOnly . ' OR ' . $db->qn('company_only') . ' = 0) ';

		$shippingRate = self::getShippingRateFirst(
			$volume, $weightTotal, $orderSubtotal, $whereCountry, $isWhere, $whereState, $whereShopper
		);

		if (null === $shippingRate)
		{
			$shippingRate = self::getShippingRateSecond($volume, $weightTotal, $orderSubtotal, $whereCountry, $isWhere, $whereState, $whereShopper);
		}

		if (null === $shippingRate)
		{
			$shippingRate = self::getShippingRateThird($volume, $weightTotal, $orderSubtotal, $whereCountry, $isWhere, $whereState, $whereShopper);
		}

		self::$defaultShipping[$key] = array('shipping_rate' => 0, 'shipping_vat' => 0);

		if (null === $shippingRate)
		{
			return self::$defaultShipping[$key];
		}

		if ($shippingRate->apply_vat != 1)
		{
			self::$defaultShipping[$key]['shipping_rate'] = $shippingRate->shipping_rate_value;

			return self::$defaultShipping[$key];
		}

		$result = RedshopHelperShipping::getShippingVatRates($shippingRate->shipping_tax_group_id, $data);
		$addVat = RedshopHelperCart::taxExemptAddToCart($userId);

		if (!empty($result) && $addVat && $result->tax_rate > 0)
		{
			$shippingVat = $shippingRate->shipping_rate_value * $result->tax_rate;
			$total       = $shippingVat + $shippingRate->shipping_rate_value;

			self::$defaultShipping[$key]['shipping_rate'] = $total;
			self::$defaultShipping[$key]['shipping_vat']  = $shippingVat;
		}

		return self::$defaultShipping[$key];
	}

	/**
	 * Method for get shipping rate base on weight and volume
	 *
	 * @param   integer $volume        Volume
	 * @param   float   $weightTotal   Weight total
	 * @param   integer $orderSubtotal Order subtotal
	 * @param   string  $whereCountry  Where country
	 * @param   string  $isWhere       Is where
	 * @param   string  $whereState    Where state
	 * @param   string  $whereShopper  Where shopper
	 *
	 * @return  mixed
	 *
	 * @since  2.1.0
	 */
	public static function getShippingRateFirst($volume = 0, $weightTotal = 0.0, $orderSubtotal = 0, $whereCountry = '', $isWhere = '',
	                                            $whereState = '', $whereShopper = '')
	{
		$cart = RedshopHelperCartSession::getCart();
		$idx  = (int) $cart['idx'];

		if (!$idx)
		{
			return null;
		}

		$db = JFactory::getDbo();

		$productWhere = self::prepareProductWhere();
		$sql          = ' SELECT * '
			. ' FROM ' . $db->qn('#__redshop_shipping_rate', 'sr')
			. ' LEFT JOIN ' . $db->qn('#__extensions', 's')
			. ' ON ' . $db->qn('sr.shipping_class') . ' = ' . $db->qn('s.element')
			. ' WHERE ' . $db->qn('s.folder') . ' = ' . $db->quote('redshop_shipping')
			. ' AND ' . $db->qn('s.enabled') . ' = 1 '
			. ' AND ' . $whereCountry . $isWhere
			. ' AND ( '
			. ' ( ' . $db->qn('shipping_rate_volume_start') . ' <= ' . $db->quote($volume)
			. ' AND ' . $db->qn('shipping_rate_volume_end') . ' >= ' . $db->quote($volume) . ' ) '
			. ' OR ( ' . $db->qn('shipping_rate_volume_end') . ' = 0) '
			. ' ) '
			. ' AND ( '
			. ' ( ' . $db->qn('shipping_rate_ordertotal_start') . ' <= ' . $db->quote($orderSubtotal)
			. ' AND ' . $db->qn('shipping_rate_ordertotal_end') . ' >= ' . $db->quote($orderSubtotal) . ' ) '
			. ' OR ( ' . $db->qn('shipping_rate_ordertotal_end') . ' = 0 '
			. ' ) '
			. ' ) '
			. ' AND ( '
			. ' ( ' . $db->qn('shipping_rate_weight_start') . ' <= ' . $db->quote($weightTotal)
			. ' AND ' . $db->qn('shipping_rate_weight_end') . ' >= ' . $db->quote($weightTotal)
			. ' ) '
			. ' OR ( ' . $db->qn('shipping_rate_weight_end') . ' = 0 ) '
			. ' ) '
			. $productWhere . $whereState . $whereShopper
			. ' ORDER BY ' . $db->qn('s.ordering') . ' , ' . $db->qn('sr.shipping_rate_priority') . ' LIMIT 0,1 ';

		return $db->setQuery($sql)->loadObject();
	}

	/**
	 * Method for prepare where product
	 *
	 * @return string
	 *
	 * @since  2.1.0
	 */
	public static function prepareProductWhere()
	{
		$cart = RedshopHelperCartSession::getCart();
		$idx  = (int) $cart['idx'];

		if (!$idx)
		{
			return '';
		}

		$db = JFactory::getDbo();

		$pWhere = 'AND ( ';

		for ($i = 0; $i < $idx; $i++)
		{
			$productId = (int) $cart[$i]['product_id'];

			$pWhere .= 'FIND_IN_SET(' . $productId . ', ' . $db->qn('shipping_rate_on_product') . ')';

			if ($i != $idx - 1)
			{
				$pWhere .= " OR ";
			}
		}

		$pWhere .= ")";

		return $pWhere;
	}

	/**
	 * Method for get shipping rate second round
	 *
	 * @param   integer $volume        Volume
	 * @param   float   $weightTotal   Weight total
	 * @param   integer $orderSubtotal Order subtotal
	 * @param   string  $whereCountry  Where country
	 * @param   string  $isWhere       Is where
	 * @param   string  $whereState    Where state
	 * @param   string  $whereShopper  Where shopper
	 *
	 * @return  mixed
	 *
	 * @since  2.1.0
	 */
	public static function getShippingRateSecond($volume = 0, $weightTotal = 0.0, $orderSubtotal = 0, $whereCountry = '', $isWhere = '',
	                                             $whereState = '', $whereShopper = '')
	{
		$cart = RedshopHelperCartSession::getCart();
		$idx  = (int) $cart['idx'];

		if (!$idx)
		{
			return null;
		}

		$db = JFactory::getDbo();

		$where = self::prepareCategoryWhere();

		$sql = "SELECT * FROM " . $db->qn('#__redshop_shipping_rate') . " AS sr
								 LEFT JOIN " . $db->qn('#__extensions') . " AS s
								 ON
								 " . $db->qn('sr.shipping_class') . " = " . $db->qn('s.element') . "
								 WHERE " . $db->qn('s.folder') . " = " . $db->quote('redshop_shipping')
			. " AND " . $db->qn('s.enabled') . " = 1 AND" . $whereCountry . $whereShopper . $isWhere . "
								 AND ((" . $db->qn('shipping_rate_volume_start') . " <= " . $db->quote($volume)
			. " AND " . $db->qn('shipping_rate_volume_end') . " >= "
			. $db->quote($volume) . ") OR (" . $db->qn('shipping_rate_volume_end') . " = 0) )
								 AND ((" . $db->qn('shipping_rate_ordertotal_start') . " <= " . $db->quote($orderSubtotal)
			. " AND " . $db->qn('shipping_rate_ordertotal_end') . " >= "
			. $db->quote($orderSubtotal) . ")  OR (" . $db->qn('shipping_rate_ordertotal_end') . " = 0))
								 AND ((" . $db->qn('shipping_rate_weight_start') . " <= " . $db->quote($weightTotal)
			. " AND " . $db->qn('shipping_rate_weight_end') . " >= "
			. $db->quote($weightTotal) . ")  OR (" . $db->qn('shipping_rate_weight_end') . " = 0))"
			. $where . $whereState . "
								ORDER BY " . $db->qn('s.ordering') . ", " . $db->qn('sr.shipping_rate_priority') . " LIMIT 0,1";

		return $db->setQuery($sql)->loadObject();
	}

	/**
	 * Method for prepare where category
	 *
	 * @return string
	 *
	 * @since  2.1.0
	 */
	public static function prepareCategoryWhere()
	{
		$cart = RedshopHelperCartSession::getCart();
		$idx  = (int) $cart['idx'];

		if (!$idx)
		{
			return '';
		}

		$db = JFactory::getDbo();

		$where = '';

		for ($i = 0; $i < $idx; $i++)
		{
			if (!array_key_exists('product_id', $cart[$i]))
			{
				continue;
			}

			$productId = (int) $cart[$i]['product_id'];
			$product   = RedshopHelperProduct::getProductById($productId);

			if (empty($product->categories))
			{
				continue;
			}

			$where .= ' AND ( ';
			$index  = 0;

			foreach ($product->categories as $category)
			{
				$where .= " FIND_IN_SET(" . (int) $category . ", " . $db->qn('shipping_rate_on_category') . ") ";

				if ($index != count($product->categories) - 1)
				{
					$where .= " OR ";
				}

				$index++;
			}

			$where .= ")";
		}

		return $where;
	}

	/**
	 * Method for get shipping rate base on weight and volume
	 *
	 * @param   integer $volume        Volume
	 * @param   float   $weightTotal   Weight total
	 * @param   integer $orderSubtotal Order subtotal
	 * @param   string  $whereCountry  Where country
	 * @param   string  $isWhere       Is where
	 * @param   string  $whereState    Where state
	 * @param   string  $whereShopper  Where shopper
	 *
	 * @return  mixed
	 *
	 * @since  2.1.0
	 */
	public static function getShippingRateThird($volume = 0, $weightTotal = 0.0, $orderSubtotal = 0, $whereCountry = '', $isWhere = '',
	                                            $whereState = '', $whereShopper = '')
	{
		$db = JFactory::getDbo();

		$newProductWhere = str_replace("AND (", "OR (", self::prepareProductWhere());
		$newCwhere       = str_replace("AND (", "OR (", self::prepareCategoryWhere());

		$sql = "SELECT * FROM " . $db->qn('#__redshop_shipping_rate') . " AS sr
							 LEFT JOIN " . $db->qn('#__extensions') . " AS s
							 ON
							 " . $db->qn('sr.shipping_class') . " = " . $db->qn('s.element') . "
					WHERE " . $db->qn('s.folder') . " = " . $db->quote('redshop_shipping') . " AND " . $db->qn('s.enabled') . " = 1 AND "
			. $whereCountry . $whereShopper . $isWhere . $whereState . "
					AND ((" . $db->qn('shipping_rate_volume_start') . " <= " . $db->quote($volume)
			. " AND " . $db->qn('shipping_rate_volume_end') . " >= "
			. $db->quote($volume) . ") OR (" . $db->qn('shipping_rate_volume_end') . " = 0) )
					AND ((" . $db->qn('shipping_rate_ordertotal_start') . " <= " . $db->quote($orderSubtotal)
			. " AND " . $db->qn('shipping_rate_ordertotal_end') . " >= "
			. $db->quote($orderSubtotal) . ")  OR (" . $db->qn('shipping_rate_ordertotal_end') . " = 0))
					AND ((" . $db->qn('shipping_rate_weight_start') . " <= " . $db->quote($weightTotal)
			. " AND " . $db->qn('shipping_rate_weight_end') . " >= " . $db->quote($weightTotal) . ")"
			. " OR (" . $db->qn('shipping_rate_weight_end') . " = 0))
					AND (" . $db->qn('shipping_rate_on_product') . " = '' " . $newProductWhere . ")"
			. " AND (" . $db->qn('shipping_rate_on_category') . " = '' " . $newCwhere . " )
					ORDER BY " . $db->qn('s.ordering') . ", " . $db->qn('sr.shipping_rate_priority') . " LIMIT 0,1";

		return $db->setQuery($sql)->loadObject();
	}
}
