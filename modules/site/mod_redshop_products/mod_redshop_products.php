<?php
/**
 * @package     RedSHOP.Frontend
 * @subpackage  mod_redshop_products
 *
 * @copyright   Copyright (C) 2005 - 2013 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

defined('_JEXEC') or die;

if (!defined('MOD_REDSHOP_PRODUCTS'))
{
	/**
	 * Get all category to set as default
	 *
	 * @return  array  Category ids in array
	 */
	function getDefaultModuleCategories()
	{
		$db = JFactory::getDbo();

		$query = $db->getQuery(true)
			->select('category_id')
			->from($db->qn('#__redshop_category'))
			->where($db->qn('published') . ' = 1')
			->order($db->qn('category_id') . ' ASC');

		// Set the query and load the result.
		$db->setQuery($query);

		try
		{
			$categoryIds = $db->loadResultArray();
		}
		catch (RuntimeException $e)
		{
			throw new RuntimeException($e->getMessage(), $e->getCode());
		}

		$cids = '';

		if (count($categoryIds) > 0)
		{
			$cids = implode(",", $categoryIds);
		}

		return $cids;
	}

	define('MOD_REDSHOP_PRODUCTS', 1);
}

// Initialize variables.
$app                     = JFactory::getApplication();
$db                      = JFactory::getDbo();
$type                    = trim($params->get('type', 0));
$count                   = trim($params->get('count', 5));
$image                   = trim($params->get('image', 0));
$showProductVertically   = trim($params->get('vertical_product', 0));
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
require_once JPATH_ADMINISTRATOR . '/components/com_redshop/helpers/configuration.php';
$redConfiguration = new Redconfiguration;
$redConfiguration->defineDynamicVars();

require_once JPATH_SITE . '/components/com_redshop/helpers/product.php';
require_once JPATH_SITE . '/components/com_redshop/helpers/helper.php';
require_once JPATH_SITE . '/administrator/components/com_redshop/helpers/template.php';
require_once JPATH_SITE . '/components/com_redshop/helpers/extra_field.php';

$query = $db->getQuery(true);

$query->select('DISTINCT(' . $db->qn('p.product_id') . '), p.*')
	->from($db->qn('#__redshop_product', 'p'));

// Set all published category as default
$categoryIds = getDefaultModuleCategories();
$category    = trim($params->get('category', false));

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

// If category not set in parameter
if (!$category)
{
	$category = $categoryIds;
}

// If category is found
if ($category != "")
{
	$subQuery = $db->getQuery(true)
				->select($db->qn('product_id'))
				->from($db->qn('#__redshop_product_category_xref'))
				->where($db->qn('category_id') . ' IN (' . $category . ')');

	$query->where($db->qn('p.product_id') . ' IN (' . $subQuery . ')');
}

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
			->order($db->qn('property_id') . ' DESC');

	break;

	// Most Sold Product
	case 2:

		$query->select('count(' . $db->qn('oi.product_quantity') . ') as qty')
			->leftjoin(
					$db->qn('#__redshop_order_item', 'oi')
					. ' ON ' . $db->qn('oi.product_id') . ' = ' . $db->qn('p.product_id')
				)
			->group($db->qn('oi.product_id'))
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
}

$query->where($db->qn('p.published') . '=1');

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

// Set the query and load the result.
$db->setQuery($query, 0, $count);

try
{
	$rows = $db->loadObjectList();
}
catch (RuntimeException $e)
{
	throw new RuntimeException($e->getMessage(), $e->getCode());
}

require JModuleHelper::getLayoutPath('mod_redshop_products');
