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
 
class opsearchViewopsearch extends JView
{
	function __construct( $config = array())
	{
		 parent::__construct( $config );
	}
    
	function display($tpl = null)
	{	
		global $mainframe, $context;

		$model = $this->getModel ( 'opsearch' );
		$document = & JFactory::getDocument();
		$order_function = new order_functions();
   		//
		$option = JRequest::getVar('option');
		$document = &JFactory::getDocument();
		$document->addStyleSheet ( 'components/com_redshop/assets/css/search.css' );
		$document->addScript ('components/com_redshop/assets/js/search.js');
		
		$document->setTitle( JText::_('PRODUCT_ORDER_SEARCH_BY_CUSTOMER') );
		JToolBarHelper::title(   JText::_( 'PRODUCT_ORDER_SEARCH_BY_CUSTOMER' ), 'redshop_order48' );
		
		$uri	=& JFactory::getURI();
		
		$lists['order']     = $mainframe->getUserStateFromRequest( $context.'filter_order',      'filter_order', 	  'order_item_name' );
		$lists['order_Dir'] = $mainframe->getUserStateFromRequest( $context.'filter_order_Dir',  'filter_order_Dir', '' );		
		$filter_user	  = $mainframe->getUserStateFromRequest( $context.'filter_user',		'filter_user',		0);
		$filter_status	  = $mainframe->getUserStateFromRequest( $context.'filter_status',		'filter_status',	0);
		
		$products	= & $this->get( 'Data');
		$total = & $this->get( 'Total');
		$pagination = & $this->get( 'Pagination' );
		
		$lists['filter_user'] = $model->getuserlist('filter_user',$filter_user,'class="inputbox" size="1" onchange="document.adminForm.submit();"' );
		$lists['filter_status'] = $order_function->getstatuslist('filter_status',$filter_status,'class="inputbox" size="1" onchange="document.adminForm.submit();"' );
		
    	$this->assignRef('lists',		$lists); 
  		$this->assignRef('products',	$products); 		
    	$this->assignRef('pagination',	$pagination);
   	 	$this->assignRef('request_url',	$uri->toString());    	
    	parent::display($tpl);
  }
}
?>