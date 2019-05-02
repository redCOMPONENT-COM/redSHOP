<?php
/**
 * @package     RedSHOP.Backend
 * @subpackage  Helper
 *
 * @copyright   Copyright (C) 2008 - 2019 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 *
 * @deprecated  2.0.3  Use RedshopHelperQuotation instead
 */

defined('_JEXEC') or die;

/**
 * Class Redshop Helper for Quotation
 *
 * @since  1.6.0
 * @deprecated  2.0.3  Use RedshopHelperQuotation instead
 */
class quotationHelper
{
	/**
	 * @var null
	 *
	 * @deprecated  2.0.3
	 */
	public $_data = null;

	/**
	 * @var null
	 *
	 * @deprecated  2.0.3
	 */
	public $_table_prefix = null;

	/**
	 * @var null
	 *
	 * @deprecated  2.0.3
	 */
	public $_db = null;

	/**
	 * @var null
	 *
	 * @deprecated  2.0.3
	 */
	protected static $instance = null;

	/**
	 * Returns the quotationHelper object, only creating it
	 * if it doesn't already exist.
	 *
	 * @return  quotationHelper  The quotationHelper object
	 *
	 * @since   1.6
	 *
	 * @deprecated  2.0.3
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
	 * Constructor
	 *
	 * @deprecated  2.0.3
	 */
	public function __construct()
	{
		$this->_db           = JFactory::getDbo();
		$this->_table_prefix = '#__redshop_';
	}

	/**
	 * Get list of quotation status
	 *
	 * @return  array  An array of status options
	 *
	 * @deprecated  2.0.3  Use RedshopHelperQuotation::getQuotationStatusList() instead
	 */
	public function getQuotationStatusList()
	{
		return RedshopHelperQuotation::getQuotationStatusList();
	}

	/**
	 * Get name of quotation status
	 *
	 * @param   integer  $value  Have 5 options: REQUESTED/REPLIED/ACCEPTED/REJECTED/ORDERED
	 *
	 * @return  string   Name of Quotation status
	 *
	 * @deprecated  2.0.3  Use RedshopHelperQuotation::getQuotationStatusName() instead
	 */
	public function getQuotationStatusName($value = 0)
	{
		return RedshopHelperQuotation::getQuotationStatusName($value);
	}

	/**
	 * Get quotation product
	 *
	 * @param   integer $quotationId     Quotation ID
	 * @param   integer $quotationItemId Quotation Item ID
	 *
	 * @return  array
	 *
	 * @deprecated  2.0.3  Use RedshopHelperQuotation::getQuotationProduct() instead
	 */
	public function getQuotationProduct($quotationId = 0, $quotationItemId = 0)
	{
		return RedshopHelperQuotation::getQuotationProduct($quotationId, $quotationItemId);
	}

	/**
	 * Get quotation detail
	 *
	 * @param   integer $quotationId Quotation ID
	 *
	 * @return  object
	 *
	 * @deprecated  2.0.3  Use RedshopHelperQuotation::getQuotationDetail() instead
	 */
	public function getQuotationDetail($quotationId)
	{
		return RedshopHelperQuotation::getQuotationDetail($quotationId);
	}

	/**
	 * Generate a unique quotation number
	 *
	 * @return  integer
	 *
	 * @deprecated  2.0.3  Use RedshopHelperQuotation::generateQuotationNumber() instead
	 */
	public function generateQuotationNumber()
	{
		return RedshopHelperQuotation::generateQuotationNumber();
	}

	/**
	 * Update Quotation Status
	 *
	 * @param   integer $quotationId Quotation ID
	 * @param   integer $status      Quotation Change status
	 *
	 * @return  void
	 *
	 * @deprecated  2.0.3  Use RedshopHelperQuotation::updateQuotationStatus() instead
	 */
	public function updateQuotationStatus($quotationId, $status = 1)
	{
		RedshopHelperQuotation::updateQuotationStatus($quotationId, $status);
	}

	/**
	 * Get list of quotation users
	 *
	 * @return  array
	 *
	 * @deprecated  2.0.3  Use RedshopHelperQuotation::getQuotationUserList() instead
	 */
	public function getQuotationUserList()
	{
		return RedshopHelperQuotation::getQuotationUserList();
	}

	/**
	 * Generate a random encrytion key for quotation
	 *
	 * @param   string $length Length of string to generate
	 *
	 * @return  string
	 *
	 * @deprecated  2.0.3  Use RedshopHelperQuotation::randomQuotationEncryptKey() instead
	 */
	public function randomQuotationEncrkey($length = '30')
	{
		return RedshopHelperQuotation::randomQuotationEncryptKey($length);
	}

