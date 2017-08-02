<?php
/**
 * @package     RedSHOP.Backend
 * @subpackage  Helper
 *
 * @copyright   Copyright (C) 2008 - 2017 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

defined('_JEXEC') or die;

/**
 * Class shipping Helper
 *
 * @deprecated  2.0.0.3
 */
class shipping
{
	protected static $instance = null;

	/**
	 * Returns the shipping object, only creating it
	 * if it doesn't already exist.
	 *
	 * @return  shipping  The shipping object
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
	 * Get Shipping rate for cart
	 *
	 * @param   array  $data  Shipping data
	 *
	 * @return  array
	 *
	 * @deprecated  2.0.0.3  Use RedshopHelperShipping::getDefaultShipping($d) instead
	 */
	public function getDefaultShipping($data)
	{
		return RedshopHelperShipping::getDefaultShipping($data);
	}

	/**
	 * Get Shipping rate for xmlexport
	 *
	 * @param   array  $d  Shipping data
	 *
	 * @return  array
	 *
	 * @deprecated  2.0.0.3  Use RedshopHelperShipping::getDefaultShippingXmlExport($d) instead
	 */
	public function getDefaultShipping_xmlexport($d)
	{
		return RedshopHelperShipping::getDefaultShippingXmlExport($d);
	}

	/**
	 * Return only one shipping rate on cart page...
	 * this function is called by ajax
	 *
	 * @return  string
	 *
	 * @deprecated  2.0.0.3  Use RedshopHelperShipping::getShippingRateCalc() instead
	 */
	public function getShippingrate_calc()
	{
		return RedshopHelperShipping::getShippingRateCalc();
	}

	/**
	 * Encrypt Shipping
	 *
	 * @param   string  $strMessage  String to encrypt
	 *
	 * @deprecated 1.6  Use RedshopShippingRate::encrypt(array);
	 *
	 * @return  string  Encrypt shipping rate
	 *
	 * @deprecated  2.0.0.3  Use RedshopHelperShipping::encryptShipping($strMessage) instead
	 */
	public function encryptShipping($strMessage)
	{
		return RedshopHelperShipping::encryptShipping($strMessage);
	}

	/**
	 * Decrypt Shipping
	 *
	 * @param   string  $strMessage  String to decrypt
	 *
	 * @deprecated 1.6  Use RedshopShippingRate::decrypt(string);
	 *
	 * @return  string  Encrypt shipping rate
	 *
	 * @deprecated  2.0.0.3  Use RedshopHelperShipping::decryptShipping($strMessage) instead
	 */
	public function decryptShipping($strMessage)
	{
		return RedshopHelperShipping::decryptShipping($strMessage);
	}

	/**
	 * Get shipping address
	 *
	 * @param   int  $userInfoId  User info id
	 *
	 * @return  object
	 *
	 * @deprecated  2.0.0.3  Use RedshopHelperShipping::getShippingAddress($userInfoId) instead
	 */
	public function getShippingAddress($userInfoId)
	{
		return RedshopHelperShipping::getShippingAddress($userInfoId);
	}

	/**
	 * Get shipping method class
	 *
	 * @param   string  $shippingClass  Shipping class
	 *
	 * @return  object
	 *
	 * @deprecated  2.0.0.3  Use RedshopHelperShipping::getShippingMethodByClass($shippingClass) instead
	 */
	public function getShippingMethodByClass($shippingClass = '')
	{
		return RedshopHelperShipping::getShippingMethodByClass($shippingClass);
	}

	/**
	 * Get shipping method by id
	 *
	 * @param   int  $id  Shipping id
	 *
	 * @return  object
	 *
	 * @deprecated  2.0.0.3  Use RedshopHelperShipping::getShippingMethodById($id) instead
	 */
	public function getShippingMethodById($id = 0)
	{
		return RedshopHelperShipping::getShippingMethodById($id);
	}

	/**
	 * Get shipping rates
	 *
	 * @param   string  $shippingClass  Shipping class
	 *
	 * @return  object
	 *
	 * @deprecated  2.0.0.3  Use RedshopHelperShipping::getShippingRates($shippingClass) instead
	 */
	public function getShippingRates($shippingClass)
	{
		return RedshopHelperShipping::getShippingRates($shippingClass);
	}

	/**
	 * Apply VAT on shipping rate
	 *
	 * @param   object  $shippingRate  Shipping Rate information
	 * @param   array   $data          Shipping Rate user information from cart or checkout selection.
	 *
	 * @return  float  Shipping Rate
	 *
	 * @deprecated  2.0.0.3  Use RedshopHelperShipping::applyVatOnShippingRate($shippingRate, $data) instead
	 */
	public function applyVatOnShippingRate($shippingRate, $data)
	{
		return RedshopHelperShipping::applyVatOnShippingRate($shippingRate, $data);
	}

	/**
	 * List shipping rates
	 *
	 * @param   object  $shippingClass  Shipping class
	 * @param   int     $usersInfoId    User info id
	 * @param   array   &$data          Shipping data
	 *
	 * @return  object  Shipping Rate
	 *
	 * @deprecated  2.0.0.3  Use RedshopHelperShipping::listShippingRates($shippingClass, $usersInfoId, &$data) instead
	 */
	public function listshippingrates($shippingClass, $usersInfoId, &$data)
	{
		return RedshopHelperShipping::listShippingRates($shippingClass, $usersInfoId, $data);
	}

