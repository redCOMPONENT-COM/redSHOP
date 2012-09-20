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

class tax_group_detailController extends JController {
	function __construct($default = array()) { 
		parent::__construct ( $default );
		$this->registerTask ( 'add', 'edit' );
	}
	function edit() 
	{
		JRequest::setVar ( 'view', 'tax_group_detail' );
		JRequest::setVar ( 'layout', 'default' );
		JRequest::setVar ( 'hidemainmenu', 1 );
		
		$model = $this->getModel ( 'tax_group_detail' );
		
		parent::display ();
	}
	function save() 
	{
				
		$post = JRequest::get ( 'post' );
	 
		$option = JRequest::getVar ('option');
		
		$model = $this->getModel ( 'tax_group_detail' );
		
		if ($model->store ( $post )) {
			
			$msg = JText::_ ( 'TAX_GROUP_DETAIL_SAVED' );
		
		} else {
			
			$msg = JText::_ ( 'ERROR_SAVING_TAX_GROUP_DETAIL' );
		}
				
		$this->setRedirect ( 'index.php?option=' . $option . '&view=tax_group', $msg );
	}
	function remove() {
		
		$option = JRequest::getVar ('option');
		
		$cid = JRequest::getVar ( 'cid', array (0 ), 'post', 'array' );
		
		if (! is_array ( $cid ) || count ( $cid ) < 1) {
			JError::raiseError ( 500, JText::_ ( 'SELECT AN ITEM TO DELETE' ) );
		}
		if (! is_array ( $cid ) && $cid == 1){
			$msg = JText::_ ( 'DEFAULT_VAT_GROUP_CAN_NOT_BE_DELETED' );
		}else if(in_array( 1 , $cid)){
			$msg = JText::_ ( 'DEFAULT_VAT_GROUP_CAN_NOT_BE_DELETED' );	
		}else{
		
			$model = $this->getModel ( 'tax_group_detail' );
			if (! $model->delete ( $cid )) {
				echo "<script> alert('" . $model->getError ( true ) . "'); window.history.go(-1); </script>\n";
			}
			$msg = JText::_ ( 'TAX_GROUP_DETAIL_DELETED_SUCCESSFULLY' );
		}
		
		$this->setRedirect ( 'index.php?option='.$option.'&view=tax_group',$msg );
	}
	function publish() {
		
		$option = JRequest::getVar ('option');
		
		$cid = JRequest::getVar ( 'cid', array (0 ), 'post', 'array' );
		
		if (! is_array ( $cid ) || count ( $cid ) < 1) {
			JError::raiseError ( 500, JText::_ ( 'SELECT_AN_ITEM_TO_PUBLISH' ) );
		}
		
		$model = $this->getModel ( 'tax_group_detail' );
		if (! $model->publish ( $cid, 1 )) {
			echo "<script> alert('" . $model->getError ( true ) . "'); window.history.go(-1); </script>\n";
		}
		$msg = JText::_ ( 'TAX_GROUP_DETAIL_PUBLISHED_SUCCESFULLY' );
		
		$this->setRedirect ( 'index.php?option='.$option.'&view=tax_group',$msg );
		
			
	}
	function unpublish() {
		
		$option = JRequest::getVar ('option');
		$layout = JRequest::getVar ('layout');
		
		$cid = JRequest::getVar ( 'cid', array (0 ), 'post', 'array' );
		
		if (! is_array ( $cid ) || count ( $cid ) < 1) {
			JError::raiseError ( 500, JText::_ ( 'SELECT_AN_ITEM_TO_UNPUBLISH' ) );
		}
		
		$model = $this->getModel ( 'tax_group_detail' );
		if (! $model->publish ( $cid, 0 )) {
			echo "<script> alert('" . $model->getError ( true ) . "'); window.history.go(-1); </script>\n";
		}
		$msg = JText::_ ( 'TAX_GROUP_DETAIL_UNPUBLISHED_SUCCESFULLY' );
		
		
		$this->setRedirect ( 'index.php?option='.$option.'&view=tax_group',$msg );
		
	}
	 
	function cancel() {
		
		$option = JRequest::getVar ('option');
		$msg = JText::_ ( 'TAX_GROUP_DETAIL_EDITING_CANCELLED' );
		$this->setRedirect ( 'index.php?option='.$option.'&view=tax_group',$msg );
	}
	 
}