	/**
	 * Inserting quotation user's fields
	 *
	 * @param   array    $cart               Array of fields to insert
	 * @param   integer  $quotation_item_id  Item ID of Quotation to match
	 * @param   integer  $section_id         Section to get field list
	 *
	 * @return  boolean  true/false when inserting success or fail
	 *
	 * @deprecated  2.0.3  Use RedshopHelperQuotation::manageQuotationUserField() instead
	 */
	public function manageQuotationUserfield($cart = array(), $quotation_item_id = 0, $section_id = 12)
	{
		return RedshopHelperQuotation::manageQuotationUserField($cart, $quotation_item_id, $section_id);
	}

	/**
	 * Insert quotation field with value
	 *
	 * @param   integer  $field_id           Field ID
	 * @param   integer  $quotation_item_id  Quotation Item ID
	 * @param   integer  $section_id         Section ID
	 * @param   string   $value              Value to insert
	 *
	 * @return  void
	 *
	 * @deprecated  2.0.3  Use RedshopHelperQuotation::insertQuotationUserField() instead
	 */
	public function insertQuotationUserfield($field_id = 0, $quotation_item_id = 0, $section_id = 12, $value = '')
	{
		RedshopHelperQuotation::insertQuotationUserField($field_id, $quotation_item_id, $section_id, $value);
	}

	/**
	 * Get quotation item fields by field ID
	 *
	 * @param   integer $quotationItemId Quotation Item ID
	 *
	 * @return  object
	 *
	 * @deprecated  2.0.3  Use RedshopHelperQuotation::getQuotationUserField() instead
	 */
	public function getQuotationUserfield($quotationItemId)
	{
		return RedshopHelperQuotation::getQuotationUserField($quotationItemId);
	}

	/**
	 * Display quotation user fields
	 *
	 * @param   integer $quotationItemId Quotation Item ID
	 * @param   integer $sectionId       Section ID
	 *
	 * @return  string   HTML to display
	 * @throws  Exception
	 *
	 * @deprecated  2.0.3  Use RedshopHelperQuotation::displayQuotationUserField() instead
	 */
	public function displayQuotationUserfield($quotationItemId = 0, $sectionId = 12)
	{
		return RedshopHelperQuotation::displayQuotationUserField($quotationItemId, $sectionId);
	}

	/**
	 * Update quotation with order ID
	 *
	 * @param   integer  $quotation_id  Quotation ID
	 * @param   integer  $order_id      Order ID
	 *
	 * @return  boolean/void           Return true if success, alert error if fail
	 *
	 * @deprecated  2.0.3  Use RedshopHelperQuotation::updateQuotationWithOrder() instead
	 */
	public function updateQuotationwithOrder($quotation_id, $order_id)
	{
		return RedshopHelperQuotation::updateQuotationWithOrder($quotation_id, $order_id);
	}

	/**
	 * Get quotation by order id
	 *
	 * @param   integer/array  $order_id  OrderID
	 *
	 * @return  object
	 *
	 * @deprecated  2.0.3  Use RedshopHelperQuotation::getQuotationWithOrder() instead
	 */
	public function getQuotationwithOrder($order_id = 0)
	{
		return RedshopHelperQuotation::getQuotationWithOrder($order_id);
	}

	/**
	 * Get quotation accesory item by ID
	 *
	 * @param   integer  $quotation_item_id  Quotation Item ID
	 *
	 * @return  object
	 *
	 * @deprecated  2.0.3  Use RedshopHelperQuotation::getQuotationItemAccessoryDetail() instead
	 */
	public function getQuotationItemAccessoryDetail($quotation_item_id = 0)
	{
		return RedshopHelperQuotation::getQuotationItemAccessoryDetail($quotation_item_id);
	}

	/**
	 * Get Quotation Item Attribute Detail
	 *
	 * @param   integer  $quotation_item_id  Quotation Item ID
	 * @param   integer  $is_accessory       Check Accessory Attribute
	 * @param   string   $section            Section: default is "attribute"
	 * @param   integer  $parent_section_id  Parent section ID
	 *
	 * @return  object
	 *
	 * @deprecated  2.0.3  Use RedshopHelperQuotation::getQuotationItemAttributeDetail() instead
	 */
	public function getQuotationItemAttributeDetail($quotation_item_id = 0, $is_accessory = 0, $section = "attribute", $parent_section_id = 0)
	{
		return RedshopHelperQuotation::getQuotationItemAttributeDetail($quotation_item_id, $is_accessory, $section, $parent_section_id);
	}
}
