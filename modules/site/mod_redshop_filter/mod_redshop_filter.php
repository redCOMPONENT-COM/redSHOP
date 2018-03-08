<?php
/**
 * @package     RedSHOP.Frontend
 * @subpackage  mod_redshop_filter
 *
 * @copyright   Copyright (C) 2008 - 2015 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

defined('_JEXEC') or die;

require_once dirname(__FILE__) . '/helper.php';
JLoader::import('redshop.library');

$productHelper      = productHelper::getInstance();
$input              = JFactory::getApplication()->input;
$cid                = $input->getInt('cid', 0);
$mid                = $input->getInt('manufacturer_id', 0);
$moduleClassSfx     = $params->get("moduleclass_sfx");
$rootCategory       = $params->get('root_category', 0);
$enableCategory     = $params->get('category');
$enableManufacturer = $params->get('manufacturer');
$enablePrice        = $params->get('price');
$enableCustomField  = $params->get('custom_field');
$productFields      = $params->get('product_fields');
$enableKeyword      = $params->get('keyword');
$template           = $params->get('template_id');
$limit              = $params->get('limit', 0);
$restricted         = $params->get('restricted', 0);
$option             = $input->getCmd('option', '');
$view               = $input->getCmd('view', '');
$layout             = $input->getCmd('layout', '');
$itemId             = $input->getInt('Itemid', 0);
$keyword            = $input->getString('keyword', '');
$action             = JRoute::_("index.php?option=com_redshop&view=search");
$getData            = $input->getArray();

if (!empty($cid))
{
	$categoryModel = JModelLegacy::getInstance('Category', 'RedshopModel');
	$categoryModel->setId($cid);
	$categoryModel->setState('include_sub_categories_products', true);
	$productList = $categoryModel->getCategoryProduct(true, true);
	$catList     = array();
	$manuList    = array();
	$pids        = ModRedshopFilter::getProductByCategory($cid);

	foreach ($productList as $k => $value)
	{
		$tmpCategories = is_array($value->categories) ? $value->categories : explode(',', $value->categories);
		$catList = array_merge($catList, $tmpCategories);
		$pids[]  = $value->product_id;

		if ($value->manufacturer_id && $value->manufacturer_id != $mid)
		{
			$manuList[] = $value->manufacturer_id;
		}
	}

	$catList       = array_unique($catList);
	$manufacturers = ModRedshopFilter::getManufacturers(array_unique($manuList));
	$categories    = ModRedshopFilter::getCategories($catList, $rootCategory, $cid);
	$rangePrice    = ModRedshopFilter::getRange($pids);
}
elseif (!empty($mid))
{
	$manufacturerModel = JModelLegacy::getInstance('Manufacturers', 'RedshopModel');
	$manufacturerModel->setId($mid);
	$products = $manufacturerModel->getManufacturerProducts();
	$productList = array();

	foreach ($products as $key => $product)
	{
		$detail = RedshopHelperProduct::getProductById($product->product_id);
		$productList[] = $detail;
	}

	$manufacturers = array();
	$pids          = ModRedshopFilter::getProductByManufacturer($mid);
	$categories    = ModRedshopFilter::getCategorybyPids($pids, $rootCategory);
	$rangePrice    = ModRedshopFilter::getRange($pids);
}
elseif ($view == 'search')
{
	$db          = JFactory::getDbo();
	$modelSearch = JModelLegacy::getInstance("Search", "RedshopModel");
	$query       = $modelSearch->_buildQuery($input->post->getArray());
	$productIds  = $db->setQuery($query)->loadColumn();
	$productList = array();

	foreach ($productIds as $key => $product)
	{
		$detail = RedshopHelperProduct::getProductById($product);
		$productList[] = $detail;
	}

	$manuList    = array();
	$catList     = array();
	$pids        = array();

	foreach ($productList as $k => $value)
	{
		$tmpCategories = is_array($value->categories) ? $value->categories : explode(',', $value->categories);
		$catList = array_merge($catList, $tmpCategories);
		$pids[]  = $value->product_id;

		if ($value->manufacturer_id && $value->manufacturer_id != $mid)
		{
			$manuList[] = $value->manufacturer_id;
		}
	}

	$manufacturers = ModRedshopFilter::getManufacturers(array_unique($manuList));
	$categories    = ModRedshopFilter::getSearchCategories(array_unique($catList));
	$rangePrice    = ModRedshopFilter::getRange($pids);
	$mid           = $input->getInt('manufacturer_id', 0);
}

$customFields = ModRedshopFilter::getCustomFields($pids, $productFields);
$rangeMin     = isset($getData['filterprice']['min']) ? $getData['filterprice']['min'] : $rangePrice['min'];
$rangeMax     = isset($getData['filterprice']['max']) ? $getData['filterprice']['max'] : $rangePrice['max'];

$pids = array_filter(array_unique($pids));

if ($enablePrice)
{
	JHtml::stylesheet('mod_redshop_filter/jquery-ui.min.css', false, true);
	/** @scrutinizer ignore-deprecated */JHtml::script('mod_redshop_filter/jquery-ui.min.js', false, true, false, false);
}

require JModuleHelper::getLayoutPath('mod_redshop_filter', $params->get('layout', 'default'));
