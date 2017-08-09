<?php
/**
 * @package     RedSHOP.Library
 * @subpackage  Helper
 *
 * @copyright   Copyright (C) 2008 - 2017 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 *
 * @since       2.0.3
 */
defined('_JEXEC') or die;

/**
 * Class Redshop Helper for Cart - Session
 *
 * @since 2.0.7
 */
class RedshopHelperCartSession
{
	/**
	 * Get cart from session
	 *
	 * @return   boolean|array
	 *
	 * @since    2.0.7
	 */
	public static function getCart()
	{
		return JFactory::getSession()->get('cart', array());
	}

	/**
	 * @param   array $cart Cart array
	 *
	 * @return  boolean|array
	 *
	 * @since   2.0.7
	 */
	public static function setCart($cart)
	{
		return JFactory::getSession()->set('cart', $cart);
	}

	/**
	 * Reset cart session
	 *
	 * @return  void
	 *
	 * @since   2.0.7
	 */
	public static function reset()
	{
		self::setCart(array());
	}
}
