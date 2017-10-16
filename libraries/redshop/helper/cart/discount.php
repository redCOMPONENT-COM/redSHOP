<?php
/**
 * @package     RedSHOP.Library
 * @subpackage  Helper
 *
 * @copyright   Copyright (C) 2008 - 2017 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */
defined('_JEXEC') or die;

use Joomla\Utilities\ArrayHelper;

/**
 * Class Redshop Helper for Cart - Discount
 *
 * @since  2.0.7
 */
class RedshopHelperCartDiscount
{
	/**
	 * @param   string   $extraIds   Extra Ids
	 * @param   integer  $productId  Product id
	 *
	 * @return  array<object>
	 *
	 * @since   2.0.7
	 */
	public static function getDiscountCalcDataExtra($extraIds = "", $productId = 0)
	{
		$db    = JFactory::getDbo();
		$query = $db->getQuery(true);

		$query->select('*')->from($db->qn('#__product_discount_calc_extra'));

		if (!empty($extraIds))
		{
			// Secure
			$extraIds = explode(',', $extraIds);
			$extraIds = ArrayHelper::toInteger($extraIds);

			$query->where($db->qn('pdcextra_id') . ' IN (' . implode(',', $extraIds) . ')');
		}

		if ($productId)
		{
			$query->where($db->qn('product_id') . ' = ' . (int) $productId);
		}

		$query->order($db->qn('option_name'));

		return $db->setQuery($query)->loadObjectList();
	}

