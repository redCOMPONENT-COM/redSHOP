<?php
/**
 * @package     RedShop
 * @subpackage  Helper
 *
 * @copyright   Copyright (C) 2008 - 2017 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

namespace Redshop\Cart;

use Redshop\Helper\Template;

defined('_JEXEC') or die;

/**
 * Cart helper
 *
 * @since  __DEPLOY_VERSION__
 */
class Helper
{
	/**
	 * Method calculate cart price.
	 * APPLY_VAT_ON_DISCOUNT = When the discount is a "fixed amount" the
	 * final price may vary, depending on if the discount affects "the price+VAT"
	 * or just "the price". This CONSTANT will define if the discounts needs to
	 * be applied BEFORE or AFTER the VAT is applied to the product price.
	 *
	 * @param   array   $cart   Cart data
	 * @param   integer $userId Current user ID
	 *
	 * @return  array
	 * @throws  \Exception
	 *
	 * @since   __DEPLOY_VERSION__
	 */
	public static function calculation($cart = array(), $userId = 0)
	{
		if (empty($cart))
		{
			$cart = \RedshopHelperCartSession::getCart();
		}

		$index         = $cart['idx'];
		$vat           = 0;
		$subTotal      = 0;
		$subTotalNoVAT = 0;
		$totalDiscount = 0;
		$discountVAT   = 0;
		$shippingVat   = 0;
		$shipping      = 0;

		for ($i = 0; $i < $index; $i++)
		{
			$quantity      = $cart[$i]['quantity'];
			$subTotal      += $quantity * $cart[$i]['product_price'];
			$subTotalNoVAT += $quantity * $cart[$i]['product_price_excl_vat'];
			$vat           += $quantity * $cart[$i]['product_vat'];
		}

		/* @TODO: Need to check why this variable still exist.
		 * $tmparr = array(
		 * 'subtotal' => $subTotal,
		 * 'tax'      => $vat
		 * );
		 */

		// Calculate shipping.
		self::calculateShipping($shipping, $shippingVat, $cart, $subTotal, $userId);

		$view = \JFactory::getApplication()->input->getCmd('view');

		if (key_exists('shipping', $cart) && $view != 'cart')
		{
			$shipping = $cart['shipping'];

			if (!isset($cart['shipping_vat']))
			{
				$cart['shipping_vat'] = 0;
			}

			$shippingVat = $cart['shipping_vat'];
		}

		$taxExemptAddToCart = \RedshopHelperCart::taxExemptAddToCart();

		if (\Redshop::getConfig()->getFloat('VAT_RATE_AFTER_DISCOUNT') && !\Redshop::getConfig()->getBool('APPLY_VAT_ON_DISCOUNT')
			&& !empty($taxExemptAddToCart))
		{
			if (isset($cart['discount_tax']) && !empty($cart['discount_tax']))
			{
				$discountVAT = $cart['discount_tax'];
				$subTotal    = $subTotal - $cart['discount_tax'];
			}
			else
			{
				$vatData = \RedshopHelperTax::getVatRates();

				if (isset($vatData->tax_rate) && !empty($vatData->tax_rate))
				{
					$discountVAT = 0;

					if ((int) $subTotalNoVAT > 0)
					{
						$avgVAT      = (($subTotalNoVAT + $vat) / $subTotalNoVAT) - 1;
						$discountVAT = ($avgVAT * $totalDiscount) / (1 + $avgVAT);
					}
				}
			}

			$vat = $vat - $discountVAT;
		}

		$total  = $subTotal + $shipping;
		$result = array($total, $subTotal, $subTotalNoVAT, $shipping);

		if (isset($cart['discount']) === false)
		{
			$cart['discount'] = 0;
		}

		$result[] = $cart['discount'];
		$result[] = $vat;
		$result[] = $shippingVat;

		return $result;
	}

