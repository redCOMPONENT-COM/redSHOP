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
//ccc
defined ( '_JEXEC' ) or die ( 'Restricted access' );

jimport ( 'joomla.application.component.view' );

class userViewuser extends JView 
{
	function __construct($config = array()) 
	{
		parent::__construct ( $config );
	}
	
	function display($tpl = null) 
	{	
		global $mainframe, $context;
		$context = 'user_info_id';
		$userhelper 	= new rsUserhelper();
		$uri = & JFactory::getURI ();
		$sync = JRequest::getVar('sync');		
//		$user_id = JRequest::getVar( 'user_id', '', 'request', 'string');
//		$shipping = JRequest::getVar( 'shipping', '', 'request', 'string');
		$spgrp_filter = JRequest::getVar( 'spgrp_filter', '', 'request', 'string');
		$approved_filter = JRequest::getVar( 'approved_filter', '', 'request', 'string');
		$tax_exempt_request_filter = JRequest::getVar( 'tax_exempt_request_filter', '', 'request', 'string');
		
		$document = & JFactory::getDocument ();
		$document->setTitle ( JText::_('COM_REDSHOP_USER' ) );
		
		$model = $this->getModel('user');
		
//		if(!$shipping)
//		{
			JToolBarHelper::title ( JText::_('COM_REDSHOP_USER_MANAGEMENT' ), 'redshop_user48' );
//		}
//		else
//		{
//			JToolBarHelper::title ( JText::_('COM_REDSHOP_USER_SHIPPING_DETAIL' ), 'redshop_user48' );
//		}
		
		if($sync)
		{
			$this->setLayout('user_sync');
			$sync_user = $userhelper->userSynchronization();
			$this->assignRef ( 'sync_user',$sync_user );
		}
		else
		{
			$this->setLayout('default');
			JToolBarHelper::addNewX ();
			JToolBarHelper::editListX ();
//			JToolBarHelper::customX ( 'copy', 'copy.png', 'copy_f2.png', 'Copy', true );
			JToolBarHelper::deleteList ();
		}		
				
		$filter_order = $mainframe->getUserStateFromRequest ( $context . 'filter_order', 'filter_order', 'users_info_id' );
		$filter_order_Dir = $mainframe->getUserStateFromRequest ( $context . 'filter_order_Dir', 'filter_order_Dir', '' );
		$lists ['order'] = $filter_order;
		$lists ['order_Dir'] = $filter_order_Dir;
		
		$user = & $this->get ( 'Data' );
		$total = & $this->get ( 'Total' );
		$pagination = & $this->get ( 'Pagination' );
		$shopper_groups = $userhelper->getShopperGroupList();
		
//		$lists ['user_id'] = $user_id;
//	 	$lists ['shipping'] = $shipping;

	 	$temps = array();
		$temps[0]->value=0;
		$temps[0]->text=JText::_('COM_REDSHOP_SELECT');
		$shopper_groups=array_merge($temps,$shopper_groups);
	 	
	 	$lists['shopper_group'] = JHTML::_('select.genericlist',$shopper_groups,'spgrp_filter','class="inputbox" size="1" onchange="document.adminForm.submit()"','value','text',$spgrp_filter);
	 	
		$optiontax_req = array();		
		$optiontax_req[]   	= JHTML::_('select.option', 'select',JText::_('COM_REDSHOP_SELECT'));
		$optiontax_req[]   	= JHTML::_('select.option', '1', JText::_('COM_REDSHOP_yes'));
		$optiontax_req[]   	= JHTML::_('select.option', '0', JText::_('COM_REDSHOP_no'));
		$lists['tax_exempt_request'] 	= JHTML::_('select.genericlist',$optiontax_req,  'tax_exempt_request_filter', 'class="inputbox" size="1" onchange="document.adminForm.submit()"' , 'value', 'text',  $tax_exempt_request_filter );
		
		$this->assignRef ( 'lists', $lists );
		$this->assignRef ( 'user', $user );
		$this->assignRef ( 'pagination', $pagination );
		$this->assignRef ( 'request_url', $uri->toString () );
		parent::display ( $tpl );
	}
}
?>
