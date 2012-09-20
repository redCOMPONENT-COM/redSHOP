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
 
class product_containerViewproduct_container extends JView
{
	function __construct( $config = array())
	{
		 parent::__construct( $config );
	}
    
	function display($tpl = null)
	{	
		global $mainframe, $context;

		$model = $this->getModel ( 'product_container' );
		$document = & JFactory::getDocument();
		
   		$container = JRequest::getVar('container','','request',0);
   		
   		$preorder = JRequest::getVar('preorder','','request',0);
   		
   		$newproducts = JRequest::getVar('newproducts','','request',0);
   		
   		$existingproducts = JRequest::getVar('existingproducts','','request',0);
   		
   		if($preorder == '1') {
   		
   			$tpl = 'preorder';
   		
   		
   		}

		if($container == 1)
		{
			$document->setTitle( JText::_('COM_REDSHOP_CONTAINER_ORDER_PRODUCTS'));
			JToolBarHelper::title(   JText::_('COM_REDSHOP_CONTAINER_ORDER_PRODUCTS' ), 'redshop_container48' );
			
	        JToolBarHelper::custom('export_data','save.png','save_f2.png','Export Data',false);
		
			JToolBarHelper::custom('print_data','save.png','save_f2.png','Print Data',false);	 	
	 	}else
		{
			JToolBarHelper::title(   JText::_('COM_REDSHOP_CONTAINER_PRE_ORDER' ) , 'redshop_container48' );
			$document->setTitle( JText::_('COM_REDSHOP_CONTAINER_PRE_ORDER') );
			JToolBarHelper::custom('addcontainer' ,'new.png','new_f2.png','Add new container',false);
		}
   		 	
 		/*JToolBarHelper::addNewX();
 		JToolBarHelper::editListX();		
		JToolBarHelper::deleteList();		
		JToolBarHelper::publishList();
		JToolBarHelper::unpublishList();*/
	   
		$uri	=& JFactory::getURI();
		
		$filter_order     = $mainframe->getUserStateFromRequest( $context.'filter_order',      'filter_order', 	  'product_id' );
		$filter_order_Dir = $mainframe->getUserStateFromRequest( $context.'filter_order_Dir',  'filter_order_Dir', '' );		
		
		//$filter_manufacturer	  = $mainframe->getUserStateFromRequest( $context.'filter_manufacturer',		'filter_manufacturer',		0);
		$filter_supplier	  = $mainframe->getUserStateFromRequest( $context.'filter_supplier',		'filter_supplier',		0);
		$filter_container	  = $mainframe->getUserStateFromRequest( $context.'filter_container',		'filter_container',		0);
	
		$lists['order'] = $filter_order;  
		$lists['order_Dir'] = $filter_order_Dir; 
		$products	= & $this->get( 'Data');
		$total = & $this->get( 'Total');
		$pagination = & $this->get( 'Pagination' );
		
		//$lists['filter_manufacturer'] = $model->getmanufacturelist('filter_manufacturer',$filter_manufacturer,'class="inputbox" size="1" onchange="document.adminForm.submit();"' );
		
		$lists['filter_supplier'] = $model->getsupplierlist('filter_supplier',$filter_supplier,'class="inputbox" size="1" onchange="document.adminForm.submit();"' );
		
		$lists['filter_container'] = $model->getcontainerlist('filter_container',$filter_container,'class="inputbox" size="1" onchange="document.adminForm.submit();"' );
		
	
	
    	$this->assignRef('user',		JFactory::getUser());	
    	$this->assignRef('lists',		$lists);    
  		$this->assignRef('products',	$products); 		
		$this->assignRef('filter_container',	$filter_container); 		
		$this->assignRef('filter_manufacturer',	$filter_manufacturer); 		
    	$this->assignRef('pagination',	$pagination);
   	 	$this->assignRef('request_url',	$uri->toString());    	
    	parent::display($tpl);
  }
}
?>