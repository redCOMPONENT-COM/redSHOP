<?php
/**
 * @package     RedSHOP.Frontend
 * @subpackage  Helper
 *
 * @copyright   Copyright (C) 2008 - 2017 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

defined('_JEXEC') or die;

/**
 * Class redHelper
 *
 * @since  1.6.0
 */
class redhelper
{
	/**
	 * @var  self
	 */
	protected static $instance = null;

	/**
	 * Returns the redHelper object, only creating it
	 * if it doesn't already exist.
	 *
	 * @return  redHelper  The redHelper object
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
	 * Constructor.
	 */
	public function __construct()
	{
		$this->_table_prefix = '#__redshop_';
		$this->_db           = JFactory::getDbo();
	}

	/**
	 * Quote an array of values.
	 *
	 * @param   array   $values     The values.
	 * @param   string  $nameQuote  Name quote, can be possible q, quote, qn, quoteName
	 *
	 * @return  array  The quoted values
	 *
	 * @deprecated  2.0.6  Use RedshopHelperUtility::quote or RedshopHelperUtility::quoteName instead
	 */
	public static function quote(array $values, $nameQuote = 'q')
	{
		if ($nameQuote == 'q')
		{
			return RedshopHelperUtility::quote($values);
		}

		return RedshopHelperUtility::quoteName($values);
	}

	/**
	 * Set Operand For Values
	 *
	 * @param   float   $leftValue   Left value
	 * @param   string  $operand     Operand
	 * @param   float   $rightValue  Right value
	 *
	 * @return  float
	 *
	 * @deprecated  2.0.6  Use RedshopHelperUtility::setOperandForValues instead
	 */
	public static function setOperandForValues($leftValue, $operand, $rightValue)
	{
		return RedshopHelperUtility::setOperandForValues($leftValue, $operand, $rightValue);
	}

	/**
	 * Get Redshop Menu Items
	 *
	 * @return array
	 *
	 * @deprecated  2.0.6  Use RedshopHelperUtility::getRedshopMenuItems instead
	 */
	public function getRedshopMenuItems()
	{
		return RedshopHelperUtility::getRedshopMenuItems();
	}

	/**
	 * Add item to cart from db ...
	 *
	 * @return  void
	 *
	 * @deprecated  2.0.6  Use RedshopHelperUtility::databaseToCart instead
	 */
	public function dbtocart()
	{
		RedshopHelperUtility::databaseToCart();
	}

	/**
	 * Delete shipping rate when shipping method is not available
	 *
	 * @return  void
	 *
	 * @deprecated  2.0.6  Use RedshopShippingRate::removeShippingRate instead
	 */
	public function removeShippingRate()
	{
		RedshopShippingRate::removeShippingRate();
	}

	/**
	 * Get plugins
	 *
	 * @param   string  $folder   Group of plugins
	 * @param   string  $enabled  -1: All, 0: not enable, 1: enabled
	 *
	 * @return  array
	 *
	 * @deprecated  2.0.6  Use RedshopHelperUtility::getPlugins instead
	 */
	public function getPlugins($folder = 'redshop', $enabled = '1')
	{
		return RedshopHelperUtility::getPlugins($folder, $enabled);
	}

	/**
	 * Method for get modules
	 *
	 * @param   string  $enabled  [-1: All, 0: not enable, 1: enabled]
	 *
	 * @return  array
	 *
	 * @since   2.0.6
	 *
	 * @deprecated  2.0.6  Use RedshopHelperUtility::getModules instead
	 */
	public function getModules($enabled = '1')
	{
		return RedshopHelperUtility::getModules($enabled);
	}

	/**
	 * Get all plugins
	 *
	 * @param   string  $folder  Group of plugins
	 *
	 * @return  array
	 *
	 * @deprecated  2.0.6  Use RedshopHelperUtility::getPlugins instead
	 */
	public function getallPlugins($folder = 'redshop')
	{
		return RedshopHelperUtility::getPlugins($folder);
	}

	/**
	 * Method for check if order has this payment is update yet?
	 *
	 * @param   object   $dbConn   DB connection
	 * @param   integer  $orderId  Order ID
	 * @param   mixed    $tid      Order payment transaction id
	 *
	 * @return  boolean
	 *
	 * @deprecated  2.0.6  Use RedshopHelperPayment::orderPaymentNotYetUpdated instead
	 */
	public function orderPaymentNotYetUpdated($dbConn, $orderId, $tid)
	{
		return RedshopHelperPayment::orderPaymentNotYetUpdated($orderId, $tid);
	}

	/**
	 * Check Menu Query
	 *
	 * @param   object  $oneMenuItem  Values current menu item
	 * @param   array   $queryItems   Name query check
	 *
	 * @return  boolean
	 *
	 * @deprecated  2.0.6  Use RedshopHelperUtility::checkMenuQuery instead
	 */
	public function checkMenuQuery($oneMenuItem, $queryItems)
	{
		return RedshopHelperUtility::checkMenuQuery($oneMenuItem, $queryItems);
	}

