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
 * Class Redshop Helper Cart
 *
 * @since  2.0.3
 */
abstract class RedshopHelperCart
{
	/**
	 * Store Cart to Database
	 *
	 * @param   array $cart Cart data.
	 *
	 * @return  boolean       True on success. False otherwise.
	 *
	 * @since   2.0.3
	 */
	public static function addCartToDatabase($cart = array())
	{
		$user = JFactory::getUser();

		// If user is not logged in don't save in db
		if ($user->guest)
		{
			return false;
		}

		if (empty($cart))
		{
			$cart = JFactory::getSession()->get('cart');
		}

		$idx = isset($cart['idx']) ? (int) ($cart['idx']) : 0;

		$db = JFactory::getDbo();

		try
		{
			$db->transactionStart();

			// Get cart ID.
			$query = $db->getQuery(true)
				->select($db->qn('cart_id'))
				->from($db->qn('#__redshop_usercart'))
				->where($db->qn('user_id') . ' = ' . (int) $user->id);

			$cartId = $db->setQuery($query)->loadResult();

			if (!$cartId)
			{
				$row          = JTable::getInstance('usercart', 'Table');
				$row->user_id = $user->id;
				$row->cdate   = time();
				$row->mdate   = time();

				if (!$row->store())
				{
					throw new Exception($row->getError());
				}

				$cartId = $row->cart_id;
			}

			$delCart = (!$idx) ? true : false;

			// Clean current cart.
			if (!self::removeCartFromDatabase($cartId, $user->id, $delCart))
			{
				return false;
			}

			for ($i = 0; $i < $idx; $i++)
			{
				$rowItem = JTable::getInstance('usercart_item', 'Table');

				$rowItem->cart_idx   = $i;
				$rowItem->cart_id    = $cartId;
				$rowItem->product_id = $cart[$i]['product_id'];
				$rowItem->attribs    = serialize($cart[$i]);

				if (isset($cart[$i]['giftcard_id']) === false)
				{
					$cart[$i]['giftcard_id'] = 0;
				}

				if (isset($cart[$i]['wrapper_id']) === false)
				{
					$cart[$i]['wrapper_id'] = 0;
				}

				$rowItem->giftcard_id        = $cart[$i]['giftcard_id'];
				$rowItem->product_quantity   = $cart[$i]['quantity'];
				$rowItem->product_wrapper_id = $cart[$i]['wrapper_id'];

				if (isset($cart[$i]['subscription_id']) === false)
				{
					$cart[$i]['subscription_id'] = 0;
				}

				$rowItem->product_subscription_id = $cart[$i]['subscription_id'];

				if (!$rowItem->store())
				{
					throw new Exception($rowItem->getError());
				}

				$cartItemId = $rowItem->cart_item_id;

				$cart_attribute = array();

				if (isset($cart[$i]['cart_attribute']))
				{
					$cart_attribute = $cart[$i]['cart_attribute'];
				}

				/* store attribute in db */
				self::addCartAttributeToDatabase($cart_attribute, $cartItemId, $rowItem->product_id);

				$cart_accessory = array();

				if (isset($cart[$i]['cart_accessory']))
				{
					$cart_accessory = $cart[$i]['cart_accessory'];
				}

				for ($j = 0, $jn = count($cart_accessory); $j < $jn; $j++)
				{
					$rowAcc               = JTable::getInstance('usercart_accessory_item', 'Table');
					$rowAcc->accessory_id = $cart_accessory[$j]['accessory_id'];

					// Store product quantity as accessory quantity.
					$rowAcc->accessory_quantity = $cart[$i]['quantity'];

					if (!$rowAcc->store())
					{
						throw new Exception($rowAcc->getError());
					}

					$accessory_childs = $cart_accessory[$j]['accessory_childs'];

					self::addCartAttributeToDatabase($accessory_childs, $cartItemId, $rowAcc->accessory_id, true);
				}
			}

			$db->transactionCommit();
		}
		catch (Exception $e)
		{
			$db->transactionRollback();

			return false;
		}

		return true;
	}

	/**
	 * Method for remove cart from Database
	 *
	 * @param   int  $cartId  ID of cart.
	 * @param   int  $userId  Id of user.
	 * @param   bool $delCart Delete cart.
	 *
	 * @return  bool
	 *
	 * @since   2.0.3
	 */
	public static function removeCartFromDatabase($cartId = 0, $userId = 0, $delCart = false)
	{
		if (!$userId)
		{
			$user   = JFactory::getUser();
			$userId = (int) $user->id;
		}

		$db = JFactory::getDbo();

		if ($cartId == 0)
		{
			$query = $db->getQuery(true)
				->select($db->qn('cart_id'))
				->from($db->qn('#__redshop_usercart'))
				->where($db->qn('user_id') . ' = ' . (int) $userId);

			$cartId = $db->setQuery($query)->loadResult();
		}

		if (!$cartId)
		{
			return true;
		}

		$query = $db->getQuery(true)
			->select($db->qn('cart_item_id'))
			->from($db->qn('#__redshop_usercart_item'))
			->where($db->qn('cart_id') . ' = ' . (int) $cartId);

		$cartItemIds = $db->setQuery($query)->loadColumn();

		try
		{
			$db->transactionStart();

			if ($cartItemIds)
			{
				$cartItemIds = ArrayHelper::toInteger($cartItemIds);

				// Delete accessory
				$query = $db->getQuery(true)
					->delete($db->qn('#__redshop_usercart_accessory_item'))
					->where($db->qn('cart_item_id') . ' IN (' . implode(',', $cartItemIds) . ')');

				$db->setQuery($query);
				$db->execute();

				// Delete attribute
				$query = $db->getQuery(true)
					->delete($db->qn('#__redshop_usercart_attribute_item'))
					->where($db->qn('cart_item_id') . ' IN (' . implode(',', $cartItemIds) . ')');

				$db->setQuery($query);
				$db->execute();
			}

			// Delete cart item
			$query = $db->getQuery(true)
				->delete($db->qn('#__redshop_usercart_item'))
				->where($db->qn('cart_id') . ' = ' . (int) $cartId);

			$db->setQuery($query);
			$db->execute();

			if ($delCart)
			{
				$query = $db->getQuery(true)
					->delete($db->qn('#__redshop_usercart'))
					->where($db->qn('cart_id') . ' = ' . (int) $cartId);

				$db->setQuery($query);
				$db->execute();
			}

			$db->transactionCommit();
		}
		catch (Exception $e)
		{
			$db->transactionRollback();

			return false;
		}

		return true;
	}

