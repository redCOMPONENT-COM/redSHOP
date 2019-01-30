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
 * @since       2.0.10
 */
class RedshopUpdate2010 extends RedshopInstallUpdate
{
	/**
	 * Return list of old files for clean
	 *
	 * @return  array
	 *
	 * @since   2.0.10
	 */
	protected function getOldFiles()
	{
		return array(
			JPATH_ADMINISTRATOR . '/components/com_redshop/controllers/discount_detail.php',
			JPATH_ADMINISTRATOR . '/components/com_redshop/models/discount_detail.php',
			JPATH_ADMINISTRATOR . '/components/com_redshop/tables/discount_detail.php',
			JPATH_ADMINISTRATOR . '/components/com_redshop/views/discount/tmpl/product.php',
			JPATH_ADMINISTRATOR . '/components/com_redshop/views/mass_discount/tmpl/product.php',
			JPATH_LIBRARIES . '/redshop/entity/product_discount.php'
		);
	}

	/**
	 * Return list of old folders for clean
	 *
	 * @return  array
	 *
	 * @since   2.0.10
	 */
	protected function getOldFolders()
	{
		return array(
			JPATH_ADMINISTRATOR . '/components/com_redshop/views/discount_detail'
		);
	}
}
