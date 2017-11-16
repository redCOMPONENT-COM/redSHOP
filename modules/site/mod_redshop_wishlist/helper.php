<?php
/**
 * @package     RedSHOP.Module
 * @subpackage  mod_redshop_wishlist
 *
 * @copyright   Copyright (C) 2008 - 2015 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

defined('_JEXEC') or die;

JLoader::import('redshop.library');

/**
 * Helper for redSHOP Wishlist Module
 *
 * @since  1.7.0
 */
abstract class ModRedshopWishlistHelper
{
	/**
	 * Retrieve a list of wishlist.
	 *
	 * @return  mixed
	 *
	 * @since   1.6.1
	 */
	public static function getList()
	{
		$user = JFactory::getUser();

		if ($user->guest)
		{
			return JFactory::getSession()->get('wishlist');
		}

		return RedshopHelperWishlist::getUserWishlist($user->id);
	}
}
