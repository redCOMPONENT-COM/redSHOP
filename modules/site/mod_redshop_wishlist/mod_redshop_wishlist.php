<?php
/**
 * @package     RedSHOP.Frontend
 * @subpackage  mod_redshop_wishlist
 *
 * @copyright   Copyright (C) 2005 - 2013 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

defined('_JEXEC') or die('Restricted access');

$user           = JFactory::getUser();
$count          = trim($params->get('count', 1));
$image          = trim($params->get('image', 0));
$show_price     = trim($params->get('show_price', 0));
$show_readmore  = trim($params->get('show_readmore', 1));
$show_addtocart = trim($params->get('show_addtocart', 1));
$show_desc      = trim($params->get('show_desc', 1));
$thumbwidth     = $params->get('thumbwidth', "100");
$thumbheight    = $params->get('thumbheight', "100");

$db = JFactory::getDbo();

// Getting the configuration
require_once JPATH_ADMINISTRATOR . '/components/com_redshop/helpers/redshop.cfg.php';
require_once JPATH_ADMINISTRATOR . '/components/com_redshop/helpers/configuration.php';
$Redconfiguration = new Redconfiguration();
$Redconfiguration->defineDynamicVars();


$rows = array();
require_once JPATH_SITE . '/components/com_redshop/helpers/product.php';

if (MY_WISHLIST)
{
	$sql = "SELECT wishlist_id,wishlist_name FROM #__redshop_wishlist where user_id = " . (int) $user->id;
	$db->setQuery($sql);
	$wishlists = $db->loadObjectList();

	if (count($wishlists) > 0 && $user->id != 0)
	{
		for ($i = 0; $i < count($wishlists); $i++)
		{
			$sql = "SELECT DISTINCT wp.* ,p.* "
				. "FROM  #__redshop_product as p "
				. ", #__redshop_wishlist_product as wp "
				. "WHERE wp.product_id = p.product_id AND wp.wishlist_id = " . (int) $wishlists[$i]->wishlist_id;
			$db->setQuery($sql);
			$wish_products[$wishlists[$i]->wishlist_id] = $db->loadObjectList();
		}
	}
	else if (isset($_SESSION["no_of_prod"]))
	{
		$prod_id = "";

		for ($add_i = 1; $add_i <= $_SESSION["no_of_prod"]; $add_i++)
			if ($_SESSION['wish_' . $add_i]->product_id != '')
			{
				$prod_id .= $_SESSION['wish_' . $add_i]->product_id . ",";
			}

		$prod_id .= $_SESSION['wish_' . $add_i]->product_id;

		$sql = "SELECT DISTINCT p.* "
			. "FROM #__redshop_product as p "
			. "WHERE p.product_id IN( " . substr_replace($prod_id, "", -1) . ")";
		$db->setQuery($sql);
		$rows = $db->loadObjectList();
	}
	require JModuleHelper::getLayoutPath('mod_redshop_wishlist');
}