	/**
	 * @param   float   $shipping    Shipping rate
	 * @param   float   $shippingVat Shipping VAT
	 * @param   array   $cart        Cart data
	 * @param   float   $subTotal    Sub total
	 * @param   integer $userId      User ID
	 *
	 * @return  void
	 *
	 * @since   __DEPLOY_VERSION__
	 */
	public static function calculateShipping(&$shipping, &$shippingVat, &$cart, $subTotal = 0.0, $userId = 0)
	{
		// If SHOW_SHIPPING_IN_CART set to no, make shipping Zero
		if (!\Redshop::getConfig()->getBool('SHOW_SHIPPING_IN_CART') || !\Redshop::getConfig()->getBool('SHIPPING_METHOD_ENABLE'))
		{
			return;
		}

		$index       = $cart['idx'];
		$usersInfoId = 0;

		if (!$userId)
		{
			$user            = \JFactory::getUser();
			$userId          = $user->id;
			$shippingAddress = \RedshopHelperOrder::getShippingAddress($userId);

			if (!empty($shippingAddress) && !empty($shippingAddress[0]))
			{
				$usersInfoId = $shippingAddress[0]->users_info_id;
			}
		}

		$numberOfGiftCards = 0;

		for ($i = 0; $i < $index; $i++)
		{
			if (isset($cart[$i]['giftcard_id']) === true && !empty($cart[$i]['giftcard_id']))
			{
				$numberOfGiftCards++;
			}
		}

		if ($numberOfGiftCards == $index)
		{
			$cart['free_shipping'] = 1;
		}
		elseif (!isset($cart['free_shipping']) || $cart['free_shipping'] != 1)
		{
			$cart['free_shipping'] = 0;
		}

		if (isset($cart['free_shipping']) && $cart['free_shipping'] > 0)
		{
			$shipping = 0;

			return;
		}

		if (!isset($cart['voucher_discount']))
		{
			$cart['coupon_discount'] = 0;
		}

		$totalDiscount = $cart['cart_discount'];
		$totalDiscount += isset($cart['voucher_discount']) ? $cart['voucher_discount'] : 0.0;
		$totalDiscount += isset($cart['coupon_discount']) ? $cart['coupon_discount'] : 0.0;

		$shippingData = array(
			'order_subtotal' => \Redshop::getConfig()->getString('SHIPPING_AFTER') == 'total' ? $subTotal - $totalDiscount : $subTotal,
			'users_info_id'  => $usersInfoId
		);

		$defaultShipping = \RedshopHelperCartShipping::getDefault($shippingData);
		$shipping        = $defaultShipping['shipping_rate'];
		$shippingVat     = $defaultShipping['shipping_vat'];
	}

	/**
	 * Method for get default quantity
	 *
	 * @param   integer $productId Product ID
	 * @param   string  $html      Template html
	 *
	 * @return  integer
	 * @throws \Exception
	 *
	 * @since   __DEPLOY_VERSION__
	 */
	public static function getDefaultQuantity($productId = 0, $html = "")
	{
		$template = Template::getAddToCart($html);
		$cartForm = null !== $template ? $template->template_desc : "";

		if (strpos($cartForm, "{addtocart_quantity_selectbox}") === false)
		{
			return 1;
		}

		$quantitySelected = 1;
		$product          = \RedshopHelperProduct::getProductById($productId);

		if ((\Redshop::getConfig()->getString('DEFAULT_QUANTITY_SELECTBOX_VALUE') != ""
			&& $product->quantity_selectbox_value == '') || $product->quantity_selectbox_value != '')
		{
			$selectBoxValue = ($product->quantity_selectbox_value) ? $product->quantity_selectbox_value
				: \Redshop::getConfig()->get('DEFAULT_QUANTITY_SELECTBOX_VALUE');
			$quantityBoxes  = explode(",", $selectBoxValue);
			$quantityBoxes  = array_merge(array(), array_unique($quantityBoxes));

			sort($quantityBoxes);

			foreach ($quantityBoxes as $quantityBox)
			{
				if (intVal($quantityBox) && intVal($quantityBox) != 0)
				{
					$quantitySelected = intVal($quantityBox);
					break;
				}
			}
		}

		return $quantitySelected;
	}
}
