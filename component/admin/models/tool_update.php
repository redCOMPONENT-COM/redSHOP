<?php
/**
 * @package     RedSHOP.Backend
 * @subpackage  Model
 *
 * @copyright   Copyright (C) 2008 - 2019 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

defined('_JEXEC') or die;

/**
 * Model Tool Update
 *
 * @package     RedSHOP.Backend
 * @subpackage  Model
 * @since       2.1.0
 */
class RedshopModelTool_Update extends RedshopModelList
{
	/**
	 * Method for get all available version of installation.
	 *
	 * @return  array
	 *
	 * @since   2.1.0
	 */
	public function getAvailableUpdate()
	{
		return RedshopInstall::loadUpdateTasks();
	}
}
