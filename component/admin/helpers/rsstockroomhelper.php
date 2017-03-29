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
 * Class Redshop Helper Stock Room
 *
 * @since  1.5
 */
class rsstockroomhelper
{
	protected static $instance = null;

	/**
	 * Returns the rsStockRoomHelper object, only creating it
	 * if it doesn't already exist.
	 *
	 * @return  rsStockRoomHelper  The rsStockRoomHelper object
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
	 * Get stockroom detail
	 *
	 * @param   int  $stockroomId  stockroom id
	 *
	 * @return  array
	 *
	 * @deprecated  2.0.0.3  Use RedshopHelperStockroom::getStockroom($stockroomId, null, true) instead
	 */
	public function getStockroomDetail($stockroomId = 0)
	{
		return RedshopHelperStockroom::getStockroom($stockroomId, null, true);
	}

	/**
	 * Check is stock exists
	 *
	 * @param   int     $sectionId    Section id
	 * @param   string  $section      Section
	 * @param   int     $stockroomId  Stockroom id
	 *
	 * @return mixed
	 *
	 * @deprecated  2.0.0.3  Use RedshopHelperStockroom::isStockExists($sectionId, $section, $stockroomId) instead
	 */
	public function isStockExists($sectionId = 0, $section = "product", $stockroomId = 0)
	{
		return RedshopHelperStockroom::isStockExists($sectionId, $section, $stockroomId);
	}

	/**
	 * Check is attribute stock exists
	 *
	 * @param   int  $productId  Product id
	 *
	 * @return mixed
	 *
	 * @deprecated  2.0.0.3  Use RedshopHelperStockroom::isAttributeStockExists($productId) instead
	 */
	public function isAttributeStockExists($productId)
	{
		return RedshopHelperStockroom::isAttributeStockExists($productId);
	}

	/**
	 * Check is pre-order stock exists
	 *
	 * @param   int     $sectionId    Section id
	 * @param   string  $section      Section
	 * @param   int     $stockroomId  Stockroom id
	 *
	 * @return mixed
	 *
	 * @deprecated  2.0.0.3  Use RedshopHelperStockroom::isPreorderStockExists($sectionId, $section, $stockroomId) instead
	 */
	public function isPreorderStockExists($sectionId = 0, $section = "product", $stockroomId = 0)
	{
		return RedshopHelperStockroom::isPreorderStockExists($sectionId, $section, $stockroomId);
	}

	/**
	 * Check is attribute pre-order stock exists
	 *
	 * @param   int  $productId  Product id
	 *
	 * @return mixed
	 *
	 * @deprecated  2.0.0.3  Use RedshopHelperStockroom::isAttributePreorderStockExists($productId) instead
	 */
	public function isAttributePreorderStockExists($productId)
	{
		return RedshopHelperStockroom::isAttributePreorderStockExists($productId);
	}

	/**
	 * Get Stockroom Total amount
	 *
	 * @param   int     $sectionId    Section id
	 * @param   string  $section      Section
	 * @param   int     $stockroomId  Stockroom id
	 *
	 * @return mixed
	 *
	 * @deprecated  2.0.0.3  Use RedshopHelperStockroom::getStockroomTotalAmount($sectionId, $section, $stockroomId) instead
	 */
	public function getStockroomTotalAmount($sectionId = 0, $section = "product", $stockroomId = 0)
	{
		return RedshopHelperStockroom::getStockroomTotalAmount($sectionId, $section, $stockroomId);
	}

	/**
	 * Get pre-order stockroom total amount
	 *
	 * @param   int     $sectionId    Section id
	 * @param   string  $section      Section
	 * @param   int     $stockroomId  Stockroom id
	 *
	 * @return mixed
	 *
	 * @deprecated  2.0.0.3  Use RedshopHelperStockroom::getPreorderStockroomTotalAmount($sectionId, $section, $stockroomId) instead
	 */
	public function getPreorderStockroomTotalAmount($sectionId = 0, $section = "product", $stockroomId = 0)
	{
		return RedshopHelperStockroom::getPreorderStockroomTotalAmount($sectionId, $section, $stockroomId);
	}

