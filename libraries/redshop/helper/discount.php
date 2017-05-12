<?php
/**
 * @package     RedSHOP
 * @subpackage  Discount
 *
 * @copyright   Copyright (C) 2008 - 2017 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

defined('_JEXEC') or die;

use Joomla\Utilities\ArrayHelper;

/**
 * Discount class
 *
 * @since  2.0.3
 */
class RedshopHelperDiscount
{

	/**
	 * Method for get discount
	 *
	 * @param   int $subTotal Sub-total amount
	 * @param   int $userId   User ID
	 *
	 * @return  mixed
	 *
	 * @since  2.0.3
	 */
	public static function getDiscount($subTotal = 0, $userId = 0)
	{
		$db   = JFactory::getDbo();
		$user = JFactory::getUser();

		if (!$userId)
		{
			$userId = $user->id;
		}

		$userData       = RedshopHelperUser::createUserSession($userId);
		$shopperGroupId = (int) $userData['rs_user_shopperGroup'];

		$query = $db->getQuery(true)
			->select($db->qn('ds.discount_id'))
			->from($db->qn('#__redshop_discount_shoppers', 'ds'))
			->where($db->qn('ds.shopper_group_id') . ' = ' . $shopperGroupId);

		$result = $db->setQuery($query)->loadColumn();

		if (empty($result))
		{
			return false;
		}

		$result = array_merge(array(0 => '0'), $result);

		// Secure ids
		$result = ArrayHelper::toInteger($result);

		$query->clear()
			->select('*')
			->from($db->qn('#__redshop_discount'))
			->where($db->qn('published') . ' = 1')
			->where($db->qn('discount_id') . ' IN (' . implode(',', $result) . ')')
			->where($db->qn('start_date') . ' <= ' . time())
			->where($db->qn('end_date') . ' >= ' . time())
			->order($db->qn('amount') . ' DESC');

		$db->setQuery($query, 0, 1);

		if (!$subTotal)
		{
			return $db->setQuery($query)->loadObject();
		}

		$newQuery = clone $query;
		$newQuery->where($db->qn('condition') . ' = 2')
			->where($db->qn('amount') . ' = ' . $subTotal);

		$result = $db->setQuery($newQuery)->loadObject();

		if (!$result)
		{
			$newQuery = clone $query;
			$newQuery->where($db->qn('condition') . ' = 1')
				->where($db->qn('amount') . ' > ' . $subTotal);

			$result = $db->setQuery($newQuery)->loadObject();

			if (!$result)
			{
				$newQuery = clone $query;
				$newQuery->where($db->qn('condition') . ' = 3')
					->where($db->qn('amount') . ' < ' . $subTotal);

				$result = $db->setQuery($newQuery)->loadObject();
			}
		}

		return $result;
	}

