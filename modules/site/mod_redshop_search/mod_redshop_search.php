<?php
/**
 * @package     RedSHOP.Frontend
 * @subpackage  mod_redshop_search
 *
 * @copyright   Copyright (C) 2008 - 2019 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

use Joomla\Registry\Registry;

defined('_JEXEC') or die;

/** @var  Registry $params Module params */

require_once dirname(__FILE__) . '/helper.php';
JLoader::import('redshop.library');
JHtml::script('modules/mod_redshop_search/js/search.min.js');

$app      = JFactory::getApplication();
$input    = $app->input;
$user     = JFactory::getUser();
$document = JFactory::getDocument();
$document->addScriptDeclaration(
    "var base_url = '" . JUri::root() . "';"
);

$enableAjaxsearch            = trim($params->get('enableAjaxsearch', 0));
$defaultSearchType           = trim($params->get('defaultSearchType', 'product_name'));
$searchProductByCategoryName = trim($params->get('searchProductByCategoryName'));
$showSearchTypeField         = trim($params->get('showSearchTypeField'));
$showSearchField             = trim($params->get('showSearchField'));
$showCategory                = trim($params->get('showCategory'));
$showManufacturer            = trim($params->get('showManufacturer'));
$showProductSearchTitle      = trim($params->get('showProductsearchtitle'));
$showKeywordTitle            = trim($params->get('showKeywordtitle'));
$standardKeyword             = trim($params->get('stdsearchtext'));
$templateId                  = trim($params->get('templateid'));
$productPerpage              = trim($params->get('productperpage'));
$modSearchItemid             = trim($params->get('modsearchitemid', ''));
$productFields               = $params->get('product_fields', array());
$showCustomfield             = trim($params->get('showCustomfield', ''));
$excludeCategories           = implode(',', $params->get('excludeCategories', array()));
$javaFun                     = "";
$itemId                      = RedshopHelperRouter::getItemId();

$categoryData     = ModRedshopSearch::getCategories();
$manufacturerData = ModRedshopSearch::getManufacturers();
$fieldData        = ModRedshopSearch::getCustomFields($productFields);

if ($modSearchItemid != "") {
    $itemId = $modSearchItemid;
}

if ($enableAjaxsearch) {
    /** @scrutinizer ignore-deprecated */
    JHtml::script('com_redshop/redshop.search.min.js', false, true);
    /** @scrutinizer ignore-deprecated */
    JHtml::stylesheet('com_redshop/redshop.search.min.css', array(), true);
    $javaFun = "makeUrl();";
}

$type    = $input->getWord('search_type', $defaultSearchType);
$cid     = $input->getInt('category_id', 0);
$mid     = $input->getInt('manufacturer_id', 0);
$keyword = $input->getString('keyword', $standardKeyword);
$fields  = $input->get('custom_field', array(), 'array');

$lists            = array();
$category         = array();
$category[]       = JHtml::_('select.option', '0', JText::_('MOD_REDSHOP_SEARCH_SELECT_CATEGORIES'));
$categoryData     = array_merge($category, $categoryData);
$lists['catdata'] = JHtml::_(
    'select.genericlist',
    $categoryData,
    'category_id',
    'class="inputbox span12" size="1" searchcategory="1" onChange="loadProducts(this.value);' . $javaFun . '" ',
    'value',
    'text',
    $cid
);

$manufacturer             = array();
$manufacturer[]           = JHtml::_('select.option', '0', JText::_('MOD_REDSHOP_SEARCH_SELECT_MANUFACTURE'));
$manufacturerData         = array_merge($manufacturer, $manufacturerData);
$lists['manufacturedata'] = JHtml::_(
    'select.genericlist',
    $manufacturerData,
    'manufacturer_id',
    'class="inputbox span12" size="1" searchmanufacturer="1"  ',
    'value',
    'text',
    $mid
);

$searchType   = array();
$searchType[] = JHtml::_(
    'select.option',
    'product_name',
    JText::_('MOD_REDSHOP_SEARCH_PRODUCT_NAME')
);
$searchType[] = JHtml::_(
    'select.option',
    'product_number',
    JText::_('MOD_REDSHOP_SEARCH_PRODUCT_NUMBER')
);
$searchType[] = JHtml::_(
    'select.option',
    'name_number',
    JText::_("MOD_REDSHOP_SEARCH_PRODUCT_NAME_AND_PRODUCT_NUMBER")
);
$searchType[] = JHtml::_(
    'select.option',
    'product_desc',
    JText::_("MOD_REDSHOP_SEARCH_PRODUCT_DESCRIPTION")
);
$searchType[] = JHtml::_(
    'select.option',
    'virtual_product_num',
    JText::_("MOD_REDSHOP_SEARCH_VIRTUAL_PRODUCT_NUM")
);
$searchType[] = JHtml::_(
    'select.option',
    'name_desc',
    JText::_("MOD_REDSHOP_SEARCH_PRODUCT_NAME_AND_PRODUCT_DESCRIPTION")
);
$searchType[] = JHtml::_(
    'select.option',
    'name_number_desc',
    JText::_("MOD_REDSHOP_SEARCH_PRODUCT_NAME_AND_PRODUCT_NUMBER_AND_VIRTUAL_PRODUCT_NUM_AND_PRODUCT_DESCRIPTION")
);

$lists['searchtypedata'] = JHtml::_(
    'select.genericlist',
    $searchType,
    'search_type',
    'class="inputbox span12" size="1" onchange="' . $javaFun . '" ',
    'value',
    'text',
    $type
);

$twigParams = array(
    'itemid'                 => $itemId,
    'formAction'             => JRoute::_('index.php?option=com_redshop&view=search&Itemid=' . $itemId),
    'goodGuy'                => !defined('JEXEC'),
    'showProductSearchTitle' => $showProductSearchTitle,
    'showSearchTypeField'    => $showSearchTypeField,
    'type'                   => $type,
    'showCategory'           => $showCategory,
    'showManufacturer'       => $showManufacturer,
    'showCustomfield'        => $showCustomfield,
    'showKeywordTitle'       => $showKeywordTitle,
    'showSearchField'        => $showSearchField,
    'templateId'             => $templateId,
    'productPerpage'         => $productPerpage,
    'excludeCategories'      => $excludeCategories,
    'searchProductByCatName' => $searchProductByCategoryName,
    'keyword'                => $keyword,
    'data'                   => $lists
);

$layout     = $params->get('layout', 'default');
$moduleName = 'mod_redshop_search';

echo RedshopLayoutHelper::render(
    $layout,
    $twigParams,
    '',
    array(
        'component'  => 'com_redshop',
        'layoutType' => 'Twig',
        'layoutOf'   => 'module',
        'prefix'     => $moduleName
    )
);
