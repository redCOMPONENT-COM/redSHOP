<?php
/**
 * @package     RedSHOP.Frontend
 * @subpackage  mod_redshop_redmanufacturer
 *
 * @copyright   Copyright (C) 2008 - 2015 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

defined('_JEXEC') or die;

JLoader::import('redshop.library');

/**
 * Helper for mod_redmanufacturer
 *
 * @since  1.6.1
 */
abstract class ModRedshopCartHelper
{
	/**
	 * Process Cart
	 *
	 * @return  int  $count
	 */
	public static function processCart()
	{
		// Helper object
		$helper = redhelper::getInstance();
		$helper->dbtocart();

		$session     = JFactory::getSession();
		$cart        = $session->get('cart');

		if (count($cart) <= 0 || $cart == "")
		{
			$cart = array();
		}

		$idx = 0;

		if (is_array($cart) && !array_key_exists("quotation_id", $cart))
		{
			if (isset($cart['idx']))
			{
				$idx = $cart['idx'];
			}
		}

		$count = 0;

		for ($i = 0; $i < $idx; $i++)
		{
			$count += $cart[$i]['quantity'];
		}

		$session->set('cart', $cart);

		return $cart;
	}

	/**
	 * getItemId function
	 * 
	 * @return  int $itemId
	 */
	public static function getItemId()
	{
		$redhelper     = redhelper::getInstance();
		$app           = JFactory::getApplication();
		$itemId        = (int) $redhelper->getCartItemid();

		$getNewItemId = true;

		if ($itemId != 0)
		{
			$menu = $app->getMenu();
			$item = $menu->getItem($itemId);

			$getNewItemId = false;

			if (isset($item->id) === false)
			{
				$getNewItemId = true;
			}
		}

		if ($getNewItemId)
		{
			$itemId = (int) $redhelper->getCategoryItemid();
		}

		return $itemId;
	}
}
