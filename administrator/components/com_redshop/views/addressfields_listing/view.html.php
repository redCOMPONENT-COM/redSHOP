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

class addressfields_listingViewaddressfields_listing extends JView
{
	function display($tpl = null)
	{
		global $mainframe, $context;

		$document = JFactory::getDocument();
		$document->setTitle( JText::_('COM_REDSHOP_FIELDS') );

   		JToolBarHelper::title(   JText::_('COM_REDSHOP_ADDRESS_FIELD_MANAGEMENT' ), 'redshop_fields48' );

 		$uri	= JFactory::getURI();

		$filter_order     = $mainframe->getUserStateFromRequest( $context.'filter_order',      'filter_order', 	  'field_id' );
		$filter_order_Dir = $mainframe->getUserStateFromRequest( $context.'filter_order_Dir',  'filter_order_Dir', '' );

		$lists['order'] 		= $filter_order;
		$lists['order_Dir'] = $filter_order_Dir;
		$fields			= $this->get( 'Data');
		$pagination = $this->get( 'Pagination' );

		$section_id = $mainframe->getUserStateFromRequest( $context.'section_id','section_id',0 );

		$sectionlist = array(
		  JHTML::_('select.option', '7', JText::_('COM_REDSHOP_CUSTOMER_ADDRESS') ),
		  JHTML::_('select.option', '8', JText::_('COM_REDSHOP_COMPANY_ADDRESS') ),
		  JHTML::_('select.option', '14', JText::_('COM_REDSHOP_CUSTOMER_SHIPPING_ADDRESS') ),
		  JHTML::_('select.option', '15', JText::_('COM_REDSHOP_COMPANY_SHIPPING_ADDRESS') )
		);


		$option = array();
		$option[0]->value="0";
		$option[0]->text=JText::_('COM_REDSHOP_SELECT');
		if( count($sectionlist) > 0 )
		{
			$option = @array_merge($option,$sectionlist);
		}

		$lists['addresssections']  = JHTML::_('select.genericlist',$option,  'section_id', 'class="inputbox" size="1" onchange="document.adminForm.submit();"', 'value', 'text',  $section_id );

    //$this->assignRef('user',		JFactory::getUser());
        $this->user = JFactory::getUser();
    $this->assignRef('lists',		$lists);
  	$this->assignRef('fields',		$fields);
    $this->assignRef('pagination',	$pagination);
    //$this->assignRef('request_url',	$uri->toString());
        $this->request_url = $uri->toString();
    	parent::display($tpl);
  }
}