	/**
	 * Get RedShop Menu Item
	 *
	 * @param   array  $queryItems  Values query
	 *
	 * @return  mixed
	 *
	 * @deprecated  2.0.6  Use RedshopHelperUtility::getRedShopMenuItem instead
	 */
	public function getRedShopMenuItem($queryItems)
	{
		return RedshopHelperUtility::getRedShopMenuItem($queryItems);
	}

	/**
	 * Get Item Id
	 *
	 * @param   int  $productId   Product Id
	 * @param   int  $categoryId  Category Id
	 *
	 * @return  mixed
	 *
	 * @deprecated  2.0.6  Use RedshopHelperUtility::getItemid instead
	 */
	public function getItemid($productId = 0, $categoryId = 0)
	{
		return RedshopHelperUtility::getItemId($productId, $categoryId);
	}

	/**
	 * Get Category Itemid
	 *
	 * @param   int  $categoryId  Category id
	 *
	 * @return  mixed
	 *
	 * @deprecated  2.0.6  Use RedshopHelperUtility::getCategoryItemid instead
	 */
	public function getCategoryItemid($categoryId = 0)
	{
		return RedshopHelperUtility::getCategoryItemid($categoryId);
	}

	/**
	 * Method for convert array of string
	 *
	 * @param   array  $arr  Language array
	 *
	 * @return  mixed
	 *
	 * @deprecated  2.0.6  Use RedshopHelperUtility::convertLanguageString instead
	 */
	public function convertLanguageString($arr)
	{
		return RedshopHelperUtility::convertLanguageString($arr);
	}

	/**
	 * Shopper Group portal info
	 *
	 * @return  object  Shopper Group Ids Object
	 *
	 * @deprecated  2.0.6  Use RedshopHelperShopper_Group::getShopperGroupPortal instead
	 */
	public function getShopperGroupPortal()
	{
		return RedshopHelperShopper_Group::getShopperGroupPortal();
	}

	/**
	 * Shopper Group category ACL
	 *
	 * @param   int  $cid  Category id
	 *
	 * @return  mixed
	 *
	 * @deprecated  2.0.6  Use RedshopHelperShopper_Group::getShopperGroupCategory instead
	 */
	public function getShopperGroupCategory($cid = 0)
	{
		return RedshopHelperShopper_Group::getShopperGroupCategory($cid);
	}

	/**
	 * Check permission for Categories shopper group can access or can't access
	 *
	 * @param   int  $cid  category id that need to be checked
	 *
	 * @return  boolean
	 *
	 * @deprecated  2.0.3 Use RedshopHelperAccess::checkPortalCategoryPermission() instead.
	 */
	public function checkPortalCategoryPermission($cid = 0)
	{
		return RedshopHelperAccess::checkPortalCategoryPermission($cid);
	}

	/**
	 * Check permission for Products shopper group can access or can't access
	 *
	 * @param   int  $pid  Product id that need to be checked
	 *
	 * @return  boolean
	 *
	 * @deprecated   2.0.6  Use RedshopHelperAccess::checkPortalProductPermission() instead
	 */
	public function checkPortalProductPermission($pid = 0)
	{
		return RedshopHelperAccess::checkPortalProductPermission($pid);
	}

	/**
	 * Shopper Group product category ACL
	 *
	 * @param   int  $pid  Category id
	 *
	 * @return  mixed
	 *
	 * @deprecated   2.0.6  Use RedshopHelperShopper_Group::checkPortalProductPermission() instead
	 */
	public function getShopperGroupProductCategory($pid = 0)
	{
		return RedshopHelperShopper_Group::getShopperGroupProductCategory($pid);
	}

	/**
	 * Method for get order by list
	 *
	 * @return  array
	 *
	 * @deprecated  2.0.6  Use RedshopHelperUtility::getOrderByList instead
	 */
	public function getOrderByList()
	{
		return RedshopHelperUtility::getOrderByList();
	}

	/**
	 * Prepare order by object for ordering from string.
	 *
	 * @param   string  $case  Order By string generated in getOrderByList method
	 *
	 * @return  object         Parsed strings in ordering and direction object key.
	 *
	 * @deprecated  2.0.6  Use RedshopHelperUtility::prepareOrderBy instead
	 */
	public function prepareOrderBy($case)
	{
		return RedshopHelperUtility::prepareOrderBy($case);
	}

	/**
	 * Method for get manufacturer order by list
	 *
	 * @return  array   List of order
	 *
	 * @deprecated  2.0.6  Use RedshopHelperUtility::getManufacturerOrderByList instead
	 */
	public function getManufacturerOrderByList()
	{
		return RedshopHelperUtility::getManufacturerOrderByList();
	}

	/**
	 * Method for get product related order by list
	 *
	 * @return  array   List of order
	 *
	 * @deprecated  2.0.6  Use RedshopHelperUtility::getRelatedOrderByList instead
	 */
	public function getRelatedOrderByList()
	{
		return RedshopHelperUtility::getRelatedOrderByList();
	}

