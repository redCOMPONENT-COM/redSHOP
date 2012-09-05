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

class couponViewcoupon extends JView
{
	function __construct( $config = array())
	{
		 parent::__construct( $config );
	}
    
	function display($tpl = null)
	{	
		global $mainframe, $context;		
		
		$document = & JFactory::getDocument();
		$document->setTitle( JText::_('COUPON') );
   		
   		JToolBarHelper::title(   JText::_( 'COUPON_MANAGEMENT' ), 'redshop_coupon48' );
   		 
 		JToolBarHelper::addNewX();
 		JToolBarHelper::editListX();		
		JToolBarHelper::deleteList();		
		JToolBarHelper::publishList();
		JToolBarHelper::unpublishList();
	   
		$uri =& JFactory::getURI();
		$context = "rating";
		$filter_order     = $mainframe->getUserStateFromRequest( $context.'filter_order',      'filter_order', 	  'coupon_id' );
		$filter_order_Dir = $mainframe->getUserStateFromRequest( $context.'filter_order_Dir',  'filter_order_Dir', '' );		
		  
		$lists['order'] = $filter_order;  
		$lists['order_Dir'] = $filter_order_Dir;
		$coupons		= & $this->get( 'Data');
		$total			= & $this->get( 'Total');
		$pagination = & $this->get( 'Pagination' );
		
	
    	$this->assignRef('user',		JFactory::getUser());	
    	$this->assignRef('lists',		$lists);    
  		$this->assignRef('coupons',		$coupons); 		
    	$this->assignRef('pagination',	$pagination);
    	$this->assignRef('request_url',	$uri->toString());    	
    	parent::display($tpl);
  }
}
?>