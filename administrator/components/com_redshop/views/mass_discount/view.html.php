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

class mass_discountViewmass_discount extends JView
{
	function __construct( $config = array())
	{
		 parent::__construct( $config );
	}

	function display($tpl = null)
	{
		global $mainframe, $context;

		$document = JFactory::getDocument();
		$document->setTitle( JText::_('COM_REDSHOP_DISCOUNT') );

		$layout = JRequest::getVar('layout');

   		JToolBarHelper::title(   JText::_('COM_REDSHOP_DISCOUNT_MANAGEMENT' ), 'redshop_massdiscountmanagmenet48' );


 		JToolBarHelper::addNewX();
 		JToolBarHelper::editListX();
		JToolBarHelper::deleteList();

		$uri	= JFactory::getURI();


		$filter_order     = $mainframe->getUserStateFromRequest( $context.'filter_order',      'filter_order', 	  'mass_discount_id' );


		$filter_order_Dir = $mainframe->getUserStateFromRequest( $context.'filter_order_Dir',  'filter_order_Dir', '' );

		$lists['order'] = $filter_order;
		$lists['order_Dir'] = $filter_order_Dir;
		$discounts	= & $this->get( 'Data');
		$total = & $this->get( 'Total');
		$pagination = & $this->get( 'Pagination' );

    	$this->assignRef('user',		JFactory::getUser());
    	$this->assignRef('lists',		$lists);
  		$this->assignRef('discounts',	$discounts);
    	$this->assignRef('pagination',	$pagination);
   	 	$this->assignRef('request_url',	$uri->toString());
    	parent::display($tpl);
  }
}
?>
