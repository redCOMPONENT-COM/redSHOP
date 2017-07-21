<?php
/**
 * @package     RedSHOP.Library
 * @subpackage  Helper
 *
 * @copyright   Copyright (C) 2008 - 2017 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

defined('_JEXEC') or die;

/**
 * Wishlist functions for redSHOP
 *
 * @since  2.0.3
 */
class RedshopHelperWishlist
{
	/**
	 * List of Wishlist.
	 *
	 * @var    array
	 *
	 * @since  2.0.3
	 */
	protected static $wishLists = array();

	/**
	 * List of Wishlist.
	 *
	 * @var    array
	 *
	 * @since  2.0.3
	 */
	protected static $usersWishlist = array();

	/**
	 * Method for replace wishlist tag in template.
	 *
	 * @param   int     $productId        Product ID
	 * @param   string  $templateContent  HTML data of template content
	 * @param   string  $formId           DOM ID of add to cart form.
	 *
	 * @return  string                    HTML data of replaced content.
	 *
	 * @since   2.0.3
	 */
	public static function replaceWishlistTag($productId = 0, $templateContent = '', $formId = '')
	{
		if (Redshop::getConfig()->get('MY_WISHLIST') == 0)
		{
			$templateContent = str_replace('{wishlist_button}', '', $templateContent);
			$templateContent = str_replace('{wishlist_link}', '', $templateContent);
			$templateContent = str_replace('{property_wishlist_link}', '', $templateContent);

			return $templateContent;
		}

		return RedshopTagsReplacer::_('wishlist', $templateContent, array('productId' => $productId, 'formId' => $formId));
	}

	/**
	 * Method for get product items of specific Wishlist Product.
	 *
	 * @param   int  $wishlistId  ID of Wishlist ID.
	 *
	 * @return  mixed             Data if success. False otherwise.
	 *
	 * @since   2.0.3
	 */
	public static function getWishlist($wishlistId = 0)
	{
		$wishlistId = (int) $wishlistId;

		if (!$wishlistId)
		{
			return false;
		}

		if (!array_key_exists($wishlistId, static::$wishLists))
		{
			$db = JFactory::getDbo();

			$query = $db->getQuery(true)
				->select('*')
				->from($db->qn('#__redshop_wishlist'))
				->where($db->qn('wishlist_id') . ' = ' . $wishlistId);

			$wishlist           = $db->setQuery($query)->loadObject();
			$wishlist->products = array();

			$query = $db->getQuery(true)
				->select('*')
				->from($db->qn('#__redshop_wishlist_product'))
				->where($db->qn('wishlist_id') . ' = ' . $wishlistId);

			$wishlistProducts = $db->setQuery($query)->loadObjectList();

			if (empty($wishlistProducts) || !Redshop::getConfig()->get('INDIVIDUAL_ADD_TO_CART_ENABLE'))
			{
				static::$wishLists[$wishlistId] = $wishlist;

				return static::$wishLists[$wishlistId];
			}

			$query->clear()
				->select($db->qn('wpi.ref_id'))
				->select($db->qn('wpi.attribute_id'))
				->select($db->qn('wpi.property_id'))
				->select($db->qn('wpi.subattribute_id'))
				->from($db->qn('#__redshop_wishlist_product_item', 'wpi'))
				->leftJoin($db->qn('#__redshop_wishlist_product', 'wp') . ' ON ' . $db->qn('wp.wishlist_product_id') . ' = ' . $db->qn('wpi.ref_id'))
				->where($db->qn('wp.wishlist_id') . ' = ' . $wishlistId);
			$wishlistProductItems = $db->setQuery($query)->loadObjectList();

			foreach ($wishlistProducts as $wishlistProduct)
			{
				if (!array_key_exists($wishlistProduct->product_id, $wishlist->products))
				{
					$wishlist->products[$wishlistProduct->product_id] = array();
				}

				$wishlistProduct->product_items = array();
				$wishlistProduct->attributes = array();
				$wishlistProduct->properties = array();
				$wishlistProduct->subAttributes = array();

				foreach ($wishlistProductItems as $key => $wishlistProductItem)
				{
					if ($wishlistProductItem->ref_id == $wishlistProduct->wishlist_product_id)
					{
						$wishlistProduct->product_items[$wishlistProductItem->attribute_id] = $wishlistProductItem;

						unset($wishlistProductItems[$key]);
					}
				}

				foreach ($wishlistProduct->product_items as $productItem)
				{
					$wishlistProduct->attributes[]    = $productItem->attribute_id;
					$wishlistProduct->properties[]    = $productItem->property_id;
					$wishlistProduct->subAttributes[] = $productItem->subattribute_id;
				}

				$wishlistProduct->attributes    = array_filter($wishlistProduct->attributes);
				$wishlistProduct->properties    = array_filter($wishlistProduct->properties);
				$wishlistProduct->subAttributes = array_filter($wishlistProduct->subAttributes);

				$wishlist->products[$wishlistProduct->product_id][] = $wishlistProduct;
			}

			static::$wishLists[$wishlistId] = $wishlist;
		}

		return static::$wishLists[$wishlistId];
	}

