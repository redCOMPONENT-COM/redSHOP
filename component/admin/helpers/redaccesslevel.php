<?php
/**
 * @package     RedSHOP.Backend
 * @subpackage  Helper
 *
 * @copyright   Copyright (C) 2008 - 2016 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 *
 * @deprecated  __DEPLOY_VERSION__  Use RedshopHelperAccess instead
 */

defined('_JEXEC') or die;

/**
 * Class Redshop Helper for Access Level
 *
 * @deprecated  __DEPLOY_VERSION__  Use RedshopHelperAccess instead
 */
class Redaccesslevel
{
	/**
	 * define default path
	 *
	 */
	public function __construct()
	{
		$this->_table_prefix = '#__redshop_';
	}

	/**
	 * Check access level of an user
	 *
	 * @param   integer  $group_id  Group ID of an user
	 *
	 * @return  array
	 *
	 * @deprecated  __DEPLOY_VERSION__  Use RedshopHelperAccess::checkAccessOfUser() instead
	 */
	public function checkaccessofuser($group_id)
	{
		return RedshopHelperAccess::checkAccessOfUser($group_id);
	}

	/**
	 * Check access level of a group users
	 *
	 * @param   string   $view      View name
	 * @param   string   $task      Have 3 options: add/ edit/ remove
	 * @param   integer  $group_id  Group ID
	 *
	 * @return  void
	 *
	 * @deprecated  __DEPLOY_VERSION__  Use RedshopHelperAccess::checkGroupAccess() instead
	 */
	public function checkgroup_access($view, $task, $group_id)
	{
		return RedshopHelperAccess::checkGroupAccess($view, $task, $group_id);
	}

	/**
	 * Get access level of group users
	 *
	 * @param   string   $view      View name
	 * @param   integer  $group_id  Group ID
	 *
	 * @return  void
	 *
	 * @deprecated  __DEPLOY_VERSION__  Use RedshopHelperAccess::getGroupAccess() instead
	 */
	public function getgroup_access($view, $group_id)
	{
		return RedshopHelperAccess::getGroupAccess($view, $group_id);
	}

	/**
	 * Get access level of group add users
	 *
	 * @param   string   $view      View name
	 * @param   integer  $group_id  Group ID
	 *
	 * @return  void
	 *
	 * @deprecated  __DEPLOY_VERSION__  Use RedshopHelperAccess::getGroupAccessTaskAdd() instead
	 */
	public function getgroup_accesstaskadd($view, $group_id)
	{
		return RedshopHelperAccess::getGroupAccessTaskAdd($view, $group_id);
	}

	/**
	 * Get access level of group edit users
	 *
	 * @param   string   $view      View name
	 * @param   integer  $group_id  Group ID
	 *
	 * @return  void
	 *
	 * @deprecated  __DEPLOY_VERSION__  Use RedshopHelperAccess::getGroupAccessTaskEdit() instead
	 */
	public function getgroup_accesstaskedit($view, $group_id)
	{
		return RedshopHelperAccess::getGroupAccessTaskEdit($view, $group_id);
	}

	/**
	 * Get access level of group delete users
	 *
	 * @param   string   $view      View name
	 * @param   integer  $group_id  Group ID
	 *
	 * @return  void
	 *
	 * @deprecated  __DEPLOY_VERSION__  Use RedshopHelperAccess::getGroupAccessTaskDelete() instead
	 */
	public function getgroup_accesstaskdelete($view, $group_id)
	{
		return RedshopHelperAccess::getGroupAccessTaskDelete($view, $group_id);
	}
}
