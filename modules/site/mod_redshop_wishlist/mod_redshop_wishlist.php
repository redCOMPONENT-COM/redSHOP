<?php
/**
 * @package     RedSHOP.Frontend
 * @subpackage  mod_redshop_wishlist
 *
 * @copyright   Copyright (C) 2008 - 2015 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

defined('_JEXEC') or die;

JLoader::import('redshop.library');
$user           = JFactory::getUser();
$db = JFactory::getDbo();

// Getting the configuration
require_once JPATH_ADMINISTRATOR . '/components/com_redshop/helpers/redshop.cfg.php';
JLoader::load('RedshopHelperAdminConfiguration');
$Redconfiguration = new Redconfiguration;
$Redconfiguration->defineDynamicVars();


$rows = array();
JLoader::load('RedshopHelperProduct');

if (MY_WISHLIST)
{
	$sql = "SELECT wishlist_id,wishlist_name FROM #__redshop_wishlist where user_id = " . (int) $user->id;
	$db->setQuery($sql);
	$wishlists = $db->loadObjectList();

	if (count($wishlists) > 0 && $user->id != 0)
	{
		for ($i = 0, $in = count($wishlists); $i < $in; $i++)
		{
			$sql = "SELECT DISTINCT wp.* ,p.* "
				. "FROM  #__redshop_product as p "
				. ", #__redshop_wishlist_product as wp "
				. "WHERE wp.product_id = p.product_id AND wp.wishlist_id = " . (int) $wishlists[$i]->wishlist_id;
			$db->setQuery($sql);
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

	require JModuleHelper::getLayoutPath('mod_redshop_wishlist');
}
