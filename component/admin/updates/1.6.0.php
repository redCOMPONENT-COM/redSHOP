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
 * @since       1.6.0
 */
class RedshopUpdate160 extends RedshopInstallUpdate
{
	/**
	 * Return list of old files for clean
	 *
	 * @return  array
	 *
	 * @since   1.6.0
	 */
	protected function getOldFiles()
	{
		return array(
			JPATH_SITE . '/components/com_redshop/assets/download/product/.htaccess',
			JPATH_ADMINISTRATOR . '/components/com_redshop/tables/navigator_detail.php',
			JPATH_ADMINISTRATOR . '/components/com_redshop/views/product_detail/tmpl/default_product_dropdown.php',
			JPATH_SITE . '/components/com_redshop/views/category/tmpl/searchletter.php',
			JPATH_ADMINISTRATOR . '/components/com_redshop/controllers/container.php',
			JPATH_ADMINISTRATOR . '/components/com_redshop/controllers/container_detail.php',
			JPATH_ADMINISTRATOR . '/components/com_redshop/controllers/customprint.php',
			JPATH_ADMINISTRATOR . '/components/com_redshop/controllers/delivery.php',
			JPATH_ADMINISTRATOR . '/components/com_redshop/controllers/order_container.php',
			JPATH_ADMINISTRATOR . '/components/com_redshop/controllers/payment.php',
			JPATH_ADMINISTRATOR . '/components/com_redshop/controllers/payment_detail.php',
			JPATH_ADMINISTRATOR . '/components/com_redshop/controllers/product_container.php',
			JPATH_ADMINISTRATOR . '/components/com_redshop/helpers/subinstall.php',
			JPATH_ADMINISTRATOR . '/components/com_redshop/models/container.php',
			JPATH_ADMINISTRATOR . '/components/com_redshop/models/container_detail.php',
			JPATH_ADMINISTRATOR . '/components/com_redshop/models/order_container.php',
			JPATH_ADMINISTRATOR . '/components/com_redshop/models/payment.php',
			JPATH_ADMINISTRATOR . '/components/com_redshop/models/payment_detail.php',
			JPATH_ADMINISTRATOR . '/components/com_redshop/models/product_container.php',
			JPATH_ADMINISTRATOR . '/components/com_redshop/tables/container_detail.php',
			JPATH_ADMINISTRATOR . '/components/com_redshop/tables/payment_detail.php',
			JPATH_ADMINISTRATOR . '/components/com_redshop/views/accessmanager/tmpl/noaccess.php',
			JPATH_ADMINISTRATOR . '/components/com_redshop/views/order/tmpl/multiprint_order.php',
			JPATH_ADMINISTRATOR . '/components/com_redshop/views/order/tmpl/previewlog.php',
			JPATH_ADMINISTRATOR . '/components/com_redshop/views/stockroom_detail/tmpl/default_product.php',
			JPATH_ADMINISTRATOR . '/components/com_redshop/assets/js/select_sort.js',
			JPATH_ADMINISTRATOR . '/components/com_redshop/assets/js/related.js',
			JPATH_ADMINISTRATOR . '/components/com_redshop/assets/js/container_search.js',
			JPATH_ADMINISTRATOR . '/components/com_redshop/assets/js/mootools.js',
			JPATH_ADMINISTRATOR . '/components/com_redshop/assets/images/redshop_white.png',
			JPATH_ADMINISTRATOR . '/components/com_redshop/assets/images/j_arrow.png',
			JPATH_ADMINISTRATOR . '/components/com_redshop/assets/images/j_arrow_down.png',
			JPATH_ADMINISTRATOR . '/components/com_redshop/assets/images/ui-icons_222222_256x240.png',
			JPATH_ADMINISTRATOR . '/components/com_redshop/assets/images/ui-icons_228ef1_256x240.png',
			JPATH_SITE . '/components/com_redshop/controllers/password.php',
			JPATH_SITE . '/components/com_redshop/controllers/price_filter.php',
			JPATH_SITE . '/components/com_redshop/helpers/class.img2thumb.php',
			JPATH_SITE . '/components/com_redshop/helpers/graph.php',
			JPATH_SITE . '/components/com_redshop/helpers/pagination.php',
			JPATH_SITE . '/components/com_redshop/helpers/thumb.php',
			JPATH_SITE . '/components/com_redshop/models/password.php',
			JPATH_SITE . '/components/com_redshop/views/price_filter/view.html.php',
			JPATH_SITE . '/components/com_redshop/views/product/tmpl/default_askquestion.php',
			JPATH_LIBRARIES . '/redshop/form/fields/rstext.php'
		);
	}

	/**
	 * Return list of old folders for clean
	 *
	 * @return  array
	 *
	 * @since   1.6.0
	 */
	protected function getOldFolders()
	{
		return array(
			JPATH_SITE . '/components/com_redshop/assets/js',
			JPATH_SITE . '/components/com_redshop/assets/css',
			JPATH_SITE . '/components/com_redshop/helpers/fonts',
			JPATH_SITE . '/components/com_redshop/helpers/tcpdf',
			JPATH_SITE . '/components/com_redshop/views/epayrelay',
			JPATH_SITE . '/components/com_redshop/views/password',
			JPATH_ADMINISTRATOR . '/components/com_redshop/models/adapters',
			JPATH_ADMINISTRATOR . '/components/com_redshop/views/container',
			JPATH_ADMINISTRATOR . '/components/com_redshop/views/container_detail',
			JPATH_ADMINISTRATOR . '/components/com_redshop/views/customprint',
			JPATH_ADMINISTRATOR . '/components/com_redshop/views/delivery',
			JPATH_ADMINISTRATOR . '/components/com_redshop/views/payment',
			JPATH_ADMINISTRATOR . '/components/com_redshop/views/payment_detail',
			JPATH_ADMINISTRATOR . '/components/com_redshop/views/product_container',
			JPATH_ADMINISTRATOR . '/components/com_redshop/layouts/system'
		);
	}
}
