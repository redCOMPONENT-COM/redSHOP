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
 * @since       2.0.0.5
 */
class RedshopUpdate2005 extends RedshopInstallUpdate
{
	/**
	 * Return list of old files for clean
	 *
	 * @return  array
	 *
	 * @since   2.0.0.5
	 */
	protected function getOldFiles()
	{
		return array(
			JPATH_ADMINISTRATOR . '/component/admin/controllers/question_detail.php',
			JPATH_ADMINISTRATOR . '/component/admin/models/question_detail.php',
			JPATH_ADMINISTRATOR . '/component/admin/tables/question_detail.php',
			JPATH_ADMINISTRATOR . '/component/admin/views/question/tmpl/default.php'
		);
	}

	/**
	 * Return list of old folders for clean
	 *
	 * @return  array
	 *
	 * @since   2.0.0.5
	 */
	protected function getOldFolders()
	{
		return array(
			JPATH_ADMINISTRATOR . '/component/admin/views/question_detail'
		);
	}
}