	/**
	 * Method for apply coupon to cart.
	 *
	 * @param   array  $cartData  Cart data
	 *
	 * @return  array|bool        Array of cart or boolean value.
	 *
	 * @since   2.0.7
	 */
	public static function applyCoupon($cartData = array())
	{
		$couponCode = JFactory::getApplication()->input->getString('discount_code', '');
		$cart       = empty($cartData) ? RedshopHelperCartSession::getCart() : $cartData;

		if (empty($couponCode))
		{
			return !empty($cartData) ? $cart : false;
		}

		$view   = JFactory::getApplication()->input->getCmd('view', '');
		$user   = JFactory::getUser();
		$db     = JFactory::getDbo();
		$return = false;

		$coupon = rsCarthelper::getInstance()->getcouponData($couponCode, $cart['product_subtotal']);

		if (!empty($coupon))
		{
			$discountType = $coupon->percent_or_total;
			$couponId     = $coupon->coupon_id;
			$couponType   = $coupon->coupon_type;
			$couponUser   = $coupon->userid;
			$userType     = false;
			$return       = true;
			$counter      = 0;

			foreach ($cart['coupon'] as $key => $val)
			{
				if ($val['coupon_code'] == $couponCode)
				{
					$counter++;
				}
			}

			if ($coupon->coupon_left <= $counter)
			{
				return false;
			}

			if ($couponType == 1)
			{
				if (!$user->id)
				{
					return false;
				}

				$query = $db->getQuery(true)
					->select('SUM(' . $db->qn('coupon_value') . ') AS usertotal')
					->from($db->qn('#__redshop_coupons_transaction'))
					->where($db->qn('userid') . ' = ' . (int) $user->id)
					->group($db->qn('userid'));

				// Set the query and load the result.
				$userData = $db->setQuery($query)->loadResult();

				if (!empty($userData))
				{
					$userType = $couponUser != $userData->userid;
				}
				else
				{
					if ($couponUser != $user->id)
					{
						return false;
					}

					$return = false;
				}
			}

			if (!$userType)
			{
				$return = true;
			}

			$productSubtotal = $cart['product_subtotal'];
			$subTotal        = $productSubtotal;

			if ($view == 'cart')
			{
				$subTotal = $productSubtotal - $cart['voucher_discount'] - $cart['cart_discount'];
			}

			if ($discountType == 0)
			{
				$avgVAT = 1;

				if ((float) Redshop::getConfig()->get('VAT_RATE_AFTER_DISCOUNT') && !Redshop::getConfig()->get('APPLY_VAT_ON_DISCOUNT'))
				{
					$avgVAT = $subTotal / $cart['product_subtotal_excl_vat'];
				}

				$couponValue = $avgVAT * $coupon->coupon_value;
			}
			else
			{
				$couponValue = ($subTotal * $coupon->coupon_value) / (100);
			}

			$key = rsCarthelper::getInstance()->rs_multi_array_key_exists('coupon', $cart);

			$coupons = array();

			if (!$key)
			{
				$oldCoupons  = array();
				$couponIndex = 0;
			}
			else
			{
				$oldCoupons  = $cart['coupon'];
				$couponIndex = count($oldCoupons) + 1;
			}

			if ($couponValue < 0)
			{
				return;
			}

			if (!Redshop::getConfig()->get('APPLY_VOUCHER_COUPON_ALREADY_DISCOUNT'))
			{
				$couponValue = rsCarthelper::getInstance()->calcAlreadyDiscount($couponValue, $cart);
			}

			$couponRemaining = 0;

			if ($couponValue > $subTotal)
			{
				$couponRemaining = $couponValue - $subTotal;
				$couponValue     = $subTotal;
			}

			if (!is_null($cart['total']) && $cart['total'] == 0 && $view != 'cart')
			{
				$couponValue = 0;
			}

			$valueExist = is_array($cart['coupon']) ? rsCarthelper::getInstance()->rs_recursiveArraySearch($cart['coupon'], $couponCode) : 0;

			switch (Redshop::getConfig()->get('DISCOUNT_TYPE'))
			{
				case 4:
					if ($valueExist)
					{
						$return = true;
					}

					break;

				case 3:
					if ($valueExist && $key)
					{
						$return = false;

					}

					break;

				case 2:
					$voucherKey = rsCarthelper::getInstance()->rs_multi_array_key_exists('voucher', $cart);

					if ($valueExist || $voucherKey)
					{
						$return = false;
					}

					break;

				case 1:
				default:
					$coupons    = array();
					$oldCoupons = array();
					unset($cart['voucher']);
					unset($cart['coupon']);
					$cart['cart_discount']    = 0;
					$cart['voucher_discount'] = 0;

					$return = true;

					break;
			}

			if ($return)
			{
				$transactionCouponId = 0;

				if (rsCarthelper::getInstance()->rs_multi_array_key_exists('transaction_coupon_id', $coupon))
				{
					$transactionCouponId = $coupon->transaction_coupon_id;
				}

				$coupons['coupon'][$couponIndex]['coupon_code']               = $couponCode;
				$coupons['coupon'][$couponIndex]['coupon_id']                 = $couponId;
				$coupons['coupon'][$couponIndex]['used_coupon']               = 1;
				$coupons['coupon'][$couponIndex]['coupon_value']              = $couponValue;
				$coupons['coupon'][$couponIndex]['remaining_coupon_discount'] = $couponRemaining;
				$coupons['coupon'][$couponIndex]['transaction_coupon_id']     = $transactionCouponId;

				$coupons['coupon']     = array_merge($coupons['coupon'], $oldCoupons);
				$cart                  = array_merge($cart, $coupons);
				$cart['free_shipping'] = $coupon->free_shipping;
				RedshopHelperCartSession::setCart($cart);
			}
		}
		elseif (Redshop::getConfig()->get('VOUCHERS_ENABLE'))
		{
			$return = self::applyVoucher();
		}

		if (!empty($cartData))
		{
			return $cart;
		}

		return $return;
	}

