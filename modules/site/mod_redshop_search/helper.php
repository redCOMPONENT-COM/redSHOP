<?php
/**
 * @package     RedSHOP.Frontend
 * @subpackage  mod_redshop_redmanufacturer
 *
 * @copyright   Copyright (C) 2008 - 2015 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

defined('_JEXEC') or die;

JLoader::import('redshop.library');

/**
 * Helper for mod_redmanufacturer
 *
 * @since  1.5
 */
abstract class ModRedshopSearchHelper
{
	/**
	 * Process data
	 *
	 * @param   array   &$params           Module parameters
	 * @param   array   &$catData          Category data
	 * @param   array   &$manufactureData  Manufacture data
	 * @param   array   &$cat              Categories data
	 * @param   array   &$lists            Lists data
	 * @param   array   &$searchType       Search Type
	 * @param   string  &$javaFun          string javascript
	 *
	 * @return  void
	 */
	public static function processCategoryAndManufacture(&$params, &$catData, &$manufactureData, &$cat, &$lists, &$searchType, &$javaFun)
	{
		$user 		= JFactory::getUser();
		$document 	= JFactory::getDocument();
		$app 		= JFactory::getApplication();
		$defaultSearchType      = trim($params->get('defaultSearchType', 'product_name'));
		$showSearchTypeField    = trim($params->get('showSearchTypeField'));
		$showSearchField        = trim($params->get('showSearchField'));
		$showCategory           = trim($params->get('showCategory'));
		$showManufacturer       = trim($params->get('showManufacturer'));
		$showProductsearchtitle = trim($params->get('showProductsearchtitle'));
		$showKeywordtitle       = trim($params->get('showKeywordtitle'));
		$standardkeyword        = trim($params->get('stdsearchtext'));
		$searchTypeParam   		= $app->input->getWord('search_type', $defaultSearchType);

		// Category Select Id
		$catId            = $app->input->getInt('category_id', 0);

		// Manufacturer_id Select Id
		$manufactureId    = $app->input->getInt('manufacturer_id', 0);

		$db 			  = JFactory::getDbo();
		$userHelper 	  = rsUserHelper::getInstance();
		$shopperGroupId   = RedshopHelperUser::getShopperGroup($user->id);
		$shopperGroupData = $userHelper->getShopperGroupList($shopperGroupId);

		$result = ['catadata' => [], 'manufacturedata' => []];

		$query = $db->getQuery(true)
			->select([$db->qn('category_id', 'value'), $db->qn('category_name', 'text')])
			->from($db->qn('#__redshop_category'))
			->where($db->qn('published') . ' = 1')
			->order($db->qn('category_name') . ' ASC');

		if ($shopperGroupData && isset($shopperGroupData[0]) && $shopperGroupData[0]->shopper_group_categories)
		{
			$query->where($db->qn('category_id') . ' IN(' . $db->q($shopperGroupData[0]->shopper_group_categories) . ')');
		}

		$catData = $db->setQuery($query)->LoadObjectList();

		$query->clear()
			->select([$db->qn('manufacturer_id', 'value'), $db->qn('manufacturer_name', 'text')])
			->from($db->qn('#__redshop_manufacturer'))
			->where($db->qn('published') . ' = 1');

		if ($shopperGroupData && isset($shopperGroupData[0]) && $shopperGroupData[0]->shopper_group_manufactures)
		{
			$query->where($db->qn('manufacturer_id') . ' IN(' . $db->q($shopperGroupData[0]->shopper_group_manufactures) . ')');
		}

		$manufactureData = $db->setQuery($query)->LoadObjectList();

		$cat[]            = JHTML::_('select.option', '0', JText::_('COM_REDSHOP_SELECT_CATEGORIES'));
		$catData          = @array_merge($cat, $catData);
		$lists['catdata'] = JHTML::_('select.genericlist', $catData, 'category_id', 'class="inputbox span12" size="1" searchcategory="1" onChange="loadProducts(this.value);' . $javaFun . '" ', 'value', 'text', $catId);

		$manufacture              = array();
		$manufacture[]            = JHTML::_('select.option', '0', JText::_('COM_REDSHOP_SELECT_MANUFACTURE'));
		$manufactureData          = @array_merge($manufacture, $manufactureData);
		$lists['manufacturedata'] = JHTML::_('select.genericlist', $manufactureData, 'manufacturer_id', 'class="inputbox span12" size="1" searchmanufacturer="1"  ', 'value', 'text', $manufactureId);

		$searchType[]	= JHTML::_('select.option', 'product_name', JText::_('COM_REDSHOP_PRODUCT_NAME'));
		$searchType[]	= JHTML::_('select.option', 'product_number', JText::_('COM_REDSHOP_PRODUCT_NUMBER'));
		$searchType[]	= JHTML::_('select.option', 'name_number', JText::_("COM_REDSHOP_PRODUCT_NAME_AND_PRODUCT_NUMBER"));
		$searchType[]	= JHTML::_('select.option', 'product_desc', JText::_("COM_REDSHOP_PRODUCT_DESCRIPTION"));
		$searchType[]	= JHTML::_('select.option', 'virtual_product_num', JTEXT::_("COM_REDSHOP_VIRTUAL_PRODUCT_NUM"));
		$searchType[]	= JHTML::_('select.option', 'name_desc', JText::_("COM_REDSHOP_PRODUCT_NAME_AND_PRODUCT_DESCRIPTION"));
		$searchType[]	= JHTML::_('select.option', 'name_number_desc', JTEXT::_("COM_REDSHOP_PRODUCT_NAME_AND_PRODUCT_NUMBER_AND_VIRTUAL_PRODUCT_NUM_AND_PRODUCT_DESCRIPTION"));
		$lists['searchtypedata'] = JHTML::_('select.genericlist', $searchType, 'search_type', 'class="inputbox span12" size="1" onchange="' . $javaFun . '" ', 'value', 'text', $searchTypeParam);
	}
}
