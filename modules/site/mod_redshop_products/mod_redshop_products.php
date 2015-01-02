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
$isUrlCategoryId         = trim($params->get('urlCategoryId', 0));

// Getting the configuration
require_once JPATH_ADMINISTRATOR . '/components/com_redshop/helpers/redshop.cfg.php';
JLoader::load('RedshopHelperAdminConfiguration');
$redConfiguration = new Redconfiguration;
$redConfiguration->defineDynamicVars();
$user = JFactory::getUser();

JLoader::load('RedshopHelperProduct');
JLoader::load('RedshopHelperHelper');
JLoader::load('RedshopHelperAdminTemplate');
JLoader::load('RedshopHelperAdminExtra_field');

$query = $db->getQuery(true)
	->select('CONCAT_WS(' . $db->q('.') . ', p.product_id, ' . (int) $user->id . ') AS concat_id')
	->where($db->qn('p.published') . ' = 1');

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
			->select('SUM(' . $db->qn('oi.product_quantity') . ')')
			->from($db->qn('#__redshop_order_item', 'oi'))
			->where($db->qn('oi.product_id') . ' = ' . $db->qn('p.product_id'));
		$query->select('(' . $subQuery . ') AS qty')
			->order($db->qn('qty') . ' DESC');

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

$productHelper = new producthelper;
$query = $productHelper->getMainProductQuery($query, $user->id);

$category = trim($params->get('category', false));

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
	$query->leftJoin($db->qn('#__redshop_category', 'c') . ' ON c.category_id = pc.category_id')
		->where($db->qn('c.published') . ' = 1');
}

// Set the query and load the result.
$db->setQuery($query, 0, $count);

try
{
	if ($rows = $db->loadObjectList('concat_id'))
	{
		$productHelper->setProduct($rows);
		$rows = array_values($rows);
	}
}
catch (RuntimeException $e)
{
	throw new RuntimeException($e->getMessage(), $e->getCode());
}

require JModuleHelper::getLayoutPath('mod_redshop_products');
