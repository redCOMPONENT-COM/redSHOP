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
 * @since       2.0.0.2
 */
class RedshopUpdate2002 extends RedshopInstallUpdate
{
	/**
	 * Return list of old files for clean
	 *
	 * @return  array
	 *
	 * @since   2.0.0.2
	 */
	protected function getOldFiles()
	{
		return array(
			JPATH_SITE . '/components/com_redshop/helpers/helper.php',
			JPATH_SITE . '/components/com_redshop/helpers/currency.php',
			JPATH_SITE . '/components/com_redshop/helpers/product.php',
			JPATH_SITE . '/components/com_redshop/helpers/cart.php',
			JPATH_SITE . '/components/com_redshop/helpers/user.php',
			JPATH_SITE . '/components/com_redshop/views/search/tmpl/default.xml',
			JPATH_SITE . '/components/com_redshop/helpers/extra_field.php',
			JPATH_SITE . '/components/com_redshop/helpers/google_analytics.php',
			JPATH_SITE . '/components/com_redshop/helpers/googleanalytics.php',
			JPATH_SITE . '/components/com_redshop/helpers/zip.php',
			JPATH_SITE . '/components/com_redshop/helpers/cron.php',
			JPATH_SITE . '/components/com_redshop/helpers/redshop.js.php',
			JPATH_SITE . '/components/com_redshop/helpers/zipfile.php',
			JPATH_ADMINISTRATOR . '/components/com_redshop/controllers/answer.php',
			JPATH_ADMINISTRATOR . '/components/com_redshop/controllers/answer_detail.php',
			JPATH_ADMINISTRATOR . '/components/com_redshop/models/answer.php',
			JPATH_ADMINISTRATOR . '/components/com_redshop/models/answer_detail.php',
			JPATH_ADMINISTRATOR . '/components/com_redshop/helpers/access_level.php',
			JPATH_ADMINISTRATOR . '/components/com_redshop/helpers/category.php',
			JPATH_ADMINISTRATOR . '/components/com_redshop/helpers/configuration.php',
			JPATH_ADMINISTRATOR . '/components/com_redshop/helpers/images.php',
			JPATH_ADMINISTRATOR . '/components/com_redshop/helpers/mail.php',
			JPATH_ADMINISTRATOR . '/components/com_redshop/helpers/media.php',
			JPATH_ADMINISTRATOR . '/components/com_redshop/helpers/order.php',
			JPATH_ADMINISTRATOR . '/components/com_redshop/helpers/quotation.php',
			JPATH_ADMINISTRATOR . '/components/com_redshop/helpers/stockroom.php',
			JPATH_ADMINISTRATOR . '/components/com_redshop/helpers/template.php',
			JPATH_ADMINISTRATOR . '/components/com_redshop/helpers/update.php',
			JPATH_ADMINISTRATOR . '/components/com_redshop/helpers/shopper.php',
			JPATH_ADMINISTRATOR . '/components/com_redshop/helpers/xmlcron.php',
			JPATH_LIBRARIES . '/redshop/form/fields/stockroom.php'
		);
	}

	/**
	 * Return list of old folders for clean
	 *
	 * @return  array
	 *
	 * @since   2.0.0.2
	 */
	protected function getOldFolders()
	{
		return array(
			JPATH_LIBRARIES . '/redshop/config',
			JPATH_ADMINISTRATOR . '/components/com_redshop/views/answer',
			JPATH_ADMINISTRATOR . '/components/com_redshop/views/answer_detail',
			JPATH_ADMINISTRATOR . '/components/com_redshop/views/barcode'
		);
	}
}
