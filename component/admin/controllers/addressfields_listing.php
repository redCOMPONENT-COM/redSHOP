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


jimport( 'joomla.application.component.controller' );

class addressfields_listingController extends JController
{
	function __construct( $default = array())
	{
		parent::__construct( $default );
	}
	function cancel()
	{
		$this->setRedirect( 'index.php' );
	}
	function display() {

		parent::display();
	}


	function saveorder()
	{
		$option = JRequest::getVar('option');
		$cid = JRequest::getVar ( 'cid', array (0 ), 'post', 'array' );
		$order 	= JRequest::getVar( 'order', array(), 'post', 'array' );
		$model = $this->getModel ( 'addressfields_listing' );
		if ($model->saveorder($cid,$order))
		{
			$msg = JText::_ ( 'NEW_ORDERING_SAVED' );
		} else {
			$msg = JText::_ ( 'NEW_ORDERING_ERROR' );
		}
		$this->setRedirect ( 'index.php?option=' .$option. '&view=addressfields_listing', $msg );
	}

	/**
	 * logic for orderup manufacturer
	 *
	 * @access public
	 * @return void
	 */
	function orderup()
	{
		global $mainframe, $context;
	    $cid = JRequest::getVar ( 'cid', array (0 ), 'post', 'array' );
	    $option = JRequest::getVar('option');
		$filter_order_Dir = $mainframe->getUserStateFromRequest( $context.'filter_order_Dir',  'filter_order_Dir', '' );
		$up = 1;
		if(strtolower($filter_order_Dir)=="asc")
		{
			$up = -1;
		}

		$model = $this->getModel('addressfields_listing');
		$model->move($up,$cid[0]);
 		//$model->orderup();
		$msg = JText::_( 'NEW_ORDERING_SAVED' );
		$this->setRedirect ( 'index.php?option='.$option.'&view=addressfields_listing',$msg );
	}
	/**
	 * logic for orderdown manufacturer
	 *
	 * @access public
	 * @return void
	 */
	function orderdown()
	{
		global $mainframe, $context;
		$option = JRequest::getVar('option');
		$cid = JRequest::getVar ( 'cid', array (0 ), 'post', 'array' );
		$filter_order_Dir = $mainframe->getUserStateFromRequest( $context.'filter_order_Dir',  'filter_order_Dir', '' );
		$down = -1;
		if(strtolower($filter_order_Dir)=="asc")
		{
			$down = 1;
		}
  		$model = $this->getModel('addressfields_listing');
		$model->move($down,$cid[0]);
		//$model->orderdown();
		$msg = JText::_( 'NEW_ORDERING_SAVED' );
		$this->setRedirect ( 'index.php?option='.$option.'&view=addressfields_listing',$msg );
	}


}

