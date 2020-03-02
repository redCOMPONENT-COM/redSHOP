<?php
/**
 * @package     RedSHOP.Backend
 * @subpackage  Helper
 *
 * @copyright   Copyright (C) 2008 - 2019 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 *
 * @deprecated  2.0.0.3  Use RedshopHelperCategory instead
 */

defined('_JEXEC') or die;

/**
 * A Helper Class for Product Category
 *
 * @since  1.0.0
 *
 * @deprecated  2.0.0.3  Use RedshopHelperCategory instead
 */
class product_category
{
	/**
	 * @deprecated  2.0.0.3
	 */
	public $_cats = array();

	/**
	 * @deprecated  2.0.0.3
	 */
	public $_table_prefix = null;

	/**
	 * Constructor
	 *
	 * @deprecated  2.0.0.3
	 */
	public function __construct()
	{
		$this->_table_prefix = '#__redshop_';
	}

	/**
	 * List all categories and return HTML format
	 *
	 * @param   string  $name               Name of list
	 * @param   integer $categoryId         Only category to show
	 * @param   array   $selectedCategories Only select categories from this
	 * @param   integer $size               Size of dropdown
	 * @param   boolean $toplevel           Add option '-Top-'
	 * @param   boolean $multiple           Dropdown is multiple or not
	 * @param   array   $disabledFields     Fields need to be disabled
	 * @param   integer $width              Width in pixel
	 *
	 * @return  string   HTML of dropdown
	 * @throws  Exception
	 *
	 * @deprecated  2.0.0.3 Use RedshopHelperCategory::listAll() instead
	 */
	public function list_all($name, $categoryId, $selectedCategories = Array(), $size = 1, $toplevel = false,
	                         $multiple = false, $disabledFields = array(), $width = 250
	)
	{
		return RedshopHelperCategory::listAll($name, $categoryId, $selectedCategories, $size, $toplevel, $multiple, $disabledFields, $width);
	}

	/**
	 * List children of category into dropdown with level,
	 * this is a function will be called resursively.
	 *
	 * @param   string $categoryId         Exclude this category ID
	 * @param   string $cid                Parent category ID
	 * @param   string $level              Default is 0
	 * @param   array  $selectedCategories Only show selected categories
	 * @param   array  $disabledFields     Didable fields
	 * @param   string $html               Before HTML
	 *
	 * @return  string   HTML of <option></option>
	 *
	 * @deprecated  2.0.0.3 Use RedshopHelperCategory::listTree() instead
	 *
	 * @throws  Exception
	 */
	public function list_tree($categoryId = "", $cid = '0', $level = '0', $selectedCategories = Array(), $disabledFields = Array(), $html = '')
	{
		return RedshopHelperCategory::listTree($selectedCategories, $disabledFields);
	}

	/**
	 * Get Category List Array
	 *
	 * @param   int  $category_id  First category level in filter
	 * @param   int  $cid          Current category id
	 *
	 * @return  array|mixed
	 * @throws  Exception
	 *
	 * @deprecated  1.5 Use RedshopHelperCategory::getCategoryListArray instead
	 */
	public function getCategoryListArray($category_id = 1, $cid = 1)
	{
		return RedshopHelperCategory::getCategoryListArray($category_id, $cid);
	}

	/**
	 * Get Category List Reverse Array
	 *
	 * @param   string  $cid  Category id
	 *
	 * @return array
	 *
	 * @deprecated  1.5  Use RedshopHelperCategory::getCategoryListReverseArray instead
	 */
	public function getCategoryListReverceArray($cid = '0')
	{
		return RedshopHelperCategory::getCategoryListReverseArray($cid);
	}

	/**
	 * Build content order by user state from request
	 *
	 * @return  string
	 * @throws  Exception
	 *
	 * @deprecated  2.0.0.3 Use RedshopHelperCategory::buildContentOrderBy() instead
	 */
	public function _buildContentOrderBy()
	{
		return RedshopHelperCategory::buildContentOrderBy();
	}

	/**
	 * Get root parent categories
	 *
	 * @return object
	 *
	 * @deprecated  2.0.0.3 Use RedshopHelperCategory::getParentCategories() instead
	 */
	public function getParentCategories()
	{
		return RedshopHelperCategory::getParentCategories();
	}

	/**
	 * Get category tree
	 *
	 * @param   string  $cid  Category ID
	 *
	 * @return  array
	 *
	 * @deprecated  2.0.0.3 Use RedshopHelperCategory::getCategoryTree() instead
	 */
	public function getCategoryTree($cid = '0')
	{
		return RedshopHelperCategory::getCategoryTree($cid);
	}

	/**
	 * Get category product list
	 *
	 * @param   string  $cid  Category ID
	 *
	 * @return  array
	 *
	 * @deprecated  2.0.0.3 Use RedshopHelperCategory::getCategoryProductList() instead
	 */
	public function getCategoryProductList($cid)
	{
		return RedshopHelperCategory::getCategoryProductList($cid);
	}

	/**
	 * Check if Accessory is existed
	 *
	 * @param   integer  $productId    Product ID
	 * @param   integer  $accessoryId  Accessory ID
	 *
	 * @return integer
	 *
	 * @deprecated  2.0.0.3 Use RedshopHelperCategory::checkAccessoryExists() instead
	 */
	public function checkAccessoryExists($productId, $accessoryId)
	{
		return RedshopHelperAccessory::checkAccessoryExists($productId, $accessoryId);
	}
}
