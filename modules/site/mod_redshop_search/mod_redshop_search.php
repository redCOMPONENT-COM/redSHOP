<?php
/**
 * @package     RedSHOP.Frontend
 * @subpackage  mod_redshop_redshop_search
 *
 * @copyright   Copyright (C) 2008 - 2015 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

defined('_JEXEC') or die;

JLoader::import('redshop.library');
JLoader::import('helper', __DIR__);

$user 		= JFactory::getUser();
$document 	= JFactory::getDocument();
$app 		= JFactory::getApplication();


JHtml::script('mod_redshop_search/search.js', false, true);

$document->addScriptDeclaration("
		var base_url = '" . JURI::root() . "';
	");

$defaultSearchType      = trim($params->get('defaultSearchType', 'product_name'));
$showSearchTypeField    = trim($params->get('showSearchTypeField'));
$showSearchField        = trim($params->get('showSearchField'));
$showCategory           = trim($params->get('showCategory'));
$showManufacturer       = trim($params->get('showManufacturer'));
$showProductSearchTitle = trim($params->get('showProductsearchtitle'));
$showKeywordTitle       = trim($params->get('showKeywordtitle'));
$standardKeyword        = trim($params->get('stdsearchtext'));
$searchTypeParam   		= $app->input->getWord('search_type', $defaultSearchType);

// Category Select Id
$catId            = $app->input->getInt('category_id', 0);

// Manufacturer_id Select Id
$manufactureId    = $app->input->getInt('manufacturer_id', 0);


$enableAjaxSearch = trim($params->get('enableAjaxsearch', '0'));

$javaFun = "";

if ($enableAjaxSearch)
{
	$document->addScript(JURI::base() . "administrator/components/com_redshop/assets/js/search.js");
	$document->addStyleSheet(JURI::base() . "administrator/components/com_redshop/assets/css/search.css");
	$javaFun = "makeUrl();";
}

$lists 			 = [];
$cat 			 = [];
$catData 		 = [];
$manufactureData = [];
$searchType 	 = [];

ModRedshopSearchHelper::processCategoryAndManufacture($params, $catData, $manufactureData, $cat, $lists, $searchType, $javaFun);

require JModuleHelper::getLayoutPath('mod_redshop_search', $params->get('layout', 'default'));
