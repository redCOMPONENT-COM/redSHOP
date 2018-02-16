<?php
/**
 * @package     RedShop
 * @subpackage  Helper
 *
 * @copyright   Copyright (C) 2008 - 2017 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

namespace Redshop\Cart;

defined('_JEXEC') or die;

/**
 * Cart class
 *
 * @since  __DEPLOY_VERSION__
 */
class Cart
{
	/**
	 * Method for modify cart data.
	 *
	 * @param   array   $cart   Cart data.
	 * @param   integer $userId User ID
	 *
	 * @return  array
	 *
	 * @since   __DEPLOY_VERSION__
	 */
	public static function modify($cart = array(), $userId = 0)
	{
		$cart            = empty($cart) ? \RedshopHelperCartSession::getCart() : $cart;
		$userId          = !$userId ? \JFactory::getUser()->id : $userId;
		$cart['user_id'] = $userId;

		$idx = isset($cart['idx']) ? (int) $cart['idx'] : 0;

		if (!$idx)
		{
			return $cart;
		}

		\JPluginHelper::importPlugin('redshop_product');

		for ($i = 0; $i < $idx; $i++)
		{
			// Skip if this is giftcard
			if (isset($cart[$i]['giftcard_id']) && $cart[$i]['giftcard_id'] > 0)
			{
				continue;
			}

			$productId    = $cart[$i]['product_id'];
			$quantity     = $cart[$i]['quantity'];
			$product      = \RedshopHelperProduct::getProductById($productId);
			$hasAttribute = isset($cart[$i]['cart_attribute']) ? true : false;

			// Attribute price
			$price = 0;

			if (!isset($cart['quotation']))
			{
				$cart['quotation'] = 0;
			}

			if ((\Redshop::getConfig()->get('DEFAULT_QUOTATION_MODE') || $cart['quotation'] == 1) && !$hasAttribute)
			{
				$price = $cart[$i]['product_price_excl_vat'];
			}

			if ($product->use_discount_calc)
			{
				$price = $cart[$i]['discount_calc_price'];
			}

			// Only set price without vat for accessories as product
			$accessoryHasProductWithoutVat = false;

			if (isset($cart['AccessoryAsProduct']))
			{
				// Accessory price fix during update
				$accessoryAsProduct = \RedshopHelperAccessory::getAccessoryAsProduct($cart['AccessoryAsProduct']);

				if (isset($accessoryAsProduct->accessory)
					&& isset($accessoryAsProduct->accessory[$cart[$i]['product_id']])
					&& isset($cart[$i]['accessoryAsProductEligible']))
				{
					$accessoryHasProductWithoutVat = '{without_vat}';

					$accessoryPrice                     = (float) $accessoryAsProduct->accessory[$cart[$i]['product_id']]->newaccessory_price;
					$price                              = \RedshopHelperProductPrice::priceRound($accessoryPrice);
					$cart[$i]['product_price_excl_vat'] = \RedshopHelperProductPrice::priceRound($accessoryPrice);
				}
			}

			$retAttArr = \productHelper::getInstance()->makeAttributeCart(
				isset($cart[$i]['cart_attribute']) ? $cart[$i]['cart_attribute'] : array(),
				(int) $product->product_id,
				$userId,
				$price,
				$quantity,
				$accessoryHasProductWithoutVat
			);

			$accessoryAsProductZero = (count($retAttArr[8]) == 0 && $price == 0 && $accessoryHasProductWithoutVat);

			// Product + attribute (price)
			$getProductPrice = ($accessoryAsProductZero) ? 0 : $retAttArr[1];

			// Product + attribute (VAT)
			$getProductTax        = ($accessoryAsProductZero) ? 0 : $retAttArr[2];
			$productOldPriceNoVat = ($accessoryAsProductZero) ? 0 : $retAttArr[5];

			// Accessory calculation
			$accessories = \productHelper::getInstance()->makeAccessoryCart(
				isset($cart[$i]['cart_accessory']) ? $cart[$i]['cart_accessory'] : array(),
				$product->product_id,
				$userId
			);

			// Accessory + attribute (price)
			$accessoryPrice = $accessories[1];

			// Accessory + attribute (VAT)
			$accessoryTax          = $accessories[2];
			$productOldPriceNoVat += $accessories[1];

			// ADD WRAPPER PRICE
			$wrapperVat   = 0;
			$wrapperPrice = 0;

			if (array_key_exists('wrapper_id', $cart[$i]) && !empty($cart[$i]['wrapper_id']))
			{
				$wrappers = \rsCarthelper::getInstance()->getWrapperPriceArr(
					array('product_id' => $cart[$i]['product_id'], 'wrapper_id' => $cart[$i]['wrapper_id'])
				);

				$wrapperVat   = $wrappers['wrapper_vat'];
				$wrapperPrice = $wrappers['wrapper_price'];

				$productOldPriceNoVat += $wrapperPrice;
			}

			$productPrice      = $accessoryPrice + $getProductPrice + $getProductTax + $accessoryTax + $wrapperPrice + $wrapperVat;
			$productVat        = ($getProductTax + $accessoryTax + $wrapperVat);
			$productPriceNoVat = ($getProductPrice + $accessoryPrice + $wrapperPrice);

			if ($product->product_type == 'subscription')
			{
				if (!isset($cart[$i]['subscription_id']) || empty($cart[$i]['subscription_id']))
				{
					return array();
				}

				$subscription      = \productHelper::getInstance()->getProductSubscriptionDetail($productId, $cart[$i]['subscription_id']);
				$subscriptionVat   = 0;
				$subscriptionPrice = $subscription->subscription_price;

				if ($subscriptionPrice)
				{
					$subscriptionVat = \RedshopHelperProduct::getProductTax($product->product_id, $subscriptionPrice);
				}

				$productVat           += $subscriptionVat;
				$productPrice          = $productPrice + $subscriptionPrice + $subscriptionVat;
				$productPriceNoVat    += $subscriptionPrice;
				$productOldPriceNoVat += $subscriptionPrice + $subscriptionVat;
			}

			// Set product price
			if ($productPrice < 0)
			{
				$productPrice = 0;
			}

			$cart[$i]['product_old_price_excl_vat'] = $productOldPriceNoVat;
			$cart[$i]['product_price_excl_vat']     = $productPriceNoVat;
			$cart[$i]['product_vat']                = $productVat;
			$cart[$i]['product_price']              = $productPrice;

			\RedshopHelperUtility::getDispatcher()->trigger('onBeforeLoginCartSession', array(&$cart, $i));
		}

		unset($cart[$idx]);

		return $cart;
	}
}
