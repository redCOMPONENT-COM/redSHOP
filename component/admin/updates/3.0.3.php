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
 * @since       __DEPLOY_VERSION__
 */
class RedshopUpdate303 extends RedshopInstallUpdate
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
            // Sample
            JPATH_ADMINISTRATOR . '/components/com_redshop/controllers/sample_detail.php',
            JPATH_ADMINISTRATOR . '/components/com_redshop/models/sample_detail.php',
            JPATH_ADMINISTRATOR . '/components/com_redshop/tables/sample_detail.php',
            JPATH_ADMINISTRATOR . '/components/com_redshop/views/sample_detail/tmpl/default.php',
            JPATH_ADMINISTRATOR . '/components/com_redshop/views/sample_detail/view.html.php',
            // Rating
            JPATH_ADMINISTRATOR . '/components/com_redshop/controllers/rating_detail.php',
            JPATH_ADMINISTRATOR . '/components/com_redshop/models/rating_detail.php',
            JPATH_ADMINISTRATOR . '/components/com_redshop/tables/rating_detail.php',
            JPATH_ADMINISTRATOR . '/components/com_redshop/views/rating_detail/tmpl/default.php',
            JPATH_ADMINISTRATOR . '/components/com_redshop/views/rating_detail/view.html.php',
            JPATH_ADMINISTRATOR . '/components/com_redshop/controllers/shipping.php',
            JPATH_ADMINISTRATOR . '/components/com_redshop/controllers/shipping_detail.php',
            JPATH_ADMINISTRATOR . '/components/com_redshop/models/shipping.php',
            JPATH_ADMINISTRATOR . '/components/com_redshop/models/shipping_detail.php',
            JPATH_ADMINISTRATOR . '/components/com_redshop/views/shipping/tmpl/default.php',
            JPATH_ADMINISTRATOR . '/components/com_redshop/views/shipping/view.html.php',
            JPATH_ADMINISTRATOR . '/components/com_redshop/views/shipping_detail/tmpl/default.php',
            JPATH_ADMINISTRATOR . '/components/com_redshop/views/shipping_detail/view.html.php',

	        // Wrapper
	        JPATH_ADMINISTRATOR . '/components/com_redshop/controllers/wrapper_detail.php',
	        JPATH_ADMINISTRATOR . '/components/com_redshop/models/wrapper_detail.php',
	        JPATH_ADMINISTRATOR . '/components/com_redshop/tables/wrapper_detail.php',
	        JPATH_ADMINISTRATOR . '/components/com_redshop/views/wrapper/tmpl/default.php',
	        JPATH_ADMINISTRATOR . '/components/com_redshop/views/wrapper/tmpl/index.html',
	        JPATH_ADMINISTRATOR . '/components/com_redshop/views/wrapper_detail/tmpl/default.php',
	        JPATH_ADMINISTRATOR . '/components/com_redshop/views/wrapper_detail/view.html.php',

            // Newsletter
            JPATH_ADMINISTRATOR . '/components/com_redshop/controllers/newsletter_detail.php',
            JPATH_ADMINISTRATOR . '/components/com_redshop/models/newsletter_detail.php',
            JPATH_ADMINISTRATOR . '/components/com_redshop/tables/newsletter_detail.php',
            JPATH_ADMINISTRATOR . '/components/com_redshop/views/newsletter_detail/tmpl/default.php',
            JPATH_ADMINISTRATOR . '/components/com_redshop/views/newsletter_detail/view.html.php',
            JPATH_ADMINISTRATOR . '/components/com_redshop/views/newsletter_detail/view.html.php',

	        // Stockroom
	        JPATH_ADMINISTRATOR . '/component/admin/controllers/stockroom_detail.php',
	        JPATH_ADMINISTRATOR . '/component/admin/models/stockroom_detail.php',
	        JPATH_ADMINISTRATOR . '/component/admin/tables/stockroom_detail.php',
	        JPATH_ADMINISTRATOR . '/component/admin/views/stockroom_detail/tmpl/default.php',
	        JPATH_ADMINISTRATOR . '/component/admin/views/stockroom_detail/tmpl/index.html',
	        JPATH_ADMINISTRATOR . '/component/admin/views/stockroom_detail/view.html.php',
	        JPATH_ADMINISTRATOR . '/component/admin/views/stockroom_detail/index.html',

            // Shopper Group
            JPATH_ADMINISTRATOR . '/components/com_redshop/controllers/shopper_group.php',
            JPATH_ADMINISTRATOR . '/components/com_redshop/controllers/shopper_group_detail.php',
            JPATH_ADMINISTRATOR . '/components/com_redshop/models/shopper_group.php',
            JPATH_ADMINISTRATOR . '/components/com_redshop/models/shopper_group_detail.php',
            JPATH_ADMINISTRATOR . '/components/com_redshop/tables/shopper_group_detail.php',
            JPATH_ADMINISTRATOR . '/components/com_redshop/views/shopper_group/tmpl/default.php',
            JPATH_ADMINISTRATOR . '/components/com_redshop/views/shopper_group/view.html.php',
            JPATH_ADMINISTRATOR . '/components/com_redshop/views/shopper_group_detail/tmpl/default.php',
            JPATH_ADMINISTRATOR . '/components/com_redshop/views/shopper_group_detail/view.html.php',
        );
    }

    /**
     * Return list of old folders for clean
     *
     * @return  array
     *
     * @since   __DEPLOY_VERSION__
     */
    protected function getOldFolders()
    {
        return array(
            JPATH_ADMINISTRATOR . '/components/com_redshop/views/sample_detail',
            JPATH_ADMINISTRATOR . '/components/com_redshop/views/rating_detail',
            JPATH_ADMINISTRATOR . '/components/com_redshop/views/shipping',
            JPATH_ADMINISTRATOR . '/components/com_redshop/views/shipping_detail',
	        JPATH_ADMINISTRATOR . '/components/com_redshop/views/wrapper_detail',
            JPATH_ADMINISTRATOR . '/components/com_redshop/views/newsletter_detail',
	        JPATH_ADMINISTRATOR . '/components/com_redshop/views/stockroom_detail',
            JPATH_ADMINISTRATOR . '/components/com_redshop/views/shopper_group',
            JPATH_ADMINISTRATOR . '/components/com_redshop/views/shopper_group_detail',
        );
    }
}
