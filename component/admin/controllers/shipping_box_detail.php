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

require_once JPATH_COMPONENT_ADMINISTRATOR.DS.'helpers'.DS.'template.php';

class shipping_box_detailController extends JController {
	function __construct($default = array()) {
		parent::__construct ( $default );
		$this->registerTask ( 'add', 'edit' );
	}
	function edit() {
		JRequest::setVar ( 'view', 'shipping_box_detail' );
		JRequest::setVar ( 'layout', 'default' );
		JRequest::setVar ( 'hidemainmenu', 1 );
		parent::display ();
	
	}
	function apply() 
	{
       $this->save(1);
	}
	function save($apply=0) {
		
		$post = JRequest::get ( 'post' );
			
		$option = JRequest::getVar('option');
		
		$model = $this->getModel ( 'shipping_box_detail' );
		$row = $model->store ( $post );
		if ($row) {
			
			$msg = JText::_ ( 'SHIPPING_BOX_SAVED' );
		
		} else {
			
			$msg = JText::_ ( 'ERROR_SAVING_BOX' );
		}
		
		if ($apply==1){
			$this->setRedirect ( 'index.php?option='.$option.'&view=shipping_box_detail&task=edit&cid[]='.$row->shipping_box_id, $msg );
		}else {
			$this->setRedirect ( 'index.php?option='.$option.'&view=shipping_box', $msg );
		}
		
		 
	}
	function remove() {
		
		$option = JRequest::getVar('option');
		
		$cid = JRequest::getVar ( 'cid', array (0 ), 'post', 'array' );
		
		if (! is_array ( $cid ) || count ( $cid ) < 1) {
			JError::raiseError ( 500, JText::_ ( 'SELECT_AN_ITEM_TO_DELETE' ) );
		}
		
		$model = $this->getModel ( 'shipping_box_detail' );
		if (! $model->delete ( $cid )) {
			echo "<script> alert('" . $model->getError ( true ) . "'); window.history.go(-1); </script>\n";
		}
		
		$this->setRedirect ( 'index.php?option='.$option.'&view=shipping_box' );
	}
	function publish() {
		
		$option = JRequest::getVar('option');
		
		$cid = JRequest::getVar ( 'cid', array (0 ), 'post', 'array' );
		
		if (! is_array ( $cid ) || count ( $cid ) < 1) {
			JError::raiseError ( 500, JText::_ ( 'SELECT_AN_ITEM_TO_PUBLISH' ) );
		}
		
		$model = $this->getModel ( 'shipping_box_detail' );
		if (! $model->publish ( $cid, 1 )) {
			echo "<script> alert('" . $model->getError ( true ) . "'); window.history.go(-1); </script>\n";
		}
		
		$this->setRedirect ( 'index.php?option='.$option.'&view=shipping_box' );
	}
	function unpublish() {
		
		$option = JRequest::getVar('option');
		
		$cid = JRequest::getVar ( 'cid', array (0 ), 'post', 'array' );
		
		if (! is_array ( $cid ) || count ( $cid ) < 1) {
			JError::raiseError ( 500, JText::_ ( 'SELECT_AN_ITEM_TO_UNPUBLISH' ) );
		}
		
		$model = $this->getModel ( 'shipping_box_detail' );
		if (! $model->publish ( $cid, 0 )) {
			echo "<script> alert('" . $model->getError ( true ) . "'); window.history.go(-1); </script>\n";
		}
		
		$this->setRedirect ( 'index.php?option='.$option.'&view=shipping_box' );
	}
	function cancel() {
		
		$option = JRequest::getVar('option');
		
		$this->setRedirect ( 'index.php?option='.$option.'&view=shipping_box' );
	}
}
