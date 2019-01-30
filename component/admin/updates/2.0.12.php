<?php
/**
 * @package     RedSHOP.Backend
 * @subpackage  Updates
 *
 * @copyright   Copyright (C) 2008 - 2019 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

defined('_JEXEC') or die;

/**
 * Update class
 *
 * @package     Redshob.Update
 *
 * @since       2.0.12
 */
class RedshopUpdate2012 extends RedshopInstallUpdate
{
	/**
	 * Return list of old files for clean
	 *
	 * @return  array
	 *
	 * @since   2.0.11
	 */
	protected function getOldFiles()
	{
		return array(
			JPATH_ADMINISTRATOR . '/components/com_redshop/controllers/textlibrary.php',
			JPATH_ADMINISTRATOR . '/components/com_redshop/controllers/textlibrary_detail.php',
			JPATH_ADMINISTRATOR . '/components/com_redshop/models/textlibrary.php',
			JPATH_ADMINISTRATOR . '/components/com_redshop/models/textlibrary_detail.php',
			JPATH_ADMINISTRATOR . '/components/com_redshop/tables/textlibrary_detail.php'
		);
	}

	/**
	 * Return list of old folders for clean
	 *
	 * @return  array
	 *
	 * @since   2.0.11
	 */
	protected function getOldFolders()
	{
		return array(
			JPATH_ADMINISTRATOR . '/components/com_redshop/views/textlibrary',
			JPATH_ADMINISTRATOR . '/components/com_redshop/views/textlibrary_detail'
		);
	}
}
