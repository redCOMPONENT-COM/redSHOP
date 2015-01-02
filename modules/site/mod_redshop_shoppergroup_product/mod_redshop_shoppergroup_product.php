<?php
/**
 * @package     RedSHOP.Frontend
 * @subpackage  mod_redshop_shoppergroup_product
 *
 * @copyright   Copyright (C) 2005 - 2015 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

defined('_JEXEC') or die;

JLoader::import('redshop.library');
$option = JRequest::getCmd('option');
//$category = trim( $params->get( 'category', '' ) );
$count                    = trim($params->get('count', 5));
$image                    = trim($params->get('image', 0));
$show_price               = trim($params->get('show_price', 0)); // get show price yes/no option
$thumbwidth               = trim($params->get('thumbwidth', 100)); // get show image thumbwidth size
$thumbheight              = trim($params->get('thumbheight', 100)); // get show image thumbheight size
$show_short_description   = trim($params->get('show_short_description', 1));
$show_readmore            = trim($params->get('show_readmore', 1));
$show_addtocart           = trim($params->get('show_addtocart', 1));
$show_discountpricelayout = trim($params->get('show_discountpricelayout', 1));
$show_desc                = trim($params->get('show_desc', 1));
$show_vat                 = trim($params->get('show_vat', 1));

$user = JFactory::getUser();
$db   = JFactory::getDbo();

if ($option != 'com_redshop')
{
	// Getting the configuration
	require_once JPATH_ADMINISTRATOR . '/components/com_redshop/helpers/redshop.cfg.php';
	JLoader::load('RedshopHelperAdminConfiguration');
	$Redconfiguration = new Redconfiguration;
	$Redconfiguration->defineDynamicVars();
}

JLoader::load('RedshopHelperProduct');
JLoader::load('RedshopHelperHelper');
JLoader::load('RedshopHelperAdminTemplate');
JLoader::load('RedshopHelperExtra_field');

$and              = "";
$shopper_group_id = SHOPPER_GROUP_DEFAULT_UNREGISTERED;
if ($user->id)
{
	$query = "SELECT shopper_group_id FROM #__redshop_users_info AS ui "
		. "WHERE ui.user_id='" . $user->id . "' ";
	$db->setQuery($query);
	$getShopperGroupID = $db->loadResult();
	if ($getShopperGroupID)
	{
		$shopper_group_id = $getShopperGroupID;
	}
}

$query = "SELECT user_id FROM #__redshop_users_info AS ui "
	. "WHERE ui.shopper_group_id='" . $shopper_group_id . "' ";
$db->setQuery($query);
$user_id_arr = $db->loadColumn();

$user_id_str = '';
if (count($user_id_arr) > 0)
{
	$user_id_str = implode(', ', $user_id_arr);
}
$query = "SELECT o.order_id FROM #__redshop_orders AS o "
	. "WHERE o.user_id IN (" . $user_id_str . ") ";
$db->setQuery($query);
$order_id_arr = $db->loadColumn();
$order_id_str = '';
if (count($order_id_arr) > 0)
{
	$order_id_str = implode(', ', $order_id_arr);
}

//if($category!="")
//{
//	$and .= "AND cx.category_id IN ($cids) ";
//}
if ($order_id_str != "")
{
	$and .= "AND oi.order_id IN ($order_id_str) ";
}

$query = "SELECT COUNT(oi.product_id) AS totalproduct,p.*,cx.category_id FROM #__redshop_order_item AS oi "
	. "LEFT JOIN #__redshop_product AS p ON p.product_id=oi.product_id "
	. "LEFT JOIN #__redshop_product_category_xref AS cx ON cx.product_id=p.product_id "
	. "WHERE p.published=1 "
	. $and
	. "GROUP BY oi.product_id "
	. "ORDER BY totalproduct DESC "
	. "LIMIT 0,$count";
$db->setQuery($query);
$rows = $db->loadObjectList();

require JModuleHelper::getLayoutPath('mod_redshop_shoppergroup_product');
