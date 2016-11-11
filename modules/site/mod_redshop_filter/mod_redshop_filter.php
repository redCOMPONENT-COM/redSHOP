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
$categoryForSale    = $params->get('category_for_sale', array());
$enableCategory     = $params->get('category');
$enableManufacturer = $params->get('manufacturer');
$enablePrice        = $params->get('price');
$enableKeyword      = $params->get('keyword');
$template           = $params->get('template_id');
$view               = $input->getString('view', '');
$layout             = $input->getString('layout', '');
$keyword            = $input->post->getString('keyword', '');
$productOnSale      = 0;

if (!empty($cid))
{
	if (in_array($cid, $categoryForSale))
	{
		$productList = array();
		$catList     = array();
		$manuList    = array();
		$pids        = array();

		if ($cid == $rootCategory)
		{
			foreach ($categoryForSale as $k => $value)
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
			$catList[]  = $value->categories;
			$manuList[] = $value->manufacturer_id;
			$pids[]     = $value->product_id;
		}

		foreach ($catList as $key => $value)
		{
			if (!empty($value))
			{
				foreach ($value as $val)
				{
					$cats[] = $val;
				}
			}
		}

		$cats          = implode(',', $catList);
		$manufacturers = ModRedshopFilter::getManufacturerOnSale(array_unique($manuList));
		$categories    = ModRedshopFilter::getParentCategoryOnSale(array_unique(explode(',', $cats)));
		$rangePrice    = ModRedshopFilter::getRange($pids);
	}
	else
	{
		$categories    = ModRedshopFilter::getParentCategory($cid);
		$rangePrice    = ModRedshopFilter::getRangeMaxMin($cid);
		$manufacturers = ModRedshopFilter::getManufacturers($cid);
	}

	$action = JRoute::_("index.php?option=com_redshop&view=search");
}

$rangeMin = $rangePrice['min'];
$rangeMax = $rangePrice['max'];

require JModuleHelper::getLayoutPath('mod_redshop_filter', $params->get('layout', 'default'));
