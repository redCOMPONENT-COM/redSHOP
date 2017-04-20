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
$mid                = $input->getInt('mid', 0);
$moduleClassSfx     = $params->get("moduleclass_sfx");
$rootCategory       = $params->get('root_category', 0);
$categoryForSale    = $params->get('category_for_sale', 0);
$enableCategory     = $params->get('category');
$enableManufacturer = $params->get('manufacturer');
$enablePrice        = $params->get('price');
$enableKeyword      = $params->get('keyword');
$template           = $params->get('template_id');
$view               = $input->getString('view', '');
$layout             = $input->getString('layout', '');
$keyword            = $input->post->getString('keyword', '');
$productOnSale      = 0;
$action             = JRoute::_("index.php?option=com_redshop&view=search");
$manufacturers 		= [];
$document           = JFactory::getDocument();

if (!empty($cid))
{
	$list = RedshopHelperCategory::getCategoryListArray($categoryForSale);
	$childCat = array($categoryForSale);

	foreach ($list as $key => $value)
	{
		$childCat[] = $value->category_id;
	}

	if (in_array($cid, $childCat))
	{
		$productList = array();
		$catList     = array();
		$manuList    = array();
		$pids        = array();

		if ($cid == $categoryForSale)
		{
			foreach ($childCat as $k => $value)
			{
				$productCats = $productHelper->getProductCategory($value);

				foreach ($productCats as $key => $value)
				{
					$productList[] = $productHelper->getProductById($value->product_id);
				}
			}
		}
		else
		{
			$productCats = $productHelper->getProductCategory($cid);

			foreach ($productCats as $key => $value)
			{
				$productList[$key] = $productHelper->getProductById($value->product_id);
			}
		}

		foreach ($productList as $k => $value)
		{
			$tmpCategories = is_array($value->categories) ? $value->categories : explode(',', $value->categories);
			$catList = array_merge($catList, $tmpCategories);
			$manuList[] = $value->manufacturer_id;
			$pids[]     = $value->product_id;
		}

		$catList = array_unique($catList);
		$manufacturers = ModRedshopFilter::getManufacturerOnSale(array_unique($manuList));
		$categories    = ModRedshopFilter::getParentCategoryOnSale($catList, $rootCategory, $categoryForSale);
		$rangePrice    = ModRedshopFilter::getRange($pids);
	}
	else
	{
		$categories    = ModRedshopFilter::getParentCategory($cid);
		$rangePrice    = ModRedshopFilter::getRangeMaxMin($cid);
		$manufacturers = ModRedshopFilter::getManufacturers($cid);
	}
}
elseif (!empty($mid))
{
	$manufacturers = ModRedshopFilter::getManufacturerById($mid);
	$pids          = ModRedshopFilter::getProductByManufacturer($mid);
	$categories    = ModRedshopFilter::getCategorybyPids($pids, $rootCategory, $categoryForSale);
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

	$manufacturers = ModRedshopFilter::getManufacturerOnSale(array_unique($manuList));
	$categories    = ModRedshopFilter::getParentCategoryOnSale(array_unique($catList));
	$rangePrice    = ModRedshopFilter::getRange($pids);
}

$rangeMin = isset($rangePrice['min'])? $rangePrice['min']: '';
$rangeMax = isset($rangePrice['max'])? $rangePrice['max']: '';

require JModuleHelper::getLayoutPath('mod_redshop_filter', $params->get('layout', 'default'));