	/**
	 * Store Cart Attribute to Database
	 *
	 * @param   array   $attributes  Cart attribute data.
	 * @param   int     $cartItemId  Cart item ID
	 * @param   int     $productId   Cart product ID.
	 * @param   boolean $isAccessory Is this accessory?
	 *
	 * @return  boolean       True on success. False otherwise.
	 *
	 * @since   2.0.3
	 */
	public static function addCartAttributeToDatabase($attributes = array(), $cartItemId = 0, $productId = 0, $isAccessory = false)
	{
		if ($cartItemId == 0)
		{
			return false;
		}

		$db = JFactory::getDbo();

		try
		{
			$db->transactionStart();

			foreach ($attributes as $attribute)
			{
				$table = JTable::getInstance('usercart_attribute_item', 'Table');

				$table->cart_item_id      = $cartItemId;
				$table->section_id        = $attribute['attribute_id'];
				$table->section           = 'attribute';
				$table->parent_section_id = $productId;
				$table->is_accessory_att  = $isAccessory;

				if (!$table->store())
				{
					throw new Exception($table->getError());
				}

				$attributeChildren = $attribute['attribute_childs'];

				foreach ($attributeChildren as $attributeChild)
				{
					$itemTable = JTable::getInstance('usercart_attribute_item', 'Table');

					$itemTable->cart_item_id      = $cartItemId;
					$itemTable->section_id        = $attributeChild['property_id'];
					$itemTable->section           = 'property';
					$itemTable->parent_section_id = $attribute['attribute_id'];
					$itemTable->is_accessory_att  = $isAccessory;

					if (!$itemTable->store())
					{
						throw new Exception($table->getError());
					}

					if (empty($attributeChild['property_childs']))
					{
						continue;
					}

					foreach ($attributeChild['property_childs'] as $property)
					{
						$propertyTable = JTable::getInstance('usercart_attribute_item', 'Table');

						$propertyTable->section_id        = $property['subproperty_id'];
						$propertyTable->section           = 'subproperty';
						$propertyTable->parent_section_id = $attributeChild['property_id'];
						$propertyTable->is_accessory_att  = $isAccessory;

						if (!$propertyTable->store())
						{
							throw new Exception($table->getError());
						}
					}
				}
			}

			$db->transactionCommit();
		}
		catch (Exception $e)
		{
			$db->transactionRollback();

			return false;
		}

		return true;
	}

	/**
	 * Method for convert data from database to cart.
	 *
	 * @param   int $userId ID of user.
	 *
	 * @return  void
	 *
	 * @since  2.0.3
	 */
	public static function databaseToCart($userId = 0)
	{
		if (!$userId == 0)
		{
			$user   = JFactory::getUser();
			$userId = $user->id;
		}

		$productHelper = productHelper::getInstance();
		$cartHelper    = rsCarthelper::getInstance();

		$db    = JFactory::getDbo();
		$query = $db->getQuery(true);

		$query->select(
			$db->qn(
				[
					'ci.cart_item_id', 'ci.cart_idx', 'ci.product_id', 'ci.product_quantity',
					'ci.product_wrapper_id', 'ci.product_subscription_id', 'ci.giftcard_id', 'ci.attribs'
				]
			)
		)
			->from($db->qn('#__redshop_usercart_item', 'ci'))
			->leftJoin($db->qn('#__redshop_usercart', 'c') . ' ON ' . $db->qn('c.cart_id') . ' = ' . $db->qn('ci.cart_id'))
			->where($db->qn('c.user_id') . ' = ' . $userId)
			->order($db->qn('ci.cart_idx'));
		$cartItems = $db->setQuery($query)->loadObjectList();

		if (empty($cartItems))
		{
			return;
		}

		$cart = array();
		$idx  = 0;

		foreach ($cartItems as $cartItem)
		{
			$setCartItem           = true;
			$quantity              = $cartItem->product_quantity;
			$calcOutput            = "";
			$calcOutputs           = array();
			$productId             = $cartItem->product_id;
			$subscriptionId        = 0;
			$wrapperPrice          = 0;
			$productVatPrice       = 0;
			$productOldPrice       = 0;
			$productOldPriceNoVat  = 0;
			$generateAttributeCart = '';
			$generateAccessoryCart = '';
			$giftCardId            = '';
			$attributes            = unserialize($cartItem->attribs);

			if ($cartItem->giftcard_id)
			{
				$section       = 13;
				$giftCardId    = $cartItem->giftcard_id;
				$giftCardPrice = 0;

				if ($giftCardData = $productHelper->getGiftcardData($giftCardId))
				{
					if ($giftCardData->customer_amount)
					{
						$customerAmount = '';

						if (isset($attributes['customer_amount']))
						{
							$customerAmount = $attributes['customer_amount'];
						}

						$giftCardPrice = $customerAmount;
					}
					else
					{
						$giftCardPrice = $giftCardData->giftcard_price;
					}
				}

				$productPrice      = $giftCardPrice;
				$productPriceNoVat = $giftCardPrice;
			}
			else
			{
				$section      = 12;
				$cartItemId   = $cartItem->cart_item_id;
				$productPrice = 0;
				$productData  = RedshopHelperProduct::getProductById($productId);

				// Attribute price added
				$generateAttributeCart = self::generateAttributeFromCart($cartItemId, 0, $productId, $quantity);
				$cartAttributes        = $productHelper->makeAttributeCart($generateAttributeCart, $productId, 0, $productPrice, $quantity);

				$productPriceNoVat    = $cartAttributes[1];
				$productVatPrice      = $cartAttributes[2];
				$selectedAttributeId  = $cartAttributes[3];
				$isStock              = $cartAttributes[4];
				$productOldPrice      = $cartAttributes[5] + $cartAttributes[6];
				$productOldPriceNoVat = $cartAttributes[5];
				$productPrice         = $productPriceNoVat + $productVatPrice;

				if (!$isStock)
				{
					$setCartItem = false;
					$msg         = JText::_('COM_REDSHOP_PRODUCT_OUTOFSTOCK_MESSAGE');
				}

				if ($productData->product_type == 'subscription')
				{
					$productSubscription = $productHelper->getProductSubscriptionDetail($productId, $cartItem->product_subscription_id);

					if ($productSubscription->subscription_id != "")
					{
						$subscriptionId    = $productSubscription->subscription_id;
						$subscriptionPrice = $productSubscription->subscription_price;
						$subscriptionVAT   = 0;

						if ($subscriptionPrice)
						{
							$subscriptionVAT = $productHelper->getProductTax($productId, $subscriptionPrice);
						}

						$productVatPrice      += $subscriptionVAT;
						$productPrice         += $subscriptionPrice + $subscriptionVAT;
						$productOldPrice      = $productOldPrice + $subscriptionPrice + $subscriptionVAT;
						$productOldPriceNoVat += $subscriptionPrice;
						$productPriceNoVat    += $subscriptionPrice;
					}
					else
					{
						$setCartItem = false;
						$msg         = JText::_('COM_REDSHOP_SELECT_PRODUCT_SUBSCRIPTION');
					}
				}

				// Accessory price
				$generateAccessoryCart = $cartHelper->generateAccessoryFromCart($cartItemId, $productId, $quantity);
				$accessoriesData       = $productHelper->makeAccessoryCart($generateAccessoryCart, $productId);
				$accessoryTotalPrice   = $accessoriesData[1];
				$accessoryVATPrice     = $accessoriesData[2];

				$productPriceNoVat    += $accessoryTotalPrice;
				$productPrice         += $accessoryTotalPrice + $accessoryVATPrice;
				$productOldPrice      += $accessoryTotalPrice + $accessoryVATPrice;
				$productOldPriceNoVat += $accessoryTotalPrice;
				$productVatPrice      = $productVatPrice + $accessoryVATPrice;

				// Check if required attribute is filled or not
				if (count($selectedAttributeId) > 0)
				{
					$selectedAttributeId = implode(",", $selectedAttributeId);
				}

				$requiredAttributeData = RedshopHelperProduct_Attribute::getProductAttribute($productId, 0, 0, 0, 1, $selectedAttributeId);

				if (count($requiredAttributeData) > 0)
				{
					$requiredAttributes = array();

					foreach ($requiredAttributeData as $requiredAttribute)
					{
						$requiredAttributes = $requiredAttribute->attribute_name;
					}

					$requiredAttributeName = implode(", ", $requiredAttributes);

					// Throw an error as first attribute is required
					$msg         = $requiredAttributeName . " " . JText::_('COM_REDSHOP_IS_REQUIRED');
					$setCartItem = false;
				}

				// ADD WRAPPER PRICE
				$wrapperVAT = 0;

				if ($cartItem->product_wrapper_id)
				{
					$wrapperArr   = $cartHelper->getWrapperPriceArr(array('product_id' => $productId, 'wrapper_id' => $cartItem->product_wrapper_id));
					$wrapperVAT   = $wrapperArr['wrapper_vat'];
					$wrapperPrice = $wrapperArr['wrapper_price'];
				}

				$productVatPrice      += $wrapperVAT;
				$productPrice         += $wrapperPrice + $wrapperVAT;
				$productOldPrice      += $wrapperPrice + $wrapperVAT;
				$productOldPriceNoVat += $wrapperPrice;
				$productPriceNoVat    += $wrapperPrice;
			}

			// END WRAPPER PRICE
			if ($setCartItem)
			{
				if ($productPrice < 0)
				{
					$productPrice = 0;
				}

				if ($fields = RedshopHelperExtrafields::getSectionFieldList($section))
				{
					foreach ($fields as $field)
					{
						$dataTxt = (isset($attributes[$field->field_name])) ? $attributes[$field->field_name] : '';
						$text    = strpbrk($dataTxt, '`');

						if ($text)
						{
							$list = explode('`', $dataTxt);

							if (is_array($list))
							{
								$dataTxt = implode(",", $list);
							}
						}

						$cart[$idx][$field->field_name] = $dataTxt;
					}
				}

				$cart[$idx]['product_price']          = $productPrice;
				$cart[$idx]['product_price_excl_vat'] = $productPriceNoVat;
				$cart[$idx]['giftcard_id']            = $giftCardId;

				if ($giftCardId)
				{
					$cart[$idx]['reciver_email']   = $attributes['reciver_email'];
					$cart[$idx]['reciver_name']    = $attributes['reciver_name'];
					$cart[$idx]['customer_amount'] = '';

					if (isset($attributes['customer_amount']))
					{
						$cart[$idx]['customer_amount'] = $attributes['customer_amount'];
					}

					$cart[$idx]['product_vat'] = 0;
					$cart[$idx]['product_id']  = '';
					$cart[$idx]['quantity']    = $quantity;
				}
				else
				{
					$cart[$idx]['product_id']                 = $productId;
					$cart[$idx]['discount_calc_output']       = $calcOutput;
					$cart[$idx]['discount_calc']              = $calcOutputs;
					$cart[$idx]['product_vat']                = $productVatPrice;
					$cart[$idx]['product_old_price']          = $productOldPrice;
					$cart[$idx]['product_old_price_excl_vat'] = $productOldPriceNoVat;
					$cart[$idx]['cart_attribute']             = $generateAttributeCart;
					$cart[$idx]['cart_accessory']             = $generateAccessoryCart;
					$cart[$idx]['subscription_id']            = $subscriptionId;
					$cart[$idx]['category_id']                = 0;
					$cart[$idx]['wrapper_id']                 = $cartItem->product_wrapper_id;
					$cart[$idx]['wrapper_price']              = $wrapperPrice;
					$cart[$idx]['quantity']                   = RedshopHelperStockroom::checkQuantityInStock($cart[$idx], $quantity);
				}

				if ($cart[$idx]['quantity'] <= 0)
				{
					$msg = JText::_('COM_REDSHOP_PRODUCT_OUTOFSTOCK_MESSAGE');

					if (Redshop::getConfig()->get('CART_RESERVATION_MESSAGE') != '')
					{
						$msg = Redshop::getConfig()->get('CART_RESERVATION_MESSAGE');
					}
				}
				else
				{
					$idx++;
				}
			}
		}

		$shopperGroup = RedshopHelperUser::getShopperGroup($userId);

		$cart['idx']                   = $idx;
		$cart['discount_type']         = 0;
		$cart['discount']              = 0;
		$cart['user_shopper_group_id'] = $shopperGroup;

		// Set 0 as default..
		$cart['free_shipping']    = 0;
		$cart['voucher_discount'] = 0;
		$cart['coupon_discount']  = 0;
		$cart['cart_discount']    = 0;

		JFactory::getSession()->set('cart', $cart);

		RedshopHelperCart::cartFinalCalculation();
	}

