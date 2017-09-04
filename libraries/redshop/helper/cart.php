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
	 * Method for remove cart from Database
	 *
	 * @param   int  $cartId  ID of cart.
	 * @param   int  $userId  Id of user.
	 * @param   bool $delCart Delete cart.
	 *
	 * @return  boolean
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

				$db->setQuery($query)->execute();

				// Delete attribute
				$query = $db->getQuery(true)
					->delete($db->qn('#__redshop_usercart_attribute_item'))
					->where($db->qn('cart_item_id') . ' IN (' . implode(',', $cartItemIds) . ')');

				$db->setQuery($query)->execute();
			}

			// Delete cart item
			$query = $db->getQuery(true)
				->delete($db->qn('#__redshop_usercart_item'))
				->where($db->qn('cart_id') . ' = ' . (int) $cartId);

			$db->setQuery($query)->execute();

			if ($delCart)
			{
				$query = $db->getQuery(true)
					->delete($db->qn('#__redshop_usercart'))
					->where($db->qn('cart_id') . ' = ' . (int) $cartId);

				$db->setQuery($query)->execute();
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

		JPluginHelper::importPlugin('redshop_product');
		RedshopHelperUtility::getDispatcher()->trigger('onAddCartToDatabase', array(&$cart));

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

				/* Store attribute in db */
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
		if (!$userId)
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
					array(
						'ci.cart_item_id', 'ci.cart_idx', 'ci.product_id', 'ci.product_quantity',
						'ci.product_wrapper_id', 'ci.product_subscription_id', 'ci.giftcard_id', 'ci.attribs')
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

				if ($productData->published == 0)
				{
					continue;
				}

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
					$cart[$idx]['quantity']                   = $cartHelper->checkQuantityInStock($cart[$idx], $quantity);
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

		$cartOutput['cart_output']    = $carts[0];
		$cartOutput['total_quantity'] = $carts[1];
		$text                         = RedshopHelperShipping::getFreeShippingRate();

		if (Redshop::getConfig()->get('AJAX_CART_BOX') == 1 && $ajax == 1)
		{
			echo "`" . $carts[0] . "`" . $text;
			JFactory::getApplication()->close();
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
	 * Empty and delete current cart
	 *
	 * @return   boolean
	 *
	 * @since    2.0.6
	 */
	public static function emptyCart()
	{
		$cart = RedshopHelperCartSession::getCart();
		unset($cart);

		setcookie("redSHOPcart", "", time() - 3600, "/");

		$cart['idx'] = 0;
		RedshopHelperCartSession::setCart($cart);

		return RedshopHelperStockroom::deleteCartAfterEmpty();
	}
}
