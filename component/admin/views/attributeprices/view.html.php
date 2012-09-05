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
jimport( 'joomla.application.component.view' );
jimport('joomla.html.pagination');

class attributepricesViewattributeprices extends JView
{
	function __construct( $config = array())
	{
		 parent::__construct( $config );
	}

	function display($tpl = null)
	{	
		global $mainframe, $context;
		
		$section_id = JRequest::getVar('section_id');
		$section = JRequest::getVar('section');
		
		$document = & JFactory::getDocument();
		$document->setTitle( JText::_('ATTRIBUTE_PRICE') );
   		
   		JToolBarHelper::title(   JText::_( 'ATTRIBUTE_PRICE' ), 'redshop_vatrates48' );
   		 
 		JToolBarHelper::addNewX();
 		JToolBarHelper::editListX();		
		JToolBarHelper::deleteList();		
		$uri	=& JFactory::getURI();

		$limitstart     = $mainframe->getUserStateFromRequest( $context.'limitstart',      'limitstart', 	  '0' );
		$limit = $mainframe->getUserStateFromRequest( $context.'limit',  'limit', '10' );

	    $total = & $this->get( 'Total');
	    $data = & $this->get( 'Data');
		$pagination = new JPagination( $total, $limitstart, $limit);
    	
		$this->assignRef('user',		JFactory::getUser());	
    	$this->assignRef('lists',		$lists);
    	$this->assignRef('data',		$data);
		$this->assignRef('section_id',	$section_id);
		$this->assignRef('section',		$section);   
  		$this->assignRef('pagination',	$pagination);
   	 	$this->assignRef('request_url',	$uri->toString());    	
   	 	parent::display($tpl);
	}
}?>