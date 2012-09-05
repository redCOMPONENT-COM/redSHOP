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

class textlibrary_detailController extends JController {
	function __construct($default = array()) {
		parent::__construct ( $default );
		$this->registerTask ( 'add', 'edit' );
	}
	function edit() {
		JRequest::setVar ( 'view', 'textlibrary_detail' );
		JRequest::setVar ( 'layout', 'default' );
		JRequest::setVar ( 'hidemainmenu', 1 );
		parent::display ();
	
	}
	function apply(){
		$this->save(1);
	}
	function save($apply=0) { 
		//$post = JRequest::get ( 'post' );
		
		$post = JRequest::get ( 'post' );
		$text_field = JRequest::getVar( 'text_field', '', 'post', 'string', JREQUEST_ALLOWRAW );
		$post["text_field"]=$text_field;	
		
		$option = JRequest::getVar('option','','request','string');
		
		$cid = JRequest::getVar ( 'cid', array (0 ), 'post', 'array' );
		
		$post ['textlibrary_id'] = $cid [0];
		
		$model = $this->getModel ( 'textlibrary_detail' );
		
		if ($row=$model->store ( $post )) {
			
			$msg = JText::_('COM_REDSHOP_TEXTLIBRARY_DETAIL_SAVED' );
		
		} else {
			
			$msg = JText::_('COM_REDSHOP_ERROR_SAVING_TEXTLIBRARY_DETAIL' );
		}
		
		if($apply==1){ //&view=textlibrary_detail&task=edit&cid[]=1
		$this->setRedirect ( 'index.php?option=' . $option . '&view=textlibrary_detail&task=edit&cid[]='.$row->textlibrary_id, $msg );
		}else{
		$this->setRedirect ( 'index.php?option=' . $option . '&view=textlibrary', $msg );	
		}
	}
	function remove() {
		
		$option = JRequest::getVar('option','','request','string');
		
		$cid = JRequest::getVar ( 'cid', array (0 ), 'post', 'array' );
		
		if (! is_array ( $cid ) || count ( $cid ) < 1) {
			JError::raiseError ( 500, JText::_('COM_REDSHOP_SELECT_AN_ITEM_TO_DELETE' ) );
		}
		
		$model = $this->getModel ( 'textlibrary_detail' );
		if (! $model->delete ( $cid )) {
			echo "<script> alert('" . $model->getError ( true ) . "'); window.history.go(-1); </script>\n";
		}
		$msg = JText::_('COM_REDSHOP_TEXT_LIBRARY_DETAIL_DELETED_SUCCESSFULLY' );
		$this->setRedirect ( 'index.php?option='.$option.'&view=textlibrary',$msg );
	}
	function publish() {
		
		$option = JRequest::getVar('option','','request','string');
		
		$cid = JRequest::getVar ( 'cid', array (0 ), 'post', 'array' );
		
		if (! is_array ( $cid ) || count ( $cid ) < 1) {
			JError::raiseError ( 500, JText::_('COM_REDSHOP_SELECT_AN_ITEM_TO_PUBLISH' ) );
		}
		
		$model = $this->getModel ( 'textlibrary_detail' );
		if (! $model->publish ( $cid, 1 )) {
			echo "<script> alert('" . $model->getError ( true ) . "'); window.history.go(-1); </script>\n";
		}
		$msg = JText::_('COM_REDSHOP_TEXT_LIBRARY_DETAIL_PUBLISHED_SUCCESSFULLY' );
		$this->setRedirect ( 'index.php?option='.$option.'&view=textlibrary',$msg );
	}
	function unpublish() {
		
		$option = JRequest::getVar('option','','request','string');
		
		$cid = JRequest::getVar ( 'cid', array (0 ), 'post', 'array' );
		
		if (! is_array ( $cid ) || count ( $cid ) < 1) {
			JError::raiseError ( 500, JText::_('COM_REDSHOP_SELECT_AN_ITEM_TO_UNPUBLISH' ) );
		}
		
		$model = $this->getModel ( 'textlibrary_detail' );
		if (! $model->publish ( $cid, 0 )) {
			echo "<script> alert('" . $model->getError ( true ) . "'); window.history.go(-1); </script>\n";
		}
		$msg = JText::_('COM_REDSHOP_TEXT_LIBRARY_DETAIL_UNPUBLISHED_SUCCESSFULLY' );
		$this->setRedirect ( 'index.php?option='.$option.'&view=textlibrary',$msg );
	}
	function cancel() {
		
		$option = JRequest::getVar('option','','request','string');
		$msg = JText::_('COM_REDSHOP_TEXT_LIBRARY_DETAIL_EDITING_CANCELLED' );
		$this->setRedirect ( 'index.php?option='.$option.'&view=textlibrary',$msg );
	}
	function copy(){
		
		$option = JRequest::getVar('option','','request','string');
		
		$cid = JRequest::getVar ( 'cid', array (0 ), 'post', 'array' );
		
		$model = $this->getModel ( 'textlibrary_detail' );
				
		if ($model->copy($cid)) {
			
			$msg = JText::_('COM_REDSHOP_TEXT_LIBRARY_DETAIL_COPIED' );
		
		} else {
			
			$msg = JText::_('COM_REDSHOP_ERROR_COPYING_TEXTLIBRARY_DETAIL' );
		}
		
		$this->setRedirect ( 'index.php?option=' .$option . '&view=textlibrary', $msg );
	}

}
