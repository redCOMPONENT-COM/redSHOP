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

class voucher_detailController extends JController {
	function __construct($default = array()) {
		parent::__construct ( $default );
		$this->registerTask ( 'add', 'edit' );
	}
	function edit()
	{
		JRequest::setVar ( 'view', 'voucher_detail' );
		JRequest::setVar ( 'layout', 'default' );
		JRequest::setVar ( 'hidemainmenu', 1 );

		$model = $this->getModel ( 'voucher_detail' );
		parent::display ();
	}
	function apply()
	{
		$this->save(1);
	}
	function save($apply=0)
	{
		global $mainframe;
		$post 				= JRequest::get ( 'post' );
		$option 			= JRequest::getVar('option','','request','string');
		$cid 				= JRequest::getVar ( 'cid', array (0), 'post', 'array' );
		$post['start_date'] = strtotime($post['start_date']);

		if($post ['end_date'])
			$post ['end_date'] = strtotime($post ['end_date'])+(23*59*59);

		$post ['voucher_id'] = $cid[0];
		$model = $this->getModel ( 'voucher_detail' );
		if($post['old_voucher_code'] != $post['voucher_code'])
		{
			$code = $model->checkduplicate($post['voucher_code']);
			if($code)
			{
				$msg = JText::_ ( 'CODE_IS_ALREADY_IN_USE' );
				$mainframe->Redirect( 'index.php?option=' . $option . '&view=voucher_detail&task=edit&cid='.$post ['voucher_id'], $msg );
			}
		}

		if ($row=$model->store ( $post ))
		{
			$msg = JText::_ ( 'VOUCHER_DETAIL_SAVED' );

		}
		else
		{
			$msg = JText::_ ( 'ERROR_SAVING_VOUCHER_DETAIL' );
		}

		if($apply == 1){
			$this->setRedirect ( 'index.php?option=' . $option . '&view=voucher_detail&task=edit&cid[]='.$row->voucher_id, $msg );
		}else{
			$this->setRedirect ( 'index.php?option=' . $option . '&view=voucher', $msg );
		}

	}
	function remove() {

		$option = JRequest::getVar('option','','request','string');

		$cid = JRequest::getVar ( 'cid', array (0), 'post', 'array' );

		if (! is_array ( $cid ) || count ( $cid ) < 1) {
			JError::raiseError ( 500, JText::_ ( 'SELECT AN ITEM TO DELETE' ) );
		}

		$model = $this->getModel ( 'voucher_detail' );
		if (! $model->delete ( $cid )) {
			echo "<script> alert('" . $model->getError ( true ) . "'); window.history.go(-1); </script>\n";
		}
		$msg = JText::_ ( 'VOUCHER_DETAIL_DELETED_SUCCESSFULLY' );
		$this->setRedirect ( 'index.php?option='.$option.'&view=voucher',$msg );
	}
	function publish() {

		$option = JRequest::getVar('option','','request','string');

		$cid = JRequest::getVar ( 'cid', array (0 ), 'post', 'array' );

		if (! is_array ( $cid ) || count ( $cid ) < 1) {
			JError::raiseError ( 500, JText::_ ( 'SELECT AN ITEM TO PUBLISH' ) );
		}

		$model = $this->getModel ( 'voucher_detail' );
		if (! $model->publish ( $cid, 1 )) {
			echo "<script> alert('" . $model->getError ( true ) . "'); window.history.go(-1); </script>\n";
		}
		$msg = JText::_ ( 'VOUCHER_DETAIL_PUBLISHED_SUCCESSFULLY' );
		$this->setRedirect ( 'index.php?option='.$option.'&view=voucher',$msg );
	}
	function unpublish() {

		$option = JRequest::getVar('option','','request','string');

		$cid = JRequest::getVar ( 'cid', array (0 ), 'post', 'array' );

		if (! is_array ( $cid ) || count ( $cid ) < 1) {
			JError::raiseError ( 500, JText::_ ( 'SELECT AN ITEM TO UNPUBLISH' ) );
		}

		$model = $this->getModel ( 'voucher_detail' );
		if (! $model->publish ( $cid, 0 )) {
			echo "<script> alert('" . $model->getError ( true ) . "'); window.history.go(-1); </script>\n";
		}
		$msg = JText::_ ( 'VOUCHER_DETAIL_UNPUBLISHED_SUCCESSFULLY' );
		$this->setRedirect ( 'index.php?option='.$option.'&view=voucher',$msg );
	}
	function cancel() {

		$option = JRequest::getVar('option','','request','string');
		$msg = JText::_ ( 'VOUCHER_DETAIL_EDITING_CANCELLED' );
		$this->setRedirect ( 'index.php?option='.$option.'&view=voucher',$msg );
	}

}