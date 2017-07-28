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
$enableKeyword      = $params->get('keyword');
$template           = $params->get('template_id');
$limit              = $params->get('limit', 0);
$view               = $input->getCmd('view', '');
$layout             = $input->getCmd('layout', '');
$keyword            = $input->post->getString('keyword', '');
$action             = JRoute::_("index.php?option=com_redshop&view=search");

if (!empty($cid))
{
	$categoryModel = JModelLegacy::getInstance('Category', 'RedshopModel');
	$categoryModel->setId($cid);
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
	$customFields  = ModRedshopFilter::getCustomFields($pids);
	$rangePrice    = ModRedshopFilter::getRange($pids);
}
elseif (!empty($mid))
{
	$manufacturers = array();
	$pids          = ModRedshopFilter::getProductByManufacturer($mid);
	$categories    = ModRedshopFilter::getCategorybyPids($pids, $rootCategory);
	$rangePrice    = ModRedshopFilter::getRange($pids);
}
elseif ($view == 'search')
{
	$modelSearch = JModelLegacy::getInstance("Search", "RedshopModel");
	$products    = $modelSearch->getData();
	$manuList    = array();
	$catList     = array();
	$pids        = array();

	foreach ($products as $key => $value)
	{
		$pids[] = $value->product_id;

		if (!empty($value->manufacturer_id))
		{
			$manuList[] = $value->manufacturer_id;
		}

		if (!empty($value->category_id))
		{
			$catList[] = $value->category_id;
		}
	}

	$manufacturers = ModRedshopFilter::getManufacturers(array_unique($manuList));
	$categories    = ModRedshopFilter::getCategories(array_unique($catList));
	$rangePrice    = ModRedshopFilter::getRange($pids);
}

$rangeMin = $rangePrice['min'];
$rangeMax = $rangePrice['max'];

require JModuleHelper::getLayoutPath('mod_redshop_filter', $params->get('layout', 'default'));
