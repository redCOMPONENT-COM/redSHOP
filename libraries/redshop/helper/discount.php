<?php
/**
 * @package     RedSHOP
 * @subpackage  Discount
 *
 * @copyright   Copyright (C) 2008 - 2019 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

defined('_JEXEC') or die;

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
		$user = JFactory::getUser();

		if (!$userId)
		{
			$userId = $user->id;
		}

		$userData       = RedshopHelperUser::createUserSession($userId);
		$shopperGroupId = (int) $userData['rs_user_shopperGroup'];

		$shopperGroupDiscounts = RedshopEntityShopper_Group::getInstance($shopperGroupId)->getDiscounts();

		if ($shopperGroupDiscounts->isEmpty())
		{
			return false;
		}

		$result      = false;
		$currentTime = time();

		foreach ($shopperGroupDiscounts->getAll() as $discount)
		{
			/** @var RedshopEntityDiscount $discount */
			$potentialDiscount = null;

			// Skip if this discount is not published
			if (!$discount->get('published', false))
			{
				continue;
			}

			$startDate = $discount->get('start_date', 0);
			$endDate   = $discount->get('end_date', 0);
			$condition = $discount->get('condition', 0);
			$amount    = $discount->get('amount', 0);

			/**
			 * Discount condition
			 * 1. Start date and End date not set
			 * 2. Had only start date and start date smaller than current time.
			 * 3. Had only end date and end date higher than current time.
			 * 4. Start date exist and smaller than current time. End date exist and higher than current time.
			 */
			if ((!$startDate && !$endDate)
				|| ($startDate && !$endDate && $startDate <= $currentTime)
				|| (!$startDate && $endDate && $endDate >= $currentTime)
				|| ($startDate && $startDate <= $currentTime && $endDate && $endDate >= $currentTime))
			{
				if (($condition == 1 && $amount > $subTotal)
					|| ($condition == 2 && $amount == $subTotal)
					|| ($condition == 3 && $amount < $subTotal))
				{
					$potentialDiscount = $discount;
				}
				else
				{
					continue;
				}
			}
			else
			{
				continue;
			}

			if (false === $result || $result->get('amount') > $potentialDiscount->get('amount'))
			{
				$result = $potentialDiscount;
			}
		}

		return $result;
	}

	/**
	 * Get discount price from product with check discount date.
	 *
	 * @param   int $productId Product id
	 *
	 * @return  float
	 *
	 * @since   2.0.7
	 */
	public static function getDiscountPriceBaseDiscountDate($productId)
	{
		$productData = RedshopHelperProduct::getProductById($productId);

		if (empty($productData))
		{
			return 0.0;
		}

		$today = time();

		// Convert discount_enddate to middle night
		$productData->discount_enddate = RedshopHelperDatetime::generateTimestamp($productData->discount_enddate);

		if (Redshop::getConfig()->getInt('DISCOUNT_ENABLE') == 0)
		{
			$productData->discount_price = 0;
		}
		else
		{
			if (($productData->discount_enddate == '0' && $productData->discount_stratdate == '0')
				|| ((int)$productData->discount_enddate >= $today && (int)$productData->discount_stratdate <= $today)
				|| ($productData->discount_enddate == '0' && (int)$productData->discount_stratdate <= $today))
			{
				return (float)$productData->discount_price;
			}
		}

		return 0.0;
	}

	/**
	 * Add GiftCard To Cart
	 *
	 * @param   array $cartItem Cart item
	 * @param   array $data     User cart data
	 *
	 * @return  void
	 *
	 * @since   2.1.0
	 */
	public static function addGiftCardToCart(&$cartItem, $data)
	{
		$cartItem['giftcard_id']     = $data['giftcard_id'];
		$cartItem['reciver_email']   = $data['reciver_email'];
		$cartItem['reciver_name']    = $data['reciver_name'];
		$cartItem['customer_amount'] = "";

		if (isset($data['customer_amount']))
		{
			$cartItem['customer_amount'] = $data['customer_amount'];
		}

		$giftCard      = RedshopEntityGiftcard::getInstance($data['giftcard_id'])->getItem();
		$giftCardPrice = $giftCard && $giftCard->customer_amount ? $cartItem['customer_amount'] : $giftCard->giftcard_price;

		$fields = RedshopHelperExtrafields::getSectionFieldList(RedshopHelperExtrafields::SECTION_GIFT_CARD_USER_FIELD);

		foreach ($fields as $field)
		{
			$dataTxt = (isset($data[$field->name])) ? $data[$field->name] : '';
			$tmpText = strpbrk($dataTxt, '`');

			if ($tmpText)
			{
				$tmpData = explode('`', $dataTxt);

				if (is_array($tmpData))
				{
					$dataTxt = implode(",", $tmpData);
				}
			}

			$cartItem[$field->name] = $dataTxt;
		}

		$cartItem['product_price']          = $giftCardPrice;
		$cartItem['product_price_excl_vat'] = $giftCardPrice;
		$cartItem['product_vat']            = 0;
		$cartItem['product_id']             = '';
	}

	/**
	 * Re-calculate the Voucher/Coupon value when the product is already discount
	 *
	 * @param   float $value Voucher/Coupon value
	 * @param   array $cart  Cart array
	 *
	 * @return  float          Voucher/Coupon value
	 *
	 * @since   2.1.0
	 */
	public static function calculateAlreadyDiscount($value, $cart)
	{
		$idx = 0;

		if (isset($cart['idx']))
		{
			$idx = $cart['idx'];
		}

		$percent = ($value * 100) / $cart['product_subtotal'];

		for ($i = 0; $i < $idx; $i++)
		{
			$productPriceArray = RedshopHelperProductPrice::getNetPrice($cart[$i]['product_id']);

			// If the product is already discount
			if ($productPriceArray['product_price_saving_percentage'] > 0 && empty($cart[$i]['cart_attribute']))
			{
				$amount = $percent * $productPriceArray['product_price'] / 100;
				$value -= $amount * $cart[$i]['quantity'];
			}
		}

		return $value < 0 ? 0 : $value;
	}

	/**
	 * Method for calculate discount.
	 *
	 * @param   string $type  Type of discount
	 * @param   array  $types List of type
	 *
	 * @return  float
	 *
	 * @since   2.1.0
	 */
	public static function calculate($type, $types)
	{
		if (empty($types))
		{
			return 0;
		}

		$value    = $type == 'voucher' ? 'voucher_value' : 'coupon_value';
		$discount = 0;

		$idx = count($types);

		for ($i = 0; $i < $idx; $i++)
		{
			$discount += $types[$i][$value];
		}

		return $discount;
	}
}
