<?php
/**
 * @package     RedSHOP.Frontend
 * @subpackage  mod_redshop_category_scroller
 *
 * @copyright   Copyright (C) 2005 - 2013 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

defined('_JEXEC') or die('Restricted access');

JLoader::import('redshop.library');
$cid = JRequest::getInt('cid', 0);

$NumberOfProducts  = trim($params->get('NumberOfProducts', 5));
$ScrollSortMethod  = trim($params->get('ScrollSortMethod', 'random'));
$show_product_name = trim($params->get('show_product_name', 1));
$show_addtocart    = trim($params->get('show_addtocart', 1));
$show_price        = trim($params->get('show_price', 1));
$show_image        = trim($params->get('show_image', 1));
$show_readmore     = trim($params->get('show_readmore', 1));
$show_vat          = trim($params->get('show_vat', 1));

$scrollerwidth            = trim($params->get('scrollerwidth', 500));
$scrollerheight           = trim($params->get('scrollerheight', 200));
$thumbwidth               = trim($params->get('thumbwidth', 100));
$thumbheight              = trim($params->get('thumbheight', 100));
$product_title_end_suffix = trim($params->get('product_title_end_suffix', '...'));
$product_title_max_chars  = trim($params->get('product_title_max_chars', 10));
$show_discountpricelayout = trim($params->get('show_discountpricelayout', 1));
$pretext                  = trim($params->get('pretext', ''));


$limit = "";
if ($NumberOfProducts > 0)
{
	$limit = "LIMIT 0,$NumberOfProducts";
}

$db      = JFactory::getDbo();
$orderby = "ORDER BY p.product_id ";
switch ($ScrollSortMethod)
{
	case 'random':
		$orderby = "ORDER BY RAND() ";
		break;

	case 'newest':
		$orderby = "ORDER BY p.publish_date DESC ";
		break;

	case 'oldest':
		$orderby = "ORDER BY p.publish_date ASC ";

	case 'mostsold':
		$orderby = "ORDER BY qty DESC ";

		break;

}
$where = "";
$query = "SELECT count(*) FROM #__redshop_product_category_xref WHERE category_id IN (" . $cid . ")";
$db->setQuery($query);
$product_count = $db->loadResult();

$db->setQuery($query);
$rows = $db->loadObjectList();
if ($cid != 0 && $product_count != 0)
{
	$where = "AND c.category_id IN (" . $cid . ") ";
}
if ($ScrollSortMethod == 'mostsold')
{
	if ($cid != 0)
	{
		$query = "SELECT *,count(product_quantity) AS qty FROM #__redshop_product AS p "
			. "LEFT JOIN #__redshop_product_category_xref AS cx ON cx.product_id = p.product_id "
			. "LEFT JOIN #__redshop_order_item AS oi ON oi.product_id = p.product_id "
			. "WHERE p.published=1 "
			. "AND cx.category_id = $cid "
			. "GROUP BY(oi.product_id) "
			. $orderby
			. $limit;
	}
	else
	{
		$query = "SELECT p.*,count(product_quantity) AS qty FROM #__redshop_product AS p "
			. "LEFT JOIN #__redshop_order_item AS oi ON oi.product_id = p.product_id "
			. "WHERE p.published=1 "
			. "GROUP BY(oi.product_id) "
			. $orderby
			. $limit;
	}

}
else
{
	if ($cid != 0)
	{
		$query = "SELECT p.product_id,p.product_name,p.product_full_image,p.product_thumb_image,p.not_for_sale,p.attribute_set_id,p.cat_in_sefurl,c.category_id FROM #__redshop_product AS p "
			. "LEFT JOIN #__redshop_product_category_xref AS x ON x.product_id=p.product_id "
			. "LEFT JOIN #__redshop_category AS c ON x.category_id=c.category_id "
			. "WHERE p.published=1 "
			. $where
			. "GROUP BY p.product_id "
			. $orderby
			. $limit;
	}
	else
	{
		$query = "SELECT p.product_id,p.product_name,p.product_full_image,p.product_thumb_image,p.not_for_sale,p.attribute_set_id,p.cat_in_sefurl FROM #__redshop_product AS p "
			. "WHERE p.published=1 "
			. "GROUP BY p.product_id "
			. $orderby
			. $limit;
	}
}
$db->setQuery($query);
$rows = $db->loadObjectList();

require JModuleHelper::getLayoutPath('mod_redshop_category_scroller');

