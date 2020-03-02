<?php
/**
 * @package     RedSHOP.Backend
 * @subpackage  Updates
 *
 * @copyright   Copyright (C) 2008 - 2020 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

defined('_JEXEC') or die;

/**
 * Update class
 *
 * @package     Redshob.Update
 *
 * @since       2.0.15
 */
class RedshopUpdate2015 extends RedshopInstallUpdate
{
	/**
	 * Return list of old files for clean
	 *
	 * @return  array
	 *
	 * @since   2.0.15
	 */
	protected function getOldFiles()
	{
		return array(
			JPATH_ADMINISTRATOR . '/components/com_redshop/controllers/manufacturer_detail.php',
			JPATH_ADMINISTRATOR . '/components/com_redshop/models/manufacturer_detail.php',
			JPATH_ADMINISTRATOR . '/components/com_redshop/tables/manufacturer_detail.php',
			JPATH_ADMINISTRATOR . '/components/com_redshop/views/manufacturer/tmpl/default.php'
		);
	}

	/**
	 * Return list of old folders for clean
	 *
	 * @return  array
	 *
	 * @since   2.0.15
	 */
	protected function getOldFolders()
	{
		return array(
			JPATH_ADMINISTRATOR . '/components/com_redshop/views/manufacturer_detail'
		);
	}
}
