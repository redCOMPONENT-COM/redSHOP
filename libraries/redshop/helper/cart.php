<?php
/**
 * @package     RedSHOP.Library
 * @subpackage  Helper
 *
 * @copyright   Copyright (C) 2008 - 2016 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

defined('_JEXEC') or die;

use Joomla\Utilities\ArrayHelper;

/**
 * Class Redshop Helper Cart
 *
 * @since  __DEPLOY_VERSION__
 */
abstract class RedshopHelperCart
{
	/**
	 * Method for remove cart from Database
	 *
	 * @param   int   $cartId   ID of cart.
	 * @param   int   $userId   Id of user.
	 * @param   bool  $delCart  Delete cart.
	 *
	 * @return  bool
	 *
	 * @since   __DEPLOY_VERSION__
	 */
	public static function removeCartFromDatabase($cartId = 0, $userId = 0, $delCart = false)
	{
		if (!$userId)
		{
			$user = JFactory::getUser();
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
	 * Store Cart to Database
	 *
	 * @param   array  $cart  Cart data.
	 *
	 * @return  boolean       True on success. False otherwise.
	 *
	 * @since   __DEPLOY_VERSION__
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

				$cart_item_id = $rowItem->cart_item_id;

				$cart_attribute = array();

				if (isset($cart[$i]['cart_attribute']))
				{
					$cart_attribute = $cart[$i]['cart_attribute'];
				}

				/* store attribute in db */
				self::addCartAttributeToDatabase($cart_attribute, $cart_item_id, $rowItem->product_id);

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
					self::addCartAttributeToDatabase($accessory_childs, $cart_item_id, $rowAcc->accessory_id, true);
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
	 * @param   int      $cartItemId   Cart item ID
	 * @param   int      $productId    Cart product ID.
	 * @param   boolean  $isAccessory  Is this accessory?
	 *
	 * @return  boolean       True on success. False otherwise.
	 *
	 * @since   __DEPLOY_VERSION__
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
}
