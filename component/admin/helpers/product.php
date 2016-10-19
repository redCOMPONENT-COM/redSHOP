<?php
/**
 * @package     RedSHOP.Backend
 * @subpackage  Helper
 *
 * @copyright   Copyright (C) 2008 - 2016 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

defined('_JEXEC') or die;

/**
 * Redshop helper product
 *
 * @since  2.0.0.2
 */
class RedshopAdminProduct
{
	protected static $instance = null;

	/**
	 * Returns the adminProductHelper object, only creating it
	 * if it doesn't already exist.
	 *
	 * @return  adminProductHelper  The adminProductHelper object
	 *
	 * @since   1.6
	 */
	public static function getInstance()
	{
		if (self::$instance === null)
		{
			self::$instance = new static;
		}

		return self::$instance;
	}

	/**
	 * Replace Accessory Data
	 *
	 * @param   int     $productId  Product id
	 * @param   array   $accessory  Accessory list
	 * @param   int     $userId     User id
	 * @param   string  $uniqueId   Unique id
	 *
	 * @return mixed
	 *
	 * @deprecated  __DEPLOY_VERSION__  Use RedshopHelperProduct::replaceAccessoryData($productId, $accessory, $userId, $uniqueId) instead
	 */
	public function replaceAccessoryData($productId = 0, $accessory = array(), $userId = 0, $uniqueId = "")
	{
		RedshopHelperProduct::replaceAccessoryData($productId, $accessory, $userId, $uniqueId);
	}

	/**
	 * Replace Attribute Data
	 *
	 * @param   int     $productId    Product id
	 * @param   int     $accessoryId  Accessory id
	 * @param   array   $attributes   Attribute list
	 * @param   int     $userId       User id
	 * @param   string  $uniqueId     Unique id
	 *
	 * @return mixed
	 *
	 * @deprecated  __DEPLOY_VERSION__  Use RedshopHelperProduct::replaceAttributeData($productId, $accessoryId, $attributes, $userId, $uniqueId) instead
	 */
	function replaceAttributeData($productId = 0, $accessoryId = 0, $attributes = array(), $userId, $uniqueId = "")
	{
		RedshopHelperProduct::replaceAttributeData($productId, $accessoryId, $attributes, $userId, $uniqueId);
	}

	/**
	 * Replace Accessory Data
	 *
	 * @param   int     $productId  Product id
	 * @param   int     $userId     User id
	 * @param   string  $uniqueId   Unique id
	 *
	 * @return mixed
	 *
	 * @deprecated  __DEPLOY_VERSION__  Use RedshopHelperProduct::replaceWrapperData($productId, $userId, $uniqueId) instead
	 */
	public function replaceWrapperData($productId = 0, $userId, $uniqueId = "")
	{
	{
		RedshopHelperProduct::replaceWrapperData($productId, $userId, $uniqueId);
	}

	/**
	 * Get product item info
	 *
	 * @param   int     $productId        Product id
	 * @param   int     $quantity         Product quantity
	 * @param   string  $uniqueId         Unique id
	 * @param   int     $userId           User id
	 * @param   int     $newProductPrice  New product price
	 *
	 * @return mixed
	 *
	 * @deprecated  __DEPLOY_VERSION__  Use RedshopHelperProduct::getProductItemInfo($productId, $quantity, $uniqueId, $userId, $newProductPrice) instead
	 */
	public function getProductItemInfo($productId = 0, $quantity = 1, $uniqueId = "", $userId = 0, $newProductPrice = 0)
	{
		RedshopHelperProduct::getProductItemInfo($productId, $quantity, $uniqueId, $userId, $newProductPrice);
	}

	/**
	 * Replace Shipping method
	 *
	 * @param   array  $d                 Data
	 * @param   int    $shippUsersInfoId  Shipping User info id
	 * @param   int    $shippingRateId    Shipping rate id
	 *
	 * @return mixed
	 *
	 * @deprecated  __DEPLOY_VERSION__  Use RedshopHelperProduct::replaceShippingMethod($d, $shippUsersInfoId, $shippingRateId) instead
	 */
	public function replaceShippingMethod($d = array(), $shippUsersInfoId = 0, $shippingRateId = 0)
	{
		RedshopHelperProduct::replaceShippingMethod($d, $shippUsersInfoId, $shippingRateId);
	}

	/**
	 * Redesign product item
	 *
	 * @param   array  $post  Data
	 *
	 * @return array
	 *
	 * @deprecated  __DEPLOY_VERSION__  Use RedshopHelperProduct::redesignProductItem($post) instead
	 */
	public function redesignProductItem($post = array())
	{
		RedshopHelperProduct::redesignProductItem($post);
	}

	/**
	 * Replace User Field
	 *
	 * @param   int     $productId   Product id
	 * @param   int     $templateId  Template id
	 * @param   string  $uniqueId    Unique id
	 *
	 * @return mixed
	 *
	 * @deprecated  __DEPLOY_VERSION__  Use RedshopHelperProduct::replaceUserfield($productId, $templateId, $uniqueId) instead
	 */
	public function replaceUserfield($productId = 0, $templateId = 0, $uniqueId = "")
	{
		RedshopHelperProduct::replaceUserfield($productId, $templateId, $uniqueId);
	}

	/**
	 * Insert Product user field
	 *
	 * @param   int     $fieldId      Field id
	 * @param   int     $orderItemId  Order item id
	 * @param   int     $sectionId    Section id
	 * @param   string  $value        Unique id
	 *
	 * @return boolen
	 *
	 * @deprecated  __DEPLOY_VERSION__  Use RedshopHelperProduct::admin_insertProdcutUserfield($fieldId, $orderItemId, $sectionId, $value) instead
	 */
	public function admin_insertProdcutUserfield($fieldId = 0, $orderItemId = 0, $sectionId = 12, $value = '')
	{
		RedshopHelperProduct::admin_insertProdcutUserfield($fieldId, $orderItemId, $sectionId, $value);
	}

	/**
	 * Get product by sort list
	 *
	 * @return array
	 *
	 * @deprecated  __DEPLOY_VERSION__  Use RedshopHelperProduct::getProductrBySortedList() instead
	 */
	public function getProductrBySortedList()
	{
		RedshopHelperProduct::getProductrBySortedList();
	}
}
