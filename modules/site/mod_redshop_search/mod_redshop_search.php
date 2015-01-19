<?php
/**
 * @package     RedSHOP.Frontend
 * @subpackage  mod_redshop_redshop_search
 *
 * @copyright   Copyright (C) 2008 - 2015 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

defined('_JEXEC') or die;

$option = JRequest::getCmd('option');
JLoader::import('redshop.library');

$session = JFactory::getSession();
$cart    = $session->get('cart');
$count   = 0;
if (isset($cart['idx']))
{
	$count = $cart['idx'];
}

require_once JPATH_ROOT . '/administrator/components/com_redshop/helpers/redshop.cfg.php';
JLoader::load('RedshopHelperAdminConfiguration');
$Redconfiguration = new Redconfiguration;
$Redconfiguration->defineDynamicVars();

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
$db = JFactory::getDbo();

if ($user->id > 0)
	$query = "SELECT sg.shopper_group_categories FROM `#__redshop_shopper_group` as sg LEFT JOIN #__redshop_users_info as uf ON sg.`shopper_group_id` = uf.shopper_group_id WHERE uf.user_id = " . (int) $user->id . " GROUP BY sg.shopper_group_id  AND sg.shopper_group_portal=1";
else
	$query = "SELECT sg.shopper_group_categories FROM `#__redshop_shopper_group` as sg WHERE  sg.`shopper_group_id` = '" . SHOPPER_GROUP_DEFAULT_UNREGISTERED . "' AND sg.shopper_group_portal=1";

$db->setQuery($query);
$shoppercatdata = $db->loadResult();

$catAcl = "";
if ($shoppercatdata)
	$catAcl = " AND category_id IN(" . $shoppercatdata . ")";

$query = "SELECT category_id as value,category_name as text FROM #__redshop_category WHERE published = 1 " . $catAcl . " order by category_name asc";
$db->setQuery($query);
$catdata = $db->LoadObjectList();

$manAcl = "";
if ($shoppercatdata)
	$manAcl = " AND manufacturer_id IN(" . $shoppercatdata . ")";
$query = "SELECT manufacturer_id as value,manufacturer_name AS text FROM #__redshop_manufacturer WHERE published = 1 " . $manAcl;
$db->setQuery($query);
$manufacturedata = $db->LoadObjectList();


$defaultSearchType      = trim($params->get('defaultSearchType', 'product_name'));
$showSearchTypeField    = trim($params->get('showSearchTypeField'));
$showSearchField        = trim($params->get('showSearchField'));
$showCategory           = trim($params->get('showCategory'));
$showManufacturer       = trim($params->get('showManufacturer'));
$showProductsearchtitle = trim($params->get('showProductsearchtitle'));
$showKeywordtitle       = trim($params->get('showKeywordtitle'));
$standardkeyword        = trim($params->get('stdsearchtext'));

$search_type      = JRequest::getWord('search_type', $defaultSearchType);
$cat_data         = (JRequest::getInt('category_id', '')); // Category Select Id
$manufacture_data = (JRequest::getInt('manufacturer_id', '')); // manufacturer_id Select Id

$lists            = array();
$cat              = array();
$cat[]            = JHTML::_('select.option', '0', JText::_('COM_REDSHOP_SELECT_CATEGORIES'));
$catdata          = @array_merge($cat, $catdata);
$lists['catdata'] = JHTML::_('select.genericlist', $catdata, 'category_id', 'class="inputbox" style="width: 163px;" size="1" searchcategory="1" onChange="loadProducts(this.value);' . $javaFun . '" ', 'value', 'text', $cat_data);

$manufacture              = array();
$manufacture[]            = JHTML::_('select.option', '0', JText::_('COM_REDSHOP_SELECT_MANUFACTURE'));
$manufacturedata          = @array_merge($manufacture, $manufacturedata);
$lists['manufacturedata'] = JHTML::_('select.genericlist', $manufacturedata, 'manufacturer_id', 'class="inputbox" style="width: 163px;" size="1" searchmanufacturer="1"  ', 'value', 'text', $manufacture_data);


$searchType = array();
//$searchType[]   = JHTML::_('select.option', '0',JText::_('COM_REDSHOP_SELECT'));
$searchType[]            = JHTML::_('select.option', 'product_name', JText::_('COM_REDSHOP_PRODUCT_NAME'));
$searchType[]            = JHTML::_('select.option', 'product_number', JText::_('COM_REDSHOP_PRODUCT_NUMBER'));
$searchType[]            = JHTML::_('select.option', 'name_number', JText::_("COM_REDSHOP_PRODUCT_NAME_AND_PRODUCT_NUMBER"));
$searchType[]            = JHTML::_('select.option', 'product_desc', JText::_("COM_REDSHOP_PRODUCT_DESCRIPTION"));
$searchType[]            = JHTML::_('select.option', 'virtual_product_num', JTEXT::_("COM_REDSHOP_VIRTUAL_PRODUCT_NUM"));
$searchType[]            = JHTML::_('select.option', 'name_desc', JText::_("COM_REDSHOP_PRODUCT_NAME_AND_PRODUCT_DESCRIPTION"));
$searchType[]            = JHTML::_('select.option', 'name_number_desc', JTEXT::_("COM_REDSHOP_PRODUCT_NAME_AND_PRODUCT_NUMBER_AND_VIRTUAL_PRODUCT_NUM_AND_PRODUCT_DESCRIPTION"));
$lists['searchtypedata'] = JHTML::_('select.genericlist', $searchType, 'search_type', 'class="inputbox" style="width: 163px;" size="1" onchange="setSearchType();' . $javaFun . '" ', 'value', 'text', $search_type);

require JModuleHelper::getLayoutPath('mod_redshop_search');

