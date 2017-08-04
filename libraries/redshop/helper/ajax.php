<?php
/**
 * @package     Redshop.Libraries
 * @subpackage  Helpers
 *
 * @copyright   Copyright (C) 2008 - 2017 redCOMPONENT.com. All rights reserved.
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
	 * @return  boolean
	 */
	public static function isAjaxRequest()
	{
		return (isset($_SERVER['HTTP_X_REQUESTED_WITH'])
			&& strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest');
	}

	/**
	 * Verify that an AJAX request has been received
	 *
	 * @param   string  $method  Method to validate the ajax request
	 *
	 * @return  void
	 *
	 * @throws  Exception
	 */
	public static function validateAjaxRequest($method = 'post')
	{
		if (!JSession::checkToken($method) || !static::isAjaxRequest())
		{
			throw new Exception(JText::_('JLIB_APPLICATION_ERROR_ACCESS_FORBIDDEN'), 403);
		}
	}
}
