<?php
/**
 * @package     Redshop.Libraries
 * @subpackage  Helpers
 *
 * @copyright   Copyright (C) 2008 - 2020 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

defined('_JEXEC') or die;

/**
 * Redshop Ajax Helper
 *
 * @package     Redshop.Libraries
 * @subpackage  Helpers
 * @since       1.6.1
 */
abstract class RedshopHelperAjax
{
    /**
     * Check if we have received an AJAX request for security reasons
     *
     * @return      boolean
     * @deprecated  Use \Redshop\Helper\Ajax::isAjaxRequest()
     *
     */
    public static function isAjaxRequest()
    {
        return \Redshop\Helper\Ajax::isAjaxRequest();
    }

    /**
     * Verify that an AJAX request has been received
     *
     * @param   string  $method  Method to validate the ajax request
     *
     * @return  void
     *
     * @throws  Exception
     * @deprecated  Use \Redshop\Helper\Ajax::validateAjaxRequest()
     *
     */
    public static function validateAjaxRequest($method = 'post')
    {
        \Redshop\Helper\Ajax::validateAjaxRequest($method);
    }
}