	/**
	 * Method for get Wishlist data of specific user.
	 *
	 * @param   int  $userId  ID of user.
	 *
	 * @return  mixed
	 *
	 * @since   2.0.3
	 */
	public static function getUserWishlist($userId = 0)
	{
		$userId = (int) $userId;

		if (!$userId)
		{
			$userId = JFactory::getUser()->id;
		}

		if (!$userId)
		{
			return false;
		}

		if (!array_key_exists($userId, static::$usersWishlist))
		{
			$db = JFactory::getDbo();

			$query = $db->getQuery(true)
				->select('wishlist_id')
				->from($db->qn('#__redshop_wishlist'))
				->where($db->qn('user_id') . ' = ' . $userId);

			$wishList = $db->setQuery($query)->loadObjectList();

			if (empty($wishList))
			{
				$wishList = self::createWishlistDefault($userId);
			}

			if (empty($wishList))
			{
				static::$usersWishlist[$userId] = array();

				return static::$usersWishlist[$userId];
			}

			foreach ($wishList as $wish)
			{
				static::$usersWishlist[$userId][$wish->wishlist_id] = self::getWishlist($wish->wishlist_id);
			}
		}

		return static::$usersWishlist[$userId];
	}

	/**
	 * Method for check product exist in wishlist.
	 *
	 * @param   int  $productId  ID of Product.
	 *
	 * @return  mixed        Data if success. False otherwise.
	 *
	 * @since   2.0.3
	 */
	public static function checkWishlistExist($productId = 0)
	{
		$user = JFactory::getUser();
		$db = JFactory::getDbo();
		$query = $db->getQuery(true)
			->select('COUNT(*)')
			->from($db->qn('#__redshop_wishlist', 'w'))
			->leftjoin($db->qn('#__redshop_wishlist_product', 'wp') . ' ON ' . $db->qn('w.wishlist_id') . ' = ' . $db->qn('wp.wishlist_id'))
			->where($db->qn('wp.product_id') . ' = ' . $db->q((int) $productId))
			->where($db->qn('w.user_id') . ' = ' . $db->q((int) $user->id));

		return $db->setQuery($query)->loadResult();
	}

	/**
	 * Method for get Wishlist module base in element name
	 *
	 * @param   string  $elementName  Element name
	 *
	 * @return  object|null
	 *
	 * @since   2.0.6
	 */
	public static function getWishlistModule($elementName)
	{
		if (empty($elementName))
		{
			return null;
		}

		$db = JFactory::getDbo();

		$query = $db->getQuery(true)
			->select('*')
			->from($db->qn('#__extensions'))
			->where($db->qn('element') . ' = ' . $db->quote($elementName));

		return $db->setQuery($query)->loadObject();
	}

	/**
	 * Method for get Wishlist user field data
	 *
	 * @param   integer  $wishlistId  Wish list id
	 * @param   integer  $productId   Product Id
	 *
	 * @return  array
	 *
	 * @since   2.0.6
	 */
	public static function getUserFieldData($wishlistId, $productId)
	{
		if (empty($wishlistId) || empty($productId))
		{
			return array();
		}

		$db = JFactory::getDbo();

		$query = $db->getQuery(true)
			->select('*')
			->from($db->qn('#__redshop_wishlist_userfielddata'))
			->where($db->qn('wishlist_id') . ' = ' . $db->quote($wishlistId))
			->where($db->qn('product_id') . ' = ' . (int) $productId)
			->order($db->qn('fieldid') . ' ASC');

		return $db->setQuery($query)->loadObjectList();
	}

	/**
	 * Create Default wishlist list
	 *
	 * @param   int  $userId  ID of user.
	 *
	 * @return  bool
	 */
	private static function createWishlistDefault($userId)
	{
		if (!$userId)
		{
			return false;
		}

		$db = JFactory::getDbo();

		$wishlist                = new stdClass();
		$wishlist->wishlist_name = 'Default';
		$wishlist->user_id       =$userId;
		$wishlist->cdate         =$db->quote(time());

		// Insert the object into the user profile table.
		if($db->insertObject('#__redshop_wishlist', $wishlist))
		{
			$wishlist->wishlist_id = $db->insertid();

			return array($wishlist);
		}

		return array();
	}
}