	/**
	 * Method for generate attribute from cart.
	 *
	 * @param   int $cartItemId      ID of cart item.
	 * @param   int $isAccessory     Is accessory?
	 * @param   int $parentSectionId ID of parent section
	 * @param   int $quantity        Quantity of product.
	 *
	 * @return  array
	 *
	 * @since  2.0.3
	 */
	public static function generateAttributeFromCart($cartItemId = 0, $isAccessory = 0, $parentSectionId = 0, $quantity = 1)
	{
		$cartHelper         = rsCarthelper::getInstance();
		$generateAttributes = array();

		$cartAttributes = $cartHelper->getCartItemAttributeDetail($cartItemId, $isAccessory, "attribute", $parentSectionId);

		for ($i = 0, $in = count($cartAttributes); $i < $in; $i++)
		{
			$attribute          = RedshopHelperProduct_Attribute::getProductAttribute(0, 0, $cartAttributes[$i]->section_id);
			$generateProperties = array();

			$generateAttributes[$i]['attribute_id']   = $cartAttributes[$i]->section_id;
			$generateAttributes[$i]['attribute_name'] = $attribute[0]->text;

			$cartProperties = $cartHelper->getCartItemAttributeDetail($cartItemId, $isAccessory, "property", $cartAttributes[$i]->section_id);

			for ($p = 0, $pn = count($cartProperties); $p < $pn; $p++)
			{
				$generateSubProperties = array();
				$property              = RedshopHelperProduct_Attribute::getAttributeProperties($cartProperties[$p]->section_id);
				$priceList             = RedshopHelperProduct_Attribute::getPropertyPrice($cartProperties[$p]->section_id, $quantity, 'property');

				if (!empty($priceList->product_price))
				{
					$propertyPrice = $priceList->product_price;
				}
				else
				{
					$propertyPrice = $property[0]->property_price;
				}

				$generateProperties[$p]['property_id']     = $cartProperties[$p]->section_id;
				$generateProperties[$p]['property_name']   = $property[0]->text;
				$generateProperties[$p]['property_oprand'] = $property[0]->oprand;
				$generateProperties[$p]['property_price']  = $propertyPrice;

				$cartSubProperties = $cartHelper->getCartItemAttributeDetail(
					$cartItemId, $isAccessory, "subproperty", $cartProperties[$p]->section_id
				);

				for ($sp = 0; $sp < count($cartSubProperties); $sp++)
				{
					$subProperty = RedshopHelperProduct_Attribute::getAttributeSubProperties($cartSubProperties[$sp]->section_id);
					$price       = RedshopHelperProduct_Attribute::getPropertyPrice($cartSubProperties[$sp]->section_id, $quantity, 'subproperty');

					if (count($price) > 0)
					{
						$subPropertyPrice = $price->product_price;
					}
					else
					{
						$subPropertyPrice = $subProperty[0]->subattribute_color_price;
					}

					$generateSubProperties[$sp]['subproperty_id']     = $cartSubProperties[$sp]->section_id;
					$generateSubProperties[$sp]['subproperty_name']   = $subProperty[0]->text;
					$generateSubProperties[$sp]['subproperty_oprand'] = $subProperty[0]->oprand;
					$generateSubProperties[$sp]['subproperty_price']  = $subPropertyPrice;
				}

				$generateProperties[$p]['property_childs'] = $generateSubProperties;
			}

			$generateAttributes[$i]['attribute_childs'] = $generateProperties;
		}

		return $generateAttributes;
	}

