<?php
/**
 * @copyright Copyright (C) 2010 redCOMPONENT.com. All rights reserved.
 * @license GNU/GPL, see license.txt or http://www.gnu.org/copyleft/gpl.html
 * Developed by email@recomponent.com - redCOMPONENT.com
 *
 * redSHOP can be downloaded from www.redcomponent.com
 * redSHOP is free software; you can redistribute it and/or
 * modify it under the terms of the GNU General Public License 2
 * as published by the Free Software Foundation.
 *
 * You should have received a copy of the GNU General Public License
 * along with redSHOP; if not, write to the Free Software
 * Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston, MA  02110-1301, USA.
 */
defined( '_JEXEC' ) or die( 'Restricted access' );
require_once( JPATH_COMPONENT.DS.'helpers'.DS.'shopper.php' );
jimport( 'joomla.application.component.view' );
require_once( JPATH_COMPONENT.DS.'helpers'.DS.'category.php' );

class shopper_group_detailVIEWshopper_group_detail extends JView
{
	function display($tpl = null)
	{
		JToolBarHelper::title(   JText::_('COM_REDSHOP_SHOPPER_GROUP_MANAGEMENT_DETAIL' ), 'redshop_manufact48' );

		$shoppergroup = new shoppergroup();
		$redhelper = new redhelper();

		$option = JRequest::getVar('option','','request','string');

		$document = JFactory::getDocument();

		//$document->addScript ('components/'.$option.'/assets/js/media.js');
		$document->addScript ('components/'.$option.'/assets/js/json.js');
		$document->addScript ('components/'.$option.'/assets/js/validation.js');

		$uri = JFactory::getURI();

		$this->setLayout('default');

		$lists = array();

		$detail	=& $this->get('data');

		$isNew = ($detail->shopper_group_id < 1);

		$text = $isNew ? JText::_('COM_REDSHOP_NEW' ) : JText::_('COM_REDSHOP_EDIT' );

		JToolBarHelper::title(   JText::_('COM_REDSHOP_SHOPPER_GROUP' ).': <small><small>[ ' . $text.' ]</small></small>' , 'redshop_manufact48' );

		JToolBarHelper::apply();

		JToolBarHelper::save();

		if ($isNew)  {
			JToolBarHelper::cancel();
		} else {

			JToolBarHelper::cancel( 'cancel', 'Close' );
		}

		$groups = $shoppergroup->list_all("parent_id", $detail->shopper_group_id );
		$lists['groups'] = $groups ;
		$model = $this->getModel('shopper_group_detail');
		$optioncustomer = array();
		$optioncustomer[]   	= JHTML::_('select.option', '-1',JText::_('COM_REDSHOP_SELECT'));
		$optioncustomer[]   	= JHTML::_('select.option', '0', JText::_('COM_REDSHOP_COMPANY'));
		$optioncustomer[]   	= JHTML::_('select.option', '1', JText::_('COM_REDSHOP_PRIVATE'));
		$lists['customertype'] 	= JHTML::_('select.genericlist',$optioncustomer,  'shopper_group_customer_type', 'class="inputbox" size="1" ' , 'value', 'text',  $detail->shopper_group_customer_type );

		$lists['portal'] = JHTML::_('select.booleanlist','shopper_group_portal', 'class="inputbox"', $detail->shopper_group_portal );
		$lists['default_shipping'] = JHTML::_('select.booleanlist','default_shipping', 'class="inputbox"', $detail->default_shipping );
		$lists['published'] = JHTML::_('select.booleanlist','published', 'class="inputbox"', $detail->published );
//		$lists['tax_exempt'] = JHTML::_('select.booleanlist','tax_exempt', 'class="inputbox"', $detail->tax_exempt );
//		$lists['tax_exempt_on_shipping'] = JHTML::_('select.booleanlist','tax_exempt_on_shipping', 'class="inputbox"', $detail->tax_exempt_on_shipping );
		$lists['show_price_without_vat'] = JHTML::_('select.booleanlist','show_price_without_vat', 'class="inputbox"', $detail->show_price_without_vat );

//		$lists['apply_product_price_vat'] = JHTML::_('select.booleanlist','apply_product_price_vat', 'class="inputbox"', $detail->apply_product_price_vat );
		$lists['shopper_group_quotation_mode'] = JHTML::_('select.booleanlist','shopper_group_quotation_mode', 'class="inputbox"', $detail->shopper_group_quotation_mode );

		// for individual show_price and catalog

		$show_price_data = $redhelper->getPreOrderByList();
		$lists['show_price'] = JHTML::_('select.genericlist',$show_price_data,'show_price','class="inputbox" size="1" ','value','text',$detail->show_price);
        $lists['use_as_catalog'] = JHTML::_('select.genericlist',$show_price_data,'use_as_catalog','class="inputbox" size="1" ','value','text',$detail->use_as_catalog);

		$shopper_group_categories = $detail->shopper_group_categories;
		$shopper_group_categories = explode(",",$shopper_group_categories);
		$shoppergroup_category = new product_category();

		$categories = $shoppergroup_category->list_all("shopper_group_categories[]",0,$shopper_group_categories,20,true,true,array(),250);
		$lists['categories'] =$categories;

		$shopper_group_manufactures = $detail->shopper_group_manufactures;
		$shopper_group_manufactures = explode(",",$shopper_group_manufactures);
		$manufacturers	= $model->getmanufacturers();
		$lists['manufacturers'] = JHTML::_('select.genericlist',$manufacturers,'shopper_group_manufactures[]','class="inputbox"  multiple="multiple"  size="10" style="width: 250px;"> ','value','text',$shopper_group_manufactures);

		$vatgroup = $model->getVatGroup();
		$tmp = array();
		$tmp[] = JHTML::_('select.option', 0, JText::_('COM_REDSHOP_SELECT'));
		$vatgroup = @array_merge($tmp,$vatgroup);
		//$shoppergrp_type = array (JHTML::_('select.option','', JText::_('COM_REDSHOP_SELECT')),JHTML::_('select.option','B2B customers',JText::_('COM_REDSHOP_B2B_CUSTOMERS')), JHTML::_('select.option','Large business',JText::_('COM_REDSHOP_B2B_LARGE_BUSINESS')), JHTML::_('select.option','Small business',JText::_('COM_REDSHOP_B2B_SMALL_BUSINESS')),  JHTML::_('select.option','B2C customers',JText::_('COM_REDSHOP_B2C_CUSTOMERS')), JHTML::_('select.option','Local customers',JText::_('COM_REDSHOP_B2C_LOCAL_CUSTOMERS')), JHTML::_('select.option','International customers',JText::_('COM_REDSHOP_B2C_INTERNATIONAL_CUSTOMERS')) );
        //$lists['shoppergrp_type'] = JHTML::_('select.genericlist',  $shoppergrp_type, 'group_type', 'class="inputbox" size="1"', 'value', 'text',  $detail->group_type );
		$lists['tax_group_id'] = JHTML::_('select.genericlist',  $vatgroup, 'tax_group_id', 'class="inputbox" size="1"', 'value', 'text',  $detail->tax_group_id );

		$this->assignRef('lists',		$lists);
		$this->assignRef('detail',		$detail);
		$this->assignRef('request_url',	$uri->toString());

		parent::display($tpl);
	}
}

