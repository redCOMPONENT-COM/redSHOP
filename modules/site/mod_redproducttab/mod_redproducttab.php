<?php
/**
 * @package     RedSHOP.Frontend
 * @subpackage  mod_redshop_producttab
 *
 * @copyright   Copyright (C) 2005 - 2013 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

defined('_JEXEC') or die('Restricted access');

$user    = JFactory::getUser();
$newprd  = trim($params->get('show_newprd', 1));
$ltsprd  = trim($params->get('show_ltsprd', 1));
$soldprd = trim($params->get('show_soldprd', 1));
$splprd  = trim($params->get('show_splprd', 1));
$adjcat  = trim($params->get('adjust_category', 1));
//$type	= trim( $params->get( 'type',0) );
$category       = trim($params->get('category', 0));
$count          = trim($params->get('count', 1));
$image          = trim($params->get('image', 0));
$show_price     = trim($params->get('show_price', 0));
$show_readmore  = trim($params->get('show_readmore', 1));
$show_addtocart = trim($params->get('show_addtocart', 1));
$show_desc      = trim($params->get('show_desc', 1));
$thumbwidth     = trim($params->get('thumbwidth', 100)); // get show image thumbwidth size
$thumbheight    = trim($params->get('thumbheight', 100)); // get show image thumbheight size
$db             = JFactory::getDbo();

// Getting the configuration
require_once JPATH_ADMINISTRATOR . '/components/com_redshop/helpers/redshop.cfg.php';
require_once JPATH_ADMINISTRATOR . '/components/com_redshop/helpers/configuration.php';
$Redconfiguration = new Redconfiguration;
$Redconfiguration->defineDynamicVars();
require_once JPATH_ADMINISTRATOR . '/components/com_redshop/helpers/category.php';

$catfld = '';
if ($adjcat == 0)
{
	if ($category != 0)
	{
		$sql = "SELECT category_id FROM #__redshop_category AS c WHERE c.category_name = " . $db->quote($category) . "";
		$db->setQuery($sql);
		$ne   = $db->loadObject();
		$cids = $ne->category_id;

		if ($cids != 0)
		{
			$product_category = new product_category;
			$categories       = $product_category->getCategoryListArray('', $cids);
			$catid_arr        = array();
			for ($i = 0; $i < count($categories); $i++)
			{
				$catid_arr [] = $categories[$i]->category_id;
			}
			$cat_id = implode(", ", $catid_arr);
			if ($cat_id)
			{
				$catfld .= " AND cx.category_id IN ($cids,$cat_id) ";
			}
			else
			{
				$catfld .= " AND cx.category_id IN ($cids) ";
			}
		}
		else
		{
			$cids             = 0;
			$product_category = new product_category;
			$categories       = $product_category->getCategoryListArray('', $cids);
			$catid_arr        = array();
			for ($i = 0; $i < count($categories); $i++)
			{
				$catid_arr [] = $categories[$i]->category_id;
			}
			$cat_id = implode(", ", $catid_arr);
			if ($cat_id)
			{
				$catfld .= " AND cx.category_id IN ($cids,$cat_id) ";
			}
			else
			{
				$catfld .= " AND cx.category_id IN ($cids) ";
			}
		}
	}
}
else
{
	$cid = JRequest::getVar('cid');
	if ($cid != 0)
	{
		$product_category = new product_category;
		$categories       = $product_category->getCategoryListArray('', $cid);
		$catid_arr        = array();
		for ($i = 0; $i < count($categories); $i++)
		{
			$catid_arr [] = $categories[$i]->category_id;
		}
		$cat_id = implode(", ", $catid_arr);
		if ($cat_id)
		{
			$catfld .= " AND cx.category_id IN ($cid,$cat_id) ";
		}
		else
		{
			$catfld .= " AND cx.category_id IN ($cid) ";
		}
	}
}
$sql = "SELECT DISTINCT(p.product_id), p.*, cx.category_id FROM #__redshop_product AS p "
	. "LEFT JOIN #__redshop_product_category_xref AS cx ON cx.product_id = p.product_id "
	. "WHERE p.published=1 "
	. $catfld
	. "ORDER BY publish_date DESC LIMIT 0,$count";
$db->setQuery($sql);
$newprdlist = $db->loadObjectList();

$sql = "SELECT DISTINCT(p.product_id),p.*, cx.category_id FROM #__redshop_product AS p "
	. "LEFT JOIN #__redshop_product_category_xref AS cx ON cx.product_id = p.product_id "
	. "WHERE p.published=1 "
	. $catfld
	. "ORDER BY p.product_id DESC LIMIT 0,$count";
$db->setQuery($sql);
$ltsprdlist = $db->loadObjectList();

$sql = "SELECT *,count(product_quantity) AS qty FROM #__redshop_product AS p "
	. "LEFT JOIN #__redshop_product_category_xref AS cx ON cx.product_id = p.product_id "
	. "LEFT JOIN #__redshop_order_item AS oi ON oi.product_id = p.product_id "
	. "WHERE p.published=1 "
	. $catfld
	. "GROUP BY(oi.product_id) "
	. "ORDER BY qty DESC "
	. "LIMIT 0,$count";
$db->setQuery($sql);
$soldprdlist = $db->loadObjectList();

$sql = "SELECT DISTINCT(p.product_id), p.*, cx.category_id FROM #__redshop_product AS p "
	. "LEFT JOIN #__redshop_product_category_xref AS cx ON cx.product_id = p.product_id "
	. "WHERE product_special = 1 "
	. "AND p.published=1 "
	. $catfld
	. "ORDER BY publish_date DESC LIMIT 0,$count";
$db->setQuery($sql);
$splprdlist = $db->loadObjectList();

require JModuleHelper::getLayoutPath('mod_redproducttab');    ?>