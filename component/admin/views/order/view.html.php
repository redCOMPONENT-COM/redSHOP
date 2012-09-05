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

class orderVieworder extends JView
{
	function __construct( $config = array())
	{
		 parent::__construct( $config );
	}

	function display($tpl = null)
	{
		global $mainframe, $context;


		require_once( JPATH_COMPONENT.DS.'helpers'.DS.'order.php' );
		$order_function = new order_functions();

		$document = & JFactory::getDocument();
		$document->setTitle( JText::_('COM_REDSHOP_ORDER') );
   		$model = $this->getModel('order');
   		$layout = JRequest::getVar('layout');

   		if($layout == 'previewlog' )
  		{
   			$this->setLayout($layout);
   		}
   		else if($layout == 'labellisting')
   		{
   		    JToolBarHelper::title(   JText::_('COM_REDSHOP_DOWNLOAD_LABEL' ), 'redshop_order48' );
   			$this->setLayout('labellisting');
   			JToolBarHelper::cancel('cancel', 'Close');
   		}else{
			JToolBarHelper::custom('multiprint_order','print_f2.png','print_f2.png',JText::_('COM_REDSHOP_MULTI_PRINT_ORDER_LBL' ),true);
            JToolBarHelper::title(   JText::_('COM_REDSHOP_ORDER_MANAGEMENT' ), 'redshop_order48' );
	   		JToolBarHelper::addNewX();
	   		JToolBarHelper::custom('allstatus','save.png','save_f2.png',JText::_('COM_REDSHOP_CHANGE_STATUS_TO_ALL_LBL' ),true);
	   		JToolBarHelper::custom('export_data','save.png','save_f2.png',JText::_('COM_REDSHOP_EXPORT_DATA_LBL' ),false);
	   		JToolBarHelper::custom('export_fullorder_data','save.png','save_f2.png',JText::_( 'COM_REDSHOP_EXPORT_FULL_DATA_LBL' ),false);
	   		JToolBarHelper::custom('gls_export','save.png','save_f2.png',JText::_( 'COM_REDSHOP_EXPORT_GLS_LBL' ),false);
			JToolBarHelper::custom('business_gls_export','save.png','save_f2.png',JText::_( 'COM_REDSHOP_EXPORT_GLS_BUSINESS_LBL' ),false);
	   		JToolBarHelper::deleteList();
   		}
		$uri	=& JFactory::getURI();

		$filter_order     = $mainframe->getUserStateFromRequest( $context.'filter_order',      'filter_order', 	  ' o.order_id ' );
		$filter_order_Dir = $mainframe->getUserStateFromRequest( $context.'filter_order_Dir',  'filter_order_Dir', 'DESC' );
		$filter_status	  = $mainframe->getUserStateFromRequest( $context.'filter_status',		'filter_status',		'',			'word' );
		$filter_payment_status	  = $mainframe->getUserStateFromRequest( $context.'filter_payment_status',		'filter_payment_status',		'',			'' );

		$lists['order'] 		= $filter_order;
		$lists['order_Dir'] = $filter_order_Dir;
		$orders			= & $this->get( 'Data');
		$total			= & $this->get( 'Total');
		$pagination = & $this->get( 'Pagination' );

		$lists['filter_status'] = $order_function->getstatuslist('filter_status',$filter_status,'class="inputbox" size="1" onchange="document.adminForm.submit();"' );
		$lists['filter_payment_status'] = $order_function->getpaymentstatuslist('filter_payment_status', $filter_payment_status,'class="inputbox" size="1" onchange="document.adminForm.submit();" ');

	    $this->assignRef('user',		JFactory::getUser());
	    $this->assignRef('lists',		$lists);
	  	$this->assignRef('orders',		$orders);
	    $this->assignRef('pagination',	$pagination);
	    $this->assignRef('request_url',	$uri->toString());
    	parent::display($tpl);
  }
}
?>
