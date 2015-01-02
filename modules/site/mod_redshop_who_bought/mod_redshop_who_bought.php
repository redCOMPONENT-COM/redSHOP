<?php
/**
 * @package     RedSHOP.Frontend
 * @subpackage  mod_redshop_who_bought
 *
 * @copyright   Copyright (C) 2008 - 2015 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

defined('_JEXEC') or die;

JLoader::import('redshop.library');
$category               = trim($params->get('category', ''));
$number_of_items        = trim($params->get('number_of_items', 5));
$thumbwidth             = trim($params->get('thumbwidth', 100));
$thumbheight            = trim($params->get('thumbheight', 100));
$sliderwidth            = trim($params->get('sliderwidth', 500));
$sliderheight           = trim($params->get('sliderheight', 350));
$show_product_image     = trim($params->get('show_product_image', 1));
$show_addtocart_button  = trim($params->get('show_addtocart_button', 1));
$show_product_name      = trim($params->get('show_product_name', 1));
$product_title_linkable = trim($params->get('product_title_linkable', 1));
$show_product_price     = trim($params->get('show_product_price', 1));

$and = "";

if ($category != "")
{
	$and = "AND xc.category_id IN (" . $category . ") ";
}

$db = JFactory::getDbo();
$sql = "SELECT p.*,xc.category_id FROM #__redshop_order_item as oi "
	. " LEFT JOIN #__redshop_product p ON p.product_id=oi.product_id "
	. " LEFT JOIN #__redshop_product_category_xref xc ON xc.product_id=oi.product_id "
	. " WHERE p.published=1 " . $and . " group by oi.product_id limit " . $number_of_items;

$db->setQuery($sql);
$productlists = $db->loadObjectList();

require_once JPATH_ADMINISTRATOR . '/components/com_redshop/helpers/redshop.cfg.php';
JLoader::load('RedshopHelperAdminConfiguration');

$Redconfiguration = new Redconfiguration;
$Redconfiguration->defineDynamicVars();

JLoader::load('RedshopHelperProduct');

JLoader::load('RedshopHelperHelper');

JLoader::load('RedshopHelperAdminTemplate');

JLoader::load('RedshopHelperExtra_field');

require JModuleHelper::getLayoutPath('mod_redshop_who_bought');