	/**
	 * Method for get accessories order by list
	 *
	 * @return  array   List of order
	 *
	 * @deprecated  2.0.6  Use RedshopHelperUtility::getAccessoryOrderByList instead
	 */
	public function getAccessoryOrderByList()
	{
		return RedshopHelperUtility::getAccessoryOrderByList();
	}

	/**
	 * Method for get pre-order by list
	 *
	 * @return  array   List of order
	 *
	 * @deprecated  2.0.6  Use RedshopHelperUtility::getPreOrderByList instead
	 */
	public function getPreOrderByList()
	{
		return RedshopHelperUtility::getPreOrderByList();
	}

	/**
	 * Method for get child product order by list
	 *
	 * @return  array   List of order
	 *
	 * @deprecated  2.0.6  Use RedshopHelperUtility::getChildProductOption instead
	 */
	public function getChildProductOption()
	{
		return RedshopHelperUtility::getChildProductOption();
	}

	/**
	 * Method for get child product order by list
	 *
	 * @return  array   List of order
	 *
	 * @deprecated  2.0.6  Use RedshopHelperUtility::getStateAbbreviationsByList instead
	 */
	public function getStateAbbrivationByList()
	{
		return RedshopHelperUtility::getStateAbbreviationsByList();
	}

	/**
	 * Method for get menu item id of checkout page
	 *
	 * @return  integer
	 *
	 * @deprecated  2.0.6  Use RedshopHelperUtility::getCheckoutItemId instead
	 */
	public function getCheckoutItemId()
	{
		return RedshopHelperUtility::getCheckoutItemId();
	}

	/**
	 * Method for get menu item id of cart page
	 *
	 * @return  integer
	 *
	 * @deprecated  2.0.6  Use RedshopHelperUtility::getCartItemId instead
	 */
	public function getCartItemId()
	{
		return RedshopHelperUtility::getCartItemId();
	}

	/**
	 *  Generate thumb image
	 *
	 * @param   string   $section          Image section
	 * @param   string   $imageName        Image name
	 * @param   string   $thumbWidth       Thumb width
	 * @param   string   $thumbHeight      Thumb height
	 * @param   integer  $enableWatermark  Enable watermark
	 *
	 * @return  string
	 *
	 * @deprecated  2.0.6  Use RedshopHelperUtility::watermark instead
	 */
	public function watermark($section, $imageName = '', $thumbWidth = '', $thumbHeight = '', $enableWatermark = -1)
	{
		return RedshopHelperMedia::watermark($section, $imageName, $thumbWidth, $thumbHeight, $enableWatermark);
	}

	/**
	 * Method for run process on order ID
	 *
	 * @param   integer  $order_id  ID of order
	 *
	 * @return  void
	 *
	 * @deprecated   2.0.6  Use RedshopHelperClickATell::clickatellSMS instead
	 */
	public function clickatellSMS($order_id)
	{
		RedshopHelperClickatell::clickatellSMS($order_id);
	}

	/**
	 * Method for send message
	 *
	 * @param   string  $text  Message text
	 * @param   string  $to    Phone number for send
	 *
	 * @return  void
	 *
	 * @deprecated   2.0.6  Use RedshopHelperClickATell::clickatellSMS instead
	 */
	public function sendmessage($text, $to)
	{
		RedshopHelperClickatell::sendMessage($text, $to);
	}

	/**
	 * Method for replace message
	 *
	 * @param   string  $message      Message text
	 * @param   object  $orderData    Object data
	 * @param   string  $paymentName  Name of payment
	 *
	 * @return  mixed
	 *
	 * @deprecated   2.0.6  Use RedshopHelperClickATell::replaceMessage instead
	 */
	public function replaceMessage($message, $orderData, $paymentName)
	{
		return RedshopHelperClickatell::replaceMessage($message, $orderData, $paymentName);
	}

	/**
	 * SSL link
	 *
	 * @param   string   $link      Link
	 * @param   integer  $applySSL  Apply ssl or not.
	 *
	 * @deprecated 1.6   Use RedshopHelperUtility::getSslLink($link, $applySSL) instead
	 *
	 * @return  string              Converted string
	 */
	public function sslLink($link, $applySSL = 1)
	{
		return RedshopHelperUtility::getSSLLink($link, $applySSL);
	}

	/**
	 * Method for get Economic Account Group
	 *
	 * @param   integer  $accountGroupId  Account group ID
	 * @param   integer  $front           Is front or not
	 *
	 * @return  array
	 *
	 * @deprecated   2.0.6   Use RedshopHelperUtility::getSslLink($link, $applySSL) instead
	 */
	public function getEconomicAccountGroup($accountGroupId = 0, $front = 0)
	{
		return RedshopHelperUtility::getEconomicAccountGroup($accountGroupId, $front);
	}

	/**
	 * Method for check if ProductFinder is available or not.
	 *
	 * @return  boolean
	 *
	 * @deprecated   2.0.6  Use RedshopHelperUtility::isRedProductFinder instead
	 */
	public function isredProductfinder()
	{
		return RedshopHelperUtility::isRedProductFinder();
	}
}