	/**
	 * Method for get coupon
	 *
	 * @param   array $data Array data.
	 *
	 * @return  mixed
	 *
	 * @since   __DEPLOY_VERSION__
	 */
	public static function coupon($data = array())
	{
		$couponCode = JFactory::getApplication()->input->get('discount_code', '');
		$view       = JFactory::getApplication()->input->getCmd('view', '');
		$user       = JFactory::getUser();
		$db         = JFactory::getDbo();
		$return     = false;

		$cart = count($data) <= 0 ? JFactory::getSession()->get('cart') : $data;

		if ($couponCode != "")
		{
			$coupon = rsCarthelper::getInstance()->getcouponData($couponCode, $cart['product_subtotal']);

			if (!empty($coupon))
			{
				$discountType = $coupon->percent_or_total;
				$couponId     = $coupon->coupon_id;
				$couponType   = $coupon->coupon_type;
				$userId       = $coupon->userid;
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
					$db->setQuery($query);
					$userData = $db->loadResult();

					if (!empty($userData))
					{
						if ($userId != $userData->userid)
						{
							$userType = true;
						}
						else
						{
							$userType = false;
						}
					}
					else
					{
						if ($userId != $user->id)
						{
							return false;
						}
						else
						{
							$return = false;
						}
					}
				}

				if (!$userType)
				{
					$return = true;
				}

				$productSubTotal = $cart['product_subtotal'];
				$tmpSubTotal     = $productSubTotal;

				if ($view == 'cart')
				{
					$tmpSubTotal = $productSubTotal - $cart['voucher_discount'] - $cart['cart_discount'];
				}

				if ($discountType == 0)
				{
					$avgVAT = 1;

					if ((float) Redshop::getConfig()->get('VAT_RATE_AFTER_DISCOUNT') && !Redshop::getConfig()->get('APPLY_VAT_ON_DISCOUNT'))
					{
						$avgVAT = $tmpSubTotal / $cart['product_subtotal_excl_vat'];
					}

					$couponValue = $avgVAT * $coupon->coupon_value;
				}
				else
				{
					$couponValue = ($tmpSubTotal * $coupon->coupon_value) / (100);
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
					$couponValue = RedshopHelperTax::calculateAlreadyDiscount($couponValue, $cart);
				}

				$remainingCouponDiscount = 0;

				if ($couponValue > $tmpSubTotal)
				{
					$remainingCouponDiscount = $couponValue - $tmpSubTotal;
					$couponValue             = $tmpSubTotal;
				}

				if (!is_null($cart['total']) && $cart['total'] == 0 && $view = !'cart')
				{
					$couponValue = 0;
				}

				$valueExist = 0;

				if (is_array($cart['coupon']))
				{
					$valueExist = rsCarthelper::getInstance()->rs_recursiveArraySearch($cart['coupon'], $couponCode);
				}

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
					$transaction_coupon_id = 0;

					if (rsCarthelper::getInstance()->rs_multi_array_key_exists('transaction_coupon_id', $coupon))
					{
						$transaction_coupon_id = $coupon->transaction_coupon_id;
					}

					$coupons['coupon'][$couponIndex]['coupon_code']               = $couponCode;
					$coupons['coupon'][$couponIndex]['coupon_id']                 = $couponId;
					$coupons['coupon'][$couponIndex]['used_coupon']               = 1;
					$coupons['coupon'][$couponIndex]['coupon_value']              = $couponValue;
					$coupons['coupon'][$couponIndex]['remaining_coupon_discount'] = $remainingCouponDiscount;
					$coupons['coupon'][$couponIndex]['transaction_coupon_id']     = $transaction_coupon_id;

					$coupons['coupon']     = array_merge($coupons['coupon'], $oldCoupons);
					$cart                  = array_merge($cart, $coupons);
					$cart['free_shipping'] = $coupon->free_shipping;
					JFactory::getSession()->set('cart', $cart);
				}
			}
			elseif (Redshop::getConfig()->get('VOUCHERS_ENABLE'))
			{
				$return = RedshopHelperDiscount::voucher();
			}
		}

		if (!empty($data))
		{
			return $cart;
		}

		return $return;
	}

	/**
	 * Method for get voucher
	 *
	 * @param   array $data Array data.
	 *
	 * @return  mixed
	 *
	 * @since   __DEPLOY_VERSION__
	 */
	public static function voucher($data = array())
	{
		$voucherCode = JFactory::getApplication()->input->get('discount_code', '');
		$return      = false;

		if (count($data) <= 0)
		{
			$cart = JFactory::getSession()->get('cart');
		}
		else
		{
			$cart = $data;
		}

		if ($voucherCode != "")
		{
			$voucher = rsCarthelper::getInstance()->getVoucherData($voucherCode);

			if (count($voucher) > 0)
			{
				$return      = true;
				$voucherType = $voucher->voucher_type;
				$voucherId   = $voucher->voucher_id;
				$counter     = 0;

				foreach ($cart['voucher'] as $key => $val)
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

				if ($voucherType == 'Percentage')
				{
					$discountType = 1;
				}
				else
				{
					$discountType = 0;
				}

				$productId = $voucher->nproduct;
				$products  = rsCarthelper::getInstance()->getCartProductPrice($productId, $cart, $voucher->voucher_left);

				if ($products['product_ids'] == '')
				{
					$return = false;
				}

				$productPrice    = $products['product_price'];
				$productQuantity = $products['product_quantity'];
				$productIds      = $products['product_ids'];

				if ($productQuantity > $voucher->voucher_left)
				{
					$productQuantity = $voucher->voucher_left;
				}

				if ($discountType == 0)
				{
					$voucher->total *= $productQuantity;
					$voucherValue   = $voucher->total;
				}
				else
				{
					$voucherValue = ($productPrice * $voucher->total) / (100);
				}

				$key = rsCarthelper::getInstance()->rs_multi_array_key_exists('voucher', $cart);

				if (!$key)
				{
					$vouchers     = array();
					$oldVouchers  = array();
					$voucherIndex = 0;
				}
				else
				{
					$oldVouchers  = $cart['voucher'];
					$voucherIndex = count($oldVouchers) + 1;
				}

				if (!Redshop::getConfig()->get('APPLY_VOUCHER_COUPON_ALREADY_DISCOUNT'))
				{
					$voucherValue = RedshopHelperTax::calculateAlreadyDiscount($voucherValue, $cart);
				}

				$remainingVoucherDiscount = 0;

				$totalDiscount = $cart['voucher_discount'] + $cart['cart_discount'] + $cart['coupon_discount'];
				$tmpSubTotal   = $productPrice - $cart['coupon_discount'] - $cart['cart_discount'];

				if ($productPrice < $voucherValue)
				{
					$remainingVoucherDiscount = $voucherValue - $productPrice;
					$voucherValue             = $productPrice;
				}
				elseif ($totalDiscount > $tmpSubTotal)
				{
					$remainingVoucherDiscount = $voucherValue;
					$voucherValue             = 0;
				}

				$valueExist = 0;

				if (is_array($cart['voucher']))
				{
					$valueExist = rsCarthelper::getInstance()->rs_recursiveArraySearch($cart['voucher'], $voucherCode);
				}

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

				$transactionVoucherId = 0;

				if (rsCarthelper::getInstance()->rs_multi_array_key_exists('transaction_voucher_id', $voucher))
				{
					$transactionVoucherId = $voucher->transaction_voucher_id;
				}

				if ($return)
				{
					$vouchers['voucher'][$voucherIndex]['voucher_code']               = $voucherCode;
					$vouchers['voucher'][$voucherIndex]['voucher_id']                 = $voucherId;
					$vouchers['voucher'][$voucherIndex]['product_id']                 = $productIds;
					$vouchers['voucher'][$voucherIndex]['used_voucher']               = $productQuantity;
					$vouchers['voucher'][$voucherIndex]['voucher_value']              = $voucherValue;
					$vouchers['voucher'][$voucherIndex]['remaining_voucher_discount'] = $remainingVoucherDiscount;
					$vouchers['voucher'][$voucherIndex]['transaction_voucher_id']     = $transactionVoucherId;

					$vouchers['voucher']   = array_merge($vouchers['voucher'], $oldVouchers);
					$cart                  = array_merge($cart, $vouchers);
					$cart['free_shipping'] = $voucher->free_shipping;

					JFactory::getSession()->set('cart', $cart);
				}
			}
		}

		if (!empty($data))
		{
			return $cart;
		}

		return $return;
	}

	/**
	 * Discount calculator ajax function
	 *
	 * @param   array $data Data
	 *
	 * @return  array
	 *
	 * @since   __DEPLOY_VERSION__
	 */
	public static function discountCalculator($data)
	{
		if (empty($data))
		{
			return array();
		}

		$productId         = $data['product_id'];
		$discountCalculate = array();
		$productPrice      = productHelper::getInstance()->getProductNetPrice($productId);
		$productPriceNoVAT = $productPrice['product_price_novat'];
		$productData       = RedshopHelperProduct::getProductById($productId);

		// Default calculation method
		$calculateMethod = $productData->discount_calc_method;

		// Default calculation unit
		$globalUnit = "m";

		// Use range or not
		$isUseRange = $productData->use_range;

		$calcHeight = $data['calcHeight'];
		$calcWidth  = $data['calcWidth'];
		$calcLength = $data['calcDepth'];
		$calcRadius = $data['calcRadius'];
		$calcUnit   = trim($data['calcUnit']);

		$calcHeight = str_replace(",", ".", $calcHeight);
		$calcWidth  = str_replace(",", ".", $calcWidth);
		$calcLength = str_replace(",", ".", $calcLength);
		$calcRadius = $cartModifyData = str_replace(",", ".", $calcRadius);
		$calcUnit   = $cartModifyData = str_replace(",", ".", $calcUnit);

		// Convert unit using helper function
		$unit = productHelper::getInstance()->getUnitConversation($globalUnit, $calcUnit);

		$calcHeight *= $unit;
		$calcWidth  *= $unit;
		$calcLength *= $unit;
		$calcRadius *= $unit;

		$productUnit = 1;

		if (!$isUseRange)
		{
			$productUnit = productHelper::getInstance()->getUnitConversation($globalUnit, Redshop::getConfig()->get('DEFAULT_VOLUME_UNIT'));

			$productHeight   = $productData->product_height * $productUnit;
			$productWidth    = $productData->product_width * $productUnit;
			$productLength   = $productData->product_length * $productUnit;
			$productDiameter = $productData->product_diameter * $productUnit;
		}

		$area = 0;

		switch ($calculateMethod)
		{
			case "volume":

				$area = $calcHeight * $calcWidth * $calcLength;

				if (!$isUseRange)
				{
					$productArea = $productHeight * $productWidth * $productLength;
				}


				break;

			case "area":

				$area = $calcLength * $calcWidth;

				if (!$isUseRange)
				{
					$productArea = $productLength * $productWidth;
				}

				break;

			case "circumference":

				$area = 2 * PI * $calcRadius;

				if (!$isUseRange)
				{
					$productArea = PI * $productDiameter;
				}

				break;
		}

		$finalArea = $area;

		if ($isUseRange)
		{
			$finalArea = number_format($finalArea, 8, '.', '');

			// Calculation prices as per various area
			$discountCalcData = rsCarthelper::getInstance()->getDiscountCalcData($finalArea, $productId);

		}
		else
		{
			// Standard size of product
			$finalProductArea = $productArea;

			// Total sheet calculation
			if ($finalProductArea <= 0)
			{
				$finalProductArea = 1;
			}

			$totalSheet = $finalArea / $finalProductArea;

			// Returns the next highest integer value by rounding up value if necessary.
			if (isset($productData->allow_decimal_piece) && $productData->allow_decimal_piece)
			{
				$totalSheet = ceil($totalSheet);
			}

			// If sheet is less than 0 or equal to 0 than
			if ($totalSheet <= 0)
			{
				$totalSheet = 1;
			}

			// Product price of all sheets
			$productPriceTotal = $totalSheet * $productPriceNoVAT;

			$discountCalcData    = array();
			$discountCalcData[0] = new stdClass;

			// Generating array
			$discountCalcData[0]->area_price         = $productPriceNoVAT;
			$discountCalcData[0]->discount_calc_unit = $productUnit;
			$discountCalcData[0]->price_per_piece    = $productPriceTotal;
		}

		$areaPrice        = 0;
		$pricePerPieceTax = 0;

		if (count($discountCalcData))
		{
			$areaPrice = $discountCalcData[0]->area_price;

			// Discount calculator extra price enhancement
			$productExtraId       = $data['pdcextraid'];
			$productCalculateText = array();
			$productCalculateIds  = array();

			if (trim($productExtraId) != "")
			{
				$productCalculateExtraData = rsCarthelper::getInstance()->getDiscountCalcDataExtra($productExtraId);

				for ($pdc = 0; $pdc < count($productCalculateExtraData); $pdc++)
				{
					$productCalculateExtraDatum = $productCalculateExtraData[$pdc];
					$optionName                 = $productCalculateExtraDatum->option_name;
					$productCalculatePrice      = $productCalculateExtraDatum->price;
					$productCalculateOprand     = $productCalculateExtraDatum->oprand;
					$productCalculateExtraId    = $productCalculateExtraDatum->pdcextra_id;

					$productCalculateText[] = $optionName . ' (' . $productCalculateOprand . ' ' . $productCalculatePrice . ' )';
					$productCalculateIds[]  = $productCalculateExtraId;

					switch ($productCalculateOprand)
					{
						case "+":
							$areaPrice += $productCalculatePrice;
							break;

						case "-":
							$areaPrice -= $productCalculatePrice;
							break;

						case "%":
							$areaPrice *= 1 + ($productCalculatePrice / 100);
							break;
					}
				}
			}

			// Applying TAX
			$isApplyTax = productHelper::getInstance()->getApplyattributeVatOrNot();

			if ($isUseRange)
			{
				$displayFinalArea         = $finalArea / ($unit * $unit);
				$pricePerPiece            = $areaPrice * $finalArea;
				$pricePerPiece            = $areaPrice;
				$formatted_price_per_area = productHelper::getInstance()->getProductFormattedPrice($areaPrice);
				$pricePerPieceTax         = RedshopHelperProduct::getProductTax($productId, $pricePerPiece, 0, 1);

				echo $displayFinalArea . "\n";
				echo $areaPrice . "\n";
				echo $pricePerPiece . "\n";
				echo JText::_('COM_REDSHOP_TOTAL_AREA') . "\n";
				echo JText::_('COM_REDSHOP_PRICE_PER_AREA') . "\n";
				echo JText::_('COM_REDSHOP_PRICE_PER_PIECE') . "\n";
				echo JText::_('COM_REDSHOP_PRICE_TOTAL') . "\n";
				echo $pricePerPieceTax . "\n";
				echo $isApplyTax . "\n";
			}
			else
			{
				$pricePerPiece    = $discountCalcData[0]->price_per_piece;
				$pricePerPieceTax = RedshopHelperProduct::getProductTax($productId, $pricePerPiece, 0, 1);

				echo $area . "<br />" . JText::_('COM_REDSHOP_TOTAL_PIECE') . $totalSheet . "\n";
				echo $areaPrice . "\n";
				echo $pricePerPiece . "\n";
				echo JText::_('COM_REDSHOP_TOTAL_AREA') . "\n";
				echo JText::_('COM_REDSHOP_PRICE_PER_PIECE') . "\n";
				echo JText::_('COM_REDSHOP_PRICE_OF_ALL_PIECE') . "\n";
				echo JText::_('COM_REDSHOP_PRICE_TOTAL') . "\n";
				echo $pricePerPieceTax . "\n";
				echo $isApplyTax . "\n";
			}
		}
		else
		{
			$pricePerPiece = false;
			echo "fail";
		}

		$discountCalculate['product_price']     = $pricePerPiece;
		$discountCalculate['product_price_tax'] = $pricePerPieceTax;
		$discountCalculate['pdcextra_data']     = "";

		if (isset($productCalculateText) && count($productCalculateText) > 0)
		{
			$discountCalculate['pdcextra_data'] = implode("<br />", $productCalculateText);
		}

		$discountCalculate['pdcextra_ids'] = '';

		if (isset($productCalculateIds) && (count($productCalculateIds) > 0))
		{
			$discountCalculate['pdcextra_ids'] = implode(",", $productCalculateIds);
		}

		if (isset($totalSheet))
		{
			$discountCalculate['total_piece'] = $totalSheet;
		}

		$discountCalculate['price_per_piece'] = $areaPrice;

		return $discountCalculate;
	}
}
