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

class shipping_rateViewshipping_rate extends JView
{
	function __construct( $config = array())
	{
		 parent::__construct( $config );
	}

	function display($tpl = null)
	{
		global $mainframe, $context;
		$context = 'shipping_rate';
		$uri	=& JFactory::getURI();
		$shippinghelper = new shipping();

		$lists['order']     = $mainframe->getUserStateFromRequest( $context.'filter_order',      'filter_order', 	  'shipping_rate_id' );
		$lists['order_Dir'] = $mainframe->getUserStateFromRequest( $context.'filter_order_Dir',  'filter_order_Dir', '' );
		$id     = $mainframe->getUserStateFromRequest( $context.'id',      'id', 	  '0' );
		
		$shipping		= $shippinghelper->getShippingMethodById($id);
		$shipping_rates	= & $this->get( 'Data');
		$total			= & $this->get( 'Total');
		$pagination = & $this->get( 'Pagination' );
		
		$shippingpath=JPATH_ROOT.DS.'plugins'.DS.$shipping->folder.DS.$shipping->element.'.xml';
	 	$myparams = new JParameter($shipping->params,$shippingpath);
	 	$is_shipper = $myparams->get( 'is_shipper' );
 		$shipper_location = $myparams->get( 'shipper_location' );

 		$jtitle = ($shipper_location) ? JText::_( 'SHIPPING_LOCATION' ) : JText::_( 'SHIPPING_RATE' );
 	    JToolBarHelper::title( $jtitle.' <small><small>[ '.$shipping->name.' ]</small></small>' , 'redshop_shipping_rates48' );
 		JToolBarHelper::addNewX();
 		JToolBarHelper::editListX();
 		if($is_shipper){
 			JToolBarHelper::customX( 'copy', 'copy.png', 'copy_f2.png', 'Copy', true );
 		}
		JToolBarHelper::deleteList();
		JToolBarHelper::cancel( 'cancel', 'Close' );

	    $this->assignRef('lists',		$lists);
  		$this->assignRef('shipping_rates',		$shipping_rates);
  		$this->assignRef('shipping',		$shipping);
    	$this->assignRef('pagination',	$pagination);
    	$this->assignRef('is_shipper',	$is_shipper);
    	$this->assignRef('shipper_location',	$shipper_location);
    	$this->assignRef('request_url',	$uri->toString());
    	parent::display($tpl);
  }
}
?>
