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


require_once( JPATH_COMPONENT_ADMINISTRATOR.DS.'helpers'.DS.'quotation.php' );

class quotationViewquotation extends JView
{
	function __construct( $config = array())
	{
		 parent::__construct( $config );
	}
    
	function display($tpl = null)
	{	
		global $mainframe, $context;
		
		$quotationHelper = new quotationHelper();
		
		$document = & JFactory::getDocument();
		$document->setTitle( JText::_('COM_REDSHOP_quotation') );
		$model = $this->getModel('quotation');
   		
   		JToolBarHelper::title(   JText::_('COM_REDSHOP_QUOTATION_MANAGEMENT' ), 'redshop_quotation48' );   		
   		JToolBarHelper::addNewX();
   		JToolBarHelper::editListX();
   		JToolBarHelper::deleteList();
	   	
		$uri	=& JFactory::getURI();
		
		$filter_order     = $mainframe->getUserStateFromRequest( $context.'filter_order',		'filter_order', 	  'quotation_cdate' );
		$filter_order_Dir = $mainframe->getUserStateFromRequest( $context.'filter_order_Dir',	'filter_order_Dir', 'DESC' );
		$filter_status	  = $mainframe->getUserStateFromRequest( $context.'filter_status',		'filter_status',	0 );
				  
		$lists['order'] 		= $filter_order;  
		$lists['order_Dir'] = $filter_order_Dir;
		
		$quotation	= & $this->get( 'Data');
		$total		= & $this->get( 'Total');
		$pagination = & $this->get( 'Pagination' );
		
		$optionsection = $quotationHelper->getQuotationStatusList();
		$lists['filter_status'] 	= JHTML::_('select.genericlist',$optionsection,  'filter_status', 'class="inputbox" size="1" onchange="document.adminForm.submit();"', 'value', 'text',  $filter_status );
		
	    $this->assignRef('lists',		$lists);    
	  	$this->assignRef('quotation',	$quotation); 		
	    $this->assignRef('pagination',	$pagination);
	    $this->assignRef('request_url',	$uri->toString());    	
    	parent::display($tpl);
  }
}
?>
