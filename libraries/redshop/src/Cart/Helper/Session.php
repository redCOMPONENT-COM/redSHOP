<?php
/**
 * @package     Redshop\Cart\Helper
 * @subpackage
 *
 * @copyright   A copyright
 * @license     A "Slug" license name e.g. GPL2
 */

namespace Redshop\Cart\Helper;

class Session
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
		return \JFactory::getSession()->get('cart', array());
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
		return \JFactory::getSession()->set('cart', $cart);
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
