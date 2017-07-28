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
 * @since       __DEPLOY_VERSION__
 */
class RedshopUpdate207Beta2 extends RedshopInstallUpdate
{
	/**
	 * Return list of old files for clean
	 *
	 * @return  array
	 *
	 * @since   __DEPLOY_VERSION__
	 */
	protected function getOldFiles()
	{
		return array(
			JPATH_ADMINISTRATOR . '/components/com_redshop/controllers/accessmanager.php',
			JPATH_ADMINISTRATOR . '/components/com_redshop/controllers/accessmanager_detail.php',
			JPATH_ADMINISTRATOR . '/components/com_redshop/helpers/redaccesslevel.php',
			JPATH_ADMINISTRATOR . '/components/com_redshop/helpers/thumbnail.php',
			JPATH_ADMINISTRATOR . '/components/com_redshop/models/accessmanager.php',
			JPATH_ADMINISTRATOR . '/components/com_redshop/models/accessmanager_detail.php',
			JPATH_ADMINISTRATOR . '/components/com_redshop/tables/accessmanager_detail.php',
			JPATH_ADMINISTRATOR . '/components/com_redshop/controllers/category_detail.php',
			JPATH_ADMINISTRATOR . '/components/com_redshop/models/category_detail.php',
			JPATH_ADMINISTRATOR . '/components/com_redshop/controllers/fields_detail.php',
			JPATH_ADMINISTRATOR . '/components/com_redshop/models/fields_detail.php',
			JPATH_ADMINISTRATOR . '/components/com_redshop/tables/fields_detail.php',
			JPATH_ADMINISTRATOR . '/components/com_redshop/views/configuration/tmpl/default_analytics.php'
		);
	}
}
