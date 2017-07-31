<?php
/**
 * @package     RedSHOP.Library
 * @subpackage  Helper
 *
 * @copyright   Copyright (C) 2008 - 2017 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 *
 * @since       2.0.7
 */
defined('_JEXEC') or die;

/**
 * Class Redshop Helper for Cart - Discount
 *
 * @since  2.0.7
 */
class RedshopHelperCartDiscount
{
	/**
	 * @param   string  $pdcextraids
	 * @param   integer $productId
	 *
	 * @return  array<object>
	 *
	 * @since   __DEPLOY_VERSION__
	 */
	public static function getDiscountCalcDataExtra($pdcextraids = "", $productId = 0)
	{
		$db    = JFactory::getDbo();
		$query = $db->getQuery(true);

		$query->select('*')->from($db->quoteName('#__product_discount_calc_extra'));

		if (!empty($pdcextraids))
		{
			// Secure $pdcextraids
			if ($extraIds = explode(',', $pdcextraids))
			{
				$extraIds = Joomla\Utilities\ArrayHelper::toInteger($extraIds);
			}

			$query->where($db->quoteName('pdcextra_id') . ' IN (' . implode(',', $extraIds) . ')');
		}

		if ($productId)
		{
			$query->where($db->quoteName('product_id') . ' = ' . (int) $productId);
		}

		$query->order($db->quoteName('option_name'));

		return $db->setQuery($query)->loadObjectList();
	}

	/**
	 * Method for apply coupon to cart.
	 *
	 * @param   array  $cartData  Cart data
	 *
	 * @return  array|bool        Array of cart or boolean value.
	 *
	 * @since   __DEPLOY_VERSION__
	 */
	public static function applyCoupon($cartData = array())
	{
		$couponCode = JFactory::getApplication()->input->get('discount_code', '');
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

			if (!$key)
			{
				$coupons     = array();
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
				RedshopHelperCartSession::set($cart);
			}
		}
		elseif (Redshop::getConfig()->get('VOUCHERS_ENABLE'))
		{
			$return = rsCarthelper::getInstance()->voucher();
		}

		if (!empty($cartData))
		{
			return $cart;
		}

		return $return;
	}
}
