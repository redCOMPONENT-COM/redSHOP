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
require_once( JPATH_COMPONENT_ADMINISTRATOR.DS.'helpers'.DS.'mail.php' );

jimport ( 'joomla.application.component.controller' );

class question_detailController extends JController
{
	function __construct($default = array())
	{
		parent::__construct ( $default );
		$this->registerTask ( 'add', 'edit' );
	}
	function edit() {
		JRequest::setVar ( 'view', 'question_detail' );
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
		$model = $this->getModel ( 'question_detail' );

		if($post['question_id']==0)
		{
			$post['question_date'] = time();
			$post['parent_id'] = 0;
		}
		$row = $model->store ( $post );

		if ($row)
		{
			$msg = JText::_('COM_REDSHOP_QUESTION_DETAIL_SAVED' );
		} else {
			$msg = JText::_('COM_REDSHOP_ERROR_SAVING_QUESTION_DETAIL' );
		}


		if($send==1)
		{
			$model->sendMailForAskQuestion($row->question_id);
		}

		$this->setRedirect ( 'index.php?option='.$option.'&view=question', $msg );
	}

	function send()
	{
		$this->save(1);
	}


	function remove()
	{
		$option = JRequest::getVar('option','','request','string');
		$cid = JRequest::getVar ( 'cid', array (0 ), 'post', 'array' );

		if (! is_array ( $cid ) || count ( $cid ) < 1) {
			JError::raiseError ( 500, JText::_('COM_REDSHOP_SELECT_AN_ITEM_TO_DELETE' ) );
		}

		$model = $this->getModel ( 'question_detail' );
		if (! $model->delete ( $cid )) {
			echo "<script> alert('" . $model->getError ( true ) . "'); window.history.go(-1); </script>\n";
		}
		$msg = JText::_('COM_REDSHOP_QUESTION_DETAIL_DELETED_SUCCESSFULLY' );
		$this->setRedirect ( 'index.php?option='.$option.'&view=question',$msg );
	}

	function removeanswer()
	{
		$option = JRequest::getVar('option','','request','string');
		$cid = JRequest::getVar ( 'aid', array (0 ), 'post', 'array' );
		$qid = JRequest::getVar ( 'cid', array (0 ), 'post', 'array' );

		if (! is_array ( $cid ) || count ( $cid ) < 1) {
			JError::raiseError ( 500, JText::_('COM_REDSHOP_SELECT_AN_ITEM_TO_DELETE' ) );
		}

		$model = $this->getModel ( 'question_detail' );
		if (! $model->delete ( $cid )) {
			echo "<script> alert('" . $model->getError ( true ) . "'); window.history.go(-1); </script>\n";
		}
		$msg = JText::_('COM_REDSHOP_QUESTION_DETAIL_DELETED_SUCCESSFULLY' );
		$this->setRedirect ( 'index.php?option='.$option.'&view=question_detail&task=edit&cid[]='.$qid[0],$msg );
	}

	function sendanswer(){
		$option = JRequest::getVar('option','','request','string');
		$cid = JRequest::getVar ( 'aid', array (0 ), 'post', 'array' );
		$qid = JRequest::getVar ( 'cid', array (0 ), 'post', 'array' );
		for( $i=0; $i < count($cid); $i++ )
		{
			$redshopMail = new redshopMail();
			$rs = $redshopMail->sendAskQuestionMail($cid[$i]);
		}
		$msg = JText::_('COM_REDSHOP_ANSWER_MAIL_SENT' );
		$this->setRedirect ( 'index.php?option='.$option.'&view=question_detail&task=edit&cid[]='.$qid[0],$msg );
	}

	function cancel()
	{
		$option = JRequest::getVar('option','','request','string');
		$msg = JText::_('COM_REDSHOP_QUESTION_DETAIL_EDITING_CANCELLED' );
		$this->setRedirect ( 'index.php?option='.$option.'&view=question',$msg );
	}
	function publish() {

		$option = JRequest::getVar ('option');

		$cid = JRequest::getVar ( 'cid', array (0 ), 'post', 'array' );

		if (! is_array ( $cid ) || count ( $cid ) < 1) {
			JError::raiseError ( 500, JText::_('COM_REDSHOP_SELECT_AN_ITEM_TO_PUBLISH' ) );
		}

		$model = $this->getModel ( 'question_detail' );
		if (! $model->publish ( $cid, 1 )) {
			echo "<script> alert('" . $model->getError ( true ) . "'); window.history.go(-1); </script>\n";
		}
		$msg = JText::_('COM_REDSHOP_QUESTION_DETAIL_PUBLISHED_SUCCESSFULLY' );
		$this->setRedirect ( 'index.php?option='.$option.'&view=question',$msg );
	}
	function unpublish() {

		$option = JRequest::getVar ('option');

		$cid = JRequest::getVar ( 'cid', array (0 ), 'post', 'array' );

		if (! is_array ( $cid ) || count ( $cid ) < 1) {
			JError::raiseError ( 500, JText::_('COM_REDSHOP_SELECT_AN_ITEM_TO_UNPUBLISH' ) );
		}

		$model = $this->getModel ( 'question_detail' );
		if (! $model->publish ( $cid, 0 )) {
			echo "<script> alert('" . $model->getError ( true ) . "'); window.history.go(-1); </script>\n";
		}
		$msg = JText::_('COM_REDSHOP_QUESTION_DETAIL_UNPUBLISHED_SUCCESSFULLY' );
		$this->setRedirect ( 'index.php?option='.$option.'&view=question',$msg );
	}
	/**
	 * logic for orderup
	 *
	 * @access public
	 * @return void
	 */
	function orderup()
	{
	    $option = JRequest::getVar('option');
		$model = $this->getModel('question_detail');
 		$model->orderup();
		$msg = JText::_('COM_REDSHOP_NEW_ORDERING_SAVED' );
		$this->setRedirect ( 'index.php?option='.$option.'&view=question',$msg );
	}

	/**
	 * logic for orderdown
	 *
	 * @access public
	 * @return void
	 */
	function orderdown()
	{
		$option = JRequest::getVar('option');
		$model = $this->getModel('question_detail');
		$model->orderdown();
		$msg = JText::_('COM_REDSHOP_NEW_ORDERING_SAVED' );
		$this->setRedirect ( 'index.php?option='.$option.'&view=question',$msg );
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
		$model = $this->getModel('question_detail');
		$model->saveorder($cid, $order);

		$msg = JText::_('COM_REDSHOP_NEW_ORDERING_SAVED' );
		$this->setRedirect ( 'index.php?option='.$option.'&view=question',$msg );
	}


}?>