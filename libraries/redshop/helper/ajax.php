<?php
/**
 * @package     Redshop.Libraries
 * @subpackage  Helpers
 *
 * @copyright   Copyright (C) 2008 - 2019 redCOMPONENT.com. All rights reserved.
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
	 * @deprecated  Use \Redshop\Helper\Ajax::isAjaxRequest()
	 *
	 * @return      boolean
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
	 * @deprecated  Use \Redshop\Helper\Ajax::validateAjaxRequest()
	 *
	 * @return  void
	 *
	 * @throws  Exception
	 */
	public static function validateAjaxRequest($method = 'post')
	{
		\Redshop\Helper\Ajax::validateAjaxRequest($method);
	}
}
