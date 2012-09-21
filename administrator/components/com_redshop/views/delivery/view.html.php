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
	function display($tpl = null)
	{
		global $mainframe, $context;

		$document = JFactory::getDocument();
		$document->setTitle( JText::_('COM_REDSHOP_DELIVERY_LIST') );

   		JToolBarHelper::title(   JText::_('COM_REDSHOP_DELIVERY_LIST' ), 'redshop_redshopcart48' );
   		JToolBarHelper::custom('export_data','save.png','save_f2.png',JText::_('COM_REDSHOP_EXPORT_DATA_LBL' ),false);

		$uri	= JFactory::getURI();
		$context = 'delivery';
		$filter_order     = $mainframe->getUserStateFromRequest( $context.'filter_order',      'filter_order', 	  'order_id' );
		$filter_order_Dir = $mainframe->getUserStateFromRequest( $context.'filter_order_Dir',  'filter_order_Dir', '' );

		$lists['order'] 		= $filter_order;
		$lists['order_Dir'] = $filter_order_Dir;

		$this->assignRef('lists',		$lists);
	    $this->assignRef('request_url',	$uri->toString());
    	parent::display($tpl);
  }
}
