<?php
/**
 * @package     RedSHOP.Frontend
 * @subpackage  mod_redshop_search
 *
 * @copyright   Copyright (C) 2008 - 2017 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

defined('_JEXEC') or die;

require_once dirname(__FILE__) . '/helper.php';
JLoader::import('redshop.library');
JHtml::script('modules/mod_redshop_search/js/search.js');

$app      = JFactory::getApplication();
$input    = $app->input;
$user     = JFactory::getUser();
$document = JFactory::getDocument();
$document->addScriptDeclaration(
	"var base_url = '" . JURI::root() . "';"
);

$categoryData     = ModRedshopSearch::getCategories();
$manufacturerData = ModRedshopSearch::getManufacturers();

$enableAjaxsearch       = trim($params->get('enableAjaxsearch', 0));
$defaultSearchType      = trim($params->get('defaultSearchType', 'product_name'));
$showSearchTypeField    = trim($params->get('showSearchTypeField'));
$showSearchField        = trim($params->get('showSearchField'));
$showCategory           = trim($params->get('showCategory'));
$showManufacturer       = trim($params->get('showManufacturer'));
$showProductSearchTitle = trim($params->get('showProductsearchtitle'));
$showKeywordTitle       = trim($params->get('showKeywordtitle'));
$standardKeyword        = trim($params->get('stdsearchtext'));
$templateId             = trim($params->get('templateid'));
$productPerpage         = trim($params->get('productperpage'));
$modSearchItemid        = trim($params->get('modsearchitemid', ''));
$javaFun                = "";
$itemId                 = RedshopHelperUtility::getItemId();

if ($modSearchItemid != "")
{
	$itemId = $modSearchItemid;
}

if ($enableAjaxsearch)
{
	$document->addScript(JURI::base() . "administrator/components/com_redshop/assets/js/search.js");
	$document->addStyleSheet(JURI::base() . "administrator/components/com_redshop/assets/css/search.css");
	$javaFun = "makeUrl();";
}

$type    = $input->getWord('search_type', $defaultSearchType);
$cid     = $input->getInt('category_id', 0);
$mid     = $input->getInt('manufacturer_id', 0);
$keyword = $input->getString('keyword', $standardKeyword);

$lists            = array();
$category         = array();
$category[]       = JHtml::_('select.option', '0', JText::_('COM_REDSHOP_SELECT_CATEGORIES'));
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
$manufacturer[]           = JHtml::_('select.option', '0', JText::_('COM_REDSHOP_SELECT_MANUFACTURE'));
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
	JText::_('COM_REDSHOP_PRODUCT_NAME')
);
$searchType[] = JHtml::_(
	'select.option',
	'product_number',
	JText::_('COM_REDSHOP_PRODUCT_NUMBER')
);
$searchType[] = JHtml::_(
	'select.option',
	'name_number',
	JText::_("COM_REDSHOP_PRODUCT_NAME_AND_PRODUCT_NUMBER")
);
$searchType[] = JHtml::_(
	'select.option',
	'product_desc',
	JText::_("COM_REDSHOP_PRODUCT_DESCRIPTION")
);
$searchType[] = JHtml::_(
	'select.option',
	'virtual_product_num',
	JText::_("COM_REDSHOP_VIRTUAL_PRODUCT_NUM")
);
$searchType[] = JHtml::_(
	'select.option',
	'name_desc',
	JText::_("COM_REDSHOP_PRODUCT_NAME_AND_PRODUCT_DESCRIPTION")
);
$searchType[] = JHtml::_(
	'select.option',
	'name_number_desc',
	JText::_("COM_REDSHOP_PRODUCT_NAME_AND_PRODUCT_NUMBER_AND_VIRTUAL_PRODUCT_NUM_AND_PRODUCT_DESCRIPTION")
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

require JModuleHelper::getLayoutPath('mod_redshop_search', $params->get('layout', 'default'));
