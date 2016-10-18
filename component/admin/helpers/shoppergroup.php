<?php
/**
 * @package     RedSHOP.Backend
 * @subpackage  Helper
 *
 * @copyright   Copyright (C) 2008 - 2016 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 *
 * @deprecated  __DEPLOY_VERSION__  Use RedshopHelperShopperGroup instead
 */

defined('_JEXEC') or die;

/**
 * Class Redshop Helper for Shopper Group
 *
 * @deprecated  __DEPLOY_VERSION__  Use RedshopHelperShopperGroup instead
 */
class shoppergroup
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
	 * @deprecated  __DEPLOY_VERSION__  Use RedshopHelperShopperGroup::listAll() instead
	 */
	public function list_all($name, $shopper_group_id, $selected_groups = Array(), $size = 1, $toplevel = true, $multiple = false, $disabledFields = array())
	{
		return RedshopHelperShopperGroup::listAll($name, $shopper_group_id, $selected_groups, $size, $toplevel, $multiple, $disabledFields);
	}

	/**
	 * List shopper group as option of dropdown list
	 *
	 * @param   string  $shopper_group_id  Shopper group ID to display
	 * @param   string  $cid               Parent ID
	 * @param   string  $level             Position
	 * @param   array   $selected_groups   Selected groups will be marked selected
	 * @param   array   $disabledFields    Disable groups
	 * @param   string  $html              Previous HTML
	 *
	 * @return  string  HTML to render <option></option>
	 *
	 * @deprecated  __DEPLOY_VERSION__  Use RedshopHelperShopperGroup::listTree() instead
	 */
	public function list_tree($shopper_group_id = "", $cid = '0', $level = '0', $selected_groups = Array(), $disabledFields = Array(), $html = '')
	{
		return RedshopHelperShopperGroup::listTree($shopper_group_id, $cid, $level, $selected_groups, $disabledFields, $html);
	}

	/**
	 * Get Shopper Group List as Array
	 *
	 * @param   string  $shopper_group_id  Shopper Group ID to display
	 * @param   string  $cid               Parent ID
	 * @param   string  $level             Position
	 *
	 * @return array
	 *
	 * @deprecated  __DEPLOY_VERSION__  Use RedshopHelperShopperGroup::getShopperGroupListArray() instead
	 */
	public function getshopperGroupListArray($shopper_group_id = "", $cid = '0', $level = '0')
	{
		return RedshopHelperShopperGroup::getShopperGroupListArray($shopper_group_id, $cid, $level);
	}

	/**
	 * Get Category List Reverce Array
	 *
	 * @param   string  $cid  Parent ID
	 *
	 * @return  array
	 *
	 * @deprecated  __DEPLOY_VERSION__  Use RedshopHelperShopperGroup::getCategoryListReverceArray() instead
	 */
	public function getCategoryListReverceArray($cid = '0')
	{
		return RedshopHelperShopperGroup::getCategoryListReverceArray($cid);
	}
}
