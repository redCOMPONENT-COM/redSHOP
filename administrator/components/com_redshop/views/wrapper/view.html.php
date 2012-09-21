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

class wrapperViewwrapper extends JView
{
	function display($tpl = null)
	{
		$product_id = JRequest::getVar('product_id');
//		$product_name = "";

		$document = JFactory::getDocument();
		$document->setTitle( JText::_('COM_REDSHOP_WRAPPER') );

   		$total = $this->get( 'Total');
	    $data = $this->get( 'Data');
//	    if(count($data) > 0)
//	    {
//	    	$product_name = " :: ".$data[0]->product_name;
//	    }
	    JToolBarHelper::title(JText::_('COM_REDSHOP_WRAPPER' ), 'redshop_wrapper48' );

 		JToolBarHelper::addNewX();
// 		JToolBarHelper::editListX();
		JToolBarHelper::deleteList();
		JToolBarHelper::publishList();
		JToolBarHelper::unpublishList();
		$pagination = $this->get('Pagination');
		$uri	= JFactory::getURI();
		$this->assignRef('user',		JFactory::getUser());
    	$this->assignRef('lists',		$lists);
    	$this->assignRef('data',		$data);
		$this->assignRef('product_id',	$product_id);
  		$this->assignRef('pagination',	$pagination);
   	 	$this->assignRef('request_url',	$uri->toString());
   	 	parent::display($tpl);
	}
}