	/**
	 * Method for calculate final price of cart.
	 *
	 * @param   bool $isModify Is modify cart?
	 *
	 * @return  array
	 *
	 * @since  2.0.3
	 */
	public static function cartFinalCalculation($isModify = true)
	{
		$ajax = JFactory::getApplication()->input->get('ajax_cart_box');
		$cart = JFactory::getSession()->get('cart');

		if ($isModify === true)
		{
			$cart = rsCarthelper::getInstance()->modifyDiscount($cart);
		}

		$cartOutput = array();
		$carts      = self::generateCartOutput($cart);
		$text       = RedshopHelperShipping::getFreeShippingRate();

		$cartOutput['cart_output']    = $carts[0];
		$cartOutput['total_quantity'] = $carts[1];

		if (Redshop::getConfig()->get('AJAX_CART_BOX') == 1 && $ajax == 1)
		{
			echo "`" . $carts[0] . "`" . $text;
			exit;
		}

		return $cartOutput;
	}

	/**
	 * Method for render cart.
	 *
	 * @param   array $cart Cart data
	 *
	 * @return  array
	 *
	 * @since  2.0.3
	 */
	public static function generateCartOutput($cart = array())
	{
		$return           = array();
		$totalQuantity    = 0;
		$idx              = $cart['idx'];
		$showWithVAT      = 0;
		$html             = 'simple';
		$showShippingLine = 0;
		$cartParams       = rsCarthelper::getInstance()->GetCartParameters();

		if (array_key_exists('cart_output', $cartParams))
		{
			$html = $cartParams['cart_output'];
		}

		if (array_key_exists('show_shipping_line', $cartParams))
		{
			$showShippingLine = $cartParams['show_shipping_line'];
		}

		for ($i = 0; $i < $idx; $i++)
		{
			$totalQuantity += $cart[$i]['quantity'];

			if (array_key_exists('show_with_vat', $cartParams))
			{
				$showWithVAT = $cartParams['show_with_vat'];
			}
		}

		// Load cart module language
		$lang = JFactory::getLanguage();
		$lang->load('mod_redshop_cart', JPATH_SITE);

		$return[] = RedshopLayoutHelper::render(
			'cart.cart',
			array(
				'cartOutput'       => $html,
				'totalQuantity'    => $totalQuantity,
				'cart'             => $cart,
				'showWithVat'      => $showWithVAT,
				'showShippingLine' => $showShippingLine
			),
			'',
			array('option' => 'com_redshop')
		);

		$return[] = $totalQuantity;

		return $return;
	}

	/**
	 * Calculate tax after Discount is apply
	 *
	 * @param   float $tax      Tax amount
	 * @param   float $discount Discount amount.
	 *
	 * @return  float             Tax after apply discount.
	 *
	 * @since   2.0.3
	 */
	public static function calculateTaxAfterDiscount($tax = 0.0, $discount = 0.0)
	{
		$taxAfterDiscount = 0;
		$cart             = JFactory::getSession()->get('cart');

		if (Redshop::getConfig()->get('APPLY_VAT_ON_DISCOUNT') && (float) Redshop::getConfig()->get('VAT_RATE_AFTER_DISCOUNT'))
		{
			if ($discount > 0.0)
			{
				$applyTax         = (float) Redshop::getConfig()->get('VAT_RATE_AFTER_DISCOUNT') * $discount;
				$taxAfterDiscount = $tax - $applyTax;
			}
		}

		$cart['tax_after_discount'] = $taxAfterDiscount;
		JFactory::getSession()->set('cart', $cart);

		return $taxAfterDiscount;
	}

	/**
	 * Check user for Tax Exemption approved
	 *
	 * @param   integer $userId                User Information Id - Login user id
	 * @param   boolean $isShowButtonAddToCart Display Add to cart button for tax exemption user
	 *
	 * @return  boolean                          True if VAT applied else false
	 *
	 * @since   2.0.6
	 */
	public static function taxExemptAddToCart($userId = 0, $isShowButtonAddToCart = false)
	{
		$user = JFactory::getUser();

		if ($userId == 0)
		{
			$userId = $user->id;
		}

		if (!$userId)
		{
			return true;
		}

		$userInformation = RedshopHelperUser::getUserInformation($userId);

		if (!empty($userInformation->user_id))
		{
			if ($userInformation->requesting_tax_exempt == 0)
			{
				return true;
			}
			elseif ($userInformation->requesting_tax_exempt == 1 && $userInformation->tax_exempt_approved == 0)
			{
				if ($isShowButtonAddToCart)
				{
					return false;
				}

				return true;
			}
			elseif ($userInformation->requesting_tax_exempt == 1 && $userInformation->tax_exempt_approved == 1)
			{
				if ($isShowButtonAddToCart)
				{
					return true;
				}

				return false;
			}
		}

		return true;
	}

