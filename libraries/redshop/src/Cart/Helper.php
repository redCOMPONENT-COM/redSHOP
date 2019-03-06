<?php
/**
 * @package     RedShop
 * @subpackage  Helper
 *
 * @copyright   Copyright (C) 2008 - 2019 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

namespace Redshop\Cart;

defined('_JEXEC') or die;

/**
 * Cart helper
 *
 * @since  2.1.0
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
	 * @since   2.1.0
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
			$quantity       = $cart[$i]['quantity'];
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

		if (array_key_exists('shipping', $cart) && $view != 'cart')
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

				if (null !== $vatData && !empty($vatData->tax_rate))
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
	 * @since   2.1.0
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
			$shipping = 0.0;

			return;
		}

		if (!isset($cart['voucher_discount']))
		{
			$cart['coupon_discount'] = 0;
		}

		$totalDiscount  = $cart['cart_discount'];
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
	 * @since   2.1.0
	 */
	public static function getDefaultQuantity($productId = 0, $html = "")
	{
		$template = \Redshop\Template\Helper::getAddToCart($html);
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

	/**
	 * Method for get discount amount fromm cart
	 *
	 * @param   array   $cart   Cart data
	 * @param   integer $userId User ID
	 *
	 * @return  float
	 *
	 * @since   2.1.0
	 */
	public static function getDiscountAmount($cart = array(), $userId = 0)
	{
		$cart     = empty($cart) ? \RedshopHelperCartSession::getCart() : $cart;
		$userId   = empty($userId) ? \JFactory::getUser()->id : $userId;
		$discount = \RedshopHelperDiscount::getDiscount($cart['product_subtotal'], $userId);

		$discountAmountFinal = 0;
		$discountVAT         = 0;

		if (!empty($discount))
		{
			$productSubtotal = $cart['product_subtotal'] + $cart['shipping'];

			// Discount total type
			if (isset($discount->discount_type) && $discount->discount_type == 0)
			{
				// 100% discount
				if ($discount->discount_amount > $productSubtotal)
				{
					$discountAmount = $productSubtotal;
				}
				else
				{
					$discountAmount = $discount->discount_amount;
				}

				$discountPercent = ($discountAmount * 100) / $productSubtotal;
			}
			// Discount percentage price
			else
			{
				$discountPercent = isset($discount->discount_amount) ? $discount->discount_amount : 0;
			}

			// Apply even products already on discount
			if (\Redshop::getConfig()->get('APPLY_VOUCHER_COUPON_ALREADY_DISCOUNT'))
			{
				$discountAmountFinal = $discountPercent * $productSubtotal / 100;
			}
			else
			{
				/*
				 * Checking which discount is the best
				 * Example 2 products in cart, 1 product 0% - 1 product 15%
				 * Cart total order discount of 10% for value over 1000, now that discount will be added to both products,
				 * so the product with 15% will now have 25% and the product with 0% will have 10%.
				 * The product with 25% should only have 15% discount as it's best practice and most logical setup
				*/

				$idx = 0;

				if (isset($cart['idx']))
				{
					$idx = $cart['idx'];
				}

				for ($i = 0; $i < $idx; $i++)
				{
					$productPrice = \RedshopHelperProductPrice::getNetPrice($cart[$i]['product_id']);

					// Product already discount
					if ($productPrice['product_discount_price'] > 0)
					{
						// Restore to the origigal price
						$cart[$i]['product_price']          = $productPrice['product_old_price'];
						$cart[$i]['product_price_excl_vat'] = $productPrice['product_old_price_excl_vat'];
						$cart[$i]['product_vat']            = $productPrice['product_old_price'] - $productPrice['product_old_price_excl_vat'];
					}

					// Checking the product discount < total discount => get total discount
					if ($productPrice['product_price_saving_percentage'] <= $discountPercent)
					{
						$discountAmount = $discountPercent * $productPrice['product_price'] / 100;
					}
					// Keep product discount
					else
					{
						$discountAmount = $productPrice['product_price_saving'];
					}

					// With quantity
					$discountAmountFinal += $discountAmount * $cart[$i]['quantity'];
				}
			}

			if (\Redshop::getConfig()->getFloat('VAT_RATE_AFTER_DISCOUNT') && !\Redshop::getConfig()->getBool('APPLY_VAT_ON_DISCOUNT'))
			{
				$discountVAT = $discountAmountFinal * \Redshop::getConfig()->getFloat('VAT_RATE_AFTER_DISCOUNT');
			}

			$cart['discount_tax'] = $discountVAT;

			\RedshopHelperCartSession::setCart($cart);
		}

		return $discountAmountFinal;
	}

	/**
	 * Method for generate attribute array
	 *
	 * @param   array   $data   Data of attributes
	 * @param   integer $userId ID of user
	 *
	 * @return  array
	 *
	 * @since   2.1.0
	 */
	public static function generateAttribute($data, $userId = 0)
	{
		if (empty($data) || !array_key_exists('attribute_data', $data) || empty($data['attribute_data']))
		{
			return array();
		}

		$result = array();

		$attributes        = explode('##', $data['attribute_data']);
		$propertiesData    = explode('##', $data['property_data']);
		$subPropertiesDatas = !empty($data['subproperty_data']) ? explode('##', $data['subproperty_data']) : null;

		foreach ($attributes as $attrIndex => $attributeId)
		{
			$propertiesOprand                     = array();
			$propertiesPrice                      = array();
			$accPropertyCart                      = array();
			$attribute                            = \RedshopHelperProduct_Attribute::getProductAttribute(0, 0, $attributeId);
			$result[$attrIndex]['attribute_id']   = $attributeId;
			$result[$attrIndex]['attribute_name'] = $attribute[0]->text;

			if ($attribute[0]->text != "" && !empty($data['property_data']) && !empty($propertiesData[$attrIndex]))
			{
				$accessoriesPropertiesData = explode(',,', $propertiesData[$attrIndex]);

				foreach ($accessoriesPropertiesData as $propIndex => $accessoriesProperty)
				{
					$accSubpropertyCart = array();
					$property           = \RedshopHelperProduct_Attribute::getAttributeProperties($accessoriesPropertiesData[$propIndex]);
					$priceList          = \RedshopHelperProduct_Attribute::getPropertyPrice(
						/** @scrutinizer ignore-type */ $accessoriesProperty, $data['quantity'], 'property', $userId
					);

					if (!empty($priceList) && $priceList != new \stdClass)
					{
						$propertyPrice = $priceList->product_price;
					}
					else
					{
						$propertyPrice = $property[0]->property_price;
					}

					$accPropertyCart[$propIndex] = array(
						'property_id'     => $property[0]->property_id,
						'attribute_id'    => $property[0]->attribute_id,
						'property_name'   => $property[0]->text,
						'property_oprand' => $property[0]->oprand,
						'property_price'  => $propertyPrice,
					);

					$propertiesOprand[$propIndex] = $property[0]->oprand;
					$propertiesPrice[$propIndex]  = $propertyPrice;

					if (!empty($subPropertiesDatas))
					{
						$subPropertiesData = explode(',,', $subPropertiesDatas[$attrIndex]);

						if (isset($subPropertiesData[$propIndex]) && $subPropertiesData[$propIndex] != "")
						{
							$subSubPropertyData = explode('::', $subPropertiesData[$propIndex]);

							foreach ($subSubPropertyData as $supPropIndex => $subSubProperty)
							{
								$subproperty = \RedshopHelperProduct_Attribute::getAttributeSubProperties($subSubProperty);
								$priceList   = \RedshopHelperProduct_Attribute::getPropertyPrice(
									$subSubProperty, $data['quantity'], 'subproperty', $userId
								);

								if (!empty($priceList) && $priceList != new \stdClass)
								{
									$subPropertyPrice = $priceList->product_price;
								}
								else
								{
									$subPropertyPrice = $subproperty[0]->subattribute_color_price;
								}

								$accSubpropertyCart[$supPropIndex] = array(
									'subproperty_id'           => $subSubProperty,
									'subproperty_name'         => $subproperty[0]->text,
									'subproperty_oprand'       => $subproperty[0]->oprand,
									'subattribute_color_title' => $subproperty[0]->subattribute_color_title,
                                    					'subattribute_color_number'=> $subproperty[0]->subattribute_color_number,
									'subproperty_price'        => $subPropertyPrice,
								);
							}
						}
					}

					$accPropertyCart[$propIndex]['property_childs'] = $accSubpropertyCart;
				}
			}

			if (!empty($accPropertyCart))
			{
				$result[array_search($accPropertyCart[0]['attribute_id'], $attributes)]['attribute_childs'] = $accPropertyCart;
			}
		}

		return $result;
	}
}