	/**
	 * Get Stock Amount with Reserve
	 *
	 * @param   int     $sectionId    Section id
	 * @param   string  $section      Section
	 * @param   int     $stockroomId  Stockroom id
	 *
	 * @return int|mixed
	 *
	 * @deprecated  2.0.0.3  Use RedshopHelperStockroom::getStockAmountwithReserve($sectionId, $section, $stockroomId) instead
	 */
	public function getStockAmountwithReserve($sectionId = 0, $section = 'product', $stockroomId = 0)
	{
		return RedshopHelperStockroom::getStockAmountWithReserve($sectionId, $section, $stockroomId);
	}

	/**
	 * Get pre-order stockroom amount with reserve
	 *
	 * @param   int     $sectionId    Section id
	 * @param   string  $section      Section
	 * @param   int     $stockroomId  Stockroom id
	 *
	 * @return mixed
	 *
	 * @deprecated  2.0.0.3  Use RedshopHelperStockroom::getPreorderStockAmountwithReserve($sectionId, $section, $stockroomId) instead
	 */
	public function getPreorderStockAmountwithReserve($sectionId = 0, $section = "product", $stockroomId = 0)
	{
		return RedshopHelperStockroom::getPreorderStockAmountwithReserve($sectionId, $section, $stockroomId);
	}

	/**
	 * Get stockroom amount detail list
	 *
	 * @param   int     $sectionId    Section id
	 * @param   string  $section      Section
	 * @param   int     $stockroomId  Stockroom id
	 *
	 * @return mixed
	 *
	 * @deprecated  2.0.0.3  Use RedshopHelperStockroom::getStockroomAmountDetailList($sectionId, $section, $stockroomId) instead
	 */
	public function getStockroomAmountDetailList($sectionId = 0, $section = "product", $stockroomId = 0)
	{
		return RedshopHelperStockroom::getStockroomAmountDetailList($sectionId, $section, $stockroomId);
	}

	/**
	 * Get pre-order stockroom amount detail list
	 *
	 * @param   int     $sectionId    Section id
	 * @param   string  $section      Section
	 * @param   int     $stockroomId  Stockroom id
	 *
	 * @return mixed
	 *
	 * @deprecated  2.0.0.3  Use RedshopHelperStockroom::getPreorderStockroomAmountDetailList($sectionId, $section, $stockroomId)
	 *  instead
	 */
	public function getPreorderStockroomAmountDetailList($sectionId = 0, $section = "product", $stockroomId = 0)
	{
		return RedshopHelperStockroom::getPreorderStockroomAmountDetailList($sectionId, $section, $stockroomId);
	}

	/**
	 * Update stockroom quantity
	 *
	 * @param   int     $sectionId  Section id
	 * @param   int     $quantity   Stockroom quantity
	 * @param   string  $section    Section
	 * @param   int     $productId  Product id
	 *
	 * @return mixed
	 *
	 * @deprecated  2.0.0.3  Use RedshopHelperStockroom::updateStockroomQuantity($sectionId, $quantity, $section, $productId) instead
	 */
	public function updateStockroomQuantity($sectionId = 0, $quantity = 0, $section = "product", $productId = 0)
	{
		return RedshopHelperStockroom::updateStockroomQuantity($sectionId, $quantity, $section, $productId);
	}

	/**
	 * Update stockroom amount
	 *
	 * @param   int     $sectionId    Section id
	 * @param   int     $quantity     Stockroom quantity
	 * @param   int     $stockroomId  Stockroom id
	 * @param   string  $section      Section
	 *
	 * @return mixed
	 *
	 * @deprecated  2.0.0.3  Use RedshopHelperStockroom::updateStockAmount($sectionId, $quantity, $stockroomId, $section) instead
	 */
	public function updateStockAmount($sectionId = 0, $quantity = 0, $stockroomId = 0, $section = "product")
	{
		return RedshopHelperStockroom::updateStockAmount($sectionId, $quantity, $stockroomId, $section);
	}