	/**
	 * Get shipping vat rates based on either billing or shipping user
	 *
	 * @param   int    $shippingTaxGroupId  Shipping Default Tax Gorup ID
	 * @param   array  $data                Shipping User Information array
	 *
	 * @return  object Shipping VAT rates
	 *
	 * @deprecated  2.0.0.3  Use RedshopHelperShipping::getShippingVatRates($shippingTaxGroupId, $data) instead
	 */
	public function getShippingVatRates($shippingTaxGroupId, $data = array())
	{
		return RedshopHelperShipping::getShippingVatRates($shippingTaxGroupId, $data);
	}

	/**
	 * Get shopper group default shipping
	 *
	 * @param   int  $userId  User id
	 *
	 * @return  array
	 *
	 * @deprecated  2.0.0.3  Use RedshopHelperShipping::getShopperGroupDefaultShipping($userId) instead
	 */
	public function getShopperGroupDefaultShipping($userId = 0)
	{
		return RedshopHelperShipping::getShopperGroupDefaultShipping($userId);
	}

	/**
	 * Find first number position
	 *
	 * @param   string  $haystack  string to find
	 * @param   array   $needles   array to find
	 * @param   int     $offset    position
	 *
	 * @return  array
	 *
	 * @deprecated  2.0.0.3  Use RedshopHelperShipping::strposa($haystack, $needles, $offset) instead
	 */
	public function strposa($haystack, $needles = array(), $offset = 0)
	{
		return RedshopHelperShipping::strposa($haystack, $needles, $offset);
	}

	/**
	 * Filter Shipping rates based on their priority
	 * Only show Higher priority rates (In [1,2,3,4] take 1 as a high priority)
	 * Rates with same priority will shown as radio button list in checkout
	 *
	 * @param   array  $shippingRates  Array shipping rates
	 *
	 * @return array
	 *
	 * @deprecated  2.0.0.3  Use RedshopHelperShipping::filterRatesByPriority($shippingRates) instead
	 */
	public static function filterRatesByPriority($shippingRates)
	{
		return RedshopHelperShipping::filterRatesByPriority($shippingRates);
	}

	/**
	 * Function to get product volume shipping
	 *
	 * @return array $cases , 3cases of shipping
	 *
	 * @deprecated  2.0.0.3  Use RedshopHelperShipping::getProductVolumeShipping() instead
	 */
	public function getProductVolumeShipping()
	{
		return RedshopHelperShipping::getProductVolumeShipping();
	}

	/**
	 * Function to get cart item dimension
	 *
	 * @return array
	 *
	 * @deprecated  2.0.0.3  Use RedshopHelperShipping::getCartItemDimension() instead
	 */
	public function getCartItemDimention()
	{
		return RedshopHelperShipping::getCartItemDimension();
	}

	/**
	 * Get available shipping boxes according to cart items
	 *
	 * @return object
	 *
	 * @deprecated  2.0.0.3  Use RedshopHelperShipping::getShippingBox() instead
	 */
	public function getShippingBox()
	{
		return RedshopHelperShipping::getShippingBox();
	}

	/**
	 * Get selected shipping BOX dimensions
	 *
	 * @param   int  $boxId  Shipping Box id
	 *
	 * @return  array  box dimensions
	 *
	 * @deprecated  2.0.0.3  Use RedshopHelperShipping::getBoxDimensions($boxId) instead
	 */
	public function getBoxDimensions($boxId = 0)
	{
		return RedshopHelperShipping::getBoxDimensions($boxId);
	}

	/**
	 * Get Shipping rate error
	 *
	 * @param   array  &$data  Shipping rate data
	 *
	 * @return  string  error text
	 *
	 * @deprecated  2.0.0.3  Use RedshopHelperShipping::getShippingRateError($data) instead
	 */
	public function getShippingRateError(&$data)
	{
		return RedshopHelperShipping::getShippingRateError($data);
	}

	/**
	 * Check cart dimension is matched
	 *
	 * @param   array  &$data  Cart data
	 *
	 * @return  boolean
	 *
	 * @deprecated  2.0.0.3  Use RedshopHelperShipping::isCartDimensionMatch($data) instead
	 */
	public function isCartDimentionMatch(&$data)
	{
		return RedshopHelperShipping::isCartDimensionMatch($data);
	}

	/**
	 * Check user info is matched
	 *
	 * @param   array  &$data  Cart data
	 *
	 * @return  boolean
	 *
	 * @deprecated  2.0.0.3  Use RedshopHelperShipping::isUserInfoMatch($data) instead
	 */
	public function isUserInfoMatch(&$data)
	{
		return RedshopHelperShipping::isUserInfoMatch($data);
	}

	/**
	 * Check product detail is matched
	 *
	 * @return  boolean
	 *
	 * @deprecated  2.0.0.3  Use RedshopHelperShipping::isProductDetailMatch() instead
	 */
	public function isProductDetailMatch()
	{
		return RedshopHelperShipping::isProductDetailMatch();
	}

	/**
	 * Get free shipping rate
	 *
	 * @param   int  $shippingRateId  Shipping rate ID
	 *
	 * @return  string
	 *
	 * @deprecated  2.0.0.3  Use RedshopHelperShipping::getFreeShippingRate($shippingRateId) instead
	 */
	public function getfreeshippingRate($shippingRateId = 0)
	{
		return RedshopHelperShipping::getFreeShippingRate($shippingRateId);
	}
}
