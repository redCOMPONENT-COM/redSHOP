<?php
/**
 * @package     RedSHOP.Frontend
 * @subpackage  mod_redshop_who_bought
 *
 * @copyright   Copyright (C) 2005 - 2013 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

defined('_JEXEC') or die('Restricted access');

function getDefaultModuleCategoriesbought()
{
	$db = JFactory::getDBO();

	$sql = "SELECT category_id FROM #__redshop_category WHERE published=1 ORDER BY category_id ASC";
	$db->setQuery($sql);
	$cats = $db->loadObjectList();

	$category = array();
	for ($i = 0; $i < count($cats); $i++)
	{

		$category[] = $cats[$i]->category_id;
	}
	if (count($category) > 0)
		$cids = implode(",", $category);
	else
		$cids = 0;

	return $cids;
}

$db = JFactory::getDBO();

$cids = getDefaultModuleCategoriesbought();

$category        = trim($params->get('category', ''));
$number_of_items = trim($params->get('number_of_items', 5)); // get show number of products
$thumbwidth      = trim($params->get('thumbwidth', 100)); // get show image thumbwidth size
$thumbheight     = trim($params->get('thumbheight', 100)); // get show image thumbheight size

$sliderwidth  = trim($params->get('sliderwidth', 500)); // get show product name linkable
$sliderheight = trim($params->get('sliderheight', 350)); // get show product price


$show_product_image     = trim($params->get('show_product_image', 1)); // get show product image
$show_addtocart_button  = trim($params->get('show_addtocart_button', 1)); // get show add to cart button
$show_product_name      = trim($params->get('show_product_name', 1)); // get show product name
$product_title_linkable = trim($params->get('product_title_linkable', 1)); // get show product name linkable
$show_product_price     = trim($params->get('show_product_price', 1)); // get show product price

$and = "";
if ($category != "")
{
	$and = "AND xc.category_id IN (" . $category . ") ";
}
$sql = "SELECT p.*,xc.category_id FROM #__redshop_order_item as oi
				LEFT JOIN #__redshop_product p ON p.product_id=oi.product_id
				LEFT JOIN #__redshop_product_category_xref xc ON xc.product_id=oi.product_id "
	. "WHERE p.published=1 " . $and . " group by oi.product_id";

$db->setQuery($sql);
$productlists = $db->loadObjectList();

require_once JPATH_ADMINISTRATOR . '/components/com_redshop/helpers/redshop.cfg.php';
require_once JPATH_ADMINISTRATOR . '/components/com_redshop/helpers/configuration.php';
$Redconfiguration = new Redconfiguration();
$Redconfiguration->defineDynamicVars();

require_once JPATH_SITE . '/components/com_redshop/helpers/product.php';

require_once JPATH_SITE . '/components/com_redshop/helpers/helper.php';

require_once JPATH_SITE . '/administrator/components/com_redshop/helpers/template.php';

require_once JPATH_SITE . '/components/com_redshop/helpers/extra_field.php';


require(JModuleHelper::getLayoutPath('mod_redshop_who_bought'));
