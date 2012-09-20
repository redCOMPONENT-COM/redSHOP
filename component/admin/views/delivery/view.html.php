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
 
class deliveryViewdelivery extends JView
{
	function __construct( $config = array())
	{
		 parent::__construct( $config );
	}
    
	function display($tpl = null)
	{	
		global $mainframe, $context;
	 	
	    $db = jFactory::getDBO();
		
		$document = & JFactory::getDocument();
		$document->setTitle( JText::_('DELIVERY_LIST') );
 
   		JToolBarHelper::title(   JText::_( 'DELIVERY_LIST' ), 'redshop_redshopcart48' );   		
   		
		JToolBarHelper::custom('export_data','save.png','save_f2.png','Export Data',false);
	   	
		$uri	=& JFactory::getURI();
		$context = 'delivery';
		$filter_order     = $mainframe->getUserStateFromRequest( $context.'filter_order',      'filter_order', 	  'order_id' );
		$filter_order_Dir = $mainframe->getUserStateFromRequest( $context.'filter_order_Dir',  'filter_order_Dir', '' );
		$filter_status	  = $mainframe->getUserStateFromRequest( $context.'filter_status',		'filter_status',		'',			'word' );
				  
		$lists['order'] 		= $filter_order;  
		$lists['order_Dir'] = $filter_order_Dir;
		
		$query = "SELECT * FROM #__".TABLE_PREFIX."_orders AS o "
				."LEFT JOIN #__".TABLE_PREFIX."_users_info AS uf ON o.user_id=uf.user_id "
				."LEFT JOIN #__".TABLE_PREFIX."_order_status AS os ON o.order_status=os.order_status_code "
				."WHERE uf.address_type='BT' "
				."AND o.order_status IN ('RD','RD1','RD2') "
				."ORDER BY $filter_order $filter_order_Dir";
		$db->setQuery($query);
		$orders = $db->loadObjectList();
			 	
	    $this->assignRef('user',		JFactory::getUser());	
	    $this->assignRef('lists',		$lists);    
	  	$this->assignRef('orders',		$orders); 		
	    $this->assignRef('pagination',	$pagination);
	    $this->assignRef('request_url',	$uri->toString());    	
    	parent::display($tpl);
  }
}
?>
