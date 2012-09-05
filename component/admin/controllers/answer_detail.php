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

class answer_detailController extends JController 
{
	function __construct($default = array()) 
	{
		parent::__construct ( $default );
		$this->registerTask ( 'add', 'edit' );
	}
	function edit() {
		JRequest::setVar ( 'view', 'answer_detail' );
		JRequest::setVar ( 'layout', 'default' );
		JRequest::setVar ( 'hidemainmenu', 1 );
		parent::display ();
	
	}
	function save($send=0) 
	{
		$post = JRequest::get ( 'post' );
		$question = JRequest::getVar( 'question', '', 'post', 'string', JREQUEST_ALLOWRAW );
		$post["question"]=$question;
		$option = JRequest::getVar('option','','request','string');
		$cid = JRequest::getVar ( 'cid', array (0 ), 'post', 'array' );
		
		$post['question_id'] = $cid [0];
		$parent_id = JRequest::getVar('parent_id');
		$model = $this->getModel ( 'answer_detail' );
		
		if($post['question_id']==0)
		{
			$post['question_date'] = time();
		}
		$row = $model->store ( $post );
		if ($row) 
		{
			$msg = JText::_ ( 'ANSWER_DETAIL_SAVED' );
		} else {
			$msg = JText::_ ( 'ERROR_SAVING_ANSWER_DETAIL' );
		}
		if($send==1)
		{
			$model->sendMailForAskQuestion($row->question_id);
		}
		$this->setRedirect ( 'index.php?option='.$option.'&view=answer&parent_id='.$parent_id, $msg );
	}
	
	function send() 
	{
		$this->save(1);
	}
	
	function remove() 
	{
		$parent_id = JRequest::getVar('parent_id');
		$option = JRequest::getVar('option','','request','string');
		$cid = JRequest::getVar ( 'cid', array (0 ), 'post', 'array' );
		
		if (! is_array ( $cid ) || count ( $cid ) < 1) {
			JError::raiseError ( 500, JText::_ ( 'SELECT_AN_ITEM_TO_DELETE' ) );
		}
		
		$model = $this->getModel ( 'answer_detail' );
		if (! $model->delete ( $cid )) {
			echo "<script> alert('" . $model->getError ( true ) . "'); window.history.go(-1); </script>\n";
		}
		$msg = JText::_ ( 'ANSWER_DETAIL_DELETED_SUCCESSFULLY' );
		$this->setRedirect ( 'index.php?option='.$option.'&view=answer&parent_id='.$parent_id,$msg );
	}
	function cancel() 
	{
		$parent_id = JRequest::getVar('parent_id');
		$option = JRequest::getVar('option','','request','string');
		$msg = JText::_ ( 'ANSWER_DETAIL_EDITING_CANCELLED' );
		$this->setRedirect ( 'index.php?option='.$option.'&view=answer&parent_id='.$parent_id,$msg );
	}
	function publish() {
		
		$option = JRequest::getVar ('option');
		$parent_id = JRequest::getVar('parent_id');
		$cid = JRequest::getVar ( 'cid', array (0 ), 'post', 'array' );
		
		if (! is_array ( $cid ) || count ( $cid ) < 1) {
			JError::raiseError ( 500, JText::_ ( 'SELECT_AN_ITEM_TO_PUBLISH' ) );
		}
		
		$model = $this->getModel ( 'answer_detail' );
		if (! $model->publish ( $cid, 1 )) {
			echo "<script> alert('" . $model->getError ( true ) . "'); window.history.go(-1); </script>\n";
		}
		$msg = JText::_ ( 'ANSWER_DETAIL_PUBLISHED_SUCCESSFULLY' );
		$this->setRedirect ( 'index.php?option='.$option.'&view=answer&parent_id='.$parent_id,$msg );
	}
	function unpublish() {
		
		$option = JRequest::getVar ('option');
		$parent_id = JRequest::getVar('parent_id');
		$cid = JRequest::getVar ( 'cid', array (0 ), 'post', 'array' );
		
		if (! is_array ( $cid ) || count ( $cid ) < 1) {
			JError::raiseError ( 500, JText::_ ( 'SELECT_AN_ITEM_TO_UNPUBLISH' ) );
		}
		
		$model = $this->getModel ( 'answer_detail' );
		if (! $model->publish ( $cid, 0 )) {
			echo "<script> alert('" . $model->getError ( true ) . "'); window.history.go(-1); </script>\n";
		}
		$msg = JText::_ ( 'ANSWER_DETAIL_UNPUBLISHED_SUCCESSFULLY' );
		$this->setRedirect ( 'index.php?option='.$option.'&view=answer&parent_id='.$parent_id,$msg );
	}
	/**
	 * logic for orderup
	 *
	 * @access public
	 * @return void
	 */
	function orderup()
	{
	    $parent_id = JRequest::getVar('parent_id');
	    $option = JRequest::getVar('option');
		$model = $this->getModel('answer_detail');
 		$model->orderup();
		$msg = JText::_( 'NEW_ORDERING_SAVED' );
		$this->setRedirect ( 'index.php?option='.$option.'&view=answer&parent_id='.$parent_id,$msg );
	}

	/**
	 * logic for orderdown 
	 *
	 * @access public
	 * @return void
	 */
	function orderdown()
	{
		$parent_id = JRequest::getVar('parent_id');
		$option = JRequest::getVar('option');
		$model = $this->getModel('answer_detail');
		$model->orderdown();
		$msg = JText::_( 'NEW_ORDERING_SAVED' );
		$this->setRedirect ( 'index.php?option='.$option.'&view=answer&parent_id='.$parent_id,$msg );
	}
	
	/**
	 * logic for save an order
	 *
	 * @access public
	 * @return void
	 */
	function saveorder()
	{
		$parent_id = JRequest::getVar('parent_id');
		$option = JRequest::getVar('option');
		$cid 	= JRequest::getVar( 'cid', array(), 'post', 'array' );
		$order 	= JRequest::getVar( 'order', array(), 'post', 'array' );

		JArrayHelper::toInteger($cid);
		JArrayHelper::toInteger($order);
		$model = $this->getModel('answer_detail');
		$model->saveorder($cid, $order);

		$msg = JText::_( 'ORDERING_SAVED' );
		$this->setRedirect ( 'index.php?option='.$option.'&view=answer&parent_id='.$parent_id,$msg );
	}
}?>