<?php
/**
 * @package     RedSHOP.Module
 * @subpackage  mod_redfeaturedproduct
 *
 * @copyright   Copyright (C) 2008 - 2015 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

defined('_JEXEC') or die;
JLoader::import('redshop.library');

/**
 * Helper for mod_articles_latest
 *
 * @since  1.5
 */
abstract class ModRedWishList
{
	/**
	 * Retrieve a list of product
	 *
	 * @param   JRegistry  &$params  module parameters
	 *
	 * @return  mixed
	 *
	 * @since   1.6.1
	 */
	public static function getList(&$params)
	{
		$user 	= JFactory::getUser();
		$db 	= JFactory::getDbo();
		$rows 	= array();
		$query 	= $db->getQuery(true);

		$query->select($db->qn(['wishlist_id', 'wishlist_name']))
			->from($db->qn('#__redshop_wishlist'))
			->where($db->qn('user_id') . ' = ' . $db->q($user->id));

		$db->setQuery($query);
		$wishlists = $db->loadObjectList();

		if (count($wishlists) > 0 && $user->id != 0)
		{
			for ($i = 0, $in = count($wishlists); $i < $in; $i++)
			{
				$query = $db->getQuery(true);
				$query->select($db->qn(['wp.*', 'p.*']))
					->from($db->qn('#__redshop_product'), 'p')
					->innerJoin(
							$db->qn('#__redshop_wishlist_product', 'wp')
							. ' ON ' . $db->qn('wp.product_id') . ' = ' . $db->qn('p.product_id')
						)
					->where($db->qn('wp.wishlist_id') . ' = ' . $db->q($wishlists[$i]->wishlist_id));
				$sql = "SELECT DISTINCT  "
					. "FROM  #__redshop_product as p "
					. ", #__redshop_wishlist_product as wp "
					. "WHERE wp.product_id = p.product_id AND wp.wishlist_id = " . (int) $wishlists[$i]->wishlist_id;
				$db->setQuery($query);
				$wish_products[$wishlists[$i]->wishlist_id] = $db->loadObjectList();
			}
		}
		elseif (isset($_SESSION["no_of_prod"]))
		{
			$prod_id = array();

			for ($add_i = 1; $add_i <= $_SESSION["no_of_prod"]; $add_i++)
			{
				if (isset($_SESSION['wish_' . $add_i]->product_id))
				{
					$prod_id[] = $_SESSION['wish_' . $add_i]->product_id . ",";
				}
			}

			if (count($prod_id))
			{
				// Sanitize ids
				JArrayHelper::toInteger($prod_id);

				$sql = "SELECT DISTINCT p.* "
					. "FROM #__redshop_product as p "
					. "WHERE p.product_id IN( " . implode(',', $prod_id) . ")";
				$db->setQuery($sql);
				$rows = $db->loadObjectList();
			}
		}

		return $rows;
	}
}
