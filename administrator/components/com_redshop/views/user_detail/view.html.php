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
require_once( JPATH_COMPONENT.DS.'helpers'.DS.'extra_field.php' );
require_once( JPATH_COMPONENT_SITE.DS.'helpers'.DS.'helper.php' );

jimport( 'joomla.application.component.view' );

class user_detailVIEWuser_detail extends JView
{
	function display($tpl = null)
	{
 		$Redconfiguration = new Redconfiguration();
 		$userhelper 	= new rsUserhelper();
 		$extra_field = new extra_field();

		$shipping = JRequest::getVar( 'shipping', '', 'request', 'string');
		$option = JRequest::getVar( 'option', '', 'request', 'string');

		$document = JFactory::getDocument();
		$document->addScript ('components/'.$option.'/assets/js/json.js');
		$document->addScript ('components/'.$option.'/assets/js/validation.js');

		$uri = JFactory::getURI();

		$this->setLayout('default');

		$lists = array();
		$detail	= $this->get('data');
		$isNew		= ($detail->users_info_id < 1);
		$text = $isNew ? JText::_('COM_REDSHOP_NEW' ) : JText::_('COM_REDSHOP_EDIT' );

		if($shipping)
		{
			JToolBarHelper::title(   JText::_('COM_REDSHOP_USER_SHIPPING_DETAIL' ).': <small><small>[ ' . $text.' ]</small></small>', 'redshop_user48' );
		}
		else
		{
			JToolBarHelper::title(   JText::_('COM_REDSHOP_USER_MANAGEMENT_DETAIL' ).': <small><small>[ ' . $text.' ]</small></small>' , 'redshop_user48' );
		}
		JToolBarHelper::apply();
		JToolBarHelper::save();

		if ($isNew) {
			JToolBarHelper::cancel();
		} else {
			JToolBarHelper::customX ( 'order', 'redshop_order32', '', JText::_('COM_REDSHOP_PLACE_ORDER'), false);
			JToolBarHelper::cancel ( 'cancel', 'Close' );
		}

		$pagination = & $this->get ( 'Pagination' );

		// get groups

		$user_groups = $userhelper->getUserGroupList($detail->users_info_id);
		$detail->user_groups = $user_groups;

		$shopper_detail = $userhelper->getShopperGroupList();
		$temps = array();
		$temps[0]->value=0;
		$temps[0]->text=JText::_('COM_REDSHOP_SELECT');
		$shopper_detail=array_merge($temps,$shopper_detail);
		$lists['shopper_group'] 	= JHTML::_('select.genericlist',   $shopper_detail, 'shopper_group_id', '', 'value', 'text', $detail->shopper_group_id );

		//$gtree = $acl->get_group_children_tree( null, 'USERS', false );
		//$lists['gid'] 	= JHTML::_('select.genericlist',   $gtree, 'gid', 'size="10"', 'value', 'text', $detail->gid );

		$lists['tax_exempt'] 		= JHTML::_('select.booleanlist',  'tax_exempt', 'class="inputbox"', $detail->tax_exempt );

		$lists['block'] 			= JHTML::_('select.booleanlist',  'block', 'class="inputbox"', $detail->block );

		$lists['tax_exempt_approved'] 			= JHTML::_('select.booleanlist',  'tax_exempt_approved', 'class="inputbox"', $detail->tax_exempt_approved );

		$lists['requesting_tax_exempt'] 		= JHTML::_('select.booleanlist',  'requesting_tax_exempt', 'class="inputbox"', $detail->requesting_tax_exempt );

//		$lists['approved'] 			= JHTML::_('select.booleanlist',  'approved', 'class="inputbox"', $detail->approved );

		$lists['is_company'] 		= JHTML::_('select.booleanlist',  'is_company', 'class="inputbox" onchange="showOfflineCompanyOrCustomer(this.value);" ', $detail->is_company ,JText::_('COM_REDSHOP_USER_COMPANY'),JText::_('COM_REDSHOP_USER_CUSTOMER') );

		$lists['sendEmail'] 		= JHTML::_('select.booleanlist',  'sendEmail', 'class="inputbox"', $detail->sendEmail );


		$lists['extra_field'] = $extra_field->list_all_field(6, $detail->users_info_id); /// field_section 6 :Userinformations
		$lists['customer_field'] = $extra_field->list_all_field(7, $detail->users_info_id); /// field_section 7 :Customer Address
		$lists['company_field'] = $extra_field->list_all_field(8, $detail->users_info_id); /// field_section 8 :Company Address
		$lists['shipping_customer_field'] = $extra_field->list_all_field(14, $detail->users_info_id); /// field_section 7 :Customer Address
		$lists['shipping_company_field'] = $extra_field->list_all_field(15, $detail->users_info_id); /// field_section 8 :Company Address

		$countryarray = $Redconfiguration->getCountryList((array)$detail);
		$detail->country_code = $countryarray['country_code'];
		$lists['country_code'] = $countryarray['country_dropdown'];
		$statearray = $Redconfiguration->getStateList((array)$detail);
		$lists['state_code'] = $statearray['state_dropdown'];
		//$showcountry = (count($countryarray['countrylist'])==1 && count($statearray['statelist'])==0) ? 0 : 1;
		//$showstates = ($statearray['is_states']<=0) ? 0 : 1;

		$this->assignRef('lists',			$lists);
		$this->assignRef('detail',		$detail);
		$this->assignRef('request_url',	$uri->toString());
		$this->assignRef('pagination', $pagination );
		parent::display($tpl);
	}
}

