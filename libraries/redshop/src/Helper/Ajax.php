<?php
/**
 * @package     RedShop
 * @subpackage  Libraries
 *
 * @copyright   Copyright (C) 2008 - 2019 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

namespace Redshop\Helper;

defined('_JEXEC') or die;

/**
 * Ajax helper class
 *
 * @since  2.1.0
 */
class Ajax
{
	/**
	 * Check if we have received an AJAX request for security reasons
	 *
	 * @return  boolean
	 *
	 * @since  2.1.0
	 */
	public static function isAjaxRequest()
	{
		return (isset($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) === 'xmlhttprequest');
	}

	/**
	 * Verify that an AJAX request has been received
	 *
	 * @param   string  $method  Method to validate the ajax request
	 *
	 * @return  void
	 *
	 * @since   2.1.0
	 *
	 * @throws  \Exception
	 */
	public static function validateAjaxRequest($method = 'post')
	{
		if (!\JSession::checkToken($method) || !static::isAjaxRequest())
		{
			throw new \Exception(\JText::_('JLIB_APPLICATION_ERROR_ACCESS_FORBIDDEN'), 403);
		}
	}
}