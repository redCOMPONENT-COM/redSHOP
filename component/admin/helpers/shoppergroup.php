<?php
/**
 * @package     RedSHOP.Backend
 * @subpackage  Helper
 *
 * @copyright   Copyright (C) 2008 - 2017 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 *
 * @deprecated  2.0.3  Use RedshopHelperShopper_Group instead
 */

defined('_JEXEC') or die;

/**
 * Class Redshop Helper for Shopper Group
 *
 * @since       1.6
 *
 * @deprecated  2.0.3  Use RedshopHelperShopper_Group instead
 */
class
shoppergroup
{
	/**
	 * List all shopper group as dropdown list
	 *
	 * @param   string   $name              Name of dropdown list
	 * @param   integer  $shopper_group_id  ID of shopper group to display
	 * @param   array    $selected_groups   Array of selected group
	 * @param   integer  $size              Size of dropdown list
	 * @param   boolean  $toplevel          Position align from top
	 * @param   boolean  $multiple          Is multiple select or not
	 * @param   array    $disabledFields    Disable some groups
	 *
	 * @return string    HTML of dropdown list to render
	 *
	 * @deprecated  2.0.3  Use RedshopHelperShopper_Group::listAll() instead
	 */
	public function list_all($name, $shopper_group_id, $selected_groups = Array(), $size = 1, $toplevel = true, $multiple = false,
		$disabledFields = array())
	{
		return RedshopHelperShopper_Group::listAll($name, $shopper_group_id, $selected_groups, $size, $toplevel, $multiple, $disabledFields);
	}

	/**
	 * List shopper group as option of dropdown list
	 *
	 * @param   integer  $shopper_group_id  Shopper group ID to display
	 * @param   integer  $cid               Parent ID
	 * @param   integer  $level             Position
	 * @param   array    $selected_groups   Selected groups will be marked selected
	 * @param   array    $disabledFields    Disable groups
	 * @param   string   $html              Previous HTML
	 *
	 * @return  string  HTML to render <option></option>
	 *
	 * @deprecated  2.0.3  Use RedshopHelperShopper_Group::listTree() instead
	 */
	public function list_tree($shopper_group_id = 0, $cid = 0, $level = 0, $selected_groups = array(), $disabledFields = array(), $html = '')
	{
		return RedshopHelperShopper_Group::listTree($shopper_group_id, $cid, $level, $selected_groups, $disabledFields, $html);
	}

	/**
	 * Get Shopper Group List as Array
	 *
	 * @param   integer  $shopper_group_id  Shopper Group ID to display
	 * @param   integer  $cid               Parent ID
	 * @param   integer  $level             Position
	 *
	 * @return array
	 *
	 * @deprecated  2.0.3  Use RedshopHelperShopper_Group::getShopperGroupListArray() instead
	 */
	public function getshopperGroupListArray($shopper_group_id = "", $cid = 0, $level = 0)
	{
		return RedshopHelperShopper_Group::getShopperGroupListArray($shopper_group_id, $cid, $level);
	}

	/**
	 * Get Category List Reverce Array
	 *
	 * @param   integer  $cid  Parent ID
	 *
	 * @return  array
	 *
	 * @deprecated  2.0.3  Use RedshopHelperShopper_Group::getCategoryListReverceArray() instead
	 */
	public function getCategoryListReverceArray($cid = 0)
	{
		return RedshopHelperShopper_Group::getCategoryListReverceArray($cid);
	}
}