	/**
	 * Add GiftCard To Cart
	 *
	 * @param   array $cartItem Cart item
	 * @param   array $data     User cart data
	 *
	 * @return  void
	 *
	 * @since   __DEPLOY_VERSION__
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

		$giftcardData = productHelper::getInstance()->getGiftcardData($data['giftcard_id']);

		if ($giftcardData && $giftcardData->customer_amount)
		{
			$giftCardPrice = $cartItem['customer_amount'];
		}
		else
		{
			$giftCardPrice = $giftcardData->giftcard_price;
		}

		$fields = RedshopHelperExtrafields::getSectionFieldList(RedshopHelperExtrafields::SECTION_GIFT_CARD_USER_FIELD);

		foreach ($fields as $field)
		{
			$text    = isset($data[$field->name]) ? $data[$field->name] : '';
			$tmpText = strpbrk($text, '`');

			if ($tmpText)
			{
				$tmpText = explode('`', $text);

				if (is_array($tmpText))
				{
					$text = implode(",", $tmpText);
				}
			}

			$cartItem[$field->name] = $text;
		}

		$cartItem['product_price']          = $giftCardPrice;
		$cartItem['product_price_excl_vat'] = $giftCardPrice;
		$cartItem['product_vat']            = 0;
		$cartItem['product_id']             = '';
	}

	/**
	 * Add Product To Cart
	 *
	 * @param   array $data Product data
	 *
	 * @return  boolean
	 *
	 * @since   __DEPLOY_VERSION__
	 */
	public static function addProductToCart($data = array())
	{
		JPluginHelper::importPlugin('redshop_product');
		$dispatcher       = RedshopHelperUtility::getDispatcher();
		$redTemplate      = Redtemplate::getInstance();
		$user             = JFactory::getUser();
		$cart             = JFactory::getSession()->get('cart');
		$data['quantity'] = round($data['quantity']);

		if (!$cart || !array_key_exists("idx", $cart) || array_key_exists("quotation_id", $cart))
		{
			$cart        = array();
			$cart['idx'] = 0;
		}

		$idx = (int) ($cart['idx']);

		// Set session for giftcard
		if (isset($data['giftcard_id']) && $data['giftcard_id'])
		{
			$sameGiftCard = false;
			$section      = 13;
			$fields       = RedshopHelperExtrafields::getSectionFieldList($section);

			for ($g = 0; $g < $idx; $g++)
			{
				if ($cart[$g]['giftcard_id'] == $data['giftcard_id']
					&& $cart[$g]['reciver_email'] == $data['reciver_email']
					&& $cart[$g]['reciver_name'] == $data['reciver_name']
				)
				{
					$sameGiftCard = true;

					// Product user field
					if (!empty($fields))
					{
						for ($r = 0, $countRowData = count($fields); $r < $countRowData; $r++)
						{
							$productUserField = $fields[$r]->name;

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

					self::addGiftCardToCart($cart[$g], $data);
				}
			}

			if (!$sameGiftCard)
			{
				$cart[$idx]             = array();
				$cart[$idx]['quantity'] = $data['quantity'];

				self::addGiftCardToCart($cart[$idx], $data);

				$cart['idx'] = $idx + 1;
			}
		}
		// Set session for product
		else
		{
			$section = 12;
			$fields  = RedshopHelperExtrafields::getSectionFieldList($section);

			if (isset($data['hidden_attribute_cartimage']))
			{
				$cart[$idx]['hidden_attribute_cartimage'] = $data['hidden_attribute_cartimage'];
			}

			$productId   = $data['product_id'];
			$quantity    = $data['quantity'];
			$productData = RedshopHelperProduct::getProductById($productId);

			// Handle individual accessory add to cart price
			if (Redshop::getConfig()->get('ACCESSORY_AS_PRODUCT_IN_CART_ENABLE')
				&& isset($data['parent_accessory_product_id'])
				&& $data['parent_accessory_product_id'] != 0
				&& isset($data['accessory_id'])
			)
			{
				$cart[$idx]['accessoryAsProductEligible'] = $data['accessory_id'];
				$accessoryInfo                            = RedshopHelperAccessory::getProductAccessories($data['accessory_id']);
				$productData->product_price               = $accessoryInfo[0]->newaccessory_price;

				$parentAccessoryProduct = RedshopHelperProduct::getProductById($data['parent_accessory_product_id']);
				$productTemplate        = RedshopHelperTemplate::getTemplate("product", $parentAccessoryProduct->product_template);
				$accessoryTemplate      = productHelper::getInstance()->getAccessoryTemplate($productTemplate[0]->template_desc);
				$templateContent        = $accessoryTemplate->template_desc;
			}
			else
			{
				$productTemplate = RedshopHelperTemplate::getTemplate("product", $productData->product_template);
				$templateContent = $productTemplate[0]->template_desc;
			}

			/*
			 * Check if required userfield are filled or not if not than redirect to product detail page...
			 * Get product userfield from selected product template...
			 */
			if (!Redshop::getConfig()->get('AJAX_CART_BOX'))
			{
				$fieldRequire = RedshopHelperExtrafields::userFieldValidation($data, $templateContent, $section);

				if (!empty($fieldRequire))
				{
					return $fieldRequire;
				}
			}

			// Get product price
			$data['product_price'] = 0;

			// Discount calculator procedure start
			$discounts = rsCarthelper::getInstance()->discountCalculatorData($productData, $data);

			$calculateOutput     = "";
			$calculateOutputList = array();
			$productPriceTax     = 0;
			$productVATPrice     = 0;

			if (!empty($discounts))
			{
				$calculateOutput     = $discounts[0];
				$calculateOutputList = $discounts[1];

				// Calculate price without VAT
				$data['product_price'] = $discounts[2];

				$cart[$idx]['product_price_excl_vat'] = $discounts[2];
				$productVATPrice                      += $discounts[3];
				$cart[$idx]['discount_calc_price']    = $discounts[2];
			}

			// Attribute price added
			$generateAttributeCart = isset($data['cart_attribute']) ?
				$data['cart_attribute'] : rsCarthelper::getInstance()->generateAttributeArray($data);

			$cartAttributesData = productHelper::getInstance()->makeAttributeCart(
				$generateAttributeCart, $productData->product_id, 0, $data['product_price'], $quantity
			);

			$selectProp = productHelper::getInstance()->getSelectedAttributeArray($data);

			$data['product_old_price']          = $cartAttributesData[5] + $cartAttributesData[6];
			$data['product_old_price_excl_vat'] = $cartAttributesData[5];
			$data['product_price']              = $cartAttributesData[1];

			$productVATPrice                      = $cartAttributesData[2];
			$cart[$idx]['product_price_excl_vat'] = $cartAttributesData[1];
			$data['product_price']                += $productVATPrice;

			if (!empty($selectProp[0]))
			{
				$attributeImage = $productId;

				if (count($selectProp[0]) == 1)
				{
					$attributeImage .= '_p' . $selectProp[0][0];
				}
				else
				{
					$productAttributeImages = implode('_p', $selectProp[0]);
					$attributeImage         .= '_p' . $productAttributeImages;
				}

				if (count($selectProp[1]) == 1)
				{
					$attributeImage .= '_sp' . $selectProp[1][0];
				}
				else
				{
					$selectedAttributeImages = implode('_sp', $selectProp[1]);

					if ($selectedAttributeImages)
					{
						$attributeImage .= '_sp' . $selectedAttributeImages;
					}
				}

				$cart[$idx]['attributeImage'] = $attributeImage . '.png';
			}

			if (!empty($data['reorder']) && !empty($data['attributeImage']))
			{
				$cart[$idx]['attributeImage'] = $data['attributeImage'];
			}

			$selectedAttrId           = $cartAttributesData[3];
			$isStock                  = $cartAttributesData[4];
			$selectedPropId           = $selectProp[0];
			$notSelectedSubPropertyId = $cartAttributesData[8];
			$productPreOrder          = $productData->preorder;
			$isPreOrderStock          = $cartAttributesData[7];

			// Check for the required attributes if selected
			if ($handleMessage = rsCarthelper::getInstance()->handleRequiredSelectedAttributeCartMessage(
				$data,
				$templateContent,
				$selectedAttrId,
				$selectedPropId,
				$notSelectedSubPropertyId
			)
			)
			{
				return $handleMessage;
			}

			// Check for product or attribute in stock
			if (!$isStock)
			{
				if (($productPreOrder == "global" && !Redshop::getConfig()->get('ALLOW_PRE_ORDER'))
					|| ($productPreOrder == "no") || ($productPreOrder == "" && !Redshop::getConfig()->get('ALLOW_PRE_ORDER'))
				)
				{
					$msg = urldecode(JText::_('COM_REDSHOP_PRODUCT_OUTOFSTOCK_MESSAGE'));

					return $msg;
				}
				elseif (!$isPreOrderStock)
				{
					$msg = urldecode(JText::_('COM_REDSHOP_PREORDER_PRODUCT_OUTOFSTOCK_MESSAGE'));

					return $msg;
				}
			}

			$cart[$idx]['subscription_id'] = 0;

			if ($productData->product_type == 'subscription')
			{
				if (isset($data['subscription_id']) && $data['subscription_id'] != "")
				{
					$subscription      = productHelper::getInstance()->getProductSubscriptionDetail($data['product_id'], $data['subscription_id']);
					$subscriptionPrice = $subscription->subscription_price;
					$subscriptionVAT   = 0;

					if ($subscriptionPrice)
					{
						$subscriptionVAT = RedshopHelperProduct::getProductTax($data['product_id'], $subscriptionPrice);
					}

					$productVATPrice                      += $subscriptionVAT;
					$data['product_price']                = $data['product_price'] + $subscriptionPrice + $subscriptionVAT;
					$data['product_old_price']            = $data['product_old_price'] + $subscriptionPrice + $subscriptionVAT;
					$data['product_old_price_excl_vat']   += $subscriptionPrice;
					$cart[$idx]['product_price_excl_vat'] += $subscriptionPrice;
					$cart[$idx]['subscription_id']        = $data['subscription_id'];
				}
				else
				{
					$msg = urldecode(JText::_('COM_REDSHOP_PLEASE_SELECT_YOUR_SUBSCRIPTION_PLAN'));

					return $msg;
				}
			}

			// Accessory price
			if (Redshop::getConfig()->get('ACCESSORY_AS_PRODUCT_IN_CART_ENABLE'))
			{
				if (isset($data['accessory_data']))
				{
					// Append previously added accessories as products
					if ($cart['AccessoryAsProduct'][0] != '')
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
					$data['cart_accessory'] : RedshopHelperCart::generateAccessoriesCart($data);

				if (isset($data['accessory_data']) && ($data['accessory_data'] != "" && $data['accessory_data'] != 0))
				{
					if (!$generateAccessoryCart)
					{
						return false;
					}
				}
			}

			$cartAccessoriesData = productHelper::getInstance()->makeAccessoryCart($generateAccessoryCart, $productData->product_id);
			$accessoryTotalPrice = $cartAccessoriesData[1];
			$accessoryVATPrice   = $cartAccessoriesData[2];

			$cart[$idx]['product_price_excl_vat'] += $accessoryTotalPrice;
			$data['product_price']                += $accessoryTotalPrice + $accessoryVATPrice;
			$data['product_old_price']            += $accessoryTotalPrice + $accessoryVATPrice;
			$data['product_old_price_excl_vat']   += $accessoryTotalPrice;
			$cart[$idx]['product_vat']            = $productVATPrice + $accessoryVATPrice;

			// ADD WRAPPER PRICE
			$wrapperPrice = 0;
			$wrapperVAT   = 0;

			if (isset($data['sel_wrapper_id']) && $data['sel_wrapper_id'])
			{
				$wrapper = rsCarthelper::getInstance()->getWrapperPriceArr(
					array('product_id' => $data['product_id'], 'wrapper_id' => $data['sel_wrapper_id'])
				);

				$wrapperVAT   = $wrapper['wrapper_vat'];
				$wrapperPrice = $wrapper['wrapper_price'];
			}

			$cart[$idx]['product_vat']            += $wrapperVAT;
			$data['product_price']                += $wrapperPrice + $wrapperVAT;
			$data['product_old_price']            += $wrapperPrice + $wrapperVAT;
			$data['product_old_price_excl_vat']   += $wrapperPrice;
			$cart[$idx]['product_price_excl_vat'] += $wrapperPrice;

			// Checking For same Product and update Quantity
			$selectedAccessories = productHelper::getInstance()->getSelectedAccessoryArray($data);
			$selectedAttributes  = productHelper::getInstance()->getSelectedAttributeArray($data);

			$sameProduct = false;

			for ($i = 0; $i < $idx; $i++)
			{
				if ($cart[$i]['product_id'] == $data['product_id'])
				{
					$sameProduct = true;

					if (isset($data['subscription_id']) && $cart[$i]['subscription_id'] != $data['subscription_id'])
					{
						$sameProduct = false;
					}

					if ($cart[$i]['wrapper_id'] != $data['sel_wrapper_id'])
					{
						$sameProduct = false;
					}

					$prevSelectAtt = rsCarthelper::getInstance()->getSelectedCartAttributeArray($cart[$i]['cart_attribute']);

					$selectedDiff1 = array_diff($prevSelectAtt[0], $selectedAttributes[0]);
					$selectedDiff2 = array_diff($selectedAttributes[0], $prevSelectAtt[0]);

					if (count($selectedDiff1) > 0 || count($selectedDiff2) > 0)
					{
						$sameProduct = false;
					}

					if (!empty($discounts)
						&& ($cart[$i]["discount_calc"]["calcWidth"] != $data["calcWidth"]
							|| $cart[$i]["discount_calc"]["calcDepth"] != $data["calcDepth"])
					)
					{
						$sameProduct = false;
					}

					$selectedDiff1 = array_diff($prevSelectAtt[1], $selectedAttributes[1]);
					$selectedDiff2 = array_diff($selectedAttributes[1], $prevSelectAtt[1]);

					if (count($selectedDiff1) > 0 || count($selectedDiff2) > 0)
					{
						$sameProduct = false;
					}

					$prevSelectAcc = rsCarthelper::getInstance()->getSelectedCartAccessoryArray($cart[$i]['cart_accessory']);

					$selectedDiff1 = array_diff($prevSelectAcc[0], $selectedAccessories[0]);
					$selectedDiff2 = array_diff($selectedAccessories[0], $prevSelectAcc[0]);

					if (count($selectedDiff1) > 0 || count($selectedDiff2) > 0)
					{
						$sameProduct = false;
					}

					$selectedDiff1 = array_diff($prevSelectAcc[1], $selectedAccessories[1]);
					$selectedDiff2 = array_diff($selectedAccessories[1], $prevSelectAcc[1]);

					if (count($selectedDiff1) > 0 || count($selectedDiff2) > 0)
					{
						$sameProduct = false;
					}

					$selectedDiff1 = array_diff($prevSelectAcc[2], $selectedAccessories[2]);
					$selectedDiff2 = array_diff($selectedAccessories[2], $prevSelectAcc[2]);

					if (count($selectedDiff1) > 0 || count($selectedDiff2) > 0)
					{
						$sameProduct = false;
					}

					// Discount calculator
					$discountsDiff = array_diff_assoc($cart[$i]['discount_calc'], $calculateOutputList);

					if (count($discountsDiff) > 0)
					{
						$sameProduct = false;
					}

					/**
					 * Previous comment stated it is not used anymore.
					 * Changing it for another purpose. It can intercept and decide whether added product should be added as same or new product.
					 */
					$dispatcher->trigger('checkSameCartProduct', array(&$cart, $data, &$sameProduct, $i));

					// Product user field
					if (!empty($fields))
					{
						$puf = 1;

						for ($r = 0, $rn = count($fields); $r < $rn; $r++)
						{
							$productUserField = $fields[$r]->name;
							$addedUserField   = $data[$productUserField];

							if (isset($cart[$i][$productUserField]) && $addedUserField != $cart[$i][$productUserField])
							{
								$puf = 0;
							}
						}

						if ($puf != 1)
						{
							$sameProduct = false;
						}
					}

					if ($sameProduct)
					{
						$newQuantity     = $cart[$i]['quantity'] + $data['quantity'];
						$newCartQuantity = RedshopHelperStockroom::checkQuantityInStock($cart[$i], $newQuantity);

						if ($newQuantity > $newCartQuantity)
						{
							$cart['notice_message'] = $newCartQuantity . " " . JTEXT::_('COM_REDSHOP_AVAILABLE_STOCK_MESSAGE');
						}
						else
						{
							$cart['notice_message'] = "";
						}

						if ($newCartQuantity != $cart[$i]['quantity'])
						{
							$cart[$i]['quantity'] = $quantity;

							/*
							 * Trigger the event of redSHOP product plugin support on Same product is going to add into cart
							 *
							 * Usually redSHOP update quantity
							 */
							$dispatcher->trigger('onSameCartProduct', array(& $cart, $data, $i));

							JFactory::getSession()->set('cart', $cart);

							$data['cart_index']    = $i;
							$data['quantity']      = $newCartQuantity;
							$data['checkQuantity'] = $newCartQuantity;

							/** @var RedshopModelCart $cartModel */
							$cartModel = RedshopModel::getInstance('cart', 'RedshopModel');
							$cartModel->update($data);

							return true;
						}
						else
						{
							return Redshop::getConfig()->get('CART_RESERVATION_MESSAGE') != '' && Redshop::getConfig()->get('IS_PRODUCT_RESERVE') ?
								Redshop::getConfig()->get('CART_RESERVATION_MESSAGE') : urldecode(JText::_('COM_REDSHOP_PRODUCT_OUTOFSTOCK_MESSAGE'));
						}
					}
				}
			}

			// Set product price
			if ($data['product_price'] < 0)
			{
				$data['product_price'] = 0;
			}

			$perProductTotal = $productData->minimum_per_product_total;

			if ($data['product_price'] < $perProductTotal)
			{
				return JText::_('COM_REDSHOP_PER_PRODUCT_TOTAL') . " " . $perProductTotal;
			}

			if (!$sameProduct)
			{
				// SET VALUES INTO SESSION CART
				$cart[$idx]['giftcard_id']                = '';
				$cart[$idx]['product_id']                 = $data['product_id'];
				$cart[$idx]['discount_calc_output']       = $calculateOutput;
				$cart[$idx]['discount_calc']              = $calculateOutputList;
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
				$cart[$idx]['quantity'] = RedshopHelperStockroom::checkQuantityInStock($cart[$idx], $newQuantity);

				if ($newQuantity > $cart[$idx]['quantity'])
				{
					$cart['notice_message'] = $cart[$idx]['quantity'] . " " . JTEXT::_('COM_REDSHOP_AVAILABLE_STOCK_MESSAGE');
				}
				else
				{
					$cart['notice_message'] = "";
				}

				if ($cart[$idx]['quantity'] <= 0)
				{
					return (Redshop::getConfig()->get('CART_RESERVATION_MESSAGE') != '' && Redshop::getConfig()->get('IS_PRODUCT_RESERVE')) ?
						Redshop::getConfig()->get('CART_RESERVATION_MESSAGE') : JText::_('COM_REDSHOP_PRODUCT_OUTOFSTOCK_MESSAGE');
				}

				$cart[$idx]['category_id']   = $data['category_id'];
				$cart[$idx]['wrapper_id']    = $data['sel_wrapper_id'];
				$cart[$idx]['wrapper_price'] = $wrapperPrice + $wrapperVAT;

				/**
				 * Implement new plugin support before session update
				 * trigger the event of redSHOP product plugin support on Before cart session is set - on prepare cart session
				 */
				$dispatcher->trigger('onBeforeSetCartSession', array(&$cart, $data, $idx));

				$cart['idx'] = $idx + 1;

				for ($i = 0, $in = count($fields); $i < $in; $i++)
				{
					$fieldName    = $fields[$i]->name;
					$fieldText    = (isset($data[$fieldName])) ? $data[$fieldName] : '';
					$tmpFieldText = strpbrk($fieldText, '`');

					if ($tmpFieldText)
					{
						$fieldText = str_replace('`', ',', $fieldText);
					}

					$cart[$idx][$fieldName] = $fieldText;
				}
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
			$cart['user_shopper_group_id'] = RedshopHelperUser::getShopperGroup($user->id);
		}

		$cart['free_shipping'] = 0;

		JFactory::getSession()->set('cart', $cart);

		return true;
	}

	/**
	 * Generate Accessories cart
	 *
	 * @param   array   $data   Data
	 * @param   integer $userId ID user
	 *
	 * @return  mixed              Array of accessories cart if success. False otherwise.
	 *
	 * @since   __DEPLOY_VERSION__
	 */
	public static function generateAccessoriesCart($data = array(), $userId = 0)
	{
		if (empty($data) || empty($data['accessory_data']))
		{
			return array();
		}

		$accessoriesCart     = array();
		$accessoryData       = explode("@@", $data['accessory_data']);
		$accessoriesQuantity = array();

		if (isset($data['acc_quantity_data']))
		{
			$accessoriesQuantity = explode("@@", $data['acc_quantity_data']);
		}

		for ($i = 0, $in = count($accessoryData); $i < $in; $i++)
		{
			$productAccessories = RedshopHelperAccessory::getProductAccessories($accessoryData[$i]);
			$accessoryPrices    = productHelper::getInstance()->getAccessoryPrice(
				$data['product_id'], $productAccessories[0]->newaccessory_price, $productAccessories[0]->accessory_main_price, 1, $userId
			);
			$accessoryPrice     = $accessoryPrices[0];
			$accessoryQuantity  = (isset($accessoriesQuantity[$i]) && $accessoriesQuantity[$i]) ? $accessoriesQuantity[$i] : $data['quantity'];

			$accessoriesCart[$i]['accessory_id']       = $accessoryData[$i];
			$accessoriesCart[$i]['accessory_name']     = $productAccessories[0]->product_name;
			$accessoriesCart[$i]['accessory_oprand']   = $productAccessories[0]->oprand;
			$accessoriesCart[$i]['accessory_price']    = $accessoryPrice * $accessoryQuantity;
			$accessoriesCart[$i]['accessory_quantity'] = $accessoryQuantity;

			$accessoryAttributesCart = array();

			if (!empty($data['acc_attribute_data']))
			{
				$accessoryAttributeData = explode('@@', $data['acc_attribute_data']);

				if (!empty($accessoryAttributeData[$i]))
				{
					$accessoryAttributeData = explode('##', $accessoryAttributeData[$i]);

					for ($ia = 0; $ia < count($accessoryAttributeData); $ia++)
					{
						$accessoryPropertyCart = array();
						$attribute             = RedshopHelperProduct_Attribute::getProductAttribute(0, 0, $accessoryAttributeData[$ia]);

						$accessoryAttributesCart[$ia]['attribute_id']   = $accessoryAttributeData[$ia];
						$accessoryAttributesCart[$ia]['attribute_name'] = $attribute[0]->text;

						if (!empty($attribute[0]->text) && !empty($data['acc_property_data']))
						{
							$accessoryPropertyData = explode('@@', $data['acc_property_data']);
							$accessoryPropertyData = explode('##', $accessoryPropertyData[$i]);

							if (isset($accessoryPropertyData[$ia]) && !empty($accessoryPropertyData[$ia]))
							{
								$accessoryPropertyData = explode(',,', $accessoryPropertyData[$ia]);

								for ($ip = 0; $ip < count($accessoryPropertyData); $ip++)
								{
									$accessorySubPropertiesCart = array();
									$property                   = RedshopHelperProduct_Attribute::getAttributeProperties($accessoryPropertyData[$ip]);
									$accessorySubPropertyPrices = RedshopHelperProduct_Attribute::getPropertyPrice(
										$accessoryPropertyData[$ip], $data['quantity'], 'property', $userId
									);

									if ($accessorySubPropertyPrices)
									{
										$propertyPrice = $accessorySubPropertyPrices->product_price;
									}
									else
									{
										$propertyPrice = $property[0]->property_price;
									}

									$accessoryPropertyCart[$ip]['property_id']     = $accessoryPropertyData[$ip];
									$accessoryPropertyCart[$ip]['property_name']   = $property[0]->text;
									$accessoryPropertyCart[$ip]['property_oprand'] = $property[0]->oprand;
									$accessoryPropertyCart[$ip]['property_price']  = $propertyPrice;

									if (!empty($data['acc_subproperty_data']))
									{
										$accessorySubPropertiesData = explode('@@', $data['acc_subproperty_data']);
										$accessorySubPropertiesData = explode('##', $accessorySubPropertiesData[$i]);
										$accessorySubPropertiesData = explode(',,', $accessorySubPropertiesData[$ia]);


										if (!empty($accessorySubPropertiesData[$ip]))
										{
											$accessorySubPropertiesData = explode('::', $accessorySubPropertiesData[$ip]);

											foreach ($accessorySubPropertiesData as $isp => $accSubPropertyData)
											{
												$subProperties = RedshopHelperProduct_Attribute::getAttributeSubProperties($accSubPropertyData);

												$accessorySubPropertyPrices = RedshopHelperProduct_Attribute::getPropertyPrice(
													$accSubPropertyData, $data['quantity'], 'subproperty', $userId
												);

												if (!empty($accessorySubPropertyPrices))
												{
													$subPropertyPrice = $accessorySubPropertyPrices->product_price;
												}
												else
												{
													$subPropertyPrice = $subProperties[0]->subattribute_color_price;
												}

												$accessorySubPropertiesCart[$isp]['subproperty_id']     = $accSubPropertyData;
												$accessorySubPropertiesCart[$isp]['subproperty_name']   = $subProperties[0]->text;
												$accessorySubPropertiesCart[$isp]['subproperty_oprand'] = $subProperties[0]->oprand;
												$accessorySubPropertiesCart[$isp]['subproperty_price']  = $subPropertyPrice;
											}
										}
									}

									$accessoryPropertyCart[$ip]['property_childs'] = $accessorySubPropertiesCart;
								}
							}
						}

						$accessoryAttributesCart[$ia]['attribute_childs'] = $accessoryPropertyCart;
					}
				}
			}
			else
			{
				$attributeSetId         = rsCarthelper::getInstance()->getAttributeSetId($productAccessories[0]->child_product_id);
				$attributesAccessorySet = array();

				if ($attributeSetId > 0)
				{
					$attributesAccessorySet = rsCarthelper::getInstance()->getProductAccAttribute(
						$productAccessories[0]->child_product_id, $attributeSetId, 0, 0, 1
					);
				}

				$requiredAttributes = RedshopHelperProduct_Attribute::getProductAttribute($productAccessories[0]->child_product_id, 0, 0, 0, 1);
				$requiredAttributes = array_merge($requiredAttributes, $attributesAccessorySet);

				if (count($requiredAttributes) > 0)
				{
					$requiredAttributesList = array();

					foreach ($requiredAttributes as $index => $requiredAttribute)
					{
						$requiredAttributesList[$index] = urlencode($requiredAttribute->attribute_name);
					}

					$requiredAttributeName = implode(", ", $requiredAttributesList);

					// Throw an error as first attribute is required
					$msg = urldecode($requiredAttributeName) . " " . JText::_('IS_REQUIRED');
					JFactory::getApplication()->enqueueMessage($msg);

					return false;
				}
			}

			$accessoriesCart[$i]['accessory_childs'] = $accessoryAttributesCart;
		}

		return $accessoriesCart;
	}

	/**
	 * APPLY_VAT_ON_DISCOUNT = When the discount is a "fixed amount" the final price may vary, depending on if the discount affects "the price+VAT"
	 * or just "the price". This CONSTANT will define if the discounts needs to be applied BEFORE or AFTER the VAT is applied to the product price.
	 *
	 * @param   array   $cart     Cart data
	 * @param   integer $shipping Is shipping calculate
	 * @param   integer $userId   ID of user.
	 *
	 * @return  array               Array of calculated cart
	 *
	 * @since   __DEPLOY_VERSION__
	 */
	public static function calculation($cart, $shipping = 0, $userId = 0)
	{
		$cartIdx            = $cart['idx'];
		$vat                = 0;
		$subtotal           = 0;
		$subTotalExcludeVAT = 0;
		$shipping           = 0;
		$usersInfoId        = 0;
		$totalDiscount      = 0;
		$discountVAT        = 0;
		$results            = array();

		for ($i = 0; $i < $cartIdx; $i++)
		{
			$quantity           = $cart[$i]['quantity'];
			$subtotal           += $quantity * $cart[$i]['product_price'];
			$subTotalExcludeVAT += $quantity * $cart[$i]['product_price_excl_vat'];
			$vat                += $quantity * $cart[$i]['product_vat'];
		}

		$tmpData             = array();
		$tmpData['subtotal'] = $subtotal;

		$tmpData['tax'] = $vat;
		$shippingVat    = 0;

		// If SHOW_SHIPPING_IN_CART set to no, make shipping Zero
		if (Redshop::getConfig()->get('SHOW_SHIPPING_IN_CART') && Redshop::getConfig()->get('SHIPPING_METHOD_ENABLE'))
		{
			if (!$userId)
			{
				$user              = JFactory::getUser();
				$userId            = $user->id;
				$shippingAddresses = RedshopHelperOrder::getShippingAddress($userId);

				if (!empty($shippingAddresses[0]))
				{
					$usersInfoId = $shippingAddresses[0]->users_info_id;
				}
			}

			$noOfGiftCard = 0;

			for ($i = 0; $i < $cartIdx; $i++)
			{
				if (isset($cart[$i]['giftcard_id']) === true)
				{
					if (!is_null($cart[$i]['giftcard_id']) && $cart[$i]['giftcard_id'] != 0)
					{
						$noOfGiftCard++;
					}
				}
			}

			if ($noOfGiftCard == $cartIdx)
			{
				$cart['free_shipping'] = 1;
			}
			elseif (!isset($cart['free_shipping']) || $cart['free_shipping'] != 1)
			{
				$cart['free_shipping'] = 0;
			}

			if (isset($cart ['free_shipping']) && $cart ['free_shipping'] > 0)
			{
				$shipping = 0;
			}
			else
			{
				if (!isset($cart['voucher_discount']))
				{
					$cart['coupon_discount'] = 0;
				}

				$totalDiscount       = $cart['cart_discount'] + (isset($cart['voucher_discount']) ? $cart['voucher_discount'] : 0) + $cart['coupon_discount'];
				$tmpData['order_subtotal'] = (Redshop::getConfig()->get('SHIPPING_AFTER') == 'total') ? $subtotal - $totalDiscount : $subtotal;
				$tmpData['users_info_id']  = $usersInfoId;
				$shippingArr         = RedshopHelperShipping::getDefaultShipping($tmpData);
				$shipping            = $shippingArr['shipping_rate'];
				$shippingVat         = $shippingArr['shipping_vat'];
			}
		}

		$view = JFactory::getApplication()->input->get('view');

		if (key_exists('shipping', $cart) && $view != 'cart')
		{
			$shipping = $cart['shipping'];

			if (!isset($cart['shipping_vat']))
			{
				$cart['shipping_vat'] = 0;
			}

			$shippingVat = $cart['shipping_vat'];
		}

		$chkTag = self::taxExemptAddToCart();

		if ((float) Redshop::getConfig()->get('VAT_RATE_AFTER_DISCOUNT') && !Redshop::getConfig()->get('APPLY_VAT_ON_DISCOUNT') && !empty($chkTag))
		{
			if (isset($cart['discount_tax']) && !empty($cart['discount_tax']))
			{
				$discountVAT = $cart['discount_tax'];
				$subtotal    = $subtotal - $cart['discount_tax'];
			}
			else
			{
				$vatData = productHelper::getInstance()->getVatRates();

				if (isset($vatData->tax_rate) && !empty($vatData->tax_rate))
				{
					$discountVAT = 0;

					if ((int) $subTotalExcludeVAT > 0)
					{
						$avgVAT      = (($subTotalExcludeVAT + $vat) / $subTotalExcludeVAT) - 1;
						$discountVAT = ($avgVAT * $totalDiscount) / (1 + $avgVAT);
					}
				}
			}

			$vat = $vat - $discountVAT;
		}

		$total     = $subtotal + $shipping;
		$results[] = $total;
		$results[] = $subtotal;
		$results[] = $subTotalExcludeVAT;
		$results[] = $shipping;

		if (isset($cart['discount']) === false)
		{
			$cart['discount'] = 0;
		}

		$results[] = $cart['discount'];
		$results[] = $vat;
		$results[] = $shippingVat;

		return $results;
	}
}
