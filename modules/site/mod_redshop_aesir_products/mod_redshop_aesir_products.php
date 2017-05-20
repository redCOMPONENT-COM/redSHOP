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
$item                    = trim($params->get('item', $app->input->getInt('id', 0)));
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
	->select($db->qn('table_name'))
	->from($db->qn('#__reditem_types'))
	->where($db->qn('id') . ' = ' . $db->q((int) $type));
$tableName = $db->setQuery($query)->loadResult();

$query = $db->getQuery(true)
	->select($db->qn('id'))
	->from($db->qn('#__reditem_items'))
	->where($db->qn('id') . ' = ' . $db->q((int) $item));
$itemId = $db->setQuery($query)->loadResult();

$query = $db->getQuery(true)
	->select($db->qn('redshop_products'))
	->from($db->qn('#__reditem_types_' . $tableName))
	->where($db->qn('id') . ' = ' . $db->q((int) $itemId));
$jsonId = $db->setQuery($query)->loadResult();
$ids = json_decode($jsonId);

$query = $db->getQuery(true)
	->select($db->qn('p.product_id'))
	->from($db->qn('#__redshop_product', 'p'))
	->leftJoin($db->qn('#__redshop_product_category_xref', 'pc') . ' ON ' . $db->qn('pc.product_id') . ' = ' . $db->qn('p.product_id'))
	->leftJoin($db->qn('#__redshop_category', 'c') . ' ON ' . $db->qn('c.category_id') . ' = ' . $db->qn('pc.category_id'))
	->where($db->qn('c.published') . ' = 1')
	->where($db->qn('p.published') . ' = 1')
	->group($db->qn('p.product_id'))
	->order($db->qn('p.product_id') . ' DESC');

if (!empty($ids))
{
	$query->where($db->qn('p.product_id') . ' IN (' . implode(',', $ids) . ')');
}
else
{
	$query->where($db->qn('p.product_id') . ' = 0');
}

// Only Display Feature Product
if ($showFeaturedProduct)
{
	$query->where($db->qn('p.product_special') . ' = 1');
}

// Show Child Products or Parent Products
if ($showChildProducts != 1)
{
	$query->where($db->qn('p.product_parent_id') . ' = 0');
}

$rows = array();

if ($productIds = $db->setQuery($query, 0, $count)->loadColumn())
{
	// Third steep get all product relate info
	$query->clear()
		->where($db->qn('p.product_id') . ' IN (' . implode(',', $productIds) . ')')
		->order('FIELD(p.product_id, ' . implode(',', $productIds) . ')');

	$query = RedshopHelperProduct::getMainProductQuery($query, $user->id)
		->select('CONCAT_WS(' . $db->q('.') . ', p.product_id, ' . (int) $user->id . ') AS concat_id');

	if ($rows = $db->setQuery($query)->loadObjectList('concat_id'))
	{
		RedshopHelperProduct::setProduct($rows);
		$rows = array_values($rows);
	}
}

require JModuleHelper::getLayoutPath('mod_redshop_products');
