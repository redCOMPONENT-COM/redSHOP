<?php
/**
 * @package     RedSHOP.Backend
 * @subpackage  Updates
 *
 * @copyright   Copyright (C) 2008 - 2017 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

defined('_JEXEC') or die;

/**
 * Update class
 *
 * @package     Redshob.Update
 *
 * @since       2.0.6-Beta1
 */
class RedshopUpdate206Beta1 extends RedshopInstallUpdate
{
	/**
	 * Method clean old files
	 *
	 * @return  void
	 *
	 * @since   2.0.6-Beta1
	 */
	public function cleanOldFiles()
	{
		$folders = array(
			JPATH_ADMINISTRATOR . '/components/com_redshop/views/accessmanager',
			JPATH_ADMINISTRATOR . '/components/com_redshop/views/accessmanager_detail'
		);

		$this->deleteFolders($folders);

		$files = array(
			JPATH_ADMINISTRATOR . '/components/com_redshop/controllers/accessmanager.php',
			JPATH_ADMINISTRATOR . '/components/com_redshop/controllers/accessmanager_detail.php',
			JPATH_ADMINISTRATOR . '/components/com_redshop/helpers/redaccesslevel.php',
			JPATH_ADMINISTRATOR . '/components/com_redshop/models/accessmanager.php',
			JPATH_ADMINISTRATOR . '/components/com_redshop/models/accessmanager_detail.php',
			JPATH_ADMINISTRATOR . '/components/com_redshop/tables/accessmanager_detail.php'
		);

		$this->deleteFiles($files);
	}
}