	/**
	 * Update pre-order stock amount
	 *
	 * @param   int     $sectionId    Section id
	 * @param   int     $quantity     Stockroom quantity
	 * @param   int     $stockroomId  Stockroom id
	 * @param   string  $section      Section
	 *
	 * @return mixed
	 *
	 * @deprecated  2.0.0.3  Use RedshopHelperStockroom::updatePreorderStockAmount($sectionId, $quantity, $stockroomId, $section)
	 *  instead
	 */
	public function updatePreorderStockAmount($sectionId = 0, $quantity = 0, $stockroomId = 0, $section = "product")
	{
		return RedshopHelperStockroom::updatePreorderStockAmount($sectionId, $quantity, $stockroomId, $section);
	}

	/**
	 * Manage stock amount
	 *
	 * @param   int     $sectionId    Section id
	 * @param   int     $quantity     Stockroom quantity
	 * @param   int     $stockroomId  Stockroom id
	 * @param   string  $section      Section
	 *
	 * @return mixed
	 *
	 * @deprecated  2.0.0.3  Use RedshopHelperStockroom::manageStockAmount($sectionId, $quantity, $stockroomId, $section) instead
	 */
	public function manageStockAmount($sectionId = 0, $quantity = 0, $stockroomId = 0, $section = "product")
	{
		return RedshopHelperStockroom::manageStockAmount($sectionId, $quantity, $stockroomId, $section);
	}

	/**
	 * Replace stockroom amount detail
	 *
	 * @param   string  $templateDesc  Template desciption
	 * @param   int     $sectionId     Section id
	 * @param   string  $section       Section
	 *
	 * @return mixed
	 *
	 * @deprecated  2.0.0.3  Use RedshopHelperStockroom::replaceStockroomAmountDetail($templateDesc, $sectionId, $section) instead
	 */
	public function replaceStockroomAmountDetail($templateDesc = "", $sectionId = 0, $section = "product")
	{
		return RedshopHelperStockroom::replaceStockroomAmountDetail($templateDesc, $sectionId, $section);
	}

	/**
	 * Get stock amount image
	 *
	 * @param   int     $sectionId    Section id
	 * @param   string  $section      Section
	 * @param   int     $stockAmount  Stockroom amount
	 *
	 * @return mixed
	 *
	 * @deprecated  2.0.0.3  Use RedshopHelperStockroom::getStockAmountImage($sectionId, $section, $stockAmount) instead
	 */
	public function getStockAmountImage($sectionId = 0, $section = "product", $stockAmount = 0)
	{
		return RedshopHelperStockroom::getStockAmountImage($sectionId, $section, $stockAmount);
	}

	/**
	 * Get reserved Stock
	 *
	 * @param   int     $sectionId  Section id
	 * @param   string  $section    Section
	 *
	 * @return mixed
	 *
	 * @deprecated  2.0.0.3  Use RedshopHelperStockroom::getReservedStock($sectionId, $section) instead
	 */
	public function getReservedStock($sectionId, $section = "product")
	{
		return RedshopHelperStockroom::getReservedStock($sectionId, $section);
	}

	/**
	 * Get current User reserved stock
	 *
	 * @param   int     $sectionId  Section id
	 * @param   string  $section    Section
	 *
	 * @return mixed
	 *
	 * @deprecated  2.0.0.3  Use RedshopHelperStockroom::getCurrentUserReservedStock($sectionId, $section) instead
	 */
	public function getCurrentUserReservedStock($sectionId, $section = "product")
	{
		return RedshopHelperStockroom::getCurrentUserReservedStock($sectionId, $section);
	}

