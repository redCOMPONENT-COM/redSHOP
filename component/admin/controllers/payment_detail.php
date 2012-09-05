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

defined ( '_JEXEC' ) or die ( 'Restricted access' );

jimport ( 'joomla.application.component.controller' );
 
define('WARNSAME',"There is already a file called '%s'.");
define('INSTALLEXT','Install %s %s');
class payment_detailController extends JController {
	function __construct($default = array()) {
		parent::__construct ( $default );
		$this->registerTask ( 'add', 'edit' );
	}
	
	function install(){
	
		$model = $this->getModel ( 'payment_detail' );
		
		$model->install();
		
		
		JRequest::setVar ( 'view', 'payment_detail' );
		JRequest::setVar ( 'layout', 'default' );
		JRequest::setVar ( 'hidemainmenu', 1 );
		parent::display ();
	}
	
	function edit() {
		JRequest::setVar ( 'view', 'payment_detail' );
		JRequest::setVar ( 'layout', 'default' );
		JRequest::setVar ( 'hidemainmenu', 1 );
		parent::display ();
	
	}
	function save() {
		$post = JRequest::get ( 'post' );
		
		$accepted_credit_card = JRequest::getVar( 'accepted_credict_card', '', 'post', 'array' );
		$accepted_credit_card = implode(",",$accepted_credit_card);
		$post["accepted_credict_card"] = $accepted_credit_card;
				
		$option = JRequest::getVar ('option');
		
		$model = $this->getModel ( 'payment_detail' );
		
	  	$payment_extrainfo = JRequest::getVar( 'payment_extrainfo', '', 'post', 'string', JREQUEST_ALLOWRAW );
	  	
	 	$post["payment_extrainfo"] = $payment_extrainfo;		
	 	
		if ($model->store ( $post )) {
			
			$msg = JText::_ ( 'PAYMENT_SAVED' );
		
		} else {
			
			$msg = JText::_ ( 'ERROR_SAVING_PAYMENT' );
		}
		
		$this->setRedirect ( 'index.php?option=' . $option . '&view=payment', $msg );
	}
	function remove() {
		
		$option = JRequest::getVar ('option');
		
		$cid = JRequest::getVar ( 'cid', array (0 ), 'post', 'array' );
		
		
		$model = $this->getModel ( 'payment_detail' );
		
		$model->uninstall($cid);
		
		//if (! is_array ( $cid ) || count ( $cid ) < 1) {
		//	JError::raiseError ( 500, JText::_ ( 'SELECT_AN_ITEM_TO_DELETE' ) );
		//}
		
		//$model = $this->getModel ( 'payment_detail' );
		//if (! $model->delete ( $cid )) {
		//	echo "<script> alert('" . $model->getError ( true ) . "'); window.history.go(-1); </script>\n";
		//}
		
		$this->setRedirect ( 'index.php?option='.$option.'&view=payment' );
	}
	function publish() {
		
		$option = JRequest::getVar ('option');
		
		$cid = JRequest::getVar ( 'cid', array (0 ), 'post', 'array' );
		
		if (! is_array ( $cid ) || count ( $cid ) < 1) {
			JError::raiseError ( 500, JText::_ ( 'SELECT_AN_ITEM_TO_PUBLISH' ) );
		}
		
		$model = $this->getModel ( 'payment_detail' );
		if (! $model->publish ( $cid, 1 )) {
			echo "<script> alert('" . $model->getError ( true ) . "'); window.history.go(-1); </script>\n";
		}
		
		$this->setRedirect ( 'index.php?option='.$option.'&view=payment' );
	}
	function unpublish() {
		
		$option = JRequest::getVar ('option');
		
		$cid = JRequest::getVar ( 'cid', array (0 ), 'post', 'array' );
		
		if (! is_array ( $cid ) || count ( $cid ) < 1) {
			JError::raiseError ( 500, JText::_ ( 'SELECT_AN_ITEM_TO_UNPUBLISH' ) );
		}
		
		$model = $this->getModel ( 'payment_detail' );
		if (! $model->publish ( $cid, 0 )) {
			echo "<script> alert('" . $model->getError ( true ) . "'); window.history.go(-1); </script>\n";
		}
		
		$this->setRedirect ( 'index.php?option='.$option.'&view=payment' );
	}
	function cancel() {
		
		$option = JRequest::getVar ('option');
		
		$this->setRedirect ( 'index.php?option='.$option.'&view=payment' );
	}
	
	/**
	 * logic for orderup manufacturer
	 *
	 * @access public
	 * @return void
	 */
	function orderup()
	{
	    $option = JRequest::getVar('option');

		$model = $this->getModel('payment_detail');
		$model->move(-1);
 		//$model->orderup();
		$msg = JText::_( 'NEW_ORDERING_SAVED' );
		$this->setRedirect ( 'index.php?option='.$option.'&view=payment',$msg );
	}
	/**
	 * logic for orderdown manufacturer
	 *
	 * @access public
	 * @return void
	 */
	function orderdown()
	{
		$option = JRequest::getVar('option');
		$model = $this->getModel('payment_detail');
		$model->move(1);
		//$model->orderdown();
		$msg = JText::_( 'NEW_ORDERING_SAVED' );
		$this->setRedirect ( 'index.php?option='.$option.'&view=payment',$msg );
	}
	
	/**
	 * logic for save an order
	 *
	 * @access public
	 * @return void
	 */
	function saveorder()
	{
		$option = JRequest::getVar('option');
		 
		$cid 	= JRequest::getVar( 'cid', array(), 'post', 'array' );
		$order 	= JRequest::getVar( 'order', array(), 'post', 'array' );
		
		JArrayHelper::toInteger($cid);
		JArrayHelper::toInteger($order);

		$model = $this->getModel('payment_detail');
		$model->saveorder($cid);

		$msg = JText::_( 'PAYMENT_SAVED' );
		$this->setRedirect ( 'index.php?option='.$option.'&view=payment',$msg );
	}
	 

}
