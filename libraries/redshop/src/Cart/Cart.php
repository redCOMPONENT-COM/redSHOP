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
 * Cart class
 *
 * @since  2.1.0
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
	 * @since   2.1.0
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
			$accessoryHasProductWithoutVat = '';

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

			$accessoryAsProductZero = !count($retAttArr[8]) && $price == 0 && !empty($accessoryHasProductWithoutVat);

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
			$accessoryTax = $accessories[2];

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

				$productPrice = $productPrice + $subscriptionPrice + $subscriptionVat;

				$productVat           += $subscriptionVat;
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

	/**
	 * Method for add product to cart
	 *
	 * @param   array $data Product data
	 *
	 * @return  mixed         True on success. Error message string if fail.
	 * @throws  \Exception
	 *
	 * @since   2.1.0
	 */
	public static function addProduct($data = array())
	{
		$user             = \JFactory::getUser();
		$cart             = \RedshopHelperCartSession::getCart();
		$data['quantity'] = round($data['quantity']);

		if (empty($cart) || !array_key_exists("idx", $cart) || array_key_exists("quotation_id", $cart))
		{
			$cart = array('idx' => 0);
		}

		$idx = (int) $cart['idx'];

		// Set session for giftcard
		if (!empty($data['giftcard_id']))
		{
			self::addGiftCardProduct($cart, $idx, $data);
		}
		// Set session for product
		else
		{
			$result = self::addNormalProduct($cart, $idx, $data);

			if (true !== $result)
			{
				return $result;
			}
		}

		if (!isset($cart['discount_type']) || !$cart['discount_type'])
		{
			$cart['discount_type'] = 0;
		}

		if (!isset($cart['discount']) || !$cart['discount'])
		{
			$cart['discount'] = 0;
		}

		if (!isset($cart['cart_discount']) || !$cart['cart_discount'])
		{
			$cart['cart_discount'] = 0;
		}

		if (!isset($cart['user_shopper_group_id']) || (isset($cart['user_shopper_group_id']) && $cart['user_shopper_group_id'] == 0))
		{
			$cart['user_shopper_group_id'] = \RedshopHelperUser::getShopperGroup($user->id);
		}

		$cart['free_shipping'] = 0;

		\RedshopHelperCartSession::setCart($cart);

		return true;
	}

	/**
	 * Method for add product giftcard to cart
	 *
	 * @param   array   $cart Cart data
	 * @param   integer $idx  Index of cart
	 * @param   array   $data Data of product
	 *
	 * @return  void
	 *
	 * @since   2.1.0
	 */
	public static function addGiftCardProduct(&$cart, $idx, $data = array())
	{
		$sameGiftCard = false;
		$section      = \RedshopHelperExtrafields::SECTION_GIFT_CARD_USER_FIELD;
		$rows         = \RedshopHelperExtrafields::getSectionFieldList($section);

		for ($g = 0; $g < $idx; $g++)
		{
			if ($cart[$g]['giftcard_id'] == $data['giftcard_id']
				&& $cart[$g]['reciver_email'] == $data['reciver_email']
				&& $cart[$g]['reciver_name'] == $data['reciver_name'])
			{
				$sameGiftCard = true;

				// Product user field
				if (!empty($rows))
				{
					foreach ($rows as $row)
					{
						$productUserField = $row->name;

						if (isset($cart[$g][$productUserField]) && $data[$productUserField] != $cart[$g][$productUserField])
						{
							$sameGiftCard = false;
							break;
						}
					}
				}

				if (!$sameGiftCard)
				{
					continue;
				}

				$cart[$g]['quantity'] += $data['quantity'];
				\RedshopHelperDiscount::addGiftCardToCart($cart[$g], $data);
			}
		}

		if (!$sameGiftCard)
		{
			$cart[$idx] = array(
				'quantity' => $data['quantity']
			);
			\RedshopHelperDiscount::addGiftCardToCart($cart[$idx], $data);
			$cart['idx'] = $idx + 1;
		}
	}

	/**
	 * Method for add normal product to cart
	 *
	 * @param   array   $cart Cart data
	 * @param   integer $idx  Index of cart
	 * @param   array   $data Data of product
	 *
	 * @return  mixed
	 * @throws  \Exception
	 *
	 * @since   2.1.0
	 */
	public static function addNormalProduct(&$cart, $idx, $data = array())
	{
		$section = \RedshopHelperExtrafields::SECTION_PRODUCT_USERFIELD;
		$rows    = \RedshopHelperExtrafields::getSectionFieldList($section);

		if (isset($data['hidden_attribute_cartimage']))
		{
			$cart[$idx]['hidden_attribute_cartimage'] = $data['hidden_attribute_cartimage'];
		}

		$productId = $data['product_id'];
		$quantity  = $data['quantity'];
		$product   = \RedshopHelperProduct::getProductById($productId);

		// Handle individual accessory add to cart price
		if (\Redshop::getConfig()->get('ACCESSORY_AS_PRODUCT_IN_CART_ENABLE')
			&& isset($data['parent_accessory_product_id'])
			&& $data['parent_accessory_product_id'] != 0
			&& isset($data['accessory_id']))
		{
			$cart[$idx]['accessoryAsProductEligible'] = $data['accessory_id'];
			$accessoryInfo                            = \RedshopHelperAccessory::getProductAccessories($data['accessory_id']);
			$product->product_price                   = $accessoryInfo[0]->newaccessory_price;

			$tempData          = \RedshopHelperProduct::getProductById($data['parent_accessory_product_id']);
			$productTemplate   = \RedshopHelperTemplate::getTemplate("product", $tempData->product_template);
			$accessoryTemplate = \Redshop\Template\Helper::getAccessory($productTemplate[0]->template_desc);
			$dataAdd           = null !== $accessoryTemplate ? $accessoryTemplate->template_desc : '';
		}
		else
		{
			$productTemplate = \RedshopHelperTemplate::getTemplate("product", $product->product_template);
			$dataAdd         = $productTemplate[0]->template_desc;
		}

		/*
		 * Check if required userfield are filled or not if not than redirect to product detail page...
		 * Get product userfield from selected product template...
		 */
		if (!\Redshop::getConfig()->get('AJAX_CART_BOX'))
		{
			$fieldRequired = \rsCarthelper::getInstance()->userfieldValidation($data, $dataAdd, $section);

			if ($fieldRequired != "")
			{
				return $fieldRequired;
			}
		}

		// Get product price
		$data['product_price'] = 0;

		// Discount calculator procedure start
		$discounts = \rsCarthelper::getInstance()->discountCalculatorData($product, $data);

		$calcOutput      = "";
		$calcOutputs     = array();
		$productVatPrice = 0;

		if (!empty($discounts))
		{
			$calcOutput  = $discounts[0];
			$calcOutputs = $discounts[1];

			// Calculate price without VAT
			$data['product_price']                = $discounts[2];
			$cart[$idx]['product_price_excl_vat'] = $discounts[2];
			$cart[$idx]['discount_calc_price']    = $discounts[2];

			$productVatPrice += $discounts[3];
		}

		// Attribute price added
		$generateAttributeCart = isset($data['cart_attribute']) ?
			$data['cart_attribute'] : \Redshop\Cart\Helper::generateAttribute($data);

		if (\Redshop::getConfig()->get('DEFAULT_QUOTATION_MODE'))
		{
			$templateCart = \RedshopHelperTemplate::getTemplate("quotation_cart");
		}
		else
		{
			if (!\Redshop::getConfig()->get('USE_AS_CATALOG'))
			{
				$templateCart = \RedshopHelperTemplate::getTemplate("cart");
			}
			else
			{
				$templateCart = \RedshopHelperTemplate::getTemplate("catalogue_cart");
			}
		}

		$retAttArr = \productHelper::getInstance()->makeAttributeCart(
			$generateAttributeCart, $product->product_id, 0, $data['product_price'], $quantity, $templateCart[0]->template_desc
		);

		$selectProp                           = \productHelper::getInstance()->getSelectedAttributeArray($data);

		if (\JFactory::getApplication()->input->getString('task') == 'reorder' && !empty($generateAttributeCart))
		{
			$propertyReOrderItemArr = array();
			$subPropertyReOrderItemArr = array();

			foreach ($generateAttributeCart as $idxRe => $itemRe)
			{
				if (!empty($itemRe['attribute_childs']))
				{
					$propertyReOrderItemArr[] = $itemRe['attribute_childs'][0]['property_id'];

					if (!empty($itemRe['attribute_childs'][0]['property_childs']))
					{
						$subPropertyReOrderItemArr[] = $itemRe['attribute_childs'][0]['property_childs'][0]['subproperty_id'];
					}
					else
					{
						$subPropertyReOrderItemArr[] = '';
					}
				}
			}

			$propertyReOrderItemStr = implode('##', $propertyReOrderItemArr);
			$subPropertyReOrderItemStr = implode('##', $subPropertyReOrderItemArr);

			$dataReOrder = array();
			$dataReOrder['property_data'] = $propertyReOrderItemStr;
			$dataReOrder['subproperty_data'] = $subPropertyReOrderItemStr;
			$selectProp = \productHelper::getInstance()->getSelectedAttributeArray($dataReOrder);
		}

		$data['product_old_price']            = $retAttArr[5] + $retAttArr[6];
		$data['product_old_price_excl_vat']   = $retAttArr[5];
		$data['product_price']                = $retAttArr[1];
		$productVatPrice                      = $retAttArr[2];
		$cart[$idx]['product_price_excl_vat'] = $retAttArr[1];

		$data['product_price'] += $productVatPrice;

		if (!empty($selectProp[0]))
		{
			$attributeImage = $productId;

			if (count($selectProp[0]) == 1)
			{
				$attributeImage .= '_p' . $selectProp[0][0];
			}
			else
			{
				$productAttrImage = implode('_p', $selectProp[0]);
				$attributeImage  .= '_p' . $productAttrImage;
			}

			if (count($selectProp[1]) == 1)
			{
				$attributeImage .= '_sp' . $selectProp[1][0];
			}
			else
			{
				$subAttrImage = implode('_sp', $selectProp[1]);

				if ($subAttrImage)
				{
					$attributeImage .= '_sp' . $subAttrImage;
				}
			}

			$cart[$idx]['attributeImage'] = $attributeImage . '.png';
		}

		if (!empty($data['reorder']) && !empty($data['attributeImage']))
		{
			$cart[$idx]['attributeImage'] = $data['attributeImage'];
		}

		$selectedAttrId       = $retAttArr[3];
		$isStock              = $retAttArr[4];
		$selectedPropId       = $selectProp[0];
		$notSelectedSubPropId = $retAttArr[8];
		$productPreOrder      = $product->preorder;

		// Check for the required attributes if selected
		$handleMessage = \rsCarthelper::getInstance()->handleRequiredSelectedAttributeCartMessage(
			$data, $dataAdd, $selectedAttrId, $selectedPropId, $notSelectedSubPropId
		);

		if (!empty($handleMessage))
		{
			return $handleMessage;
		}

		// Check for product or attribute in stock
		if (!$isStock)
		{
			if (($productPreOrder == "global" && !\Redshop::getConfig()->get('ALLOW_PRE_ORDER'))
				|| ($productPreOrder == "no") || ($productPreOrder == "" && !\Redshop::getConfig()->get('ALLOW_PRE_ORDER')))
			{
				return urldecode(\JText::_('COM_REDSHOP_PRODUCT_OUTOFSTOCK_MESSAGE'));
			}
		}

		$cart[$idx]['subscription_id'] = 0;

		if ($product->product_type === 'subscription')
		{
			if (isset($data['subscription_id']) && $data['subscription_id'] != "")
			{
				$subscription      = \productHelper::getInstance()->getProductSubscriptionDetail($data['product_id'], $data['subscription_id']);
				$subscriptionPrice = $subscription->subscription_price;
				$subscriptionVat   = 0;

				if ($subscriptionPrice)
				{
					$subscriptionVat = \RedshopHelperProduct::getProductTax($data['product_id'], $subscriptionPrice);
				}

				$productVatPrice                      += $subscriptionVat;
				$data['product_price']                 = $data['product_price'] + $subscriptionPrice + $subscriptionVat;
				$data['product_old_price']             = $data['product_old_price'] + $subscriptionPrice + $subscriptionVat;
				$data['product_old_price_excl_vat']   += $subscriptionPrice;
				$cart[$idx]['product_price_excl_vat'] += $subscriptionPrice;
				$cart[$idx]['subscription_id']         = $data['subscription_id'];
			}
			else
			{
				return urldecode(\JText::_('COM_REDSHOP_PLEASE_SELECT_YOUR_SUBSCRIPTION_PLAN'));
			}
		}

		// Accessory price
		if (\Redshop::getConfig()->get('ACCESSORY_AS_PRODUCT_IN_CART_ENABLE'))
		{
			if (isset($data['accessory_data']))
			{
				// Append previously added accessories as products
				if (!empty($cart['AccessoryAsProduct'][0]))
				{
					$data['accessory_data']       = $cart['AccessoryAsProduct'][0] . '@@' . $data['accessory_data'];
					$data['acc_quantity_data']    = $cart['AccessoryAsProduct'][1] . '@@' . $data['acc_quantity_data'];
					$data['acc_attribute_data']   = $cart['AccessoryAsProduct'][2] . '@@' . $data['acc_attribute_data'];
					$data['acc_property_data']    = $cart['AccessoryAsProduct'][3] . '@@' . $data['acc_property_data'];
					$data['acc_subproperty_data'] = $cart['AccessoryAsProduct'][4] . '@@' . $data['acc_subproperty_data'];
				}

				$cart['AccessoryAsProduct'] = array(
					$data['accessory_data'],
					$data['acc_quantity_data'],
					$data['acc_attribute_data'],
					$data['acc_property_data'],
					$data['acc_subproperty_data']
				);
			}

			$generateAccessoryCart        = array();
			$data['accessory_data']       = "";
			$data['acc_quantity_data']    = "";
			$data['acc_attribute_data']   = "";
			$data['acc_property_data']    = "";
			$data['acc_subproperty_data'] = "";
		}
		else
		{
			$generateAccessoryCart = isset($data['cart_accessory']) ?
				$data['cart_accessory'] : \rsCarthelper::getInstance()->generateAccessoryArray($data);

			if (isset($data['accessory_data']) && ($data['accessory_data'] != "" && $data['accessory_data'] != 0))
			{
				if (is_bool($generateAccessoryCart))
				{
					return \JText::_('COM_REDSHOP_ACCESSORY_HAS_REQUIRED_ATTRIBUTES');
				}

				return false;
			}
		}

		$resultAccessories   = \productHelper::getInstance()->makeAccessoryCart($generateAccessoryCart, $product->product_id);
		$accessoryTotalPrice = $resultAccessories[1];
		$accessoryVatPrice   = $resultAccessories[2];

		$cart[$idx]['product_price_excl_vat'] += $accessoryTotalPrice;
		$data['product_price']                += $accessoryTotalPrice + $accessoryVatPrice;
		$data['product_old_price']            += $accessoryTotalPrice + $accessoryVatPrice;
		$data['product_old_price_excl_vat']   += $accessoryTotalPrice;

		$cart[$idx]['product_vat'] = $productVatPrice + $accessoryVatPrice;

		// ADD WRAPPER PRICE
		$wrapperPrice = 0;
		$wrapperVat   = 0;

		if (isset($data['sel_wrapper_id']) && $data['sel_wrapper_id'])
		{
			$wrapperArr = \rsCarthelper::getInstance()->getWrapperPriceArr(
				array('product_id' => $data['product_id'], 'wrapper_id' => $data['sel_wrapper_id'])
			);

			$wrapperVat   = $wrapperArr['wrapper_vat'];
			$wrapperPrice = $wrapperArr['wrapper_price'];
		}

		$cart[$idx]['product_vat']            += $wrapperVat;
		$data['product_price']                += $wrapperPrice + $wrapperVat;
		$data['product_old_price']            += $wrapperPrice + $wrapperVat;
		$data['product_old_price_excl_vat']   += $wrapperPrice;
		$cart[$idx]['product_price_excl_vat'] += $wrapperPrice;

		// Checking For same Product and update Quantity
		$selectAcc = \productHelper::getInstance()->getSelectedAccessoryArray($data);
		$selectAtt = \productHelper::getInstance()->getSelectedAttributeArray($data);

		$sameProduct = false;

		\JPluginHelper::importPlugin('redshop_product');

		for ($i = 0; $i < $idx; $i++)
		{
			if ($cart[$i]['product_id'] === $data['product_id'])
			{
				$sameProduct = true;

				if (isset($data['subscription_id']) && $cart[$i]['subscription_id'] != $data['subscription_id'])
				{
					$sameProduct = false;
				}

				if (isset($data['sel_wrapper_id']) && $cart[$i]['wrapper_id'] != $data['sel_wrapper_id'])
				{
					$sameProduct = false;
				}

				$prevSelectAtt = \rsCarthelper::getInstance()->getSelectedCartAttributeArray($cart[$i]['cart_attribute']);

				$diff1 = array_diff($prevSelectAtt[0], $selectAtt[0]);
				$diff2 = array_diff($selectAtt[0], $prevSelectAtt[0]);

				if (count($diff1) > 0 || count($diff2) > 0)
				{
					$sameProduct = false;
				}

				if (!empty($discounts)
					&& ($cart[$i]["discount_calc"]["calcWidth"] !== $data["calcWidth"]
					|| $cart[$i]["discount_calc"]["calcDepth"] !== $data["calcDepth"])
				)
				{
					$sameProduct = false;
				}

				$diff1 = array_diff($prevSelectAtt[1], $selectAtt[1]);
				$diff2 = array_diff($selectAtt[1], $prevSelectAtt[1]);

				if (count($diff1) > 0 || count($diff2) > 0)
				{
					$sameProduct = false;
				}

				$prevSelectAcc = \rsCarthelper::getInstance()->getSelectedCartAccessoryArray($cart[$i]['cart_accessory']);

				$diff1 = array_diff($prevSelectAcc[0], $selectAcc[0]);
				$diff2 = array_diff($selectAcc[0], $prevSelectAcc[0]);

				if (count($diff1) > 0 || count($diff2) > 0)
				{
					$sameProduct = false;
				}

				$diff1 = array_diff($prevSelectAcc[1], $selectAcc[1]);
				$diff2 = array_diff($selectAcc[1], $prevSelectAcc[1]);

				if (count($diff1) > 0 || count($diff2) > 0)
				{
					$sameProduct = false;
				}

				$diff1 = array_diff($prevSelectAcc[2], $selectAcc[2]);
				$diff2 = array_diff($selectAcc[2], $prevSelectAcc[2]);

				if (count($diff1) > 0 || count($diff2) > 0)
				{
					$sameProduct = false;
				}

				// Discount calculator
				$arrayDiffCalc = array_diff_assoc($cart[$i]['discount_calc'], $calcOutputs);

				if (count($arrayDiffCalc) > 0)
				{
					$sameProduct = false;
				}

				/**
				 * Previous comment stated it is not used anymore.
				 * Changing it for another purpose. It can intercept and decide whether added product should be added as same or new product.
				 */
				\RedshopHelperUtility::getDispatcher()->trigger('checkSameCartProduct', array(&$cart, $data, &$sameProduct, $i));

				// Product userfield
				if (!empty($rows))
				{
					$puf = 1;

					foreach ($rows as $row)
					{
						$productUserField = $row->name;
						$addedUserField   = isset($data[$productUserField]) ? $data[$productUserField] : '';

						if (isset($cart[$i][$productUserField]) && $addedUserField !== $cart[$i][$productUserField])
						{
							$puf = 0;
						}
					}

					if ($puf !== 1)
					{
						$sameProduct = false;
					}
				}

				if ($sameProduct)
				{
					$newQuantity     = $cart[$i]['quantity'] + $data['quantity'];
					$newCartQuantity = \rsCarthelper::getInstance()->checkQuantityInStock($cart[$i], $newQuantity);

					if ($newQuantity > $newCartQuantity)
					{
						$cart['notice_message'] = $newCartQuantity . ' ' . \JText::_('COM_REDSHOP_AVAILABLE_STOCK_MESSAGE');
					}
					else
					{
						$cart['notice_message'] = '';
					}

					if ($newCartQuantity != $cart[$i]['quantity'])
					{
						$cart[$i]['quantity'] = $newCartQuantity;

						/*
						 * Trigger the event of redSHOP product plugin support on Same product is going to add into cart
						 *
						 * Usually redSHOP update quantity
						 */
						\RedshopHelperUtility::getDispatcher()->trigger('onSameCartProduct', array(&$cart, $data, $i));

						\RedshopHelperCartSession::setCart($cart);
						$data['cart_index']    = $i;
						$data['quantity']      = $newCartQuantity;
						$data['checkQuantity'] = $newCartQuantity;

						/** @var \RedshopModelCart $cartModel */
						$cartModel = \RedshopModel::getInstance('cart', 'RedshopModel');
						$cartModel->update($data);

						return true;
					}

					return \Redshop::getConfig()->getString('CART_RESERVATION_MESSAGE') !== '' && \Redshop::getConfig()->getBool('IS_PRODUCT_RESERVE')
						? \Redshop::getConfig()->get('CART_RESERVATION_MESSAGE')
						: urldecode(\JText::_('COM_REDSHOP_PRODUCT_OUTOFSTOCK_MESSAGE'));
				}
			}
		}

		// Set product price
		$data['product_price'] = $data['product_price'] < 0 ? 0 : $data['product_price'];

		$perProductTotal = $product->minimum_per_product_total;

		if ($data['product_price'] < $perProductTotal)
		{
			return \JText::_('COM_REDSHOP_PER_PRODUCT_TOTAL') . " " . $perProductTotal;
		}

		if (!$sameProduct)
		{
			// SET VALVUES INTO SESSION CART
			$cart[$idx]['giftcard_id']                = '';
			$cart[$idx]['product_id']                 = $data['product_id'];
			$cart[$idx]['discount_calc_output']       = $calcOutput;
			$cart[$idx]['discount_calc']              = $calcOutputs;
			$cart[$idx]['product_price']              = $data['product_price'];
			$cart[$idx]['product_old_price']          = $data['product_old_price'];
			$cart[$idx]['product_old_price_excl_vat'] = $data['product_old_price_excl_vat'];
			$cart[$idx]['cart_attribute']             = $generateAttributeCart;

			$cart[$idx]['cart_accessory'] = $generateAccessoryCart;

			if (isset($data['hidden_attribute_cartimage']))
			{
				$cart[$idx]['hidden_attribute_cartimage'] = $data['hidden_attribute_cartimage'];
			}

			$cart[$idx]['quantity'] = 0;

			$newQuantity            = $data['quantity'];
			$cart[$idx]['quantity'] = \rsCarthelper::getInstance()->checkQuantityInStock($cart[$idx], $newQuantity);

			if ($newQuantity > $cart[$idx]['quantity'])
			{
				$cart['notice_message'] = $cart[$idx]['quantity'] . " " . \JText::_('COM_REDSHOP_AVAILABLE_STOCK_MESSAGE');
			}
			else
			{
				$cart['notice_message'] = "";
			}

			if ($cart[$idx]['quantity'] <= 0)
			{
				$msg = \Redshop::getConfig()->get('CART_RESERVATION_MESSAGE') != '' && \Redshop::getConfig()->get('IS_PRODUCT_RESERVE')
					? \Redshop::getConfig()->get('CART_RESERVATION_MESSAGE') : \JText::_('COM_REDSHOP_PRODUCT_OUTOFSTOCK_MESSAGE');

				return $msg;
			}

			$cart[$idx]['category_id']   = $data['category_id'];
			$cart[$idx]['wrapper_id']    = !empty($data['sel_wrapper_id']) ? $data['sel_wrapper_id'] : 0;
			$cart[$idx]['wrapper_price'] = $wrapperPrice + $wrapperVat;

			/**
			 * Implement new plugin support before session update
			 * trigger the event of redSHOP product plugin support on Before cart session is set - on prepare cart session
			 */
			\RedshopHelperUtility::getDispatcher()->trigger('onBeforeSetCartSession', array(&$cart, $data, $idx));

			$cart['idx'] = $idx + 1;

			foreach ($rows as $row)
			{
				$fieldName = $row->name;
				$dataTxt   = (isset($data[$fieldName])) ? $data[$fieldName] : '';
				$tmpTxt    = strpbrk($dataTxt, '`');

				if ($tmpTxt)
				{
					$dataTxt = str_replace('`', ',', $dataTxt);
				}

				$cart[$idx][$fieldName] = $dataTxt;
			}
		}

		return true;
	}
}