	/**
	 * Delete expired cart product
	 *
	 * @return mixed
	 *
	 * @deprecated  2.0.0.3  Use RedshopHelperStockroom::deleteExpiredCartProduct() instead
	 */
	public function deleteExpiredCartProduct()
	{
		return RedshopHelperStockroom::deleteExpiredCartProduct();
	}

	/**
	 * Delete cart after empty
	 *
	 * @param   int     $sectionId  Section id
	 * @param   string  $section    Section
	 * @param   int     $quantity   Stockroom quantity
	 *
	 * @return mixed
	 *
	 * @deprecated  2.0.0.3  Use RedshopHelperStockroom::deleteCartAfterEmpty($sectionId, $section, $quantity) instead
	 */
	public function deleteCartAfterEmpty($sectionId = 0, $section = "product", $quantity = 0)
	{
		return RedshopHelperStockroom::deleteCartAfterEmpty($sectionId, $section, $quantity);
	}

	/**
	 * Add reserved stock
	 *
	 * @param   int     $sectionId  Section id
	 * @param   int     $quantity   Stockroom quantity
	 * @param   string  $section    Section
	 *
	 * @return mixed
	 *
	 * @deprecated  2.0.0.3  Use RedshopHelperStockroom::addReservedStock($sectionId, $quantity, $section) instead
	 */
	public function addReservedStock($sectionId, $quantity = 0, $section = "product")
	{
		return RedshopHelperStockroom::addReservedStock($sectionId, $quantity, $section);
	}

	/**
	 * Get stockroom detail
	 *
	 * @param   mixed  $stockroomId  Stockroom ID in string
	 *
	 * @return  array
	 *
	 * @deprecated  2.0.0.3  Use RedshopHelperStockroom::getStockroom($stockroomId, 1) instead
	 */
	public function getStockroom($stockroomId)
	{
		return RedshopHelperStockroom::getStockroom($stockroomId, 1);
	}

	/**
	 * Get min delivery time
	 *
	 * @param   int  $stockroomId  Stockroom id
	 *
	 * @return mixed
	 *
	 * @deprecated  2.0.0.3  Use RedshopHelperStockroom::getStockroomMaxDelivery($stockroomId) instead
	 */
	public function getStockroom_maxdelivery($stockroomId)
	{
		return RedshopHelperStockroom::getStockroomMaxDelivery($stockroomId);
	}

	/**
	 * Get date diff
	 *
	 * @param   int  $endDate    End date
	 * @param   int  $beginDate  Begin date
	 *
	 * @return mixed
	 *
	 * @deprecated  2.0.0.3  Use RedshopHelperStockroom::getDateDiff($endDate, $beginDate) instead
	 */
	public function getdatediff($endDate, $beginDate)
	{
		return RedshopHelperStockroom::getDateDiff($endDate, $beginDate);
	}

	/**
	 * Get final stock of product
	 *
	 * @param   int  $productId  Product id
	 * @param   int  $totalAtt   Total attribute
	 *
	 * @return mixed
	 *
	 * @deprecated  2.0.0.3  Use RedshopHelperStockroom::getFinalStockofProduct($productId, $totalAtt) instead
	 */
	public function getFinalStockofProduct($productId, $totalAtt)
	{
		return RedshopHelperStockroom::getFinalStockofProduct($productId, $totalAtt);
	}

	/**
	 * Get final pre-order stock of product
	 *
	 * @param   int  $productId  Product id
	 * @param   int  $totalAtt   Total attribute
	 *
	 * @return mixed
	 *
	 * @deprecated  2.0.0.3  Use RedshopHelperStockroom::getFinalPreorderStockofProduct($productId, $totalAtt) instead
	 */
	public function getFinalPreorderStockofProduct($productId, $totalAtt)
	{
		return RedshopHelperStockroom::getFinalPreorderStockofProduct($productId, $totalAtt);
	}
}
