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

jimport( 'joomla.application.component.view' );

require_once( JPATH_COMPONENT_ADMINISTRATOR.DS.'helpers'.DS.'extra_field.php' );
require_once( JPATH_COMPONENT_ADMINISTRATOR.DS.'helpers'.DS.'order.php' );

class addquotation_detailVIEWaddquotation_detail extends JView
{
	function display($tpl = null)
	{
		$option = JRequest::getVar('option');
		$order_functions = new order_functions();
		$extra_field = new extra_field();
		$document = & JFactory::getDocument();
		$document->setTitle( JText::_('QUOTATION_MANAGEMENT') );
		
		$document->addScript ('components/'.$option.'/assets/js/json.js');
		$document->addScript ('components/'.$option.'/assets/js/validation.js');
		$document->addScript ('components/'.$option.'/assets/js/order.js');
		$document->addScript ('components/'.$option.'/assets/js/common.js');
		$document->addScript ('components/'.$option.'/assets/js/select_sort.js');
		$document->addStyleSheet ( 'components/'.$option.'/assets/css/search.css' );
		$document->addScript ('components/'.$option.'/assets/js/search.js');
		
		$session =& JFactory::getSession();
		$uri 		=& JFactory::getURI();
//		$layout = JRequest::getVar('layout');
		$lists = array();
		$billing = array();
		$model = $this->getModel();
		$detail	=& $this->get('data');
		$Redconfiguration = new Redconfiguration();
		
		$user_id = JRequest::getVar('user_id', 0);
		if($user_id!=0)
		{
			$billing = $order_functions->getBillingAddress($user_id);
		} else {
			$billing = $model->setBilling();
		}
		$detail->user_id = $user_id;
		
		$session->set( 'offlineuser_id',$user_id );
		
		$userop[0]->user_id = 0;
		$userop[0]->text = JText::_('SELECT');
		$userlists = $model->getUserData(0,"BT");
		$userlist = array_merge($userop,$userlists);
		$lists['userlist'] 	= JHTML::_('select.genericlist',$userlist, 'user_id', 'class="inputbox" onchange="showquotationUserDetail();" ' , 'user_id', 'text',  $user_id );

		JToolBarHelper::title(JText::_( 'QUOTATION_MANAGEMENT' ).': <small><small>[ '.JText::_( 'NEW' ).' ]</small></small>', 'redshop_order48' );
		
		JToolBarHelper::save();
		JToolBarHelper::custom( 'send','send.png','send.png',JText::_('SEND'),false);
		JToolBarHelper::cancel();
		
		// PRODUCT/ATTRIBUTE STOCK ROOM QUANTITY CHECKING IS IMPLEMENTED

		$countryarray = $Redconfiguration->getCountryList((array)$billing);
		$billing->country_code = $countryarray['country_code'];
		$lists['country_code'] = $countryarray['country_dropdown'];
		$statearray = $Redconfiguration->getStateList((array)$billing);
		$lists['state_code'] = $statearray['state_dropdown'];
		$lists['quotation_extrafield'] = $extra_field->list_all_field(16, $billing->users_info_id);
		
		$this->assignRef('lists',		$lists);
		$this->assignRef('detail',		$detail);
		$this->assignRef('billing',		$billing);
		$this->assignRef('userlist',	$userlists);
		$this->assignRef('request_url',	$uri->toString());
		parent::display($tpl);
	}
}
?>