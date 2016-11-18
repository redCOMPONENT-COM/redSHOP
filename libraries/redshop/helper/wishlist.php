<?php
/**
 * @package     RedSHOP.Library
 * @subpackage  Helper
 *
 * @copyright   Copyright (C) 2008 - 2016 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

defined('_JEXEC') or die;

/**
 * Wishlist functions for redSHOP
 *
 * @since  __DEPLOY_VERSION__
 */
class RedshopHelperWishlist
{
	/**
	 * List of Wishlist.
	 *
	 * @var    array
	 *
	 * @since  __DEPLOY_VERSION__
	 */
	protected static $wishLists = array();

	/**
	 * Method for replace wishlist tag in template.
	 *
	 * @param   int     $productId        Product ID
	 * @param   string  $templateContent  HTML data of template content
	 *
	 * @return  string                    HTML data of replaced content.
	 *
	 * @since   __DEPLOY_VERSION__
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
	 * @return  bool|mixed        Data if success. False otherwise.
	 *
	 * @since   __DEPLOY_VERSION__
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

			$wishlist = $db->setQuery($query)->loadObject();

			$query->clear()
				->select($db->qn('wpi.attribute_id'))
				->select($db->qn('wpi.property_id'))
				->select($db->qn('wpi.subattribute_id'))
				->from($db->qn('#__redshop_wishlist_product_item', 'wpi'))
				->leftJoin($db->qn('#__redshop_wishlist_product', 'wp') . ' ON ' . $db->qn('wp.wishlist_product_id') . ' = ' . $db->qn('wpi.ref_id'))
				->where($db->qn('wp.wishlist_id') . ' = ' . $wishlistId);

			$wishlist->product_items = $db->setQuery($query)->loadObjectList();

			static::$wishLists[$wishlistId] = $wishlist;
		}

		return static::$wishLists[$wishlistId];
	}
}
