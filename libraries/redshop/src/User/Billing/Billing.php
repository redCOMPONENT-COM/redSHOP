<?php
/**
 * @package     RedShop
 * @subpackage  Helper
 *
 * @copyright   Copyright (C) 2008 - 2019 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

namespace Redshop\User\Billing;

defined('_JEXEC') or die;

/**
 * User Billing
 *
 * @since  2.1.0
 */
class Billing
{
	/**
	 * @var string
	 */
	protected static $key = 'redshop.billing_address';

	/**
	 * Method for get global stored billing address
	 *
	 * @return  object
	 *
	 * @since   2.1.0
	 */
	public static function getGlobal()
	{
		return \JFactory::getSession()->get(self::$key, null);
	}

	/**
	 * Method for get global stored billing address
	 *
	 * @param   mixed  $data  Billing address data.
	 *
	 * @return  void
	 *
	 * @since   2.1.0
	 */
	public static function setGlobal($data = null)
	{
		\JFactory::getSession()->set(self::$key, $data);
	}
}
