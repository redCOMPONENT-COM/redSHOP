<?php
/**
 * @package     RedSHOP.Frontend
 * @subpackage  mod_redshop_products
 *
 * @copyright   Copyright (C) 2005 - 2013 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

defined('_JEXEC') or die('Restricted access');


if (!defined('MOD_REDSHOP_PRODUCTS'))
{
	// get all category to set as default
	function getDefaultModuleCategories()
	{

		$db = JFactory::getDbo();

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

	define('MOD_REDSHOP_PRODUCTS', 1);
}

$type = trim($params->get('type', 0));

// set all published category as default
$cids     = getDefaultModuleCategories();
$category = trim($params->get('category', ''));

// set product output limit
$count = trim($params->get('count', 5));

// get show image yes/no option
$image = trim($params->get('image', 0));

$vertical_product = trim($params->get('vertical_product', 0)); // get Vertical product yes/no option for horizontal and vertical product display

$featured_product = trim($params->get('featured_product', 0)); // get featured product yes/no option
$where_featured   = "";
if ($featured_product)
{
	$where_featured = " AND p.product_special = 1 ";
}

$show_price  = trim($params->get('show_price', 0)); // get show price yes/no option
$thumbwidth  = trim($params->get('thumbwidth', 100)); // get show image thumbwidth size
$thumbheight = trim($params->get('thumbheight', 100)); // get show image thumbheight size

$show_short_description = trim($params->get('show_short_description', 1));

$show_readmore = trim($params->get('show_readmore', 1));

$show_addtocart = trim($params->get('show_addtocart', 1));

$show_discountpricelayout = trim($params->get('show_discountpricelayout', 1));

$show_desc = trim($params->get('show_desc', 1));

$show_vat = trim($params->get('show_vat', 1));

$show_childproducts = trim($params->get('show_childproducts', 1));

if ($show_childproducts == 1)
{
	$main_child = "";
}
else
{
	$main_child = " AND p.product_parent_id=0";
}
$show_stockroom_status = trim($params->get('show_stockroom_status', 1));
if ($category == "")
{
	$category = $cids;
}

$db = JFactory::getDbo();

// Getting the configuration

require_once JPATH_ADMINISTRATOR . '/components/com_redshop/helpers/redshop.cfg.php';
JLoader::import('LoadHelpers', JPATH_SITE . '/components/com_redshop');
JLoader::load('RedshopHelperAdminConfiguration');
$Redconfiguration = new Redconfiguration;
$Redconfiguration->defineDynamicVars();

JLoader::load('RedshopHelperProduct');

JLoader::load('RedshopHelperHelper');

JLoader::load('RedshopHelperAdminTemplate');

JLoader::load('RedshopHelperAdminExtra_field');


switch ($type)
{

	case '0':

		if ($category == "")
			$sql = "SELECT DISTINCT(p.product_id),p.* FROM #__redshop_product p WHERE p.published=1 " . $where_featured . " " . $main_child . " ORDER BY product_id desc LIMIT 0,$count";
		else
			$sql = "SELECT DISTINCT(p.product_id),p.* FROM #__redshop_product p left outer join #__redshop_product_category_xref cx on cx.product_id = p.product_id WHERE  p.published=1 " . $where_featured . " " . $main_child . " AND cx.category_id IN ($category) ORDER BY product_id desc LIMIT 0,$count";

		break;

	case '1':

		$sql = "SELECT DISTINCT(p.product_id),p.*  FROM #__redshop_product p left outer join #__redshop_product_category_xref cx on cx.product_id = p.product_id left outer join #__redshop_product_attribute a on a.product_id = p.product_id left outer join  #__redshop_product_attribute_property ap on  a.attribute_id = ap.attribute_id WHERE  p.published=1 " . $where_featured . " " . $main_child . " AND cx.category_id IN ($category) ORDER BY property_id desc LIMIT 0,$count";

		break;

	case '2':

		$sql = "SELECT  DISTINCT(p.product_id),p.*,count(product_quantity) as qty FROM #__redshop_product p left outer join #__redshop_product_category_xref cx on cx.product_id = p.product_id left outer join  #__redshop_order_item oi on oi.product_id = p.product_id  WHERE p.published=1 " . $where_featured . " " . $main_child . " AND cx.category_id IN ($category) group by(oi.product_id)  ORDER BY qty desc LIMIT 0,$count";

		break;

	case '3':

		$sql = "SELECT DISTINCT(p.product_id),p.*  FROM #__redshop_product p left outer join #__redshop_product_category_xref cx on cx.product_id = p.product_id  WHERE p.published=1 " . $where_featured . " " . $main_child . " AND cx.category_id IN ($category) ORDER BY rand() LIMIT 0,$count";

		break;

	case '4':

		$sql = "SELECT DISTINCT(p.product_id),p.*  FROM #__redshop_product p left outer join #__redshop_product_category_xref cx on cx.product_id = p.product_id  WHERE p.published=1 AND p.product_on_sale = 1 " . $where_featured . " " . $main_child . " AND cx.category_id IN ($category) ORDER BY rand() LIMIT 0,$count";

		break;

}

$db->setQuery($sql);

$rows = $db->loadObjectList();


require JModuleHelper::getLayoutPath('mod_redshop_products');
