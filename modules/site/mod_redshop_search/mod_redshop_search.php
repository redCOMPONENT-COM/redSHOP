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

$user = JFactory::getUser();
$document = JFactory::getDocument();
$document->addScriptDeclaration("
		var base_url = '" . JURI::root() . "';
	");

JHTML::script('modules/mod_redshop_search/js/search.js');
$enableAjaxsearch = trim($params->get('enableAjaxsearch', '0'));

$javaFun = "";

if ($enableAjaxsearch)
{
	$document->addScript(JURI::base() . "administrator/components/com_redshop/assets/js/search.js");
	$document->addStyleSheet(JURI::base() . "administrator/components/com_redshop/assets/css/search.css");
	$javaFun = "makeUrl();";
}

$app = JFactory::getApplication();
$db = JFactory::getDbo();
$userHelper = rsUserHelper::getInstance();
$shopperGroupId = RedshopHelperUser::getShopperGroup($user->id);
$shopperGroupData = Redshop\Helper\ShopperGroup::generateList($shopperGroupId);
$query = $db->getQuery(true)
	->select('id as value, name as text')
	->from($db->qn('#__redshop_category'))
	->where('published = 1')
	->order('name asc');

if (!empty($shopperGroupData) && isset($shopperGroupData[0]) && $shopperGroupData[0]->shopper_group_categories)
{
	$query->where('id IN(' . $shopperGroupData[0]->shopper_group_categories . ')');
}

$catdata = $db->setQuery($query)->LoadObjectList();

$query->clear()
	->select('manufacturer_id as value,manufacturer_name AS text')
	->from($db->qn('#__redshop_manufacturer'))
	->where('published = 1');

if (!empty($shopperGroupData) && isset($shopperGroupData[0]) && $shopperGroupData[0]->shopper_group_manufactures)
{
	$query->where('manufacturer_id IN(' . $shopperGroupData[0]->shopper_group_manufactures . ')');
}

$manufacturedata = $db->setQuery($query)->LoadObjectList();

$defaultSearchType      = trim($params->get('defaultSearchType', 'product_name'));
$showSearchTypeField    = trim($params->get('showSearchTypeField'));
$showSearchField        = trim($params->get('showSearchField'));
$showCategory           = trim($params->get('showCategory'));
$showManufacturer       = trim($params->get('showManufacturer'));
$showProductsearchtitle = trim($params->get('showProductsearchtitle'));
$showKeywordtitle       = trim($params->get('showKeywordtitle'));
$standardkeyword        = trim($params->get('stdsearchtext'));

$search_type      = $app->input->getWord('search_type', $defaultSearchType);

// Category Select Id
$cat_data         = $app->input->getInt('category_id', 0);

// Manufacturer_id Select Id
$manufacture_data = $app->input->getInt('manufacturer_id', 0);

$lists            = array();
$cat              = array();
$cat[]            = JHTML::_('select.option', '0', JText::_('COM_REDSHOP_SELECT_CATEGORIES'));
$catdata          = @array_merge($cat, $catdata);
$lists['catdata'] = JHTML::_('select.genericlist', $catdata, 'category_id', 'class="inputbox span12" size="1" searchcategory="1" onChange="loadProducts(this.value);' . $javaFun . '" ', 'value', 'text', $cat_data);

$manufacture              = array();
$manufacture[]            = JHTML::_('select.option', '0', JText::_('COM_REDSHOP_SELECT_MANUFACTURE'));
$manufacturedata          = @array_merge($manufacture, $manufacturedata);
$lists['manufacturedata'] = JHTML::_('select.genericlist', $manufacturedata, 'manufacturer_id', 'class="inputbox span12" size="1" searchmanufacturer="1"  ', 'value', 'text', $manufacture_data);

$searchType = array();
$searchType[]            = JHTML::_('select.option', 'product_name', JText::_('COM_REDSHOP_PRODUCT_NAME'));
$searchType[]            = JHTML::_('select.option', 'product_number', JText::_('COM_REDSHOP_PRODUCT_NUMBER'));
$searchType[]            = JHTML::_('select.option', 'name_number', JText::_("COM_REDSHOP_PRODUCT_NAME_AND_PRODUCT_NUMBER"));
$searchType[]            = JHTML::_('select.option', 'product_desc', JText::_("COM_REDSHOP_PRODUCT_DESCRIPTION"));
$searchType[]            = JHTML::_('select.option', 'virtual_product_num', JTEXT::_("COM_REDSHOP_VIRTUAL_PRODUCT_NUM"));
$searchType[]            = JHTML::_('select.option', 'name_desc', JText::_("COM_REDSHOP_PRODUCT_NAME_AND_PRODUCT_DESCRIPTION"));
$searchType[]            = JHTML::_('select.option', 'name_number_desc', JTEXT::_("COM_REDSHOP_PRODUCT_NAME_AND_PRODUCT_NUMBER_AND_VIRTUAL_PRODUCT_NUM_AND_PRODUCT_DESCRIPTION"));
$lists['searchtypedata'] = JHTML::_('select.genericlist', $searchType, 'search_type', 'class="inputbox span12" size="1" onchange="' . $javaFun . '" ', 'value', 'text', $search_type);

require JModuleHelper::getLayoutPath('mod_redshop_search', $params->get('layout', 'default'));
