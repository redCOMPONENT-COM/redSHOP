<?php
/**
 * @package     RedSHOP.Frontend
 * @subpackage  mod_redshop_products
 *
 * @copyright   Copyright (C) 2008 - 2015 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

defined('_JEXEC') or die;

JLoader::import('redshop.library');

// Initialize variables.
$app                     = JFactory::getApplication();
$db                      = JFactory::getDbo();
$type                    = trim($params->get('type', 0));
$count                   = trim($params->get('count', 5));
$image                   = trim($params->get('image', 0));
$showFeaturedProduct     = trim($params->get('featured_product', 0));
$showPrice               = trim($params->get('show_price', 0));
$thumbWidth              = trim($params->get('thumbwidth', 100));
$thumbHeight             = trim($params->get('thumbheight', 100));
$showShortDescription    = trim($params->get('show_short_description', 1));
$showReadmore            = trim($params->get('show_readmore', 1));
$showAddToCart           = trim($params->get('show_addtocart', 1));
$showDiscountPriceLayout = trim($params->get('show_discountpricelayout', 1));
$showDescription         = trim($params->get('show_desc', 1));
$showVat                 = trim($params->get('show_vat', 1));
$showStockroomStatus     = trim($params->get('show_stockroom_status', 1));
$showChildProducts       = trim($params->get('show_childproducts', 1));
$showWishlist            = trim($params->get('show_wishlist', 0));
$isUrlCategoryId         = trim($params->get('urlCategoryId', 0));

$user = JFactory::getUser();

$query = $db->getQuery(true)
	->select('p.product_id')
	->from($db->qn('#__redshop_product', 'p'))
	->leftJoin('#__redshop_product_category_xref AS pc ON pc.product_id = p.product_id')
	->where($db->qn('p.published') . ' = 1')
	->group('p.product_id');

switch ((int) $type)
{
	// Newest Product
	case 0:
		$query->order($db->qn('p.product_id') . ' DESC');
	break;

	// Latest Product
	case 1:

		$query->leftjoin(
					$db->qn('#__redshop_product_attribute', 'a')
					. ' ON ' . $db->qn('a.product_id') . ' = ' . $db->qn('p.product_id')
				)
			->leftjoin(
					$db->qn('#__redshop_product_attribute_property', 'ap')
					. ' ON ' . $db->qn('a.attribute_id') . ' = ' . $db->qn('ap.attribute_id')
				)
			->order($db->qn('ap.property_id') . ' DESC')
			->order($db->qn('p.product_id') . ' DESC');

	break;

	// Most Sold Product
	case 2:

		$subQuery = $db->getQuery(true)
			->select('SUM(' . $db->qn('oi.product_quantity') . ') AS qty, oi.product_id')
			->from($db->qn('#__redshop_order_item', 'oi'))
			->group('oi.product_id');
		$query->select('orderItems.qty')
			->leftJoin('(' . $subQuery . ') orderItems ON orderItems.product_id = p.product_id')
			->order($db->qn('orderItems.qty') . ' DESC');

		break;

	// Random Product
	case 3:

		$query->order('rand()');

		break;

	// Product On Sale
	case 4:

		$query->where($db->qn('p.product_on_sale') . '=1')
			->order($db->qn('p.product_name'));

		break;

	// Product On Sale and discount date check
	case 5:
		$time = time();
		$query->where($db->qn('p.product_on_sale') . ' = 1')
			->where('((p.discount_stratdate = 0 AND p.discount_enddate = 0) OR (p.discount_stratdate <= '
				. $time . ' AND p.discount_enddate >= ' . $time . ') OR (p.discount_stratdate <= '
				. $time . ' AND p.discount_enddate = 0))')
			->order($db->qn('p.product_name'));
		break;
	case 6:
		$session = JFactory::getSession();
		$watched = $session->get('watched_product');

		if (!empty($watched))
		{
			$query->where($db->qn('p.product_id') . ' IN (' . implode(',', $watched) . ')');	
		}
		
		break;
}

// Only Display Feature Product
if ($showFeaturedProduct)
{
	$query->where($db->qn('p.product_special') . '=1');
}

// Show Child Products or Parent Products
if ($showChildProducts != 1)
{
	$query->where($db->qn('p.product_parent_id') . '=0');
}

$category = $params->get('category', '');

if (is_array($category))
{
	$category = implode(',', $category);
}
else
{
	$category = trim($category);
}

if ($isUrlCategoryId)
{
	// Get Category id from menu params if not found in URL
	$urlCategoryId = (int) $app->input->getInt('cid', $app->getParams('com_redshop')->get('cid', ''));

	if ($category)
	{
		$categoryArray = explode(",", $category);
		array_push($categoryArray, $urlCategoryId);
		JArrayHelper::toInteger($categoryArray);

		$category = implode(",", $categoryArray);
	}
	else
	{
		$category = $urlCategoryId;
	}
}

// If category is found
if ($category)
{
	$query->where($db->qn('pc.category_id') . ' IN (' . $category . ')');
}
else
{
	$query->leftJoin($db->qn('#__redshop_category', 'c') . ' ON c.id = pc.category_id')
		->where($db->qn('c.published') . ' = 1');
}

$stockrooms = $params->get('stockrooms', '');

if (is_array($stockrooms))
{
	$stockrooms = implode(',', $stockrooms);
}
else
{
	$stockrooms = trim($stockrooms);
}

if ($stockrooms && Redshop::getConfig()->get('USE_STOCKROOM') == 1)
{
	$query->leftjoin($db->qn('#__redshop_product_stockroom_xref', 'sx') . ' ON p.product_id = sx.product_id')
			->where($db->qn('sx.stockroom_id') . ' IN (' . $stockrooms . ')')
			->where($db->qn('sx.quantity') . ' > 0');
}

$rows = array();

if ($productIds = $db->setQuery($query, 0, $count)->loadColumn())
{
	// Third steep get all product relate info
	$query->clear()
		->where('p.product_id IN (' . implode(',', $productIds) . ')')
		->order('FIELD(p.product_id, ' . implode(',', $productIds) . ')');

	$query = RedshopHelperProduct::getMainProductQuery($query, $user->id)
		->select('CONCAT_WS(' . $db->q('.') . ', p.product_id, ' . (int) $user->id . ') AS concat_id');

	if ($rows = $db->setQuery($query)->loadObjectList('concat_id'))
	{
		RedshopHelperProduct::setProduct($rows);
		$rows = array_values($rows);
	}
}

require JModuleHelper::getLayoutPath('mod_redshop_products', $params->get('layout', 'default'));
