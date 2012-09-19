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
 
class fieldsViewfields extends JView
{
	function __construct( $config = array())
	{
		 parent::__construct( $config );
	}
    
	function display($tpl = null)
	{	
		global $mainframe, $context;
		$context = 'field_id';
		$redtemplate = new Redtemplate();
		$document = & JFactory::getDocument();
		$document->setTitle( JText::_('COM_REDSHOP_FIELDS') );
   		 
   		JToolBarHelper::title(   JText::_('COM_REDSHOP_FIELDS_MANAGEMENT' ), 'redshop_fields48' );
        
   		
 		JToolBarHelper::addNewX();
 		JToolBarHelper::editListX();		
		JToolBarHelper::deleteList();		
		JToolBarHelper::publishList();
		JToolBarHelper::unpublishList();
	   
		$uri	=& JFactory::getURI();		
		$fields			= & $this->get( 'Data');
		$total			= & $this->get( 'Total');
		$pagination = & $this->get( 'Pagination' );
		$optiontype = $redtemplate->getFieldTypeSections();
		$optionsection = $redtemplate->getFieldSections();
				
		$filter_order     = $mainframe->getUserStateFromRequest( $context.'filter_order',      'filter_order', 	  'field_id' );
		$filter_order_Dir = $mainframe->getUserStateFromRequest( $context.'filter_order_Dir',  'filter_order_Dir', '' );
		$filtertypes = $mainframe->getUserStateFromRequest( $context.'filtertypes',  'filtertypes', 0 );
		$filtersection = $mainframe->getUserStateFromRequest( $context.'filtertypes',  'filtersection', 0 );

		$lists['order'] 		= $filter_order;  
		$lists['order_Dir'] = $filter_order_Dir;
		
		$lists['type'] 		= JHTML::_('select.genericlist',$optiontype,  'filtertypes', 'class="inputbox" size="1" onchange="document.adminForm.submit();" ', 'value', 'text',$filtertypes);
		$lists['section'] 	= JHTML::_('select.genericlist',$optionsection,  'filtersection', 'class="inputbox" size="1" onchange="document.adminForm.submit();"', 'value', 'text',  $filtersection );
	
	    $this->assignRef('user',		JFactory::getUser());	
	    $this->assignRef('lists',		$lists);    
	  	$this->assignRef('fields',		$fields); 		
	    $this->assignRef('pagination',	$pagination);
	    $this->assignRef('request_url',	$uri->toString());    	
    	parent::display($tpl);
  }
}
?>
