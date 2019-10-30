<?php
/**
 * @package     RedSHOP.Library
 * @subpackage  Helper
 *
 * @copyright   Copyright (C) 2008 - 2019 redCOMPONENT.com. All rights reserved.
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
	 * @var array
	 */
	public static $cart = array();

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
	 * @throws  Exception
	 */
	public static function removeCartFromDatabase($cartId = 0, $userId = 0, $delCart = false)
	{
		if (!$userId)
		{
			$user   = JFactory::getUser();
			$userId = (int) $user->id;
		}

		$db = JFactory::getDbo();

		if ($cartId === 0)
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
	 * @param   array  $cart  Cart data.
	 *
	 * @return  boolean       True on success. False otherwise.
	 * @throws  Exception
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

		$cart = empty($cart) ? RedshopHelperCartSession::getCart() : $cart;

		JPluginHelper::importPlugin('redshop_product');
		RedshopHelperUtility::getDispatcher()->trigger('onAddCartToDatabase', array(&$cart));

		$idx = isset($cart['idx']) ? (int) $cart['idx'] : 0;
		$db  = JFactory::getDbo();

		// Get cart ID.

		/** @var RedshopTableUsercart $userCartTable */
		$userCartTable = RedshopTable::getInstance('Usercart', 'RedshopTable');

		if (!$userCartTable->load(array('user_id' => $user->id)))
		{
			$userCartTable->user_id = $user->id;
			$userCartTable->cdate   = time();
			$userCartTable->mdate   = time();

			if (!$userCartTable->store())
			{
				return false;
			}

			$cartId = $userCartTable->cart_id;
		}
		else
		{
			$cartId = $userCartTable->cart_id;
		}

		try
		{
			$db->transactionStart();

			$delCart = (!$idx) ? true : false;

			// Clean current cart.
			if (!self::removeCartFromDatabase($cartId, $user->id, $delCart))
			{
				return false;
			}

			for ($i = 0; $i < $idx; $i++)
			{
				/** @var RedshopTableUsercart_Item $userCartItem */
				$userCartItem = RedshopTable::getInstance('Usercart_Item', 'RedshopTable');

				$userCartItem->cart_idx   = $i;
				$userCartItem->cart_id    = $cartId;
				$userCartItem->product_id = $cart[$i]['product_id'];
				$userCartItem->attribs    = serialize($cart[$i]);

				if (isset($cart[$i]['giftcard_id']) === false)
				{
					$cart[$i]['giftcard_id'] = 0;
				}

				if (isset($cart[$i]['wrapper_id']) === false)
				{
					$cart[$i]['wrapper_id'] = 0;
				}

				$userCartItem->giftcard_id        = $cart[$i]['giftcard_id'];
				$userCartItem->product_quantity   = $cart[$i]['quantity'];
				$userCartItem->product_wrapper_id = $cart[$i]['wrapper_id'];

				if (isset($cart[$i]['subscription_id']) === false)
				{
					$cart[$i]['subscription_id'] = 0;
				}

				$userCartItem->product_subscription_id = $cart[$i]['subscription_id'];

				if (!$userCartItem->store())
				{
					throw new Exception(/** @scrutinizer ignore-deprecated */ $userCartItem->getError());
				}

				$cartItemId     = $userCartItem->cart_item_id;
				$cartAttributes = array();

				if (isset($cart[$i]['cart_attribute']))
				{
					$cartAttributes = $cart[$i]['cart_attribute'];
				}

				// Store attribute in db
				self::addCartAttributeToDatabase($cartAttributes, $cartItemId, $userCartItem->product_id);

				$cartAccessories = !empty($cart[$i]['cart_accessory']) ? (array) $cart[$i]['cart_accessory'] : array();

				foreach ($cartAccessories as $cartAccessory)
				{
					/** @var RedshopTableUsercart_Accessory_Item $userCartItemAccessory */
					$userCartItemAccessory               = RedshopTable::getInstance('Usercart_Accessory_Item', 'RedshopTable');
					$userCartItemAccessory->accessory_id = $cartAccessory['accessory_id'];

					// Store product quantity as accessory quantity.
					$userCartItemAccessory->accessory_quantity = $cart[$i]['quantity'];

					if (!$userCartItemAccessory->store())
					{
						throw new Exception(/** @scrutinizer ignore-deprecated */ $userCartItemAccessory->getError());
					}

					self::addCartAttributeToDatabase(
						$cartAccessory['accessory_childs'], $cartItemId, $userCartItemAccessory->accessory_id, true
					);
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
	 * @param   array    $attributes   Cart attribute data.
	 * @param   integer  $cartItemId   Cart item ID
	 * @param   integer  $productId    Cart product ID.
	 * @param   boolean  $isAccessory  Is this accessory?
	 *
	 * @return  boolean                True on success. False otherwise.
	 *
	 * @since   2.0.3
	 * @throws  Exception
	 */
	public static function addCartAttributeToDatabase($attributes = array(), $cartItemId = 0, $productId = 0, $isAccessory = false)
	{
		if (!$cartItemId)
		{
			return false;
		}

		$db = JFactory::getDbo();

		try
		{
			$db->transactionStart();

			foreach ($attributes as $attribute)
			{
				/** @var RedshopTableUsercart_Attribute_Item $table */
				$table = RedshopTable::getInstance('Usercart_Attribute_Item', 'RedshopTable');

				$table->cart_item_id      = $cartItemId;
				$table->section_id        = $attribute['attribute_id'];
				$table->section           = 'attribute';
				$table->parent_section_id = $productId;
				$table->is_accessory_att  = (int) $isAccessory;

				if (!$table->store())
				{
					throw new Exception($table->getError());
				}

				$attributeChildren = (array) $attribute['attribute_childs'];

				foreach ($attributeChildren as $attributeChild)
				{
					/** @var RedshopTableUsercart_Attribute_Item $itemTable */
					$itemTable = RedshopTable::getInstance('Usercart_Attribute_Item', 'RedshopTable');

					$itemTable->cart_item_id      = $cartItemId;
					$itemTable->section_id        = $attributeChild['property_id'];
					$itemTable->section           = 'property';
					$itemTable->parent_section_id = $attribute['attribute_id'];
					$itemTable->is_accessory_att  = (int) $isAccessory;

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
						/** @var RedshopTableUsercart_Attribute_Item $propertyTable */
						$propertyTable = RedshopTable::getInstance('usercart_attribute_item', 'RedshopTable');

						$propertyTable->section_id        = $property['subproperty_id'];
						$propertyTable->section           = 'subproperty';
						$propertyTable->parent_section_id = $attributeChild['property_id'];
						$propertyTable->is_accessory_att  = (int) $isAccessory;

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
	 * @since   2.0.3
	 * @throws  Exception
	 */
	public static function databaseToCart($userId = 0)
	{
		if (!$userId)
		{
			$user   = JFactory::getUser();
			$userId = $user->id;
		}

		JPluginHelper::importPlugin('redshop_product');

		$productHelper = productHelper::getInstance();
		$cartHelper    = rsCarthelper::getInstance();

		if (!array_key_exists($userId, self::$cart))
		{
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

			self::$cart[$userId] = $db->setQuery($query)->loadObjectList();
		}

		$cartItems = self::$cart[$userId];

		if (empty($cartItems))
		{
			return;
		}

		$cart = array();
		$idx  = 0;

		foreach ($cartItems as $cartItem)
		{
			RedshopHelperUtility::getDispatcher()->trigger('onDatabaseToCart', array(&$cartItem));

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
				$giftCardData  = RedshopEntityGiftcard::getInstance($giftCardId)->getItem();

				if (!empty($giftCardData))
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

				if ($productData->published === 0)
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

				if ($productData->product_type === 'subscription')
				{
					$productSubscription = $productHelper->getProductSubscriptionDetail($productId, $cartItem->product_subscription_id);

					if (!empty($productSubscription->subscription_id))
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
						$productOldPrice       = $productOldPrice + $subscriptionPrice + $subscriptionVAT;
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
				$productVatPrice       = $productVatPrice + $accessoryVATPrice;

				// Check if required attribute is filled or not
				if (count($selectedAttributeId) > 0)
				{
					$selectedAttributeId = implode(",", $selectedAttributeId);
				}

				$requiredAttributeData = RedshopHelperProduct_Attribute::getProductAttribute(
					$productId, 0, 0, 0, 1, $selectedAttributeId
				);

				if (count($requiredAttributeData) > 0)
				{
					$requiredAttributes = array();

					foreach ($requiredAttributeData as $requiredAttribute)
					{
						$requiredAttributes = $requiredAttribute->attribute_name;
					}

					$requiredAttributeName = implode(', ', $requiredAttributes);

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
						$dataTxt = isset($attributes[$field->name]) ? $attributes[$field->name] : '';
						$text    = strpbrk($dataTxt, '`');

						if ($text)
						{
							$list = explode('`', $dataTxt);

							if (is_array($list))
							{
								$dataTxt = implode(",", $list);
							}
						}

						$cart[$idx][$field->name] = $dataTxt;
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

					if (!empty(Redshop::getConfig()->getString('CART_RESERVATION_MESSAGE')))
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

		self::cartFinalCalculation();
	}

	/**
	 * Method for generate attribute from cart.
	 *
	 * @param   integer  $cartItemId       ID of cart item.
	 * @param   integer  $isAccessory      Is accessory?
	 * @param   integer  $parentSectionId  ID of parent section
	 * @param   integer  $quantity         Quantity of product.
	 *
	 * @return  array
	 * @throws  Exception
	 *
	 * @since  2.0.3
	 */
	public static function generateAttributeFromCart($cartItemId = 0, $isAccessory = 0, $parentSectionId = 0, $quantity = 1)
	{
		$cartAttributes = (array) rsCarthelper::getInstance()->getCartItemAttributeDetail(
			$cartItemId, $isAccessory, 'attribute', $parentSectionId
		);

		if (empty($cartAttributes))
		{
			return array();
		}

		$generateAttributes = array();

		foreach ($cartAttributes as $i => $cartAttribute)
		{
			$attribute          = RedshopHelperProduct_Attribute::getProductAttribute(0, 0, $cartAttribute->section_id);
			$generateProperties = array();

			$generateAttributes[$i]['attribute_id']   = $cartAttribute->section_id;
			$generateAttributes[$i]['attribute_name'] = $attribute[0]->text;

			$cartProperties = (array) rsCarthelper::getInstance()->getCartItemAttributeDetail(
				$cartItemId, $isAccessory, 'property', $cartAttribute->section_id
			);

			foreach ($cartProperties as $p => $cartProperty)
			{
				$generateSubProperties = array();
				$property              = RedshopHelperProduct_Attribute::getAttributeProperties($cartProperty->section_id);
				$priceList             = RedshopHelperProduct_Attribute::getPropertyPrice($cartProperty->section_id, $quantity, 'property');

				if (!empty($priceList->product_price))
				{
					$propertyPrice = $priceList->product_price;
				}
				else
				{
					$propertyPrice = $property[0]->property_price;
				}

				$generateProperties[$p]['property_id']     = $cartProperty->section_id;
				$generateProperties[$p]['property_name']   = $property[0]->text;
				$generateProperties[$p]['property_oprand'] = $property[0]->oprand;
				$generateProperties[$p]['property_price']  = $propertyPrice;

				$cartSubProperties = (array) rsCarthelper::getInstance()->getCartItemAttributeDetail(
					$cartItemId, $isAccessory, 'subproperty', $cartProperty->section_id
				);

				foreach ($cartSubProperties as $index => $cartSubProperty)
				{
					$subProperty      = RedshopHelperProduct_Attribute::getAttributeSubProperties($cartSubProperty->section_id);
					$price            = RedshopHelperProduct_Attribute::getPropertyPrice($cartSubProperty->section_id, $quantity, 'subproperty');
					$subPropertyPrice = $subProperty[0]->subattribute_color_price;

					if (!empty($price))
					{
						$subPropertyPrice = $price->product_price;
					}

					$generateSubProperties[$index]['subproperty_id']     = $cartSubProperty->section_id;
					$generateSubProperties[$index]['subproperty_name']   = $subProperty[0]->text;
					$generateSubProperties[$index]['subproperty_oprand'] = $subProperty[0]->oprand;
					$generateSubProperties[$index]['subproperty_price']  = $subPropertyPrice;
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
	 * @param   bool  $isModify  Is modify cart?
	 *
	 * @return  array
	 *
	 * @since   2.0.3
	 *
	 * @throws  Exception
	 */
	public static function cartFinalCalculation($isModify = true)
	{
		$ajax = JFactory::getApplication()->input->getInt('ajax_cart_box');
		$cart = RedshopHelperCartSession::getCart();

		if ($isModify === true)
		{
			$cart = rsCarthelper::getInstance()->modifyDiscount($cart);
		}

		$cartOutput = array();
		$carts      = self::generateCartOutput($cart);

		$cartOutput['cart_output']    = $carts[0];
		$cartOutput['total_quantity'] = $carts[1];
		$text                         = Redshop\Shipping\Rate::getFreeShippingRate();

		if ($ajax === 1 &&Redshop::getConfig()->getBool('AJAX_CART_BOX'))
		{
			echo '`' . $carts[0] . '`' . $text;
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
		$cartParams       = \Redshop\Cart\Module::getParams();
		$html             = (string) $cartParams->get('cart_output', 'simple');
		$showShippingLine = (int) $cartParams->get('show_shipping_line', 0);
		$showWithVAT      = (int) $cartParams->get('show_with_vat', 0);

		for ($i = 0; $i < $idx; $i++)
		{
			$totalQuantity += $cart[$i]['quantity'];
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
	 * @param   integer  $userId                 User Information Id - Login user id
	 * @param   boolean  $isShowButtonAddToCart  Display Add to cart button for tax exemption user
	 *
	 * @return  boolean                          True if VAT applied else false
	 *
	 * @since   2.0.6
	 */
	public static function taxExemptAddToCart($userId = 0, $isShowButtonAddToCart = false)
	{
		$userId = !$userId ? JFactory::getUser()->id : $userId;

		if (!$userId)
		{
			return true;
		}

		$userInformation = RedshopHelperUser::getUserInformation($userId);

		if (empty($userInformation->user_id))
		{
			return true;
		}

		if ($userInformation->requesting_tax_exempt === 0)
		{
			return true;
		}

		if ($userInformation->requesting_tax_exempt === 1 && $userInformation->tax_exempt_approved === 0)
		{
			if ($isShowButtonAddToCart)
			{
				return false;
			}

			return true;
		}

		if ($userInformation->requesting_tax_exempt === 1 && $userInformation->tax_exempt_approved === 1)
		{
			if ($isShowButtonAddToCart)
			{
				return true;
			}

			return false;
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

		setcookie('redSHOPcart', '', time() - 3600, '/');

		$cart['idx'] = 0;
		RedshopHelperCartSession::setCart($cart);

		return RedshopHelperStockroom::deleteCartAfterEmpty();
	}
}
