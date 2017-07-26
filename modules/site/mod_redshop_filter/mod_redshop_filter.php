<?php
/**
 * @package     RedSHOP.Frontend
 * @subpackage  mod_redshop_filter
 *
 * @copyright   Copyright (C) 2008 - 2015 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

defined('_JEXEC') or die;

/**
 * @var  \Joomla\Registry\Registry $params Module parameters
 */

JLoader::import('redshop.library');

require_once dirname(__FILE__) . '/helper.php';

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
$rangePrice         = array();

if (!empty($cid))
{
	$list     = RedshopHelperCategory::getCategoryListArray($categoryForSale);
	$childCat = array($categoryForSale);

	foreach ($list as $key => $value)
	{
		$childCat[] = $value->id;
	}

	if (in_array($cid, $childCat))
	{
		$productList      = array();
		$categoryList     = array();
		$manufacturerList = array();
		$productIds       = array();

		if ($cid == $categoryForSale)
		{
			foreach ($childCat as $k => $value)
			{
				$productCats = $productHelper->getProductCategory($value);

				foreach ($productCats as $key => $value)
				{
					$productList[] = RedshopHelperProduct::getProductById($value->product_id);
				}
			}
		}
		else
		{
			$productCats = $productHelper->getProductCategory($cid);

			foreach ($productCats as $key => $value)
			{
				$productList[$key] = RedshopHelperProduct::getProductById($value->product_id);
			}
		}

		foreach ($productList as $k => $value)
		{
			$tmpCategories      = is_array($value->categories) ? $value->categories : explode(',', $value->categories);
			$categoryList       = array_merge($categoryList, $tmpCategories);
			$manufacturerList[] = $value->manufacturer_id;
			$productIds[]       = $value->product_id;
		}

		$categoryList  = array_unique($categoryList);
		$manufacturers = ModRedshopFilter::getManufacturerOnSale(array_unique($manufacturerList));
		$categories    = ModRedshopFilter::getParentCategoryOnSale($categoryList, $rootCategory, $categoryForSale);
		$rangePrice    = ModRedshopFilter::getRange($productIds);
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
	$productIds    = ModRedshopFilter::getProductByManufacturer($mid);
	$categories    = ModRedshopFilter::getCategorybyPids($productIds, $rootCategory, $categoryForSale);
	$rangePrice    = ModRedshopFilter::getRange($productIds);
}
elseif ($view == 'search')
{
	$modelSearch      = JModelLegacy::getInstance("Search", "RedshopModel");
	$products         = $modelSearch->getData();
	$manufacturerList = array();
	$categoryList     = array();
	$productIds       = array();

	foreach ($products as $key => $value)
	{
		$productIds[] = $value->product_id;

		if (!empty($value->manufacturer_id))
		{
			$manufacturerList[] = $value->manufacturer_id;
		}

		if (!empty($value->category_id))
		{
			$categoryList[] = $value->category_id;
		}
	}

	$manufacturers = ModRedshopFilter::getManufacturerOnSale(array_unique($manufacturerList));
	$categories    = ModRedshopFilter::getParentCategoryOnSale(array_unique($categoryList));
	$rangePrice    = ModRedshopFilter::getRange($productIds);
}

$rangeMin = $rangePrice['min'];
$rangeMax = $rangePrice['max'];

require JModuleHelper::getLayoutPath('mod_redshop_filter', $params->get('layout', 'default'));