	/**
	 * Method for apply voucher to cart.
	 *
	 * @param   array  $cartData  Cart data
	 *
	 * @return  array|bool        Array of cart or boolean value.
	 *
	 * @since   2.0.7
	 */
	public static function applyVoucher($cartData = array())
	{
		$voucherCode = JFactory::getApplication()->input->getString('discount_code', '');
		$cart        = empty($cartData) ? RedshopHelperCartSession::getCart() : $cartData;

		if (empty($voucherCode))
		{
			return !empty($cartData) ? $cart : false;
		}

		$voucher = rsCarthelper::getInstance()->getVoucherData($voucherCode);

		if (null === $voucher)
		{
			return !empty($cartData) ? $cart : false;
		}

		$counter = 0;

		foreach ($cart['voucher'] as $val)
		{
			if ($val['voucher_code'] == $voucherCode)
			{
				$counter++;
			}
		}

		if ($voucher->voucher_left <= $counter)
		{
			return false;
		}

		$return    = true;
		$type      = $voucher->type;
		$voucherId = $voucher->id;

		$productId  = $voucher->nproduct;
		$productArr = rsCarthelper::getInstance()->getCartProductPrice($productId, $cart, $voucher->voucher_left);

		if (empty($productArr['product_ids']))
		{
			$return = false;
		}

		$productPrice    = $productArr['product_price'];
		$productIds      = $productArr['product_ids'];
		$productQuantity = $productArr['product_quantity'];
		$productQuantity = $productQuantity > $voucher->voucher_left ? $voucher->voucher_left : $productQuantity;

		if ($type != 'Percentage')
		{
			$voucher->total *= $productQuantity;
			$voucherValue   = $voucher->total;
		}
		else
		{
			$voucherValue = ($productPrice * $voucher->total) / (100);
		}

		$vouchers    = array();
		$oldVouchers = array();

		$multiArrayKeyExists = rsCarthelper::getInstance()->rs_multi_array_key_exists('voucher', $cart);

		if (!$multiArrayKeyExists)
		{
			$voucherIndex = 0;
		}
		else
		{
			$oldVouchers  = $cart['voucher'];
			$voucherIndex = count($oldVouchers) + 1;
		}

		if (!Redshop::getConfig()->get('APPLY_VOUCHER_COUPON_ALREADY_DISCOUNT'))
		{
			$voucherValue = rsCarthelper::getInstance()->calcAlreadyDiscount($voucherValue, $cart);
		}

		$remainingVoucherDiscount = 0;

		$totalDiscount = $cart['voucher_discount'] + $cart['cart_discount'] + $cart['coupon_discount'];
		$subTotal      = $productPrice - $cart['coupon_discount'] - $cart['cart_discount'];

		if ($productPrice < $voucherValue)
		{
			$remainingVoucherDiscount = $voucherValue - $productPrice;
			$voucherValue             = $productPrice;
		}
		elseif ($totalDiscount > $subTotal)
		{
			$remainingVoucherDiscount = $voucherValue;
			$voucherValue             = 0;
		}

		$valueExist = is_array($cart['voucher']) ? rsCarthelper::getInstance()->rs_recursiveArraySearch($cart['voucher'], $voucherCode) : 0;

		switch (Redshop::getConfig()->get('DISCOUNT_TYPE'))
		{
			case 4:
				if ($valueExist)
				{
					$return = true;
				}

				break;

			case 3:
				if ($valueExist && $multiArrayKeyExists)
				{
					$return = false;
				}

				break;

			case 2:
				$couponKey = rsCarthelper::getInstance()->rs_multi_array_key_exists('coupon', $cart);

				if ($valueExist || $couponKey)
				{
					$return = false;
				}

				break;

			case 1:
			default:
				$vouchers    = array();
				$oldVouchers = array();

				unset($cart['coupon']);

				$cart['cart_discount']    = 0;
				$cart['coupon_discount']  = 0;
				$cart['voucher_discount'] = 0;

				$return = true;

				break;
		}

		if ($return)
		{
			$transactionVoucherId = 0;

			if (rsCarthelper::getInstance()->rs_multi_array_key_exists('transaction_voucher_id', $voucher))
			{
				$transactionVoucherId = $voucher->transaction_voucher_id;
			}

			$vouchers['voucher'][$voucherIndex]['voucher_code']               = $voucherCode;
			$vouchers['voucher'][$voucherIndex]['voucher_id']                 = $voucherId;
			$vouchers['voucher'][$voucherIndex]['product_id']                 = $productIds;
			$vouchers['voucher'][$voucherIndex]['used_voucher']               = $productQuantity;
			$vouchers['voucher'][$voucherIndex]['voucher_value']              = $voucherValue;
			$vouchers['voucher'][$voucherIndex]['remaining_voucher_discount'] = $remainingVoucherDiscount;
			$vouchers['voucher'][$voucherIndex]['transaction_voucher_id']     = $transactionVoucherId;

			$vouchers['voucher']   = array_merge($vouchers['voucher'], $oldVouchers);
			$cart                  = array_merge($cart, $vouchers);
			$cart['free_shipping'] = $voucher->free_ship;

			RedshopHelperCartSession::setCart($cart);
		}

		if (!empty($cartData))
		{
			return $cart;
		}

		return $return;
	}
}
