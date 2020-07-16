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
            JPATH_ADMINISTRATOR . '/components/com_redshop/controllers/sample_detail.php',
            JPATH_ADMINISTRATOR . '/components/com_redshop/models/sample_detail.php',
            JPATH_ADMINISTRATOR . '/components/com_redshop/tables/sample_detail.php',
            JPATH_ADMINISTRATOR . '/components/com_redshop/views/sample_detail/tmpl/default.php',
            JPATH_ADMINISTRATOR . '/components/com_redshop/views/sample_detail/view.html.php',
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
            JPATH_ADMINISTRATOR . '/components/com_redshop/views/shipping_detail/view.html.php'
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
            JPATH_ADMINISTRATOR . '/components/com_redshop/views/shipping_detail'
        );
    }
}
